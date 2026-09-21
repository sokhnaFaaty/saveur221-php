<?php

header('Content-Type: application/xml; charset=utf-8');

require __DIR__ . '/../vendor/autoload.php';
Dotenv\Dotenv::createImmutable(dirname(__DIR__))->safeLoad();

$base = 'https://saveur-221.alwaysdata.net';

$sitemap = '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
$sitemap .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";

foreach (['', 'catalogue'] as $route) {
    $sitemap .= "<url><loc>$base/$route</loc><priority>" . ($route === '' ? '1.00' : '0.80') . "</priority></url>\n";
}

try {
    $stmt = \Core\Database::connect()->query('SELECT id FROM produits WHERE deleted_at IS NULL');
    while ($p = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $sitemap .= "<url><loc>$base/produits/{$p['id']}</loc><priority>0.70</priority></url>\n";
    }
} catch (\Throwable) {
    http_response_code(500);
    exit('Sitemap temporairement indisponible.');
}

$sitemap .= '</urlset>';
echo $sitemap;