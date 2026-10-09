<?php
declare(strict_types=1);

function h(string $value): string
{
    return htmlspecialchars($value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

/** Canonical ordered service records, keyed by slug (see data/services.php). */
function services(): array
{
    static $services = null;
    return $services ??= require __DIR__ . '/../data/services.php';
}

/** Who We Help audience records, in display order (see data/audiences.php). */
function audiences(): array
{
    static $audiences = null;
    return $audiences ??= require __DIR__ . '/../data/audiences.php';
}

function service_url(string $slug, string $basePath = ''): string
{
    return $basePath . 'services/' . rawurlencode($slug) . '.php';
}

/** Section labels used to group the services overview. */
const SERVICE_GROUPS = [
    'advice' => 'Advice & planning',
    'records' => 'Accounts & records',
    'tax' => 'Tax & HMRC',
    'company' => 'Company compliance',
    'people' => 'People & payroll',
];

function audience_url(string $slug, string $basePath = ''): string
{
    return $basePath . 'who-we-help/' . rawurlencode($slug) . '.php';
}

/** Pages under the About menu, in menu order: file => label. */
const ABOUT_PAGES = [
    'about.php' => 'Who We Are',
    'testimonials.php' => 'Testimonials',
    'privacy-cookies.php' => 'Privacy & Cookies Policy',
];

/* Firm contact details (same firm as the previous website, rebranded as Harrison). */
const CONTACT = [
    'address' => '3A Coldharbour Lane, Hayes, London UB3 3EA',
    'phone' => '020 8573 2666',
    'phone_href' => '+442085732666',
    'mobile' => '078 1844 4133',
    'mobile_href' => '+447818444133',
    'hours' => 'Monday to Friday, 10:00am – 6:00pm',
    'email' => 'info@harrison.co.uk',
];

/**
 * Photo for the right side of an inner page's title area. $src is relative to assets/ (a file in
 * assets/images/pages/ or one of the site photos); $pos is the CSS object-position crop.
 */
function hero_art(string $src, string $pos = '50% 50%', string $basePath = ''): string
{
    return '<figure class="hero-art"><img src="' . h($basePath . 'assets/' . $src) . '" alt="" loading="eager" style="object-position: ' . h($pos) . '"></figure>';
}

/** Deterministic fallback photo + crop for pages that have no photo of their own. */
function hero_fallback(string $slug): array
{
    $photos = ['conversation.webp', 'architecture.webp'];
    $crops = ['30% 45%', '70% 35%', '50% 20%', '62% 70%', '15% 55%', '85% 50%'];
    $n = crc32($slug);
    return [$photos[$n % 2], $crops[intdiv($n, 2) % count($crops)]];
}

/**
 * URL for a static asset with a version stamp (the file's last-modified time), so browsers,
 * phones especially, fetch a fresh copy whenever the file changes instead of reusing a stale one.
 */
function asset(string $path, string $basePath = ''): string
{
    $file = __DIR__ . '/../' . $path;
    return $basePath . $path . (is_file($file) ? '?v=' . filemtime($file) : '');
}
