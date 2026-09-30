# Harrison Accountants — Design System

Source of truth: **Brand Guide.pdf** (Brand Identity Design, supplied by the client). This file records how the guide is applied to the website. Code lives in `assets/css/tokens.css` (values) and `assets/css/brand.css` (application). Change brand values in `tokens.css` only.

Stylesheet order (`includes/header.php`): `tokens.css` → `styles.css` → `skeleton.css` → `pages.css` → `brand.css`. `brand.css` loads last and enforces the brand type system.

## 1. Logo

- File: `assets/harrison-logo.png` (transparent, cropped). Original JPG kept as `harrison-logo.jpg`. Ask the designer for the original vector (SVG/AI) for print or very large uses.
- The mark is the **H icon + HARRISON wordmark + ACCOUNTANTS line**, always used together as the complete logo. Do not recolour, outline, stretch or re-typeset it. The H icon alone is used only for the favicon (`assets/favicon.png`).
- **Clear space:** keep at least **50px** around the complete logo (Guide p.05). Token: `--logo-clear`. Applied in the footer (space between logo and tagline). The header is a fixed-height bar and is the one place the full 50px cannot apply; the logo is kept at `--logo-header-h` (76px, 58px on phones) so "ACCOUNTANTS" stays legible.
- Place the logo on light surfaces (`--surface`, white) or over a photo only through the light header band. Do not place it directly on busy imagery.

## 2. Colour (Guide p.08)

| Token | Hex | Role |
|---|---|---|
| `--brand-navy` | `#222D65` | Primary. Headings, logo, key UI, active list markers |
| `--brand-gold` | `#A08359` | Accent. Rules, icons, emphasised words in headings (large text only) |
| `--brand-gold-light` | `#D7BB72` | Accent on navy/dark; primary button fill; ring highlights |
| `--brand-grey` | `#E5E5E5` | Dividers, borders, quiet fills |
| `--brand-ink` | `#242E3D` | Body text, dark neutral |
| `--brand-indigo` | `#070049` | Deepest tone: dark bands, hover-on-dark |

Derived tones (adjustments of the palette, not new hues):

| Token | Hex | Why |
|---|---|---|
| `--brand-gold-text` | `#86693F` | Gold darkened to pass WCAG AA for small text on cream. Use for small gold labels; `--brand-gold` is for large text and graphics. |
| `--brand-text-muted` | `#5D6B8A` | Secondary copy |
| `--surface` / `--surface-raised` / `--surface-sunken` | `#F7F6F1` / `#FFFFFF` / `#ECEBE5` | Page, card and alternate-section backgrounds |

Rules
- Headings navy, body ink, accents gold. Gold never carries small body text on cream (contrast ≈ 3.3:1); use `--brand-gold-text`.
- On the Home hero the copy is white and light gold over a navy scrim; headings elsewhere are navy.
- Focus rings use `--color-focus` (gold). Hover states darken or lift; they do not introduce new colours.
- Audit note: at handover about 28% of hex values in the legacy CSS were brand colours; the rest are neutrals left from the earlier theme. New work must use tokens, and legacy neutrals should be migrated to tokens when a section is next touched.

## 3. Typography (Guide p.06–07)

| Role | Face | Guide size | Web token |
|---|---|---|---|
| Primary (headings) | **Trajan Pro Bold** | H1 34pt · H2 22pt · H3 18pt | `--font-heading`, `--fs-h1/h2/h3` |
| Secondary (body, UI) | **Roboto** (Regular/Medium/Bold) | Body 14pt Medium · Description 12pt Regular | `--font-body`, `--fs-body`, `--fs-small` |

- **Trajan Pro is a licensed Adobe font and is not bundled.** The stack is `"Trajan Pro 3", "Trajan Pro", Cinzel, serif`. **Cinzel** (free, SIL OFL, self-hosted in `assets/fonts/`) is the stand-in and renders very similarly. When Harrison provides a Trajan Pro web licence (e.g. an Adobe Fonts kit), add it and it will take over automatically.
- Headings are set in the primary face, Bold, navy, with light tracking (`.012em`). No italic; emphasised words stay upright in gold.
- Web sizes scale up from the guide with `clamp()` (e.g. H1 34px → 58px) so they hold up on large screens. Do not go below the guide sizes.
- Body copy, navigation, buttons, form controls and captions use Roboto.

## 4. Components

| Component | Notes |
|---|---|
| Header | Flat translucent band, same on every page. Logo left, five links, "Let's talk" outline button. Services opens a 13-link mega-menu on hover, focus or click. |
| Buttons | Primary: `--brand-gold-light` fill, ink text, pill. Secondary: outline. Focus ring gold. |
| Cards | White (`--surface-raised`), 16–30px radius, 1px `--brand-grey`/gold hairline, soft navy shadow (`--shadow-card`). |
| Pinned-scroll section (`includes/pin-section.php`) | Images scroll left while the list is pinned right. Active item = white pill with a filled gold circle marker; inactive = outlined marker, muted title. Stacks on phones. |
| Dot field (`assets/js/dot-field.js`) | Decorative canvas: navy dots that part around the cursor and turn gold. Edges fade out. Static under reduced motion. |
| Scroll typewriter (`assets/js/typewriter.js`) | Home headings type in as they scroll into view. |
| Scroll blur lines (`assets/js/scroll-lines.js`) | Home About statement sharpens line by line. |
| Section boundaries | Never a hard edge: photo sections dissolve into `--surface`; decorative layers fade at their edges. |

## 5. Motion

- Restrained and purposeful: reveals, gentle hover zoom (4–6%), pinned-list transitions.
- Everything must work without JavaScript or the animation CDNs, and switch off under `prefers-reduced-motion`.

## 6. Accessibility

- Text contrast ≥ 4.5:1 (small) / 3:1 (large). Use `--brand-gold-text` for small gold text.
- Visible focus on all controls; menus close on Escape; collapsed content is `inert`.
- Decorative canvases and images are `aria-hidden` / empty `alt`.

## 7. Open items for Harrison

- Trajan Pro web licence (or approval to keep Cinzel).
- Vector logo files (SVG/AI) and the approved dark-background logo variant, if wanted.
- One photograph per service for the pinned sections (currently two images reused).
