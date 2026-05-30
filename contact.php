<?php
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/turnstile.php';

$successMsg = '';
$errorMsg = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!csrf_verify()) {
        $errorMsg = 'Security validation failed (CSRF token mismatch).';
    } else {
        $fullname = trim($_POST['fullname'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $country_code = trim($_POST['country_code'] ?? '+91');
        $phone_raw = trim($_POST['phone'] ?? '');
        $phone_digits = preg_replace('/\D/', '', $phone_raw);
        $phone = $country_code . ' ' . $phone_raw;
        $subject = trim($_POST['subject'] ?? '');
        $message = trim($_POST['message'] ?? '');

        // Verify Turnstile CAPTCHA first
        $turnstileToken = $_POST['cf-turnstile-response'] ?? '';
        if (!verify_turnstile($turnstileToken, $_SERVER['REMOTE_ADDR'] ?? '')) {
            $errorMsg = 'CAPTCHA verification failed. Please try again.';
        } elseif (empty($fullname) || empty($email) || empty($phone_raw) || empty($subject) || empty($message)) {
            $errorMsg = 'All fields marked with an asterisk are required.';
        } elseif ($country_code === '+91' && strlen($phone_digits) !== 10) {
            $errorMsg = 'Indian phone numbers must be exactly 10 digits.';
        } elseif (strlen($phone_digits) < 7 || strlen($phone_digits) > 15) {
            $errorMsg = 'Please enter a valid phone number (between 7 and 15 digits).';
        } else {
            try {
                $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
                $source_page = $protocol . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];

                $stmt = $pdo->prepare("INSERT INTO leads (type, fullname, email, phone, subject, message, source_page) VALUES ('contact', ?, ?, ?, ?, ?, ?)");
                $stmt->execute([$fullname, $email, $phone, $subject, $message, $source_page]);

                // Send email notification (fails silently if SMTP disabled or fails)
                try {
                    $leadData = [
                        'type' => 'contact',
                        'fullname' => $fullname,
                        'email' => $email,
                        'phone' => $phone,
                        'subject' => $subject,
                        'message' => $message,
                        'source_page' => $source_page
                    ];
                    require_once __DIR__ . '/includes/mailer.php';
                    send_lead_notification($leadData);
                } catch (\Exception $ex) {
                    // Fallback catch, error_log handled within send_lead_notification
                }

                $successMsg = 'Thank you! Your message has been sent successfully. We will get back to you shortly.';
            } catch (PDOException $e) {
                $errorMsg = 'Error saving lead: ' . $e->getMessage();
            }
        }
    }
}

$pageTitle = "Contact Us";
$pageDesc = "Get in touch with Wanderoo for bespoke itinerary design, luxury honeymoons, and premium travel planning support.";
$extraHeadHtml = '<script src="https://challenges.cloudflare.com/turnstile/v0/api.js" async defer></script>';
include_once 'includes/header.php';

$contactEmail = get_setting('contact_email', 'support@wanderoo.world');
$contactPhone = get_setting('contact_phone', '+91 91 135 154 62');
$contactWhatsapp = get_setting('contact_whatsapp', '919113515462');
$contactAddress = get_setting('contact_address', "Wanderoo\nThe landmark\n2nd Floor, Santacruz West\nMumbai - 400049");
?>

<main>
    <div class="contact-hero-banner">
        <img src="<?php echo SITE_PATH; ?>/assets/img/step3howitworks.jpg" alt="Contact Us" class="contact-hero-bg">
        <div class="contact-hero-overlay"></div>
        <div class="contact-hero-content">
            <span class="contact-breadcrumb-text"><a href="<?php echo SITE_PATH; ?>/">Home</a> &raquo; Contact</span>
            <h1 class="contact-hero-title">Contact Us</h1>
        </div>
    </div>

    <div class="contact-container">
        <div class="contact-header-section">
            <div class="contact-header-left">
                <h2 class="contact-section-title"><span class="urbanist">Start Your Travel Story With Us,</span><br><span class="playfair italic">Don't Hesitate To Contact Us</span></h2>
            </div>
            <div class="contact-header-right">
                <p class="contact-section-desc">Got questions about your next trip, need itinerary advice, or want help with bookings? Our travel experts are ready to assist. Whether you're planning a relaxing getaway or an adrenaline-filled adventure, we're just a message away!</p>
            </div>
        </div>

        <div class="contact-grid">
            <!-- Contact Form Column -->
            <div class="contact-form-col">
                <h3 class="form-title">Lets Get In Touch With Us!</h3>

                <?php if (!empty($successMsg)): ?>
                    <div class="alert alert-success" style="margin-bottom: 20px; padding: 15px; background: rgba(34, 197, 94, 0.1); border: 1.5px solid #22c55e; color: #15803d; border-radius: 12px; font-family: 'Urbanist', sans-serif; font-weight: 600;">
                        <?php echo htmlspecialchars($successMsg); ?>
                    </div>
                    <script>
                        localStorage.setItem('timed_lead_submitted', 'true');
                        sessionStorage.setItem('timed_lead_submitted', 'true');
                    </script>
                <?php elseif (!empty($errorMsg)): ?>
                    <div class="alert alert-danger" style="margin-bottom: 20px; padding: 15px; background: rgba(239, 68, 68, 0.1); border: 1.5px solid #ef4444; color: #b91c1c; border-radius: 12px; font-family: 'Urbanist', sans-serif; font-weight: 600;">
                        <?php echo htmlspecialchars($errorMsg); ?>
                    </div>
                <?php endif; ?>

                <form action="" method="POST" class="contact-form">
                    <?php csrf_input(); ?>
                    <div class="form-row">
                        <div class="form-group">
                            <label for="fullname">Full Name</label>
                            <input type="text" id="fullname" name="fullname" required placeholder="Enter your full name">
                        </div>
                        <div class="form-group">
                            <label for="email">Email Id</label>
                            <input type="email" id="email" name="email" required placeholder="Enter your email ID">
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label for="phone">Phone No.</label>
                            <div class="phone-input-wrapper">
                                <select id="country_code" name="country_code" required style="padding: 12px 10px 12px 20px; font-family: 'Urbanist', sans-serif; font-size: 15px; font-weight: 600; color: #4a5568; background: #f1f5f9; border: none; border-right: 1px solid #e2e8f0; outline: none; cursor: pointer; height: 100%;">
                                    <option value="+91" selected>🇮🇳 +91</option>
                                    <option value="+1">🇺🇸 +1</option>
                                    <option value="+44">🇬🇧 +44</option>
                                    <option value="+65">🇸🇬 +65</option>
                                    <option value="+971">🇦🇪 +971</option>
                                    <option value="+62">🇮🇩 +62</option>
                                    <option value="+81">🇯🇵 +81</option>
                                    <option value="+960">🇲🇻 +960</option>
                                    <option value="+60">🇲🇾 +60</option>
                                    <option value="+66">🇹🇭 +66</option>
                                    <option value="+61">🇦🇺 +61</option>
                                </select>
                                <input type="tel" id="phone" name="phone" required placeholder="Enter 10-digit number">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="subject">Subject</label>
                            <input type="text" id="subject" name="subject" required placeholder="Enter subject">
                        </div>
                    </div>
                    <div class="form-group full-width">
                        <label for="message">Your Message <span class="required">*</span></label>
                        <textarea id="message" name="message" rows="5" required placeholder="Enter your message"></textarea>
                    </div>
                    <!-- Cloudflare Turnstile CAPTCHA -->
                    <div class="cf-turnstile" data-sitekey="<?php echo TURNSTILE_SITE_KEY; ?>" style="margin-bottom: 8px;"></div>
                    <button type="submit" class="btn-send-message">Send Message</button>
                </form>
            </div>

            <!-- Contact Info Column -->
            <div class="contact-info-col">
                <!-- Card 1: Email -->
                <div class="info-card">
                    <div class="info-icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/>
                            <polyline points="22,6 12,13 2,6"/>
                        </svg>
                    </div>
                    <div class="info-details">
                        <h4>Email Address</h4>
                        <a href="mailto:<?php echo htmlspecialchars($contactEmail); ?>"><?php echo htmlspecialchars($contactEmail); ?></a>
                    </div>
                </div>

                <!-- Card 2: Whatsapp -->
                <div class="info-card">
                    <div class="info-icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z"/>
                        </svg>
                    </div>
                    <div class="info-details">
                        <h4>Whatsapp Us</h4>
                        <a href="https://wa.me/<?php echo htmlspecialchars($contactWhatsapp); ?>" target="_blank"><?php echo htmlspecialchars($contactPhone); ?></a>
                    </div>
                </div>

                <!-- Card 3: Address -->
                <div class="info-card">
                    <div class="info-icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M12 2a8 8 0 0 0-8 8c0 5.25 8 12 8 12s8-6.75 8-12a8 8 0 0 0-8-8z"/>
                            <circle cx="12" cy="10" r="3"/>
                        </svg>
                    </div>
                    <div class="info-details">
                        <h4>Address</h4>
                        <p><?php echo nl2br(htmlspecialchars($contactAddress)); ?></p>
                    </div>
                </div>
            </div>
        </div>


    </div>
<script>
document.addEventListener('DOMContentLoaded', () => {
    const countrySelect = document.getElementById('country_code');
    const phoneInput = document.getElementById('phone');
    const contactForm = document.querySelector('.contact-form');

    if (countrySelect && phoneInput) {
        const updatePlaceholder = () => {
            if (countrySelect.value === '+91') {
                phoneInput.placeholder = 'Enter 10-digit number';
                phoneInput.setAttribute('pattern', '[0-9]{10}');
                phoneInput.setAttribute('title', 'Indian phone numbers must be exactly 10 digits.');
            } else {
                phoneInput.placeholder = 'Enter phone number';
                phoneInput.removeAttribute('pattern');
                phoneInput.removeAttribute('title');
            }
        };

        countrySelect.addEventListener('change', updatePlaceholder);
        updatePlaceholder(); // init

        if (contactForm) {
            contactForm.addEventListener('submit', (e) => {
                const phoneVal = phoneInput.value.replace(/\D/g, ''); // strip non-digits
                if (countrySelect.value === '+91' && phoneVal.length !== 10) {
                    e.preventDefault();
                    alert('Please enter a valid 10-digit Indian phone number.');
                    phoneInput.focus();
                } else if (phoneVal.length < 7 || phoneVal.length > 15) {
                    e.preventDefault();
                    alert('Please enter a valid phone number (between 7 and 15 digits).');
                    phoneInput.focus();
                }
            });
        }
    }
});
</script>

<?php include_once 'includes/footer.php'; ?>
