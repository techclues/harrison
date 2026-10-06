<?php
declare(strict_types=1);
require_once __DIR__ . '/includes/site.php';
require_once __DIR__ . '/includes/forms.php';
$pageTitle = 'Book an Appointment';
$pageDescription = 'Book an appointment with Harrison Accountants. Choose a date and time that suits you.';
$currentNav = 'appointment';
require __DIR__ . '/includes/header.php';
?>
<section class="detail-hero" aria-labelledby="page-heading">
  <div class="wrap detail-hero-inner has-art" data-reveal>
    <div class="hero-copy">
      <p class="eyebrow-dark">Book an appointment</p>
      <h1 id="page-heading">Book a time <em>that suits you.</em></h1>
      <p class="detail-lead">Tell us when you would like to meet and what you would like to discuss. Your first consultation is free, and gives us both the chance to understand each other.</p>
    </div>
    <?= hero_art('architecture.webp', '60% 40%') ?>
  </div>
</section>

<section class="page-section" aria-labelledby="form-heading">
  <div class="wrap contact-grid">
    <div data-reveal>
      <h2 id="form-heading">Your appointment</h2>
      <form class="contact-form" method="post" action="send.php" novalidate data-enquiry-form aria-describedby="form-status">
        <div class="field-row">
          <div class="field">
            <label for="appt-first">First name <span class="req" aria-hidden="true">*</span></label>
            <input id="appt-first" name="first_name" type="text" autocomplete="given-name" required aria-required="true">
          </div>
          <div class="field">
            <label for="appt-last">Last name <span class="req" aria-hidden="true">*</span></label>
            <input id="appt-last" name="last_name" type="text" autocomplete="family-name" required aria-required="true">
          </div>
        </div>
        <div class="field-row">
          <div class="field">
            <label for="appt-email">Email <span class="req" aria-hidden="true">*</span></label>
            <input id="appt-email" name="email" type="email" autocomplete="email" required aria-required="true">
          </div>
          <div class="field">
            <label for="appt-phone">Phone number <span class="req" aria-hidden="true">*</span></label>
            <input id="appt-phone" name="phone" type="tel" autocomplete="tel" required aria-required="true">
          </div>
        </div>
        <div class="field-row">
          <div class="field">
            <label for="appt-date">Preferred date <span class="req" aria-hidden="true">*</span></label>
            <input id="appt-date" name="date" type="date" min="<?= h(date('Y-m-d')) ?>" required aria-required="true">
          </div>
          <div class="field">
            <label for="appt-time">Preferred time <span class="optional">(10:00am – 6:00pm)</span></label>
            <input id="appt-time" name="time" type="time" min="10:00" max="18:00" step="900">
          </div>
        </div>
        <div class="field">
          <label for="appt-service">What would you like to discuss?</label>
          <select id="appt-service" name="service">
            <option value="">Not sure yet</option>
            <?php foreach (services() as $service): ?>
              <option><?= h($service['title']) ?></option>
            <?php endforeach; ?>
          </select>
        </div>
        <div class="field">
          <label for="appt-message">Message <span class="optional">(optional)</span></label>
          <textarea id="appt-message" name="message" rows="5"></textarea>
        </div>
        <?php $formType = 'appointment'; $formSubmitLabel = 'Request appointment'; require __DIR__ . '/includes/form-fields.php'; ?>
      </form>
    </div>
    <aside data-reveal aria-labelledby="details-heading">
      <h2 id="details-heading">Prefer to call?</h2>
      <?php require __DIR__ . '/includes/contact-details.php'; ?>
      <?php require __DIR__ . '/includes/contact-aside.php'; ?>
    </aside>
  </div>
</section>
<?php require __DIR__ . '/includes/footer.php'; ?>
