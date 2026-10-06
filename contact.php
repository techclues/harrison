<?php
declare(strict_types=1);
require_once __DIR__ . '/includes/site.php';
require_once __DIR__ . '/includes/forms.php';
$pageTitle = 'Contact';
$pageDescription = 'Contact Harrison Accountants in Hayes, London. Call 020 8573 2666 or send us a message.';
$currentNav = 'contact';
require __DIR__ . '/includes/header.php';
?>
<section class="detail-hero" aria-labelledby="page-heading">
  <div class="wrap detail-hero-inner has-art" data-reveal>
    <div class="hero-copy">
      <p class="eyebrow-dark">Contact</p>
      <h1 id="page-heading">Get in touch <em>with us.</em></h1>
      <p class="detail-lead">It would be great to hear from you. If you have any questions, please do not hesitate to send us a message. We look forward to hearing from you.</p>
    </div>
    <?= hero_art('conversation.webp', '28% 45%') ?>
  </div>
</section>

<section class="page-section" aria-labelledby="form-heading">
  <div class="wrap contact-grid">
    <div data-reveal>
      <h2 id="form-heading">Send an enquiry</h2>
      <form class="contact-form" method="post" action="send.php" novalidate data-enquiry-form aria-describedby="form-status">
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
        <?php $formType = 'contact'; $formSubmitLabel = 'Send enquiry'; require __DIR__ . '/includes/form-fields.php'; ?>
      </form>
    </div>
    <aside data-reveal aria-labelledby="details-heading">
      <h2 id="details-heading">Where to find us</h2>
      <?php require __DIR__ . '/includes/contact-details.php'; ?>
      <a class="btn btn-gold" href="appointment.php">Book an appointment</a>
      <?php require __DIR__ . '/includes/contact-aside.php'; ?>
    </aside>
  </div>
</section>
<?php require __DIR__ . '/includes/footer.php'; ?>
