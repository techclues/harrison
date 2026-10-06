<?php
declare(strict_types=1);
require_once __DIR__ . '/includes/site.php';
$pageTitle = 'Privacy & Cookies Policy';
$pageDescription = 'How Harrison Accountants handles personal data, and our use of cookies.';
$currentNav = 'about';
$isAboutSub = true;
require __DIR__ . '/includes/header.php';
?>
<section class="detail-hero" aria-labelledby="page-heading">
  <div class="wrap detail-hero-inner has-art" data-reveal>
    <div class="hero-copy">
      <p class="eyebrow-dark">Privacy</p>
      <h1 id="page-heading">Privacy &amp; Cookies Policy</h1>
    </div>
    <?= hero_art('architecture.webp', '40% 70%') ?>
  </div>
</section>

<section class="page-section" aria-label="Policy">
  <div class="wrap detail-layout">
    <article class="detail-article policy">
      <p>Harrison Accountants is based at <?= h(CONTACT['address']) ?>. We may process “personal data” (as defined in UK data protection legislation, including the UK GDPR and the Data Protection Act 2018) as part of our contracted services and in administering those services. We may also process data “in compliance with a legal obligation” with respect to tax matters. We may process data on staff and on contacts in client companies or suppliers.</p>
      <p>The security of all data is important to us, and all feasible security measures are in place. Data is not knowingly transferred outside the United Kingdom, but we do use private cloud-based services. Data is held for as long as it remains relevant to the purpose for which it was collected. Once no longer required, data on paper or in electronic format is deleted by secure means.</p>
      <h2 id="sharing-data">Sharing data</h2>
      <p>Data may be shared with third parties as part of our contracted services, or where we are required by law to do so. “Third parties” may include specialist contractors we involve in specific projects for clients. We cannot accept any liability for any processing carried out by a third party outside our remit.</p>
      <h2 id="cookies">Cookies</h2>
      <p>As part of our compliance, we have conducted a cookie audit of our website. Cookies are small files that websites store on your device. This website does not use any cookies.</p>
      <h2 id="your-rights">Your rights</h2>
      <p>None of the above affects your rights under the legislation, in particular your right to access the data we hold about you. If you would like a copy of your data, please ask us in writing or by email, including enough information for us to identify you and search for the relevant data.</p>
      <p>If you are dissatisfied with this policy, have questions about our data protection procedures or wish to make a complaint, please contact us first. You also have the right to complain to the supervisory authority, the Information Commissioner’s Office (ICO):</p>
      <address class="policy-address">The Information Commissioner’s Office<br>Wycliffe House, Water Lane<br>Wilmslow, Cheshire SK9 5AF<br><a href="https://ico.org.uk/make-a-complaint/" rel="noopener">ico.org.uk/make-a-complaint</a></address>
    </article>
    <aside class="detail-aside" aria-label="About this policy">
      <nav class="aside-card aside-nav" aria-label="On this page">
        <p class="aside-title">On this page</p>
        <ul>
          <li><a href="#sharing-data">Sharing data</a></li>
          <li><a href="#cookies">Cookies</a></li>
          <li><a href="#your-rights">Your rights</a></li>
        </ul>
      </nav>
      <div class="aside-card aside-contact">
        <p class="aside-title">Questions about your data?</p>
        <p>Get in touch and we will be happy to help.</p>
        <a class="aside-phone" href="tel:<?= h(CONTACT['phone_href']) ?>"><?= h(CONTACT['phone']) ?></a>
        <a class="btn btn-gold" href="contact.php">Contact us</a>
      </div>
    </aside>
  </div>
</section>
<?php require __DIR__ . '/includes/footer.php'; ?>
