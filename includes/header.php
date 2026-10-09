<?php
declare(strict_types=1);
require_once __DIR__ . '/site.php';
$basePath = $basePath ?? '';
$pageTitle = $pageTitle ?? 'Harrison Accountants';
$pageDescription = $pageDescription ?? 'Financial clarity and considered accounting support from Harrison.';
$currentNav = $currentNav ?? '';
$isServiceDetail = $isServiceDetail ?? false;
$isAboutSub = $isAboutSub ?? false;
?>
<!doctype html>
<html lang="en-GB" class="no-js">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?= h($pageTitle) ?> | Harrison Accountants</title>
  <script>(function(){var d=document.documentElement;if(window.matchMedia&&matchMedia("(prefers-reduced-motion: reduce)").matches)return;d.classList.add("fx-js");setTimeout(function(){d.classList.remove("fx-js")},3000)})()</script>
  <meta name="description" content="<?= h($pageDescription) ?>">
  <meta name="robots" content="noindex,nofollow">
  <meta name="theme-color" content="#ffffff">
  <link rel="icon" type="image/png" sizes="192x192" href="<?= h($basePath) ?>assets/favicon.png">
  <link rel="icon" type="image/png" sizes="32x32" href="<?= h($basePath) ?>assets/favicon-32.png">
  <link rel="apple-touch-icon" href="<?= h($basePath) ?>assets/apple-touch-icon.png">
  <link rel="preload" href="<?= h(asset('assets/fonts/cinzel-700.ttf', $basePath)) ?>" as="font" type="font/ttf" crossorigin>
  <link rel="preload" href="<?= h($basePath) ?>assets/roboto-regular.woff2" as="font" type="font/woff2" crossorigin>
  <!-- three.js (pinned) for the optional 3D pieces; modules load only on pages that use them -->
  <script type="importmap">{"imports":{"three":"https://cdn.jsdelivr.net/npm/three@0.186.1/build/three.module.min.js","three/addons/":"https://cdn.jsdelivr.net/npm/three@0.186.1/examples/jsm/"}}</script>
  <link rel="stylesheet" href="<?= h(asset('assets/css/tokens.css', $basePath)) ?>">
  <link rel="stylesheet" href="<?= h(asset('assets/css/styles.css', $basePath)) ?>">
  <link rel="stylesheet" href="<?= h(asset('assets/css/skeleton.css', $basePath)) ?>">
  <link rel="stylesheet" href="<?= h(asset('assets/css/pages.css', $basePath)) ?>">
  <link rel="stylesheet" href="<?= h(asset('assets/css/brand.css', $basePath)) ?>">
</head>
<body id="top" class="visari-mode light-mode" data-contact-url="<?= h($basePath) ?>contact.php">
<a class="skip-link" href="#main">Skip to content</a>
<div class="ambient-gold" aria-hidden="true"></div>
<header class="site-header">
  <div class="wrap header-inner">
    <a class="brand" href="<?= h($basePath) ?>index.php" aria-label="Harrison Accountants, home"><img src="<?= h($basePath) ?>assets/harrison-logo.png" width="1412" height="775" alt="Harrison Accountants"></a>
    <nav class="desktop-nav" aria-label="Main navigation">
      <a href="<?= h($basePath) ?>index.php"<?= $currentNav === 'home' ? ' aria-current="page"' : '' ?>>Home</a>
      <div class="nav-services" data-dropdown>
        <a href="<?= h($basePath) ?>services.php"<?= $currentNav === 'services' ? ($isServiceDetail ? ' aria-current="true"' : ' aria-current="page"') : '' ?>>Services</a>
        <button class="nav-dropdown-toggle" type="button" aria-label="Show Services menu" aria-controls="desktop-services-menu" aria-expanded="false" data-dropdown-toggle><svg viewBox="0 0 12 12" width="12" height="12" aria-hidden="true" focusable="false"><path d="M2.5 4.5 6 8l3.5-3.5" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg></button>
        <div class="services-dropdown mega-menu" id="desktop-services-menu" hidden>
          <div class="mega-col">
            <p class="mega-title"><a href="<?= h($basePath) ?>who-we-help.php">Who We Help</a></p>
            <ul>
              <?php foreach (audiences() as $navSlug => $navAudience): ?>
                <li><a href="<?= h(audience_url($navSlug, $basePath)) ?>"><?= h($navAudience['title']) ?></a></li>
              <?php endforeach; ?>
            </ul>
          </div>
          <div class="mega-col mega-col--wide">
            <p class="mega-title"><a href="<?= h($basePath) ?>services.php">What We Provide</a></p>
            <ul class="mega-two">
              <?php foreach (services() as $navSlug => $navService): ?>
                <li><a href="<?= h(service_url($navSlug, $basePath)) ?>"><?= h($navService['title']) ?></a></li>
              <?php endforeach; ?>
            </ul>
          </div>
        </div>
      </div>
      <div class="nav-services" data-dropdown>
        <a href="<?= h($basePath) ?>about.php"<?= $currentNav === 'about' ? ($isAboutSub ? ' aria-current="true"' : ' aria-current="page"') : '' ?>>About</a>
        <button class="nav-dropdown-toggle" type="button" aria-label="Show About menu" aria-controls="desktop-about-menu" aria-expanded="false" data-dropdown-toggle><svg viewBox="0 0 12 12" width="12" height="12" aria-hidden="true" focusable="false"><path d="M2.5 4.5 6 8l3.5-3.5" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg></button>
        <div class="services-dropdown nav-small-menu" id="desktop-about-menu" hidden>
          <ul>
            <?php foreach (ABOUT_PAGES as $navFile => $navLabel): ?>
              <li><a href="<?= h($basePath . $navFile) ?>"><?= h($navLabel) ?></a></li>
            <?php endforeach; ?>
          </ul>
        </div>
      </div>
      <a href="<?= h($basePath) ?>news.php"<?= $currentNav === 'news' ? ' aria-current="page"' : '' ?>>News</a>
      <a href="<?= h($basePath) ?>contact.php"<?= $currentNav === 'contact' ? ' aria-current="page"' : '' ?>>Contact</a>
    </nav>
    <div class="header-side"><a class="btn header-cta" href="<?= h($basePath) ?>contact.php">Let’s talk</a></div>
    <button class="menu-toggle" type="button" aria-expanded="false" aria-controls="mobile-navigation" aria-label="Open navigation" data-mobile-toggle><span class="menu-icon" aria-hidden="true"><i></i><i></i><i></i></span></button>
  </div>
</header>
<nav class="mobile-nav" id="mobile-navigation" aria-label="Mobile navigation" hidden>
  <div class="mobile-nav-label">Harrison / Navigation</div>
  <a href="<?= h($basePath) ?>index.php"<?= $currentNav === 'home' ? ' aria-current="page"' : '' ?>>Home</a>
  <div class="mobile-services-row"><a href="<?= h($basePath) ?>services.php">Services</a><button type="button" aria-label="Expand Services menu" aria-expanded="false" aria-controls="mobile-services-menu" data-mobile-sub-toggle>+</button></div>
  <div class="mobile-services-list" id="mobile-services-menu" hidden>
    <p class="mobile-sub-title"><a href="<?= h($basePath) ?>who-we-help.php">Who We Help</a></p>
    <?php foreach (audiences() as $navSlug => $navAudience): ?>
      <a href="<?= h(audience_url($navSlug, $basePath)) ?>"><?= h($navAudience['title']) ?></a>
    <?php endforeach; ?>
    <p class="mobile-sub-title"><a href="<?= h($basePath) ?>services.php">What We Provide</a></p>
    <?php foreach (services() as $navSlug => $navService): ?>
      <a href="<?= h(service_url($navSlug, $basePath)) ?>"><?= h($navService['title']) ?></a>
    <?php endforeach; ?>
  </div>
  <div class="mobile-services-row"><a href="<?= h($basePath) ?>about.php">About</a><button type="button" aria-label="Expand About menu" aria-expanded="false" aria-controls="mobile-about-menu" data-mobile-sub-toggle>+</button></div>
  <div class="mobile-services-list" id="mobile-about-menu" hidden>
    <?php foreach (ABOUT_PAGES as $navFile => $navLabel): ?>
      <a href="<?= h($basePath . $navFile) ?>"><?= h($navLabel) ?></a>
    <?php endforeach; ?>
  </div>
  <a href="<?= h($basePath) ?>news.php"<?= $currentNav === 'news' ? ' aria-current="page"' : '' ?>>News</a>
  <a href="<?= h($basePath) ?>contact.php"<?= $currentNav === 'contact' ? ' aria-current="page"' : '' ?>>Contact</a>
  <a class="btn btn-gold mobile-cta" href="<?= h($basePath) ?>contact.php">Let’s talk</a>
</nav>
<main id="main">
