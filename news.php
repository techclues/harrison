<?php
declare(strict_types=1);
require_once __DIR__ . '/includes/site.php';
$pageTitle = 'News';
$pageDescription = 'News, guidance and practical tips on tax, VAT, payroll and running a business, from Harrison Accountants.';
$currentNav = 'news';
require __DIR__ . '/includes/header.php';

/*
 * SAMPLE ARTICLES. The previous website's posts were demo text from its website template, so none
 * were carried over. These are placeholder articles written for Harrison, kept general on purpose
 * (no rates, thresholds or deadlines that could go out of date). Replace with the firm's own news
 * before launch. Each: id, image (assets/images/pages/), title, date (Y-m-d), category, minutes, summary, body (paragraphs and
 * ['list', [items]] blocks).
 */
$posts = [
    [
        'id' => 'when-to-talk-to-an-accountant',
        'image' => 'freelancers.webp',
        'title' => 'Five signs it is time to talk to an accountant',
        'date' => '2026-09-24',
        'category' => 'Starting out',
        'minutes' => 3,
        'summary' => 'You do not need to wait for a problem. These are the moments when a conversation tends to pay for itself.',
        'body' => [
            'Many people wait until a deadline is close, or a letter arrives, before they speak to an accountant. A short conversation earlier is usually simpler, and often cheaper. Here are five moments when it is worth picking up the phone.',
            ['list', [
                'You are about to start trading, or you have just started, and are not sure whether to be a sole trader or form a company.',
                'You are taking on your first employee, or your first subcontractor.',
                'Your income has changed, for example a new source of income, rental property or a larger turnover than you expected.',
                'You are spending more time on paperwork than on the work itself.',
                'You are planning something bigger: buying equipment or premises, bringing in a partner, or selling the business.',
            ]],
            'None of these needs to be urgent. A first conversation is a chance to understand where you stand, what you need to keep track of, and what to do next.',
        ],
    ],
    [
        'id' => 'sole-trader-or-limited-company',
        'image' => 'start-ups.webp',
        'title' => 'Sole trader or limited company? Questions to ask first',
        'date' => '2026-09-10',
        'category' => 'Starting out',
        'minutes' => 4,
        'summary' => 'There is no single right answer. These questions help you work out which structure fits your situation.',
        'body' => [
            'Choosing how to run your business affects how you are taxed, what you must file and how much of your own money is at risk. The right choice depends on your circumstances, and it can change as the business grows.',
            ['list', [
                'How much profit do you expect, and how much of it do you need to take out for yourself?',
                'Do you want a clear separation between your own finances and the business?',
                'Will clients, or the sector you work in, expect you to be a company?',
                'How comfortable are you with extra admin, such as company accounts and filings?',
                'Do you plan to take on partners, investors or employees?',
            ]],
            'A limited company is a separate legal entity, which brings added responsibilities for directors. A sole trader has less paperwork but no separation between personal and business finances. Talk it through with us before you decide, so the choice is based on your numbers rather than a rule of thumb.',
        ],
    ],
    [
        'id' => 'records-to-keep-for-your-tax-return',
        'image' => 'bookkeeping-services.webp',
        'title' => 'The records worth keeping for your tax return',
        'date' => '2026-08-27',
        'category' => 'Personal tax',
        'minutes' => 3,
        'summary' => 'A simple habit of keeping the right paperwork makes Self Assessment far less stressful.',
        'body' => [
            'Good records are the foundation of an accurate tax return. You do not need a complicated system, only a consistent one. As a starting point, keep:',
            ['list', [
                'Sales invoices and a record of money received.',
                'Receipts and invoices for business expenses.',
                'Bank statements for any account used by the business.',
                'Details of other income, such as rent, interest or dividends.',
                'Statements and certificates from employers, pension providers and banks.',
            ]],
            'Keep them in one place, digital or paper, and update them regularly rather than once a year. If you are not sure how long to keep something, ask us. The answer depends on the type of record.',
        ],
    ],
    [
        'id' => 'making-tax-digital-day-to-day',
        'image' => 'vat.webp',
        'title' => 'What Making Tax Digital means day to day',
        'date' => '2026-08-13',
        'category' => 'VAT',
        'minutes' => 4,
        'summary' => 'Digital record-keeping is now part of VAT. Here is what that looks like in practice, and how to make it easy.',
        'body' => [
            'Making Tax Digital (MTD) means keeping VAT records digitally and sending returns through compatible software, rather than typing figures into a government website. For most businesses, the practical change is small once a routine is in place.',
            ['list', [
                'Choose accounting software that is compatible with MTD, or ask us to recommend one.',
                'Record sales and purchases as they happen, or upload them regularly.',
                'Review the figures before each return and ask questions early.',
                'Let us prepare and submit the return, or review it with you first.',
            ]],
            'The aim is fewer mistakes and less last-minute work. If you are unsure whether your current setup is compliant, we can check it with you.',
        ],
    ],
    [
        'id' => 'getting-ready-for-your-first-payroll',
        'image' => 'small-businesses.webp',
        'title' => 'Getting ready for your first payroll',
        'date' => '2026-07-30',
        'category' => 'Payroll',
        'minutes' => 4,
        'summary' => 'Taking on your first employee is exciting, and comes with a short list of things to set up first.',
        'body' => [
            'Becoming an employer brings new responsibilities, but they are manageable when you prepare in advance. Before the first pay day, you will need to:',
            ['list', [
                'Register as an employer and set up a payroll scheme.',
                'Collect the right details from your new employee.',
                'Agree pay dates and how payslips will be provided.',
                'Check your workplace pension duties.',
                'Decide who will run payroll and the monthly reporting that goes with it.',
            ]],
            'Many small employers choose to outsource payroll so that it is handled accurately and on time, with someone to ask when questions come up.',
        ],
    ],
    [
        'id' => 'dealing-with-a-letter-from-hmrc',
        'image' => 'hmrc-correspondence.webp',
        'title' => 'What to do when a letter from HMRC arrives',
        'date' => '2026-07-16',
        'category' => 'HMRC',
        'minutes' => 3,
        'summary' => 'A letter from HMRC can feel alarming, but a calm, methodical response usually works best.',
        'body' => [
            'Letters from HMRC cover everything from routine reminders to queries about a return. Whatever it says, the best first step is the same: do not ignore it.',
            ['list', [
                'Read it carefully and note any reference numbers and the date to respond by.',
                'Keep the letter and the envelope.',
                'Gather the records the letter refers to.',
                'Send us a copy as soon as you can. With your authority, we can read it with you and respond on your behalf.',
            ]],
            'Most letters are straightforward once you know what is being asked. Getting advice early keeps small queries small.',
        ],
    ],
];
$featured = array_shift($posts);
?>
<section class="detail-hero" aria-labelledby="page-heading">
  <div class="wrap detail-hero-inner has-art" data-reveal>
    <div class="hero-copy">
      <p class="eyebrow-dark">News</p>
      <h1 id="page-heading">News <em>&amp; insights.</em></h1>
      <p class="detail-lead">Practical guidance on tax, VAT, payroll and running a business, in plain English.</p>
    </div>
    <?= hero_art('architecture.webp', '75% 30%') ?>
  </div>
</section>

<section class="page-section news-section" aria-labelledby="posts-heading">
  <div class="wrap">
    <h2 id="posts-heading" class="visually-hidden">Latest articles</h2>

    <article class="post-feature" data-reveal>
      <div class="post-feature-art"><img src="assets/images/pages/<?= h($featured['image']) ?>" alt="" loading="lazy"></div>
      <div class="post-feature-body">
        <p class="post-meta"><span class="post-tag"><?= h($featured['category']) ?></span><time datetime="<?= h($featured['date']) ?>"><?= h(date('j F Y', strtotime($featured['date']))) ?></time><span><?= (int) $featured['minutes'] ?> min read</span></p>
        <h3><?= h($featured['title']) ?></h3>
        <p class="post-summary"><?= h($featured['summary']) ?></p>
        <details class="post-more">
          <summary><span>Read the article</span></summary>
          <div class="post-text">
<?php foreach ($featured['body'] as $block): ?>
<?php if (is_array($block)): ?>
            <ul class="check-list">
<?php foreach ($block[1] as $item): ?>
              <li><?= h($item) ?></li>
<?php endforeach; ?>
            </ul>
<?php else: ?>
            <p><?= h($block) ?></p>
<?php endif; ?>
<?php endforeach; ?>
          </div>
        </details>
      </div>
    </article>

    <div class="post-grid">
<?php foreach ($posts as $post): ?>
      <article class="post-card" data-reveal>
        <figure class="post-card-art"><img src="assets/images/pages/<?= h($post['image']) ?>" alt="" loading="lazy"></figure>
        <p class="post-meta"><span class="post-tag"><?= h($post['category']) ?></span><time datetime="<?= h($post['date']) ?>"><?= h(date('j M Y', strtotime($post['date']))) ?></time></p>
        <h3><?= h($post['title']) ?></h3>
        <p class="post-summary"><?= h($post['summary']) ?></p>
        <details class="post-more">
          <summary><span>Read the article</span><span class="post-read"><?= (int) $post['minutes'] ?> min</span></summary>
          <div class="post-text">
<?php foreach ($post['body'] as $block): ?>
<?php if (is_array($block)): ?>
            <ul class="check-list">
<?php foreach ($block[1] as $item): ?>
              <li><?= h($item) ?></li>
<?php endforeach; ?>
            </ul>
<?php else: ?>
            <p><?= h($block) ?></p>
<?php endif; ?>
<?php endforeach; ?>
          </div>
        </details>
      </article>
<?php endforeach; ?>
      <aside class="cta-card post-cta" data-reveal><p class="cta-card-title">Have a question?</p><p>If something here sounds like your situation, get in touch and we will talk it through.</p><a class="btn btn-gold" href="appointment.php">Book an appointment</a></aside>
    </div>

  </div>
</section>

<section class="cta-band"><div class="wrap cta-inner"><div><p class="eyebrow">Have a question?</p><h2>Ask us<br><em>anything.</em></h2></div><div><p>If something here sounds like your situation, get in touch and we will talk it through.</p><a class="btn btn-gold" href="appointment.php">Book an appointment <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M5 19 19 5M5 5h14v14" fill="none" stroke="currentColor" stroke-width="1.8"/></svg></a></div></div></section>
<?php require __DIR__ . '/includes/footer.php'; ?>
