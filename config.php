<?php
/**
 * Global Configuration
 */

// Site Information
// Site Information
define('SITE_NAME', 'Wanderoo');

// Dynamic Path Detection
$script_name = $_SERVER['SCRIPT_NAME']; // e.g. /index.php or /folder/index.php
$base_dir = rtrim(dirname($script_name), '/\\');

// If we are inside the admin folder, we need to go up one level to find the root
if (strpos($script_name, '/admin/') !== false) {
    $base_dir = rtrim(dirname($base_dir), '/\\');
}

define('SITE_PATH', $base_dir);

// Database Credentials
define('DB_HOST', 'localhost');
define('DB_NAME', 'wanderoo_db');
define('DB_USER', 'root');
define('DB_PASS', '');

// Error Reporting (Set to 0 in production)
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Start Session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
