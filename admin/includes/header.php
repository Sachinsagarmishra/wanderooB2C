<?php
require_once __DIR__ . '/../../config.php';
// Add authentication check here in the future
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard | <?php echo SITE_NAME; ?></title>
    <link rel="stylesheet" href="<?php echo SITE_PATH; ?>/admin/assets/css/admin-style.css">
</head>
<body>
    <div class="admin-layout">
        <aside class="admin-sidebar">
            <div class="sidebar-logo">
                Wanderoo Admin
            </div>
            <nav class="admin-nav">
                <a href="dashboard.php" class="nav-item active">Dashboard</a>
                <a href="#" class="nav-item">Pages</a>
                <a href="#" class="nav-item">Users</a>
                <a href="#" class="nav-item">Settings</a>
            </nav>
            <nav class="admin-nav nav-spacer">
                <a href="<?php echo SITE_PATH; ?>/" class="nav-item" target="_blank">View Site</a>
                <a href="logout.php" class="nav-item" style="color: var(--danger);">Logout</a>
            </nav>
        </aside>
        
        <main class="admin-main">
