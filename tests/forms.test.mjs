// End-to-end tests of the enquiry forms against a real PHP server. Runtime data (rate-limit counters,
// the 'log' outbox, the signing secret) goes to a throwaway temp folder via HARRISON_STORAGE, so a
// test run never touches the project's own storage folder.
import test from 'node:test';
import assert from 'node:assert/strict';
import { spawn, spawnSync } from 'node:child_process';
import { existsSync, mkdtempSync, readdirSync, readFileSync } from 'node:fs';
import { tmpdir } from 'node:os';
import { join, dirname } from 'node:path';
import { fileURLToPath } from 'node:url';

const root = dirname(dirname(fileURLToPath(import.meta.url)));
const hasPhp = spawnSync('php', ['-v']).status === 0;
const sleep = ms => new Promise(r => setTimeout(r, ms));

test('enquiry forms: validation, abuse protection and delivery', { skip: !hasPhp, timeout: 60000 }, async (t) => {
  const storage = mkdtempSync(join(tmpdir(), 'harrison-forms-'));
  const port = 18000 + Math.floor(Math.random() * 1500);
  const base = `http://127.0.0.1:${port}`;
  const server = spawn('php', ['-S', `127.0.0.1:${port}`, '-t', root], { env: { ...process.env, HARRISON_STORAGE: storage }, stdio: 'ignore' });
  t.after(() => server.kill());
  for (let i = 0; i < 50; i++) { try { await fetch(base + '/'); break; } catch { await sleep(100); } }

  const outbox = () => (existsSync(join(storage, 'outbox')) ? readdirSync(join(storage, 'outbox')).sort() : []);
  const mail = name => readFileSync(join(storage, 'outbox', name), 'utf8');
  const post = (fields) => fetch(base + '/send.php', {
    method: 'POST', headers: { Accept: 'application/json', 'Content-Type': 'application/x-www-form-urlencoded' },
    body: new URLSearchParams(fields).toString(),
  });
  const tokenFrom = async page => (await (await fetch(`${base}/${page}`)).text()).match(/name="ts" value="([^"]+)"/)[1];

  const contactToken = await tokenFrom('contact.php');
  const appointmentToken = await tokenFrom('appointment.php');
  const good = { form: 'contact', ts: contactToken, name: 'Ann Example', email: 'ann@example.com', phone: '020 1234 5678', service: 'VAT', message: 'Hello, I would like help with VAT.', consent: 'yes' };

  await t.test('a form submitted instantly after loading is refused (bot speed)', async () => {
    const r = await post(good);
    assert.equal(r.status, 400);
    assert.equal((await r.json()).ok, false);
    assert.equal(outbox().length, 0);
  });

  await sleep(3300); // the minimum time between loading and submitting

  await t.test('a valid enquiry is delivered to techcluesltd@gmail.com with the visitor as Reply-To', async () => {
    const r = await post(good);
    assert.equal(r.status, 200);
    assert.equal((await r.json()).ok, true);
    const files = outbox();
    assert.equal(files.length, 1);
    const eml = mail(files[0]);
    assert.match(eml, /^To: techcluesltd@gmail\.com$/m);
    assert.match(eml, /^Reply-To: Ann Example <ann@example\.com>$/m);
    assert.match(eml, /^Subject: New enquiry from the Harrison website: Ann Example$/m);
    assert.match(eml, /Service of interest: VAT/);
    assert.match(eml, /Hello, I would like help with VAT\./);
  });

  await t.test('invalid input is rejected with field errors and nothing is sent', async () => {
    const r = await post({ ...good, email: 'not-an-email', consent: '', message: 'hi' });
    assert.equal(r.status, 422);
    const body = await r.json();
    assert.ok(body.errors.email && body.errors.consent && body.errors.message);
    assert.equal(outbox().length, 1);
  });

  await t.test('the honeypot field silently drops bot submissions', async () => {
    const r = await post({ ...good, website: 'http://spam.example' });
    assert.equal((await r.json()).ok, true, 'bots are told it worked');
    assert.equal(outbox().length, 1, 'but nothing is sent');
  });

  await t.test('missing and forged tokens are refused', async () => {
    for (const ts of ['', `1700000000.${'a'.repeat(64)}`]) {
      const r = await post({ ...good, ts });
      assert.equal(r.status, 400);
    }
    assert.equal(outbox().length, 1);
  });

  await t.test('line breaks in the name cannot inject extra email headers', async () => {
    const r = await post({ ...good, name: 'Evil\r\nBcc: victim@example.com', email: 'evil@example.com' });
    assert.equal(r.status, 200);
    const sent = mail(outbox().find(f => mail(f).includes('evil@example.com')));
    assert.ok(!/^Bcc:/mi.test(sent), 'no Bcc header line may be created');
    assert.equal(sent.split('\n\n')[0].split('\n').length, 4, 'headers are exactly To, From, Reply-To, Subject');
  });

  await t.test('the appointment form validates its date and time and delivers a request', async () => {
    const bad = await post({ form: 'appointment', ts: appointmentToken, first_name: 'Sam', last_name: 'Taylor', email: 'sam@example.com', phone: '07700 900123', date: '2020-01-01', time: '23:00', consent: 'yes' });
    assert.equal(bad.status, 422);
    const errs = (await bad.json()).errors;
    assert.ok(errs.date && errs.time);
    const future = new Date(Date.now() + 3 * 864e5).toISOString().slice(0, 10);
    const ok = await post({ form: 'appointment', ts: appointmentToken, first_name: 'Sam', last_name: 'Taylor', email: 'sam@example.com', phone: '07700 900123', date: future, time: '14:30', service: 'Payroll', message: 'Please call.', consent: 'yes' });
    assert.equal(ok.status, 200);
    const eml = mail(outbox().find(f => mail(f).includes('appointment request')));
    assert.match(eml, /Preferred date: \d{4}-\d{2}-\d{2}/);
    assert.match(eml, /Preferred time: 14:30/);
    assert.match(eml, /To: techcluesltd@gmail\.com/);
  });

  await t.test('visitors are rate limited after repeated attempts', async () => {
    let last;
    for (let i = 0; i < 6; i++) last = await post(good);
    assert.equal(last.status, 429);
  });

  await t.test('without JavaScript the handler redirects back with a status and GET just redirects', async () => {
    const get = await fetch(base + '/send.php', { redirect: 'manual' });
    assert.equal(get.status, 303);
    assert.match(get.headers.get('location'), /contact\.php$/);
    const r = await fetch(base + '/send.php', { method: 'POST', redirect: 'manual', headers: { 'Content-Type': 'application/x-www-form-urlencoded' }, body: new URLSearchParams(good).toString() });
    assert.equal(r.status, 303);
    assert.match(r.headers.get('location'), /contact\.php\?status=(sent|rate)#form-status/);
  });

  await t.test('the status message from the redirect is rendered on the page', async () => {
    const html = await (await fetch(`${base}/contact.php?status=sent`)).text();
    assert.match(html, /data-kind="ok">Thank you\. Your message has been sent/);
  });
});
