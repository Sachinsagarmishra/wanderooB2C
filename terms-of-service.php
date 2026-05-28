<?php
$pageTitle = "Terms of Service";
$pageDesc = "Read Wanderoo's terms of service for travel enquiries, package information, bookings, payments, cancellations, and website use.";
$bodyClass = "policy-page";
include_once 'includes/header.php';

$contactEmail = get_setting('contact_email', 'support@wanderoo.world');
?>

<main>
    <div class="about-hero-banner">
        <img src="<?php echo SITE_PATH; ?>/assets/img/group-hiking.png" alt="Terms of Service" class="about-hero-bg">
        <div class="about-hero-overlay"></div>
        <div class="about-hero-content">
            <span class="about-breadcrumb-text"><a href="<?php echo SITE_PATH; ?>/">Home</a> &raquo; Terms of Service</span>
            <h1 class="about-hero-title">Terms of Service</h1>
        </div>
    </div>

    <section class="policy-container">
        <p class="policy-updated">Last updated: May 26, 2026</p>

        <h2>1. Acceptance of Terms</h2>
        <p>By using the Wanderoo website, submitting an enquiry, or communicating with us about travel services, you agree to these Terms of Service.</p>

        <h2>2. Travel Information and Package Details</h2>
        <p>Package details, prices, inclusions, exclusions, images, hotel names, and availability shown on the website are for general information and may change based on supplier availability, seasonality, currency changes, and final booking confirmation.</p>

        <h2>3. Enquiries and Quotes</h2>
        <p>Submitting an enquiry does not create a confirmed booking. A booking is confirmed only after final itinerary acceptance, payment requirements, and supplier confirmations are completed.</p>

        <h2>4. Payments</h2>
        <p>Payment schedules, accepted payment methods, taxes, fees, and any non-refundable amounts will be communicated before booking confirmation. Failure to pay on time may result in cancellation or price changes.</p>

        <h2>5. Cancellations and Changes</h2>
        <p>Cancellation, amendment, and refund terms depend on the hotels, airlines, transport providers, destination partners, and other suppliers involved in your booking. Wanderoo will share applicable terms wherever available before confirmation.</p>

        <h2>6. Traveller Responsibilities</h2>
        <p>You are responsible for ensuring that traveller names, passport details, visas, travel documents, health requirements, insurance, and other trip information are accurate and valid for travel.</p>

        <h2>7. Third-Party Suppliers</h2>
        <p>Travel services may be provided by independent third-party suppliers. Wanderoo is not responsible for delays, cancellations, service changes, closures, weather events, government restrictions, or supplier actions outside our control.</p>

        <h2>8. Website Use</h2>
        <p>You agree not to misuse the website, attempt unauthorised access, disrupt website operations, copy content for commercial use without permission, or submit false or misleading information.</p>

        <h2>9. Intellectual Property and Image Disclaimer</h2>
        <p>All content on this website, including text, graphics, logos, and layout, is owned by or licensed to Wanderoo. The images used on our website are either copyright-free, purchased under standard commercial licenses, or sourced from third-party travel platforms. We respect all intellectual property rights and make every effort to attribute appropriate credit to original creators. If you believe any image or content has been used on our website without proper authorization or credit, please contact us at <a href="mailto:<?php echo htmlspecialchars($contactEmail); ?>"><?php echo htmlspecialchars($contactEmail); ?></a>, and we will review and update or remove it immediately.</p>

        <h2>10. Limitation of Liability</h2>
        <p>To the maximum extent permitted by law, Wanderoo is not liable for indirect, incidental, consequential, or unforeseen losses arising from website use, travel supplier changes, or events outside our reasonable control.</p>

        <h2>11. Contact Us</h2>
        <p>If you have questions about these Terms of Service, contact us at <a href="mailto:<?php echo htmlspecialchars($contactEmail); ?>"><?php echo htmlspecialchars($contactEmail); ?></a>.</p>
    </section>
</main>

<?php include_once 'includes/footer.php'; ?>
