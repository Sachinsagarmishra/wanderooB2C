<?php
require_once __DIR__ . '/../config.php';
$pageTitle = isset($pageTitle) ? $pageTitle . " | " . SITE_NAME : SITE_NAME;
$pageDesc = isset($pageDesc) ? $pageDesc : "A premium PHP-based website with custom admin panel.";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $pageTitle; ?></title>
    <meta name="description" content="<?php echo $pageDesc; ?>">
    
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="<?php echo SITE_PATH; ?>/assets/img/favicon.png">
    
    <!-- Google Fonts & Styles -->
    <link rel="stylesheet" href="<?php echo SITE_PATH; ?>/assets/css/style.css?v=1.2">
</head>
<body>
    <header>
        <div class="header-container">
            <a href="<?php echo SITE_PATH; ?>/" class="logo">
                <img src="<?php echo SITE_PATH; ?>/assets/img/wanderoo_Logo.png" alt="Wanderoo Logo">
            </a>
            
            <nav class="nav-glass">
                <a href="<?php echo SITE_PATH; ?>/" class="active">Home</a>
                <a href="#">About Us</a>
                <a href="#">Destinations <svg width="10" height="6" viewBox="0 0 10 6" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M1 1L5 5L9 1" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg></a>
                <a href="#">Contact Us</a>
                <a href="#">Blogs</a>
            </nav>

            <div class="header-actions">
                <a href="#" class="btn-enquire">Enquire Now</a>
                <div class="mobile-toggle">☰</div>
            </div>
        </div>
    </header>
