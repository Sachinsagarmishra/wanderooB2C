<?php
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/turnstile.php';

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

try {
    $pdo->exec("CREATE TABLE IF NOT EXISTS `testimonials` (
      `id` int(11) NOT NULL AUTO_INCREMENT,
      `customer_name` varchar(120) NOT NULL,
      `image_path` varchar(500) DEFAULT NULL,
      `image_alt` varchar(255) DEFAULT NULL,
      `content` text NOT NULL,
      `rating` tinyint(1) NOT NULL DEFAULT 5,
      `sort_order` int(11) DEFAULT 0,
      `status` enum('active','draft') NOT NULL DEFAULT 'active',
      `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
      `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
      PRIMARY KEY (`id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");
} catch (PDOException $e) {
    // Non-blocking migration; pages can still render without testimonials.
}

try {
    $pdo->exec("CREATE TABLE IF NOT EXISTS `package_day_images` (
      `id` int(11) NOT NULL AUTO_INCREMENT,
      `package_id` int(11) NOT NULL,
      `day_number` int(11) NOT NULL,
      `image_path` varchar(500) NOT NULL,
      `alt_text` varchar(255) DEFAULT NULL,
      `sort_order` int(11) DEFAULT 0,
      PRIMARY KEY (`id`),
      KEY `package_id` (`package_id`),
      KEY `day_number` (`day_number`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");
} catch (PDOException $e) {
    // Non-blocking migration; day-wise images are optional.
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
