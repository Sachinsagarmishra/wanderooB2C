<?php
$pageTitle = "Luxury Honeymoon";
$pageDesc = "Welcome to Adaaran Prestige Vadoo – a luxurious adults-only escape in the Maldives.";
include 'includes/header.php';
?>

<div class="detail-gallery">
    <div class="detail-gallery-main">
        <img src="https://images.unsplash.com/photo-1544550581-5f7ceaf7f992?auto=format&fit=crop&q=80&w=800" alt="Main Honeymoon Image" class="detail-gallery-img">
    </div>
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
</div>

<div class="detail-container">
    <div class="detail-layout">
        <!-- Main Content Column -->
        <div class="detail-main-content">
            <!-- Badges -->
            <div class="detail-badges">
                <span class="detail-badge">4D/3N</span>
                <span class="detail-badge">Honeymoon Alone</span>
                <span class="detail-badge">Floating Breakfast</span>
                <span class="detail-badge">Dolphin Safari</span>
                <span class="detail-badge">Sandbank Lunch</span>
            </div>
            
            <!-- Title -->
            <h1 class="detail-title">Luxury Honeymoon</h1>
            
            <!-- Subdescription -->
            <p class="detail-desc">
                Welcome to Adaaran Prestige Vadoo – a luxurious adults-only escape in the Maldives, where romance meets all-inclusive indulgence.
            </p>
            
            <!-- Overview -->
            <h3>Overview:</h3>
            <p class="detail-desc">
                Set in the heart of the Indian Ocean, Adaaran Prestige Vadoo offers the ultimate private island experience for couples seeking luxury and serenity. With direct lagoon access from your villa, floating breakfasts, candlelit beach dinners, and a personal butler to anticipate your every need, every detail is designed for intimate relaxation. Enjoy curated excursions like sunset cruises and dolphin safaris, and unwind with unlimited gourmet dining, drinks, and water activities. This award-winning all-inclusive resort promises a tailored Maldivian escape unlike any other.
            </p>
            
            <!-- Highlights -->
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
                    Excursions: sunset photo flip & dolphin safari (once per stay)
                </li>
                <li>
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg>
                    Complimentary snorkelling gear & non-motorized watersports (canoe, SUP, catamaran)
                </li>
            </ul>
            
            <!-- Day-wise Itinerary -->
            <h3>Day-wise:</h3>
            <div class="itinerary-tabs">
                <button class="itinerary-tab-btn active" data-day="1">Day 01</button>
                <button class="itinerary-tab-btn" data-day="2">Day 02</button>
                <button class="itinerary-tab-btn" data-day="3">Day 03</button>
                <button class="itinerary-tab-btn" data-day="4">Day 04</button>
            </div>
            
            <div class="itinerary-accordion">
                <!-- Day 1 -->
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
                            Welcome to Maldives. Resort check-in. Welcome to Adaaran Prestige Vadoo, an adults-only sanctuary in the Maldives. Custom-made for romance and tranquility, our premium all-inclusive resort, set amidst lush foliage and pristine shores, offers bespoke amenities and personalized butler service for a secluded escape. Dive into your intimate getaway with direct sea access from your villa to swim alongside vibrant marine life.
                            <div class="itinerary-details-meta">
                                <span><strong>Accommodation:</strong> Adaaran Prestige Vadoo</span>
                                <span><strong>Meals:</strong> Breakfast, Lunch, Dinner</span>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Day 2 -->
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
                            Enjoy a relaxing day at the resort. Opt for a romantic spa session, lounge on the sun deck, or explore the colorful house reef. Indulge in premium cocktails and culinary delights at our signature restaurants.
                            <div class="itinerary-details-meta">
                                <span><strong>Accommodation:</strong> Adaaran Prestige Vadoo</span>
                                <span><strong>Meals:</strong> Breakfast, Lunch, Dinner</span>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Day 3 -->
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
                            Experience a floating breakfast in your private pool, followed by a guided dolphin safari in the afternoon. Cap off the night with a special candlelit dinner served directly on the beach.
                            <div class="itinerary-details-meta">
                                <span><strong>Accommodation:</strong> Adaaran Prestige Vadoo</span>
                                <span><strong>Meals:</strong> Breakfast, Lunch, Dinner</span>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Day 4 -->
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
                            Check out of the resort. Board your speedboat back to Male International Airport for your journey home, carrying unforgettable memories of your luxury Maldivian honeymoon.
                            <div class="itinerary-details-meta">
                                <span><strong>Accommodation:</strong> None</span>
                                <span><strong>Meals:</strong> Breakfast</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Inclusions & Exclusions Box -->
            <div class="detail-inclusions-box">
                <h3>What's inside the package?:</h3>
                <div class="detail-inclusions-columns">
                    <div class="detail-inclusions-col inclusions">
                        <h4>Inclusions</h4>
                        <ul>
                            <li>
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg>
                                Accommodation
                            </li>
                            <li>
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg>
                                All Meals
                            </li>
                            <li>
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg>
                                Airport transfers
                            </li>
                            <li>
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg>
                                Airport Assistance
                            </li>
                            <li>
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg>
                                Government Taxes
                            </li>
                            <li>
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg>
                                Water Sports
                            </li>
                        </ul>
                    </div>
                    <div class="detail-inclusions-col exclusions">
                        <h4>Exclusions</h4>
                        <ul>
                            <li>
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                                Cooking Class
                            </li>
                            <li>
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                                Sunset Cruise
                            </li>
                            <li>
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                                Beach Swing
                            </li>
                            <li>
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                                Snorkeling
                            </li>
                            <li>
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                                Water Sports
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Sidebar Column -->
        <div class="detail-sidebar">
            <div class="detail-sidebar-box">
                <h4 class="sidebar-package-title">Luxury Honeymoon</h4>
                <h3 class="sidebar-heading">Select Destination</h3>
                <div class="enquiry-select-wrapper">
                    <select class="enquiry-select">
                        <option value="" disabled selected>Choose your dream destination...</option>
                        <option value="maldives">Maldives</option>
                        <option value="singapore">Singapore</option>
                        <option value="bali">Bali</option>
                        <option value="japan">Japan</option>
                        <option value="kerala">Kerala, India</option>
                    </select>
                </div>
                <button class="btn-enquiry-next">
                    Next &rarr;
                </button>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
