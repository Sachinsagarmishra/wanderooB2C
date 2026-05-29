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

    <?php if (get_setting('ai_agent_enabled', '0') === '1'): ?>
    <!-- Joey AI Chatbot Widget -->
    <link rel="stylesheet" href="<?php echo SITE_PATH; ?>/assets/css/ai-chatbot.css?v=1.0">

    <?php
    // Load quick-start cards from settings
    $joeyQuickCards = [];
    for ($qi = 1; $qi <= 6; $qi++) {
        $qIcon  = get_setting("ai_quick_card_{$qi}_icon", '');
        $qTitle = get_setting("ai_quick_card_{$qi}_title", '');
        $qDesc  = get_setting("ai_quick_card_{$qi}_desc", '');
        if (!empty($qTitle)) {
            $joeyQuickCards[] = ['icon' => $qIcon, 'title' => $qTitle, 'desc' => $qDesc];
        }
    }
    $joeyQuickCardsJson = !empty($joeyQuickCards) ? htmlspecialchars(json_encode($joeyQuickCards), ENT_QUOTES, 'UTF-8') : '';
    $joeyAvatarPath = SITE_PATH . '/assets/img/favicon.png';
    ?>

    <!-- Floating Launcher -->
    <button class="joey-launcher" id="joeyLauncher" title="Chat with Joey AI">
        <img src="<?php echo $joeyAvatarPath; ?>" alt="Joey AI">
        <span class="joey-close-icon">✕</span>
    </button>

    <!-- Backdrop -->
    <div class="joey-backdrop" id="joeyBackdrop"></div>

    <!-- Chat Container -->
    <div class="joey-chat-container" id="joeyChatContainer"
         data-api-base="<?php echo SITE_PATH; ?>"
         data-avatar="<?php echo $joeyAvatarPath; ?>"
         data-quick-cards="<?php echo $joeyQuickCardsJson; ?>">

        <!-- Header -->
        <div class="joey-header">
            <img src="<?php echo $joeyAvatarPath; ?>" alt="Joey AI" class="joey-header-avatar">
            <div class="joey-header-info">
                <p class="joey-header-title">Joey AI</p>
                <p class="joey-header-subtitle">Your personal travel advisor</p>
            </div>
            <div class="joey-header-badges">
                <span class="joey-badge joey-badge-encrypted">🔒 ENCRYPTED</span>
                <span class="joey-badge joey-badge-new" id="joeyNewChatBtn" style="cursor: pointer;" title="Start New Conversation">NEW</span>
            </div>
            <button class="joey-close-btn" id="joeyCloseBtn" title="Close">×</button>
        </div>

        <!-- Chat Body -->
        <div class="joey-body" id="joeyChatBody">
            <!-- Welcome screen rendered by JS -->
        </div>

        <!-- Input Footer -->
        <div class="joey-footer">
            <div class="joey-input-container">
                <input type="text" class="joey-input" id="joeyChatInput" placeholder="Ask about destinations, packages..." autocomplete="off">
                <button class="joey-send-btn" id="joeySendBtn" title="Send">
                    <svg viewBox="0 0 24 24"><line x1="22" y1="2" x2="11" y2="13"></line><polygon points="22 2 15 22 11 13 2 9 22 2"></polygon></svg>
                </button>
            </div>
        </div>

        <!-- Credits -->
        <div class="joey-credits">
            <span>shared with Wanderoo for planning</span>
            <span>developed by Joey AI</span>
        </div>

        <!-- Lead Capture Form Overlay -->
        <div class="joey-lead-form" id="joeyLeadForm">
            <div id="joeyLeadFormInner">
                <h3>📋 Let's Connect You!</h3>
                <p>Share your details and a Wanderoo expert will reach out on WhatsApp.</p>
                <input type="text" id="joeyLeadName" placeholder="Your Name" autocomplete="name">
                <input type="email" id="joeyLeadEmail" placeholder="Email Address" autocomplete="email">
                <input type="tel" id="joeyLeadPhone" placeholder="WhatsApp Number (with country code)" autocomplete="tel">
                <button class="joey-lead-submit" id="joeyLeadSubmit">Submit Details</button>
                <button class="joey-lead-cancel" id="joeyLeadCancel">Maybe later</button>
            </div>
            <div class="joey-lead-success" id="joeyLeadSuccess" style="display:none;">
                <span class="joey-checkmark">✅</span>
                <h3>Thank You!</h3>
                <p>A Wanderoo travel expert will contact you shortly.</p>
            </div>
        </div>
    </div>

    <script src="<?php echo SITE_PATH; ?>/assets/js/ai-chatbot.js?v=1.0"></script>
    <?php endif; ?>

    <script src="<?php echo SITE_PATH; ?>/assets/js/main.js?v=2.9"></script>
    <!-- Cloudflare Turnstile -->
    <script src="https://challenges.cloudflare.com/turnstile/v0/api.js" async defer></script>
    <script>
        // Global Turnstile callbacks for the enquiry modal
        function onEnquiryTurnstileSuccess(token) {
            // Token is automatically inserted into the hidden input by Turnstile;
            // hide any captcha error if it was showing
            var banner = document.getElementById('enquiryErrorBanner');
            var bannerText = document.getElementById('enquiryErrorText');
            if (banner && bannerText && bannerText.textContent.indexOf('CAPTCHA') !== -1) {
                banner.style.display = 'none';
            }
        }
        function onEnquiryTurnstileExpired() {
            if (typeof turnstile !== 'undefined') {
                try { turnstile.reset('#enquiryTurnstile'); } catch(e) {}
            }
        }
        function onEnquiryTurnstileError() {
            var banner = document.getElementById('enquiryErrorBanner');
            var bannerText = document.getElementById('enquiryErrorText');
            if (banner && bannerText) {
                bannerText.textContent = 'CAPTCHA failed to load. Please refresh the page and try again.';
                banner.style.display = 'flex';
            }
        }
    </script>
    <?php include_once __DIR__ . '/enquiry-modal.php'; ?>
    <?php include_once __DIR__ . '/timed-popup.php'; ?>
</body>
</html>
