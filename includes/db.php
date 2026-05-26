<?php
require_once __DIR__ . '/../config.php';

/**
 * Database Connection using PDO
 */
try {
    $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4";
    $options = [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES   => false,
    ];
    
    $pdo = new PDO($dsn, DB_USER, DB_PASS, $options);
} catch (PDOException $e) {
    // In production, log error and show a user-friendly message
    die("Database Connection Failed: " . $e->getMessage());
}

// Load global settings
global $site_settings;
$site_settings = [];
try {
    $stmt = $pdo->query("SELECT setting_key, setting_value FROM settings");
    while ($row = $stmt->fetch()) {
        $site_settings[$row['setting_key']] = $row['setting_value'];
    }
} catch (PDOException $e) {
    // Fail silently if table doesn't exist yet (e.g. before create-admin is run)
}

if (!function_exists('get_setting')) {
    function get_setting($key, $default = '') {
        global $site_settings;
        return isset($site_settings[$key]) ? $site_settings[$key] : $default;
    }
}
?>
