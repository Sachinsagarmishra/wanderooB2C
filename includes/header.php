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
    
    <!-- Favicon & Touch Icons -->
    <link rel="icon" type="image/png" sizes="32x32" href="<?php echo SITE_PATH; ?>/assets/img/favicon.png">
    <link rel="icon" type="image/png" sizes="16x16" href="<?php echo SITE_PATH; ?>/assets/img/favicon.png">
    <link rel="apple-touch-icon" sizes="180x180" href="<?php echo SITE_PATH; ?>/assets/img/favicon.png">
    
    <!-- Google Fonts & Styles -->
    <link rel="stylesheet" href="<?php echo SITE_PATH; ?>/assets/css/style.css?v=5.2">
</head>
<body class="<?php echo isset($bodyClass) ? htmlspecialchars($bodyClass) : ''; ?>">
    <header>
        <div class="header-container">
            <a href="<?php echo SITE_PATH; ?>/" class="logo">
                <img src="<?php echo SITE_PATH; ?>/assets/img/wanderoo_Logo.png" alt="Wanderoo Logo">
            </a>
            
            <nav class="nav-glass">
                <a href="<?php echo SITE_PATH; ?>/"<?php echo basename($_SERVER['SCRIPT_NAME']) == 'index.php' || basename($_SERVER['SCRIPT_NAME']) == '' ? ' class="active"' : ''; ?>>Home</a>
                <a href="<?php echo SITE_PATH; ?>/about-us"<?php echo basename($_SERVER['SCRIPT_NAME']) == 'about-us.php' ? ' class="active"' : ''; ?>>About Us</a>
                <div class="nav-dropdown">
                    <a href="#" class="nav-dropdown-trigger">Destinations <svg width="10" height="6" viewBox="0 0 10 6" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M1 1L5 5L9 1" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg></a>
                    <div class="dropdown-menu">
                        <a href="<?php echo SITE_PATH; ?>/destination/singapore">
                            <img src="<?php echo SITE_PATH; ?>/assets/img/Singapur.svg" alt="Singapore" class="dropdown-icon">
                            Singapore
                        </a>
                        <a href="<?php echo SITE_PATH; ?>/destination/maldives">
                            <img src="<?php echo SITE_PATH; ?>/assets/img/Maldives.svg" alt="Maldives" class="dropdown-icon">
                            Maldives
                        </a>
                        <a href="<?php echo SITE_PATH; ?>/destination/bali">
                            <img src="<?php echo SITE_PATH; ?>/assets/img/bali.svg" alt="Bali" class="dropdown-icon">
                            Bali
                        </a>
                        <a href="<?php echo SITE_PATH; ?>/destination/japan">
                            <img src="<?php echo SITE_PATH; ?>/assets/img/japan.svg" alt="Japan" class="dropdown-icon">
                            Japan
                        </a>
                        <a href="<?php echo SITE_PATH; ?>/destination/kerala">
                            <img src="<?php echo SITE_PATH; ?>/assets/img/Kerala.svg" alt="Kerala" class="dropdown-icon">
                            Kerala
                        </a>
                    </div>
                </div>
                <a href="<?php echo SITE_PATH; ?>/contact"<?php echo basename($_SERVER['SCRIPT_NAME']) == 'contact.php' ? ' class="active"' : ''; ?>>Contact Us</a>
            </nav>

            <div class="header-actions">
                <a href="#" class="btn-enquire">Enquire Now</a>
                <div class="mobile-toggle">☰</div>
            </div>
        </div>
    </header>
