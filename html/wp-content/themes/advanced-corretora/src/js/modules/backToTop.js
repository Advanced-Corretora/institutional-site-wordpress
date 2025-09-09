/**
 * Back to Top functionality
 * 
 * Handles the back to top button behavior
 */

class BackToTop {
    constructor() {
        this.mobileButton = document.getElementById('back-to-top');
        this.desktopButton = document.getElementById('back-to-top-desktop');
        this.scrollThreshold = 300; // Show button after scrolling 300px
        
        this.init();
    }

    init() {
        // Add click event listeners for both buttons
        if (this.mobileButton) {
            this.mobileButton.addEventListener('click', this.scrollToTop.bind(this));
        }
        
        if (this.desktopButton) {
            this.desktopButton.addEventListener('click', this.scrollToTop.bind(this));
        }
        
        // Add scroll event listener for desktop button visibility
        window.addEventListener('scroll', this.handleScroll.bind(this));
        
        // Initial check
        this.handleScroll();
    }

    scrollToTop() {
        // Smooth scroll to top
        window.scrollTo({
            top: 0,
            behavior: 'smooth'
        });
    }

    handleScroll() {
        const scrollTop = window.pageYOffset || document.documentElement.scrollTop;
        
        // Only apply show/hide logic to desktop button
        if (this.desktopButton) {
            if (scrollTop > this.scrollThreshold) {
                this.desktopButton.classList.add('show');
            } else {
                this.desktopButton.classList.remove('show');
            }
        }
    }
}

// Initialize when DOM is loaded
document.addEventListener('DOMContentLoaded', () => {
    new BackToTop();
});

export default BackToTop;
