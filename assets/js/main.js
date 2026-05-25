/**
 * Main Javascript for Wanderoo
 */

document.addEventListener('DOMContentLoaded', () => {
    console.log('Wanderoo initialized');
    
    // Mobile Menu Toggle
    const mobileToggle = document.querySelector('.mobile-toggle');
    const navGlass = document.querySelector('.nav-glass');
    
    if (mobileToggle) {
        mobileToggle.addEventListener('click', () => {
            navGlass.classList.toggle('active');
            // Basic toggle for demonstration
            if (navGlass.style.display === 'flex') {
                navGlass.style.display = 'none';
            } else {
                navGlass.style.display = 'flex';
                navGlass.style.flexDirection = 'column';
                navGlass.style.position = 'absolute';
                navGlass.style.top = '80px';
                navGlass.style.left = '20px';
                navGlass.style.right = '20px';
                navGlass.style.borderRadius = '20px';
            }
        });
    }

    // Packages Slider Carousel (Desktop, Tablet & Mobile)
    const sliderContainers = document.querySelectorAll('.packages-slider-container');
    
    sliderContainers.forEach(sliderContainer => {
        const track = sliderContainer.querySelector('.packages-grid');
        const prevArrow = sliderContainer.querySelector('.slider-arrow-prev');
        const nextArrow = sliderContainer.querySelector('.slider-arrow-next');
        const dots = sliderContainer.querySelectorAll('.dot');
        const cards = sliderContainer.querySelectorAll('.package-card');
        
        if (!track || cards.length === 0) return;
        
        let currentIndex = 0;
        let startX = 0;
        let currentTranslate = 0;
        let prevTranslate = 0;
        let isDragging = false;
        let animationId = 0;
        let autoSlideTimer = null;
        
        // Calculate dimensions dynamically
        function getVisibleCards() {
            if (window.innerWidth <= 768) return 1;
            if (window.innerWidth <= 1024) return 2;
            if (sliderContainer.closest('.wander-section')) return 4;
            return 3;
        }
        
        function getMaxIndex() {
            return Math.max(0, cards.length - getVisibleCards());
        }
        
        function getCardWidth() {
            const card = cards[0];
            return card ? card.offsetWidth : 0;
        }
        
        function getGap() {
            return 30; // Gap is 30px
        }
        
        function setPositionByIndex() {
            const cardWidth = getCardWidth();
            const gap = getGap();
            currentTranslate = -currentIndex * (cardWidth + gap);
            prevTranslate = currentTranslate;
            setSliderPosition();
            updateDots();
        }
        
        function setSliderPosition() {
            track.style.transform = `translateX(${currentTranslate}px)`;
        }
        
        function updateDots() {
            if (dots.length > 0) {
                dots.forEach((dot, i) => {
                    dot.classList.toggle('active', i === currentIndex);
                });
            }
        }
        
        // Navigation function
        function slideTo(index) {
            const maxIndex = getMaxIndex();
            currentIndex = index;
            if (currentIndex < 0) {
                currentIndex = maxIndex;
            } else if (currentIndex > maxIndex) {
                currentIndex = 0;
            }
            track.style.transition = 'transform 0.5s cubic-bezier(0.25, 0.46, 0.45, 0.94)';
            setPositionByIndex();
        }
        
        // Event Listeners for Arrows
        if (nextArrow) {
            nextArrow.addEventListener('click', () => {
                resetAutoSlide();
                slideTo(currentIndex + 1);
            });
        }
        
        if (prevArrow) {
            prevArrow.addEventListener('click', () => {
                resetAutoSlide();
                slideTo(currentIndex - 1);
            });
        }
        
        // Auto slide (Only for Honeymooners to avoid excessive background activity)
        const shouldAutoSlide = !sliderContainer.closest('.wander-section');
        
        function startAutoSlide() {
            if (!shouldAutoSlide) return;
            autoSlideTimer = setInterval(() => {
                slideTo(currentIndex + 1);
            }, 4000); // slide every 4 seconds
        }
        
        function stopAutoSlide() {
            if (autoSlideTimer) {
                clearInterval(autoSlideTimer);
            }
        }
        
        function resetAutoSlide() {
            stopAutoSlide();
            startAutoSlide();
        }
        
        if (shouldAutoSlide) {
            sliderContainer.addEventListener('mouseenter', stopAutoSlide);
            sliderContainer.addEventListener('mouseleave', startAutoSlide);
        }
        
        // Drag / Swipe Functionality (Cursor & Touch)
        track.addEventListener('mousedown', dragStart);
        track.addEventListener('touchstart', dragStart, { passive: true });
        
        window.addEventListener('mousemove', dragMove);
        window.addEventListener('touchmove', dragMove, { passive: true });
        
        window.addEventListener('mouseup', dragEnd);
        window.addEventListener('touchend', dragEnd);
        
        function dragStart(e) {
            isDragging = true;
            startX = getPositionX(e);
            stopAutoSlide();
            track.style.transition = 'none'; // remove transition during drag
            animationId = requestAnimationFrame(animation);
        }
        
        function dragMove(e) {
            if (!isDragging) return;
            const currentX = getPositionX(e);
            const diffX = currentX - startX;
            currentTranslate = prevTranslate + diffX;
        }
        
        function dragEnd() {
            if (!isDragging) return;
            isDragging = false;
            cancelAnimationFrame(animationId);
            
            track.style.transition = 'transform 0.5s cubic-bezier(0.25, 0.46, 0.45, 0.94)';
            
            const movedBy = currentTranslate - prevTranslate;
            const cardWidth = getCardWidth();
            const threshold = cardWidth / 4; // swipe 25% of card to slide
            
            if (movedBy < -threshold) {
                slideTo(currentIndex + 1);
            } else if (movedBy > threshold) {
                slideTo(currentIndex - 1);
            } else {
                slideTo(currentIndex);
            }
            
            startAutoSlide();
        }
        
        function getPositionX(e) {
            return e.type.includes('touch') ? e.touches[0].clientX : e.clientX;
        }
        
        function animation() {
            setSliderPosition();
            if (isDragging) requestAnimationFrame(animation);
        }
        
        // Initial setup and responsive resizing
        window.addEventListener('resize', () => {
            slideTo(currentIndex);
        });
        
        // Start slider
        setTimeout(() => {
            slideTo(0);
        }, 200);
        startAutoSlide();
    });

    // Tabs Filtering for "Where would you like to wander?"
    const tabButtons = document.querySelectorAll('.tab-btn');
    const categories = document.querySelectorAll('.category-packages');
    
    if (tabButtons.length > 0 && categories.length > 0) {
        tabButtons.forEach(button => {
            button.addEventListener('click', () => {
                const target = button.getAttribute('data-target');
                
                // Update active button
                tabButtons.forEach(btn => btn.classList.remove('active'));
                button.classList.add('active');
                
                // Show matching category, hide others
                categories.forEach(cat => {
                    if (cat.id === target) {
                        cat.classList.add('active');
                        cat.style.display = 'block';
                        setTimeout(() => {
                            cat.style.opacity = '1';
                            // Force slider recalculation once visible
                            window.dispatchEvent(new Event('resize'));
                        }, 50);
                    } else {
                        cat.classList.remove('active');
                        cat.style.opacity = '0';
                        cat.style.display = 'none';
                    }
                });
            });
        });
    }
});
