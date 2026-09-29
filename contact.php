<?php
declare(strict_types=1);
require_once __DIR__ . '/includes/site.php';
$pageTitle = 'Contact';
$pageDescription = 'Get in touch with Harrison Accountants. Contact details and enquiry form to be confirmed.';
$currentNav = 'contact';
require __DIR__ . '/includes/header.php';
?>
<section class="detail-hero" aria-labelledby="page-heading">
  <div class="wrap detail-hero-inner" data-reveal>
    <p class="eyebrow-dark">Contact</p>
    <h1 id="page-heading">Let’s <em>talk.</em></h1>
    <p class="detail-lead">Tell us a little about what you need and we will get back to you.</p>
  </div>
</section>

<section class="page-section" aria-labelledby="form-heading">
  <div class="wrap contact-grid">
    <div data-reveal>
      <h2 id="form-heading">Send an enquiry</h2>
      <p class="preview-banner" role="note"><strong>Preview only.</strong> This form does not send or store anything yet. It will be connected once Harrison confirms where enquiries should go.</p>
      <form class="contact-form" method="post" novalidate data-preview-form aria-describedby="form-status">
        <div class="field">
          <label for="contact-name">Name <span class="req" aria-hidden="true">*</span></label>
          <input id="contact-name" name="name" type="text" autocomplete="name" required aria-required="true">
        </div>
        <div class="field">
          <label for="contact-email">Email <span class="req" aria-hidden="true">*</span></label>
          <input id="contact-email" name="email" type="email" autocomplete="email" required aria-required="true">
        </div>
        <div class="field">
          <label for="contact-phone">Phone <span class="optional">(optional)</span></label>
          <input id="contact-phone" name="phone" type="tel" autocomplete="tel">
        </div>
        <div class="field">
          <label for="contact-service">Service of interest</label>
          <select id="contact-service" name="service">
            <option value="">Not sure yet</option>
            <?php foreach (services() as $service): ?>
              <option><?= h($service['title']) ?></option>
            <?php endforeach; ?>
          </select>
        </div>
        <div class="field">
          <label for="contact-message">Message <span class="req" aria-hidden="true">*</span></label>
          <textarea id="contact-message" name="message" rows="6" required aria-required="true"></textarea>
        </div>
        <button class="btn btn-gold" type="button" aria-disabled="true" data-preview-submit>Send enquiry (disabled in preview)</button>
        <p class="form-status" id="form-status" role="status" aria-live="polite">Submissions are disabled while this page is in draft.</p>
      </form>
    </div>
    <aside data-reveal aria-labelledby="details-heading">
      <h2 id="details-heading">Contact details</h2>
      <p class="preview-banner" role="note">Harrison’s verified details have not been supplied yet. The items below are placeholders.</p>
      <dl class="contact-details">
        <?php foreach (CONTACT_PLACEHOLDERS as $label => $value): ?>
          <dt><?= h($label) ?></dt>
          <dd><?= h($value) ?></dd>
        <?php endforeach; ?>
      </dl>
    </aside>
  </div>
</section>
<?php require __DIR__ . '/includes/footer.php'; ?>
