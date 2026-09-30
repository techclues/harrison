<?php
declare(strict_types=1);
/*
 * Pinned-scroll section shared by Home and Services (behaviour: assets/js/pin-scroll.js).
 * Expects, set by the including page (prefixed to avoid clobbering page variables):
 *   $pinItems      list of [title, summary, text, href, cta, image, object-position]
 *   $pinEyebrow    small label above the heading
 *   $pinHeading    trusted heading HTML (may contain <br> and <em>)
 *   $pinHeadingId  id for the heading (aria-labelledby target)
 *   $pinLead       intro paragraph
 *   $pinWatermark  faded background word
 *   $pinDots       true to add the interactive dot canvas behind the content
 *   $pinFooterLink optional [href, label] shown under the list
 *   $pinTypewriter true to use the scroll typewriter on the heading
 */
?>
  <section class="pin-section" aria-labelledby="<?= h($pinHeadingId) ?>" data-pin-section>
<?php if (!empty($pinDots)): ?>
    <canvas class="dot-field" data-dot-field aria-hidden="true"></canvas>
<?php endif; ?>
    <span class="pin-watermark" aria-hidden="true"><?= h($pinWatermark) ?></span>
    <div class="wrap pin-grid">
      <div class="pin-media" aria-hidden="true">
<?php foreach ($pinItems as $pinN => [, , , , , $pinImg, $pinPos]): ?>
        <figure class="pin-figure" id="pin-figure-<?= $pinN ?>" data-pin-figure="<?= $pinN ?>"><img src="assets/<?= h($pinImg) ?>" alt="" loading="lazy" style="object-position: <?= h($pinPos) ?>"><figcaption><?= sprintf('%02d', $pinN + 1) ?></figcaption></figure>
<?php endforeach; ?>
      </div>
      <div class="pin-panel">
        <p class="eyebrow-dark"><?= h($pinEyebrow) ?></p>
        <h2 id="<?= h($pinHeadingId) ?>"<?= !empty($pinTypewriter) ? ' class="v-typewriter-heading"' : '' ?>><?= $pinHeading ?></h2>
        <p class="pin-lead"><?= h($pinLead) ?></p>
        <ol class="pin-list">
<?php foreach ($pinItems as $pinN => [$pinTitle, $pinSummary, $pinText, $pinHref, $pinCta]): ?>
          <li class="pin-item" data-pin-item="<?= $pinN ?>">
            <button class="pin-title" type="button" aria-controls="pin-body-<?= $pinN ?>"><span class="pin-mark" aria-hidden="true"><svg viewBox="0 0 12 12" width="12" height="12" focusable="false"><path d="M4.5 2.5 8 6l-3.5 3.5" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"/></svg></span><span><?= h($pinTitle) ?></span></button>
            <div class="pin-body" id="pin-body-<?= $pinN ?>"><div><?php if ($pinSummary !== ''): ?><p class="pin-summary"><?= h($pinSummary) ?></p><?php endif; ?><p><?= h($pinText) ?></p><a class="link-dark" href="<?= h($pinHref) ?>"><?= h($pinCta) ?> <span aria-hidden="true">↗</span></a></div></div>
          </li>
<?php endforeach; ?>
        </ol>
<?php if (!empty($pinFooterLink)): ?>
        <a class="pin-all" href="<?= h($pinFooterLink[0]) ?>"><?= h($pinFooterLink[1]) ?> <span aria-hidden="true">→</span></a>
<?php endif; ?>
      </div>
    </div>
  </section>
