    <footer>
        <div class="footer-content">
            <div class="footer-section">
                <h3 class="logo"><?php echo SITE_NAME; ?></h3>
                <p>Building modern experiences with PHP and MySQL. Scalable, secure, and beautiful.</p>
            </div>
            <div class="footer-section">
                <h3>Quick Links</h3>
                <p><a href="#">Privacy Policy</a></p>
                <p><a href="#">Terms of Service</a></p>
                <p><a href="#">Contact Support</a></p>
            </div>
            <div class="footer-section">
                <h3>Newsletter</h3>
                <p>Subscribe to stay updated with our latest news.</p>
                <div style="display: flex; gap: 0.5rem; margin-top: 1rem;">
                    <input type="email" placeholder="Email" style="background: var(--surface-light); border: 1px solid var(--border); padding: 0.5rem; border-radius: 0.4rem; color: var(--text); outline: none; flex: 1;">
                    <button class="btn btn-primary" style="padding: 0.5rem 1rem;">Go</button>
                </div>
            </div>
        </div>
        <div class="footer-bottom">
            <p>&copy; <?php echo date('Y'); ?> <?php echo SITE_NAME; ?>. All rights reserved.</p>
        </div>
    </footer>
    <script src="<?php echo SITE_PATH; ?>/assets/js/main.js"></script>
</body>
</html>
