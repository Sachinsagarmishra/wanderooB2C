<?php
$pageTitle = "Wanderoo - Premium Handpicked Getaways & Luxury Honeymoons";
$pageDesc = "Discover handpicked luxury getaways, romantic honeymoon packages, and custom travel itineraries to Singapore, Maldives, Bali, Japan, and Kerala with Wanderoo.";
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

    <section class="who-we-are" style="padding: 20px 0px; max-width: 1280px; margin: 0 auto; display: flex; gap: 40px; align-items: flex-start;">
        <div style="flex: 1;">
            <h2 style="line-height: 1.1;"><span class="urbanist">Who</span> <span class="playfair italic">We Are</span></h2>
        </div>
        <div style="flex: 1.5;">
            <p style="font-size: 18px; color: #4a4a4a; letter-spacing: 0.4px; margin-bottom: 15px; font-weight: 500; line-height: 1.4;">
                We're not just another booking site — we're your travel partner. At Wanderoo, you'll have your own dedicated destination expert to plan every step of your trip with care, clarity, and a genuine local touch.
            </p>
            <a href="#" class="read-more" style="font-weight: 700; display: flex; align-items: center; gap: 8px; font-size: 16px; border-bottom: 2px solid var(--primary); width: max-content; padding-bottom: 2px;">
                Read More <img src="<?php echo SITE_PATH; ?>/assets/img/arrow.svg" alt="Arrow" style="height: 14px; width: auto;">
            </a>
        </div>
    </section>

    <div class="divider" style="max-width: 1280px; margin: 0 auto; border-top: 1px solid #eee; height: 1px;"></div>
    <section class="honeymooners" style="padding: 60px 0px; max-width: 1280px; margin: 0 auto;">
        <div class="section-header" style="display: flex; justify-content: space-between; align-items: flex-end; margin-bottom: 40px;">
            <div>
                <h2 style="margin-bottom: 10px;"><span class="urbanist">For</span> <span class="playfair italic">Honeymooners</span></h2>
                <p style="font-size: 18px; color: #4a4a4a; font-weight: 500;">Honeymoons crafted for forever memories.</p>
            </div>
            <a href="#" class="btn-enquire">Enquire Now</a>
        </div>

        <div class="packages-slider-container">
            <!-- Prev Arrow -->
            <div class="slider-arrow slider-arrow-prev">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="15 18 9 12 15 6"></polyline></svg>
            </div>

            <div class="packages-grid">
                <!-- Card 1 -->
                <div class="package-card">
                    <div class="card-img">
                        <img src="https://images.unsplash.com/photo-1544550581-5f7ceaf7f992?auto=format&fit=crop&q=80&w=800" alt="Maldives">
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
                            <span class="duration">9 days & 8 nights</span>
                            <span class="rating"><span class="star">★</span> 4.8 <span class="count">(12)</span></span>
                        </div>
                        <h3>All Inclusive Maldives Fly & Stay</h3>
                        <div class="card-tags">
                            <span class="tag">9D Maldives</span>
                        </div>
                        <div class="price-section">
                            <div class="old-price-row">
                                <span class="old-price">INR 1,50,000</span>
                                <span class="save-badge">SAVE INR 30,000</span>
                            </div>
                            <div class="new-price-row">
                                <span class="current-price">INR 1,20,000</span>
                                <span class="per-adult">/Adult</span>
                            </div>
                        </div>
                        <div class="card-actions">
                            <a href="tel:+1234567890" class="btn-phone">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path></svg>
                            </a>
                            <a href="#" class="btn-request">Get a quote</a>
                        </div>
                    </div>
                </div>

                <!-- Card 2 -->
                <div class="package-card">
                    <div class="card-img">
                        <img src="https://images.unsplash.com/photo-1514282401047-d79a71a590e8?auto=format&fit=crop&q=80&w=800" alt="Honeymoon">
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
                            <span class="duration">4 days & 3 nights</span>
                            <span class="rating"><span class="star">★</span> 4.9 <span class="count">(45)</span></span>
                        </div>
                        <h3>Luxury Honeymoon at Adaaran Prestige</h3>
                        <div class="card-tags">
                            <span class="tag">4D Prestige Vadoo</span>
                        </div>
                        <div class="price-section">
                            <div class="old-price-row">
                                <span class="old-price">INR 2,20,000</span>
                                <span class="save-badge">SAVE INR 45,000</span>
                            </div>
                            <div class="new-price-row">
                                <span class="current-price">INR 1,75,000</span>
                                <span class="per-adult">/Adult</span>
                            </div>
                        </div>
                        <div class="card-actions">
                            <a href="tel:+1234567890" class="btn-phone">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path></svg>
                            </a>
                            <a href="#" class="btn-request">Get a quote</a>
                        </div>
                    </div>
                </div>

                <!-- Card 3 -->
                <div class="package-card">
                    <div class="card-img">
                        <img src="https://images.unsplash.com/photo-1573843225804-bbad83002646?auto=format&fit=crop&q=80&w=800" alt="Overwater">
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
                            <span class="duration">7 days & 6 nights</span>
                            <span class="rating"><span class="star">★</span> 4.7 <span class="count">(18)</span></span>
                        </div>
                        <h3>Luxury Overwater Maldives Stay & Fly</h3>
                        <div class="card-tags">
                            <span class="tag">7D Sunrise Overwater Villa</span>
                        </div>
                        <div class="price-section">
                            <div class="old-price-row">
                                <span class="old-price">INR 3,10,000</span>
                                <span class="save-badge">SAVE INR 60,000</span>
                            </div>
                            <div class="new-price-row">
                                <span class="current-price">INR 2,50,000</span>
                                <span class="per-adult">/Adult</span>
                            </div>
                        </div>
                        <div class="card-actions">
                            <a href="tel:+1234567890" class="btn-phone">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path></svg>
                            </a>
                            <a href="#" class="btn-request">Get a quote</a>
                        </div>
                    </div>
                </div>

                <!-- Card 4 (Hidden in desktop view, part of slider) -->
                <div class="package-card hidden-mobile">
                    <div class="card-img">
                        <img src="https://images.unsplash.com/photo-1540202404-a2f29036bb52?auto=format&fit=crop&q=80&w=800" alt="Bali">
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
                            <span class="duration">5 days & 4 nights</span>
                            <span class="rating"><span class="star">★</span> 4.9 <span class="count">(88)</span></span>
                        </div>
                        <h3>Bali Romantic Escape with Private Pool</h3>
                        <div class="card-tags">
                            <span class="tag">3D Ubud</span>
                            <span class="tag">2D Seminyak</span>
                        </div>
                        <div class="price-section">
                            <div class="old-price-row">
                                <span class="old-price">INR 1,10,000</span>
                                <span class="save-badge">SAVE INR 25,000</span>
                            </div>
                            <div class="new-price-row">
                                <span class="current-price">INR 85,000</span>
                                <span class="per-adult">/Adult</span>
                            </div>
                        </div>
                        <div class="card-actions">
                            <a href="tel:+1234567890" class="btn-phone">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path></svg>
                            </a>
                            <a href="#" class="btn-request">Get a quote</a>
                        </div>
                    </div>
                </div>

                <!-- Card 5 -->
                <div class="package-card hidden-mobile">
                    <div class="card-img">
                        <img src="https://images.unsplash.com/photo-1506929197414-435728669527?auto=format&fit=crop&q=80&w=800" alt="Seychelles">
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
                            <span class="duration">8 days & 7 nights</span>
                            <span class="rating"><span class="star">★</span> 4.6 <span class="count">(14)</span></span>
                        </div>
                        <h3>Seychelles Paradise & Island Hopping</h3>
                        <div class="card-tags">
                            <span class="tag">5D Mahe</span>
                            <span class="tag">3D Praslin</span>
                        </div>
                        <div class="price-section">
                            <div class="old-price-row">
                                <span class="old-price">INR 2,80,000</span>
                                <span class="save-badge">SAVE INR 50,000</span>
                            </div>
                            <div class="new-price-row">
                                <span class="current-price">INR 2,30,000</span>
                                <span class="per-adult">/Adult</span>
                            </div>
                        </div>
                        <div class="card-actions">
                            <a href="tel:+1234567890" class="btn-phone">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path></svg>
                            </a>
                            <a href="#" class="btn-request">Get a quote</a>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Next Arrow -->
            <div class="slider-arrow slider-arrow-next">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"></polyline></svg>
            </div>
            
            <div class="slider-dots">
                <span class="dot active"></span>
                <span class="dot"></span>
                <span class="dot"></span>
            </div>
        </div>
    </section>

    <section class="wander-section" style="background-image: url('<?php echo SITE_PATH; ?>/assets/img/patternbg.png');">
        <div class="wander-container">
            <h2 class="wander-title"><span class="urbanist">Where would you</span> <span class="playfair italic">like to wander?</span></h2>
            <p class="wander-subtitle">Handpicked getaways designed for romance, adventure, and slow travel—crafted for memories that last a lifetime</p>
            
            <div class="filter-tabs">
                <button class="tab-btn active" data-target="singapore">Singapore</button>
                <button class="tab-btn" data-target="maldives">Maldives</button>
                <button class="tab-btn" data-target="bali">Bali</button>
                <button class="tab-btn" data-target="japan">Japan</button>
                <button class="tab-btn" data-target="kerala">Kerala, India</button>
            </div>
            
            <!-- Singapore Category -->
            <div class="category-packages active" id="singapore">
                <div class="packages-slider-container">
                    <!-- Prev Arrow -->
                    <div class="slider-arrow slider-arrow-prev">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="15 18 9 12 15 6"></polyline></svg>
                    </div>
                    <div class="packages-grid">
                        <!-- Singapore Card 1 -->
                        <div class="package-card">
                            <div class="card-img">
                                <img src="https://images.unsplash.com/photo-1525625293386-3f8f99389edd?auto=format&fit=crop&q=80&w=800" alt="Singapore Stopover">
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
                                    <span class="duration">4 days & 3 nights</span>
                                    <span class="rating"><span class="star">★</span> 4.7 <span class="count">(28)</span></span>
                                </div>
                                <h3>Singapore Stopover Package</h3>
                                <div class="card-tags">
                                    <span class="tag">Stopover</span>
                                    <span class="tag">City Tour</span>
                                </div>
                                <div class="price-section">
                                    <div class="old-price-row">
                                        <span class="old-price">INR 95,000</span>
                                        <span class="save-badge">SAVE INR 15,000</span>
                                    </div>
                                    <div class="new-price-row">
                                        <span class="current-price">INR 80,000</span>
                                        <span class="per-adult">/Adult</span>
                                    </div>
                                </div>
                                <div class="card-actions">
                                    <a href="tel:+1234567890" class="btn-phone">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path></svg>
                                    </a>
                                    <a href="#" class="btn-request">Get a quote</a>
                                </div>
                            </div>
                        </div>

                        <!-- Singapore Card 2 -->
                        <div class="package-card">
                            <div class="card-img">
                                <img src="https://images.unsplash.com/photo-1563212879-1bf482d8c368?auto=format&fit=crop&q=80&w=800" alt="Gardens by the Bay">
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
                                    <span class="duration">4 days & 3 nights</span>
                                    <span class="rating"><span class="star">★</span> 4.8 <span class="count">(34)</span></span>
                                </div>
                                <h3>Gardens By The Bay Experience</h3>
                                <div class="card-tags">
                                    <span class="tag">Gardens</span>
                                    <span class="tag">Activities</span>
                                </div>
                                <div class="price-section">
                                    <div class="old-price-row">
                                        <span class="old-price">INR 70,000</span>
                                        <span class="save-badge">SAVE INR 10,000</span>
                                    </div>
                                    <div class="new-price-row">
                                        <span class="current-price">INR 60,000</span>
                                        <span class="per-adult">/Adult</span>
                                    </div>
                                </div>
                                <div class="card-actions">
                                    <a href="tel:+1234567890" class="btn-phone">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path></svg>
                                    </a>
                                    <a href="#" class="btn-request">Get a quote</a>
                                </div>
                            </div>
                        </div>

                        <!-- Singapore Card 3 -->
                        <div class="package-card">
                            <div class="card-img">
                                <img src="https://images.unsplash.com/photo-1506970113724-bc41ee661c5c?auto=format&fit=crop&q=80&w=800" alt="Flavours of Singapore">
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
                                    <span class="duration">3 days & 2 nights</span>
                                    <span class="rating"><span class="star">★</span> 4.6 <span class="count">(19)</span></span>
                                </div>
                                <h3>Flavours Of Singapore</h3>
                                <div class="card-tags">
                                    <span class="tag">Food Tour</span>
                                    <span class="tag">Culture</span>
                                </div>
                                <div class="price-section">
                                    <div class="old-price-row">
                                        <span class="old-price">INR 55,000</span>
                                        <span class="save-badge">SAVE INR 8,000</span>
                                    </div>
                                    <div class="new-price-row">
                                        <span class="current-price">INR 47,000</span>
                                        <span class="per-adult">/Adult</span>
                                    </div>
                                </div>
                                <div class="card-actions">
                                    <a href="tel:+1234567890" class="btn-phone">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path></svg>
                                    </a>
                                    <a href="#" class="btn-request">Get a quote</a>
                                </div>
                            </div>
                        </div>

                        <!-- Singapore Card 4 -->
                        <div class="package-card">
                            <div class="card-img">
                                <img src="https://images.unsplash.com/photo-1504280390367-361c6d9f38f4?auto=format&fit=crop&q=80&w=800" alt="Sentosa Island">
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
                                    <span class="duration">5 days & 4 nights</span>
                                    <span class="rating"><span class="star">★</span> 4.8 <span class="count">(43)</span></span>
                                </div>
                                <h3>Sentosa Island Resort Luxury</h3>
                                <div class="card-tags">
                                    <span class="tag">Sentosa</span>
                                    <span class="tag">Universal Studios</span>
                                </div>
                                <div class="price-section">
                                    <div class="old-price-row">
                                        <span class="old-price">INR 1,10,000</span>
                                        <span class="save-badge">SAVE INR 20,000</span>
                                    </div>
                                    <div class="new-price-row">
                                        <span class="current-price">INR 90,000</span>
                                        <span class="per-adult">/Adult</span>
                                    </div>
                                </div>
                                <div class="card-actions">
                                    <a href="tel:+1234567890" class="btn-phone">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path></svg>
                                    </a>
                                    <a href="#" class="btn-request">Get a quote</a>
                                </div>
                            </div>
                        </div>

                        <!-- Singapore Card 5 -->
                        <div class="package-card">
                            <div class="card-img">
                                <img src="https://images.unsplash.com/photo-1540202404-a2f29036bb52?auto=format&fit=crop&q=80&w=800" alt="Marina Bay Sands">
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
                                    <span class="duration">3 days & 2 nights</span>
                                    <span class="rating"><span class="star">★</span> 4.9 <span class="count">(67)</span></span>
                                </div>
                                <h3>Marina Bay Sands Premium</h3>
                                <div class="card-tags">
                                    <span class="tag">Infinity Pool</span>
                                    <span class="tag">Luxury</span>
                                </div>
                                <div class="price-section">
                                    <div class="old-price-row">
                                        <span class="old-price">INR 1,80,000</span>
                                        <span class="save-badge">SAVE INR 30,000</span>
                                    </div>
                                    <div class="new-price-row">
                                        <span class="current-price">INR 1,50,000</span>
                                        <span class="per-adult">/Adult</span>
                                    </div>
                                </div>
                                <div class="card-actions">
                                    <a href="tel:+1234567890" class="btn-phone">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path></svg>
                                    </a>
                                    <a href="#" class="btn-request">Get a quote</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Next Arrow -->
                    <div class="slider-arrow slider-arrow-next">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"></polyline></svg>
                    </div>
                </div>
            </div>
            
            <!-- Maldives Category -->
            <div class="category-packages" id="maldives">
                <div class="packages-slider-container">
                    <!-- Prev Arrow -->
                    <div class="slider-arrow slider-arrow-prev">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="15 18 9 12 15 6"></polyline></svg>
                    </div>
                    <div class="packages-grid">
                        <!-- Maldives Card 1 -->
                        <div class="package-card">
                            <div class="card-img">
                                <img src="https://images.unsplash.com/photo-1507525428034-b723cf961d3e?auto=format&fit=crop&q=80&w=800" alt="Maldives Beachfront">
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
                                    <span class="duration">5 days & 4 nights</span>
                                    <span class="rating"><span class="star">★</span> 4.9 <span class="count">(42)</span></span>
                                </div>
                                <h3>Maldives Beachfront Retreat</h3>
                                <div class="card-tags">
                                    <span class="tag">Beach Villa</span>
                                    <span class="tag">All Inclusive</span>
                                </div>
                                <div class="price-section">
                                    <div class="old-price-row">
                                        <span class="old-price">INR 1,60,000</span>
                                        <span class="save-badge">SAVE INR 30,000</span>
                                    </div>
                                    <div class="new-price-row">
                                        <span class="current-price">INR 1,30,000</span>
                                        <span class="per-adult">/Adult</span>
                                    </div>
                                </div>
                                <div class="card-actions">
                                    <a href="tel:+1234567890" class="btn-phone">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path></svg>
                                    </a>
                                    <a href="#" class="btn-request">Get a quote</a>
                                </div>
                            </div>
                        </div>

                        <!-- Maldives Card 2 -->
                        <div class="package-card">
                            <div class="card-img">
                                <img src="https://images.unsplash.com/photo-1439066615861-d1af74d74000?auto=format&fit=crop&q=80&w=800" alt="Maldives Water Villa">
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
                                    <span class="duration">7 days & 6 nights</span>
                                    <span class="rating"><span class="star">★</span> 4.9 <span class="count">(88)</span></span>
                                </div>
                                <h3>Maldives Water Villa Luxury</h3>
                                <div class="card-tags">
                                    <span class="tag">Water Villa</span>
                                    <span class="tag">Private Pool</span>
                                </div>
                                <div class="price-section">
                                    <div class="old-price-row">
                                        <span class="old-price">INR 2,90,000</span>
                                        <span class="save-badge">SAVE INR 50,000</span>
                                    </div>
                                    <div class="new-price-row">
                                        <span class="current-price">INR 2,40,000</span>
                                        <span class="per-adult">/Adult</span>
                                    </div>
                                </div>
                                <div class="card-actions">
                                    <a href="tel:+1234567890" class="btn-phone">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path></svg>
                                    </a>
                                    <a href="#" class="btn-request">Get a quote</a>
                                </div>
                            </div>
                        </div>

                        <!-- Maldives Card 3 -->
                        <div class="package-card">
                            <div class="card-img">
                                <img src="https://images.unsplash.com/photo-1505118380757-91f5f5632de0?auto=format&fit=crop&q=80&w=800" alt="Maldives Island Adventure">
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
                                    <span class="duration">6 days & 5 nights</span>
                                    <span class="rating"><span class="star">★</span> 4.7 <span class="count">(23)</span></span>
                                </div>
                                <h3>Maldives Island Adventure</h3>
                                <div class="card-tags">
                                    <span class="tag">Snorkeling</span>
                                    <span class="tag">Speedboat</span>
                                </div>
                                <div class="price-section">
                                    <div class="old-price-row">
                                        <span class="old-price">INR 1,20,000</span>
                                        <span class="save-badge">SAVE INR 20,000</span>
                                    </div>
                                    <div class="new-price-row">
                                        <span class="current-price">INR 1,00,000</span>
                                        <span class="per-adult">/Adult</span>
                                    </div>
                                </div>
                                <div class="card-actions">
                                    <a href="tel:+1234567890" class="btn-phone">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path></svg>
                                    </a>
                                    <a href="#" class="btn-request">Get a quote</a>
                                </div>
                            </div>
                        </div>

                        <!-- Maldives Card 4 -->
                        <div class="package-card">
                            <div class="card-img">
                                <img src="https://images.unsplash.com/photo-1544550581-5f7ceaf7f992?auto=format&fit=crop&q=80&w=800" alt="Club Rannalhi">
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
                                    <span class="duration">5 days & 4 nights</span>
                                    <span class="rating"><span class="star">★</span> 4.8 <span class="count">(39)</span></span>
                                </div>
                                <h3>Adaaran Club Rannalhi Stay</h3>
                                <div class="card-tags">
                                    <span class="tag">Overwater Villa</span>
                                    <span class="tag">All Inclusive</span>
                                </div>
                                <div class="price-section">
                                    <div class="old-price-row">
                                        <span class="old-price">INR 1,45,000</span>
                                        <span class="save-badge">SAVE INR 25,000</span>
                                    </div>
                                    <div class="new-price-row">
                                        <span class="current-price">INR 1,20,000</span>
                                        <span class="per-adult">/Adult</span>
                                    </div>
                                </div>
                                <div class="card-actions">
                                    <a href="tel:+1234567890" class="btn-phone">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path></svg>
                                    </a>
                                    <a href="#" class="btn-request">Get a quote</a>
                                </div>
                            </div>
                        </div>

                        <!-- Maldives Card 5 -->
                        <div class="package-card">
                            <div class="card-img">
                                <img src="https://images.unsplash.com/photo-1573843225804-bbad83002646?auto=format&fit=crop&q=80&w=800" alt="Taj Exotica">
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
                                    <span class="duration">6 days & 5 nights</span>
                                    <span class="rating"><span class="star">★</span> 4.9 <span class="count">(51)</span></span>
                                </div>
                                <h3>Taj Exotica Resort Honeymoon</h3>
                                <div class="card-tags">
                                    <span class="tag">Taj Exotica</span>
                                    <span class="tag">Private Butler</span>
                                </div>
                                <div class="price-section">
                                    <div class="old-price-row">
                                        <span class="old-price">INR 3,20,000</span>
                                        <span class="save-badge">SAVE INR 50,000</span>
                                    </div>
                                    <div class="new-price-row">
                                        <span class="current-price">INR 2,70,000</span>
                                        <span class="per-adult">/Adult</span>
                                    </div>
                                </div>
                                <div class="card-actions">
                                    <a href="tel:+1234567890" class="btn-phone">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path></svg>
                                    </a>
                                    <a href="#" class="btn-request">Get a quote</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Next Arrow -->
                    <div class="slider-arrow slider-arrow-next">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"></polyline></svg>
                    </div>
                </div>
            </div>
            
            <!-- Bali Category -->
            <div class="category-packages" id="bali">
                <div class="packages-slider-container">
                    <!-- Prev Arrow -->
                    <div class="slider-arrow slider-arrow-prev">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="15 18 9 12 15 6"></polyline></svg>
                    </div>
                    <div class="packages-grid">
                        <!-- Bali Card 1 -->
                        <div class="package-card">
                            <div class="card-img">
                                <img src="https://images.unsplash.com/photo-1537996194471-e657df975ab4?auto=format&fit=crop&q=80&w=800" alt="Bali Jungle">
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
                                    <span class="duration">5 days & 4 nights</span>
                                    <span class="rating"><span class="star">★</span> 4.8 <span class="count">(64)</span></span>
                                </div>
                                <h3>Ubud Jungle Resort Stay</h3>
                                <div class="card-tags">
                                    <span class="tag">Jungle Villa</span>
                                    <span class="tag">Breakfast</span>
                                </div>
                                <div class="price-section">
                                    <div class="old-price-row">
                                        <span class="old-price">INR 85,000</span>
                                        <span class="save-badge">SAVE INR 15,000</span>
                                    </div>
                                    <div class="new-price-row">
                                        <span class="current-price">INR 70,000</span>
                                        <span class="per-adult">/Adult</span>
                                    </div>
                                </div>
                                <div class="card-actions">
                                    <a href="tel:+1234567890" class="btn-phone">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 .7A2 2 0 0 1 22 16.92z"></path></svg>
                                    </a>
                                    <a href="#" class="btn-request">Get a quote</a>
                                </div>
                            </div>
                        </div>

                        <!-- Bali Card 2 -->
                        <div class="package-card">
                            <div class="card-img">
                                <img src="https://images.unsplash.com/photo-1539367628448-4bc5c9d171c8?auto=format&fit=crop&q=80&w=800" alt="Bali Beachfront">
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
                                    <span class="duration">6 days & 5 nights</span>
                                    <span class="rating"><span class="star">★</span> 4.9 <span class="count">(53)</span></span>
                                </div>
                                <h3>Seminyak Beachfront Villa</h3>
                                <div class="card-tags">
                                    <span class="tag">Beachfront</span>
                                    <span class="tag">Private Pool</span>
                                </div>
                                <div class="price-section">
                                    <div class="old-price-row">
                                        <span class="old-price">INR 1,40,000</span>
                                        <span class="save-badge">SAVE INR 25,000</span>
                                    </div>
                                    <div class="new-price-row">
                                        <span class="current-price">INR 1,15,000</span>
                                        <span class="per-adult">/Adult</span>
                                    </div>
                                </div>
                                <div class="card-actions">
                                    <a href="tel:+1234567890" class="btn-phone">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path></svg>
                                    </a>
                                    <a href="#" class="btn-request">Get a quote</a>
                                </div>
                            </div>
                        </div>

                        <!-- Bali Card 3 -->
                        <div class="package-card">
                            <div class="card-img">
                                <img src="https://images.unsplash.com/photo-1518156677180-95a2893f3e9f?auto=format&fit=crop&q=80&w=800" alt="Bali Temple">
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
                                    <span class="duration">7 days & 6 nights</span>
                                    <span class="rating"><span class="star">★</span> 4.7 <span class="count">(31)</span></span>
                                </div>
                                <h3>Bali Cultural Explorer</h3>
                                <div class="card-tags">
                                    <span class="tag">Temple Tour</span>
                                    <span class="tag">Private Car</span>
                                </div>
                                <div class="price-section">
                                    <div class="old-price-row">
                                        <span class="old-price">INR 95,000</span>
                                        <span class="save-badge">SAVE INR 15,000</span>
                                    </div>
                                    <div class="new-price-row">
                                        <span class="current-price">INR 80,000</span>
                                        <span class="per-adult">/Adult</span>
                                    </div>
                                </div>
                                <div class="card-actions">
                                    <a href="tel:+1234567890" class="btn-phone">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path></svg>
                                    </a>
                                    <a href="#" class="btn-request">Get a quote</a>
                                </div>
                            </div>
                        </div>

                        <!-- Bali Card 4 -->
                        <div class="package-card">
                            <div class="card-img">
                                <img src="https://images.unsplash.com/photo-1538964173425-93884d6680c0?auto=format&fit=crop&q=80&w=800" alt="Nusa Penida">
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
                                    <span class="duration">5 days & 4 nights</span>
                                    <span class="rating"><span class="star">★</span> 4.8 <span class="count">(49)</span></span>
                                </div>
                                <h3>Nusa Penida Island Tour</h3>
                                <div class="card-tags">
                                    <span class="tag">Nusa Penida</span>
                                    <span class="tag">Snorkeling</span>
                                </div>
                                <div class="price-section">
                                    <div class="old-price-row">
                                        <span class="old-price">INR 65,000</span>
                                        <span class="save-badge">SAVE INR 10,000</span>
                                    </div>
                                    <div class="new-price-row">
                                        <span class="current-price">INR 55,000</span>
                                        <span class="per-adult">/Adult</span>
                                    </div>
                                </div>
                                <div class="card-actions">
                                    <a href="tel:+1234567890" class="btn-phone">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path></svg>
                                    </a>
                                    <a href="#" class="btn-request">Get a quote</a>
                                </div>
                            </div>
                        </div>

                        <!-- Bali Card 5 -->
                        <div class="package-card">
                            <div class="card-img">
                                <img src="https://images.unsplash.com/photo-1552083375-1447ce886485?auto=format&fit=crop&q=80&w=800" alt="Uluwatu Cliff">
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
                                    <span class="duration">6 days & 5 nights</span>
                                    <span class="rating"><span class="star">★</span> 4.9 <span class="count">(72)</span></span>
                                </div>
                                <h3>Uluwatu Cliffside Romantic Stay</h3>
                                <div class="card-tags">
                                    <span class="tag">Cliffside</span>
                                    <span class="tag">Private Pool</span>
                                </div>
                                <div class="price-section">
                                    <div class="old-price-row">
                                        <span class="old-price">INR 1,25,000</span>
                                        <span class="save-badge">SAVE INR 20,000</span>
                                    </div>
                                    <div class="new-price-row">
                                        <span class="current-price">INR 1,05,000</span>
                                        <span class="per-adult">/Adult</span>
                                    </div>
                                </div>
                                <div class="card-actions">
                                    <a href="tel:+1234567890" class="btn-phone">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path></svg>
                                    </a>
                                    <a href="#" class="btn-request">Get a quote</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Next Arrow -->
                    <div class="slider-arrow slider-arrow-next">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"></polyline></svg>
                    </div>
                </div>
            </div>

            <!-- Japan Category -->
            <div class="category-packages" id="japan">
                <div class="packages-slider-container">
                    <!-- Prev Arrow -->
                    <div class="slider-arrow slider-arrow-prev">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="15 18 9 12 15 6"></polyline></svg>
                    </div>
                    <div class="packages-grid">
                        <!-- Japan Card 1 -->
                        <div class="package-card">
                            <div class="card-img">
                                <img src="https://images.unsplash.com/photo-1493976040374-85c8e12f0c0e?auto=format&fit=crop&q=80&w=800" alt="Japan Kyoto">
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
                                    <span class="duration">8 days & 7 nights</span>
                                    <span class="rating"><span class="star">★</span> 4.9 <span class="count">(112)</span></span>
                                </div>
                                <h3>Tokyo & Kyoto Highlights</h3>
                                <div class="card-tags">
                                    <span class="tag">Bullet Train</span>
                                    <span class="tag">City Tour</span>
                                </div>
                                <div class="price-section">
                                    <div class="old-price-row">
                                        <span class="old-price">INR 2,50,000</span>
                                        <span class="save-badge">SAVE INR 40,000</span>
                                    </div>
                                    <div class="new-price-row">
                                        <span class="current-price">INR 2,10,000</span>
                                        <span class="per-adult">/Adult</span>
                                    </div>
                                </div>
                                <div class="card-actions">
                                    <a href="tel:+1234567890" class="btn-phone">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path></svg>
                                    </a>
                                    <a href="#" class="btn-request">Get a quote</a>
                                </div>
                            </div>
                        </div>

                        <!-- Japan Card 2 -->
                        <div class="package-card">
                            <div class="card-img">
                                <img src="https://images.unsplash.com/photo-1490761668535-35497054764d?auto=format&fit=crop&q=80&w=800" alt="Mount Fuji">
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
                                    <span class="duration">5 days & 4 nights</span>
                                    <span class="rating"><span class="star">★</span> 4.8 <span class="count">(47)</span></span>
                                </div>
                                <h3>Mount Fuji & Hakone Onsen</h3>
                                <div class="card-tags">
                                    <span class="tag">Hot Spring</span>
                                    <span class="tag">Fuji View</span>
                                </div>
                                <div class="price-section">
                                    <div class="old-price-row">
                                        <span class="old-price">INR 1,80,000</span>
                                        <span class="save-badge">SAVE INR 30,000</span>
                                    </div>
                                    <div class="new-price-row">
                                        <span class="current-price">INR 1,50,000</span>
                                        <span class="per-adult">/Adult</span>
                                    </div>
                                </div>
                                <div class="card-actions">
                                    <a href="tel:+1234567890" class="btn-phone">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path></svg>
                                    </a>
                                    <a href="#" class="btn-request">Get a quote</a>
                                </div>
                            </div>
                        </div>

                        <!-- Japan Card 3 -->
                        <div class="package-card">
                            <div class="card-img">
                                <img src="https://images.unsplash.com/photo-1503899036084-c55cdd92da26?auto=format&fit=crop&q=80&w=800" alt="Osaka Food">
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
                                    <span class="duration">6 days & 5 nights</span>
                                    <span class="rating"><span class="star">★</span> 4.7 <span class="count">(39)</span></span>
                                </div>
                                <h3>Osaka & Nara Foodie Adventure</h3>
                                <div class="card-tags">
                                    <span class="tag">Food Tour</span>
                                    <span class="tag">Local Guide</span>
                                </div>
                                <div class="price-section">
                                    <div class="old-price-row">
                                        <span class="old-price">INR 1,45,000</span>
                                        <span class="save-badge">SAVE INR 25,000</span>
                                    </div>
                                    <div class="new-price-row">
                                        <span class="current-price">INR 1,20,000</span>
                                        <span class="per-adult">/Adult</span>
                                    </div>
                                </div>
                                <div class="card-actions">
                                    <a href="tel:+1234567890" class="btn-phone">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path></svg>
                                    </a>
                                    <a href="#" class="btn-request">Get a quote</a>
                                </div>
                            </div>
                        </div>

                        <!-- Japan Card 4 -->
                        <div class="package-card">
                            <div class="card-img">
                                <img src="https://images.unsplash.com/photo-1542051841857-5f90071e7989?auto=format&fit=crop&q=80&w=800" alt="Hokkaido Snow">
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
                                    <span class="duration">7 days & 6 nights</span>
                                    <span class="rating"><span class="star">★</span> 4.8 <span class="count">(58)</span></span>
                                </div>
                                <h3>Hokkaido Winter Magic</h3>
                                <div class="card-tags">
                                    <span class="tag">Hokkaido</span>
                                    <span class="tag">Snow Resort</span>
                                </div>
                                <div class="price-section">
                                    <div class="old-price-row">
                                        <span class="old-price">INR 2,20,000</span>
                                        <span class="save-badge">SAVE INR 40,000</span>
                                    </div>
                                    <div class="new-price-row">
                                        <span class="current-price">INR 1,80,000</span>
                                        <span class="per-adult">/Adult</span>
                                    </div>
                                </div>
                                <div class="card-actions">
                                    <a href="tel:+1234567890" class="btn-phone">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path></svg>
                                    </a>
                                    <a href="#" class="btn-request">Get a quote</a>
                                </div>
                            </div>
                        </div>

                        <!-- Japan Card 5 -->
                        <div class="package-card">
                            <div class="card-img">
                                <img src="https://images.unsplash.com/photo-1542931287-023b922fa89b?auto=format&fit=crop&q=80&w=800" alt="Okinawa Beach">
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
                                    <span class="duration">6 days & 5 nights</span>
                                    <span class="rating"><span class="star">★</span> 4.9 <span class="count">(41)</span></span>
                                </div>
                                <h3>Okinawa Tropical Beach Getaway</h3>
                                <div class="card-tags">
                                    <span class="tag">Okinawa</span>
                                    <span class="tag">Beach Resort</span>
                                </div>
                                <div class="price-section">
                                    <div class="old-price-row">
                                        <span class="old-price">INR 1,90,000</span>
                                        <span class="save-badge">SAVE INR 30,000</span>
                                    </div>
                                    <div class="new-price-row">
                                        <span class="current-price">INR 1,60,000</span>
                                        <span class="per-adult">/Adult</span>
                                    </div>
                                </div>
                                <div class="card-actions">
                                    <a href="tel:+1234567890" class="btn-phone">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path></svg>
                                    </a>
                                    <a href="#" class="btn-request">Get a quote</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Next Arrow -->
                    <div class="slider-arrow slider-arrow-next">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"></polyline></svg>
                    </div>
                </div>
            </div>

            <!-- Kerala Category -->
            <div class="category-packages" id="kerala">
                <div class="packages-slider-container">
                    <!-- Prev Arrow -->
                    <div class="slider-arrow slider-arrow-prev">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="15 18 9 12 15 6"></polyline></svg>
                    </div>
                    <div class="packages-grid">
                        <!-- Kerala Card 1 -->
                        <div class="package-card">
                            <div class="card-img">
                                <img src="https://images.unsplash.com/photo-1593693397690-362cb9666fc2?auto=format&fit=crop&q=80&w=800" alt="Kerala Tea Gardens">
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
                                    <span class="duration">4 days & 3 nights</span>
                                    <span class="rating"><span class="star">★</span> 4.7 <span class="count">(51)</span></span>
                                </div>
                                <h3>Munnar Hills & Tea Gardens</h3>
                                <div class="card-tags">
                                    <span class="tag">Hills</span>
                                    <span class="tag">Tea Garden</span>
                                </div>
                                <div class="price-section">
                                    <div class="old-price-row">
                                        <span class="old-price">INR 45,000</span>
                                        <span class="save-badge">SAVE INR 8,000</span>
                                    </div>
                                    <div class="new-price-row">
                                        <span class="current-price">INR 37,000</span>
                                        <span class="per-adult">/Adult</span>
                                    </div>
                                </div>
                                <div class="card-actions">
                                    <a href="tel:+1234567890" class="btn-phone">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path></svg>
                                    </a>
                                    <a href="#" class="btn-request">Get a quote</a>
                                </div>
                            </div>
                        </div>

                        <!-- Kerala Card 2 -->
                        <div class="package-card">
                            <div class="card-img">
                                <img src="https://images.unsplash.com/photo-1593693411515-c202e974eb8f?auto=format&fit=crop&q=80&w=800" alt="Kerala Backwaters">
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
                                    <span class="duration">3 days & 2 nights</span>
                                    <span class="rating"><span class="star">★</span> 4.8 <span class="count">(73)</span></span>
                                </div>
                                <h3>Alleppey Houseboat Cruise</h3>
                                <div class="card-tags">
                                    <span class="tag">Backwaters</span>
                                    <span class="tag">Houseboat</span>
                                </div>
                                <div class="price-section">
                                    <div class="old-price-row">
                                        <span class="old-price">INR 35,000</span>
                                        <span class="save-badge">SAVE INR 7,000</span>
                                    </div>
                                    <div class="new-price-row">
                                        <span class="current-price">INR 28,000</span>
                                        <span class="per-adult">/Adult</span>
                                    </div>
                                </div>
                                <div class="card-actions">
                                    <a href="tel:+1234567890" class="btn-phone">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path></svg>
                                    </a>
                                    <a href="#" class="btn-request">Get a quote</a>
                                </div>
                            </div>
                        </div>

                        <!-- Kerala Card 3 -->
                        <div class="package-card">
                            <div class="card-img">
                                <img src="https://images.unsplash.com/photo-1502086223501-7ea6ecd79368?auto=format&fit=crop&q=80&w=800" alt="Kerala Complete">
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
                                    <span class="duration">7 days & 6 nights</span>
                                    <span class="rating"><span class="star">★</span> 4.9 <span class="count">(92)</span></span>
                                </div>
                                <h3>Kerala Complete Experience</h3>
                                <div class="card-tags">
                                    <span class="tag">Hills</span>
                                    <span class="tag">Houseboat</span>
                                    <span class="tag">Beach</span>
                                </div>
                                <div class="price-section">
                                    <div class="old-price-row">
                                        <span class="old-price">INR 85,000</span>
                                        <span class="save-badge">SAVE INR 15,000</span>
                                    </div>
                                    <div class="new-price-row">
                                        <span class="current-price">INR 70,000</span>
                                        <span class="per-adult">/Adult</span>
                                    </div>
                                </div>
                                <div class="card-actions">
                                    <a href="tel:+1234567890" class="btn-phone">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path></svg>
                                    </a>
                                    <a href="#" class="btn-request">Get a quote</a>
                                </div>
                            </div>
                        </div>

                        <!-- Kerala Card 4 -->
                        <div class="package-card">
                            <div class="card-img">
                                <img src="https://images.unsplash.com/photo-1546708973-b339540b5162?auto=format&fit=crop&q=80&w=800" alt="Wayanad Treehouse">
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
                                    <span class="duration">5 days & 4 nights</span>
                                    <span class="rating"><span class="star">★</span> 4.8 <span class="count">(37)</span></span>
                                </div>
                                <h3>Wayanad Treehouse & Wildlife</h3>
                                <div class="card-tags">
                                    <span class="tag">Treehouse</span>
                                    <span class="tag">Safari</span>
                                </div>
                                <div class="price-section">
                                    <div class="old-price-row">
                                        <span class="old-price">INR 48,000</span>
                                        <span class="save-badge">SAVE INR 8,000</span>
                                    </div>
                                    <div class="new-price-row">
                                        <span class="current-price">INR 40,000</span>
                                        <span class="per-adult">/Adult</span>
                                    </div>
                                </div>
                                <div class="card-actions">
                                    <a href="tel:+1234567890" class="btn-phone">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path></svg>
                                    </a>
                                    <a href="#" class="btn-request">Get a quote</a>
                                </div>
                            </div>
                        </div>

                        <!-- Kerala Card 5 -->
                        <div class="package-card">
                            <div class="card-img">
                                <img src="https://images.unsplash.com/photo-1589982441164-325cfccb9557?auto=format&fit=crop&q=80&w=800" alt="Kovalam Kovalam">
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
                                    <span class="duration">6 days & 5 nights</span>
                                    <span class="rating"><span class="star">★</span> 4.9 <span class="count">(45)</span></span>
                                </div>
                                <h3>Kovalam Beach & Varkala Cliffs</h3>
                                <div class="card-tags">
                                    <span class="tag">Beach</span>
                                    <span class="tag">Varkala Cliff</span>
                                </div>
                                <div class="price-section">
                                    <div class="old-price-row">
                                        <span class="old-price">INR 44,000</span>
                                        <span class="save-badge">SAVE INR 6,000</span>
                                    </div>
                                    <div class="new-price-row">
                                        <span class="current-price">INR 38,000</span>
                                        <span class="per-adult">/Adult</span>
                                    </div>
                                </div>
                                <div class="card-actions">
                                    <a href="tel:+1234567890" class="btn-phone">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path></svg>
                                    </a>
                                    <a href="#" class="btn-request">Get a quote</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Next Arrow -->
                    <div class="slider-arrow slider-arrow-next">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"></polyline></svg>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>

<?php include_once 'includes/footer.php'; ?>