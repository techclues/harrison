import test from 'node:test';
import assert from 'node:assert/strict';
import { readFileSync, existsSync, readdirSync, statSync } from 'node:fs';
import { join, dirname } from 'node:path';
import { fileURLToPath } from 'node:url';
import { spawnSync } from 'node:child_process';

const root = dirname(dirname(fileURLToPath(import.meta.url)));
const read = (...p) => readFileSync(join(root, ...p), 'utf8');
const mainPages = [
  'index.php', 'services.php', 'who-we-help.php', 'about.php', 'testimonials.php',
  'privacy-cookies.php', 'news.php', 'contact.php', 'appointment.php'
];
const servicePages = [
  'business-tax-advice', 'business-rates', 'accounting-services',
  'corporation-tax', 'bookkeeping-services', 'company-secretarial-services',
  'confirmation-statements', 'vat', 'payroll', 'pension-auto-enrolment',
  'personal-tax', 'construction-industry-scheme', 'hmrc-correspondence'
];
const audiencePages = [
  'start-ups', 'small-businesses', 'established-businesses', 'directors', 'sole-traders',
  'contractors', 'freelancers', 'landlords', 'health-workers', 'employed-individuals'
];
const audienceLabels = [
  'Start-ups', 'Small Businesses', 'Established Businesses', 'Directors', 'Sole Traders',
  'Contractors', 'Freelancers', 'Landlords', 'Health Workers', 'Employed Individuals'
];

const php = spawnSync('php', ['-v']);
const hasPhp = php.status === 0;
const phpJson = code => JSON.parse(spawnSync('php', ['-r', code], { cwd: root, encoding: 'utf8' }).stdout);
const servicesData = () => phpJson("echo json_encode(require 'data/services.php');");
const audiencesData = () => phpJson("echo json_encode(require 'data/audiences.php');");

// Every PHP/JS/CSS source file the site ships (no tests, tools or vendor assets).
const walk = (dir, out = []) => {
  for (const name of readdirSync(dir)) {
    const full = join(dir, name);
    if (['tests', 'tools', 'node_modules', '.git'].includes(name)) continue;
    statSync(full).isDirectory() ? walk(full, out) : out.push(full);
  }
  return out;
};
const sources = walk(root).filter(f => /\.(php|js|css)$/.test(f));

// Page content blocks: ['p', text] | ['h', heading] | ['ul', [items]]; images must exist.
const assertBody = (slug, rec) => {
  assert.ok(rec.body.length >= 1, `${slug} needs body content`);
  for (const [kind, value] of rec.body) {
    assert.ok(['p', 'h', 'ul'].includes(kind), `${slug} bad block ${kind}`);
    assert.ok(kind === 'ul' ? value.length && value.every(v => v.length > 1) : value.length > 1, `${slug} empty ${kind}`);
  }
  if (rec.image) assert.ok(existsSync(join(root, 'assets', 'images', 'pages', rec.image)), `${slug} missing image ${rec.image}`);
};

test('all 32 routes (the previous site\'s 29 pages plus two overviews and Home) have entry files', () => {
  for (const file of mainPages) assert.ok(existsSync(join(root, file)), `Missing ${file}`);
  for (const slug of servicePages) assert.ok(existsSync(join(root, 'services', `${slug}.php`)), `Missing service: ${slug}`);
  for (const slug of audiencePages) assert.ok(existsSync(join(root, 'who-we-help', `${slug}.php`)), `Missing audience: ${slug}`);
  assert.equal(mainPages.length + servicePages.length + audiencePages.length, 32);
});

test('service entry points only set their own slug and delegate to the shared template', () => {
  for (const slug of servicePages) {
    const src = read('services', `${slug}.php`);
    assert.match(src, new RegExp(`\\$serviceSlug = '${slug}'`), `${slug} sets wrong slug`);
    assert.match(src, /includes\/service-page\.php/);
  }
});

test('audience entry points only set their own slug and delegate to the shared template', () => {
  for (const slug of audiencePages) {
    const src = read('who-we-help', `${slug}.php`);
    assert.match(src, new RegExp(`\\$detailSlug = '${slug}'`), `${slug} sets wrong slug`);
    assert.match(src, /\$detailKind = 'audience'/);
    assert.match(src, /includes\/detail-page\.php/);
  }
});

test('canonical service data has 13 ordered, unique, complete records', { skip: !hasPhp }, () => {
  const data = servicesData();
  assert.deepEqual(Object.keys(data), servicePages);
  const titles = new Set(), leads = new Set();
  for (const [slug, s] of Object.entries(data)) {
    for (const key of ['title', 'summary', 'description', 'lead']) assert.ok(s[key]?.length >= 3, `${slug} missing ${key}`);
    assert.ok(['advice', 'records', 'tax', 'company', 'people'].includes(s.group), `${slug} bad group`);
    assertBody(slug, s);
    assert.ok(s.related.length >= 2, `${slug} needs related`);
    for (const r of s.related) assert.ok(servicePages.includes(r) && r !== slug, `${slug} bad related ${r}`);
    titles.add(s.title); leads.add(s.lead);
  }
  assert.equal(titles.size, 13);
  assert.equal(leads.size, 13);
});

test('audience data has the ten audiences, each with page content, linking only to real services', { skip: !hasPhp }, () => {
  const raw = audiencesData();
  assert.deepEqual(Object.keys(raw), audiencePages);
  assert.deepEqual(Object.values(raw).map(a => a.title), audienceLabels);
  for (const [slug, a] of Object.entries(raw)) {
    for (const key of ['summary', 'text', 'description', 'lead']) assert.ok(a[key]?.length >= 3, `${slug} missing ${key}`);
    assertBody(slug, a);
    for (const s of a.services) assert.ok(servicePages.includes(s), `${a.title} links to unknown ${s}`);
  }
});

test('navigation matches the previous site: Home, Services mega-menu, About menu, News, Contact and a Let’s talk button', () => {
  const header = read('includes', 'header.php');
  for (const name of ['Home', 'Services', 'About', 'News', 'Contact', 'Let’s talk']) {
    assert.ok(header.includes(`>${name}</a>`), `Missing menu item: ${name}`);
  }
  for (const title of ['Who We Help', 'What We Provide']) assert.ok(header.includes(`>${title}</a></p>`), `Mega-menu column missing: ${title}`);
  assert.match(header, /foreach \(services\(\)/, 'menus must be driven by the canonical service data');
  assert.match(header, /foreach \(audiences\(\)/, 'menus must be driven by the audience data');
  assert.match(header, /foreach \(ABOUT_PAGES/);
  assert.match(read('includes', 'site.php'), /'about\.php' => 'Who We Are',\s*'testimonials\.php' => 'Testimonials',\s*'privacy-cookies\.php' => 'Privacy & Cookies Policy'/);
  assert.ok(!header.includes('theme-toggle'), 'Light-only header must not show theme toggle');
});

test('menus expose accessible expanded/controls state and separate section links', () => {
  const header = read('includes', 'header.php');
  for (const id of ['desktop-services-menu', 'desktop-about-menu', 'mobile-services-menu', 'mobile-about-menu', 'mobile-navigation']) {
    assert.match(header, new RegExp(`aria-controls="${id}"`), `missing control for ${id}`);
    assert.match(header, new RegExp(`id="${id}"`), `missing panel ${id}`);
  }
  assert.equal((header.match(/aria-expanded="false"/g) || []).length, 5);
  assert.match(header, /<a href="<\?= h\(\$basePath\) \?>services\.php"[^>]*>Services<\/a>\s*<button/);
  assert.match(header, /<a href="<\?= h\(\$basePath\) \?>about\.php"[^>]*>About<\/a>\s*<button/);
});

test('layout wires stylesheets, menu script and optional motion stack', () => {
  const header = read('includes', 'header.php');
  const footer = read('includes', 'footer.php');
  for (const css of ['styles', 'skeleton', 'pages']) assert.match(header, new RegExp(`assets/css/${css}\\.css`));
  assert.ok(header.indexOf('pages.css') > header.indexOf('skeleton.css'), 'pages.css loads after skeleton.css');
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

test('no old brand name, old email domain or template filler survives the rebrand', () => {
  for (const file of sources) {
    const src = readFileSync(file, 'utf8');
    assert.ok(!/\bred\s*(&\s*co\s*)?account|redaccount|Cube Accountants|Finaxo/i.test(src), `${file} references the old brand`);
    // Demo blog posts and the About-page demo reviews from the previous site's template must never be published.
    assert.ok(!/creative agency|Defer, Delegate or Delete/i.test(src), `${file} contains template filler`);
    // The demo testimonials may appear only on the testimonials page (see the next test).
    if (!file.endsWith('testimonials.php')) assert.ok(!/Anne Smith|Felica Queen|Michael Bean|John Doe|Sara Lisbon|lab equipment/i.test(src), `${file} contains demo reviews`);
  }
});

test('testimonials carried over from the old site stay flagged in the launch checklist until replaced', () => {
  const src = read('testimonials.php');
  for (const name of ['Michael Bean', 'Anne Smith', 'Felica Queen', 'John Doe', 'Sara Lisbon']) assert.ok(src.includes(name), `missing review by ${name}`);
  assert.ok(!/★|5 stars/.test(src), 'no invented ratings');
  const readme = read('README.md');
  assert.match(readme, /## Content to confirm before launch/);
  assert.match(readme, /### Testimonials[\s\S]*Replace them with genuine reviews/);
  assert.match(readme, /### News/);
});

test('no draft-note boxes are rendered anywhere', () => {
  for (const file of sources.filter(f => f.endsWith('.php'))) {
    assert.ok(!/class="draft-note"/.test(readFileSync(file, 'utf8')), `${file} renders a draft note`);
  }
});

test('outdated facts from the previous site are corrected', { skip: !hasPhp }, () => {
  const strip = data => Object.values(data).map(({ review, ...rest }) => rest);
  const text = JSON.stringify([strip(servicesData()), strip(audiencesData())]);
  for (const stale of ['£85,000', 'in excess of £50,000', 'P35', 'P14', 'P9D', 'persecution', 'introduction of MTD', 'annual return.']) {
    assert.ok(!text.includes(stale), `stale fact still present: ${stale}`);
  }
});

test('who we help overview renders every audience and links to its page and to real services', () => {
  const src = read('who-we-help.php');
  assert.match(src, /foreach \(audiences\(\)/);
  assert.match(src, /audience_url\(\$id\)/);
  assert.match(src, /service_url\(\$slug\)/);
});

test('contact form has the specified fields and labels, posts to send.php, and holds no secrets', () => {
  const src = read('contact.php');
  for (const name of ['name', 'email', 'phone', 'service', 'message']) assert.match(src, new RegExp(`name="${name}"`));
  for (const id of ['contact-name', 'contact-email', 'contact-phone', 'contact-service', 'contact-message']) {
    assert.match(src, new RegExp(`<label for="${id}"`), `label for ${id}`);
  }
  assert.match(src, /<form[^>]*method="post"[^>]*action="send\.php"[^>]*data-enquiry-form/);
  assert.match(src, /form-fields\.php/, 'shared token, honeypot, consent and submit');
  assert.ok(!/mail\(|\$_POST|\$_REQUEST|password/i.test(src), 'pages only render the form; send.php handles it');
  assert.ok(!/Preview only/.test(src), 'the form is no longer a preview');
});

test('the firm email is info@harrison.co.uk everywhere it is shown', () => {
  assert.match(read('includes', 'site.php'), /'email' => 'info@harrison\.co\.uk'/);
  assert.match(read('includes', 'contact-details.php'), /mailto:<\?= h\(CONTACT\['email'\]\)/);
  assert.match(read('includes', 'footer.php'), /mailto:<\?= h\(CONTACT\['email'\]\)/);
  for (const file of sources) assert.ok(!/@redaccountants|EMAIL_PLACEHOLDER/i.test(readFileSync(file, 'utf8')), `${file} has an old or placeholder email`);
});

test('appointment page has the previous site\'s fields as an accessible form posting to send.php', () => {
  const src = read('appointment.php');
  for (const name of ['first_name', 'last_name', 'email', 'phone', 'date', 'time', 'message']) assert.match(src, new RegExp(`name="${name}"`));
  for (const id of ['appt-first', 'appt-last', 'appt-email', 'appt-phone', 'appt-date', 'appt-time', 'appt-message']) {
    assert.match(src, new RegExp(`<label for="${id}"`), `label for ${id}`);
  }
  assert.match(src, /<form[^>]*method="post"[^>]*action="send\.php"/);
  assert.match(src, /\$formType = 'appointment'/);
  assert.ok(!/\$_POST|mail\(/.test(src));
});

test('form plumbing: shared partial has token, honeypot and consent; secrets stay out of git and the web', () => {
  const part = read('includes', 'form-fields.php');
  assert.match(part, /name="ts" value="<\?= h\(form_token\(\)\) \?>"/);
  assert.match(part, /name="website"[^>]*tabindex="-1"/);
  assert.match(part, /name="consent"[^>]*required/);
  assert.match(part, /privacy-cookies\.php/, 'consent line links to the privacy policy');
  const ignore = read('.gitignore');
  assert.match(ignore, /config\/mail\.local\.php/);
  assert.match(ignore, /storage\/\*/);
  for (const dir of ['config', 'storage', 'includes']) assert.match(read(dir, '.htaccess'), /Require all denied/, `${dir} must not be web-readable`);
  const cfg = read('config', 'mail.php');
  assert.match(cfg, /'to' => 'techcluesltd@gmail\.com'/);
  assert.match(cfg, /'password' => ''/, 'no SMTP password in committed config');
  assert.match(read('assets', 'js', 'site.js'), /data-enquiry-form/);
});

test('each service and audience page renders its own title, heading and breadcrumb (no shared-variable clobbering)', { skip: !hasPhp }, () => {
  const data = { ...servicesData(), ...audiencesData() };
  const pages = [...servicePages.map(s => ['services', s]), ...audiencePages.map(s => ['who-we-help', s])];
  for (const [dir, slug] of pages) {
    const file = join(root, dir, `${slug}.php`).replace(/\\/g, '/');
    const r = spawnSync('php', ['-r', `chdir(dirname('${file}')); include '${file}';`], { cwd: root, encoding: 'utf8' });
    const title = data[slug].title.replace(/&/g, '&amp;');
    assert.ok(r.stdout.includes(`<h1 id="page-heading">${title}</h1>`), `${slug}: wrong h1`);
    assert.ok(r.stdout.includes(`<title>${title} |`), `${slug}: wrong <title>`);
    assert.ok(r.stdout.includes(`aria-current="page">${title}</li>`), `${slug}: wrong breadcrumb`);
  }
});

test('every CSS colour is a brand colour, white, or a 5%-step tint of one', { skip: !hasPhp }, () => {
  const r = spawnSync('python', ['tools/brand-colours.py', '--check'], { cwd: root, encoding: 'utf8' });
  assert.equal(r.status, 0, r.stdout + r.stderr);
});

test('tokens hold the exact Brand Guide palette, type sizes and logo clear space', () => {
  const t = read('assets', 'css', 'tokens.css');
  for (const hex of ['#222d65', '#a08359', '#d7bb72', '#e5e5e5', '#242e3d', '#070049']) assert.ok(t.includes(hex), `missing ${hex}`);
  for (const [k, v] of [['--fs-h1', '34pt'], ['--fs-h2', '22pt'], ['--fs-h3', '18pt'], ['--fs-body', '14pt'], ['--fs-desc', '12pt'], ['--logo-clear', '50px']]) {
    assert.match(t, new RegExp(`${k}:${v}`), `${k} must be ${v}`);
  }
  const header = read('includes', 'header.php');
  assert.ok(header.indexOf('tokens.css') < header.indexOf('styles.css'), 'tokens load first');
  assert.ok(header.indexOf('brand.css') > header.indexOf('pages.css'), 'brand.css loads last');
});

test('3D H mark loads lazily from a pinned three.js, with an SVG fallback and reduced-motion support', () => {
  const home = read('index.php');
  assert.match(home, /data-h-mark="assets\/images\/h-mark\.json"/);
  assert.match(home, /class="h-mark-fallback" src="assets\/images\/h-mark\.svg"/);
  assert.match(read('includes', 'header.php'), /"three":"https:\/\/cdn\.jsdelivr\.net\/npm\/three@\d+\.\d+\.\d+\/build\/three\.module\.min\.js"/, 'three.js must be pinned to an exact version, in one shared import map');
  assert.ok(!/importmap/.test(home), 'pages must not declare their own import map');
  for (const f of ['h-mark.json', 'h-mark.svg']) assert.ok(existsSync(join(root, 'assets', 'images', f)), `missing ${f}`);
  const js = read('assets', 'js', 'h-mark-3d.js');
  assert.match(js, /IntersectionObserver/, 'three.js must load only near the viewport');
  assert.match(js, /prefers-reduced-motion/);
  assert.match(js, /getContext\('webgl2'\) \|\| probe\.getContext\('webgl'\)/, 'must check WebGL before replacing the fallback');
  assert.ok(!/THREE\.Clock/.test(js), 'THREE.Clock is deprecated');
});

test('particle H (Who We Are) and gold ribbon (Services) are progressive, lazy and reduced-motion aware', () => {
  const about = read('about.php'), services = read('services.php');
  assert.match(about, /data-h-particles="assets\/images\/h-mark\.json"/);
  assert.match(about, /about-hero-particles[^>]*>\s*<img/, 'the hero photo stays inside the stage as the fallback');
  assert.match(about, /src="assets\/js\/h-particles\.js"/);
  assert.match(services, /data-gold-ribbon data-gold-ribbon-above="\.page-hero-grid" data-gold-ribbon-below="\.service-hero-line"/);
  assert.match(services, /src="assets\/js\/gold-ribbon\.js"/);
  for (const f of ['h-particles.js', 'gold-ribbon.js']) {
    const js = read('assets', 'js', f);
    assert.match(js, /prefers-reduced-motion/, `${f} must respect reduced motion`);
    assert.match(js, /getContext\('webgl2'\) \|\| probe\.getContext\('webgl'\)/, `${f} must check WebGL first`);
    assert.match(js, /IntersectionObserver/, `${f} must pause when off-screen`);
    assert.match(js, /import\('three'\)/, `${f} must load three.js on demand`);
  }
});

test('Home carries the ribbon band at the hero seam and the Who We Are particle section above the FAQ', () => {
  const home = read('index.php');
  assert.ok(home.indexOf('data-gold-ribbon="band"') < home.indexOf('class="v-about'), 'ribbon band sits before About');
  assert.ok(home.indexOf('class="about-hero-dark home-who"') < home.indexOf('class="v-faq'), 'Who We Are sits above the FAQ');
  assert.match(home, /src="assets\/js\/gold-ribbon\.js"/);
  assert.match(home, /src="assets\/js\/h-particles\.js"/);
  assert.match(home, /<h2 id="home-who-heading">/, 'Home uses h2 so the page keeps a single h1');
});

test('news page shows sample articles (flagged once, in the draft note) with accessible in-page disclosures and no template filler', () => {
  const src = read('news.php');
  assert.ok((src.match(/'id' => '/g) || []).length >= 6, 'at least six sample articles');
  assert.ok(!/post-sample/.test(src), 'no per-article Sample badge');
  assert.match(src, /<details class="post-more">\s*<summary>/);
  assert.ok(!/draft-note/.test(src), 'no on-screen draft note');
  assert.ok(!/href="#"/.test(src), 'no dead links');
  const images = [...src.matchAll(/'image' => '([^']+)'/g)].map(m => m[1]);
  assert.equal(images.length, (src.match(/'id' => '/g) || []).length, 'every article has a photo');
  for (const img of images) assert.ok(existsSync(join(root, 'assets', 'images', 'pages', img)), `missing news photo ${img}`);
});

test('inner pages fill their title area with a photo and their last grid slot with a call-to-action', () => {
  for (const f of ['contact.php', 'appointment.php', 'news.php', 'testimonials.php', 'who-we-help.php', 'privacy-cookies.php']) {
    assert.match(read(f), /detail-hero-inner has-art[\s\S]*hero_art\(/, `${f} needs a hero photo`);
  }
  assert.match(read('includes', 'detail-page.php'), /<\?= \$heroArt \?>/, 'service and audience pages show a hero photo');
  assert.match(read('includes', 'site.php'), /function hero_art\(/);
  for (const [f, cls] of [['who-we-help.php', 'audience-cta'], ['news.php', 'post-cta'], ['testimonials.php', 'review-cta'], ['services.php', 'service-cta']]) {
    assert.ok(read(f).includes(`cta-card ${cls}`), `${f} should fill its last grid slot with a CTA card`);
  }
  assert.ok(read('contact.php').includes('contact-aside.php') && read('appointment.php').includes('contact-aside.php'));
  assert.match(read('privacy-cookies.php'), /detail-layout[\s\S]*On this page/);
  assert.match(read('about.php'), /who-top[\s\S]*who-photo/);
});

test('form states: red required marks and errors, green Sent button, status colours documented', () => {
  const tokens = read('assets', 'css', 'tokens.css');
  assert.match(tokens, /--color-error:#b3261e/);
  assert.match(tokens, /--color-success:#1b7a3d/);
  const css = read('assets', 'css', 'pages.css');
  assert.match(css, /\.field \.req,\.consent \.req\{color:var\(--color-error\)/, 'required asterisks are red before anything is submitted');
  assert.match(css, /\[aria-invalid="true"\][\s\S]*var\(--color-error\)/, 'invalid fields are red');
  assert.match(css, /button\[data-submit\]\.is-sent[\s\S]*background:var\(--color-success\)/, 'the sent button is green');
  const js = read('assets', 'js', 'site.js');
  assert.match(js, /setButton\('sent'\)/);
  assert.match(js, /showFieldError/);
  assert.match(read('includes', 'form-fields.php'), /<span class="req" aria-hidden="true">\*<\/span>/, 'the consent tick box is marked required');
  assert.match(read('DESIGN-SYSTEM.md'), /Status colours/i, 'the red/green exception is documented');
});

test('service pages have no "Talk to us" card in the sidebar (Who We Help pages keep it)', () => {
  const tpl = read('includes', 'detail-page.php');
  assert.match(tpl, /<\?php if \(!\$isService\): \?>\s*<div class="aside-card aside-contact">/, 'the card is shown for audience pages only');
});

test('site-wide scroll animations are progressive: reduced-motion aware, fail open, no hidden content without the script', () => {
  const js = read('assets', 'js', 'scroll-fx.js');
  assert.match(js, /prefers-reduced-motion: reduce/, 'respects reduced motion');
  assert.match(js, /IntersectionObserver/);
  assert.match(js, /const sweep = /, 'a safety sweep reveals anything missed');
  assert.match(js, /scrolled past without being seen/, 'skipped elements are shown, not left hidden');
  assert.ok(!/gsap|ScrollTrigger/.test(js), 'does not depend on the animation CDNs');
  assert.match(read('includes', 'footer.php'), /assets\/js\/scroll-fx\.js/);
  const header = read('includes', 'header.php');
  assert.match(header, /classList\.add\("fx-js"\)[\s\S]*setTimeout\(function\(\)\{d\.classList\.remove\("fx-js"\)\},3000\)/, 'the head pre-hide fails open after 3s');
  const css = read('assets', 'css', 'pages.css');
  assert.match(css, /\.fx-ready \.fx\{opacity:0/, 'items are only hidden once the script is running');
  assert.ok(!/(^|\n)\.fx\{[^}]*opacity:0/.test(css), 'no unconditional .fx hiding rule');
  assert.match(css, /\.scroll-progress\{/);
  assert.match(css, /@media\(prefers-reduced-motion:reduce\)/);
});

test('site stays noindex and light-only', () => {
  const header = read('includes', 'header.php');
  assert.match(header, /noindex,nofollow/);
  assert.match(header, /class="visari-mode light-mode"/);
});

test('every PHP route renders successfully and unknown slugs 404', { skip: !hasPhp }, () => {
  const render = file => spawnSync('php', ['-r', `$_SERVER['REQUEST_METHOD']='GET'; chdir(dirname('${file.replace(/\\/g, '/')}')); ob_start(); include '${file.replace(/\\/g, '/')}'; $len = strlen(ob_get_clean()); echo (int)http_response_code(), "x", $len;`], { cwd: root, encoding: 'utf8' });
  for (const page of mainPages) {
    const r = render(join(root, page));
    assert.match(r.stdout, /^(200|0)x[1-9]\d{3,}/, `${page}: ${r.stdout} ${r.stderr}`);
  }
  for (const [dir, list] of [['services', servicePages], ['who-we-help', audiencePages]]) {
    for (const slug of list) {
      const r = render(join(root, dir, `${slug}.php`));
      assert.match(r.stdout, /^(200|0)x[1-9]\d{3,}/, `${slug}: ${r.stdout} ${r.stderr}`);
    }
  }
  const bad = spawnSync('php', ['-r', `$serviceSlug='nope'; chdir('services'); ob_start(); require '../includes/service-page.php';`], { cwd: root, encoding: 'utf8' });
  assert.match(bad.stdout + bad.stderr, /Service not found/);
  const badAudience = spawnSync('php', ['-r', `$detailKind='audience'; $detailSlug='nope'; chdir('who-we-help'); ob_start(); require '../includes/detail-page.php';`], { cwd: root, encoding: 'utf8' });
  assert.match(badAudience.stdout + badAudience.stderr, /Page not found/);
});
