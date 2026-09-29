// One-time mechanical extraction of the three approved HTML page bodies.
// Re-run only when intentionally refreshing them from the supplied reference files.
import { readFileSync, writeFileSync, cpSync, mkdirSync } from 'node:fs';
import { resolve } from 'node:path';

const root = resolve(import.meta.dirname, '..');
const source = resolve(root, '..', 'upload');
const existingAssets = resolve(root, '..', 'harrison-advisory-studio', 'dist', 'assets');
mkdirSync(resolve(root, 'assets'), { recursive: true });
cpSync(existingAssets, resolve(root, 'assets'), { recursive: true, force: true });
const stylesheet = readFileSync(resolve(source, 'styles.css'), 'utf8')
  .replaceAll("url('assets/", "url('../")
  .replaceAll('url("assets/', 'url("../');
writeFileSync(resolve(root, 'assets', 'css', 'styles.css'), stylesheet);
const pages = [
  { from: 'index.html', to: 'index.php', title: 'Financial clarity, fully considered', nav: 'home' },
  { from: 'services.html', to: 'services.php', title: 'Services', nav: 'services' },
  { from: 'about.html', to: 'about.php', title: 'About Harrison', nav: 'about' },
];

for (const page of pages) {
  const html = readFileSync(resolve(source, page.from), 'utf8');
  const main = html.match(/<main id="main">([\s\S]*?)<\/main>/);
  if (!main) throw new Error(`Could not find main content in ${page.from}`);
  const content = main[1].replace(/\.html(?=(?:[#"?]|$))/g, '.php');
  const php = `<?php\ndeclare(strict_types=1);\n$pageTitle = '${page.title}';\n$currentNav = '${page.nav}';\nrequire __DIR__ . '/includes/header.php';\n?>\n${content.trim()}\n<?php require __DIR__ . '/includes/footer.php'; ?>\n`;
  writeFileSync(resolve(root, page.to), php);
}
