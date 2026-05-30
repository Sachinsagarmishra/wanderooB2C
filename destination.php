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
                    Learn More
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
                        'occasion' => [
                            'honeymoon' => 'Honeymoon',
                            'family-holiday' => 'Family Holiday',
                            'anniversary' => 'Anniversary',
                            'birthday' => 'Birthday',
                            'baby-moon' => 'Baby Moon',
                            'others' => 'Others'
                        ],
                        'duration' => [],
                        'inclusive' => []
                    ];

                    if (!empty($dbPkgs)) {
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

                                // Occasion Mapping
                                if (stripos($tagValue, 'honeymoon') !== false || stripos($tagValue, 'couple') !== false || stripos($tagValue, 'romantic') !== false) {
                                    $pkgFilters['occasion'][] = 'honeymoon';
                                }
                                if (stripos($tagValue, 'family') !== false || stripos($tagValue, 'kid') !== false) {
                                    $pkgFilters['occasion'][] = 'family-holiday';
                                }
                                if (stripos($tagValue, 'anniversary') !== false) {
                                    $pkgFilters['occasion'][] = 'anniversary';
                                }
                                if (stripos($tagValue, 'birthday') !== false) {
                                    $pkgFilters['occasion'][] = 'birthday';
                                }
                                if (stripos($tagValue, 'baby') !== false || stripos($tagValue, 'babymoon') !== false) {
                                    $pkgFilters['occasion'][] = 'baby-moon';
                                }
                                if (stripos($tagValue, 'friends') !== false || stripos($tagValue, 'solo') !== false || stripos($tagValue, 'group') !== false || stripos($tagValue, 'other') !== false) {
                                    $pkgFilters['occasion'][] = 'others';
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
                        <style>
                        .empty-dest-wrapper {
                            display: flex;
                            gap: 40px;
                            background: #ffffff;
                            border: 1px solid #e2e8f0;
                            border-radius: 24px;
                            padding: 40px;
                            margin-top: 20px;
                            box-shadow: 0 10px 30px rgba(0,0,0,0.02);
                            flex-wrap: wrap;
                            box-sizing: border-box;
                            width: 100%;
                        }
                        .empty-dest-info {
                            flex: 1;
                            min-width: 300px;
                            display: flex;
                            flex-direction: column;
                            justify-content: center;
                            font-family: 'Urbanist', sans-serif;
                            box-sizing: border-box;
                        }
                        .empty-dest-form-card {
                            flex: 1.2;
                            min-width: 320px;
                            background: #f8fafc;
                            border-radius: 20px;
                            padding: 30px;
                            border: 1px solid #edf2f7;
                            font-family: 'Urbanist', sans-serif;
                            box-sizing: border-box;
                        }
                        .empty-dest-form-grid-row {
                            display: grid;
                            grid-template-columns: 1fr 1fr;
                            gap: 15px;
                        }
                        @media (max-width: 768px) {
                            .empty-dest-wrapper {
                                padding: 24px 16px !important;
                                gap: 24px !important;
                                border-radius: 16px !important;
                            }
                            .empty-dest-info {
                                min-width: 100% !important;
                            }
                            .empty-dest-form-card {
                                min-width: 100% !important;
                                padding: 20px 16px !important;
                            }
                            .empty-dest-form-grid-row {
                                grid-template-columns: 1fr !important;
                                gap: 16px !important;
                            }
                        }
                        </style>

                        <div class="empty-dest-container" style="grid-column: 1 / -1; width: 100%; box-sizing: border-box;">
                            <div class="empty-dest-wrapper">
                                <!-- Left Info Block -->
                                <div class="empty-dest-info">
                                    <span style="color: #FFb800; font-weight: 800; font-size: 14px; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 12px; display: inline-block; background: rgba(255,222,89,0.1); padding: 6px 12px; border-radius: 20px; width: fit-content;">Bespoke Travel Planning</span>
                                    <h3 style="font-family: 'Playfair Display', serif; font-size: 32px; font-style: italic; font-weight: 700; color: #1e293b; margin: 0 0 16px 0; line-height: 1.2;">Plan a Custom Trip to <?php echo htmlspecialchars($dest['name']); ?></h3>
                                    <p style="font-size: 16px; color: #475569; line-height: 1.6; margin: 0 0 24px 0;">Experience <?php echo htmlspecialchars($dest['name']); ?> exactly the way you envision it. Our destination experts craft 100% tailor-made holidays designed around your interests, pace, and budget—complete with handpicked accommodations, unique experiences, and dedicated support throughout your journey.</p>
                                    
                                    <ul style="list-style: none; padding: 0; margin: 0; display: flex; flex-direction: column; gap: 14px;">
                                        <li style="display: flex; align-items: center; gap: 10px; font-size: 15px; font-weight: 600; color: #334155;">
                                            <span style="display: flex; align-items: center; justify-content: center; width: 22px; height: 22px; border-radius: 50%; background: #4ade80; color: #fff; font-size: 12px;">✓</span>
                                            100% Customized to your pace & budget
                                        </li>
                                        <li style="display: flex; align-items: center; gap: 10px; font-size: 15px; font-weight: 600; color: #334155;">
                                            <span style="display: flex; align-items: center; justify-content: center; width: 22px; height: 22px; border-radius: 50%; background: #4ade80; color: #fff; font-size: 12px;">✓</span>
                                            Vetted local stays, activities & transfers
                                        </li>
                                        <li style="display: flex; align-items: center; gap: 10px; font-size: 15px; font-weight: 600; color: #334155;">
                                            <span style="display: flex; align-items: center; justify-content: center; width: 22px; height: 22px; border-radius: 50%; background: #4ade80; color: #fff; font-size: 12px;">✓</span>
                                            24/7 support throughout your holiday
                                        </li>
                                    </ul>
                                </div>

                                <!-- Right Form Block -->
                                <div class="empty-dest-form-card">
                                    <h4 style="font-size: 18px; font-weight: 750; color: #0f172a; margin: 0 0 8px 0;">Request a Free Callback</h4>
                                    <p style="font-size: 13px; color: #64748b; margin: 0 0 20px 0;">Fill in your details and our expert will get in touch with you shortly.</p>

                                    <form id="destCustomEnquiryForm" style="display: flex; flex-direction: column; gap: 16px;">
                                        <?php csrf_input(); ?>
                                        <input type="hidden" name="destination" value="<?php echo htmlspecialchars($slug); ?>">
                                        <input type="hidden" name="source_page" value="<?php 
                                            $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? 'https://' : 'http://';
                                            echo $protocol . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']; 
                                        ?>">

                                        <div style="display: none; background-color: #fee2e2; border: 1px solid #fecaca; color: #ef4444; padding: 12px 16px; border-radius: 12px; font-size: 13px; font-weight: 600; box-sizing: border-box;" id="destFormError"></div>

                                        <div style="display: flex; flex-direction: column; gap: 6px;">
                                            <label style="font-size: 13px; font-weight: 700; color: #475569;" for="destFormName">Full Name*</label>
                                            <input style="width: 100%; padding: 12px 16px; border: 1.5px solid #cbd5e1; border-radius: 12px; font-size: 15px; outline: none; background: #ffffff; box-sizing: border-box;" type="text" id="destFormName" name="fullname" placeholder="Enter your full name" required>
                                        </div>

                                        <div class="empty-dest-form-grid-row">
                                            <div style="display: flex; flex-direction: column; gap: 6px;">
                                                <label style="font-size: 13px; font-weight: 700; color: #475569;" for="destFormEmail">Email Address*</label>
                                                <input style="width: 100%; padding: 12px 16px; border: 1.5px solid #cbd5e1; border-radius: 12px; font-size: 15px; outline: none; background: #ffffff; box-sizing: border-box;" type="email" id="destFormEmail" name="email" placeholder="Enter email address" required>
                                            </div>
                                            <div style="display: flex; flex-direction: column; gap: 6px;">
                                                <label style="font-size: 13px; font-weight: 700; color: #475569;" for="destFormPhone">WhatsApp / Phone*</label>
                                                <input style="width: 100%; padding: 12px 16px; border: 1.5px solid #cbd5e1; border-radius: 12px; font-size: 15px; outline: none; background: #ffffff; box-sizing: border-box;" type="tel" id="destFormPhone" name="phone" placeholder="Enter contact number" required>
                                            </div>
                                        </div>

                                        <div class="empty-dest-form-grid-row">
                                            <div style="display: flex; flex-direction: column; gap: 6px;">
                                                <label style="font-size: 13px; font-weight: 700; color: #475569;" for="destFormDate">Travel Date</label>
                                                <input style="width: 100%; padding: 12px 16px; border: 1.5px solid #cbd5e1; border-radius: 12px; font-size: 15px; outline: none; background: #ffffff; box-sizing: border-box;" type="date" id="destFormDate" name="departure_date" min="<?php echo date('Y-m-d'); ?>">
                                            </div>
                                            <div style="display: flex; flex-direction: column; gap: 6px;">
                                                <label style="font-size: 13px; font-weight: 700; color: #475569;" for="destFormNights">Duration (Nights)</label>
                                                <input style="width: 100%; padding: 12px 16px; border: 1.5px solid #cbd5e1; border-radius: 12px; font-size: 15px; outline: none; background: #ffffff; box-sizing: border-box;" type="number" id="destFormNights" name="nights" min="1" placeholder="e.g. 5">
                                            </div>
                                        </div>

                                        <!-- Cloudflare Turnstile CAPTCHA -->
                                        <div style="margin-top: 10px; margin-bottom: 10px;">
                                            <div class="cf-turnstile"
                                                 id="destTurnstile"
                                                 data-sitekey="<?php echo TURNSTILE_SITE_KEY; ?>"
                                                 data-callback="onDestTurnstileSuccess"
                                                 data-expired-callback="onDestTurnstileExpired"
                                                 data-error-callback="onDestTurnstileError">
                                            </div>
                                        </div>

                                        <button type="submit" id="destFormSubmitBtn" style="background-color: var(--primary, #FFDE59); color: #111111; border: none; border-radius: 30px; padding: 14px; font-size: 16px; font-weight: 750; cursor: pointer; box-shadow: 0 6px 20px rgba(255, 222, 89, 0.2); transition: background-color 0.2s, transform 0.2s; margin-top: 10px; font-family: inherit; width: 100%;">Get a Callback</button>
                                    </form>

                                    <!-- Success Screen inside Card -->
                                    <div id="destFormSuccess" style="display: none; flex-direction: column; align-items: center; text-align: center; padding: 20px 0;">
                                        <div style="font-size: 48px; margin-bottom: 12px; display: block;">🎉</div>
                                        <h4 style="font-size: 22px; font-weight: 800; color: #16a34a; margin: 0 0 10px 0;">Request Received!</h4>
                                        <p style="font-size: 14px; color: #475569; line-height: 1.5; font-weight: 600; margin: 0;">Thank you for getting in touch. One of our destination experts will call or WhatsApp you shortly to plan your custom <?php echo htmlspecialchars($dest['name']); ?> trip.</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- AJAX form script -->
                        <script>
                        document.addEventListener('DOMContentLoaded', () => {
                            const form = document.getElementById('destCustomEnquiryForm');
                            const submitBtn = document.getElementById('destFormSubmitBtn');
                            const errorBanner = document.getElementById('destFormError');
                            const successScreen = document.getElementById('destFormSuccess');

                            // Define Turnstile callbacks
                            window.onDestTurnstileSuccess = function(token) {
                                errorBanner.style.display = 'none';
                            };
                            window.onDestTurnstileExpired = function() {
                                if (typeof turnstile !== 'undefined') {
                                    try { turnstile.reset('#destTurnstile'); } catch(e) {}
                                }
                            };
                            window.onDestTurnstileError = function() {
                                showFormError('CAPTCHA failed to load. Please refresh the page and try again.');
                            };

                            if (form) {
                                form.addEventListener('submit', (e) => {
                                    e.preventDefault();
                                    
                                    const name = document.getElementById('destFormName').value.trim();
                                    const email = document.getElementById('destFormEmail').value.trim();
                                    const phone = document.getElementById('destFormPhone').value.trim();
                                    
                                    if (!name || !email || !phone) {
                                        showFormError('Please fill in all required fields.');
                                        return;
                                    }

                                    // Email validation
                                    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                                    if (!emailRegex.test(email)) {
                                        showFormError('Please enter a valid email address.');
                                        return;
                                    }

                                    // Phone digits validation
                                    const digits = phone.replace(/\D/g, '');
                                    if (digits.length < 7 || digits.length > 15) {
                                        showFormError('Please enter a valid phone number (between 7 and 15 digits).');
                                        return;
                                    }

                                    // Check Turnstile token
                                    const turnstileInput = document.querySelector('#destTurnstile [name="cf-turnstile-response"]');
                                    if (!turnstileInput || !turnstileInput.value) {
                                        showFormError('Please complete the CAPTCHA verification.');
                                        return;
                                    }

                                    submitBtn.disabled = true;
                                    const originalText = submitBtn.textContent;
                                    submitBtn.textContent = 'Submitting...';

                                    const formData = new FormData(form);

                                    fetch('<?php echo SITE_PATH; ?>/submit-popup.php', {
                                        method: 'POST',
                                        body: formData
                                    })
                                    .then(response => response.json())
                                    .then(data => {
                                        submitBtn.textContent = originalText;
                                        submitBtn.disabled = false;
                                        
                                        if (data.success) {
                                            // Set flag to suppress timed-popup form
                                            localStorage.setItem('timed_lead_submitted', 'true');
                                            sessionStorage.setItem('timed_lead_submitted', 'true');
                                            
                                            // Show success screen
                                            form.style.display = 'none';
                                            successScreen.style.display = 'flex';
                                        } else {
                                            showFormError(data.error || 'Failed to submit form. Please try again.');
                                            if (typeof turnstile !== 'undefined') {
                                                try { turnstile.reset('#destTurnstile'); } catch(e) {}
                                            }
                                        }
                                    })
                                    .catch(err => {
                                        submitBtn.textContent = originalText;
                                        submitBtn.disabled = false;
                                        console.error('Submission error:', err);
                                        showFormError('An error occurred during submission. Please try again.');
                                        if (typeof turnstile !== 'undefined') {
                                            try { turnstile.reset('#destTurnstile'); } catch(e) {}
                                        }
                                    });
                                });

                                const inputs = form.querySelectorAll('input');
                                inputs.forEach(input => {
                                    input.addEventListener('input', () => {
                                        errorBanner.style.display = 'none';
                                    });
                                });
                            }

                            function showFormError(msg) {
                                errorBanner.textContent = msg;
                                errorBanner.style.display = 'block';
                            }
                        });
                        </script>
                    <?php
                    else:
                        $whatsappNum = preg_replace('/\D/', '', get_setting('contact_whatsapp', '919113515462'));
                    ?>
                        <div class="destination-package-layout">
                            <aside class="destination-filter-sidebar" aria-label="Package filters">
                                <div class="mobile-filter-header-nav">
                                    <div class="mobile-filter-title">
                                        <svg class="binoculars-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M10 10h4M12 10V6M9 6h6"/>
                                            <circle cx="7" cy="15" r="3"/>
                                            <circle cx="17" cy="15" r="3"/>
                                            <path d="M10 15h4"/>
                                        </svg>
                                        Explore by
                                    </div>
                                </div>
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
                                <button type="button" class="destination-filter-apply">Apply Filters</button>
                                <button type="button" class="destination-filter-clear">Clear Filters</button>
                                <div class="mobile-filter-footer-nav">
                                    <button type="button" class="btn-mobile-filter-close">
                                        <svg viewBox="0 0 24 24" width="24" height="24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                                    </button>
                                </div>
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
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path></svg>
                                    </a>
                                    <a href="#" class="btn-request btn-enquire" data-destination="<?php echo htmlspecialchars($slug); ?>" data-package="<?php echo htmlspecialchars($dbPkg['title']); ?>">Get a quote</a>
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

<!-- Mobile Sticky CTA Bar -->
<div class="mobile-filter-trigger-bar">
    <button type="button" class="btn-mobile-filter-trigger">
        <svg class="binoculars-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
            <path d="M10 10h4M12 10V6M9 6h6"/>
            <circle cx="7" cy="15" r="3"/>
            <circle cx="17" cy="15" r="3"/>
            <path d="M10 15h4"/>
        </svg>
        Explore by
        <span class="filter-chevron" style="display: inline-block; margin-left: 2px;">⌄</span>
    </button>
</div>

<div class="mobile-sticky-cta">
    <a href="#" class="btn-quote btn-enquire btn-craft-trip" data-destination="<?php echo htmlspecialchars($slug); ?>">Craft your trip</a>
</div>

<?php include_once 'includes/footer.php'; ?>
