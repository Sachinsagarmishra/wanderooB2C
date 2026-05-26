<?php
$pageTitle = "About Us";
$pageDesc = "Learn more about Wanderoo - we understand travel is more than flights and hotels, pairing you with dedicated destination experts.";
$bodyClass = "about-page";
include_once 'includes/header.php';
?>

<main>
    <!-- About Hero Banner -->
    <div class="about-hero-banner">
        <img src="<?php echo SITE_PATH; ?>/assets/img/group-hiking.png" alt="About Us" class="about-hero-bg">
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
</main>

<?php include_once 'includes/footer.php'; ?>
