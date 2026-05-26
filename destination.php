<?php
// Dynamic Destination Page Template
require_once __DIR__ . '/config.php';

// Define destinations data
$destinations = [
    'singapore' => [
        'name' => 'Singapore',
        'title' => 'Singapore Packages',
        'breadcrumb' => 'Singapore',
        'hero_bg' => 'https://images.unsplash.com/photo-1525625293386-3f8f99389edd?auto=format&fit=crop&q=80&w=1600',
        'desc' => 'Experience the vibrant garden city of Singapore with our premium travel itineraries. From the futuristic Gardens by the Bay and shopping on Orchard Road to family fun at Universal Studios and cultural walks in Chinatown, Singapore offers a perfect mix of modern luxury and rich heritage.',
        'packages' => [
            [
                'img' => 'https://images.unsplash.com/photo-1525625293386-3f8f99389edd?auto=format&fit=crop&q=80&w=800',
                'alt' => 'Singapore Stopover',
                'duration' => '4 days & 3 nights',
                'rating' => '4.7',
                'rating_count' => '28',
                'title' => 'Singapore Stopover Package',
                'tags' => ['Stopover', 'City Tour'],
                'old_price' => 'INR 95,000',
                'save' => 'SAVE INR 15,000',
                'price' => 'INR 80,000'
            ],
            [
                'img' => 'https://images.unsplash.com/photo-1563212879-1bf482d8c368?auto=format&fit=crop&q=80&w=800',
                'alt' => 'Gardens by the Bay',
                'duration' => '4 days & 3 nights',
                'rating' => '4.8',
                'rating_count' => '34',
                'title' => 'Gardens By The Bay Experience',
                'tags' => ['Gardens', 'Activities'],
                'old_price' => 'INR 70,000',
                'save' => 'SAVE INR 10,000',
                'price' => 'INR 60,000'
            ],
            [
                'img' => 'https://images.unsplash.com/photo-1506970113724-bc41ee661c5c?auto=format&fit=crop&q=80&w=800',
                'alt' => 'Flavours of Singapore',
                'duration' => '3 days & 2 nights',
                'rating' => '4.6',
                'rating_count' => '19',
                'title' => 'Flavours Of Singapore',
                'tags' => ['Food Tour', 'Culture'],
                'old_price' => 'INR 55,000',
                'save' => 'SAVE INR 8,000',
                'price' => 'INR 47,000'
            ],
            [
                'img' => 'https://images.unsplash.com/photo-1504280390367-361c6d9f38f4?auto=format&fit=crop&q=80&w=800',
                'alt' => 'Sentosa Island',
                'duration' => '5 days & 4 nights',
                'rating' => '4.8',
                'rating_count' => '43',
                'title' => 'Sentosa Island Resort Luxury',
                'tags' => ['Sentosa', 'Universal Studios'],
                'old_price' => 'INR 1,10,000',
                'save' => 'SAVE INR 20,000',
                'price' => 'INR 90,000'
            ],
            [
                'img' => 'https://images.unsplash.com/photo-1540202404-a2f29036bb52?auto=format&fit=crop&q=80&w=800',
                'alt' => 'Marina Bay Sands',
                'duration' => '3 days & 2 nights',
                'rating' => '4.9',
                'rating_count' => '67',
                'title' => 'Marina Bay Sands Premium',
                'tags' => ['Infinity Pool', 'Luxury'],
                'old_price' => 'INR 1,80,000',
                'save' => 'SAVE INR 30,000',
                'price' => 'INR 1,50,000'
            ]
        ]
    ],
    'maldives' => [
        'name' => 'Maldives',
        'title' => 'Maldives Packages',
        'breadcrumb' => 'Maldives',
        'hero_bg' => 'https://images.unsplash.com/photo-1506929197414-435728669527?auto=format&fit=crop&q=80&w=1600',
        'desc' => 'Discover the tropical paradise of the Maldives with our thoughtfully curated travel packages. Whether you\'re dreaming of a luxurious overwater escape, a romantic honeymoon, or a serene family getaway, our Maldives packages offer the perfect blend of relaxation, adventure, and unforgettable island memories.',
        'packages' => [
            [
                'img' => 'https://images.unsplash.com/photo-1507525428034-b723cf961d3e?auto=format&fit=crop&q=80&w=800',
                'alt' => 'Maldives Beachfront',
                'duration' => '5 days & 4 nights',
                'rating' => '4.9',
                'rating_count' => '42',
                'title' => 'Maldives Beachfront Retreat',
                'tags' => ['Beach Villa', 'All Inclusive'],
                'old_price' => 'INR 1,60,000',
                'save' => 'SAVE INR 30,000',
                'price' => 'INR 1,30,000'
            ],
            [
                'img' => 'https://images.unsplash.com/photo-1439066615861-d1af74d74000?auto=format&fit=crop&q=80&w=800',
                'alt' => 'Maldives Water Villa',
                'duration' => '7 days & 6 nights',
                'rating' => '4.9',
                'rating_count' => '88',
                'title' => 'Maldives Water Villa Luxury',
                'tags' => ['Water Villa', 'Private Pool'],
                'old_price' => 'INR 2,90,000',
                'save' => 'SAVE INR 50,000',
                'price' => 'INR 2,40,000'
            ],
            [
                'img' => 'https://images.unsplash.com/photo-1505118380757-91f5f5632de0?auto=format&fit=crop&q=80&w=800',
                'alt' => 'Maldives Island Adventure',
                'duration' => '6 days & 5 nights',
                'rating' => '4.7',
                'rating_count' => '23',
                'title' => 'Maldives Island Adventure',
                'tags' => ['Snorkeling', 'Speedboat'],
                'old_price' => 'INR 1,20,000',
                'save' => 'SAVE INR 20,000',
                'price' => 'INR 1,00,000'
            ],
            [
                'img' => 'https://images.unsplash.com/photo-1544550581-5f7ceaf7f992?auto=format&fit=crop&q=80&w=800',
                'alt' => 'Club Rannalhi',
                'duration' => '5 days & 4 nights',
                'rating' => '4.8',
                'rating_count' => '39',
                'title' => 'Adaaran Club Rannalhi Stay',
                'tags' => ['Overwater Villa', 'All Inclusive'],
                'old_price' => 'INR 1,45,000',
                'save' => 'SAVE INR 25,000',
                'price' => 'INR 1,20,000'
            ],
            [
                'img' => 'https://images.unsplash.com/photo-1573843225804-bbad83002646?auto=format&fit=crop&q=80&w=800',
                'alt' => 'Taj Exotica',
                'duration' => '6 days & 5 nights',
                'rating' => '4.9',
                'rating_count' => '51',
                'title' => 'Taj Exotica Resort Honeymoon',
                'tags' => ['Taj Exotica', 'Private Butler'],
                'old_price' => 'INR 3,20,000',
                'save' => 'SAVE INR 50,000',
                'price' => 'INR 2,70,000'
            ]
        ]
    ],
    'bali' => [
        'name' => 'Bali',
        'title' => 'Bali Packages',
        'breadcrumb' => 'Bali',
        'hero_bg' => 'https://images.unsplash.com/photo-1537996194471-e657df975ab4?auto=format&fit=crop&q=80&w=1600',
        'desc' => 'Immerse yourself in the spiritual warmth and scenic beauty of Bali. Explore ancient cliffside temples, pristine beaches, vibrant cultural dances, and lush green rice terraces in Ubud. Our Bali packages are tailored for romantic escapes and adventurous spirits alike.',
        'packages' => [
            [
                'img' => 'https://images.unsplash.com/photo-1537996194471-e657df975ab4?auto=format&fit=crop&q=80&w=800',
                'alt' => 'Bali Jungle',
                'duration' => '5 days & 4 nights',
                'rating' => '4.8',
                'rating_count' => '64',
                'title' => 'Ubud Jungle Resort Stay',
                'tags' => ['Jungle Villa', 'Breakfast'],
                'old_price' => 'INR 85,000',
                'save' => 'SAVE INR 15,000',
                'price' => 'INR 70,000'
            ],
            [
                'img' => 'https://images.unsplash.com/photo-1539367628448-4bc5c9d171c8?auto=format&fit=crop&q=80&w=800',
                'alt' => 'Bali Beachfront',
                'duration' => '6 days & 5 nights',
                'rating' => '4.9',
                'rating_count' => '53',
                'title' => 'Seminyak Beachfront Villa',
                'tags' => ['Beachfront', 'Private Pool'],
                'old_price' => 'INR 1,40,000',
                'save' => 'SAVE INR 25,000',
                'price' => 'INR 1,15,000'
            ],
            [
                'img' => 'https://images.unsplash.com/photo-1518156677180-95a2893f3e9f?auto=format&fit=crop&q=80&w=800',
                'alt' => 'Bali Temple',
                'duration' => '7 days & 6 nights',
                'rating' => '4.7',
                'rating_count' => '31',
                'title' => 'Bali Cultural Explorer',
                'tags' => ['Temple Tour', 'Private Car'],
                'old_price' => 'INR 95,000',
                'save' => 'SAVE INR 15,000',
                'price' => 'INR 80,000'
            ],
            [
                'img' => 'https://images.unsplash.com/photo-1538964173425-93884d6680c0?auto=format&fit=crop&q=80&w=800',
                'alt' => 'Nusa Penida',
                'duration' => '5 days & 4 nights',
                'rating' => '4.8',
                'rating_count' => '49',
                'title' => 'Nusa Penida Island Tour',
                'tags' => ['Nusa Penida', 'Snorkeling'],
                'old_price' => 'INR 65,000',
                'save' => 'SAVE INR 10,000',
                'price' => 'INR 55,000'
            ],
            [
                'img' => 'https://images.unsplash.com/photo-1552083375-1447ce886485?auto=format&fit=crop&q=80&w=800',
                'alt' => 'Uluwatu Cliff',
                'duration' => '6 days & 5 nights',
                'rating' => '4.9',
                'rating_count' => '72',
                'title' => 'Uluwatu Cliffside Romantic Stay',
                'tags' => ['Cliffside', 'Private Pool'],
                'old_price' => 'INR 1,25,000',
                'save' => 'SAVE INR 20,000',
                'price' => 'INR 1,05,000'
            ]
        ]
    ],
    'japan' => [
        'name' => 'Japan',
        'title' => 'Japan Packages',
        'breadcrumb' => 'Japan',
        'hero_bg' => 'https://images.unsplash.com/photo-1493976040374-85c8e12f0c0e?auto=format&fit=crop&q=80&w=1600',
        'desc' => 'Discover the perfect harmony of ancient traditions and futuristic innovation in Japan. Journey through the bustling streets of Tokyo, the historic temples of Kyoto, and the scenic beauty of Mount Fuji. Our custom Japan itineraries bring you the best of cherry blossoms, culinary wonders, and rich culture.',
        'packages' => [
            [
                'img' => 'https://images.unsplash.com/photo-1493976040374-85c8e12f0c0e?auto=format&fit=crop&q=80&w=800',
                'alt' => 'Japan Kyoto',
                'duration' => '8 days & 7 nights',
                'rating' => '4.9',
                'rating_count' => '112',
                'title' => 'Tokyo & Kyoto Highlights',
                'tags' => ['Bullet Train', 'City Tour'],
                'old_price' => 'INR 2,50,000',
                'save' => 'SAVE INR 40,000',
                'price' => 'INR 2,10,000'
            ],
            [
                'img' => 'https://images.unsplash.com/photo-1490761668535-35497054764d?auto=format&fit=crop&q=80&w=800',
                'alt' => 'Mount Fuji',
                'duration' => '5 days & 4 nights',
                'rating' => '4.8',
                'rating_count' => '47',
                'title' => 'Mount Fuji & Hakone Onsen',
                'tags' => ['Hot Spring', 'Fuji View'],
                'old_price' => 'INR 1,80,000',
                'save' => 'SAVE INR 30,000',
                'price' => 'INR 1,50,000'
            ],
            [
                'img' => 'https://images.unsplash.com/photo-1503899036084-c55cdd92da26?auto=format&fit=crop&q=80&w=800',
                'alt' => 'Osaka Food',
                'duration' => '6 days & 5 nights',
                'rating' => '4.7',
                'rating_count' => '39',
                'title' => 'Osaka & Nara Foodie Adventure',
                'tags' => ['Food Tour', 'Local Guide'],
                'old_price' => 'INR 1,45,000',
                'save' => 'SAVE INR 25,000',
                'price' => 'INR 1,20,000'
            ],
            [
                'img' => 'https://images.unsplash.com/photo-1542051841857-5f90071e7989?auto=format&fit=crop&q=80&w=800',
                'alt' => 'Hokkaido Snow',
                'duration' => '7 days & 6 nights',
                'rating' => '4.8',
                'rating_count' => '58',
                'title' => 'Hokkaido Winter Magic',
                'tags' => ['Hokkaido', 'Snow Resort'],
                'old_price' => 'INR 2,20,000',
                'save' => 'SAVE INR 40,000',
                'price' => 'INR 1,80,000'
            ],
            [
                'img' => 'https://images.unsplash.com/photo-1542931287-023b922fa89b?auto=format&fit=crop&q=80&w=800',
                'alt' => 'Okinawa Beach',
                'duration' => '6 days & 5 nights',
                'rating' => '4.9',
                'rating_count' => '41',
                'title' => 'Okinawa Tropical Beach Getaway',
                'tags' => ['Okinawa', 'Beach Resort'],
                'old_price' => 'INR 1,90,000',
                'save' => 'SAVE INR 30,000',
                'price' => 'INR 1,60,000'
            ]
        ]
    ],
    'kerala' => [
        'name' => 'Kerala',
        'title' => 'Kerala Packages',
        'breadcrumb' => 'Kerala',
        'hero_bg' => 'https://images.unsplash.com/photo-1593693397690-362cb9666fc2?auto=format&fit=crop&q=80&w=1600',
        'desc' => 'Unwind in \'God\'s Own Country\' with our curated Kerala tour packages. Cruise along the serene backwaters of Alappuzha on a traditional houseboat, explore the misty tea gardens of Munnar, and relax on the pristine beaches of Kovalam. Kerala is the ultimate destination for slow travel and rejuvenation.',
        'packages' => [
            [
                'img' => 'https://images.unsplash.com/photo-1593693397690-362cb9666fc2?auto=format&fit=crop&q=80&w=800',
                'alt' => 'Kerala Tea Gardens',
                'duration' => '4 days & 3 nights',
                'rating' => '4.7',
                'rating_count' => '51',
                'title' => 'Munnar Hills & Tea Gardens',
                'tags' => ['Hills', 'Tea Garden'],
                'old_price' => 'INR 45,000',
                'save' => 'SAVE INR 8,000',
                'price' => 'INR 37,000'
            ],
            [
                'img' => 'https://images.unsplash.com/photo-1593693411515-c202e974eb8f?auto=format&fit=crop&q=80&w=800',
                'alt' => 'Kerala Backwaters',
                'duration' => '3 days & 2 nights',
                'rating' => '4.8',
                'rating_count' => '73',
                'title' => 'Alleppey Houseboat Cruise',
                'tags' => ['Backwaters', 'Houseboat'],
                'old_price' => 'INR 35,000',
                'save' => 'SAVE INR 7,000',
                'price' => 'INR 28,000'
            ],
            [
                'img' => 'https://images.unsplash.com/photo-1502086223501-7ea6ecd79368?auto=format&fit=crop&q=80&w=800',
                'alt' => 'Kerala Complete',
                'duration' => '7 days & 6 nights',
                'rating' => '4.9',
                'rating_count' => '92',
                'title' => 'Kerala Complete Experience',
                'tags' => ['Hills', 'Houseboat', 'Beach'],
                'old_price' => 'INR 85,000',
                'save' => 'SAVE INR 15,000',
                'price' => 'INR 70,000'
            ],
            [
                'img' => 'https://images.unsplash.com/photo-1546708973-b339540b5162?auto=format&fit=crop&q=80&w=800',
                'alt' => 'Wayanad Treehouse',
                'duration' => '5 days & 4 nights',
                'rating' => '4.8',
                'rating_count' => '37',
                'title' => 'Wayanad Treehouse & Wildlife',
                'tags' => ['Treehouse', 'Safari'],
                'old_price' => 'INR 48,000',
                'save' => 'SAVE INR 8,000',
                'price' => 'INR 40,000'
            ],
            [
                'img' => 'https://images.unsplash.com/photo-1589982441164-325cfccb9557?auto=format&fit=crop&q=80&w=800',
                'alt' => 'Kovalam Beach',
                'duration' => '6 days & 5 nights',
                'rating' => '4.9',
                'rating_count' => '45',
                'title' => 'Kovalam Beach & Varkala Cliffs',
                'tags' => ['Beach', 'Varkala Cliff'],
                'old_price' => 'INR 44,000',
                'save' => 'SAVE INR 6,000',
                'price' => 'INR 38,000'
            ]
        ]
    ]
];

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
                                    <a href="<?php echo SITE_PATH; ?>/<?php echo $slug; ?>/<?php echo rawurlencode($pkg['title']); ?>" style="color: inherit; text-decoration: none;">
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
