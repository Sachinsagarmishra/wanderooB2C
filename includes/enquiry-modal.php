<!-- Enquiry Multi-Step Modal Component -->
<div class="enquiry-modal-backdrop" id="enquiryModal">
    <div class="enquiry-modal-container">
        <!-- Close Button -->
        <button class="enquiry-modal-close" id="closeEnquiryModal">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                <line x1="18" y1="6" x2="6" y2="18"></line>
                <line x1="6" y1="6" x2="18" y2="18"></line>
            </svg>
        </button>

        <!-- Left Side: Hero Panel (Marketing) -->
        <div class="enquiry-modal-left">
            <div class="enquiry-left-overlay"></div>
            <div class="enquiry-left-content">
                <h2 class="enquiry-left-title">Create Super Hit Holidays</h2>
                <ul class="enquiry-left-list">
                    <li>
                        <span class="check-icon">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
                                <polyline points="20 6 9 17 4 12"></polyline>
                            </svg>
                        </span>
                        100% Customised
                    </li>
                    <li>
                        <span class="check-icon">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
                                <polyline points="20 6 9 17 4 12"></polyline>
                            </svg>
                        </span>
                        Curated by Experts
                    </li>
                    <li>
                        <span class="check-icon">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
                                <polyline points="20 6 9 17 4 12"></polyline>
                            </svg>
                        </span>
                        24x7 Live Support
                    </li>
                </ul>
            </div>
        </div>

        <!-- Right Side: Form Panel -->
        <div class="enquiry-modal-right">
            <!-- Progress Bar -->
            <div class="enquiry-progress-wrapper">
                <div class="enquiry-progress-track">
                    <div class="enquiry-progress-bar" id="enquiryProgressBar" style="width: 20%;"></div>
                </div>
                <div class="enquiry-step-indicator" id="enquiryStepIndicator">Step 1 of 5</div>
            </div>

            <!-- Error Banner -->
            <div class="enquiry-error-banner" id="enquiryErrorBanner" style="display: none;">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"></path>
                    <line x1="12" y1="9" x2="12" y2="13"></line>
                    <line x1="12" y1="17" x2="12.01" y2="17"></line>
                </svg>
                <span id="enquiryErrorText">Please select an option to proceed.</span>
            </div>

            <!-- Form Multi-steps Container -->
            <form action="#" method="POST" id="enquiryForm" class="enquiry-form-container">
                <input type="hidden" name="source_page" id="enquirySourcePage" value="">
                <!-- Step 1: Select Destination -->
                <div class="enquiry-form-step active" data-step="1">
                    <h3 class="enquiry-step-title">Select Destination</h3>
                    <div class="enquiry-input-group">
                        <select id="enquiryDestination" name="destination" class="enquiry-select-field">
                            <option value="" disabled selected>Choose your dream destination...</option>
                            <?php
                            try {
                                $stmtModalDests = $pdo->query("SELECT slug, name FROM destinations ORDER BY sort_order, name");
                                while ($mDest = $stmtModalDests->fetch()) {
                                    echo '<option value="' . htmlspecialchars($mDest['slug']) . '">' . htmlspecialchars($mDest['name']) . '</option>';
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
                </div>

                <!-- Step 2: Select Travel Date -->
                <div class="enquiry-form-step" data-step="2">
                    <h3 class="enquiry-step-title">Select Travel Date</h3>
                    <div class="enquiry-input-group">
                        <label for="enquiryDate" class="enquiry-input-label">Departure Date</label>
                        <input type="date" id="enquiryDate" name="departure_date" class="enquiry-input-field" min="<?php echo date('Y-m-d'); ?>">
                    </div>
                </div>

                <!-- Step 3: Choose Number Of Nights -->
                <div class="enquiry-form-step" data-step="3">
                    <h3 class="enquiry-step-title">Choose Number Of Nights</h3>
                    <div class="enquiry-options-grid">
                        <div class="enquiry-option-card" data-value="3 Nights">3 Nights</div>
                        <div class="enquiry-option-card" data-value="4 Nights">4 Nights</div>
                        <div class="enquiry-option-card" data-value="5 Nights">5 Nights</div>
                        <div class="enquiry-option-card" data-value="6 Nights or More">6 Nights or More</div>
                    </div>
                    <input type="hidden" id="enquiryNights" name="nights" value="">
                </div>

                <!-- Step 4: Who Are You Travelling With? -->
                <div class="enquiry-form-step" data-step="4">
                    <h3 class="enquiry-step-title">Who Are You Travelling With?</h3>
                    <div class="enquiry-options-grid">
                        <div class="enquiry-option-card with-icon" data-value="Couple">
                            <span class="option-icon"><img src="<?php echo SITE_PATH; ?>/assets/img/couple.png" alt="Couple"></span>
                            Couple
                        </div>
                        <div class="enquiry-option-card with-icon" data-value="Family">
                            <span class="option-icon"><img src="<?php echo SITE_PATH; ?>/assets/img/family.png" alt="Family"></span>
                            Family
                        </div>
                        <div class="enquiry-option-card with-icon" data-value="Friends">
                            <span class="option-icon"><img src="<?php echo SITE_PATH; ?>/assets/img/friends.png" alt="Friends"></span>
                            Friends
                        </div>
                        <div class="enquiry-option-card with-icon" data-value="Solo">
                            <span class="option-icon"><img src="<?php echo SITE_PATH; ?>/assets/img/solo.png" alt="Solo"></span>
                            Solo
                        </div>
                    </div>
                    <input type="hidden" id="enquiryCompanion" name="companion" value="">
                </div>

                <!-- Step 5: How To Configure Your Rooms? (Conditional Step for Family / Friends) -->
                <div class="enquiry-form-step" data-step="5">
                    <h3 class="enquiry-step-title">How To Configure Your Rooms?</h3>
                    <div class="enquiry-rooms-outer-wrapper">
                        <div class="enquiry-rooms-container" id="enquiryRoomsContainer">
                            <!-- Dynamic Rooms List gets setup by JS -->
                        </div>
                        <!-- Add Room Button -->
                        <button type="button" class="btn-add-room-outline" id="enquiryAddRoomBtn">+ Add New Room</button>
                    </div>
                    <input type="hidden" id="enquiryRoomsConfig" name="rooms_config" value="">
                </div>

                <!-- Step 6: Personal Details (Always Final Step) -->
                <div class="enquiry-form-step" data-step="6">
                    <h3 class="enquiry-step-title">Personal Details</h3>
                    <div class="enquiry-personal-details-fields">
                        <div class="enquiry-input-group">
                            <label for="enquiryName" class="enquiry-input-label">Full Name *</label>
                            <input type="text" id="enquiryName" name="fullname" class="enquiry-input-field" placeholder="Enter your full name">
                        </div>
                        <div class="enquiry-input-group">
                            <label for="enquiryPhone" class="enquiry-input-label">Phone Number (with country code) *</label>
                            <div class="enquiry-phone-input-wrapper">
                                <select id="enquiryCountryCode" name="country_code" required style="width: auto; padding: 0 10px 0 15px; font-family: inherit; font-size: 15px; font-weight: 700; color: #475569; background-color: #f1f5f9; border: none; border-right: 1.5px solid var(--enquiry-border); height: 52px; outline: none; cursor: pointer; border-radius: 0; flex-shrink: 0; -webkit-appearance: none; appearance: none;">
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
                                <input type="tel" id="enquiryPhone" name="phone" class="enquiry-input-field" placeholder="Enter 10-digit number">
                            </div>
                        </div>
                        <div class="enquiry-input-group">
                            <label for="enquiryEmail" class="enquiry-input-label">Email ID *</label>
                            <input type="email" id="enquiryEmail" name="email" class="enquiry-input-field" placeholder="Enter your email ID">
                        </div>
                        <div class="enquiry-input-group">
                            <label for="enquiryNotes" class="enquiry-input-label">How can we make this trip better for you? (Optional)</label>
                            <textarea id="enquiryNotes" name="notes" rows="3" class="enquiry-textarea-field" placeholder="Any preferences (e.g. food, hotels, sightseeing)..."></textarea>
                        </div>
                        <!-- Cloudflare Turnstile CAPTCHA -->
                        <div class="enquiry-input-group">
                            <div class="cf-turnstile"
                                 id="enquiryTurnstile"
                                 data-sitekey="<?php echo TURNSTILE_SITE_KEY; ?>"
                                 data-callback="onEnquiryTurnstileSuccess"
                                 data-expired-callback="onEnquiryTurnstileExpired"
                                 data-error-callback="onEnquiryTurnstileError">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Step Footer Actions -->
                <div class="enquiry-modal-footer">
                    <button type="button" class="btn-enquiry-prev" id="enquiryPrevBtn" style="display: none;">&larr; Previous</button>
                    <button type="button" class="btn-enquiry-next-step" id="enquiryNextBtn">Next &rarr;</button>
                </div>
            </form>

            <!-- Success Screen -->
            <div class="enquiry-success-screen" id="enquirySuccessScreen" style="display: none;">
                <div class="success-icon-wrapper">🎉</div>
                <h3 class="success-title">Thank You!</h3>
                <p class="success-desc">Your travel request has been submitted successfully. Our travel experts will contact you shortly to plan your perfect holiday!</p>
                <div class="success-adventure-card">
                    <p class="success-adventure-text">🚀 Get ready for an amazing adventure!</p>
                </div>
                <p class="success-countdown" id="successCountdown">This popup will close automatically in 5 seconds...</p>
            </div>
        </div>
    </div>
</div>

<style>
/* Enquiry Modal Core Variables */
:root {
    --enquiry-primary: #FFDE59;
    --enquiry-primary-dark: #e6c850;
    --enquiry-text-dark: #1e293b;
    --enquiry-border: #cbd5e1;
    --enquiry-bg: #f8fafc;
    --enquiry-teal: #50bfa5;
    --enquiry-teal-hover: #3bbaa0;
}

/* Backdrop */
.enquiry-modal-backdrop {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.6);
    backdrop-filter: blur(8px);
    -webkit-backdrop-filter: blur(8px);
    z-index: 99999;
    display: none; /* Controlled by JS */
    align-items: center;
    justify-content: center;
    padding: 20px;
    opacity: 0;
    transition: opacity 0.3s ease;
}

.enquiry-modal-backdrop.show {
    opacity: 1;
}

/* Container */
.enquiry-modal-container {
    background-color: #ffffff;
    max-width: 900px;
    width: 100%;
    min-height: 520px;
    border-radius: 24px;
    box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
    overflow: hidden;
    display: flex;
    position: relative;
    transform: scale(0.9);
    transition: transform 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
}

.enquiry-modal-backdrop.show .enquiry-modal-container {
    transform: scale(1);
}

/* Close button */
.enquiry-modal-close {
    position: absolute;
    top: 20px;
    right: 20px;
    background-color: #ff0f0f;
    border: none;
    width: 32px;
    height: 32px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #ffffff;
    cursor: pointer;
    box-shadow: 0 4px 10px rgba(255, 15, 15, 0.3);
    z-index: 100;
    transition: transform 0.2s, background-color 0.2s;
}

.enquiry-modal-close:hover {
    transform: scale(1.1);
    background-color: #e00b0b;
}

.enquiry-modal-close svg {
    width: 16px;
    height: 16px;
    stroke-width: 3px;
}

/* Left Hero Section */
.enquiry-modal-left {
    width: 42%;
    background-image: url('https://images.unsplash.com/photo-1544550581-5f7ceaf7f992?auto=format&fit=crop&q=80&w=600');
    background-size: cover;
    background-position: center;
    position: relative;
    display: flex;
    flex-direction: column;
    justify-content: flex-end;
    padding: 40px 30px;
}

.enquiry-left-overlay {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: linear-gradient(180deg, rgba(0, 0, 0, 0.2) 0%, rgba(0, 0, 0, 0.8) 100%);
    z-index: 1;
}

.enquiry-left-content {
    position: relative;
    z-index: 2;
    color: #ffffff;
}

.enquiry-left-title {
    font-family: 'Urbanist', sans-serif;
    font-size: 32px !important;
    font-weight: 700 !important;
    line-height: 1.2;
    margin-bottom: 25px;
    letter-spacing: -0.5px;
}

.enquiry-left-list {
    list-style: none;
    padding: 0;
    margin: 0;
    display: flex;
    flex-direction: column;
    gap: 15px;
}

.enquiry-left-list li {
    display: flex;
    align-items: center;
    gap: 10px;
    font-family: 'Urbanist', sans-serif !important;
    font-size: 16px !important;
    font-weight: 600 !important;
    color: #ffffff !important;
}

.check-icon {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 20px;
    height: 20px;
    border-radius: 50%;
    background-color: var(--enquiry-primary);
    color: #000000;
}

.check-icon svg {
    width: 11px;
    height: 11px;
}

/* Right Form Section */
.enquiry-modal-right {
    width: 58%;
    padding: 40px 45px;
    background-color: var(--enquiry-bg);
    display: flex;
    flex-direction: column;
    justify-content: space-between;
}

/* Progress indicator styles */
.enquiry-progress-wrapper {
    margin-bottom: 20px;
}

.enquiry-progress-track {
    width: 100%;
    height: 6px;
    background-color: #e2e8f0;
    border-radius: 10px;
    overflow: hidden;
}

.enquiry-progress-bar {
    height: 100%;
    background: linear-gradient(90deg, #4ade80, #3b82f6);
    border-radius: 10px;
    transition: width 0.4s cubic-bezier(0.25, 0.46, 0.45, 0.94);
}

.enquiry-step-indicator {
    text-align: center;
    font-family: 'Inter', sans-serif;
    font-size: 13px;
    font-weight: 700;
    color: #1e3a8a;
    background-color: #eff6ff;
    padding: 5px 15px;
    border-radius: 50px;
    width: fit-content;
    margin: 15px auto 0 auto;
    border: 1px solid #dbeafe;
}

/* Error Banner */
.enquiry-error-banner {
    display: flex;
    align-items: center;
    gap: 10px;
    background-color: #fee2e2;
    border: 1px solid #fecaca;
    color: #ef4444;
    padding: 12px 16px;
    border-radius: 12px;
    margin-bottom: 20px;
    font-size: 14px;
    font-family: 'Urbanist', sans-serif;
    font-weight: 600;
}

.enquiry-error-banner svg {
    width: 18px;
    height: 18px;
    flex-shrink: 0;
}

/* Form transition steps */
.enquiry-form-container {
    flex-grow: 1;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    margin-top: 10px;
}

.enquiry-form-step {
    display: none;
    opacity: 0;
    transition: opacity 0.3s ease;
}

.enquiry-form-step.active {
    display: block;
    opacity: 1;
}

.enquiry-step-title {
    font-family: 'Urbanist', sans-serif;
    font-size: 26px !important;
    font-weight: 700 !important;
    color: var(--enquiry-text-dark);
    margin-bottom: 20px;
    letter-spacing: -0.3px;
}

/* Selection Fields styling */
.enquiry-input-group {
    display: flex;
    flex-direction: column;
    gap: 8px;
    margin-bottom: 20px;
}

.enquiry-input-label {
    font-family: 'Urbanist', sans-serif;
    font-size: 14px;
    font-weight: 700;
    color: #475569;
}

.enquiry-select-field,
.enquiry-input-field {
    width: 100%;
    padding: 14px 18px;
    border: 1.5px solid var(--enquiry-border);
    border-radius: 12px;
    font-family: 'Urbanist', sans-serif;
    font-size: 16px;
    background-color: #ffffff;
    color: #0f172a;
    transition: border-color 0.2s, box-shadow 0.2s;
    outline: none;
    -webkit-appearance: none;
    -moz-appearance: none;
    appearance: none;
    box-sizing: border-box;
}

/* Custom dropdown arrow for select fields (replaces native Safari arrow) */
.enquiry-select-field {
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='8' viewBox='0 0 12 8'%3E%3Cpath d='M1 1.5L6 6.5L11 1.5' stroke='%2364748b' stroke-width='2' stroke-linecap='round' stroke-linejoin='round' fill='none'/%3E%3C/svg%3E");
    background-repeat: no-repeat;
    background-position: right 16px center;
    background-size: 12px;
    padding-right: 40px;
}

.enquiry-select-field:focus,
.enquiry-input-field:focus,
.enquiry-textarea-field:focus {
    border-color: #3b82f6;
    box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.1);
}

.enquiry-textarea-field {
    width: 100%;
    padding: 14px 18px;
    border: 1.5px solid var(--enquiry-border);
    border-radius: 12px;
    font-family: 'Urbanist', sans-serif;
    font-size: 15px;
    outline: none;
    resize: none;
    transition: border-color 0.2s, box-shadow 0.2s;
    -webkit-appearance: none;
    -moz-appearance: none;
    appearance: none;
    box-sizing: border-box;
}

/* Date Input custom styling — Safari + Chrome cross-browser */
.enquiry-input-field[type="date"] {
    -webkit-appearance: none;
    -moz-appearance: none;
    appearance: none;
    position: relative;
    min-height: 52px;
    line-height: 1.4;
    color: #0f172a;
    /* Remove Safari native date segments styling */
    -webkit-text-fill-color: #0f172a;
}

/* Fix Safari thick blue focus ring */
.enquiry-input-field[type="date"]:focus {
    outline: none;
    border-color: #3b82f6;
    box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.1);
    -webkit-box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.1);
}

/* Style the date text inside Safari */
.enquiry-input-field[type="date"]::-webkit-datetime-edit {
    padding: 0;
    font-family: 'Urbanist', sans-serif;
    font-size: 16px;
    color: #0f172a;
}

.enquiry-input-field[type="date"]::-webkit-datetime-edit-fields-wrapper {
    padding: 0;
}

.enquiry-input-field[type="date"]::-webkit-datetime-edit-text {
    color: #94a3b8;
    padding: 0 3px;
}

.enquiry-input-field[type="date"]::-webkit-datetime-edit-month-field,
.enquiry-input-field[type="date"]::-webkit-datetime-edit-day-field,
.enquiry-input-field[type="date"]::-webkit-datetime-edit-year-field {
    color: #0f172a;
    font-weight: 500;
}

/* Calendar picker icon */
input[type="date"]::-webkit-calendar-picker-indicator {
    cursor: pointer;
    filter: invert(0.3);
    opacity: 0.7;
    padding: 4px;
}

/* Options Grid for nights & companion */
.enquiry-options-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 15px;
    margin-bottom: 10px;
}

.enquiry-option-card {
    background-color: #ffffff;
    border: 1.5px solid #e2e8f0;
    border-radius: 14px;
    padding: 20px 15px;
    text-align: center;
    font-family: 'Urbanist', sans-serif;
    font-size: 16px;
    font-weight: 700;
    color: #334155;
    cursor: pointer;
    transition: all 0.2s ease;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    gap: 8px;
}

.enquiry-option-card:hover {
    border-color: #94a3b8;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.03);
}

.enquiry-option-card.active {
    border-color: #3b82f6;
    background-color: #eff6ff;
    color: #1e40af;
    box-shadow: 0 4px 15px rgba(59, 130, 246, 0.12);
}

.option-icon {
    width: 56px;
    height: 56px;
    border-radius: 50%;
    overflow: hidden;
    display: flex;
    align-items: center;
    justify-content: center;
    background-color: #f1f5f9;
    border: 2px solid #e2e8f0;
    flex-shrink: 0;
}

.option-icon img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    border-radius: 50%;
}

/* Phone prefix wrapper */
.enquiry-phone-input-wrapper {
    display: flex;
    align-items: center;
    border: 1.5px solid var(--enquiry-border);
    border-radius: 12px;
    background-color: #ffffff;
    overflow: hidden;
    transition: border-color 0.2s;
}

.enquiry-phone-input-wrapper:focus-within {
    border-color: #3b82f6;
    box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.1);
}

.enquiry-flag-prefix {
    padding: 0 14px;
    font-family: 'Urbanist', sans-serif;
    font-size: 15px;
    font-weight: 700;
    color: #475569;
    background-color: #f1f5f9;
    border-right: 1.5px solid var(--enquiry-border);
    height: 52px;
    display: flex;
    align-items: center;
    user-select: none;
}

.enquiry-phone-input-wrapper .enquiry-input-field {
    border: none;
    border-radius: 0;
    height: 52px;
}

/* Rooms step outer container styling */
.enquiry-rooms-outer-wrapper {
    max-height: 290px;
    overflow-y: auto;
    padding-right: 8px;
}

.enquiry-rooms-outer-wrapper::-webkit-scrollbar {
    width: 6px;
}

.enquiry-rooms-outer-wrapper::-webkit-scrollbar-track {
    background: transparent;
}

.enquiry-rooms-outer-wrapper::-webkit-scrollbar-thumb {
    background: #cbd5e1;
    border-radius: 10px;
}

/* Rooms Card Styles */
.enquiry-room-card {
    background-color: #ffffff;
    border: 1.5px solid #e2e8f0;
    border-radius: 16px;
    padding: 24px;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.02);
    margin-bottom: 20px;
}

.enquiry-room-card-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 20px;
    border-bottom: 1px solid #f1f5f9;
    padding-bottom: 8px;
}

.enquiry-room-title {
    font-family: 'Urbanist', sans-serif;
    font-size: 16px;
    font-weight: 700;
    color: #1e3a8a;
}

.btn-remove-room {
    background: none;
    border: none;
    color: #ef4444;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 4px;
    border-radius: 6px;
    transition: background-color 0.2s;
}

.btn-remove-room:hover {
    background-color: #fef2f2;
}

.btn-remove-room svg {
    width: 18px;
    height: 18px;
}

/* Counter controls style */
.enquiry-counter-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 20px;
}

.counter-label {
    font-family: 'Urbanist', sans-serif;
    font-size: 16px;
    font-weight: 600;
    color: #475569;
}

.counter-controls {
    display: flex;
    align-items: center;
    gap: 12px;
}

.btn-counter-minus,
.btn-counter-plus {
    width: 36px;
    height: 36px;
    border-radius: 50%;
    border: none;
    background-color: var(--enquiry-teal);
    color: #ffffff;
    cursor: pointer;
    font-size: 20px;
    font-weight: 700;
    display: flex;
    align-items: center;
    justify-content: center;
    box-shadow: 0 4px 8px rgba(80, 191, 165, 0.2);
    transition: all 0.2s;
    outline: none;
}

.btn-counter-minus:hover,
.btn-counter-plus:hover {
    background-color: var(--enquiry-teal-hover);
    transform: scale(1.05);
}

.counter-value {
    border: 1px solid #cbd5e1;
    border-radius: 8px;
    width: 48px;
    height: 36px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-family: 'Urbanist', sans-serif;
    font-size: 16px;
    font-weight: 700;
    color: #1e293b;
}

/* Child Ages Dropdown box styling */
.enquiry-child-ages-list {
    background-color: #fdfaf2;
    border: 1.5px solid #fef08a;
    border-radius: 16px;
    padding: 16px;
    margin-top: 15px;
    display: flex;
    flex-direction: column;
    gap: 12px;
}

/* Add Room button outline style */
.btn-add-room-outline {
    border: 2px solid var(--enquiry-primary);
    background-color: transparent;
    color: #000000;
    font-family: 'Urbanist', sans-serif;
    font-weight: 700;
    font-size: 15px;
    padding: 12px;
    width: 100%;
    text-align: center;
    border-radius: 50px;
    cursor: pointer;
    transition: all 0.2s;
    outline: none;
}

.btn-add-room-outline:hover {
    background-color: rgba(255, 222, 89, 0.1);
    transform: translateY(-1px);
}

.enquiry-personal-details-fields {
    max-height: 290px;
    overflow-y: auto;
    padding-right: 8px;
}

/* Custom Scrollbar for personal details fields */
.enquiry-personal-details-fields::-webkit-scrollbar {
    width: 6px;
}

.enquiry-personal-details-fields::-webkit-scrollbar-track {
    background: transparent;
}

.enquiry-personal-details-fields::-webkit-scrollbar-thumb {
    background: #cbd5e1;
    border-radius: 10px;
}

/* Footer buttons area */
.enquiry-modal-footer {
    display: flex;
    gap: 15px;
    margin-top: 30px;
    justify-content: flex-end;
}

.btn-enquiry-prev {
    background-color: transparent;
    border: 1.5px solid var(--enquiry-border);
    color: #475569;
    font-family: 'Urbanist', sans-serif;
    font-weight: 700;
    font-size: 15px;
    padding: 12px 28px;
    border-radius: 50px;
    cursor: pointer;
    transition: all 0.2s;
}

.btn-enquiry-prev:hover {
    background-color: #f1f5f9;
    border-color: #94a3b8;
}

.btn-enquiry-next-step {
    background-color: var(--enquiry-primary);
    border: none;
    color: #000000;
    font-family: 'Urbanist', sans-serif;
    font-weight: 700;
    font-size: 15px;
    padding: 13px 32px;
    border-radius: 50px;
    cursor: pointer;
    box-shadow: 0 6px 18px rgba(255, 222, 89, 0.35);
    transition: all 0.2s;
}

.btn-enquiry-next-step:hover {
    background-color: var(--enquiry-primary-dark);
    transform: translateY(-2px);
}

/* Success Screen Styles */
.enquiry-success-screen {
    display: none;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    text-align: center;
    padding: 20px 10px;
    height: 100%;
    animation: enquiryFadeIn 0.4s ease-out;
}

@keyframes enquiryFadeIn {
    from { opacity: 0; transform: translateY(10px); }
    to { opacity: 1; transform: translateY(0); }
}

.success-icon-wrapper {
    font-size: 64px;
    margin-bottom: 15px;
    display: block;
    animation: enquiryBounce 1s ease infinite alternate;
}

@keyframes enquiryBounce {
    from { transform: translateY(0); }
    to { transform: translateY(-8px); }
}

.success-title {
    font-family: 'Urbanist', sans-serif !important;
    font-size: 36px !important;
    font-weight: 800 !important;
    color: #16a34a !important; /* Premium green */
    margin: 0 0 16px 0 !important;
    letter-spacing: -0.5px;
}

.success-desc {
    font-family: 'Urbanist', sans-serif !important;
    font-size: 16px !important;
    line-height: 1.5 !important;
    color: #16a34a !important; /* Premium green */
    font-weight: 600 !important;
    max-width: 440px;
    margin: 0 auto 24px auto !important;
}

.success-adventure-card {
    background-color: #f0fdf4; /* Light green-blue background tint */
    border: 1.5px solid #bbf7d0; /* Soft green border */
    border-radius: 16px;
    padding: 18px 24px;
    width: 100%;
    max-width: 420px;
    margin-bottom: 24px;
    box-shadow: 0 4px 12px rgba(22, 163, 74, 0.05);
}

.success-adventure-text {
    font-family: 'Urbanist', sans-serif !important;
    font-size: 18px !important;
    font-weight: 750 !important;
    color: #1e3a8a !important; /* Slate blue text */
    margin: 0;
    text-align: center;
}

.success-countdown {
    font-family: 'Urbanist', sans-serif;
    font-size: 13px;
    color: #64748b;
    font-weight: 500;
    margin: 0;
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .enquiry-modal-left {
        display: none; /* Hide left side marketing panel */
    }

    .enquiry-modal-right {
        width: 100%;
        padding: 30px 20px;
    }

    .enquiry-modal-container {
        min-height: auto;
        border-radius: 20px;
    }

    .enquiry-step-title {
        font-size: 22px !important;
        margin-bottom: 20px;
    }

    .enquiry-option-card {
        padding: 15px 10px;
        font-size: 15px;
    }
}
</style>

<script>
// Expose the current page's destination slug to Javascript
window.currentPageDestination = <?php 
    $detectedSlug = '';
    $currentScript = basename($_SERVER['SCRIPT_NAME']);
    if ($currentScript === 'package-detail.php' && isset($destSlug)) {
        $detectedSlug = $destSlug;
    } elseif ($currentScript === 'destination.php' && isset($slug)) {
        $detectedSlug = $slug;
    }
    echo json_encode($detectedSlug); 
?>;

document.addEventListener('DOMContentLoaded', () => {
    const modal = document.getElementById('enquiryModal');
    const closeBtn = document.getElementById('closeEnquiryModal');
    const prevBtn = document.getElementById('enquiryPrevBtn');
    const nextBtn = document.getElementById('enquiryNextBtn');
    const steps = document.querySelectorAll('.enquiry-form-step');
    const progressBar = document.getElementById('enquiryProgressBar');
    const stepIndicator = document.getElementById('enquiryStepIndicator');
    const errorBanner = document.getElementById('enquiryErrorBanner');
    const errorText = document.getElementById('enquiryErrorText');
    const form = document.getElementById('enquiryForm');
    
    // Rooms Configuration variables
    const roomsContainer = document.getElementById('enquiryRoomsContainer');
    const addRoomBtn = document.getElementById('enquiryAddRoomBtn');
    let roomCount = 1;

    let currentStep = 1; // Visual step index: ranges 1 to 5 (without Rooms) or 1 to 6 (with Rooms)

    // Open modal on click of any enquiry/quote buttons
    const triggerButtons = document.querySelectorAll('.btn-enquire, .btn-quote, .btn-request, .btn-craft-trip, .btn-group-plan');
    triggerButtons.forEach(btn => {
        btn.addEventListener('click', (e) => {
            e.preventDefault();
            e.stopPropagation();
            const dest = btn.getAttribute('data-destination');
            openModal(dest);
        });
    });

    function openModal(dest) {
        modal.style.display = 'flex';
        document.body.style.overflow = 'hidden';
        
        // Track the source page URL
        const srcPageInput = document.getElementById('enquirySourcePage');
        if (srcPageInput) {
            srcPageInput.value = window.location.href;
        }

        setTimeout(() => {
            modal.classList.add('show');
            resetModal();
            
            // Auto pre-fill destination: passed dest first, then page destination
            const activeDest = dest || window.currentPageDestination;
            if (activeDest) {
                const destSelect = document.getElementById('enquiryDestination');
                if (destSelect) {
                    destSelect.value = activeDest;
                }
            }
        }, 10);
    }

    function closeModal() {
        modal.classList.remove('show');
        document.body.style.overflow = '';
        
        // Clear countdown interval if active
        if (modal.dataset.countdownInterval) {
            clearInterval(parseInt(modal.dataset.countdownInterval));
            modal.removeAttribute('data-countdown-interval');
        }
        
        // Reset Turnstile so next open gets a fresh challenge
        if (typeof turnstile !== 'undefined') {
            try { turnstile.reset('#enquiryTurnstile'); } catch(e) {}
        }

        setTimeout(() => {
            modal.style.display = 'none';
        }, 300);
    }

    closeBtn.addEventListener('click', closeModal);

    // Close on backdrop click
    modal.addEventListener('click', (e) => {
        if (e.target === modal) {
            closeModal();
        }
    });

    // Option cards selection toggle (Step 3 and Step 4)
    const optionCards = document.querySelectorAll('.enquiry-option-card');
    optionCards.forEach(card => {
        card.addEventListener('click', () => {
            hideError();
            
            // Get parent container and value
            const stepContainer = card.closest('.enquiry-form-step');
            const stepNum = stepContainer.getAttribute('data-step');
            const valInputId = stepNum === '3' ? 'enquiryNights' : 'enquiryCompanion';
            const valueInput = document.getElementById(valInputId);
            
            // Toggle active visual class
            stepContainer.querySelectorAll('.enquiry-option-card').forEach(c => c.classList.remove('active'));
            card.classList.add('active');
            
            // Set input value
            valueInput.value = card.getAttribute('data-value');
        });
    });

    // Select and Date inputs auto hide error on input
    document.getElementById('enquiryDestination').addEventListener('change', hideError);
    document.getElementById('enquiryDate').addEventListener('change', hideError);
    
    const personalInputs = ['enquiryName', 'enquiryPhone', 'enquiryEmail'];
    personalInputs.forEach(id => {
        document.getElementById(id).addEventListener('input', hideError);
    });

    const enquiryCountryCode = document.getElementById('enquiryCountryCode');
    const enquiryPhone = document.getElementById('enquiryPhone');
    if (enquiryCountryCode && enquiryPhone) {
        const updateEnquiryPlaceholder = () => {
            if (enquiryCountryCode.value === '+91') {
                enquiryPhone.placeholder = 'Enter 10-digit number';
            } else {
                enquiryPhone.placeholder = 'Enter phone number';
            }
        };
        enquiryCountryCode.addEventListener('change', updateEnquiryPlaceholder);
        enquiryCountryCode.addEventListener('change', hideError);
        updateEnquiryPlaceholder(); // init
    }

    // Helper functions for dynamic steps
    function getCompanionValue() {
        return document.getElementById('enquiryCompanion').value;
    }

    function hasRoomsStep() {
        const val = getCompanionValue();
        return val === 'Family' || val === 'Friends';
    }

    function getTotalVisualSteps() {
        return hasRoomsStep() ? 6 : 5;
    }

    // Navigation triggers
    nextBtn.addEventListener('click', () => {
        const totalVisualSteps = getTotalVisualSteps();
        if (validateStep(currentStep)) {
            if (currentStep < totalVisualSteps) {
                goToStep(currentStep + 1);
            } else {
                submitEnquiry();
            }
        }
    });

    prevBtn.addEventListener('click', () => {
        if (currentStep > 1) {
            goToStep(currentStep - 1);
        }
    });

    function goToStep(stepNum) {
        hideError();
        
        let targetStepInMarkup = stepNum;
        
        // Dynamic step mapping
        // Step 1 to 4 are mapped 1-to-1.
        // If we don't have the Rooms step (visual total is 5):
        // Visual Step 5 = Personal Details (which is data-step="6" in HTML).
        // If we have the Rooms step (visual total is 6):
        // Visual Step 5 = Rooms Configuration (data-step="5" in HTML).
        // Visual Step 6 = Personal Details (data-step="6" in HTML).
        if (!hasRoomsStep()) {
            if (stepNum === 5) {
                targetStepInMarkup = 6;
            }
        }
        
        // Hide current step, show target step
        steps.forEach(step => {
            step.classList.remove('active');
            if (parseInt(step.getAttribute('data-step')) === targetStepInMarkup) {
                step.classList.add('active');
            }
        });

        currentStep = stepNum;
        
        // Update progress bar & indicators
        const totalVisualSteps = getTotalVisualSteps();
        const progressPercentage = (currentStep / totalVisualSteps) * 100;
        progressBar.style.width = `${progressPercentage}%`;
        stepIndicator.textContent = `Step ${currentStep} of ${totalVisualSteps}`;

        // Show/hide Previous Button
        if (currentStep === 1) {
            prevBtn.style.display = 'none';
        } else {
            prevBtn.style.display = 'block';
        }

        // Change Next Button text on the final step
        if (currentStep === totalVisualSteps) {
            nextBtn.textContent = 'Submit Request';
        } else {
            nextBtn.textContent = 'Next →';
        }
    }

    function validateStep(stepNum) {
        if (stepNum === 1) {
            const dest = document.getElementById('enquiryDestination').value;
            if (!dest) {
                showError('Please choose your dream destination.');
                return false;
            }
        } else if (stepNum === 2) {
            const date = document.getElementById('enquiryDate').value;
            if (!date) {
                showError('Please select a departure date.');
                return false;
            }
        } else if (stepNum === 3) {
            const nights = document.getElementById('enquiryNights').value;
            if (!nights) {
                showError('Please select the number of nights.');
                return false;
            }
        } else if (stepNum === 4) {
            const companion = document.getElementById('enquiryCompanion').value;
            if (!companion) {
                showError('Please select who you are travelling with.');
                return false;
            }
        } else if (stepNum === 5 && hasRoomsStep()) {
            // Validate child age dropdown selects inside Room cards
            const childSelects = roomsContainer.querySelectorAll('.child-age-select');
            let allSelected = true;
            childSelects.forEach(select => {
                if (!select.value) allSelected = false;
            });
            
            if (!allSelected) {
                showError('Please select the age of all children.');
                return false;
            }
        } else if (stepNum === getTotalVisualSteps()) {
            const name = document.getElementById('enquiryName').value.trim();
            const phone = document.getElementById('enquiryPhone').value.trim();
            const email = document.getElementById('enquiryEmail').value.trim();

            if (!name) {
                showError('Please enter your full name.');
                return false;
            }
            if (!phone) {
                showError('Please enter your phone number.');
                return false;
            }
            
            const phoneDigits = phone.replace(/\D/g, '');
            const countryCodeVal = document.getElementById('enquiryCountryCode').value;
            if (countryCodeVal === '+91' && phoneDigits.length !== 10) {
                showError('Indian phone number must be exactly 10 digits.');
                return false;
            }
            if (phoneDigits.length < 7 || phoneDigits.length > 15) {
                showError('Please enter a valid phone number (between 7 and 15 digits).');
                return false;
            }
            if (!email) {
                showError('Please enter your email ID.');
                return false;
            }
            if (!validateEmail(email)) {
                showError('Please enter a valid email address.');
                return false;
            }
        }
        return true;
    }

    function showError(message) {
        errorText.textContent = message;
        errorBanner.style.display = 'flex';
    }

    function hideError() {
        errorBanner.style.display = 'none';
    }

    function validateEmail(email) {
        const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return re.test(email);
    }

    function resetModal() {
        form.reset();
        optionCards.forEach(c => c.classList.remove('active'));
        document.getElementById('enquiryNights').value = '';
        document.getElementById('enquiryCompanion').value = '';
        
        // Reset dynamic rooms
        resetRooms();
        
        // Restore visibility of form and progress bar, hide success screen
        const progressWrapper = document.querySelector('.enquiry-progress-wrapper');
        if (progressWrapper) progressWrapper.style.display = '';
        form.style.display = '';
        const successScreen = document.getElementById('enquirySuccessScreen');
        if (successScreen) successScreen.style.display = 'none';
        
        goToStep(1);
    }

    function submitEnquiry() {
        // Check Turnstile token
        const turnstileInput = document.querySelector('#enquiryTurnstile [name="cf-turnstile-response"]');
        if (!turnstileInput || !turnstileInput.value) {
            showError('Please complete the CAPTCHA verification before submitting.');
            return;
        }

        const formData = new FormData(form);
        
        // Show submission state on Next button
        const originalText = nextBtn.textContent;
        nextBtn.textContent = 'Submitting...';
        nextBtn.disabled = true;

        fetch('<?php echo SITE_PATH; ?>/submit-enquiry.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(res => {
            nextBtn.textContent = originalText;
            nextBtn.disabled = false;
            
            if (res.success) {
                showSuccessScreen();
            } else {
                showError(res.error || 'Failed to submit enquiry. Please try again.');
            }
        })
        .catch(err => {
            nextBtn.textContent = originalText;
            nextBtn.disabled = false;
            console.error('Error:', err);
            showError('An error occurred. Please try again.');
        });
    }

    function showSuccessScreen() {
        // Hide progress bar wrapper, error banner, and form container
        const progressWrapper = document.querySelector('.enquiry-progress-wrapper');
        if (progressWrapper) progressWrapper.style.display = 'none';
        errorBanner.style.display = 'none';
        form.style.display = 'none';
        
        // Show success screen
        const successScreen = document.getElementById('enquirySuccessScreen');
        if (successScreen) {
            successScreen.style.display = 'flex';
        }
        
        // Start countdown timer
        let timeLeft = 5;
        const countdownEl = document.getElementById('successCountdown');
        if (countdownEl) {
            countdownEl.textContent = `This popup will close automatically in ${timeLeft} seconds...`;
        }
        
        const countdownInterval = setInterval(() => {
            timeLeft--;
            if (countdownEl) {
                countdownEl.textContent = `This popup will close automatically in ${timeLeft} seconds...`;
            }
            if (timeLeft <= 0) {
                clearInterval(countdownInterval);
                closeModal();
            }
        }, 1000);
        
        // Store interval ID on modal
        modal.dataset.countdownInterval = countdownInterval;
    }

    // Rooms Configuration logic functions
    function setupRoomCardEvents(roomCard) {
        const minusBtns = roomCard.querySelectorAll('.btn-counter-minus');
        const plusBtns = roomCard.querySelectorAll('.btn-counter-plus');
        const roomNum = roomCard.getAttribute('data-room');
        
        minusBtns.forEach(btn => {
            btn.addEventListener('click', () => {
                hideError();
                const type = btn.getAttribute('data-type');
                const valSpan = roomCard.querySelector(`#room-${roomNum}-${type}-val`);
                let val = parseInt(valSpan.textContent);
                
                if (type === 'adults') {
                    if (val > 1) {
                        val--;
                        valSpan.textContent = val;
                    }
                } else if (type === 'children') {
                    if (val > 0) {
                        val--;
                        valSpan.textContent = val;
                        updateChildAgeSelects(roomCard, val);
                    }
                }
                updateRoomsConfigJSON();
            });
        });
        
        plusBtns.forEach(btn => {
            btn.addEventListener('click', () => {
                hideError();
                const type = btn.getAttribute('data-type');
                const valSpan = roomCard.querySelector(`#room-${roomNum}-${type}-val`);
                let val = parseInt(valSpan.textContent);
                
                if (type === 'adults') {
                    if (val < 10) {
                        val++;
                        valSpan.textContent = val;
                    }
                } else if (type === 'children') {
                    if (val < 10) {
                        val++;
                        valSpan.textContent = val;
                        updateChildAgeSelects(roomCard, val);
                    }
                }
                updateRoomsConfigJSON();
            });
        });
    }

    function updateChildAgeSelects(roomCard, childCount) {
        const roomNum = roomCard.getAttribute('data-room');
        const agesList = roomCard.querySelector(`#room-${roomNum}-child-ages`);
        
        if (childCount === 0) {
            agesList.style.display = 'none';
            agesList.innerHTML = '';
            return;
        }
        
        agesList.style.display = 'flex';
        agesList.style.flexDirection = 'column';
        agesList.style.gap = '12px';
        
        // Preserve values if already exists
        const existingSelects = agesList.querySelectorAll('.child-age-select');
        const existingValues = Array.from(existingSelects).map(select => select.value);
        
        agesList.innerHTML = '';
        
        for (let i = 1; i <= childCount; i++) {
            const row = document.createElement('div');
            row.className = 'enquiry-child-age-row';
            row.style.display = 'flex';
            row.style.alignItems = 'center';
            row.style.justifyContent = 'space-between';
            row.style.gap = '15px';
            
            const label = document.createElement('span');
            label.className = 'child-num-label';
            label.style.fontFamily = "'Urbanist', sans-serif";
            label.style.fontSize = '14px';
            label.style.fontWeight = '700';
            label.style.color = '#475569';
            label.textContent = `Child ${i}:`;
            
            const select = document.createElement('select');
            select.className = 'enquiry-select-field child-age-select';
            select.name = `room_${roomNum}_child_${i}_age`;
            select.style.padding = '10px 15px';
            select.style.borderRadius = '8px';
            select.style.fontSize = '14px';
            select.style.width = '70%';
            select.style.height = '42px';
            
            const defaultOpt = document.createElement('option');
            defaultOpt.value = '';
            defaultOpt.disabled = true;
            defaultOpt.selected = true;
            defaultOpt.textContent = 'Select age';
            select.appendChild(defaultOpt);
            
            // Generate ages 0 to 15
            for (let age = 0; age <= 15; age++) {
                const opt = document.createElement('option');
                opt.value = age;
                opt.textContent = age === 0 ? '< 1 year' : `${age} ${age === 1 ? 'year' : 'years'}`;
                select.appendChild(opt);
            }
            
            // Restore selection if previously selected
            if (existingValues[i - 1] !== undefined && existingValues[i - 1] !== '') {
                select.value = existingValues[i - 1];
            }
            
            select.addEventListener('change', () => {
                hideError();
                updateRoomsConfigJSON();
            });
            
            row.appendChild(label);
            row.appendChild(select);
            agesList.appendChild(row);
        }
    }

    addRoomBtn.addEventListener('click', () => {
        hideError();
        if (roomCount >= 4) {
            showError('You can add a maximum of 4 rooms.');
            return;
        }
        roomCount++;
        
        const newRoom = document.createElement('div');
        newRoom.className = 'enquiry-room-card';
        newRoom.setAttribute('data-room', roomCount);
        
        newRoom.innerHTML = `
            <div class="enquiry-room-card-header">
                <h4 class="enquiry-room-title">ROOM ${roomCount}</h4>
                <button type="button" class="btn-remove-room">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <polyline points="3 6 5 6 21 6"></polyline>
                        <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                    </svg>
                </button>
            </div>
            
            <!-- Adults Counter -->
            <div class="enquiry-counter-row">
                <span class="counter-label">Adults</span>
                <div class="counter-controls">
                    <button type="button" class="btn-counter-minus" data-type="adults">&minus;</button>
                    <span class="counter-value" id="room-${roomCount}-adults-val">2</span>
                    <button type="button" class="btn-counter-plus" data-type="adults">+</button>
                </div>
            </div>
            
            <!-- Children Counter -->
            <div class="enquiry-counter-row">
                <span class="counter-label">Children (0 to 15 yrs)</span>
                <div class="counter-controls">
                    <button type="button" class="btn-counter-minus" data-type="children">&minus;</button>
                    <span class="counter-value" id="room-${roomCount}-children-val">0</span>
                    <button type="button" class="btn-counter-plus" data-type="children">+</button>
                </div>
            </div>

            <!-- Child Age Dropdowns Container -->
            <div class="enquiry-child-ages-list" id="room-${roomCount}-child-ages" style="display: none;"></div>
        `;
        
        roomsContainer.appendChild(newRoom);
        setupRoomCardEvents(newRoom);
        
        // Bind remove event
        const removeBtn = newRoom.querySelector('.btn-remove-room');
        removeBtn.addEventListener('click', () => {
            hideError();
            newRoom.remove();
            roomCount--;
            reindexRooms();
            updateRoomsConfigJSON();
        });
        
        updateRoomsConfigJSON();
    });

    function reindexRooms() {
        const roomCards = roomsContainer.querySelectorAll('.enquiry-room-card');
        roomCount = roomCards.length;
        
        roomCards.forEach((card, idx) => {
            const newRoomNum = idx + 1;
            card.setAttribute('data-room', newRoomNum);
            
            const title = card.querySelector('.enquiry-room-title');
            title.textContent = `ROOM ${newRoomNum}`;
            
            const adultsVal = card.querySelector('.counter-controls span[id$="-adults-val"]');
            adultsVal.id = `room-${newRoomNum}-adults-val`;
            
            const childrenVal = card.querySelector('.counter-controls span[id$="-children-val"]');
            childrenVal.id = `room-${newRoomNum}-children-val`;
            
            const childAges = card.querySelector('.enquiry-child-ages-list');
            childAges.id = `room-${newRoomNum}-child-ages`;
            
            const selects = childAges.querySelectorAll('select');
            selects.forEach((select, sIdx) => {
                select.name = `room_${newRoomNum}_child_${sIdx + 1}_age`;
            });
            
            const removeBtn = card.querySelector('.btn-remove-room');
            if (newRoomNum === 1) {
                removeBtn.style.display = 'none';
            } else {
                removeBtn.style.display = 'flex';
            }
        });
    }

    function updateRoomsConfigJSON() {
        const config = [];
        const roomCards = roomsContainer.querySelectorAll('.enquiry-room-card');
        
        roomCards.forEach(card => {
            const roomNum = card.getAttribute('data-room');
            const adults = parseInt(card.querySelector(`#room-${roomNum}-adults-val`).textContent);
            const childSelects = card.querySelectorAll('.child-age-select');
            const childrenAges = Array.from(childSelects).map(select => parseInt(select.value) || 0);
            
            config.push({
                room: parseInt(roomNum),
                adults: adults,
                children: childrenAges
            });
        });
        
        document.getElementById('enquiryRoomsConfig').value = JSON.stringify(config);
    }

    function resetRooms() {
        roomsContainer.innerHTML = `
            <div class="enquiry-room-card" data-room="1">
                <div class="enquiry-room-card-header">
                    <h4 class="enquiry-room-title">ROOM 1</h4>
                    <button type="button" class="btn-remove-room" style="display: none;">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                            <polyline points="3 6 5 6 21 6"></polyline>
                            <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                        </svg>
                    </button>
                </div>
                
                <!-- Adults Counter -->
                <div class="enquiry-counter-row">
                    <span class="counter-label">Adults</span>
                    <div class="counter-controls">
                        <button type="button" class="btn-counter-minus" data-type="adults">&minus;</button>
                        <span class="counter-value" id="room-1-adults-val">2</span>
                        <button type="button" class="btn-counter-plus" data-type="adults">+</button>
                    </div>
                </div>
                
                <!-- Children Counter -->
                <div class="enquiry-counter-row">
                    <span class="counter-label">Children (0 to 15 yrs)</span>
                    <div class="counter-controls">
                        <button type="button" class="btn-counter-minus" data-type="children">&minus;</button>
                        <span class="counter-value" id="room-1-children-val">0</span>
                        <button type="button" class="btn-counter-plus" data-type="children">+</button>
                    </div>
                </div>

                <!-- Child Age Dropdowns Container -->
                <div class="enquiry-child-ages-list" id="room-1-child-ages" style="display: none;"></div>
            </div>
        `;
        roomCount = 1;
        setupRoomCardEvents(roomsContainer.querySelector('.enquiry-room-card'));
        updateRoomsConfigJSON();
    }
});
</script>
