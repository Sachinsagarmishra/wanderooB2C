<?php
$pageTitle = "Modern PHP CMS Solution";
$pageDesc = "Welcome to Wanderoo - The ultimate PHP/MySQL starter for your next big project.";
include_once 'includes/header.php';
?>

<main>
    <section class="hero">
        <img src="<?php echo SITE_PATH; ?>/assets/img/hero-bg.webp" alt="Travel Destination" class="hero-bg">
        <div class="hero-overlay"></div>

        <div class="hero-content">
            <div class="social-proof">
                <div class="avatar-group">
                    <img src="https://i.pravatar.cc/100?u=1" alt="User">
                    <img src="https://i.pravatar.cc/100?u=2" alt="User">
                    <img src="https://i.pravatar.cc/100?u=3" alt="User">
                </div>
                <div class="rating">
                    ★★★★★
                </div>
                <div class="trusted-text">Trusted by 400+ happy Tourists</div>
            </div>

            <h1 class="hero-title">Your Dream Holiday<br> <span class="playfair italic">Perfectly Planned</span></h1>

            <p class="hero-subtitle">We plan, you relax</p>

            <a href="#" class="btn-quote">Get Instant Quote</a>
        </div>
    </section>

    <section class="who-we-are" style="padding: 60px 40px; max-width: 1280px; margin: 0 auto; display: flex; gap: 40px; align-items: flex-start;">
        <div style="flex: 1;">
            <h2 style="font-size: 56px; line-height: 1.1;"><span class="urbanist">Who</span> <span class="playfair italic">We Are</span></h2>
        </div>
        <div style="flex: 1.5;">
            <p style="font-size: 18px; color: #4a4a4a; margin-bottom: 15px; font-weight: 500; line-height: 1.4;">
                We're not just another booking site — we're your travel partner. At Wanderoo, you'll have your own dedicated destination expert to plan every step of your trip with care, clarity, and a genuine local touch.
            </p>
            <a href="#" class="read-more" style="font-weight: 700; display: flex; align-items: center; gap: 8px; font-size: 16px; border-bottom: 2px solid var(--primary); width: max-content; padding-bottom: 2px;">
                Read More <img src="<?php echo SITE_PATH; ?>/assets/img/arrow.svg" alt="Arrow" style="height: 14px; width: auto;">
            </a>
        </div>
    </section>

    <div class="divider" style="max-width: 1280px; margin: 0 auto; border-top: 1px solid #eee; height: 1px;"></div>

    <section class="honeymooners" style="padding: 80px 40px; max-width: 1280px; margin: 0 auto;">
        <div class="section-header" style="display: flex; justify-content: space-between; align-items: flex-end; margin-bottom: 40px;">
            <div>
                <h2 style="font-size: 56px; margin-bottom: 10px;"><span class="urbanist">For</span> <span class="playfair italic">Honeymooners</span></h2>
                <p style="font-size: 18px; color: #4a4a4a; font-weight: 500;">Honeymoons crafted for forever memories.</p>
            </div>
            <a href="#" class="btn-enquire">Enquire Now</a>
        </div>

        <div class="packages-slider-container">
            <div class="packages-grid">
                <!-- Card 1 -->
                <div class="package-card">
                    <div class="card-img">
                        <img src="https://images.unsplash.com/photo-1544550581-5f7ceaf7f992?auto=format&fit=crop&q=80&w=800" alt="Maldives">
                    </div>
                    <div class="card-body">
                        <div class="card-tags">
                            <span>09 Days / 08 Nights</span>
                            <span>Flights not included</span>
                            <span>More+</span>
                        </div>
                        <h3>All Inclusive Maldives Fly & Stay</h3>
                        <p>Flights not included Escape to Adaaran Club Rannalhi, Maldives, for 7 nights of white-sand beaches, marine adventures, tropical feasts, water sports, and pure island relaxation</p>
                        <div class="card-pricing">
                            <span class="sub">Maldives Fly & Stay</span>
                            <h4>Contact Us</h4>
                            <span class="per">Per person</span>
                            <div class="instalment-tag">
                                💳 Pay in instalments available
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Card 2 -->
                <div class="package-card">
                    <div class="card-img">
                        <img src="https://images.unsplash.com/photo-1514282401047-d79a71a590e8?auto=format&fit=crop&q=80&w=800" alt="Honeymoon">
                    </div>
                    <div class="card-body">
                        <div class="card-tags">
                            <span>04 Days / 03 Nights</span>
                            <span>Flights not included</span>
                            <span>More+</span>
                        </div>
                        <h3>Luxury Honeymoon</h3>
                        <p>Flights not included Indulge in Adaaran Prestige Vadoo's private island luxury — lagoon-access villas, butler service, gourmet dining, and romantic experiences in the Maldives</p>
                        <div class="card-pricing">
                            <span class="sub">Luxury Honeymoon</span>
                            <h4>Contact Us</h4>
                            <span class="per">Per person</span>
                            <div class="instalment-tag">
                                💳 Pay in instalments available
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Card 3 -->
                <div class="package-card">
                    <div class="card-img">
                        <img src="https://images.unsplash.com/photo-1573843225804-bbad83002646?auto=format&fit=crop&q=80&w=800" alt="Overwater">
                    </div>
                    <div class="card-body">
                        <div class="card-tags">
                            <span>07 Days / 06 Nights</span>
                            <span>Flights not included</span>
                            <span>More+</span>
                        </div>
                        <h3>Luxury Overwater Maldives Fly & Stay</h3>
                        <p>Flights not included Stay in a Sunrise Overwater Villa with plunge pool, enjoy butler service, world-class dining, snorkelling, spa indulgence, and island adventures.</p>
                        <div class="card-pricing">
                            <span class="sub">Luxury Overwater Maldives Fly & Stay</span>
                            <h4>Contact Us</h4>
                            <span class="per">Per person twin share</span>
                            <div class="instalment-tag">
                                💳 Pay in instalments available
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Card 4 (Hidden in desktop view, part of slider) -->
                <div class="package-card hidden-mobile">
                    <div class="card-img">
                        <img src="https://images.unsplash.com/photo-1540202404-a2f29036bb52?auto=format&fit=crop&q=80&w=800" alt="Bali">
                    </div>
                    <div class="card-body">
                        <div class="card-tags">
                            <span>05 Days / 04 Nights</span>
                            <span>All inclusive</span>
                            <span>More+</span>
                        </div>
                        <h3>Bali Romantic Escape</h3>
                        <p>Experience the magic of Bali with private pool villas, flower baths, and sunset dinners at Tanah Lot.</p>
                        <div class="card-pricing">
                            <span class="sub">Bali Escape</span>
                            <h4>Contact Us</h4>
                            <span class="per">Per person</span>
                            <div class="instalment-tag">
                                💳 Pay in instalments available
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Card 5 -->
                <div class="package-card hidden-mobile">
                    <div class="card-img">
                        <img src="https://images.unsplash.com/photo-1506929197414-435728669527?auto=format&fit=crop&q=80&w=800" alt="Seychelles">
                    </div>
                    <div class="card-body">
                        <div class="card-tags">
                            <span>08 Days / 07 Nights</span>
                            <span>Breakfast included</span>
                            <span>More+</span>
                        </div>
                        <h3>Seychelles Paradise</h3>
                        <p>Unwind on the pristine beaches of Mahe and Praslin with exclusive resort stays and island hopping.</p>
                        <div class="card-pricing">
                            <span class="sub">Seychelles Paradise</span>
                            <h4>Contact Us</h4>
                            <span class="per">Per person</span>
                            <div class="instalment-tag">
                                💳 Pay in instalments available
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="slider-dots">
                <span class="dot active"></span>
                <span class="dot"></span>
                <span class="dot"></span>
            </div>
        </div>
    </section>
</main>

<?php include_once 'includes/footer.php'; ?>