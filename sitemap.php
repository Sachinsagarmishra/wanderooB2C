<?php
require_once __DIR__ . '/includes/db.php';

header('Content-Type: application/xml; charset=utf-8');

function sitemap_base_url() {
    $https = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') || (isset($_SERVER['SERVER_PORT']) && $_SERVER['SERVER_PORT'] == 443);
    $scheme = $https ? 'https' : 'http';
    $host = $_SERVER['HTTP_HOST'] ?? 'localhost';
    return rtrim($scheme . '://' . $host . SITE_PATH, '/');
}

function sitemap_date($date = null) {
    if (empty($date)) {
        return date('Y-m-d');
    }

    $timestamp = strtotime($date);
    return $timestamp ? date('Y-m-d', $timestamp) : date('Y-m-d');
}

function sitemap_url_xml($loc, $lastmod, $changefreq, $priority) {
    return "  <url>\n"
        . "    <loc>" . htmlspecialchars($loc, ENT_XML1, 'UTF-8') . "</loc>\n"
        . "    <lastmod>" . htmlspecialchars($lastmod, ENT_XML1, 'UTF-8') . "</lastmod>\n"
        . "    <changefreq>" . htmlspecialchars($changefreq, ENT_XML1, 'UTF-8') . "</changefreq>\n"
        . "    <priority>" . htmlspecialchars($priority, ENT_XML1, 'UTF-8') . "</priority>\n"
        . "  </url>\n";
}

$baseUrl = sitemap_base_url();
$today = date('Y-m-d');
$urls = [];

$urls[] = [$baseUrl . '/', $today, 'weekly', '1.0'];
$urls[] = [$baseUrl . '/about-us', $today, 'monthly', '0.7'];
$urls[] = [$baseUrl . '/contact', $today, 'monthly', '0.7'];

try {
    $stmtDestinations = $pdo->query("SELECT slug, updated_at FROM destinations ORDER BY sort_order, name");
    while ($destination = $stmtDestinations->fetch()) {
        $urls[] = [
            $baseUrl . '/destination/' . rawurlencode($destination['slug']),
            sitemap_date($destination['updated_at'] ?? null),
            'weekly',
            '0.9'
        ];
    }
} catch (PDOException $e) {
    // Keep sitemap valid even before destination table exists.
}

try {
    $stmtPackages = $pdo->query("SELECT destination, slug, updated_at FROM tour_packages WHERE status = 'active' ORDER BY updated_at DESC, created_at DESC");
    while ($package = $stmtPackages->fetch()) {
        $urls[] = [
            $baseUrl . '/' . rawurlencode($package['destination']) . '/' . rawurlencode($package['slug']),
            sitemap_date($package['updated_at'] ?? null),
            'weekly',
            '0.8'
        ];
    }
} catch (PDOException $e) {
    // Keep sitemap valid even before package table exists.
}

echo "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
echo "<urlset xmlns=\"http://www.sitemaps.org/schemas/sitemap/0.9\">\n";
foreach ($urls as $url) {
    echo sitemap_url_xml($url[0], $url[1], $url[2], $url[3]);
}
echo "</urlset>\n";
?>
