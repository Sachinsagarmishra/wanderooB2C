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

    <section class="honeymooners" style="padding: 80px 40px; max-width: 1280px; margin: 0 auto;">
        <div class="section-header" style="display: flex; justify-content: space-between; align-items: flex-end; margin-bottom: 40px;">
            <div>
                <h2 style="margin-bottom: 10px;"><span class="urbanist">For</span> <span class="playfair italic">Honeymooners</span></h2>
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
                        <div class="city-strip">
                            9D Maldives
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
                            <a href="#" class="btn-request">Request Callback</a>
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
                        <div class="city-strip">
                            4D Prestige Vadoo
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
                            <a href="#" class="btn-request">Request Callback</a>
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
                        <div class="city-strip">
                            7D Sunrise Overwater Villa
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
                            <a href="#" class="btn-request">Request Callback</a>
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
                        <div class="city-strip">
                            3D Ubud • 2D Seminyak
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
                            <a href="#" class="btn-request">Request Callback</a>
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
                        <div class="city-strip">
                            5D Mahe • 3D Praslin
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
                            <a href="#" class="btn-request">Request Callback</a>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Slider Nav Arrow -->
            <div class="slider-arrow-next">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"></polyline></svg>
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