<?php
// Dynamic Destination Page Template
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/includes/db.php';

function package_card_image_url($path) {
    if (empty($path) || strpos($path, 'images.unsplash.com') !== false) {
        return '';
    }

    if (strpos($path, 'http://') === 0 || strpos($path, 'https://') === 0) {
        return $path;
    }

    $localFile = __DIR__ . '/' . ltrim($path, '/');
    if (!file_exists($localFile)) {
        return '';
    }

    return SITE_PATH . '/' . ltrim($path, '/');
}



// Get destination from URL parameter, default to maldives
$slug = isset($_GET['name']) ? trim(strtolower($_GET['name'])) : 'maldives';

// Fetch destination from Database
$dest = null;
try {
    $stmtDest = $pdo->prepare("SELECT * FROM destinations WHERE slug = ?");
    $stmtDest->execute([$slug]);
    $dest = $stmtDest->fetch();
} catch (PDOException $e) {
    // Fail through
}

// Redirect if invalid destination
if (!$dest) {
    header("Location: " . SITE_PATH . "/");
    exit;
}

// Map db column to matching config structure keys
$dest['desc'] = $dest['description'];

$heroBg = $dest['hero_bg'];
if (!empty($heroBg) && strpos($heroBg, 'http://') !== 0 && strpos($heroBg, 'https://') !== 0) {
    $heroBg = SITE_PATH . '/' . $heroBg;
}

$pageTitle = !empty($dest['meta_title']) ? $dest['meta_title'] : $dest['title'];
$pageDesc = !empty($dest['meta_description']) ? $dest['meta_description'] : $dest['desc'];
$pageKeywords = !empty($dest['focus_keywords']) ? $dest['focus_keywords'] : '';
$bodyClass = "destination-page " . htmlspecialchars($slug) . "-page";

include_once 'includes/header.php';
?>

<main>
    <!-- Destination Hero Banner -->
    <div class="destination-hero-banner">
        <img src="<?php echo htmlspecialchars($heroBg); ?>" alt="<?php echo htmlspecialchars(!empty($dest['hero_bg_alt']) ? $dest['hero_bg_alt'] : $dest['name']); ?>" class="destination-hero-bg">
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
                <a href="#" class="read-more-btn btn-enquire" data-destination="<?php echo htmlspecialchars($slug); ?>">
                    Read More
                </a>
            </div>
        </div>

        <div class="divider" style="margin: 40px auto; border-top: 1px solid #eee; height: 1px;"></div>

        <!-- Packages Grid Section -->
        <div class="destination-packages-section">
            <!-- Wrap in category-packages to initialize the main.js automatic image carousel -->
            <div class="category-packages active" id="<?php echo $slug; ?>">
                <div class="destination-packages-grid">
                    <?php
                    // ─── Fetch dynamic packages from DB ───
                    $dbPkgs = [];
                    try {
                        $stmtDb = $pdo->prepare("SELECT * FROM tour_packages WHERE destination = ? AND status = 'active' ORDER BY created_at DESC");
                        $stmtDb->execute([$slug]);
                        $dbPkgs = $stmtDb->fetchAll();
                    } catch (PDOException $e) {
                        // DB error
                    }

                    if (empty($dbPkgs)):
                    ?>
                        <div style="grid-column: 1 / -1; text-align: center; padding: 60px 20px; color: #666; font-family: 'Urbanist', sans-serif;">
                            <h3 style="font-size: 20px; font-weight: 700; color: var(--text-dark); margin-bottom: 8px;">No Packages Available</h3>
                            <p style="font-size: 14px; color: #666;">We are currently updating our offerings. Please check back later or contact us to customize a bespoke trip.</p>
                            <a href="<?php echo SITE_PATH; ?>/contact" class="btn-enquire" style="display: inline-block; margin-top: 20px; text-decoration: none; padding: 12px 28px; border-radius: 30px; font-weight: 750;">Contact Us</a>
                        </div>
                    <?php
                    else:
                        $contactPhone = preg_replace('/\D/', '', get_setting('contact_phone', '919113515462'));
                        foreach ($dbPkgs as $dbPkg) {
                            $cardImages = [];
                            $cardAlts = [];

                            $heroCardUrl = package_card_image_url($dbPkg['hero_image'] ?? '');
                            if (!empty($heroCardUrl)) {
                                $cardImages[] = $heroCardUrl;
                                $cardAlts[] = !empty($dbPkg['hero_image_alt']) ? $dbPkg['hero_image_alt'] : $dbPkg['title'];
                            }

                            $stmtPhoto = $pdo->prepare("SELECT image_path, alt_text FROM package_photos WHERE package_id = ? ORDER BY sort_order");
                            $stmtPhoto->execute([$dbPkg['id']]);
                            $pkgPhotos = $stmtPhoto->fetchAll();
                            foreach ($pkgPhotos as $photo) {
                                $photoUrl = package_card_image_url($photo['image_path'] ?? '');
                                if (empty($photoUrl) || in_array($photoUrl, $cardImages, true)) {
                                    continue;
                                }
                                $cardImages[] = $photoUrl;
                                $cardAlts[] = !empty($photo['alt_text']) ? $photo['alt_text'] : $dbPkg['title'];
                            }

                            $cardImg = $cardImages[0] ?? '';
                            $cardAlt = $cardAlts[0] ?? $dbPkg['title'];

                            // Fetch tags
                            $stmtTags = $pdo->prepare("SELECT tag_name FROM package_tags WHERE package_id = ? ORDER BY id");
                            $stmtTags->execute([$dbPkg['id']]);
                            $dbTagNames = $stmtTags->fetchAll(PDO::FETCH_COLUMN);
                    ?>
                        <div class="package-card" data-card-images="<?php echo htmlspecialchars(json_encode($cardImages)); ?>" data-card-alts="<?php echo htmlspecialchars(json_encode($cardAlts)); ?>">
                            <div class="card-img">
                                <?php if (!empty($cardImg)): ?>
                                    <img src="<?php echo htmlspecialchars($cardImg); ?>" alt="<?php echo htmlspecialchars($cardAlt); ?>">
                                    <div class="card-img-dots">
                                        <?php for ($dotIdx = 0; $dotIdx < count($cardImages); $dotIdx++): ?>
                                            <span class="img-dot <?php echo $dotIdx === 0 ? 'active' : ''; ?>"></span>
                                        <?php endfor; ?>
                                    </div>
                                <?php else: ?>
                                    <div class="package-card-no-image">No image uploaded</div>
                                <?php endif; ?>
                            </div>
                            <div class="card-body">
                                <div class="card-meta">
                                    <span class="duration"><?php echo htmlspecialchars($dbPkg['duration']); ?></span>
                                    <span class="rating">
                                        <span class="star">★</span> <?php echo htmlspecialchars($dbPkg['rating']); ?>
                                        <span class="count">(<?php echo htmlspecialchars($dbPkg['rating_count']); ?>)</span>
                                    </span>
                                </div>
                                <h3>
                                    <a href="<?php echo SITE_PATH; ?>/<?php echo $slug; ?>/<?php echo htmlspecialchars($dbPkg['slug']); ?>" style="color: inherit; text-decoration: none;">
                                        <?php echo htmlspecialchars($dbPkg['title']); ?>
                                    </a>
                                </h3>
                                <div class="card-tags">
                                    <?php foreach ($dbTagNames as $tagName): ?>
                                        <span class="tag"><?php echo htmlspecialchars($tagName); ?></span>
                                    <?php endforeach; ?>
                                </div>
                                <div class="price-section">
                                    <?php if (!empty($dbPkg['old_price'])): ?>
                                        <div class="old-price-row">
                                            <span class="old-price"><?php echo htmlspecialchars($dbPkg['old_price']); ?></span>
                                            <?php if (!empty($dbPkg['save_text'])): ?>
                                                <span class="save-badge"><?php echo htmlspecialchars($dbPkg['save_text']); ?></span>
                                            <?php endif; ?>
                                        </div>
                                    <?php endif; ?>
                                    <div class="new-price-row">
                                        <span class="current-price"><?php echo htmlspecialchars($dbPkg['price']); ?></span>
                                        <span class="per-adult">/Adult</span>
                                    </div>
                                </div>
                                <div class="card-actions">
                                    <a href="tel:+<?php echo htmlspecialchars($contactPhone); ?>" class="btn-phone">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path></svg>
                                    </a>
                                    <a href="#" class="btn-request btn-enquire" data-destination="<?php echo htmlspecialchars($slug); ?>">Get a quote</a>
                                </div>
                            </div>
                        </div>
                    <?php
                        }
                    endif;
                    ?>
                </div>
            </div>
        </div>
    </div>
</main>

<?php include_once 'includes/footer.php'; ?>
