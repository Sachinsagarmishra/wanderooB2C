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
            mobileToggle.classList.toggle('active');
        });
    }

    const navDropdownTrigger = document.querySelector('.nav-dropdown-trigger');
    const navDropdown = document.querySelector('.nav-dropdown');
    if (navDropdownTrigger && navDropdown) {
        // Desktop Hover Persistence Handling
        navDropdown.addEventListener('mouseenter', () => {
            if (window.innerWidth > 1024) {
                navDropdown.classList.add('hover-active');
            }
        });

        // Click Handler (Mobile toggle / Desktop toggle)
        navDropdownTrigger.addEventListener('click', (e) => {
            e.preventDefault();
            if (window.innerWidth <= 1024) {
                navDropdown.classList.toggle('active');
            } else {
                navDropdown.classList.toggle('hover-active');
            }
        });

        // Close dropdown when clicking outside
        document.addEventListener('click', (e) => {
            if (!navDropdown.contains(e.target)) {
                navDropdown.classList.remove('hover-active');
            }
        });

        // Close dropdown when pressing Escape key
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') {
                navDropdown.classList.remove('hover-active');
            }
        });
    }

    // Packages Slider Carousel (Desktop, Tablet & Mobile)
    const sliderContainers = document.querySelectorAll('.packages-slider-container');
    
    sliderContainers.forEach(sliderContainer => {
        const track = sliderContainer.querySelector('.packages-grid');
        const prevArrow = sliderContainer.querySelector('.slider-arrow-prev');
        const nextArrow = sliderContainer.querySelector('.slider-arrow-next');
        const dotsContainer = sliderContainer.querySelector('.slider-dots');
        const cards = sliderContainer.querySelectorAll('.package-card');
        
        if (!track || cards.length === 0) return;
        
        let currentIndex = 0;
        let startX = 0;
        let startY = 0;
        let isScrolling = false;
        let isSwiping = false;
        let currentTranslate = 0;
        let prevTranslate = 0;
        let isDragging = false;
        let animationId = 0;
        let autoSlideTimer = null;
        
        // Calculate dimensions dynamically
        function getVisibleCards() {
            if (window.innerWidth <= 768) return 1;
            if (window.innerWidth <= 1024) return 2;
            return 4; // Show 4 cards in a row on desktop for all sections
        }
        
        function getMaxIndex() {
            return Math.max(0, cards.length - getVisibleCards());
        }
        
        function getCardWidth() {
            const card = cards[0];
            return card ? card.offsetWidth : 0;
        }
        
        function getGap() {
            return 10; // Gap is now 10px
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
        
        function buildDots() {
            if (!dotsContainer) return;
            dotsContainer.innerHTML = '';
            const maxIndex = getMaxIndex();
            for (let i = 0; i <= maxIndex; i++) {
                const dot = document.createElement('span');
                dot.classList.add('dot');
                if (i === currentIndex) dot.classList.add('active');
                dot.addEventListener('click', () => {
                    resetAutoSlide();
                    slideTo(i);
                });
                dotsContainer.appendChild(dot);
            }
        }
        
        function updateDots() {
            if (dotsContainer) {
                const dots = dotsContainer.querySelectorAll('.dot');
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
        track.addEventListener('touchmove', dragMove, { passive: false });
        
        window.addEventListener('mouseup', dragEnd);
        track.addEventListener('touchend', dragEnd);
        
        function dragStart(e) {
            isDragging = true;
            isScrolling = false;
            isSwiping = false;
            startX = getPositionX(e);
            startY = getPositionY(e);
            stopAutoSlide();
            track.style.transition = 'none'; // remove transition during drag
        }
        
        function dragMove(e) {
            if (!isDragging) return;
            const currentX = getPositionX(e);
            const currentY = getPositionY(e);
            const diffX = currentX - startX;
            const diffY = currentY - startY;

            if (Math.abs(diffX) > 10 || Math.abs(diffY) > 10) {
                window.wanderooDragging = true;
            }

            if (window.innerWidth <= 768) {
                if (!isScrolling && !isSwiping) {
                    if (Math.abs(diffX) > 5 || Math.abs(diffY) > 5) {
                        if (Math.abs(diffX) > Math.abs(diffY)) {
                            isSwiping = true;
                        } else {
                            isScrolling = true;
                        }
                    }
                }

                if (isScrolling) {
                    return; // let native scrolling handle it
                }

                if (isSwiping) {
                    if (e.cancelable) e.preventDefault();
                    currentTranslate = prevTranslate + diffX;
                    if (animationId === 0) {
                        animationId = requestAnimationFrame(animation);
                    }
                }
            } else {
                currentTranslate = prevTranslate + diffX;
                if (animationId === 0) {
                    animationId = requestAnimationFrame(animation);
                }
            }
        }
        
        function dragEnd() {
            if (!isDragging) return;
            isDragging = false;
            if (animationId !== 0) {
                cancelAnimationFrame(animationId);
                animationId = 0;
            }
            
            track.style.transition = 'transform 0.5s cubic-bezier(0.25, 0.46, 0.45, 0.94)';
            
            if (window.innerWidth <= 768) {
                if (isSwiping) {
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
                } else {
                    slideTo(currentIndex);
                }
            } else {
                const movedBy = currentTranslate - prevTranslate;
                const cardWidth = getCardWidth();
                const threshold = cardWidth / 4;
                
                if (movedBy < -threshold) {
                    slideTo(currentIndex + 1);
                } else if (movedBy > threshold) {
                    slideTo(currentIndex - 1);
                } else {
                    slideTo(currentIndex);
                }
            }
            
            startAutoSlide();
            setTimeout(() => {
                window.wanderooDragging = false;
            }, 50);
        }
        
        function getPositionX(e) {
            return e.type.includes('touch') ? e.touches[0].clientX : e.clientX;
        }

        function getPositionY(e) {
            return e.type.includes('touch') ? e.touches[0].clientY : e.clientY;
        }
        
        function animation() {
            setSliderPosition();
            if (isDragging) requestAnimationFrame(animation);
        }
        
        // Initial setup and responsive resizing
        window.addEventListener('resize', () => {
            buildDots();
            slideTo(currentIndex);
        });
        
        // Start slider
        setTimeout(() => {
            buildDots();
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

    // Destination package filters
    const destinationFilterLayout = document.querySelector('.destination-package-layout');
    if (destinationFilterLayout) {
        const filterGroups = destinationFilterLayout.querySelectorAll('.destination-filter-group');
        const filterChips = destinationFilterLayout.querySelectorAll('.destination-filter-chip');
        const clearBtn = destinationFilterLayout.querySelector('.destination-filter-clear');
        const filterCards = destinationFilterLayout.querySelectorAll('.destination-filter-results .package-card');
        const noResults = destinationFilterLayout.querySelector('.destination-no-filter-results');
        const selectedFilters = {};

        filterGroups.forEach(group => {
            const toggle = group.querySelector('.destination-filter-toggle');
            if (!toggle) return;
            toggle.addEventListener('click', () => {
                group.classList.toggle('active');
            });
        });

        function applyDestinationFilters() {
            let visibleCount = 0;

            filterCards.forEach(card => {
                let matches = true;
                Object.keys(selectedFilters).forEach(key => {
                    const values = Array.from(selectedFilters[key] || []);
                    if (values.length === 0) return;
                    const cardValues = (card.dataset[`filter${key.charAt(0).toUpperCase()}${key.slice(1)}`] || '').split(/\s+/);
                    const groupMatches = values.some(value => cardValues.includes(value));
                    if (!groupMatches) matches = false;
                });

                card.style.display = matches ? '' : 'none';
                if (matches) visibleCount++;
            });

            if (noResults) {
                noResults.style.display = visibleCount === 0 ? 'block' : 'none';
            }
        }

        filterChips.forEach(chip => {
            chip.addEventListener('click', () => {
                const key = chip.dataset.filterKey;
                const value = chip.dataset.filterValue;
                if (!selectedFilters[key]) selectedFilters[key] = new Set();

                if (selectedFilters[key].has(value)) {
                    selectedFilters[key].delete(value);
                    chip.classList.remove('active');
                } else {
                    selectedFilters[key].add(value);
                    chip.classList.add('active');
                }

                applyDestinationFilters();
            });
        });

        if (clearBtn) {
            clearBtn.addEventListener('click', () => {
                Object.keys(selectedFilters).forEach(key => selectedFilters[key].clear());
                filterChips.forEach(chip => chip.classList.remove('active'));
                applyDestinationFilters();
            });
        }

        // Mobile Filter Drawer Toggle
        const sidebar = destinationFilterLayout.querySelector('.destination-filter-sidebar');
        const openBtn = document.querySelector('.btn-mobile-filter-trigger');
        const closeBtn = destinationFilterLayout.querySelector('.btn-mobile-filter-close');
        const applyBtn = destinationFilterLayout.querySelector('.destination-filter-apply');

        if (sidebar) {
            if (openBtn) {
                openBtn.addEventListener('click', () => {
                    sidebar.classList.add('open');
                    document.body.style.overflow = 'hidden';
                });
            }
            if (closeBtn) {
                closeBtn.addEventListener('click', () => {
                    sidebar.classList.remove('open');
                    document.body.style.overflow = '';
                });
            }
            if (applyBtn) {
                applyBtn.addEventListener('click', () => {
                    if (sidebar.classList.contains('open')) {
                        sidebar.classList.remove('open');
                        document.body.style.overflow = '';
                    }
                    const resultsSection = destinationFilterLayout.querySelector('.destination-filter-results');
                    if (resultsSection && window.innerWidth <= 992) {
                        resultsSection.scrollIntoView({ behavior: 'smooth', block: 'start' });
                    }
                });
            }
        }
    }

    // Staggered card image slideshow auto-cycle
    const cardsList = document.querySelectorAll('.package-card');
    
    cardsList.forEach((card, cardIndex) => {
        const imgContainer = card.querySelector('.card-img');
        if (!imgContainer) return;
        const originalImg = imgContainer.querySelector('img');
        const dots = imgContainer.querySelectorAll('.img-dot');
        if (!originalImg || dots.length === 0) return;

        let images = [];
        let alts = [];
        try {
            images = JSON.parse(card.dataset.cardImages || '[]');
            alts = JSON.parse(card.dataset.cardAlts || '[]');
        } catch (e) {
            images = [];
            alts = [];
        }

        if (images.length === 0) {
            images = [originalImg.src];
            alts = [originalImg.alt || 'Package image'];
        }
        
        const imgElements = [];
        originalImg.src = images[0];
        originalImg.alt = alts[0] || originalImg.alt || 'Package image';
        originalImg.classList.add('active');
        imgElements.push(originalImg);
        
        // Pre-append other images to the container for smooth fading
        for (let i = 1; i < images.length; i++) {
            const newImg = document.createElement('img');
            newImg.src = images[i];
            newImg.alt = alts[i] || originalImg.alt || 'Package image';
            imgContainer.insertBefore(newImg, imgContainer.querySelector('.card-img-dots'));
            imgElements.push(newImg);
        }

        dots.forEach((dot, idx) => {
            if (idx >= images.length) {
                dot.remove();
            }
        });

        if (images.length <= 1) {
            const dotsWrap = imgContainer.querySelector('.card-img-dots');
            if (dotsWrap) dotsWrap.style.display = 'none';
        }
        
        let activeImgIndex = 0;
        
        // Add click events to image pagination dots for manual override
        dots.forEach((dot, dotIdx) => {
            dot.addEventListener('click', (e) => {
                e.stopPropagation();
                if (dotIdx === activeImgIndex) return;
                
                imgElements[activeImgIndex].classList.remove('active');
                activeImgIndex = dotIdx;
                imgElements[activeImgIndex].classList.add('active');
                
                dots.forEach((d, idx) => {
                    d.classList.toggle('active', idx === activeImgIndex);
                });
            });
        });
        
        // Add a slight stagger delay based on card index so cards do not slide synchronously
        const staggerDelay = (cardIndex * 800) % 3000;
        
        setTimeout(() => {
            setInterval(() => {
                // Deactivate current image
                imgElements[activeImgIndex].classList.remove('active');
                
                // Advance index
                activeImgIndex = (activeImgIndex + 1) % imgElements.length;
                
                // Activate new image
                imgElements[activeImgIndex].classList.add('active');
                
                // Update active class on pagination dots
                dots.forEach((dot, dotIdx) => {
                    dot.classList.toggle('active', dotIdx === activeImgIndex);
                });
            }, 5000); // Cycle every 5 seconds
        }, staggerDelay);
    });

    // FAQ Accordion Interactivity
    const faqItems = document.querySelectorAll('.faq-item');
    if (faqItems.length > 0) {
        faqItems.forEach(item => {
            const header = item.querySelector('.faq-header');
            if (!header) return;
            
            header.addEventListener('click', () => {
                const isActive = item.classList.contains('active');
                
                if (isActive) {
                    item.classList.remove('active');
                    const svg = item.querySelector('.faq-toggle svg');
                    if (svg) {
                        svg.innerHTML = '<line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line>';
                    }
                } else {
                    item.classList.add('active');
                    const svg = item.querySelector('.faq-toggle svg');
                    if (svg) {
                        svg.innerHTML = '<line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line>';
                    }
                }
            });
        });
    }

    // How It Works Tab Switching
    const howTabs = document.querySelectorAll('.how-tab-btn');
    const howPanels = document.querySelectorAll('.how-panel');
    
    if (howTabs.length > 0 && howPanels.length > 0) {
        howTabs.forEach(tab => {
            tab.addEventListener('click', () => {
                const targetTab = tab.getAttribute('data-tab');
                
                // Remove active classes from all tabs and hide panels
                howTabs.forEach(t => t.classList.remove('active'));
                howPanels.forEach(p => {
                    p.classList.remove('active');
                    p.style.display = 'none';
                });
                
                // Add active classes to selected tab and show active panel
                tab.classList.add('active');
                const activePanel = document.getElementById(`how-panel-${targetTab}`);
                if (activePanel) {
                    activePanel.style.display = 'block';
                    setTimeout(() => {
                        activePanel.classList.add('active');
                    }, 50);
                }
            });
        });
    }

    // Testimonials Slider Carousel
    const testimonialSlider = document.querySelector('.testimonials-section');
    if (testimonialSlider) {
        const track = testimonialSlider.querySelector('.testimonials-grid');
        const prevArrow = testimonialSlider.querySelector('.slider-arrow-prev');
        const nextArrow = testimonialSlider.querySelector('.slider-arrow-next');
        const dotsContainer = testimonialSlider.querySelector('.slider-dots');
        const cards = testimonialSlider.querySelectorAll('.testimonial-card');
        
        if (track && cards.length > 0) {
            let currentIndex = 0;
            let startX = 0;
            let currentTranslate = 0;
            let prevTranslate = 0;
            let isDragging = false;
            let animationId = 0;
            
            function getVisibleCards() {
                if (window.innerWidth <= 768) return 1;
                if (window.innerWidth <= 1024) return 2;
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
                return 10; // Gap is 10px
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
            
            function buildDots() {
                if (!dotsContainer) return;
                dotsContainer.innerHTML = '';
                const maxIndex = getMaxIndex();
                for (let i = 0; i <= maxIndex; i++) {
                    const dot = document.createElement('span');
                    dot.classList.add('dot');
                    if (i === currentIndex) dot.classList.add('active');
                    dot.addEventListener('click', () => {
                        slideTo(i);
                    });
                    dotsContainer.appendChild(dot);
                }
            }
            
            function updateDots() {
                if (dotsContainer) {
                    const dots = dotsContainer.querySelectorAll('.dot');
                    dots.forEach((dot, i) => {
                        dot.classList.toggle('active', i === currentIndex);
                    });
                }
            }
            
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
                    slideTo(currentIndex + 1);
                });
            }
            
            if (prevArrow) {
                prevArrow.addEventListener('click', () => {
                    slideTo(currentIndex - 1);
                });
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
                    slideTo(Math.min(currentIndex + 1, getMaxIndex()));
                } else if (movedBy > threshold) {
                    slideTo(Math.max(currentIndex - 1, 0));
                } else {
                    slideTo(currentIndex);
                }
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
                const maxIdx = getMaxIndex();
                if (currentIndex > maxIdx) {
                    currentIndex = maxIdx;
                }
                buildDots();
                slideTo(currentIndex);
            });
            
            // Start slider
            setTimeout(() => {
                buildDots();
                slideTo(0);
            }, 200);
        }
    }

    // Package Detail Itinerary Tabs & Accordion Interactivity
    const itinerarySection = document.querySelector('.itinerary-accordion');
    if (itinerarySection) {
        const tabs = document.querySelectorAll('.itinerary-tab-btn');
        const items = document.querySelectorAll('.itinerary-item');
        
        // Sync active state helper
        function expandItem(item) {
            // Collapse all others
            items.forEach(i => {
                i.classList.remove('active');
                const body = i.querySelector('.itinerary-body');
                if (body) body.style.maxHeight = null;
            });
            
            // Expand this one
            item.classList.add('active');
            const body = item.querySelector('.itinerary-body');
            if (body) {
                body.style.maxHeight = body.scrollHeight + 'px';
            }
            
            // Sync Tab Button
            const dayId = item.id.replace('day-', '');
            tabs.forEach(tab => {
                if (tab.getAttribute('data-day') === dayId) {
                    tab.classList.add('active');
                } else {
                    tab.classList.remove('active');
                }
            });
        }
        
        function collapseItem(item) {
            item.classList.remove('active');
            const body = item.querySelector('.itinerary-body');
            if (body) body.style.maxHeight = null;
        }
        
        // Tab Clicks
        tabs.forEach(tab => {
            tab.addEventListener('click', () => {
                const dayId = tab.getAttribute('data-day');
                const targetItem = document.getElementById(`day-${dayId}`);
                if (targetItem) {
                    expandItem(targetItem);
                }
            });
        });
        
        // Accordion Header Clicks
        items.forEach(item => {
            const header = item.querySelector('.itinerary-header');
            if (header) {
                header.addEventListener('click', () => {
                    const isActive = item.classList.contains('active');
                    if (isActive) {
                        collapseItem(item);
                    } else {
                        expandItem(item);
                    }
                });
            }
        });
        
        // Initialize: expand the first active one on load
        const activeItem = document.querySelector('.itinerary-item.active');
        if (activeItem) {
            const body = activeItem.querySelector('.itinerary-body');
            if (body) {
                // Wait slightly for DOM styling to apply
                setTimeout(() => {
                    body.style.maxHeight = body.scrollHeight + 'px';
                }, 100);
            }
        }
    }

    // Gallery Lightbox Modal Interactivity
    const viewAllBtns = document.querySelectorAll('.btn-view-all-images');
    const galleryModal = document.getElementById('galleryModal');
    if (viewAllBtns.length > 0 && galleryModal) {
        const closeBtn = document.getElementById('closeGallery');
        const modalImg = document.getElementById('galleryModalImg');
        const prevBtn = document.getElementById('prevGalleryImg');
        const nextBtn = document.getElementById('nextGalleryImg');
        const counter = document.getElementById('galleryCounter');
        const thumbsContainer = document.getElementById('galleryModalThumbs');
        
        const galleryImages = window.packageGalleryImages && window.packageGalleryImages.length > 0
            ? window.packageGalleryImages
            : [];
        
        let activeIdx = 0;
        
        // Build thumbnails once
        thumbsContainer.innerHTML = '';
        galleryImages.forEach((imgSrc, idx) => {
            const thumb = document.createElement('div');
            thumb.classList.add('gallery-modal-thumb');
            if (idx === 0) thumb.classList.add('active');
            
            const img = document.createElement('img');
            img.src = imgSrc;
            img.alt = `Thumb ${idx + 1}`;
            
            thumb.appendChild(img);
            thumb.addEventListener('click', () => {
                showImage(idx);
            });
            thumbsContainer.appendChild(thumb);
        });
        
        const thumbs = thumbsContainer.querySelectorAll('.gallery-modal-thumb');
        
        function showImage(idx) {
            activeIdx = idx;
            
            // Fade out current image
            modalImg.classList.remove('active');
            
            setTimeout(() => {
                modalImg.src = galleryImages[activeIdx];
                modalImg.classList.add('active');
                
                // Update counter
                counter.textContent = `${activeIdx + 1} of ${galleryImages.length}`;
                
                // Update thumbnails active state
                thumbs.forEach((thumb, tIdx) => {
                    thumb.classList.toggle('active', tIdx === activeIdx);
                });
                
                // Scroll active thumbnail into view inside thumbs container
                const activeThumb = thumbs[activeIdx];
                if (activeThumb) {
                    activeThumb.scrollIntoView({
                        behavior: 'smooth',
                        block: 'nearest',
                        inline: 'center'
                    });
                }
            }, 150);
        }
        
        function nextImage() {
            let nextIdx = (activeIdx + 1) % galleryImages.length;
            showImage(nextIdx);
        }
        
        function prevImage() {
            let prevIdx = (activeIdx - 1 + galleryImages.length) % galleryImages.length;
            showImage(prevIdx);
        }
        
        // Open Modal
        function openGallery(startIdx = 0) {
            galleryModal.style.display = 'flex';
            setTimeout(() => {
                galleryModal.classList.add('show');
                showImage(startIdx);
            }, 10);
            document.body.style.overflow = 'hidden'; // disable page scroll
        }

        window.openGallery = openGallery;
        
        viewAllBtns.forEach(btn => {
            btn.addEventListener('click', () => {
                openGallery(0);
            });
        });
        
        // Close Modal
        function closeModal() {
            galleryModal.classList.remove('show');
            setTimeout(() => {
                galleryModal.style.display = 'none';
            }, 400);
            document.body.style.overflow = ''; // restore page scroll
        }
        
        closeBtn.addEventListener('click', closeModal);
        
        // Click outside image content to close
        galleryModal.addEventListener('click', (e) => {
            if (e.target === galleryModal || e.target.classList.contains('gallery-modal-content')) {
                closeModal();
            }
        });
        
        // Prev/Next Clicks
        nextBtn.addEventListener('click', (e) => {
            e.stopPropagation();
            nextImage();
        });
        
        prevBtn.addEventListener('click', (e) => {
            e.stopPropagation();
            prevImage();
        });
        
        // Keyboard bindings
        window.addEventListener('keydown', (e) => {
            if (!galleryModal.classList.contains('show')) return;
            
            if (e.key === 'ArrowRight') {
                nextImage();
            } else if (e.key === 'ArrowLeft') {
                prevImage();
            } else if (e.key === 'Escape') {
                closeModal();
            }
        });
    }

    // Make package cards fully clickable
    document.addEventListener('click', (e) => {
        const card = e.target.closest('.package-card');
        if (!card) return;

        // Check if we are dragging a slider
        if (window.wanderooDragging) return;

        // If clicking on phone button, enquiry/request/craft button, slider dots/arrows, or any link, do not trigger card navigation
        if (
            e.target.closest('.btn-phone') || 
            e.target.closest('.btn-enquire') || 
            e.target.closest('.btn-request') || 
            e.target.closest('.img-dot') || 
            e.target.closest('.card-img-dots') ||
            e.target.closest('.card-img-arrow') || 
            e.target.closest('.slider-arrow') ||
            e.target.tagName === 'A' ||
            e.target.closest('a')
        ) {
            return;
        }

        // Find the title link to navigate to
        const titleLink = card.querySelector('h3 a');
        if (titleLink) {
            window.location.href = titleLink.href;
        }
    });
});
