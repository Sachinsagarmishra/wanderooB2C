<?php
require_once __DIR__ . '/config.php';

header('Content-Type: text/plain; charset=utf-8');

$https = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') || (isset($_SERVER['SERVER_PORT']) && $_SERVER['SERVER_PORT'] == 443);
$scheme = $https ? 'https' : 'http';
$host = $_SERVER['HTTP_HOST'] ?? 'localhost';
$baseUrl = rtrim($scheme . '://' . $host . SITE_PATH, '/');

echo "User-agent: *\n";
echo "Allow: /\n\n";
echo "Disallow: /admin/\n";
echo "Disallow: /uploads/save_debug.log\n\n";
echo "Sitemap: " . $baseUrl . "/sitemap.xml\n";
?>
