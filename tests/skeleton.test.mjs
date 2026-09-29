import test from 'node:test';
import assert from 'node:assert/strict';
import { readFileSync, existsSync, readdirSync, statSync } from 'node:fs';
import { join, dirname } from 'node:path';
import { fileURLToPath } from 'node:url';
import { spawnSync } from 'node:child_process';

const root = dirname(dirname(fileURLToPath(import.meta.url)));
const read = (...p) => readFileSync(join(root, ...p), 'utf8');
const mainPages = ['index.php', 'services.php', 'about.php', 'who-we-help.php', 'contact.php'];
const servicePages = [
  'business-tax-advice', 'business-rates', 'accounting-services',
  'corporation-tax', 'bookkeeping-services', 'company-secretarial-services',
  'confirmation-statements', 'vat', 'payroll', 'pension-auto-enrolment',
  'personal-tax', 'construction-industry-scheme', 'hmrc-correspondence'
];
const audienceLabels = [
  'Start-ups', 'Small Businesses', 'Established Businesses', 'Directors', 'Sole Traders',
  'Contractors', 'Freelancers', 'Landlords', 'Health Workers', 'Employed Individuals'
];

const php = spawnSync('php', ['-v']);
const hasPhp = php.status === 0;
const phpJson = code => JSON.parse(spawnSync('php', ['-r', code], { cwd: root, encoding: 'utf8' }).stdout);

// Every PHP/JS/CSS source file the site ships (no tests, tools or vendor assets).
const walk = (dir, out = []) => {
  for (const name of readdirSync(dir)) {
    const full = join(dir, name);
    if (['tests', 'tools', 'node_modules'].includes(name)) continue;
    statSync(full).isDirectory() ? walk(full, out) : out.push(full);
  }
  return out;
};
const sources = walk(root).filter(f => /\.(php|js|css)$/.test(f));

test('all 18 requested routes have physical PHP entry files', () => {
  for (const file of mainPages) assert.ok(existsSync(join(root, file)), `Missing ${file}`);
  for (const slug of servicePages) assert.ok(existsSync(join(root, 'services', `${slug}.php`)), `Missing service: ${slug}`);
});

test('service entry points only set their own slug and delegate to the shared template', () => {
  for (const slug of servicePages) {
    const src = read('services', `${slug}.php`);
    assert.match(src, new RegExp(`\\$serviceSlug = '${slug}'`), `${slug} sets wrong slug`);
    assert.match(src, /includes\/service-page\.php/);
  }
});

test('canonical service data has 13 ordered, unique, complete records', { skip: !hasPhp }, () => {
  const data = phpJson("echo json_encode(require 'data/services.php');");
  assert.deepEqual(Object.keys(data), servicePages);
  const titles = new Set(), intros = new Set();
  for (const [slug, s] of Object.entries(data)) {
    for (const key of ['title', 'summary', 'description', 'intro', 'review']) assert.ok(s[key]?.length >= 3, `${slug} missing ${key}`);
    assert.ok(['advice', 'records', 'tax', 'company', 'people'].includes(s.group), `${slug} bad group`);
    assert.ok(s.covers.length >= 3, `${slug} needs covers`);
    assert.equal(s.steps.length, 3, `${slug} needs 3 steps`);
    assert.ok(s.related.length >= 2, `${slug} needs related`);
    for (const r of s.related) assert.ok(servicePages.includes(r) && r !== slug, `${slug} bad related ${r}`);
    titles.add(s.title); intros.add(s.intro);
  }
  assert.equal(titles.size, 13);
  assert.equal(intros.size, 13);
});

test('audience data lists the ten audiences and links only to real services', { skip: !hasPhp }, () => {
  const data = Object.values(phpJson("echo json_encode(require 'data/audiences.php');"));
  assert.deepEqual(data.map(a => a.title), audienceLabels);
  for (const a of data) for (const slug of a.services) assert.ok(servicePages.includes(slug), `${a.title} links to unknown ${slug}`);
});

test('shared navigation exposes five top-level pages and one Who We Help route', () => {
  const header = read('includes', 'header.php');
  for (const name of ['Home', 'Services', 'Who We Help', 'About', 'Contact']) assert.ok(header.includes(`>${name}</a>`), `Missing menu item: ${name}`);
  assert.equal((header.match(/who-we-help\.php/g) || []).length, 2, 'Who We Help appears once per menu (desktop + mobile)');
  assert.ok(!header.includes('theme-toggle'), 'Light-only header must not show theme toggle');
  assert.match(header, /foreach \(services\(\)/, 'menus must be driven by the canonical service data');
});

test('menu exposes accessible expanded/controls state and a separate services link', () => {
  const header = read('includes', 'header.php');
  assert.match(header, /data-services-toggle[^>]*|aria-controls="desktop-services-menu"/);
  assert.match(header, /aria-controls="desktop-services-menu"/);
  assert.match(header, /aria-controls="mobile-services-menu"/);
  assert.match(header, /aria-controls="mobile-navigation"/);
  assert.equal((header.match(/aria-expanded="false"/g) || []).length, 3);
  assert.match(header, /<a href="<\?= h\(\$basePath\) \?>services\.php"[^>]*>Services<\/a>\s*<button/);
});

test('layout wires stylesheets, menu script and optional motion stack', () => {
  const header = read('includes', 'header.php');
  const footer = read('includes', 'footer.php');
  for (const css of ['styles', 'skeleton', 'pages']) assert.match(header, new RegExp(`assets/css/${css}\\.css`));
  assert.ok(header.indexOf('pages.css') > header.indexOf('skeleton.css'), 'pages.css must load last');
  assert.match(footer, /assets\/js\/site\.js/);
  assert.match(footer, /assets\/js\/animations\.js/);
  assert.match(footer, /gsap/i);
  assert.match(footer, /ScrollTrigger/);
  assert.match(footer, /lenis/i);
});

test('navigation script does not depend on animation libraries; animations bail out safely', () => {
  const site = read('assets', 'js', 'site.js');
  assert.ok(!/gsap|ScrollTrigger|Lenis/.test(site), 'site.js must not reference animation libraries');
  assert.match(site, /Escape/);
  const anim = read('assets', 'js', 'animations.js');
  assert.match(anim, /prefers-reduced-motion/);
  assert.match(anim, /if \(reduced \|\| !gsap \|\| !ScrollTrigger\) return/);
});

test('no stale links, dead anchors, theme controls or placeholder copy remain', () => {
  for (const file of sources.filter(f => f.endsWith('.php'))) {
    const src = readFileSync(file, 'utf8');
    assert.ok(!/href="[^"]*\.html/.test(src), `${file} links to .html`);
    assert.ok(!/href="#"/.test(src), `${file} has dead href="#"`);
    assert.ok(!/data-consultation/.test(src), `${file} has old consultation trigger`);
    assert.ok(!/to be developed|coming soon/i.test(src), `${file} has placeholder copy`);
  }
});

test('no Red Accountants branding, contact details or wording is copied', () => {
  for (const file of sources) {
    const src = readFileSync(file, 'utf8');
    assert.ok(!/red\s*account|redaccount|020 8573|Cube Accountants/i.test(src), `${file} references Red Accountants`);
  }
});

test('who we help page renders every audience from data and links to real service pages', () => {
  const src = read('who-we-help.php');
  assert.match(src, /foreach \(audiences\(\)/);
  assert.match(src, /service_url\(\$slug\)/);
});

test('contact form has the specified fields, labels and no submission path', () => {
  const src = read('contact.php');
  for (const name of ['name', 'email', 'phone', 'service', 'message']) assert.match(src, new RegExp(`name="${name}"`));
  for (const id of ['contact-name', 'contact-email', 'contact-phone', 'contact-service', 'contact-message']) {
    assert.match(src, new RegExp(`<label for="${id}"`), `label for ${id}`);
  }
  assert.ok(!/action=/.test(src), 'form must not have an action');
  assert.ok(!/type="submit"/.test(src), 'form must not have a submit button');
  assert.match(src, /Preview only/);
  assert.ok(!/mail\(|file_put_contents|\$_POST|\$_REQUEST/.test(src), 'contact page must not process submissions');
  assert.match(read('assets', 'js', 'site.js'), /preventDefault/);
  assert.ok(!/@\w+\.\w+/.test(src.replace(/@\w+\s*\(/g, '')), 'no email addresses in the contact page');
});

test('each service page renders its own title, heading and breadcrumb (no shared-variable clobbering)', { skip: !hasPhp }, () => {
  const data = phpJson("echo json_encode(require 'data/services.php');");
  for (const slug of servicePages) {
    const file = join(root, 'services', `${slug}.php`).replace(/\\/g, '/');
    const r = spawnSync('php', ['-r', `chdir(dirname('${file}')); include '${file}';`], { cwd: root, encoding: 'utf8' });
    const title = data[slug].title.replace(/&/g, '&amp;');
    assert.ok(r.stdout.includes(`<h1 id="page-heading">${title}</h1>`), `${slug}: wrong h1`);
    assert.ok(r.stdout.includes(`<title>${title} |`), `${slug}: wrong <title>`);
    assert.ok(r.stdout.includes(`aria-current="page">${title}</li>`), `${slug}: wrong breadcrumb`);
  }
});

test('site stays noindex and light-only', () => {
  const header = read('includes', 'header.php');
  assert.match(header, /noindex,nofollow/);
  assert.match(header, /class="visari-mode light-mode"/);
});

test('every PHP route renders with HTTP-level success and unknown slugs 404', { skip: !hasPhp }, () => {
  const render = file => spawnSync('php', ['-r', `$_SERVER['REQUEST_METHOD']='GET'; chdir(dirname('${file.replace(/\\/g, '/')}')); ob_start(); include '${file.replace(/\\/g, '/')}'; $len = strlen(ob_get_clean()); echo (int)http_response_code(), "x", $len;`], { cwd: root, encoding: 'utf8' });
  for (const page of mainPages) {
    const r = render(join(root, page));
    assert.match(r.stdout, /^(200|0)x[1-9]\d{3,}/, `${page}: ${r.stdout} ${r.stderr}`);
  }
  for (const slug of servicePages) {
    const r = render(join(root, 'services', `${slug}.php`));
    assert.match(r.stdout, /^(200|0)x[1-9]\d{3,}/, `${slug}: ${r.stdout} ${r.stderr}`);
  }
  const bad = spawnSync('php', ['-r', `$serviceSlug='nope'; chdir('services'); ob_start(); require '../includes/service-page.php';`], { cwd: root, encoding: 'utf8' });
  assert.match(bad.stdout + bad.stderr, /Service not found/);
});
