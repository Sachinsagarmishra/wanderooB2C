<?php
// Dynamic Destination Page Template
require_once __DIR__ . '/config.php';



// Get destination from URL parameter, default to maldives
$slug = isset($_GET['name']) ? trim(strtolower($_GET['name'])) : 'maldives';

// Redirect if invalid destination
if (!array_key_exists($slug, $destinations)) {
    header("Location: " . SITE_PATH . "/");
    exit;
}

$dest = $destinations[$slug];

$pageTitle = $dest['title'];
$pageDesc = $dest['desc'];
$bodyClass = "destination-page " . $slug . "-page";

include_once 'includes/header.php';
?>

<main>
    <!-- Destination Hero Banner -->
    <div class="destination-hero-banner">
        <img src="<?php echo $dest['hero_bg']; ?>" alt="<?php echo htmlspecialchars($dest['name']); ?>" class="destination-hero-bg">
        <div class="destination-hero-overlay"></div>
        <div class="destination-hero-content">
            <span class="destination-breadcrumb-text"><a href="<?php echo SITE_PATH; ?>/">Home</a> &raquo; <a href="#">Destination</a> &raquo; <?php echo htmlspecialchars($dest['breadcrumb']); ?></span>
            <h1 class="destination-hero-title"><?php echo htmlspecialchars($dest['name']); ?></h1>
        </div>
    </div>

    <!-- About Destination Section -->
    <div class="destination-container">
        <div class="destination-intro-grid">
            <!-- Left: Title Block -->
            <div class="destination-intro-left">
                <h2 class="destination-main-heading">
                    <span class="urbanist">About</span><br>
                    <span class="playfair italic"><?php echo htmlspecialchars($dest['name']); ?> Packages</span>
                </h2>
            </div>
            
            <!-- Right: Paragraph Content & Action -->
            <div class="destination-intro-right">
                <p><?php echo htmlspecialchars($dest['desc']); ?></p>
                <a href="#" class="read-more-btn btn-enquire">
                    Read More ↗
                </a>
            </div>
        </div>

        <div class="divider" style="margin: 40px auto; border-top: 1px solid #eee; height: 1px;"></div>

        <!-- Packages Grid Section -->
        <div class="destination-packages-section">
            <!-- Wrap in category-packages to initialize the main.js automatic image carousel -->
            <div class="category-packages active" id="<?php echo $slug; ?>">
                <div class="destination-packages-grid">
                    <?php foreach ($dest['packages'] as $pkg): ?>
                        <div class="package-card">
                            <div class="card-img">
                                <img src="<?php echo $pkg['img']; ?>" alt="<?php echo htmlspecialchars($pkg['alt']); ?>">
                                <div class="card-img-dots">
                                    <span class="img-dot active"></span>
                                    <span class="img-dot"></span>
                                    <span class="img-dot"></span>
                                    <span class="img-dot"></span>
                                    <span class="img-dot"></span>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="card-meta">
                                    <span class="duration"><?php echo htmlspecialchars($pkg['duration']); ?></span>
                                    <span class="rating">
                                        <span class="star">★</span> <?php echo htmlspecialchars($pkg['rating']); ?> 
                                        <span class="count">(<?php echo htmlspecialchars($pkg['rating_count']); ?>)</span>
                                    </span>
                                </div>
                                <h3>
                                    <a href="<?php echo SITE_PATH; ?>/<?php echo $slug; ?>/<?php echo slugify($pkg['title']); ?>" style="color: inherit; text-decoration: none;">
                                        <?php echo htmlspecialchars($pkg['title']); ?>
                                    </a>
                                </h3>
                                <div class="card-tags">
                                    <?php foreach ($pkg['tags'] as $tag): ?>
                                        <span class="tag"><?php echo htmlspecialchars($tag); ?></span>
                                    <?php endforeach; ?>
                                </div>
                                <div class="price-section">
                                    <div class="old-price-row">
                                        <span class="old-price"><?php echo htmlspecialchars($pkg['old_price']); ?></span>
                                        <span class="save-badge"><?php echo htmlspecialchars($pkg['save']); ?></span>
                                    </div>
                                    <div class="new-price-row">
                                        <span class="current-price"><?php echo htmlspecialchars($pkg['price']); ?></span>
                                        <span class="per-adult">/Adult</span>
                                    </div>
                                </div>
                                <div class="card-actions">
                                    <a href="tel:+919113515462" class="btn-phone">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path></svg>
                                    </a>
                                    <a href="#" class="btn-request btn-enquire">Get a quote</a>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
</main>

<?php include_once 'includes/footer.php'; ?>
