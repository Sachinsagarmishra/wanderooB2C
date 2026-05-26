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

function destination_filter_slug($value) {
    return slugify(trim($value));
}

function add_destination_filter_option(&$options, $label) {
    $label = trim($label);
    if ($label === '') {
        return '';
    }

    $slug = destination_filter_slug($label);
    $options[$slug] = $label;
    return $slug;
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

                    $packageFilterMeta = [];
                    $filterOptions = [
                        'city' => [],
                        'occasion' => [],
                        'duration' => [],
                        'inclusive' => []
                    ];

                    if (!empty($dbPkgs)) {
                        $occasionKeywords = ['birthday', 'honeymoon', 'anniversary', 'family', 'couple', 'friends', 'solo', 'group'];
                        foreach ($dbPkgs as $filterPkg) {
                            $pkgId = intval($filterPkg['id']);
                            $stmtMetaTags = $pdo->prepare("SELECT tag_name FROM package_tags WHERE package_id = ? ORDER BY id");
                            $stmtMetaTags->execute([$pkgId]);
                            $metaTags = $stmtMetaTags->fetchAll(PDO::FETCH_COLUMN);

                            $stmtMetaInclusions = $pdo->prepare("SELECT item_text FROM package_inclusions WHERE package_id = ? AND type = 'inclusion' ORDER BY sort_order");
                            $stmtMetaInclusions->execute([$pkgId]);
                            $metaInclusions = $stmtMetaInclusions->fetchAll(PDO::FETCH_COLUMN);

                            $pkgFilters = [
                                'city' => [],
                                'occasion' => [],
                                'duration' => [],
                                'inclusive' => []
                            ];

                            if (!empty($filterPkg['duration'])) {
                                $pkgFilters['duration'][] = add_destination_filter_option($filterOptions['duration'], $filterPkg['duration']);
                            }

                            foreach ($metaTags as $tagValue) {
                                $tagValue = trim($tagValue);
                                if (preg_match('/^from\s+/i', $tagValue)) {
                                    $pkgFilters['city'][] = add_destination_filter_option($filterOptions['city'], $tagValue);
                                }

                                foreach ($occasionKeywords as $keyword) {
                                    if (stripos($tagValue, $keyword) !== false) {
                                        $pkgFilters['occasion'][] = add_destination_filter_option($filterOptions['occasion'], $tagValue);
                                        break;
                                    }
                                }

                                if (preg_match('/inclusive|hotel|flight|resort/i', $tagValue)) {
                                    $pkgFilters['inclusive'][] = add_destination_filter_option($filterOptions['inclusive'], $tagValue);
                                }
                            }

                            foreach ($metaInclusions as $inclusionValue) {
                                if (preg_match('/all\s*inclusive|inclusive|hotel|flight|resort/i', $inclusionValue)) {
                                    $pkgFilters['inclusive'][] = add_destination_filter_option($filterOptions['inclusive'], $inclusionValue);
                                }
                            }

                            $packageFilterMeta[$pkgId] = [
                                'tags' => $metaTags,
                                'filters' => array_map('array_unique', $pkgFilters)
                            ];
                        }
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
                        $whatsappNum = preg_replace('/\D/', '', get_setting('contact_whatsapp', '919113515462'));
                    ?>
                        <div class="destination-package-layout">
                            <aside class="destination-filter-sidebar" aria-label="Package filters">
                                <?php
                                $filterLabels = [
                                    'city' => 'City',
                                    'occasion' => 'Occasion',
                                    'duration' => 'Duration',
                                    'inclusive' => 'Inclusive'
                                ];
                                $filterIcons = [
                                    'city' => '☷',
                                    'occasion' => '◎',
                                    'duration' => '▦',
                                    'inclusive' => '▣'
                                ];
                                foreach ($filterLabels as $filterKey => $filterLabel):
                                    if (empty($filterOptions[$filterKey])) {
                                        continue;
                                    }
                                ?>
                                    <div class="destination-filter-group <?php echo $filterKey === 'city' ? 'active' : ''; ?>" data-filter-group="<?php echo htmlspecialchars($filterKey); ?>">
                                        <button type="button" class="destination-filter-toggle">
                                            <span><span class="filter-icon"><?php echo htmlspecialchars($filterIcons[$filterKey]); ?></span><?php echo htmlspecialchars($filterLabel); ?></span>
                                            <span class="filter-chevron">⌄</span>
                                        </button>
                                        <div class="destination-filter-options">
                                            <?php foreach ($filterOptions[$filterKey] as $optionSlug => $optionLabel): ?>
                                                <button type="button" class="destination-filter-chip" data-filter-key="<?php echo htmlspecialchars($filterKey); ?>" data-filter-value="<?php echo htmlspecialchars($optionSlug); ?>">
                                                    <?php echo htmlspecialchars($optionLabel); ?>
                                                </button>
                                            <?php endforeach; ?>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                                <button type="button" class="destination-filter-clear">Clear Filters</button>
                            </aside>
                            <div class="destination-filter-results">
                    <?php
                        foreach ($dbPkgs as $dbPkg) {
                            $pkgId = intval($dbPkg['id']);
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
                            $dbTagNames = $packageFilterMeta[$pkgId]['tags'] ?? [];
                            $pkgFilters = $packageFilterMeta[$pkgId]['filters'] ?? ['city' => [], 'occasion' => [], 'duration' => [], 'inclusive' => []];
                            $packageUrl = SITE_PATH . '/' . $slug . '/' . $dbPkg['slug'];
                            $requestScheme = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
                            $packageAbsUrl = $requestScheme . '://' . ($_SERVER['HTTP_HOST'] ?? '') . $packageUrl;
                            $whatsappMessage = "Hi Wanderoo, I came from a destination package card CTA.\nPackage: " . $dbPkg['title'] . "\nDestination: " . $dest['name'] . "\nLink: " . $packageAbsUrl;
                            $whatsappUrl = 'https://wa.me/' . $whatsappNum . '?text=' . urlencode($whatsappMessage);
                    ?>
                        <div class="package-card" data-card-images="<?php echo htmlspecialchars(json_encode($cardImages)); ?>" data-card-alts="<?php echo htmlspecialchars(json_encode($cardAlts)); ?>" data-filter-city="<?php echo htmlspecialchars(implode(' ', $pkgFilters['city'])); ?>" data-filter-occasion="<?php echo htmlspecialchars(implode(' ', $pkgFilters['occasion'])); ?>" data-filter-duration="<?php echo htmlspecialchars(implode(' ', $pkgFilters['duration'])); ?>" data-filter-inclusive="<?php echo htmlspecialchars(implode(' ', $pkgFilters['inclusive'])); ?>">
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
                                    <a href="<?php echo htmlspecialchars($whatsappUrl); ?>" class="btn-phone" target="_blank" rel="noopener" title="Ask on WhatsApp">
                                        <img src="<?php echo SITE_PATH; ?>/assets/img/whatsapp.svg" alt="WhatsApp" style="width:28px;height:28px;display:block;">
                                    </a>
                                    <a href="#" class="btn-request btn-enquire" data-destination="<?php echo htmlspecialchars($slug); ?>">Get a quote</a>
                                </div>
                            </div>
                        </div>
                    <?php
                        }
                    ?>
                            <div class="destination-no-filter-results" style="display:none;">No packages match the selected filters.</div>
                            </div>
                        </div>
                    <?php
                    endif;
                    ?>
                </div>
            </div>
        </div>
    </div>
</main>

<?php include_once 'includes/footer.php'; ?>
