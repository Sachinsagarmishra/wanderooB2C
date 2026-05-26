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

// ─── Static fallback from config.php ───
$matchedPkg = null;
if (!$dbPackage && !empty($destSlug) && isset($destinations[$destSlug])) {
    foreach ($destinations[$destSlug]['packages'] as $pkg) {
        if (slugify($pkg['title']) === slugify($tourSlug) || strtolower($pkg['title']) === strtolower($tourSlug)) {
            $matchedPkg = $pkg;
            break;
        }
    }
}

// ─── Determine what to render ───
$isDynamic = $dbPackage !== null;

if ($isDynamic) {
    $tourTitle = $dbPackage['title'];
    $pageTitle = $tourTitle;
    $pageDesc = !empty($dbPackage['description']) ? $dbPackage['description'] : "Welcome to " . htmlspecialchars($tourTitle) . " – a custom package in " . htmlspecialchars(ucfirst($destSlug)) . " curated by Wanderoo.";
    $mainImg = !empty($dbPackage['hero_image']) ? SITE_PATH . '/' . $dbPackage['hero_image'] : SITE_PATH . '/assets/img/hero-bg.webp';
    $heroForBanner = $mainImg;
    $tourDuration = $dbPackage['duration'];
    $tourDescription = $dbPackage['description'];
    $tourOverview = $dbPackage['overview'];
    $tourPrice = $dbPackage['price'];
    $tourOldPrice = $dbPackage['old_price'];
    $tourSaveText = $dbPackage['save_text'];
    $tourRating = $dbPackage['rating'];
    $tourRatingCount = $dbPackage['rating_count'];
} elseif ($matchedPkg) {
    $tourTitle = $matchedPkg['title'];
    $pageTitle = $tourTitle;
    $pageDesc = "Welcome to " . htmlspecialchars($tourTitle) . " – a custom package in " . htmlspecialchars(ucfirst($destSlug)) . " curated by Wanderoo.";
    $mainImg = $matchedPkg['img'];
    $heroForBanner = SITE_PATH . '/assets/img/hero-bg.webp';
    $tourDuration = $matchedPkg['duration'] ?? '';
    $tourDescription = '';
    $tourOverview = '';
    $tourPrice = $matchedPkg['price'];
    $tourOldPrice = $matchedPkg['old_price'] ?? '';
    $tourSaveText = $matchedPkg['save'] ?? '';
    $tourRating = $matchedPkg['rating'] ?? '4.5';
    $tourRatingCount = $matchedPkg['rating_count'] ?? '0';
} else {
    // Default fallback
    $tourTitle = "Luxury Honeymoon at Adaaran Prestige";
    $pageTitle = $tourTitle;
    $pageDesc = "Welcome to " . htmlspecialchars($tourTitle) . " – a luxurious escape in Maldives curated by Wanderoo.";
    $mainImg = "https://images.unsplash.com/photo-1544550581-5f7ceaf7f992?auto=format&fit=crop&q=80&w=800";
    $heroForBanner = SITE_PATH . '/assets/img/hero-bg.webp';
    $tourDuration = '4D/3N';
    $tourDescription = '';
    $tourOverview = '';
    $tourPrice = 'INR 80,000';
    $tourOldPrice = 'INR 95,000';
    $tourSaveText = 'SAVE INR 15,000';
    $tourRating = '4.7';
    $tourRatingCount = '28';
}

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

include 'includes/header.php';
?>

    <div class="detail-hero-banner">
    <img src="<?php echo htmlspecialchars($heroForBanner); ?>" alt="Travel Destination" class="detail-hero-bg">
    <div class="detail-hero-overlay"></div>
    
    <div class="detail-gallery">
        <div class="detail-gallery-main">
            <img src="<?php echo htmlspecialchars($mainImg); ?>" alt="<?php echo htmlspecialchars($tourTitle); ?>" class="detail-gallery-img">
            <button class="btn-view-all-images mobile-btn-view-all">View All Images</button>
        </div>
        <?php if ($isDynamic && !empty($dbPhotos)): ?>
            <?php $thumbPhotos = array_slice($dbPhotos, 0, 4); ?>
            <?php foreach ($thumbPhotos as $idx => $photo): ?>
                <div class="detail-gallery-thumb">
                    <img src="<?php echo SITE_PATH; ?>/<?php echo htmlspecialchars($photo['image_path']); ?>" alt="<?php echo htmlspecialchars($photo['alt_text']); ?>" class="detail-gallery-img">
                    <?php if ($idx === count($thumbPhotos) - 1): ?>
                        <button class="btn-view-all-images">View All Images</button>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="detail-gallery-thumb">
                <img src="https://images.unsplash.com/photo-1514282401047-d79a71a590e8?auto=format&fit=crop&q=80&w=600" alt="Honeymoon hammock" class="detail-gallery-img">
            </div>
            <div class="detail-gallery-thumb">
                <img src="https://images.unsplash.com/photo-1507525428034-b723cf961d3e?auto=format&fit=crop&q=80&w=600" alt="Maldives Beach" class="detail-gallery-img">
            </div>
            <div class="detail-gallery-thumb">
                <img src="https://images.unsplash.com/photo-1573843225804-bbad83002646?auto=format&fit=crop&q=80&w=600" alt="Couple in Sea" class="detail-gallery-img">
            </div>
            <div class="detail-gallery-thumb">
                <img src="https://images.unsplash.com/photo-1506929197414-435728669527?auto=format&fit=crop&q=80&w=600" alt="Water villas" class="detail-gallery-img">
                <button class="btn-view-all-images">View All Images</button>
            </div>
        <?php endif; ?>
    </div>
</div>

<div class="detail-container">
    <div class="detail-layout">
        <!-- Main Content Column -->
        <div class="detail-main-content">
            <!-- Badges -->
            <div class="detail-badges">
                <?php if ($isDynamic): ?>
                    <?php if (!empty($tourDuration)): ?>
                        <span class="detail-badge"><?php echo htmlspecialchars($tourDuration); ?></span>
                    <?php endif; ?>
                    <?php foreach ($dbTags as $tag): ?>
                        <span class="detail-badge"><?php echo htmlspecialchars($tag); ?></span>
                    <?php endforeach; ?>
                <?php elseif ($matchedPkg): ?>
                    <span class="detail-badge"><?php echo htmlspecialchars($matchedPkg['duration']); ?></span>
                    <?php foreach ($matchedPkg['tags'] as $tag): ?>
                        <span class="detail-badge"><?php echo htmlspecialchars($tag); ?></span>
                    <?php endforeach; ?>
                <?php else: ?>
                    <span class="detail-badge">4D/3N</span>
                    <span class="detail-badge">Honeymoon Alone</span>
                    <span class="detail-badge">Floating Breakfast</span>
                    <span class="detail-badge">Dolphin Safari</span>
                    <span class="detail-badge">Sandbank Lunch</span>
                <?php endif; ?>
            </div>
            
            <!-- Title -->
            <h1 class="detail-title"><?php echo htmlspecialchars($tourTitle); ?></h1>
            
            <!-- Subdescription -->
            <?php if ($isDynamic && !empty($tourDescription)): ?>
                <p class="detail-desc"><?php echo nl2br(htmlspecialchars($tourDescription)); ?></p>
            <?php elseif (!$isDynamic): ?>
                <p class="detail-desc">
                    Welcome to Adaaran Prestige Vadoo – a luxurious adults-only escape in the Maldives, where romance meets all-inclusive indulgence.
                </p>
            <?php endif; ?>
            
            <!-- Overview -->
            <?php if ($isDynamic && !empty($tourOverview)): ?>
                <h3>Overview:</h3>
                <p class="detail-desc"><?php echo nl2br(htmlspecialchars($tourOverview)); ?></p>
            <?php elseif (!$isDynamic): ?>
                <h3>Overview:</h3>
                <p class="detail-desc">
                    Set in the heart of the Indian Ocean, Adaaran Prestige Vadoo offers the ultimate private island experience for couples seeking luxury and serenity. With direct lagoon access from your villa, floating breakfasts, candlelit beach dinners, and a personal butler to anticipate your every need, every detail is designed for intimate relaxation. Enjoy curated excursions like sunset cruises and dolphin safaris, and unwind with unlimited gourmet dining, drinks, and water activities. This award-winning all-inclusive resort promises a tailored Maldivian escape unlike any other.
                </p>
            <?php endif; ?>
            
            <!-- Highlights -->
            <?php if ($isDynamic && !empty($dbHighlights)): ?>
                <h3>Highlights</h3>
                <ul class="detail-highlights">
                    <?php foreach ($dbHighlights as $hl): ?>
                        <li>
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg>
                            <?php echo htmlspecialchars($hl); ?>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php elseif (!$isDynamic): ?>
                <h3>Highlights</h3>
                <ul class="detail-highlights">
                    <li>
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg>
                        All-inclusive dining: breakfast, lunch, and dinner
                    </li>
                    <li>
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg>
                        Complimentary speedboat transfers from Male
                    </li>
                    <li>
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg>
                        Floating breakfast and romantic beach dinner (once per stay)
                    </li>
                    <li>
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg>
                        Excursions: sunset photo flip &amp; dolphin safari (once per stay)
                    </li>
                    <li>
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg>
                        Complimentary snorkelling gear &amp; non-motorized watersports (canoe, SUP, catamaran)
                    </li>
                </ul>
            <?php endif; ?>
            
            <!-- Day-wise Itinerary -->
            <?php if ($isDynamic && !empty($dbDays)): ?>
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
                            <div class="itinerary-body" <?php echo $idx === 0 ? 'style="max-height: 500px;"' : ''; ?>>
                                <div class="itinerary-body-content">
                                    <?php echo nl2br(htmlspecialchars($day['day_content'])); ?>
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
            <?php elseif (!$isDynamic): ?>
                <h3>Day-wise:</h3>
                <div class="itinerary-tabs">
                    <button class="itinerary-tab-btn active" data-day="1">Day 01</button>
                    <button class="itinerary-tab-btn" data-day="2">Day 02</button>
                    <button class="itinerary-tab-btn" data-day="3">Day 03</button>
                    <button class="itinerary-tab-btn" data-day="4">Day 04</button>
                </div>
                
                <div class="itinerary-accordion">
                    <div class="itinerary-item active" id="day-1">
                        <div class="itinerary-header">
                            <div class="itinerary-title-area">
                                <span class="itinerary-day-badge">Day 01</span>
                                <span class="itinerary-title-text">Arrival</span>
                            </div>
                            <svg class="itinerary-toggle-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 12 15 18 9"></polyline></svg>
                        </div>
                        <div class="itinerary-body" style="max-height: 500px;">
                            <div class="itinerary-body-content">
                                Welcome to Maldives. Resort check-in. Welcome to Adaaran Prestige Vadoo, an adults-only sanctuary in the Maldives.
                                <div class="itinerary-details-meta">
                                    <span><strong>Accommodation:</strong> Adaaran Prestige Vadoo</span>
                                    <span><strong>Meals:</strong> Breakfast, Lunch, Dinner</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="itinerary-item" id="day-2">
                        <div class="itinerary-header">
                            <div class="itinerary-title-area">
                                <span class="itinerary-day-badge">Day 02</span>
                                <span class="itinerary-title-text">Adaaran Prestige Vadoo</span>
                            </div>
                            <svg class="itinerary-toggle-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 12 15 18 9"></polyline></svg>
                        </div>
                        <div class="itinerary-body">
                            <div class="itinerary-body-content">
                                Enjoy a relaxing day at the resort. Opt for a romantic spa session, lounge on the sun deck, or explore the colorful house reef.
                                <div class="itinerary-details-meta">
                                    <span><strong>Accommodation:</strong> Adaaran Prestige Vadoo</span>
                                    <span><strong>Meals:</strong> Breakfast, Lunch, Dinner</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="itinerary-item" id="day-3">
                        <div class="itinerary-header">
                            <div class="itinerary-title-area">
                                <span class="itinerary-day-badge">Day 03</span>
                                <span class="itinerary-title-text">Adaaran Prestige Vadoo</span>
                            </div>
                            <svg class="itinerary-toggle-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 12 15 18 9"></polyline></svg>
                        </div>
                        <div class="itinerary-body">
                            <div class="itinerary-body-content">
                                Experience a floating breakfast in your private pool, followed by a guided dolphin safari in the afternoon.
                                <div class="itinerary-details-meta">
                                    <span><strong>Accommodation:</strong> Adaaran Prestige Vadoo</span>
                                    <span><strong>Meals:</strong> Breakfast, Lunch, Dinner</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="itinerary-item" id="day-4">
                        <div class="itinerary-header">
                            <div class="itinerary-title-area">
                                <span class="itinerary-day-badge">Day 04</span>
                                <span class="itinerary-title-text">Departure</span>
                            </div>
                            <svg class="itinerary-toggle-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 12 15 18 9"></polyline></svg>
                        </div>
                        <div class="itinerary-body">
                            <div class="itinerary-body-content">
                                Check out of the resort. Board your speedboat back to Male International Airport.
                                <div class="itinerary-details-meta">
                                    <span><strong>Accommodation:</strong> None</span>
                                    <span><strong>Meals:</strong> Breakfast</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
            
            <!-- Inclusions & Exclusions Box -->
            <?php if ($isDynamic && (!empty($dbInclusions) || !empty($dbExclusions))): ?>
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
                                            <?php echo htmlspecialchars($inc); ?>
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
                                            <?php echo htmlspecialchars($exc); ?>
                                        </li>
                                    <?php endforeach; ?>
                                </ul>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            <?php elseif (!$isDynamic): ?>
                <div class="detail-inclusions-box">
                    <h3>What's inside the package?:</h3>
                    <div class="detail-inclusions-columns">
                        <div class="detail-inclusions-col inclusions">
                            <h4>Inclusions</h4>
                            <ul>
                                <li><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg> Accommodation</li>
                                <li><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg> All Meals</li>
                                <li><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg> Airport transfers</li>
                                <li><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg> Airport Assistance</li>
                                <li><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg> Government Taxes</li>
                                <li><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg> Water Sports</li>
                            </ul>
                        </div>
                        <div class="detail-inclusions-col exclusions">
                            <h4>Exclusions</h4>
                            <ul>
                                <li><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg> Cooking Class</li>
                                <li><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg> Sunset Cruise</li>
                                <li><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg> Beach Swing</li>
                                <li><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg> Snorkeling</li>
                                <li><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg> Water Sports</li>
                            </ul>
                        </div>
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

                <button class="btn-quote btn-enquire sidebar-cta-btn" data-destination="<?php echo htmlspecialchars($destSlug); ?>">
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

<!-- Mobile Sticky CTA Bar -->
<div class="mobile-sticky-cta">
    <a href="#" class="btn-craft-trip" data-destination="<?php echo htmlspecialchars($destSlug); ?>">Craft Your Trip</a>
</div>

<?php include 'includes/footer.php'; ?>
