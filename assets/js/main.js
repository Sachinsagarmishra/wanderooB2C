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

    // Package Slider Auto-slide (Mobile)
    const packageGrid = document.querySelector('.packages-grid');
    if (packageGrid && window.innerWidth <= 768) {
        let index = 0;
        const cards = document.querySelectorAll('.package-card');
        const dots = document.querySelectorAll('.dot');
        
        setInterval(() => {
            index = (index + 1) % (cards.length - 2); // Only slide through first 3 on mobile if others are hidden
            packageGrid.scrollTo({
                left: cards[index].offsetLeft - 20,
                behavior: 'smooth'
            });
            
            dots.forEach((dot, i) => {
                dot.classList.toggle('active', i === index);
            });
        }, 3000);
    }
