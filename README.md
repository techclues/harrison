# Harrison PHP site — local draft



A local-only, 18-route PHP website in the approved Harrison light theme. All pages are draft copy for client approval. Nothing has been deployed.



## Run locally



Install PHP 8.1 or later, then from this directory run:



```bash

php -S localhost:8000

```



Open `http://localhost:8000/`. Do not open `.php` files directly with `file://`; PHP must be served by a PHP-capable web server.



## Page map



32 routes. They mirror the firm's previous website (29 pages) plus Home and two overview pages, with the same page names:



- **Main:** `index.php` (Home), `news.php` (News), `contact.php` (Contact), `appointment.php` (Book an appointment).

- **About menu:** `about.php` (Who We Are), `testimonials.php` (Testimonials), `privacy-cookies.php` (Privacy & Cookies Policy).

- **Services mega-menu, What We Provide:** `services.php` overview + 13 pages in `services/`.

- **Services mega-menu, Who We Help:** `who-we-help.php` overview + 10 pages in `who-we-help/`.



Service and audience pages share one template, `includes/detail-page.php`. Their copy lives in `data/services.php` and `data/audiences.php` as `lead` + `body` blocks.



The copy was carried over from the previous site (same firm, rebranded) by `tools/extract-red.php` and `tools/build-content.py`, which list every wording change and fact correction. Testimonials and News show placeholders, because the old site's reviews and posts were demo text from its website template. Both forms (Contact, Appointment) email enquiries via `send.php`.



## Design system



The site follows the client's Brand Guide. Tokens are in `assets/css/tokens.css`, applied by `assets/css/brand.css`, and the rules are written up in [DESIGN-SYSTEM.md](DESIGN-SYSTEM.md). Read it before changing colours, type or the logo.



## Where things live



- `data/services.php` — canonical, ordered service records (title, group, summary, intro, covers, steps, related, and a `review` note listing what Harrison must confirm). The menus, footer, overview and pages all read from it.

- `data/audiences.php` — the ten audience records and the services each links to.

- `includes/` — `site.php` (helpers, contact placeholders), `header.php` (head, menus), `footer.php`, `service-page.php`.

- `assets/css/` — `tokens.css` (brand values), `styles.css` (approved design), `skeleton.css` (menus/shell), `pages.css` (Who We Help, Contact, service pages, pinned sections), `brand.css` (brand type and logo rules, loaded last).

- `assets/js/site.js` — menus, focus behaviour, enquiry form submission. Independent of animation libraries.

- `assets/js/animations.js` — optional GSAP/ScrollTrigger reveals and Lenis smooth scrolling. Skipped under `prefers-reduced-motion` or when the CDNs are unavailable; content is never hidden in CSS.



## Enquiry forms (Contact and Appointment)

Both forms post to `send.php`, which validates the input and emails the enquiry to **techcluesltd@gmail.com**. Visitors do not get an automatic confirmation email, and nothing is stored: the only things kept on the server are a short-lived per-visitor counter for rate limiting and the signing secret.

- **Protection:** a signed time-stamped token (a form submitted too fast, too late or forged is refused), a hidden honeypot field, a limit of 5 submissions per visitor per hour, server-side validation, and removal of line breaks so email headers cannot be injected. A consent checkbox links to the Privacy Policy.
- **Settings:** `config/mail.php` (recipient, sender, transport, limits). It holds no secrets. Put private values such as SMTP credentials in `config/mail.local.php` (copy `mail.local.php.example`), which is git-ignored.
- **Transports:** `log` (the default; writes each enquiry to `storage/outbox/*.eml` instead of sending, for local testing), `mail` (PHP's built-in `mail()`) and `smtp` (authenticated, via the bundled PHPMailer 7.1.1).
- **Folders:** `config/`, `storage/` and `includes/` carry `.htaccess` files that block web access on Apache. If the host is not Apache, move them outside the public web root or block them in the server config.

### Going live

1. Deploy, then set `'transport' => 'mail'` in `config/mail.local.php`. Most shared hosts, HostGator included, support `mail()` with no further setup. `'from'` must be a real address on the site's own domain (default `info@harrison.co.uk`).
2. For the most reliable delivery to Gmail and other providers, use `'smtp'` with the mailbox's host, username and password in `config/mail.local.php`.
3. Send a test enquiry and check it arrives, including the spam folder. Check `storage/errors.log` if it does not.
4. Make sure `storage/` is writable by the web server.

## Content to confirm before launch



The pages no longer show on-screen draft notes. Everything the client needs to confirm is listed here instead. (The same notes remain in the `review` field of each record in `data/`, which the site no longer renders.)



### Site-wide



- Verified contact details. The address, phone numbers and opening hours were carried over from the previous website, and the email is set to `info@harrison.co.uk` (confirm it receives mail), in `includes/site.php`.

- Registered company name and number: the privacy policy currently just says "Harrison Accountants". Add the registered name and number to its opening paragraph.

- Enquiry forms: switch the mail transport from `log` to `mail` or `smtp` when deploying (see "Enquiry forms"), and test that enquiries arrive at techcluesltd@gmail.com. Confirm the recipient and the privacy wording.

- Trajan Pro web licence (Cinzel is the stand-in), vector logo files, and a photo per service for the pinned sections.

- Then remove `noindex,nofollow` from `includes/header.php`.



### Testimonials (`testimonials.php`)



The five reviews were carried over from the previous website, where they were sample text from its website template (they mention lab equipment, a time difference, software development organisations and a website theme). **Replace them with genuine reviews from Harrison's clients, with permission, or a link to Google or Trustpilot reviews, before launch.** Publishing unverified reviews as real ones could breach consumer protection law (DMCC Act 2024).



### News (`news.php`)



The six articles are sample pieces written to show the layout. They are kept general (no rates, thresholds or deadlines). Replace them with Harrison's own articles before launch. The previous website's posts were demo text from its template, so they were not used.



### Privacy & Cookies Policy (`privacy-cookies.php`)



Carried over from the previous website and updated for UK GDPR (the old wording referred to the European Economic Area). Review it with a data protection adviser before launch.



### Services pages



- **Business Rates** (`services/business-rates.php`): Confirm the claim “We have reclaimed thousands for our previous clients” is still accurate for Harrison.

- **Company Secretarial Services** (`services/company-secretarial-services.php`): Confirm Harrison offers registered office, certification, scanning, shredding and company search services, and still has a Corporate Finance department.

- **Confirmation Statements** (`services/confirmation-statements.php`): Updated: the Annual Return was replaced in 2016. Since 2024 statements also confirm a registered email address and lawful purpose; mention if wanted.

- **VAT** (`services/vat.php`): Updated: Making Tax Digital is now in force for all VAT-registered businesses (the old copy said it was coming).

- **Payroll** (`services/payroll.php`): Updated: P35, P14 and P9D forms were abolished; replaced with current P60 and P11D wording.

- **Pension / Auto-Enrolment** (`services/pension-auto-enrolment.php`): Confirm the “financial planners” recommending schemes are FCA-authorised, or reword. The self-assessment paragraph on the old page moved to Personal Tax.

- **Personal Tax** (`services/personal-tax.php`): A stray Confirmation Statement paragraph from the old page was removed.

- **HMRC Correspondence** (`services/hmrc-correspondence.php`): Confirm “over a decade’s worth of experience” for Harrison.



### Who We Help pages



- **Start-ups** (`who-we-help/start-ups.php`): Confirm the free initial meeting is still offered.

- **Sole Traders** (`who-we-help/sole-traders.php`): Updated: VAT registration threshold is £90,000 (was £85,000). Confirm “hundreds of self-employed clients”.

- **Contractors** (`who-we-help/contractors.php`): A Companies House paragraph repeated under every business type on the old page now appears once, under Limited Company.

- **Employed Individuals** (`who-we-help/employed-individuals.php`): Updated: High Income Child Benefit Charge threshold is £60,000 (was £50,000).



## Deployment (GitHub Actions)

`.github/workflows/deploy.yml` runs on every push to `main`: it lints the PHP and JavaScript, runs the brand-colour check and the tests, and only if all pass uploads the site over FTPS. It can also be run by hand from the Actions tab. Only changed files are uploaded, and nothing on the server is deleted.

One-time setup:

1. In the GitHub repository: Settings → Secrets and variables → Actions → add secrets `FTP_SERVER`, `FTP_USERNAME` and `FTP_PASSWORD` (HostGator: cPanel → FTP Accounts). Optionally add a *variable* `FTP_DIR` if the site is not in `public_html/`.
2. Optional: Settings → Environments → `production` → add required reviewers, so each deploy needs an approval.
3. On the server, once, create `config/mail.local.php` (see "Enquiry forms") so enquiries are emailed. It is never deployed or overwritten.
4. If the host does not offer FTPS, change `protocol: ftps` to `ftp` in the workflow.

Not uploaded: tests, tools, Markdown files, Git files, `storage/*` runtime data and `config/mail.local.php`.

## Checks



```bash

node --test tests/skeleton.test.mjs tests/forms.test.mjs

node --check assets/js/site.js && node --check assets/js/animations.js

python tools/brand-colours.py --check   # every colour must be on the brand palette

find . -name '*.php' -print0 | xargs -0 -n1 php -l

```



The tests cover routes, data completeness, per-page rendering, menu semantics, contact-form safety and stale-link scans. Rendering tests are skipped when PHP is not installed.



## Launch checklist (summary)



- Verified address, telephone, email, booking link and social accounts (placeholders in `includes/site.php`)

- Approval of all service and audience copy, including each record's `review` note

- Logo/brand asset approval

- Team names and credentials, and real client testimonials (none are included)

- Contact-form recipient and data-handling approval

- Then remove `noindex,nofollow` from `includes/header.php`



`tools/import-existing-pages.mjs` is a one-time mechanical importer for the three original HTML page bodies. Do not re-run it: it would overwrite `index.php`, `services.php` and `about.php` with the original versions.
