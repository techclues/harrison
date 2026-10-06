<?php
declare(strict_types=1);
require_once __DIR__ . '/includes/site.php';
$pageTitle = 'Testimonials';
$pageDescription = 'What clients say about working with Harrison Accountants.';
$currentNav = 'about';
$isAboutSub = true;
require __DIR__ . '/includes/header.php';
/*
 * Reviews as [quote, name, context]. These five come from the previous website: the three on its
 * Testimonials page plus two more from its About page (names and text as on that site, firm name changed). They are template demo text, not verified client reviews, so the
 * draft note below must stay until each is replaced by a genuine review from a client who has agreed
 * to be quoted (or by a link to Harrison's Google or Trustpilot reviews).
 */
$reviews = [
    ['The most essential aspect of a team is the ability to grow with experience in terms of understanding and skill. The team we have at Harrison Accountants has been very expert at this.', 'Michael Bean', ''],
    ['I routinely receive email and support well beyond an expected time. Although there is a significant time difference of 11-12 hours between us it appears that we receive 16 hours daily service.', 'Anne Smith', ''],
    ['Our partnership with Harrison Accountants has allowed us to decrease our labor costs significantly, and increase round-the-clock utilization of expensive lab equipment. Thanks!', 'Felica Queen', ''],
    ['What sets Harrison Accountants apart is its ability to ramp up work quickly with their employees. Other software development organizations often have to hire to fill a demand for new work.', 'John Doe', ''],
    ['I had a few things I needed help with on this theme... Their customer service was amazing and helped me out many times. One of the complete themes with different requirements.', 'Sara Lisbon', ''],
];
?>
<section class="detail-hero" aria-labelledby="page-heading">
  <div class="wrap detail-hero-inner has-art" data-reveal>
    <div class="hero-copy">
      <p class="eyebrow-dark">Testimonials</p>
      <h1 id="page-heading">Client opinions <em>&amp; reviews.</em></h1>
      <p class="detail-lead">We believe in building long-term relationships with our clients. Here is what some of them say about working with us.</p>
    </div>
    <?= hero_art('conversation.webp', '78% 40%') ?>
  </div>
</section>

<section class="page-section" aria-labelledby="reviews-heading">
  <div class="wrap">
    <h2 id="reviews-heading" class="visually-hidden">Client reviews</h2>
<?php if ($reviews): ?>
    <div class="review-grid">
<?php foreach ($reviews as [$quote, $name, $context]): ?>
      <figure class="review-card" data-reveal>
        <blockquote><p><?= h($quote) ?></p></blockquote>
        <figcaption><cite><?= h($name) ?></cite><?php if ($context !== ''): ?><span><?= h($context) ?></span><?php endif; ?></figcaption>
      </figure>
<?php endforeach; ?>
      <aside class="cta-card review-cta" data-reveal><p class="cta-card-title">Talk to us</p><p>Find out how we can help you, with a free initial consultation.</p><a class="btn btn-gold" href="appointment.php">Book an appointment</a></aside>
    </div>
<?php else: ?>
    <div class="review-grid" aria-hidden="true">
<?php for ($i = 0; $i < 3; $i++): ?>
      <div class="review-card review-card--placeholder"><span></span><span></span><span></span><i></i></div>
<?php endfor; ?>
    </div>
<?php endif; ?>
  </div>
</section>

<section class="cta-band"><div class="wrap cta-inner"><div><p class="eyebrow">Worked with us?</p><h2>Leave us<br><em>a review.</em></h2></div><div><p>We would love to hear how we did. Get in touch and tell us about your experience.</p><a class="btn btn-gold" href="contact.php">Share your experience <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M5 19 19 5M5 5h14v14" fill="none" stroke="currentColor" stroke-width="1.8"/></svg></a></div></div></section>
<?php require __DIR__ . '/includes/footer.php'; ?>
