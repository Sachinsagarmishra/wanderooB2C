<?php
$pageTitle = "Privacy Policy";
$pageDesc = "Read Wanderoo's privacy policy to understand how enquiry details, contact information, and website data are collected and used.";
$bodyClass = "policy-page";
include_once 'includes/header.php';

$contactEmail = get_setting('contact_email', 'support@wanderoo.world');
?>

<main>
    <div class="about-hero-banner">
        <img src="<?php echo SITE_PATH; ?>/assets/img/group-hiking.png" alt="Privacy Policy" class="about-hero-bg">
        <div class="about-hero-overlay"></div>
        <div class="about-hero-content">
            <span class="about-breadcrumb-text"><a href="<?php echo SITE_PATH; ?>/">Home</a> &raquo; Privacy Policy</span>
            <h1 class="about-hero-title">Privacy Policy</h1>
        </div>
    </div>

    <section class="policy-container">
        <p class="policy-updated">Last updated: May 26, 2026</p>

        <h2>1. Information We Collect</h2>
        <p>When you contact Wanderoo or submit a travel enquiry, we may collect your name, email address, phone number, destination preferences, travel dates, number of nights, traveller details, room preferences, and any notes you choose to share.</p>

        <h2>2. How We Use Your Information</h2>
        <p>We use your information to respond to enquiries, prepare travel packages, coordinate bookings, provide customer support, improve our services, and send relevant communication about your trip request.</p>

        <h2>3. Sharing With Travel Partners</h2>
        <p>To help plan or fulfil your trip, we may share necessary details with hotels, resorts, transport providers, destination partners, or other travel service providers. We share only the information required for the requested service.</p>

        <h2>4. Website Data</h2>
        <p>Our website may collect basic technical data such as page visits, browser information, referring pages, and device details through hosting logs or analytics tools. This helps us monitor performance and improve the website.</p>

        <h2>5. Data Security</h2>
        <p>We take reasonable measures to protect your information from unauthorised access, misuse, loss, or disclosure. However, no online transmission or storage method is completely secure.</p>

        <h2>6. Data Retention</h2>
        <p>We retain enquiry and booking-related information for as long as needed to provide services, maintain business records, resolve disputes, and meet legal or operational requirements.</p>

        <h2>7. Your Choices</h2>
        <p>You may request access, correction, or deletion of your personal information by contacting us. Some records may need to be retained where required for legal, accounting, or service-related purposes.</p>

        <h2>8. Third-Party Links</h2>
        <p>Our website may link to third-party websites or services. We are not responsible for the privacy practices or content of those external websites.</p>

        <h2>9. Contact Us</h2>
        <p>If you have questions about this Privacy Policy, contact us at <a href="mailto:<?php echo htmlspecialchars($contactEmail); ?>"><?php echo htmlspecialchars($contactEmail); ?></a>.</p>
    </section>
</main>

<?php include_once 'includes/footer.php'; ?>
