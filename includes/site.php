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

/*
 * Harrison's verified contact details have not been supplied. These are visible
 * draft placeholders: never substitute another firm's details.
 */
const CONTACT_PLACEHOLDERS = [
    'Address' => '[Harrison office address — to be supplied]',
    'Telephone' => '[Harrison telephone — to be supplied]',
    'Email' => '[Harrison email — to be supplied]',
    'Booking' => '[Harrison booking link — to be supplied]',
];
