<?php
$pageTitle = "Frequently Asked Questions";
$pageDesc = "Find answers to frequently asked questions about planning, booking, customizing, and enjoying your premium holiday or honeymoon with Wanderoo.";
$bodyClass = "faqs-page";
include_once 'includes/header.php';
?>

<main>
    <!-- FAQ Hero Banner -->
    <div class="about-hero-banner">
        <img src="<?php echo SITE_PATH; ?>/assets/img/policy-hero.jpg" alt="FAQs" class="about-hero-bg">
        <div class="about-hero-overlay"></div>
        <div class="about-hero-content">
            <span class="about-breadcrumb-text"><a href="<?php echo SITE_PATH; ?>/">Home</a> &raquo; FAQs</span>
            <h1 class="about-hero-title">Frequently Asked Questions</h1>
        </div>
    </div>

    <!-- FAQ Accordion Section -->
    <section class="faq-section" style="border-top: none;">
        <div class="faq-container">
            <div class="faq-sidebar">
                <h2 class="faq-title">
                    <span class="urbanist">We've got</span> <span class="playfair italic">answers</span>
                </h2>
                <p class="faq-subtitle">Everything explained. Before you book.</p>
            </div>
            
            <div class="faq-content">
                <div class="faq-accordion">
                    <!-- Item 1 (Active by default) -->
                    <div class="faq-item active" id="faq-1">
                        <div class="faq-header">
                            <span class="faq-question">What Does a Wanderoo Trip Include?</span>
                            <span class="faq-toggle">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                    <line x1="18" y1="6" x2="6" y2="18"></line>
                                    <line x1="6" y1="6" x2="18" y2="18"></line>
                                </svg>
                            </span>
                        </div>
                        <div class="faq-body">
                            <p class="faq-answer">Every Wanderoo trip is thoughtfully designed around you. Your package can include accommodations, airport transfers, transportation, activities, sightseeing, local recommendations, and expert trip planning. We take care of the details so you can focus on enjoying the journey.</p>
                        </div>
                    </div>
                    
                    <!-- Item 2 -->
                    <div class="faq-item" id="faq-2">
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
                            <p class="faq-answer">International flights are usually not included unless specifically mentioned in your package. This gives you the flexibility to choose the airline, route, and fare that work best for you. If you’d like help finding and booking the best flight options, we’re happy to help you.</p>
                        </div>
                    </div>
                    
                    <!-- Item 3 -->
                    <div class="faq-item" id="faq-3">
                        <div class="faq-header">
                            <span class="faq-question">Who Are Wanderoo’s Destination Experts?</span>
                            <span class="faq-toggle">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                    <line x1="12" y1="5" x2="12" y2="19"></line>
                                    <line x1="5" y1="12" x2="19" y2="12"></line>
                                </svg>
                            </span>
                        </div>
                        <div class="faq-body">
                            <p class="faq-answer">Our destination experts are travel specialists who have extensive experience planning trips to the countries they handle. They’ve spent years researching destinations, working with local partners, understanding logistics, and helping travelers create memorable holidays. Think of them as your personal travel advisor who knows the destination inside out.</p>
                        </div>
                    </div>

                    <!-- Item 4 -->
                    <div class="faq-item" id="faq-4">
                        <div class="faq-header">
                            <span class="faq-question">How Far In Advance Should I Book My Trip?</span>
                            <span class="faq-toggle">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                    <line x1="12" y1="5" x2="12" y2="19"></line>
                                    <line x1="5" y1="12" x2="19" y2="12"></line>
                                </svg>
                            </span>
                        </div>
                        <div class="faq-body">
                            <p class="faq-answer">The sooner, the better! Booking early gives you access to better hotel choices, lower prices, and more availability for popular experiences. <br><br>That said, we also love a good last-minute adventure. We can often plan trips within 7–10 days of departure, though flights, hotels, and activities may be more expensive closer to your travel dates.</p>
                        </div>
                    </div>

                    <!-- Item 5 -->
                    <div class="faq-item" id="faq-5">
                        <div class="faq-header">
                            <span class="faq-question">Will Someone Meet Me At The Airport?</span>
                            <span class="faq-toggle">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                    <line x1="12" y1="5" x2="12" y2="19"></line>
                                    <line x1="5" y1="12" x2="19" y2="12"></line>
                                </svg>
                            </span>
                        </div>
                        <div class="faq-body">
                            <p class="faq-answer">In most of our packages, yes. We can arrange airport pickups in either private or shared vehicles, depending on your preferences and destination. If you have specific requirements, we’ll happily customize the transfer to suit your travel style.</p>
                        </div>
                    </div>

                    <!-- Item 6 -->
                    <div class="faq-item" id="faq-6">
                        <div class="faq-header">
                            <span class="faq-question">What Can I Customize?</span>
                            <span class="faq-toggle">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                    <line x1="12" y1="5" x2="12" y2="19"></line>
                                    <line x1="5" y1="12" x2="19" y2="12"></line>
                                </svg>
                            </span>
                        </div>
                        <div class="faq-body">
                            <p class="faq-answer">A better question might be: What can’t you customize? <br><br>From hotels and activities to food experiences, transport, special occasions, hidden gems, adventure activities, family-friendly experiences, and even surprise proposals—we’ll do our best to make it happen. Every itinerary is built around what you love.</p>
                        </div>
                    </div>

                    <!-- Item 7 -->
                    <div class="faq-item" id="faq-7">
                        <div class="faq-header">
                            <span class="faq-question">Can I Add More Travellers Later?</span>
                            <span class="faq-toggle">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                    <line x1="12" y1="5" x2="12" y2="19"></line>
                                    <line x1="5" y1="12" x2="19" y2="12"></line>
                                </svg>
                            </span>
                        </div>
                        <div class="faq-body">
                            <p class="faq-answer">Absolutely! The more, the merrier. <br><br>If you’d like to add friends or family after booking, we’ll do our best to accommodate everyone. As long as hotels, transport, and activities have availability, we can update your trip accordingly.</p>
                        </div>
                    </div>

                    <!-- Item 8 -->
                    <div class="faq-item" id="faq-8">
                        <div class="faq-header">
                            <span class="faq-question">What Happens After I Enquire?</span>
                            <span class="faq-toggle">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                    <line x1="12" y1="5" x2="12" y2="19"></line>
                                    <line x1="5" y1="12" x2="19" y2="12"></line>
                                </svg>
                            </span>
                        </div>
                        <div class="faq-body">
                            <p class="faq-answer">Once you submit your enquiry, one of our destination experts will get in touch to understand your travel style, interests, budget, and wishlist. We’ll then craft a personalized itinerary and refine it with you until it feels just right.</p>
                        </div>
                    </div>

                    <!-- Item 9 -->
                    <div class="faq-item" id="faq-9">
                        <div class="faq-header">
                            <span class="faq-question">Can You Help With Special Occasions?</span>
                            <span class="faq-toggle">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                    <line x1="12" y1="5" x2="12" y2="19"></line>
                                    <line x1="5" y1="12" x2="19" y2="12"></line>
                                </svg>
                            </span>
                        </div>
                        <div class="faq-body">
                            <p class="faq-answer">Of course! Whether you’re celebrating a honeymoon, anniversary, birthday, babymoon, family reunion, or simply ticking off a bucket-list adventure, we’ll help make it extra special with personalized touches and experiences.</p>
                        </div>
                    </div>

                    <!-- Item 10 -->
                    <div class="faq-item" id="faq-10">
                        <div class="faq-header">
                            <span class="faq-question">Is There Support During My Trip?</span>
                            <span class="faq-toggle">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                    <line x1="12" y1="5" x2="12" y2="19"></line>
                                    <line x1="5" y1="12" x2="19" y2="12"></line>
                                </svg>
                            </span>
                        </div>
                        <div class="faq-body">
                            <p class="faq-answer">Yes. Travel plans can change, and we’re here when they do. Our team provides support before and during your trip, so you’ll always have someone to reach out to if you need assistance or advice along the way.</p>
                        </div>
                    </div>

                    <!-- Item 11 -->
                    <div class="faq-item" id="faq-11">
                        <div class="faq-header">
                            <span class="faq-question">Do You Only Plan Popular Tourist Attractions?</span>
                            <span class="faq-toggle">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                    <line x1="12" y1="5" x2="12" y2="19"></line>
                                    <line x1="5" y1="12" x2="19" y2="12"></line>
                                </svg>
                            </span>
                        </div>
                        <div class="faq-body">
                            <p class="faq-answer">Not at all. We can include iconic must-see attractions, but we also love recommending local favorites, hidden gems, unique experiences, and places that most tourists miss. The balance is entirely up to you.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>

<?php include_once 'includes/footer.php'; ?>
