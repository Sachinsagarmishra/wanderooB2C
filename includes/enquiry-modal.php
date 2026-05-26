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
                <!-- Step 1: Select Destination -->
                <div class="enquiry-form-step active" data-step="1">
                    <h3 class="enquiry-step-title">Select Destination</h3>
                    <div class="enquiry-input-group">
                        <select id="enquiryDestination" name="destination" class="enquiry-select-field">
                            <option value="" disabled selected>Choose your dream destination...</option>
                            <option value="maldives">Maldives</option>
                            <option value="singapore">Singapore</option>
                            <option value="bali">Bali</option>
                            <option value="japan">Japan</option>
                            <option value="kerala">Kerala, India</option>
                        </select>
                    </div>
                </div>

                <!-- Step 2: Select Travel Date -->
                <div class="enquiry-form-step" data-step="2">
                    <h3 class="enquiry-step-title">Select Travel Date</h3>
                    <div class="enquiry-input-group">
                        <label for="enquiryDate" class="enquiry-input-label">Departure Date</label>
                        <input type="date" id="enquiryDate" name="departure_date" class="enquiry-input-field">
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
                            <span class="option-icon">👫</span>
                            Couple
                        </div>
                        <div class="enquiry-option-card with-icon" data-value="Family">
                            <span class="option-icon">👨‍👩‍👧‍👦</span>
                            Family
                        </div>
                        <div class="enquiry-option-card with-icon" data-value="Friends">
                            <span class="option-icon">👥</span>
                            Friends
                        </div>
                        <div class="enquiry-option-card with-icon" data-value="Solo">
                            <span class="option-icon">🚶</span>
                            Solo
                        </div>
                    </div>
                    <input type="hidden" id="enquiryCompanion" name="companion" value="">
                </div>

                <!-- Step 5: Personal Details -->
                <div class="enquiry-form-step" data-step="5">
                    <h3 class="enquiry-step-title">Personal Details</h3>
                    <div class="enquiry-personal-details-fields">
                        <div class="enquiry-input-group">
                            <label for="enquiryName" class="enquiry-input-label">Full Name *</label>
                            <input type="text" id="enquiryName" name="fullname" class="enquiry-input-field" placeholder="Enter your full name">
                        </div>
                        <div class="enquiry-input-group">
                            <label for="enquiryPhone" class="enquiry-input-label">Phone Number (with country code) *</label>
                            <div class="enquiry-phone-input-wrapper">
                                <span class="enquiry-flag-prefix">🇮🇳 +91</span>
                                <input type="tel" id="enquiryPhone" name="phone" class="enquiry-input-field" placeholder="Enter your phone number">
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
                    </div>
                </div>

                <!-- Step Footer Actions -->
                <div class="enquiry-modal-footer">
                    <button type="button" class="btn-enquiry-prev" id="enquiryPrevBtn" style="display: none;">&larr; Previous</button>
                    <button type="button" class="btn-enquiry-next-step" id="enquiryNextBtn">Next &rarr;</button>
                </div>
            </form>
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
    padding: 45px;
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
    margin-bottom: 25px;
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
}

/* Date Input custom styling */
input[type="date"]::-webkit-calendar-picker-indicator {
    cursor: pointer;
    filter: invert(0.3);
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
    font-size: 24px;
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

    let currentStep = 1;
    const totalSteps = 5;

    // Open modal on click of any enquiry/quote buttons
    const triggerButtons = document.querySelectorAll('.btn-enquire, .btn-quote, .btn-request, .btn-craft-trip, .btn-group-plan');
    triggerButtons.forEach(btn => {
        btn.addEventListener('click', (e) => {
            e.preventDefault();
            e.stopPropagation();
            openModal();
        });
    });

    function openModal() {
        modal.style.display = 'flex';
        document.body.style.overflow = 'hidden';
        setTimeout(() => {
            modal.classList.add('show');
            resetModal();
        }, 10);
    }

    function closeModal() {
        modal.classList.remove('show');
        document.body.style.overflow = '';
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

    // Navigation triggers
    nextBtn.addEventListener('click', () => {
        if (validateStep(currentStep)) {
            if (currentStep < totalSteps) {
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
        
        // Hide current step, show target step
        steps.forEach(step => {
            step.classList.remove('active');
            if (parseInt(step.getAttribute('data-step')) === stepNum) {
                step.classList.add('active');
            }
        });

        currentStep = stepNum;
        
        // Update progress bar & indicators
        const progressPercentage = (currentStep / totalSteps) * 100;
        progressBar.style.width = `${progressPercentage}%`;
        stepIndicator.textContent = `Step ${currentStep} of ${totalSteps}`;

        // Show/hide Previous Button
        if (currentStep === 1) {
            prevBtn.style.display = 'none';
        } else {
            prevBtn.style.display = 'block';
        }

        // Change Next Button text on the final step
        if (currentStep === totalSteps) {
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
        } else if (stepNum === 5) {
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
        goToStep(1);
    }

    function submitEnquiry() {
        // Collect form data
        const formData = new FormData(form);
        const data = {};
        formData.forEach((value, key) => {
            data[key] = value;
        });

        console.log('Enquiry data submitted:', data);

        // Simple UX success notification
        alert('Thank you! Your travel request has been submitted successfully. A destination expert will get back to you shortly.');
        closeModal();
    }
});
</script>
