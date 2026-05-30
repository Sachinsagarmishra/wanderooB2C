<?php
$pageTitle = "Wanderoo - Premium Handpicked Getaways & Luxury Honeymoons";
$pageDesc = "Discover handpicked luxury getaways, romantic honeymoon packages, and custom travel itineraries to Singapore, Maldives, Bali, Japan, and Sri Lanka with Wanderoo.";
$bodyClass = "home-page";
include_once 'includes/header.php';

function home_package_card_image_url($path) {
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

function home_media_image_url($path) {
    if (empty($path)) {
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

function home_fetch_package_tags($pdo, $packageId) {
    $stmtTags = $pdo->prepare("SELECT tag_name FROM package_tags WHERE package_id = ? ORDER BY id");
    $stmtTags->execute([$packageId]);
    return $stmtTags->fetchAll(PDO::FETCH_COLUMN);
}

function home_fetch_package_images($pdo, $pkg) {
    $images = [];
    $alts = [];

    $heroUrl = home_package_card_image_url($pkg['hero_image'] ?? '');
    if (!empty($heroUrl)) {
        $images[] = $heroUrl;
        $alts[] = !empty($pkg['hero_image_alt']) ? $pkg['hero_image_alt'] : $pkg['title'];
    }

    $stmtPhoto = $pdo->prepare("SELECT image_path, alt_text FROM package_photos WHERE package_id = ? ORDER BY sort_order");
    $stmtPhoto->execute([$pkg['id']]);
    foreach ($stmtPhoto->fetchAll() as $photo) {
        $photoUrl = home_package_card_image_url($photo['image_path'] ?? '');
        if (empty($photoUrl) || in_array($photoUrl, $images, true)) {
            continue;
        }
        $images[] = $photoUrl;
        $alts[] = !empty($photo['alt_text']) ? $photo['alt_text'] : $pkg['title'];
    }

    return [$images, $alts];
}

function home_render_package_card($pdo, $pkg, $whatsappNum, $extraClass = '') {
    $tags = home_fetch_package_tags($pdo, intval($pkg['id']));
    [$cardImages, $cardAlts] = home_fetch_package_images($pdo, $pkg);
    $cardImg = $cardImages[0] ?? '';
    $cardAlt = $cardAlts[0] ?? $pkg['title'];
    $destinationSlug = $pkg['destination'];
    $packageUrl = SITE_PATH . '/' . htmlspecialchars($destinationSlug) . '/' . htmlspecialchars($pkg['slug']);
    $requestScheme = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
    $packageAbsUrl = $requestScheme . '://' . ($_SERVER['HTTP_HOST'] ?? '') . SITE_PATH . '/' . $destinationSlug . '/' . $pkg['slug'];
    $whatsappMessage = "Hi Wanderoo, I came from a package card CTA.\nPackage: " . $pkg['title'] . "\nLink: " . $packageAbsUrl;
    $whatsappUrl = 'https://wa.me/' . preg_replace('/\D/', '', $whatsappNum) . '?text=' . urlencode($whatsappMessage);
    $className = trim('package-card ' . $extraClass);
    ?>
    <div class="<?php echo htmlspecialchars($className); ?>" data-card-images="<?php echo htmlspecialchars(json_encode($cardImages)); ?>" data-card-alts="<?php echo htmlspecialchars(json_encode($cardAlts)); ?>">
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
                <span class="duration"><?php echo htmlspecialchars($pkg['duration']); ?></span>
                <span class="rating"><span class="star">★</span> <?php echo htmlspecialchars($pkg['rating']); ?> <span class="count">(<?php echo htmlspecialchars($pkg['rating_count']); ?>)</span></span>
            </div>
            <h3><a href="<?php echo $packageUrl; ?>" style="color: inherit; text-decoration: none;"><?php echo htmlspecialchars($pkg['title']); ?></a></h3>
            <div class="card-tags">
                <?php foreach ($tags as $tagName): ?>
                    <span class="tag"><?php echo htmlspecialchars($tagName); ?></span>
                <?php endforeach; ?>
            </div>
            <div class="price-section">
                <?php if (!empty($pkg['old_price'])): ?>
                    <div class="old-price-row">
                        <span class="old-price"><?php echo htmlspecialchars($pkg['old_price']); ?></span>
                        <?php if (!empty($pkg['save_text'])): ?>
                            <span class="save-badge"><?php echo htmlspecialchars($pkg['save_text']); ?></span>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
                <div class="new-price-row">
                    <span class="current-price"><?php echo htmlspecialchars($pkg['price']); ?></span>
                    <span class="per-adult">/Adult</span>
                </div>
            </div>
            <div class="card-actions">
                <a href="<?php echo htmlspecialchars($whatsappUrl); ?>" class="btn-phone" target="_blank" rel="noopener" title="Ask on WhatsApp">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path></svg>
                </a>
                <a href="#" class="btn-request btn-enquire" data-destination="<?php echo htmlspecialchars($destinationSlug); ?>" data-package="<?php echo htmlspecialchars($pkg['title']); ?>">Get a quote</a>
            </div>
        </div>
    </div>
    <?php
}

$homeWhatsappNum = preg_replace('/\\D/', '', get_setting('contact_whatsapp', '919113515462'));

try {
    $stmtHomeHoneymoon = $pdo->query("SELECT DISTINCT p.* FROM tour_packages p LEFT JOIN package_tags t ON t.package_id = p.id WHERE p.status = 'active' AND (p.title LIKE '%honeymoon%' OR p.title LIKE '%couple%' OR p.title LIKE '%romantic%' OR t.tag_name LIKE '%honeymoon%' OR t.tag_name LIKE '%couple%' OR t.tag_name LIKE '%romantic%') ORDER BY p.created_at DESC LIMIT 10");
    $homeHoneymoonPackages = $stmtHomeHoneymoon->fetchAll();
    if (empty($homeHoneymoonPackages)) {
        $stmtHomeHoneymoon = $pdo->query("SELECT * FROM tour_packages WHERE status = 'active' ORDER BY created_at DESC LIMIT 10");
        $homeHoneymoonPackages = $stmtHomeHoneymoon->fetchAll();
    }
} catch (PDOException $e) {
    $homeHoneymoonPackages = [];
}

try {
    $stmtHomeDests = $pdo->query("SELECT d.slug, d.name FROM destinations d INNER JOIN tour_packages p ON p.destination = d.slug AND p.status = 'active' GROUP BY d.id, d.slug, d.name, d.sort_order ORDER BY d.sort_order, d.name");
    $homeDestinations = $stmtHomeDests->fetchAll();
} catch (PDOException $e) {
    $homeDestinations = [];
}

try {
    $stmtHomeTestimonials = $pdo->query("SELECT * FROM testimonials WHERE status = 'active' ORDER BY sort_order, created_at DESC");
    $homeTestimonials = $stmtHomeTestimonials->fetchAll();
} catch (PDOException $e) {
    $homeTestimonials = [];
}
?>

<main>
    <section class="hero">
        <img src="<?php echo SITE_PATH; ?>/assets/img/hero-bg.webp" alt="Travel Destination" class="hero-bg">
        <div class="hero-overlay"></div>

        <div class="hero-content">
            <div class="social-proof">
                <img src="<?php echo SITE_PATH; ?>/assets/img/trustedby.png?v=1.1" alt="Trusted by 400+ Tourists" class="trusted-by-img">
            </div>

            <h1 class="hero-title">Your Dream Holiday<br> <span class="playfair italic">Perfectly Planned</span></h1>

            <p class="hero-subtitle">We plan, you relax</p>

            <a href="#" class="btn-quote">Get Instant Quote</a>
        </div>
    </section>

    <section class="who-we-are" style="padding: 60px 0px; max-width: 1280px; margin: 0 auto; display: flex; gap: 40px; align-items: flex-start;">
        <div style="flex: 1;">
            <h2 style="line-height: 1.1;"><span class="urbanist">Who</span> <span class="playfair italic">We Are</span></h2>
        </div>
        <div style="flex: 1.5;">
            <p style="font-size: 18px; color: #4a4a4a; font-family: 'Urbanist', sans-serif; letter-spacing: 0.4px; margin-bottom: 15px; font-weight: 500; line-height: 1.4;">
                We're not just another booking site — we're your travel partner. At Wanderoo, you'll have your own dedicated destination expert to plan every step of your trip with care, clarity, and a genuine local touch.
            </p>
            <a href="<?php echo SITE_PATH; ?>/about-us" class="read-more" style="font-weight: 700; display: flex; align-items: center; gap: 8px; font-size: 16px; width: max-content;">
                Read More <img src="<?php echo SITE_PATH; ?>/assets/img/arrow.svg" alt="Arrow" style="height: 14px; width: auto;">
            </a>
        </div>
    </section>

    <div class="divider" style="max-width: 1280px; margin: 0 auto; border-top: 1px solid #eee; height: 1px;"></div>
    <section class="honeymooners" style="padding: 60px 0px; max-width: 1280px; margin: 0 auto;">
        <div class="section-header" style="display: flex; justify-content: space-between; align-items: flex-end; margin-bottom: 40px;">
            <div>
                <h2 style="margin-bottom: 10px;"><span class="urbanist">For</span> <span class="playfair italic">Honeymooners</span></h2>
                <p style="font-size: 18px; color: #4a4a4a; font-family: 'Urbanist', sans-serif; letter-spacing: 0.4px; margin-bottom: 15px; font-weight: 500; line-height: 1.4;">Honeymoons crafted for forever memories.</p>
            </div>
            <a href="#" class="btn-enquire">Enquire Now</a>
        </div>

        <div class="packages-slider-container">
            <div class="slider-arrow slider-arrow-prev">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="15 18 9 12 15 6"></polyline></svg>
            </div>

            <div class="packages-grid">
                <?php foreach ($homeHoneymoonPackages as $homeIndex => $homePkg): ?>
                    <?php home_render_package_card($pdo, $homePkg, $homeWhatsappNum, $homeIndex >= 3 ? 'hidden-mobile' : ''); ?>
                <?php endforeach; ?>
            </div>
            
            <div class="slider-arrow slider-arrow-next">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"></polyline></svg>
            </div>
            
            <div class="slider-dots"></div>
        </div>
    </section>

    <section class="wander-section" style="background-image: url('<?php echo SITE_PATH; ?>/assets/img/patternbg.png');">
        <div class="wander-container">
            <h2 class="wander-title"><span class="urbanist">Where would you</span> <span class="playfair italic">like to wander?</span></h2>
            <p class="wander-subtitle">Handpicked getaways designed for romance, adventure, and slow travel—crafted for memories that last a lifetime</p>
            
            <div class="filter-tabs">
                <?php foreach ($homeDestinations as $destIndex => $homeDest): ?>
                    <button class="tab-btn <?php echo $destIndex === 0 ? 'active' : ''; ?>" data-target="<?php echo htmlspecialchars($homeDest['slug']); ?>"><?php echo htmlspecialchars($homeDest['name']); ?></button>
                <?php endforeach; ?>
            </div>
            
            <?php foreach ($homeDestinations as $destIndex => $homeDest): ?>
                <?php
                $stmtHomeDestPkgs = $pdo->prepare("SELECT * FROM tour_packages WHERE destination = ? AND status = 'active' ORDER BY created_at DESC LIMIT 10");
                $stmtHomeDestPkgs->execute([$homeDest['slug']]);
                $homeDestinationPackages = $stmtHomeDestPkgs->fetchAll();
                ?>
                <div class="category-packages <?php echo $destIndex === 0 ? 'active' : ''; ?>" id="<?php echo htmlspecialchars($homeDest['slug']); ?>">
                    <div class="packages-slider-container">
                        <div class="slider-arrow slider-arrow-prev">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="15 18 9 12 15 6"></polyline></svg>
                        </div>
                        <div class="packages-grid">
                            <?php foreach ($homeDestinationPackages as $homePkg): ?>
                                <?php home_render_package_card($pdo, $homePkg, $homeWhatsappNum); ?>
                            <?php endforeach; ?>
                        </div>
                        <div class="slider-arrow slider-arrow-next">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"></polyline></svg>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </section>
    
    <section class="group-travel-section" style="background-image: url('<?php echo SITE_PATH; ?>/assets/img/patternbg.png');">
        <div class="group-container">
            <div class="group-image-col">
                <img src="<?php echo SITE_PATH; ?>/assets/img/rafting.jpg" alt="Traveling in a Group">
            </div>
            <div class="group-content-col">
                <h2 class="group-title">
                    <span class="urbanist">Traveling in a Group?</span> <span class="playfair italic">We've Got</span><br>
                    <span class="playfair italic">You Covered</span>
                </h2>
                <p>Planning a trip for groups can be overwhelming — from bookings to coordination. We handle it all, so your group can simply show up and enjoy the experience together.</p>
                <ul class="group-features">
                    <li>
                        <svg viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <circle cx="10" cy="10" r="10" fill="var(--primary)"/>
                            <path d="M6 10L9 13L14 7" stroke="#000000" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                        Custom itineraries for teams & groups
                    </li>
                    <li>
                        <svg viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <circle cx="10" cy="10" r="10" fill="var(--primary)"/>
                            <path d="M6 10L9 13L14 7" stroke="#000000" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                        Best-in-market pricing for 10+ travellers
                    </li>
                    <li>
                        <svg viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <circle cx="10" cy="10" r="10" fill="var(--primary)"/>
                            <path d="M6 10L9 13L14 7" stroke="#000000" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                        End-to-end planning & coordination
                    </li>
                    <li>
                        <svg viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <circle cx="10" cy="10" r="10" fill="var(--primary)"/>
                            <path d="M6 10L9 13L14 7" stroke="#000000" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                        Dedicated support throughout your trip
                    </li>
                </ul>
                <a href="#" class="btn-group-plan">Plan A Group Trip</a>
            </div>
        </div>
    </section>
    
    <section class="how-it-works-section">
        <div class="how-it-works-container">
            <h2 class="how-title">
                <span class="urbanist">How it</span> <span class="playfair italic">Works</span>
            </h2>
            
            <!-- Tab Navigation Buttons -->
            <div class="how-tabs">
                <button class="how-tab-btn active" data-tab="1">
                    <span class="tab-num">1</span>
                    <span class="tab-text">Make A Request</span>
                </button>
                <button class="how-tab-btn" data-tab="2">
                    <span class="tab-num">2</span>
                    <span class="tab-text">Meet Your Expert</span>
                </button>
                <button class="how-tab-btn" data-tab="3">
                    <span class="tab-num">3</span>
                    <span class="tab-text">Plan And Book</span>
                </button>
            </div>
            
            <!-- Tab Panels -->
            <div class="how-panels">
                <!-- Panel 1 (Active by default) -->
                <div class="how-panel active" id="how-panel-1" style="display: block;">
                    <div class="panel-content">
                        <div class="panel-left">
                            <div class="panel-item">
                                <span class="panel-icon">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M3 3l7.07 16.97 2.51-7.39 7.39-2.51L3 3z" />
                                        <line x1="13" y1="3" x2="15" y2="1" />
                                        <line x1="3" y1="13" x2="1" y2="15" />
                                        <line x1="10" y1="10" x2="8" y2="8" />
                                    </svg>
                                </span>
                                <p class="panel-text"><strong>Planning a holiday,</strong> honeymoon, or mates' getaway? We're here to listen.</p>
                            </div>
                            <div class="panel-item">
                                <span class="panel-icon">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z" />
                                    </svg>
                                </span>
                                <p class="panel-text"><strong>Fill in a few quick details,</strong> and we'll match you with a destination expert who'll help customise your trip to perfection &mdash; right down to the tiniest detail.</p>
                            </div>
                            <a href="#" class="btn-craft-trip">Craft Your Trip</a>
                        </div>
                        <div class="panel-right">
                            <img src="<?php echo SITE_PATH; ?>/assets/img/balistep1.jpg" alt="How it works">
                        </div>
                    </div>
                </div>
                
                <!-- Panel 2 -->
                <div class="how-panel" id="how-panel-2">
                    <div class="panel-content">
                        <div class="panel-left">
                            <div class="panel-item">
                                <span class="panel-icon">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M3 3l7.07 16.97 2.51-7.39 7.39-2.51L3 3z" />
                                        <line x1="13" y1="3" x2="15" y2="1" />
                                        <line x1="3" y1="13" x2="1" y2="15" />
                                        <line x1="10" y1="10" x2="8" y2="8" />
                                    </svg>
                                </span>
                                <p class="panel-text"><strong>Tell us a few details,</strong> and we'll match you with a destination expert.</p>
                            </div>
                            <div class="panel-item">
                                <span class="panel-icon">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z" />
                                    </svg>
                                </span>
                                <p class="panel-text"><strong>Think of them as your travel companion &mdash;</strong> always ready to listen, support, and make sure your holiday feels truly special.</p>
                            </div>
                            <a href="#" class="btn-craft-trip">Craft Your Trip</a>
                        </div>
                        <div class="panel-right">
                            <img src="<?php echo SITE_PATH; ?>/assets/img/step2hpwitworks.webp" alt="How it works">
                        </div>
                    </div>
                </div>
                
                <!-- Panel 3 -->
                <div class="how-panel" id="how-panel-3">
                    <div class="panel-content">
                        <div class="panel-left">
                            <div class="panel-item">
                                <span class="panel-icon">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M3 3l7.07 16.97 2.51-7.39 7.39-2.51L3 3z" />
                                        <line x1="13" y1="3" x2="15" y2="1" />
                                        <line x1="3" y1="13" x2="1" y2="15" />
                                        <line x1="10" y1="10" x2="8" y2="8" />
                                    </svg>
                                </span>
                                <p class="panel-text"><strong>Together, we'll craft a trip</strong> tailored to you &mdash; down to the last detail.</p>
                            </div>
                            <div class="panel-item">
                                <span class="panel-icon">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z" />
                                    </svg>
                                </span>
                                <p class="panel-text"><strong>Your expert will stay by your side,</strong> ready to address any questions or updates before your trip.</p>
                            </div>
                            <a href="#" class="btn-craft-trip">Craft Your Trip</a>
                        </div>
                        <div class="panel-right">
                            <img src="<?php echo SITE_PATH; ?>/assets/img/step3howitworks.jpg" alt="How it works">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    
    <section class="why-choose-section" style="background-image: url('<?php echo SITE_PATH; ?>/assets/img/patternbg.png');">
        <div class="why-choose-container">
            <div class="section-header" style="display: flex; justify-content: space-between; align-items: flex-end; margin-bottom: 50px;">
                <div>
                    <h2 class="why-choose-title"><span class="urbanist">Why Choose</span> <span class="playfair italic">Wanderoo</span></h2>
                </div>
                <a href="#" class="btn-enquire">Enquire Now</a>
            </div>
            
            <div class="why-choose-grid">
                <!-- Col 1 -->
                <div class="why-choose-card">
                    <div class="why-icon-wrapper">
                        <img src="<?php echo SITE_PATH; ?>/assets/img/Tailored-Just-for-You.svg" alt="Tailored Just For You">
                    </div>
                    <h3>Tailored Just For You</h3>
                    <p>Every itinerary is designed around your pace, interests, and budget.</p>
                </div>
                
                <!-- Col 2 -->
                <div class="why-choose-card">
                    <div class="why-icon-wrapper">
                        <img src="<?php echo SITE_PATH; ?>/assets/img/Dedicated-Destination-Experts.svg" alt="Dedicated Destination Experts">
                    </div>
                    <h3>Dedicated Destination Experts</h3>
                    <p>Real people who know your dream destination inside-out.</p>
                </div>
                
                <!-- Col 3 -->
                <div class="why-choose-card">
                    <div class="why-icon-wrapper">
                        <img src="<?php echo SITE_PATH; ?>/assets/img/Hassle-Free-Experience.svg" alt="Hassle-Free Experience">
                    </div>
                    <h3>Hassle-Free Experience</h3>
                    <p>From flights to local gems, we handle it all so you can just enjoy</p>
                </div>
            </div>
        </div>
    </section>
    
    <?php if (!empty($homeTestimonials)): ?>
    <section class="testimonials-section">
        <div class="testimonials-container">
            <div class="testimonials-header">
                <h2 class="testimonials-title"><span class="urbanist">Testi</span><span class="playfair italic">monials</span></h2>
                <p class="testimonials-subtitle">Don't just take our word for it&mdash;see what other couples are saying</p>
            </div>
            
            <div class="testimonials-slider-wrapper">
                <!-- Prev Arrow -->
                <div class="slider-arrow slider-arrow-prev">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="15 18 9 12 15 6"></polyline></svg>
                </div>
                
                <div class="testimonials-grid-viewport">
                    <div class="testimonials-grid">
                        <?php foreach ($homeTestimonials as $testimonial): ?>
                            <?php
                            $testimonialImage = home_media_image_url($testimonial['image_path'] ?? '');
                            $testimonialAlt = !empty($testimonial['image_alt']) ? $testimonial['image_alt'] : $testimonial['customer_name'];
                            $testimonialRating = max(1, min(5, intval($testimonial['rating'])));
                            ?>
                            <div class="testimonial-card">
                                <div class="testimonial-user">
                                    <?php if (!empty($testimonialImage)): ?>
                                        <img src="<?php echo htmlspecialchars($testimonialImage); ?>" alt="<?php echo htmlspecialchars($testimonialAlt); ?>" class="user-avatar">
                                    <?php endif; ?>
                                    <span class="user-name"><?php echo htmlspecialchars($testimonial['customer_name']); ?></span>
                                </div>
                                <div class="testimonial-rating">
                                    <?php for ($starIndex = 0; $starIndex < $testimonialRating; $starIndex++): ?>
                                        <span class="star">★</span>
                                    <?php endfor; ?>
                                </div>
                                <p class="testimonial-text"><?php echo htmlspecialchars($testimonial['content']); ?></p>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
                
                <!-- Next Arrow -->
                <div class="slider-arrow slider-arrow-next">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"></polyline></svg>
                </div>
            </div>
            
            <div class="slider-dots">
            </div>
        </div>
    </section>
    <?php endif; ?>
    
    <section class="faq-section">
        <div class="faq-container">
            <div class="faq-sidebar">
                <h2 class="faq-title">
                    <span class="urbanist">We've got</span> <span class="playfair italic">answers</span>
                </h2>
                <p class="faq-subtitle">Everything explained. Before you book.</p>
            </div>
            <div class="faq-content">
                <div class="faq-accordion">
                    <!-- Item 1 -->
                    <div class="faq-item active">
                        <div class="faq-header">
                            <span class="faq-question">What Does An Wanderoo Trip Include?</span>
                            <span class="faq-toggle">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                    <line x1="18" y1="6" x2="6" y2="18"></line>
                                    <line x1="6" y1="6" x2="18" y2="18"></line>
                                </svg>
                            </span>
                        </div>
                        <div class="faq-body">
                            <p class="faq-answer">It includes custom planning, accommodations, and expert advice.</p>
                        </div>
                    </div>
                    
                    <!-- Item 2 -->
                    <div class="faq-item">
                        <div class="faq-header">
                            <span class="faq-question">Are International Flights Included?</span>
                            <span class="faq-toggle">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                    <line x1="12" y1="5" x2="12" y2="19"></line>
                                    <line x1="5" y1="12" x2="19" y2="12"></line>
                                </svg>
                            </span>
                        </div>
                        <div class="faq-body">
                            <p class="faq-answer">International flights are not included unless specified.</p>
                        </div>
                    </div>
                    
                    <!-- Item 3 -->
                    <div class="faq-item">
                        <div class="faq-header">
                            <span class="faq-question">What Is A Local Expert?</span>
                            <span class="faq-toggle">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                    <line x1="12" y1="5" x2="12" y2="19"></line>
                                    <line x1="5" y1="12" x2="19" y2="12"></line>
                                </svg>
                            </span>
                        </div>
                        <div class="faq-body">
                            <p class="faq-answer">A vetted local with on-ground knowledge who customizes your trip.</p>
                        </div>
                    </div>
                </div>
                
                <div class="faq-action-row">
                    <a href="#" class="btn-more-faqs">More FAQ's</a>
                </div>
            </div>
        </div>
    </section>
</main>

<?php include_once 'includes/footer.php'; ?>
