<?php
require_once __DIR__ . '/../../config.php';

// Enforce admin authentication
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: index.php");
    exit;
}

$currentPage = basename($_SERVER['SCRIPT_NAME']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard | <?php echo SITE_NAME; ?></title>
    <link rel="stylesheet" href="<?php echo SITE_PATH; ?>/admin/assets/css/admin-style.css">
    <!-- Prevent flash of wrong theme -->
    <script>
        (function() {
            var theme = localStorage.getItem('admin_theme');
            if (theme === 'light') {
                document.documentElement.classList.add('light-mode');
            }
        })();
    </script>
</head>
<body class="">
    <div class="admin-layout">
        <aside class="admin-sidebar">
            <div class="sidebar-logo">
                <span>Wanderoo Admin</span>
                <button class="theme-toggle" id="themeToggle" title="Toggle Light/Dark Mode" onclick="toggleAdminTheme()">
                    <span class="icon-sun">☀️</span>
                    <span class="icon-moon">🌙</span>
                </button>
            </div>
            <nav class="admin-nav">
                <a href="dashboard.php" class="nav-item <?php echo $currentPage === 'dashboard.php' ? 'active' : ''; ?>">Dashboard</a>
                <a href="manage-packages.php" class="nav-item <?php echo $currentPage === 'manage-packages.php' || $currentPage === 'package-form.php' ? 'active' : ''; ?>">Packages</a>
                <a href="leads.php" class="nav-item <?php echo $currentPage === 'leads.php' ? 'active' : ''; ?>">Leads</a>
            </nav>
            <nav class="admin-nav nav-spacer">
                <div style="padding: 10px 12px; font-size: 11px; color: var(--fg3);">Logged in as: <strong style="color: var(--fg2);"><?php echo htmlspecialchars($_SESSION['admin_username']); ?></strong></div>
                <a href="<?php echo SITE_PATH; ?>/" class="nav-item" target="_blank">View Site</a>
                <a href="logout.php" class="nav-item" style="color: var(--danger);">Logout</a>
            </nav>
        </aside>
        
        <main class="admin-main">

<script>
function toggleAdminTheme() {
    var html = document.documentElement;
    if (html.classList.contains('light-mode')) {
        html.classList.remove('light-mode');
        localStorage.setItem('admin_theme', 'dark');
    } else {
        html.classList.add('light-mode');
        localStorage.setItem('admin_theme', 'light');
    }
}
// Apply saved theme on page load (belt-and-suspenders with inline script above)
(function() {
    var theme = localStorage.getItem('admin_theme');
    if (theme === 'light') {
        document.documentElement.classList.add('light-mode');
    }
})();
</script>
