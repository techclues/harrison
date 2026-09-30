<?php
declare(strict_types=1);
require_once __DIR__ . '/site.php';
$basePath = $basePath ?? '';
$pageTitle = $pageTitle ?? 'Harrison Accountants';
$pageDescription = $pageDescription ?? 'Financial clarity and considered accounting support from Harrison.';
$currentNav = $currentNav ?? '';
$isServiceDetail = $isServiceDetail ?? false;
?>
<!doctype html>
<html lang="en-GB" class="no-js">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?= h($pageTitle) ?> | Harrison Accountants</title>
  <meta name="description" content="<?= h($pageDescription) ?>">
  <meta name="robots" content="noindex,nofollow">
  <meta name="theme-color" content="#f7f6f2">
  <link rel="icon" type="image/png" href="<?= h($basePath) ?>assets/favicon.png">
  <link rel="preload" href="<?= h($basePath) ?>assets/fonts/cinzel-700.ttf" as="font" type="font/ttf" crossorigin>
  <link rel="preload" href="<?= h($basePath) ?>assets/roboto-regular.woff2" as="font" type="font/woff2" crossorigin>
  <link rel="stylesheet" href="<?= h($basePath) ?>assets/css/tokens.css">
  <link rel="stylesheet" href="<?= h($basePath) ?>assets/css/styles.css">
  <link rel="stylesheet" href="<?= h($basePath) ?>assets/css/skeleton.css">
  <link rel="stylesheet" href="<?= h($basePath) ?>assets/css/pages.css">
  <link rel="stylesheet" href="<?= h($basePath) ?>assets/css/brand.css">
</head>
<body id="top" class="visari-mode light-mode" data-contact-url="<?= h($basePath) ?>contact.php">
<a class="skip-link" href="#main">Skip to content</a>
<div class="ambient-gold" aria-hidden="true"></div>
<header class="site-header">
  <div class="wrap header-inner">
    <a class="brand" href="<?= h($basePath) ?>index.php" aria-label="Harrison Accountants, home"><img src="<?= h($basePath) ?>assets/harrison-logo.png" width="674" height="371" alt="Harrison Accountants"></a>
    <nav class="desktop-nav" aria-label="Main navigation">
      <a href="<?= h($basePath) ?>index.php"<?= $currentNav === 'home' ? ' aria-current="page"' : '' ?>>Home</a>
      <div class="nav-services" data-services-menu>
        <a href="<?= h($basePath) ?>services.php"<?= $currentNav === 'services' ? ($isServiceDetail ? ' aria-current="true"' : ' aria-current="page"') : '' ?>>Services</a>
        <button class="nav-dropdown-toggle" type="button" aria-label="Show Services pages" aria-controls="desktop-services-menu" aria-expanded="false" data-services-toggle><svg viewBox="0 0 12 12" width="12" height="12" aria-hidden="true" focusable="false"><path d="M2.5 4.5 6 8l3.5-3.5" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg></button>
        <div class="services-dropdown" id="desktop-services-menu" hidden>
          <div class="services-dropdown-intro"><span>Explore expertise</span><strong>Services, clearly considered.</strong><a href="<?= h($basePath) ?>services.php">View all services</a></div>
          <div class="services-dropdown-links">
            <?php foreach (services() as $navSlug => $navService): ?>
              <a href="<?= h(service_url($navSlug, $basePath)) ?>"><?= h($navService['title']) ?></a>
            <?php endforeach; ?>
          </div>
        </div>
      </div>
      <a href="<?= h($basePath) ?>who-we-help.php"<?= $currentNav === 'who' ? ' aria-current="page"' : '' ?>>Who We Help</a>
      <a href="<?= h($basePath) ?>about.php"<?= $currentNav === 'about' ? ' aria-current="page"' : '' ?>>About</a>
      <a href="<?= h($basePath) ?>contact.php"<?= $currentNav === 'contact' ? ' aria-current="page"' : '' ?>>Contact</a>
    </nav>
    <div class="header-side"><a class="btn header-cta" href="<?= h($basePath) ?>contact.php">Let’s talk</a></div>
    <button class="menu-toggle" type="button" aria-expanded="false" aria-controls="mobile-navigation" aria-label="Open navigation" data-mobile-toggle><span class="menu-icon" aria-hidden="true"><i></i><i></i><i></i></span></button>
  </div>
</header>
<nav class="mobile-nav" id="mobile-navigation" aria-label="Mobile navigation" hidden>
  <div class="mobile-nav-label">Harrison / Navigation</div>
  <a href="<?= h($basePath) ?>index.php"<?= $currentNav === 'home' ? ' aria-current="page"' : '' ?>>Home</a>
  <div class="mobile-services-row"><a href="<?= h($basePath) ?>services.php">Services</a><button type="button" aria-label="Expand Services pages" aria-expanded="false" aria-controls="mobile-services-menu" data-mobile-services-toggle>+</button></div>
  <div class="mobile-services-list" id="mobile-services-menu" hidden>
    <?php foreach (services() as $navSlug => $navService): ?>
      <a href="<?= h(service_url($navSlug, $basePath)) ?>"><?= h($navService['title']) ?></a>
    <?php endforeach; ?>
  </div>
  <a href="<?= h($basePath) ?>who-we-help.php"<?= $currentNav === 'who' ? ' aria-current="page"' : '' ?>>Who We Help</a>
  <a href="<?= h($basePath) ?>about.php"<?= $currentNav === 'about' ? ' aria-current="page"' : '' ?>>About</a>
  <a href="<?= h($basePath) ?>contact.php"<?= $currentNav === 'contact' ? ' aria-current="page"' : '' ?>>Contact</a>
</nav>
<main id="main">
