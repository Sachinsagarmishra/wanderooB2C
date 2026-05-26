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
                    <li><a href="<?php echo SITE_PATH; ?>/destination/maldives">Maldives</a></li>
                    <li><a href="<?php echo SITE_PATH; ?>/destination/singapore">Singapore</a></li>
                    <li><a href="<?php echo SITE_PATH; ?>/destination/bali">Bali</a></li>
                </ul>
            </div>
            <div class="footer-col">
                <h3>Policies:</h3>
                <ul>
                    <li><a href="#">Terms & Condition</a></li>
                    <li><a href="#">Privacy Policy</a></li>
                    <li><a href="#">Sitemap</a></li>
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
        <svg viewBox="0 0 32 32" class="whatsapp-svg-icon" xmlns="http://www.w3.org/2000/svg" width="24" height="24">
            <path d="M19.11 17.29c-.37-.18-2.17-1.07-2.51-1.19-.33-.12-.58-.18-.82.18-.24.36-.95 1.19-1.16 1.43-.22.24-.43.27-.8.09a10.08 10.08 0 0 1-2.97-1.83 11.13 11.13 0 0 1-2.05-2.55c-.22-.38-.02-.59.16-.77.17-.16.38-.43.57-.65.19-.22.25-.37.38-.62.13-.25.06-.47-.03-.65-.09-.18-.82-1.98-1.13-2.72-.3-.72-.6-0.62-.82-.63-.21-.01-.45-.01-.69-.01a1.32 1.32 0 0 0-.96.45c-.33.36-1.25 1.22-1.25 2.98 0 1.76 1.28 3.46 1.46 3.7.18.24 2.52 3.85 6.1 5.39.85.37 1.52.59 2.04.75.86.27 1.64.23 2.26.14.69-.1 2.17-.89 2.48-1.75.31-.86.31-1.6.22-1.75-.09-.15-.34-.24-.71-.42zM16 2A14 14 0 0 0 2 16c0 2.51.66 4.96 1.91 7.12L2 29.8l7-1.84A13.88 13.88 0 0 0 16 30c7.73 0 14-6.27 14-14S23.73 2 16 2z" fill="#fff"/>
        </svg>
    </a>

    <script src="<?php echo SITE_PATH; ?>/assets/js/main.js?v=2.7"></script>
    <?php include_once __DIR__ . '/enquiry-modal.php'; ?>
</body>
</html>
