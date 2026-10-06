<?php
declare(strict_types=1);
/*
 * Shared template for the 13 service pages (services/*.php) and the 10 Who We Help pages
 * (who-we-help/*.php). Each entry file sets $detailKind ('service' | 'audience') and
 * $detailSlug, then requires this file. Unknown slugs return 404.
 */
require_once __DIR__ . '/site.php';
$detailKind = $detailKind ?? 'service';
$detailSlug = $detailSlug ?? '';
$isService = $detailKind === 'service';
$records = $isService ? services() : audiences();
$record = $records[$detailSlug] ?? null;
if ($record === null) {
    http_response_code(404);
    exit($isService ? 'Service not found.' : 'Page not found.');
}
$basePath = '../';
$currentNav = 'services';
$isServiceDetail = true;
$pageTitle = $record['title'];
$pageDescription = $record['description'];
$sectionLabel = $isService ? 'Services' : 'Who We Help';
$sectionHref = $basePath . ($isService ? 'services.php' : 'who-we-help.php');
$related = $isService ? $record['related'] : $record['services'];
[$fallbackPhoto, $fallbackCrop] = hero_fallback($detailSlug);
$heroArt = $record['image'] !== ''
    ? hero_art('images/pages/' . $record['image'], '50% 50%', $basePath)
    : hero_art($fallbackPhoto, $fallbackCrop, $basePath);
require __DIR__ . '/header.php';
?>
<section class="detail-hero" aria-labelledby="page-heading">
  <div class="wrap detail-hero-inner has-art" data-reveal>
    <div class="hero-copy">
      <nav class="crumbs" aria-label="Breadcrumb">
        <ol>
          <li><a href="<?= h($basePath) ?>index.php">Home</a></li>
          <li><a href="<?= h($sectionHref) ?>"><?= h($sectionLabel) ?></a></li>
          <li aria-current="page"><?= h($record['title']) ?></li>
        </ol>
      </nav>
      <p class="eyebrow-dark"><?= h($isService ? SERVICE_GROUPS[$record['group']] : 'Who we help') ?></p>
      <h1 id="page-heading"><?= h($record['title']) ?></h1>
      <p class="detail-lead"><?= h($record['lead']) ?></p>
    </div>
    <?= $heroArt ?>
  </div>
</section>

<section class="page-section detail-body" aria-label="<?= h($record['title']) ?> details">
  <div class="wrap detail-layout">
    <article class="detail-article">
<?php foreach ($record['body'] as [$blockType, $blockValue]): ?>
<?php if ($blockType === 'h'): ?>
      <h2><?= h($blockValue) ?></h2>
<?php elseif ($blockType === 'ul'): ?>
      <ul class="check-list">
<?php foreach ($blockValue as $blockItem): ?>
        <li><?= h($blockItem) ?></li>
<?php endforeach; ?>
      </ul>
<?php else: ?>
      <p><?= h($blockValue) ?></p>
<?php endif; ?>
<?php endforeach; ?>
    </article>

    <aside class="detail-aside" aria-label="More from <?= h($sectionLabel) ?>">
      <nav class="aside-card aside-nav" aria-label="<?= h($isService ? 'What we provide' : 'Who we help') ?>">
        <p class="aside-title"><?= h($isService ? 'What we provide' : 'Who we help') ?></p>
        <ul>
<?php foreach ($records as $siblingSlug => $sibling): ?>
          <li><a href="<?= h($isService ? service_url($siblingSlug, $basePath) : audience_url($siblingSlug, $basePath)) ?>"<?= $siblingSlug === $detailSlug ? ' aria-current="page"' : '' ?>><?= h($sibling['title']) ?></a></li>
<?php endforeach; ?>
        </ul>
      </nav>
      <div class="aside-card aside-contact">
        <p class="aside-title">Talk to us</p>
        <p>Call us or book a time that suits you.</p>
        <a class="aside-phone" href="tel:<?= h(CONTACT['phone_href']) ?>"><?= h(CONTACT['phone']) ?></a>
        <a class="btn btn-gold" href="<?= h($basePath) ?>appointment.php">Book an appointment</a>
      </div>
    </aside>
  </div>
</section>

<section class="page-section related-section" aria-labelledby="related-heading">
  <div class="wrap">
    <h2 id="related-heading"><?= $isService ? 'Related services' : 'Services that help' ?></h2>
    <div class="related-grid">
<?php foreach ($related as $relatedSlug): $relatedService = services()[$relatedSlug]; ?>
      <a class="related-card" href="<?= h(service_url($relatedSlug, $basePath)) ?>" data-reveal>
        <strong><?= h($relatedService['title']) ?></strong>
        <span><?= h($relatedService['summary']) ?></span>
      </a>
<?php endforeach; ?>
    </div>
  </div>
</section>

<section class="cta-band"><div class="wrap cta-inner"><div><p class="eyebrow">Your next decision</p><h2>Talk it through<br><em>with us.</em></h2></div><div><p>Tell us where you are and what you need. We will take it from there.</p><a class="btn btn-gold" href="<?= h($basePath) ?>appointment.php">Book an appointment <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M5 19 19 5M5 5h14v14" fill="none" stroke="currentColor" stroke-width="1.8"/></svg></a></div></div></section>
<?php require __DIR__ . '/footer.php'; ?>
