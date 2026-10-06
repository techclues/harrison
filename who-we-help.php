<?php
declare(strict_types=1);
require_once __DIR__ . '/includes/site.php';
$pageTitle = 'Who We Help';
$pageDescription = 'Harrison Accountants supports start-ups, small and established businesses, directors, sole traders, contractors, freelancers, landlords, health workers and employed individuals.';
$currentNav = 'services';
require __DIR__ . '/includes/header.php';
$services = services();
?>
<section class="detail-hero" aria-labelledby="page-heading">
  <div class="wrap detail-hero-inner has-art" data-reveal>
    <div class="hero-copy">
      <p class="eyebrow-dark">Who we help</p>
      <h1 id="page-heading">Support that fits <em>your situation.</em></h1>
      <p class="detail-lead">Different people need different things from an accountant. Find the description closest to you to see how we can help.</p>
    </div>
    <?= hero_art('conversation.webp', '50% 40%') ?>
  </div>
</section>

<section class="page-section" aria-labelledby="audiences-heading">
  <div class="wrap">
    <h2 id="audiences-heading" class="visually-hidden">Who we help</h2>
    <div class="audience-grid">
      <?php foreach (audiences() as $id => $audience): ?>
        <article class="audience-card" id="<?= h($id) ?>" data-reveal aria-labelledby="<?= h($id) ?>-title">
          <h3 id="<?= h($id) ?>-title"><a href="<?= h(audience_url($id)) ?>"><?= h($audience['title']) ?></a></h3>
          <p class="audience-summary"><?= h($audience['summary']) ?></p>
          <p><?= h($audience['text']) ?></p>
          <p class="audience-links-label">Relevant services</p>
          <ul class="audience-links">
            <?php foreach ($audience['services'] as $slug): ?>
              <li><a href="<?= h(service_url($slug)) ?>"><?= h($services[$slug]['title']) ?></a></li>
            <?php endforeach; ?>
          </ul>
          <a class="audience-more" href="<?= h(audience_url($id)) ?>">More for <?= h(strtolower($audience['title'])) ?> <span aria-hidden="true">→</span></a>
        </article>
      <?php endforeach; ?>
      <aside class="cta-card audience-cta" data-reveal><p class="cta-card-title">Don’t see yourself here?</p><p>Every situation is different. Tell us about yours and we will tell you how we can help.</p><a class="btn btn-gold" href="appointment.php">Book an appointment</a></aside>
    </div>
  </div>
</section>

<section class="cta-band"><div class="wrap cta-inner"><div><p class="eyebrow">Not sure where you fit?</p><h2>Start with a<br><em>conversation.</em></h2></div><div><p>Tell us about your situation and we will point you to the right support.</p><a class="btn btn-gold" href="appointment.php">Book an appointment <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M5 19 19 5M5 5h14v14" fill="none" stroke="currentColor" stroke-width="1.8"/></svg></a></div></div></section>
<?php require __DIR__ . '/includes/footer.php'; ?>
