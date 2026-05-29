<?php
/**
 * Global Configuration
 */

// Site Information
// Site Information
define('SITE_NAME', 'Wanderoo');

// Dynamic Path Detection
$script_name = $_SERVER['SCRIPT_NAME']; // e.g. /index.php or /folder/index.php
$base_dir = rtrim(dirname($script_name), '/\\');

// If we are inside the admin folder, we need to go up one level to find the root
if (strpos($script_name, '/admin/') !== false) {
    $base_dir = rtrim(dirname($base_dir), '/\\');
}

define('SITE_PATH', $base_dir);

// Database Credentials
define('DB_HOST', 'localhost');
define('DB_NAME', 'u829703776_world');
define('DB_USER', 'u829703776_world');
define('DB_PASS', 'Wanderoo@001');

// Error Reporting (Set to 0 in production)
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Start Session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/**
 * Slugify Helper Function
 */
function slugify($text) {
    // Replace non letter or digits by -
    $text = preg_replace('~[^\pL\d]+~u', '-', $text);
    // Transliterate
    if (function_exists('iconv')) {
        $text = @iconv('utf-8', 'us-ascii//TRANSLIT', $text);
    }
    // Remove unwanted characters
    $text = preg_replace('~[^-\w]+~', '', $text);
    // Trim
    $text = trim($text, '-');
    // Remove duplicate -
    $text = preg_replace('~-+~', '-', $text);
    // Lowercase
    $text = strtolower($text);
    if (empty($text)) {
        return 'n-a';
    }
    return $text;
}

// Global Destinations Data
global $destinations;
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
    'sri-lanka' => [
        'name' => 'Sri Lanka',
        'title' => 'Sri Lanka Packages',
        'breadcrumb' => 'Sri Lanka',
        'hero_bg' => 'https://images.unsplash.com/photo-1588252613571-86c316a8fb90?auto=format&fit=crop&q=80&w=1600',
        'desc' => 'Explore Sri Lanka with curated tour packages covering golden beaches, tea hills, wildlife safaris, heritage forts, river rides and romantic coastal stays. Sri Lanka is ideal for couples, families and travellers who want culture, nature and easy island comfort in one trip.',
        'packages' => [
            [
                'img' => 'https://images.unsplash.com/photo-1588252613571-86c316a8fb90?auto=format&fit=crop&q=80&w=800',
                'alt' => 'Sri Lanka coast',
                'duration' => '5 days & 4 nights',
                'rating' => '4.7',
                'rating_count' => '51',
                'title' => 'Sri Lanka Seaside Escape',
                'tags' => ['Beach Holiday', 'Mirissa'],
                'old_price' => 'INR 43,799',
                'save' => 'SAVE INR 13,700',
                'price' => 'INR 30,099'
            ],
            [
                'img' => 'https://images.unsplash.com/photo-1507525428034-b723cf961d3e?auto=format&fit=crop&q=80&w=800',
                'alt' => 'Sri Lanka beach',
                'duration' => '7 days & 6 nights',
                'rating' => '4.8',
                'rating_count' => '73',
                'title' => 'Offbeat Sri Lanka South Coast',
                'tags' => ['Offbeat Trip', 'South Coast'],
                'old_price' => 'INR 93,701',
                'save' => 'SAVE INR 28,302',
                'price' => 'INR 65,399'
            ],
            [
                'img' => 'https://images.unsplash.com/photo-1500530855697-b586d89ba3ee?auto=format&fit=crop&q=80&w=800',
                'alt' => 'Sri Lanka hills',
                'duration' => '9 days & 8 nights',
                'rating' => '4.9',
                'rating_count' => '92',
                'title' => 'Romantic Sri Lanka Hills & Beach',
                'tags' => ['Romantic Trip', 'Hill Country'],
                'old_price' => 'INR 81,499',
                'save' => 'SAVE INR 24,500',
                'price' => 'INR 56,999'
            ],
            [
                'img' => 'https://images.unsplash.com/photo-1547471080-7cc2caa01a7e?auto=format&fit=crop&q=80&w=800',
                'alt' => 'Sri Lanka wildlife',
                'duration' => '10 days & 9 nights',
                'rating' => '4.8',
                'rating_count' => '37',
                'title' => 'Best of Sri Lanka Complete',
                'tags' => ['Best Seller', 'Wildlife'],
                'old_price' => 'INR 102,199',
                'save' => 'SAVE INR 34,000',
                'price' => 'INR 68,199'
            ],
            [
                'img' => 'https://images.unsplash.com/photo-1586861635167-e5223aadc9fe?auto=format&fit=crop&q=80&w=800',
                'alt' => 'Sri Lanka heritage',
                'duration' => '6 days & 5 nights',
                'rating' => '4.9',
                'rating_count' => '45',
                'title' => 'Sri Lanka Heritage Coast',
                'tags' => ['Heritage Trip', 'Galle'],
                'old_price' => 'INR 58,500',
                'save' => 'SAVE INR 17,250',
                'price' => 'INR 41,250'
            ]
        ]
    ]
];
?>
