<?php
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/includes/db.php';

$tourSlug = isset($_GET['tour']) ? trim($_GET['tour']) : '';
$destSlug = isset($_GET['destination']) ? trim(strtolower($_GET['destination'])) : '';

// ─── Try to fetch from Database first ───
$dbPackage = null;
$dbTags = [];
$dbDays = [];
$dbHighlights = [];
$dbInclusions = [];
$dbExclusions = [];
$dbPhotos = [];
$dbDayImages = [];

if (!empty($tourSlug) && !empty($destSlug)) {
    try {
        // Try exact slug match
        $stmt = $pdo->prepare("SELECT * FROM tour_packages WHERE slug = ? AND destination = ? AND status = 'active'");
        $stmt->execute([$tourSlug, $destSlug]);
        $dbPackage = $stmt->fetch();

        if ($dbPackage) {
            $pkgId = $dbPackage['id'];

            // Fetch tags
            $stmt = $pdo->prepare("SELECT tag_name FROM package_tags WHERE package_id = ? ORDER BY id");
            $stmt->execute([$pkgId]);
            $dbTags = $stmt->fetchAll(PDO::FETCH_COLUMN);

            // Fetch days
            $stmt = $pdo->prepare("SELECT * FROM package_days WHERE package_id = ? ORDER BY day_number");
            $stmt->execute([$pkgId]);
            $dbDays = $stmt->fetchAll();

            $stmt = $pdo->prepare("SELECT * FROM package_day_images WHERE package_id = ? ORDER BY day_number, sort_order");
            $stmt->execute([$pkgId]);
            foreach ($stmt->fetchAll() as $dayImage) {
                $dbDayImages[intval($dayImage['day_number'])][] = $dayImage;
            }

            // Fetch highlights
            $stmt = $pdo->prepare("SELECT highlight_text FROM package_highlights WHERE package_id = ? ORDER BY sort_order");
            $stmt->execute([$pkgId]);
            $dbHighlights = $stmt->fetchAll(PDO::FETCH_COLUMN);

            // Fetch inclusions
            $stmt = $pdo->prepare("SELECT item_text FROM package_inclusions WHERE package_id = ? AND type = 'inclusion' ORDER BY sort_order");
            $stmt->execute([$pkgId]);
            $dbInclusions = $stmt->fetchAll(PDO::FETCH_COLUMN);

            // Fetch exclusions
            $stmt = $pdo->prepare("SELECT item_text FROM package_inclusions WHERE package_id = ? AND type = 'exclusion' ORDER BY sort_order");
            $stmt->execute([$pkgId]);
            $dbExclusions = $stmt->fetchAll(PDO::FETCH_COLUMN);

            // Fetch photos
            $stmt = $pdo->prepare("SELECT * FROM package_photos WHERE package_id = ? ORDER BY sort_order");
            $stmt->execute([$pkgId]);
            $dbPhotos = $stmt->fetchAll();
        }
    } catch (PDOException $e) {
        // DB error — fall through to static fallback
    }
}

if (!$dbPackage) {
    $redirectUrl = SITE_PATH . '/';
    if (!empty($destSlug)) {
        $redirectUrl = SITE_PATH . '/' . $destSlug;
    }
    header("Location: " . $redirectUrl);
    exit;
}

$tourTitle = $dbPackage['title'];
$pageTitle = !empty($dbPackage['meta_title']) ? $dbPackage['meta_title'] : $tourTitle;
$pageDesc = !empty($dbPackage['meta_description']) ? $dbPackage['meta_description'] : (!empty($dbPackage['description']) ? $dbPackage['description'] : "Welcome to " . htmlspecialchars($tourTitle) . " – a custom package in " . htmlspecialchars(ucfirst($destSlug)) . " curated by Wanderoo.");
$pageKeywords = !empty($dbPackage['focus_keywords']) ? $dbPackage['focus_keywords'] : '';

function package_image_url($path) {
    if (empty($path)) {
        return SITE_PATH . '/assets/img/hero-bg.webp';
    }
    if (strpos($path, 'http://') === 0 || strpos($path, 'https://') === 0) {
        return $path;
    }
    return SITE_PATH . '/' . ltrim($path, '/');
}

$mainImg = package_image_url($dbPackage['hero_image'] ?? '');
$heroForBanner = $mainImg;
$tourDuration = $dbPackage['duration'];
$tourDescription = $dbPackage['description'];
$tourOverview = $dbPackage['overview'];
$tourPrice = $dbPackage['price'];
$tourOldPrice = $dbPackage['old_price'];
$tourSaveText = $dbPackage['save_text'];
$tourRating = $dbPackage['rating'];
$tourRatingCount = $dbPackage['rating_count'];
$tourHeroAlt = !empty($dbPackage['hero_image_alt']) ? $dbPackage['hero_image_alt'] : $tourTitle;

// Auto calculate save text if empty
if (empty($tourSaveText) && !empty($tourOldPrice) && !empty($tourPrice)) {
    $oldNum = intval(preg_replace('/[^\d]/', '', $tourOldPrice));
    $priceNum = intval(preg_replace('/[^\d]/', '', $tourPrice));
    if ($oldNum > $priceNum) {
        $diff = $oldNum - $priceNum;
        if (strpos($tourPrice, '₹') !== false || strpos($tourOldPrice, '₹') !== false) {
            $tourSaveText = "SAVE ₹" . number_format($diff);
        } else {
            $tourSaveText = "SAVE INR " . number_format($diff);
        }
    }
}

// Generate array of gallery images for JS lightbox
$galleryImagesJs = [];
$galleryImagesJs[] = $mainImg;
$validDbPhotos = [];

foreach ($dbPhotos as $photo) {
    $isExternal = (strpos($photo['image_path'], 'http://') === 0 || strpos($photo['image_path'], 'https://') === 0);
    if ($isExternal) {
        $validDbPhotos[] = $photo;
        if ($photo['image_path'] !== $mainImg) {
            $galleryImagesJs[] = $photo['image_path'];
        }
    } else {
        $localFile = __DIR__ . '/' . $photo['image_path'];
        if (file_exists($localFile)) {
            $validDbPhotos[] = $photo;
            $fullPath = SITE_PATH . '/' . $photo['image_path'];
            if ($fullPath !== $mainImg && $photo['image_path'] !== $mainImg) {
                $galleryImagesJs[] = $fullPath;
            }
        }
    }
}

include 'includes/header.php';
?>
<script>
    window.packageGalleryImages = <?php echo json_encode($galleryImagesJs); ?>;
</script>

    <div class="detail-hero-banner">
    <img src="<?php echo htmlspecialchars($heroForBanner); ?>" alt="<?php echo htmlspecialchars($tourHeroAlt); ?>" class="detail-hero-bg">
    <div class="detail-hero-overlay"></div>
    
    <?php
    // Filter out main hero image from the thumbnails so it doesn't display twice
    $galleryThumbs = [];
    foreach ($validDbPhotos as $photo) {
        $isExternal = (strpos($photo['image_path'], 'http://') === 0 || strpos($photo['image_path'], 'https://') === 0);
        $fullPath = $isExternal ? $photo['image_path'] : SITE_PATH . '/' . $photo['image_path'];
        if ($fullPath !== $mainImg && $photo['image_path'] !== $mainImg && $photo['image_path'] !== $dbPackage['hero_image']) {
            $galleryThumbs[] = $photo;
        }
    }
    $thumbPhotos = array_slice($galleryThumbs, 0, 4);
    $totalCountClass = count($thumbPhotos) + 1;
    ?>
    <div class="detail-gallery gallery-count-<?php echo $totalCountClass; ?>">
        <div class="detail-gallery-main">
            <img src="<?php echo htmlspecialchars($mainImg); ?>" alt="<?php echo htmlspecialchars($tourHeroAlt); ?>" class="detail-gallery-img">
            <?php if (count($thumbPhotos) > 0): ?>
                <button class="btn-view-all-images mobile-btn-view-all">View All Images</button>
            <?php endif; ?>
        </div>
        <?php
        foreach ($thumbPhotos as $idx => $photo):
            $isExternal = (strpos($photo['image_path'], 'http://') === 0 || strpos($photo['image_path'], 'https://') === 0);
            $imgUrl = $isExternal ? $photo['image_path'] : SITE_PATH . '/' . $photo['image_path'];
            
            $isLastDesktop = ($idx === count($thumbPhotos) - 1);
            $isLastMobile = ($idx === min(2, count($thumbPhotos) - 1));
            
            $thumbClass = "detail-gallery-thumb";
            if ($idx >= 3) {
                $thumbClass .= " desktop-only-thumb";
            }
        ?>
            <div class="<?php echo $thumbClass; ?> thumb-<?php echo $idx; ?>">
                <img src="<?php echo htmlspecialchars($imgUrl); ?>" alt="<?php echo htmlspecialchars($photo['alt_text'] ?? 'Travel Photo'); ?>" class="detail-gallery-img">
                <?php if ($isLastDesktop): ?>
                    <button class="btn-view-all-images desktop-btn-view-all">View All Images</button>
                <?php endif; ?>
                <?php if ($isLastMobile): ?>
                    <div class="btn-view-all-images mobile-view-all-overlay">
                        <span>View All</span>
                    </div>
                <?php endif; ?>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<div class="detail-container">
    <div class="detail-layout">
        <!-- Main Content Column -->
        <div class="detail-main-content">
            <!-- Badges -->
            <div class="detail-badges">
                <?php if (!empty($tourDuration)): ?>
                    <span class="detail-badge"><?php echo htmlspecialchars($tourDuration); ?></span>
                <?php endif; ?>
                <?php foreach ($dbTags as $tag): ?>
                    <span class="detail-badge"><?php echo htmlspecialchars($tag); ?></span>
                <?php endforeach; ?>
            </div>
            
            <!-- Title -->
            <h1 class="detail-title"><?php echo htmlspecialchars($tourTitle); ?></h1>
            
            <!-- Subdescription -->
            <?php if (!empty($tourDescription)): ?>
                <p class="detail-desc"><?php echo nl2br(htmlspecialchars($tourDescription)); ?></p>
            <?php endif; ?>
            
            <!-- Overview -->
            <?php if (!empty($tourOverview)): ?>
                <h3>Overview:</h3>
                <p class="detail-desc"><?php echo nl2br(htmlspecialchars($tourOverview)); ?></p>
            <?php endif; ?>
            
            <!-- Highlights -->
            <?php if (!empty($dbHighlights)): ?>
                <h3>Highlights</h3>
                <ul class="detail-highlights">
                    <?php foreach ($dbHighlights as $hl): ?>
                        <li>
                            <svg xmlns:xlink="http://www.w3.org/1999/xlink" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16" fill="none"><path d="M14.2507 8.98735C13.7507 11.4874 11.8657 13.8414 9.22071 14.3674C7.9307 14.6243 6.59252 14.4676 5.39672 13.9197C4.20091 13.3719 3.20843 12.4607 2.56061 11.3159C1.91278 10.1712 1.64263 8.85124 1.78862 7.54402C1.93461 6.23681 2.4893 5.00898 3.37371 4.03535C5.18771 2.03735 8.25071 1.48735 10.7507 2.48735" stroke="#0FB680" stroke-width="1.5px" stroke-linecap="round" stroke-linejoin="round" fill-opacity="0" fill="#F6F6F6"></path><path d="M5.75 7.98828L8.25 10.4883L14.25 3.98828" stroke="#0FB680" stroke-width="1.5px" stroke-linecap="round" stroke-linejoin="round" fill-opacity="0" fill="#F6F6F6"></path></svg>
                            <span><?php echo htmlspecialchars($hl); ?></span>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>
            
            <!-- Day-wise Itinerary -->
            <?php if (!empty($dbDays)): ?>
                <h3>Day-wise:</h3>
                <div class="itinerary-tabs">
                    <?php foreach ($dbDays as $idx => $day): ?>
                        <button class="itinerary-tab-btn <?php echo $idx === 0 ? 'active' : ''; ?>" data-day="<?php echo $day['day_number']; ?>">Day <?php echo str_pad($day['day_number'], 2, '0', STR_PAD_LEFT); ?></button>
                    <?php endforeach; ?>
                </div>
                
                <div class="itinerary-accordion">
                    <?php foreach ($dbDays as $idx => $day): ?>
                        <div class="itinerary-item <?php echo $idx === 0 ? 'active' : ''; ?>" id="day-<?php echo $day['day_number']; ?>">
                            <div class="itinerary-header">
                                <div class="itinerary-title-area">
                                    <span class="itinerary-day-badge">Day <?php echo str_pad($day['day_number'], 2, '0', STR_PAD_LEFT); ?></span>
                                    <span class="itinerary-title-text"><?php echo htmlspecialchars($day['day_title']); ?></span>
                                </div>
                                <svg class="itinerary-toggle-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 12 15 18 9"></polyline></svg>
                            </div>
                            <div class="itinerary-body" <?php echo $idx === 0 ? 'style="max-height: 1200px;"' : ''; ?>>
                                <div class="itinerary-body-content">
                                    <?php echo nl2br(htmlspecialchars($day['day_content'])); ?>
                                    <?php $dayImages = $dbDayImages[intval($day['day_number'])] ?? []; ?>
                                    <?php if (!empty($dayImages)): ?>
                                        <?php
                                        $imgCount = count($dayImages);
                                        $gridClass = $imgCount >= 4 ? '4' : (string)$imgCount;
                                        ?>
                                        <div class="itinerary-image-grid image-count-<?php echo $gridClass; ?>">
                                            <?php foreach ($dayImages as $dayImage): ?>
                                                <?php
                                                    $dayImgPath = $dayImage['image_path'];
                                                    $dayImgUrl = (strpos($dayImgPath, 'http://') === 0 || strpos($dayImgPath, 'https://') === 0) ? $dayImgPath : SITE_PATH . '/' . $dayImgPath;
                                                    $dayImgAlt = !empty($dayImage['alt_text']) ? $dayImage['alt_text'] : $day['day_title'];
                                                ?>
                                                <figure class="itinerary-image-item">
                                                    <img src="<?php echo htmlspecialchars($dayImgUrl); ?>" alt="<?php echo htmlspecialchars($dayImgAlt); ?>">
                                                </figure>
                                            <?php endforeach; ?>
                                        </div>
                                    <?php endif; ?>
                                    <?php if (!empty($day['accommodation']) || !empty($day['meals'])): ?>
                                        <div class="itinerary-details-meta">
                                            <?php if (!empty($day['accommodation'])): ?>
                                                <span><strong>Accommodation:</strong> <?php echo htmlspecialchars($day['accommodation']); ?></span>
                                            <?php endif; ?>
                                            <?php if (!empty($day['meals'])): ?>
                                                <span><strong>Meals:</strong> <?php echo htmlspecialchars($day['meals']); ?></span>
                                            <?php endif; ?>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
            
            <!-- Inclusions & Exclusions Box -->
            <?php if (!empty($dbInclusions) || !empty($dbExclusions)): ?>
                <div class="detail-inclusions-box">
                    <h3>What's inside the package?:</h3>
                    <div class="detail-inclusions-columns">
                        <?php if (!empty($dbInclusions)): ?>
                            <div class="detail-inclusions-col inclusions">
                                <h4>Inclusions</h4>
                                <ul>
                                    <?php foreach ($dbInclusions as $inc): ?>
                                        <li>
                                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg>
                                            <span><?php echo htmlspecialchars($inc); ?></span>
                                        </li>
                                    <?php endforeach; ?>
                                </ul>
                            </div>
                        <?php endif; ?>
                        <?php if (!empty($dbExclusions)): ?>
                            <div class="detail-inclusions-col exclusions">
                                <h4>Exclusions</h4>
                                <ul>
                                    <?php foreach ($dbExclusions as $exc): ?>
                                        <li>
                                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                                            <span><?php echo htmlspecialchars($exc); ?></span>
                                        </li>
                                    <?php endforeach; ?>
                                </ul>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endif; ?>
        </div>
        
        <!-- Sidebar Column -->
        <div class="detail-sidebar">
            <div class="detail-sidebar-box">
                <h4 class="sidebar-package-title"><?php echo htmlspecialchars($tourTitle); ?></h4>
                
                <!-- Premium Pricing Box -->
                <div class="sidebar-pricing-container">
                    <div class="price-row">
                        <div class="price-col">
                            <span class="starting-from">Starting From</span>
                            <span class="price-val"><?php echo htmlspecialchars($tourPrice); ?><span class="per-person">/Person</span></span>
                        </div>
                        <?php if (!empty($tourRating) && $tourRating > 0): ?>
                            <div class="rating-col">
                                <span class="star-icon">★</span>
                                <span class="rating-num"><?php echo htmlspecialchars($tourRating); ?></span>
                                <span class="rating-count">(<?php echo htmlspecialchars($tourRatingCount); ?>)</span>
                            </div>
                        <?php endif; ?>
                    </div>
                    
                    <?php if (!empty($tourOldPrice) || !empty($tourSaveText)): ?>
                        <div class="discount-row">
                            <?php if (!empty($tourOldPrice)): ?>
                                <span class="old-price"><?php echo htmlspecialchars($tourOldPrice); ?></span>
                            <?php endif; ?>
                            <?php if (!empty($tourSaveText)): ?>
                                <span class="save-badge <?php echo (strpos(strtolower($tourSaveText), 'save') !== false) ? 'green-badge' : 'gold-badge'; ?>">
                                    <?php echo htmlspecialchars($tourSaveText); ?>
                                </span>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>
                </div>

                <div class="sidebar-divider"></div>

                <button class="btn-quote btn-enquire sidebar-cta-btn" data-destination="<?php echo htmlspecialchars($destSlug); ?>" data-package="<?php echo htmlspecialchars($tourTitle); ?>">
                    Send Enquiry
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Gallery Lightbox Modal -->
<div class="gallery-modal" id="galleryModal">
    <div class="gallery-modal-close" id="closeGallery">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
    </div>
    
    <div class="gallery-modal-content">
        <button class="gallery-modal-arrow gallery-modal-arrow-prev" id="prevGalleryImg">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="15 18 9 12 15 6"></polyline></svg>
        </button>
        
        <div class="gallery-modal-image-wrapper">
            <img src="" alt="Gallery Image" class="gallery-modal-img" id="galleryModalImg">
            <div class="gallery-modal-counter" id="galleryCounter">1 of 8</div>
        </div>
        
        <button class="gallery-modal-arrow gallery-modal-arrow-next" id="nextGalleryImg">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"></polyline></svg>
        </button>
    </div>
    
    <!-- Thumbnails -->
    <div class="gallery-modal-thumbs" id="galleryModalThumbs"></div>
</div>

<?php
$whatsappNum = preg_replace('/\D/', '', get_setting('contact_whatsapp', '919113515462'));
$requestScheme = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
$packageUrl = SITE_PATH . '/' . $destSlug . '/' . $dbPackage['slug'];
$packageAbsUrl = $requestScheme . '://' . ($_SERVER['HTTP_HOST'] ?? '') . $packageUrl;
$whatsappMessage = "Hi Wanderoo, I am interested in the package \"" . $dbPackage['title'] . "\" for " . ucfirst($destSlug) . ".\nLink: " . $packageAbsUrl;
$whatsappUrl = 'https://wa.me/' . $whatsappNum . '?text=' . urlencode($whatsappMessage);
?>

<!-- Mobile Sticky CTA Bar -->
<div class="mobile-sticky-cta">
    <div class="mobile-sticky-cta-flex">
        <a href="<?php echo htmlspecialchars($whatsappUrl); ?>" class="btn-phone-sticky" target="_blank" rel="noopener" title="Ask on WhatsApp">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path></svg>
        </a>
        <a href="#" class="btn-quote btn-enquire btn-craft-trip" data-destination="<?php echo htmlspecialchars($destSlug); ?>" data-package="<?php echo htmlspecialchars($tourTitle); ?>">
            <svg class="ticket-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                <path d="M2 9a3 3 0 0 1 0 6v2a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2v-2a3 3 0 0 1 0-6V7a2 2 0 0 0-2-2H4a2 2 0 0 0-2 2Z"/>
                <path d="M13 5v14M9 9h.01M9 15h.01"/>
            </svg>
            Get a quote
        </a>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
