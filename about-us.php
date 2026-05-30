<?php
$pageTitle = "About Us";
$pageDesc = "Learn more about Wanderoo - we understand travel is more than flights and hotels, pairing you with dedicated destination experts.";
$bodyClass = "about-page";

require_once __DIR__ . '/config.php';
require_once __DIR__ . '/includes/db.php';

function about_media_image_url($path) {
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

try {
    $stmtAboutTestimonials = $pdo->query("SELECT * FROM testimonials WHERE status = 'active' ORDER BY sort_order, created_at DESC");
    $aboutTestimonials = $stmtAboutTestimonials->fetchAll();
} catch (PDOException $e) {
    $aboutTestimonials = [];
}

include_once 'includes/header.php';
?>

<main>
    <!-- About Hero Banner -->
    <div class="about-hero-banner">
        <img src="<?php echo SITE_PATH; ?>/assets/img/rafting.jpg" alt="About Us" class="about-hero-bg">
        <div class="about-hero-overlay"></div>
        <div class="about-hero-content">
            <span class="about-breadcrumb-text"><a href="<?php echo SITE_PATH; ?>/">Home</a> &raquo; About Us</span>
            <h1 class="about-hero-title">About Us</h1>
        </div>
    </div>

    <!-- About Main Content Section -->
    <div class="about-container">
        <div class="about-header-section">
            <h2 class="about-main-heading">
                <span class="urbanist">Wanderoo isn’t just another booking site -</span><br>
                <span class="playfair italic">we’re your travel mate</span>
            </h2>
        </div>
        
        <div class="about-grid">
            <!-- Left Side: Content Column -->
            <div class="about-content-col">
                <p>We understand that travel is more than flights and hotels. It’s about discovering the world in a way that feels right for you. That’s why we pair you with a dedicated destination expert who knows your dream location inside-out – including the best-kept local secrets.</p>
                <p>Whether you’re planning a luxury island getaway, a family adventure, or a romantic escape, we’ll handle the details so you can focus on enjoying the journey. From your first enquiry to the moment you return home, we’re here to make it smooth, safe, and unforgettable.</p>
                <p>With years of experience curating travel for Australians, we know exactly what makes a trip truly special – and we deliver it with care, clarity, and a personal touch.</p>
            </div>
            
            <!-- Right Side: Image Column -->
            <div class="about-image-col">
                <img src="https://images.unsplash.com/photo-1501555088652-021faa106b9b?auto=format&fit=crop&q=80&w=800" alt="Group of hikers on a trail">
            </div>
        </div>
    </div>

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
    
    <?php if (!empty($aboutTestimonials)): ?>
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
                        <?php foreach ($aboutTestimonials as $testimonial): ?>
                            <?php
                            $testimonialImage = about_media_image_url($testimonial['image_path'] ?? '');
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
                            <p class="faq-answer">International flights are usually not included unless specifically mentioned in your package. This gives you the flexibility to choose the airline, route, and fare that work best for you. If you’d like help finding and booking the best flight options, we’re happy to help you.</p>
                        </div>
                    </div>
                    
                    <!-- Item 3 -->
                    <div class="faq-item">
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
                </div>
                
                <div class="faq-action-row">
                    <a href="<?php echo SITE_PATH; ?>/faqs" class="btn-more-faqs">More FAQ's</a>
                </div>
            </div>
        </div>
    </section>
</main>

<?php include_once 'includes/footer.php'; ?>
