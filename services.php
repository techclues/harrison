<?php
declare(strict_types=1);
require_once __DIR__ . '/includes/site.php';
$pageTitle = 'Services';
$currentNav = 'services';
require __DIR__ . '/includes/header.php';
?>
<section class="page-hero-dark"><div class="wrap"><div class="page-hero-grid"><div><p class="eyebrow">The work behind the clarity</p><h1>Every number<br>has a <em>next move.</em></h1></div><p class="page-intro">From the detail of your day-to-day accounts to the decisions that shape your future, we bring the right support into focus.</p></div><div class="service-hero-line"><span>Accounting</span><span>Tax</span><span>Payroll</span><span>Advisory</span><span>Business support</span></div></div></section>
  <?php
$pinItems = [
  ['Company accounts', 'A clearer picture of your business, properly presented.', 'Accounts that help you understand performance, responsibilities and what deserves attention next.', 'services/accounting-services.php', 'Talk about accounts', 'conversation.webp', '30% 50%'],
  ['Tax & self assessment', 'Confidence around what you owe and why.', 'Practical support for personal and business tax, explained clearly and prepared with care.', 'services/personal-tax.php', 'Talk about tax', 'architecture.webp', '50% 20%'],
  ['Payroll & pensions', 'Reliable support for the people who keep your business moving.', 'Consistent payroll processes, useful reporting and support for workplace pension administration.', 'services/payroll.php', 'Talk about payroll', 'conversation.webp', '75% 40%'],
  ['Bookkeeping & VAT', 'Keep the everyday in good order.', 'Accurate records and organised VAT routines that make the bigger decisions easier.', 'services/bookkeeping-services.php', 'Talk about bookkeeping', 'architecture.webp', '50% 65%'],
  ['Business advisory', 'Turn financial information into a useful plan.', 'Connect cash flow, budgets and business milestones to a conversation about where you want to go.', 'services/business-tax-advice.php', 'Talk about advisory', 'conversation.webp', '55% 75%'],
  ['Starting a business', 'Build a thoughtful foundation for your next chapter.', 'Understand structure, records and the practical accounting habits that support a good start.', 'who-we-help.php#start-ups', 'Talk about starting up', 'architecture.webp', '50% 90%'],
  ['Corporation tax', 'Clarity for your company’s tax position.', 'Preparation and a clear conversation around computations, returns and filing responsibilities.', 'services/corporation-tax.php', 'Talk about corporation tax', 'conversation.webp', '15% 30%'],
  ['Personal tax', 'Considered support for your circumstances.', 'Support for income sources, rental records and the personal details behind every return.', 'services/personal-tax.php', 'Talk about personal tax', 'architecture.webp', '40% 45%'],
];
?>
<?php
$pinEyebrow = 'Our services';
$pinHeading = 'Practical expertise.<br><em>Useful perspective.</em>';
$pinHeadingId = 'catalogue-heading';
$pinLead = 'A complete view of the numbers, with a clear sense of what they mean for you.';
$pinWatermark = 'Services';
$pinDots = false;
$pinTypewriter = false;
$pinFooterLink = null;
require __DIR__ . '/includes/pin-section.php';
?>
  <section class="section light-section all-services" aria-labelledby="all-services-heading"><div class="wrap"><div class="section-heading-row"><div><p class="eyebrow-dark">All services</p><h2 id="all-services-heading">Find the <em>right support.</em></h2></div><p class="section-lead">Every service has its own page, grouped by what you are trying to get done.</p></div>
<?php foreach (SERVICE_GROUPS as $groupKey => $groupLabel): ?>
    <h3 class="service-group-title"><?= h($groupLabel) ?></h3>
    <div class="related-grid service-group">
<?php foreach (services() as $slug => $service): if ($service['group'] !== $groupKey) continue; ?>
      <a class="related-card" href="<?= h(service_url($slug)) ?>" data-reveal><strong><?= h($service['title']) ?></strong><span><?= h($service['summary']) ?></span></a>
<?php endforeach; ?>
    </div>
<?php endforeach; ?>
  </div></section>
  <section class="section ink-section advisory-section"><div class="wrap advisory-grid"><div class="advisory-image"><img src="assets/conversation.webp" width="1536" height="1024" loading="lazy" alt="Illustrative adviser and business owner discussing a document"><span>THE WHOLE<br><em>PICTURE.</em></span></div><div class="advisory-copy"><p class="eyebrow">A connected approach</p><h2>Not one service.<br><em>The right relationship.</em></h2><p>Accounts, tax, payroll and business plans are connected. Your support should be too.</p><div class="advisory-list"><div><b>01</b><span>Start with what needs attention now.</span></div><div><b>02</b><span>Build the systems that make progress visible.</span></div><div><b>03</b><span>Keep the conversation open as things change.</span></div></div><a class="btn btn-gold" href="contact.php">Find your starting point <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M5 19 19 5M5 5h14v14" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg></a></div></div></section>
  <section class="section value-section" aria-labelledby="value-heading"><div class="wrap"><div class="value-intro"><div><p class="value-badge"><span>✦</span>Where we create value</p><h2 id="value-heading">The business<br><em>outgrows guesswork.</em></h2></div><p class="value-lead">At some point, every business reaches the same place. The stakes get higher. The decisions get bigger. And the financial side can no longer run on instinct alone. That’s where Harrison creates leverage.</p></div><div class="value-grid"><article><span class="value-marker">✦</span><h3>Cash needs context.</h3><p>Knowing your bank balance is not the same as understanding what is driving it.</p></article><article><span class="value-marker">✦</span><h3>Success creates complexity.</h3><p>What got the business here is not always enough to support what’s next.</p></article><article><span class="value-marker">✦</span><h3>Decisions need visibility.</h3><p>The best decisions happen when the right information shows up at the right time.</p></article><article><span class="value-marker">✦</span><h3>Leadership needs leverage.</h3><p>The right financial partner creates capacity, confidence and better decisions.</p></article></div></div></section>
  <section class="cta-band"><div class="wrap cta-inner"><div><p class="eyebrow">Your next decision</p><h2>Make room for<br><em>what’s next.</em></h2></div><div><p>Start with a conversation that puts the whole picture on the table.</p><a class="btn btn-gold" href="contact.php">Let’s talk <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M5 19 19 5M5 5h14v14" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg></a></div></div></section>
<?php require __DIR__ . '/includes/footer.php'; ?>
