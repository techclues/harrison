<?php
declare(strict_types=1);
/*
 * One-off migration helper: extracts the main content of each Red Accountants page
 * (../RedAccountants/*.php) into structured blocks for review. Output: JSON on stdout.
 * Usage: php tools/extract-red.php Corporation-Tax [About ...]
 */
$src = dirname(__DIR__, 2) . '/RedAccountants/';
$out = [];
foreach (array_slice($argv, 1) as $page) {
    $html = file_get_contents($src . $page . '.php');
    $html = preg_replace('~<\?php.*?\?>~s', '', $html);
    $doc = new DOMDocument();
    libxml_use_internal_errors(true);
    $doc->loadHTML('<?xml encoding="utf-8"?>' . $html);
    $xp = new DOMXPath($doc);
    $title = trim($xp->evaluate('string(//h1)'));
    // Main content: the inner-page column, else every section after the inner header.
    $roots = $xp->query("//div[contains(@class,'blog-post-content')]");
    if (!$roots->length) $roots = $xp->query("//section");
    $blocks = [];
    foreach ($roots as $root) walk($root, $blocks);
    $imgs = [];
    foreach ($xp->query("//section//img/@src") as $a) $imgs[] = $a->value;
    $out[$page] = ['title' => $title, 'images' => array_values(array_unique($imgs)), 'blocks' => $blocks];
}
echo json_encode($out, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);

function clean(string $s): string { return trim(preg_replace('/\s+/u', ' ', $s)); }

function walk(DOMNode $node, array &$blocks): void
{
    foreach ($node->childNodes as $c) {
        if (!$c instanceof DOMElement) continue;
        $tag = strtolower($c->tagName);
        if (in_array($tag, ['script', 'style', 'form', 'nav'], true)) continue;
        if (preg_match('/^h[1-6]$/', $tag)) { $t = clean($c->textContent); if ($t !== '') $blocks[] = ['h', $t]; continue; }
        if ($tag === 'p') { $t = clean($c->textContent); if ($t !== '') $blocks[] = ['p', $t]; continue; }
        if ($tag === 'ul' || $tag === 'ol') {
            $items = [];
            foreach ($c->getElementsByTagName('li') as $li) { $t = clean($li->textContent); if ($t !== '') $items[] = $t; }
            if ($items) $blocks[] = ['ul', $items];
            continue;
        }
        if ($tag === 'blockquote') { $t = clean($c->textContent); if ($t !== '') $blocks[] = ['quote', $t]; continue; }
        walk($c, $blocks);
    }
}
