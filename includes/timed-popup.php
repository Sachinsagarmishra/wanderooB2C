<!-- Timed Lead Popup Component -->
<div class="timed-popup-backdrop" id="timedLeadPopup">
    <div class="timed-popup-container">
        <!-- Close Button -->
        <button class="timed-popup-close" id="closeTimedPopup" aria-label="Close Popup">&times;</button>
        
        <div class="timed-popup-content">
            <h2 class="timed-popup-title">Get Quick Quote</h2>
            
            <form id="timedPopupForm" class="timed-popup-form" method="POST">
                <?php csrf_input(); ?>
                <input type="hidden" name="source_page" id="popupSourcePage" value="">
                
                <div id="popupErrorBanner" class="popup-error-banner" style="display: none;"></div>

                <div class="timed-popup-group">
                    <label for="popupName">Name*</label>
                    <input type="text" id="popupName" name="fullname" placeholder="Enter Your Name" required>
                </div>
                
                <div class="timed-popup-group">
                    <label for="popupEmail">Email Address*</label>
                    <input type="email" id="popupEmail" name="email" placeholder="Enter Email Address" required>
                </div>
                
                <div class="timed-popup-group">
                    <label for="popupPhone">Contact Number*</label>
                    <input type="tel" id="popupPhone" name="phone" placeholder="+91 | Enter phone number" required>
                </div>
                
                <div class="timed-popup-group">
                    <label for="popupDestination">Destination*</label>
                    <select id="popupDestination" name="destination" required>
                        <option value="" disabled selected>Select destination</option>
                        <?php
                        try {
                            $stmtPopupDests = $pdo->query("SELECT slug, name FROM destinations ORDER BY sort_order, name");
                            while ($pDest = $stmtPopupDests->fetch()) {
                                echo '<option value="' . htmlspecialchars($pDest['slug']) . '">' . htmlspecialchars($pDest['name']) . '</option>';
                            }
                        } catch (Exception $e) {
                            echo '<option value="singapore">Singapore</option>';
                            echo '<option value="maldives">Maldives</option>';
                            echo '<option value="bali">Bali</option>';
                            echo '<option value="japan">Japan</option>';
                            echo '<option value="kerala">Kerala</option>';
                        }
                        ?>
                    </select>
                </div>
                
                <!-- Adults & Kids (Side by side) -->
                <div class="timed-popup-row">
                    <div class="timed-popup-group">
                        <label for="popupAdults">No. of Adults*</label>
                        <input type="number" id="popupAdults" name="adults" min="1" value="2" required>
                    </div>
                    <div class="timed-popup-group">
                        <label for="popupKids">No. of Kids*</label>
                        <input type="number" id="popupKids" name="kids" min="0" value="0" required>
                    </div>
                </div>
                
                <!-- Date & Nights (Side by side) -->
                <div class="timed-popup-row">
                    <div class="timed-popup-group">
                        <label for="popupDate">Travelling on</label>
                        <input type="date" id="popupDate" name="departure_date" min="<?php echo date('Y-m-d'); ?>">
                    </div>
                    <div class="timed-popup-group">
                        <label for="popupNights">No. of Nights</label>
                        <input type="number" id="popupNights" name="nights" min="1" placeholder="00">
                    </div>
                </div>
                
                <div class="timed-popup-group">
                    <label for="popupDepCity">Departure City</label>
                    <input type="text" id="popupDepCity" name="departure_city" placeholder="Enter your City">
                </div>
                
                <div class="timed-popup-group flight-status-group">
                    <label>Are your flights already booked?</label>
                    <div class="timed-popup-radio-group">
                        <label class="radio-label">
                            <input type="radio" name="flights_booked" value="Yes"> Yes
                        </label>
                        <label class="radio-label">
                            <input type="radio" name="flights_booked" value="No" checked> No
                        </label>
                    </div>
                </div>
                
                <button type="submit" class="timed-popup-submit-btn" id="popupSubmitBtn">Submit</button>
            </form>
            
            <!-- Success Screen -->
            <div class="timed-popup-success" id="timedPopupSuccess" style="display: none;">
                <div class="success-icon-wrapper">🎉</div>
                <h3 class="success-title">Thank You!</h3>
                <p class="success-desc">Your travel enquiry has been submitted successfully. Our travel experts will get in touch with you shortly.</p>
                <p class="success-countdown" id="popupCountdown">This popup will close automatically in 5 seconds...</p>
            </div>
        </div>
    </div>
</div>

<style>
/* Timed Lead Popup Design System */
.timed-popup-backdrop {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.6);
    backdrop-filter: blur(5px);
    -webkit-backdrop-filter: blur(5px);
    z-index: 999999;
    display: none;
    align-items: center;
    justify-content: center;
    padding: 20px;
    opacity: 0;
    transition: opacity 0.3s ease;
}

.timed-popup-backdrop.show {
    opacity: 1;
}

.timed-popup-container {
    background-color: #ffffff;
    max-width: 480px;
    width: 100%;
    max-height: 90vh;
    border-radius: 24px;
    box-shadow: 0 20px 50px rgba(0, 0, 0, 0.2);
    overflow-y: auto;
    position: relative;
    transform: scale(0.9);
    transition: transform 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
    box-sizing: border-box;
}

.timed-popup-backdrop.show .timed-popup-container {
    transform: scale(1);
}

/* Scrollbar styling */
.timed-popup-container::-webkit-scrollbar {
    width: 6px;
}
.timed-popup-container::-webkit-scrollbar-track {
    background: transparent;
}
.timed-popup-container::-webkit-scrollbar-thumb {
    background: #cbd5e1;
    border-radius: 10px;
}

/* Close button */
.timed-popup-close {
    position: absolute;
    top: 20px;
    right: 20px;
    background: none;
    border: none;
    font-size: 28px;
    color: #64748b;
    cursor: pointer;
    line-height: 1;
    transition: color 0.2s, transform 0.2s;
    z-index: 10;
    outline: none;
    padding: 0;
}

.timed-popup-close:hover {
    color: #ef4444;
    transform: scale(1.1);
}

.timed-popup-content {
    padding: 40px 30px;
}

.timed-popup-title {
    font-family: "Playfair Display", serif;
    font-size: 32px;
    font-weight: 700;
    font-style: italic;
    color: #1e293b;
    margin-bottom: 24px;
    margin-top: 0;
    text-align: center;
}

.timed-popup-form {
    display: flex;
    flex-direction: column;
    gap: 16px;
}

.timed-popup-group {
    display: flex;
    flex-direction: column;
    gap: 6px;
    box-sizing: border-box;
}

.timed-popup-group label {
    font-family: 'Urbanist', sans-serif;
    font-size: 13px;
    font-weight: 700;
    color: #475569;
}

.timed-popup-group input[type="text"],
.timed-popup-group input[type="email"],
.timed-popup-group input[type="tel"],
.timed-popup-group input[type="number"],
.timed-popup-group input[type="date"],
.timed-popup-group select {
    width: 100%;
    padding: 12px 16px;
    border: 1.5px solid #cbd5e1;
    border-radius: 12px;
    font-family: 'Urbanist', sans-serif;
    font-size: 15px;
    background-color: #f8fafc;
    color: #0f172a;
    outline: none;
    transition: border-color 0.2s, box-shadow 0.2s;
    box-sizing: border-box;
}

.timed-popup-group input:focus,
.timed-popup-group select:focus {
    border-color: #ff2d55;
    box-shadow: 0 0 0 4px rgba(255, 45, 85, 0.1);
    background-color: #ffffff;
}

.timed-popup-row {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 15px;
}

/* Radio button styles */
.flight-status-group {
    margin-top: 5px;
}

.timed-popup-radio-group {
    display: flex;
    gap: 20px;
    margin-top: 4px;
}

.radio-label {
    display: flex;
    align-items: center;
    gap: 6px;
    font-family: 'Urbanist', sans-serif;
    font-size: 14px;
    font-weight: 600;
    color: #1e293b;
    cursor: pointer;
}

.radio-label input[type="radio"] {
    width: 18px;
    height: 18px;
    accent-color: #ff2d55;
    cursor: pointer;
}

/* Submit button */
.timed-popup-submit-btn {
    background-color: #ff2d55;
    color: #ffffff;
    border: none;
    border-radius: 30px;
    padding: 14px;
    font-family: 'Urbanist', sans-serif;
    font-size: 16px;
    font-weight: 750;
    cursor: pointer;
    box-shadow: 0 6px 20px rgba(255, 45, 85, 0.3);
    transition: background-color 0.2s, transform 0.2s, box-shadow 0.2s;
    margin-top: 10px;
    outline: none;
}

.timed-popup-submit-btn:hover {
    background-color: #e02047;
    transform: translateY(-1px);
    box-shadow: 0 8px 24px rgba(255, 45, 85, 0.4);
}

.timed-popup-submit-btn:disabled {
    background-color: #cbd5e1;
    color: #94a3b8;
    box-shadow: none;
    cursor: not-allowed;
    transform: none;
}

/* Error Banner */
.popup-error-banner {
    background-color: #fee2e2;
    border: 1px solid #fecaca;
    color: #ef4444;
    padding: 12px 16px;
    border-radius: 12px;
    font-size: 13px;
    font-family: 'Urbanist', sans-serif;
    font-weight: 600;
    line-height: 1.4;
}

/* Success layout styles */
.timed-popup-success {
    display: flex;
    flex-direction: column;
    align-items: center;
    text-align: center;
    padding: 30px 10px;
    animation: popupFadeIn 0.4s ease-out;
}

@keyframes popupFadeIn {
    from { opacity: 0; transform: translateY(10px); }
    to { opacity: 1; transform: translateY(0); }
}

.success-icon-wrapper {
    font-size: 60px;
    margin-bottom: 16px;
    display: block;
    animation: popupBounce 1s ease infinite alternate;
}

@keyframes popupBounce {
    from { transform: translateY(0); }
    to { transform: translateY(-8px); }
}

.success-title {
    font-family: 'Urbanist', sans-serif;
    font-size: 28px;
    font-weight: 800;
    color: #16a34a;
    margin: 0 0 12px 0;
}

.success-desc {
    font-family: 'Urbanist', sans-serif;
    font-size: 15px;
    line-height: 1.5;
    color: #475569;
    font-weight: 600;
    max-width: 320px;
    margin: 0 auto 20px auto;
}

.success-countdown {
    font-family: 'Urbanist', sans-serif;
    font-size: 12px;
    color: #94a3b8;
    margin: 0;
}

/* Responsive adjustments */
@media (max-width: 576px) {
    .timed-popup-container {
        border-radius: 20px;
    }
    .timed-popup-content {
        padding: 35px 20px 25px 20px;
    }
    .timed-popup-title {
        font-size: 26px;
        margin-bottom: 20px;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const popup = document.getElementById('timedLeadPopup');
    const closeBtn = document.getElementById('closeTimedPopup');
    const form = document.getElementById('timedPopupForm');
    const errorBanner = document.getElementById('popupErrorBanner');
    const submitBtn = document.getElementById('popupSubmitBtn');
    const successScreen = document.getElementById('timedPopupSuccess');
    const countdownEl = document.getElementById('popupCountdown');
    
    let popupTimer;
    let reappearTimer;
    let countdownInterval;

    // Check if the popup was already submitted successfully in this session
    if (sessionStorage.getItem('timed_lead_submitted') === 'true') {
        return; // Don't run the timer at all
    }

    // Start 10-second timer on load/refresh
    startPopupTimer(10000);

    function startPopupTimer(delay) {
        clearTimeout(popupTimer);
        popupTimer = setTimeout(() => {
            showPopup();
        }, delay);
    }

    function showPopup() {
        // Double check submission flag
        if (sessionStorage.getItem('timed_lead_submitted') === 'true') return;

        popup.style.display = 'flex';
        // Set values
        const srcPageInput = document.getElementById('popupSourcePage');
        if (srcPageInput) {
            srcPageInput.value = window.location.href;
        }

        // Auto pre-fill destination from current page context if available
        if (window.currentPageDestination) {
            const destSelect = document.getElementById('popupDestination');
            if (destSelect && !destSelect.value) {
                destSelect.value = window.currentPageDestination;
            }
        }

        setTimeout(() => {
            popup.classList.add('show');
            document.body.style.overflow = 'hidden';
        }, 10);
    }

    function closePopup() {
        popup.classList.remove('show');
        document.body.style.overflow = '';
        
        if (countdownInterval) {
            clearInterval(countdownInterval);
        }

        setTimeout(() => {
            popup.style.display = 'none';
        }, 300);

        // Schedule to show again in 60 seconds
        clearTimeout(reappearTimer);
        reappearTimer = setTimeout(() => {
            showPopup();
        }, 60000);
    }

    closeBtn.addEventListener('click', closePopup);

    // Close when clicking on the backdrop overlay
    popup.addEventListener('click', (e) => {
        if (e.target === popup) {
            closePopup();
        }
    });

    // Hide error banner when inputs are modified
    const inputs = form.querySelectorAll('input, select');
    inputs.forEach(input => {
        input.addEventListener('input', () => {
            errorBanner.style.display = 'none';
        });
    });

    // Form Submission
    form.addEventListener('submit', (e) => {
        e.preventDefault();
        
        // Basic Client validations
        const name = document.getElementById('popupName').value.trim();
        const email = document.getElementById('popupEmail').value.trim();
        const phone = document.getElementById('popupPhone').value.trim();
        const dest = document.getElementById('popupDestination').value;
        const adults = document.getElementById('popupAdults').value;
        
        if (!name || !email || !phone || !dest || !adults) {
            showError('Please fill in all required fields marked with *');
            return;
        }

        // Email validation
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!emailRegex.test(email)) {
            showError('Please enter a valid email address.');
            return;
        }

        // Phone digits validation
        const digits = phone.replace(/\D/g, '');
        if (digits.length < 7 || digits.length > 15) {
            showError('Please enter a valid phone number (between 7 and 15 digits).');
            return;
        }

        // Disable submit button and trigger fetch request
        submitBtn.disabled = true;
        const originalText = submitBtn.textContent;
        submitBtn.textContent = 'Submitting...';

        const formData = new FormData(form);

        fetch('<?php echo SITE_PATH; ?>/submit-popup.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            submitBtn.textContent = originalText;
            submitBtn.disabled = false;
            
            if (data.success) {
                // Set flag to never show again this session
                sessionStorage.setItem('timed_lead_submitted', 'true');
                clearTimeout(reappearTimer);
                clearTimeout(popupTimer);

                // Show success screen
                form.style.display = 'none';
                successScreen.style.display = 'flex';
                
                let secondsLeft = 5;
                countdownEl.textContent = `This popup will close automatically in ${secondsLeft} seconds...`;
                
                countdownInterval = setInterval(() => {
                    secondsLeft--;
                    countdownEl.textContent = `This popup will close automatically in ${secondsLeft} seconds...`;
                    if (secondsLeft <= 0) {
                        clearInterval(countdownInterval);
                        popup.classList.remove('show');
                        document.body.style.overflow = '';
                        setTimeout(() => {
                            popup.style.display = 'none';
                        }, 300);
                    }
                }, 1000);

            } else {
                showError(data.error || 'Failed to submit form. Please try again.');
            }
        })
        .catch(err => {
            submitBtn.textContent = originalText;
            submitBtn.disabled = false;
            console.error('Submission error:', err);
            showError('An error occurred during submission. Please try again.');
        });
    });

    function showError(msg) {
        errorBanner.textContent = msg;
        errorBanner.style.display = 'block';
        // Scroll popup top so error is visible
        document.querySelector('.timed-popup-container').scrollTop = 0;
    }
});
</script>
