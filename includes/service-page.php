<?php
declare(strict_types=1);
// Service entry points set $serviceSlug; rendering lives in the shared detail template.
$detailKind = 'service';
$detailSlug = $serviceSlug ?? '';
require __DIR__ . '/detail-page.php';
