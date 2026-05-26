    <footer>
        <div class="footer-container">
            <div class="footer-col brand-col">
                <div class="footer-brand">
                    <img src="<?php echo SITE_PATH; ?>/assets/img/wanderoo_Logo.png" alt="Wanderoo Logo">
                </div>
            </div>
            <div class="footer-col">
                <h3>Quick Links</h3>
                <ul>
                    <li><a href="<?php echo SITE_PATH; ?>/">Home</a></li>
                    <li><a href="<?php echo SITE_PATH; ?>/about-us">About Us</a></li>
                    <li><a href="<?php echo SITE_PATH; ?>/contact">Contact Us</a></li>
                </ul>
            </div>
            <div class="footer-col">
                <h3>Top Destinations:</h3>
                <ul>
                    <?php
                    try {
                        $stmtFootDests = $pdo->query("SELECT slug, name FROM destinations ORDER BY sort_order, name LIMIT 4");
                        while ($footRow = $stmtFootDests->fetch()) {
                            echo '<li><a href="' . SITE_PATH . '/destination/' . htmlspecialchars($footRow['slug']) . '">' . htmlspecialchars($footRow['name']) . '</a></li>';
                        }
                    } catch (Exception $e) {
                        echo '<li><a href="' . SITE_PATH . '/destination/maldives">Maldives</a></li>';
                        echo '<li><a href="' . SITE_PATH . '/destination/singapore">Singapore</a></li>';
                        echo '<li><a href="' . SITE_PATH . '/destination/bali">Bali</a></li>';
                    }
                    ?>
                </ul>
            </div>
            <div class="footer-col">
                <h3>Policies:</h3>
                <ul>
                    <li><a href="<?php echo SITE_PATH; ?>/terms-of-service">Terms of Service</a></li>
                    <li><a href="<?php echo SITE_PATH; ?>/privacy-policy">Privacy Policy</a></li>
                    <li><a href="<?php echo SITE_PATH; ?>/sitemap.xml">Sitemap</a></li>
                </ul>
            </div>
        </div>
        <div class="footer-bottom-bar">
            <p>Copyright &copy; 2025. All Rights Reserved.</p>
        </div>
    </footer>
    
    <?php
    $whatsappNum = get_setting('contact_whatsapp', '919113515462');
    ?>
    <!-- Floating WhatsApp Widget -->
    <a href="https://wa.me/<?php echo htmlspecialchars($whatsappNum); ?>" class="whatsapp-float-btn" target="_blank" title="Chat on WhatsApp">
        <img src="<?php echo SITE_PATH; ?>/assets/img/whatsapp.svg" alt="WhatsApp" style="width: 100%; height: 100%; border-radius: 50%; object-fit: cover; display: block;">
    </a>

    <script src="<?php echo SITE_PATH; ?>/assets/js/main.js?v=2.9"></script>
    <?php include_once __DIR__ . '/enquiry-modal.php'; ?>
</body>
</html>
