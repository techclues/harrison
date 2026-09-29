<?php
declare(strict_types=1);
require_once __DIR__ . '/site.php';
$serviceSlug = $serviceSlug ?? '';
$service = services()[$serviceSlug] ?? null;
if ($service === null) {
    http_response_code(404);
    exit('Service not found.');
}
$basePath = '../';
$currentNav = 'services';
$isServiceDetail = true;
$pageTitle = $service['title'];
$pageDescription = $service['description'];
require __DIR__ . '/header.php';
?>
<section class="detail-hero" aria-labelledby="page-heading">
  <div class="wrap detail-hero-inner" data-reveal>
    <nav class="crumbs" aria-label="Breadcrumb">
      <ol>
        <li><a href="<?= h($basePath) ?>index.php">Home</a></li>
        <li><a href="<?= h($basePath) ?>services.php">Services</a></li>
        <li aria-current="page"><?= h($service['title']) ?></li>
      </ol>
    </nav>
    <p class="eyebrow-dark"><?= h(SERVICE_GROUPS[$service['group']]) ?></p>
    <h1 id="page-heading"><?= h($service['title']) ?></h1>
    <p class="detail-lead"><?= h($service['intro']) ?></p>
  </div>
</section>

<section class="page-section detail-body" aria-labelledby="covers-heading">
  <div class="wrap detail-grid">
    <div data-reveal>
      <h2 id="covers-heading">What this can include</h2>
      <ul class="check-list">
        <?php foreach ($service['covers'] as $item): ?>
          <li><?= h($item) ?></li>
        <?php endforeach; ?>
      </ul>
    </div>
    <aside data-reveal aria-labelledby="steps-heading">
      <h2 id="steps-heading">How we work with you</h2>
      <ol class="step-list">
        <?php foreach ($service['steps'] as [$stepTitle, $stepText]): ?>
          <li><strong><?= h($stepTitle) ?></strong><span><?= h($stepText) ?></span></li>
        <?php endforeach; ?>
      </ol>
    </aside>
  </div>
</section>

<section class="page-section related-section" aria-labelledby="related-heading">
  <div class="wrap">
    <h2 id="related-heading">Related services</h2>
    <div class="related-grid">
      <?php foreach ($service['related'] as $relatedSlug): $related = services()[$relatedSlug]; ?>
        <a class="related-card" href="<?= h(service_url($relatedSlug, $basePath)) ?>" data-reveal>
          <strong><?= h($related['title']) ?></strong>
          <span><?= h($related['summary']) ?></span>
        </a>
      <?php endforeach; ?>
    </div>
    <p class="draft-note"><strong>Draft for approval:</strong> <?= h($service['review']) ?></p>
  </div>
</section>

<section class="cta-band"><div class="wrap cta-inner"><div><p class="eyebrow">Your next decision</p><h2>Talk it through<br><em>with us.</em></h2></div><div><p>Tell us where you are and what you need. We will take it from there.</p><a class="btn btn-gold" href="<?= h($basePath) ?>contact.php">Let’s talk <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M5 19 19 5M5 5h14v14" fill="none" stroke="currentColor" stroke-width="1.8"/></svg></a></div></div></section>
<?php require __DIR__ . '/footer.php'; ?>
