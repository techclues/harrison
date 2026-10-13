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


/* Group the body into an intro (before the first heading) and one card per heading. */
$intro = [];
$sections = [];
$openSection = null;
foreach ($record['body'] as [$blockType, $blockValue]) {
    if ($blockType === 'h') {
        $label = rtrim($blockValue, ': ');
        $anchor = 'sec-' . trim((string) preg_replace('/[^a-z0-9]+/', '-', strtolower($label)), '-');
        $sections[] = ['title' => $label, 'id' => $anchor, 'blocks' => []];
        $openSection = array_key_last($sections);
    } elseif ($openSection === null) {
        $intro[] = [$blockType, $blockValue];
    } else {
        $sections[$openSection]['blocks'][] = [$blockType, $blockValue];
    }
}

/** Renders paragraph and list blocks (lists of four or more become a two-column tick grid). */
$renderBlocks = static function (array $blocks): void {
    foreach ($blocks as [$type, $value]) {
        if ($type === 'ul') {
            echo '<ul class="', count($value) >= 4 ? 'tick-grid' : 'tick-list', '">';
            foreach ($value as $item) echo '<li>', h($item), '</li>';
            echo '</ul>', "
";
        } else {
            echo '<p>', h($value), '</p>', "
";
        }
    }
};
$showJump = count($sections) >= 3;
$asCards = count($sections) >= 2;   // a page with a single heading reads better as flowing text than as one big card
$processSteps = [
    ['Listen first.', 'Understand the circumstances before discussing the solution.'],
    ['Agree what matters.', 'Make the scope, responsibilities and next steps clear.'],
    ['Stay connected.', 'Keep room for questions as your plans and priorities evolve.'],
];
require __DIR__ . '/header.php';
?>
<section class="detail-hero detail-hero--navy" aria-labelledby="page-heading">
  <div class="wrap detail-hero-inner has-art" data-detail-hero>
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
      <div class="hero-actions">
        <a class="btn btn-gold" href="<?= h($basePath) ?>contact.php">Let’s talk</a>
        <a class="btn btn-outline-light" href="tel:<?= h(CONTACT['phone_href']) ?>">Call <?= h(CONTACT['phone']) ?></a>
      </div>
<?php if ($showJump): ?>
      <nav class="jump-chips" aria-label="On this page">
        <span class="jump-label">On this page</span>
<?php foreach ($sections as $sec): ?>
        <a href="#<?= h($sec['id']) ?>"><?= h($sec['title']) ?></a>
<?php endforeach; ?>
      </nav>
<?php endif; ?>
    </div>
    <?= $heroArt ?>
  </div>
</section>

<section class="page-section detail-body" aria-label="<?= h($record['title']) ?> details">
  <div class="wrap detail-layout">
    <article class="detail-article" data-detail-content>
<?php if ($intro): ?>
      <div class="detail-intro">
<?php $renderBlocks($intro); ?>
      </div>
<?php endif; ?>
<?php foreach ($sections as $n => $sec): ?>
       <section class="detail-block<?= $asCards ? '' : ' is-flat' ?>" id="<?= h($sec['id']) ?>" aria-labelledby="<?= h($sec['id']) ?>-title" data-detail-section>
<?php if ($asCards && count($sections) >= 3): ?>
        <span class="block-num" aria-hidden="true"><?= sprintf('%02d', $n + 1) ?></span>
<?php endif; ?>
        <h2 id="<?= h($sec['id']) ?>-title"><?= h($sec['title']) ?></h2>
<?php $renderBlocks($sec['blocks']); ?>
      </section>
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
<?php if (!$isService): ?>
      <div class="aside-card aside-contact">
        <p class="aside-title">Talk to us</p>
        <p>Call us or book a time that suits you.</p>
        <a class="aside-phone" href="tel:<?= h(CONTACT['phone_href']) ?>"><?= h(CONTACT['phone']) ?></a>
        <a class="btn btn-gold" href="<?= h($basePath) ?>appointment.php">Book an appointment</a>
      </div>
<?php endif; ?>
    </aside>
  </div>
</section>

<section class="page-section process-band" aria-labelledby="process-heading">
  <div class="wrap">
    <p class="eyebrow-dark">How we work with you</p>
    <h2 id="process-heading">Good relationships make <em>good advice.</em></h2>
    <div class="process-grid">
<?php foreach ($processSteps as $i => [$stepTitle, $stepText]): ?>
      <article class="process-card" data-detail-card><span class="process-num" aria-hidden="true"><?= sprintf('%02d', $i + 1) ?></span><h3><?= h($stepTitle) ?></h3><p><?= h($stepText) ?></p></article>
<?php endforeach; ?>
    </div>
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
        <i class="related-arrow" aria-hidden="true">→</i>
      </a>
<?php endforeach; ?>
    </div>
  </div>
</section>

<section class="cta-band"><div class="wrap cta-inner"><div><p class="eyebrow">Your next decision</p><h2>Talk it through<br><em>with us.</em></h2></div><div><p>Tell us where you are and what you need. We will take it from there.</p><a class="btn btn-gold" href="<?= h($basePath) ?>appointment.php">Book an appointment <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M5 19 19 5M5 5h14v14" fill="none" stroke="currentColor" stroke-width="1.8"/></svg></a></div></div></section>
<?php require __DIR__ . '/footer.php'; ?>
