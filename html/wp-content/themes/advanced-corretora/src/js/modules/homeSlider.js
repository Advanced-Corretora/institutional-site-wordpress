import Flickity from 'flickity';

let homeSliderInstances = [];

// Global function to equalize heights for any slider
function equalizeSlideHeights(slider) {
  const slides = slider.querySelectorAll('.slider-cell');
  if (slides.length === 0) return;

  // Reset heights to auto to get natural heights
  slides.forEach(slide => {
    slide.style.height = 'auto';
  });

  // Get the tallest slide height
  let maxHeight = 0;
  slides.forEach(slide => {
    const slideHeight = slide.offsetHeight;
    if (slideHeight > maxHeight) {
      maxHeight = slideHeight;
    }
  });

  // Apply the max height to all slides
  slides.forEach(slide => {
    slide.style.height = maxHeight + 'px';
  });
}

// Function to convert hex color to rgba with opacity
const hexToRgba = (hex, opacity) => {
  const result = /^#?([a-f\d]{2})([a-f\d]{2})([a-f\d]{2})$/i.exec(hex);
  if (result) {
    const r = parseInt(result[1], 16);
    const g = parseInt(result[2], 16);
    const b = parseInt(result[3], 16);
    return `rgba(${r}, ${g}, ${b}, ${opacity})`;
  }
  return null;
};

// Function to update submenu background color
const updateSubmenuColor = (color) => {
  // Remove existing dynamic style if it exists
  const existingStyle = document.getElementById('dynamic-submenu-style');
  if (existingStyle) {
    existingStyle.remove();
  }

  if (color) {
    // Convert color to rgba with 95% opacity
    const rgbaColor = hexToRgba(color, 0.95);
    
    if (rgbaColor) {
      // Create and inject CSS
      const style = document.createElement('style');
      style.id = 'dynamic-submenu-style';
      style.textContent = `
        @media (min-width: 1024px) {
          .header .menu .submenu-area {
            background-color: ${rgbaColor} !important;
          }
        }
      `;
      document.head.appendChild(style);
    }
  }
};

const createHomeSliderInstance = slider => {
  const flkty = new Flickity(slider, {
    wrapAround: true,
    pageDots: false,
    prevNextButtons: false,
    contain: false,
    cellAlign: 'center',
    initialIndex: 0,
    percentPosition: false,
    autoPlay: 8000, // Auto-play a cada 8 segundos
    pauseAutoPlayOnHover: true,
    // Configurações específicas para full width
    adaptiveHeight: false,
    setGallerySize: true,
  });

  // Force hide navigation elements after initialization
  flkty.on('ready', () => {
    const dotsContainer = slider.querySelector('.flickity-page-dots');
    if (dotsContainer) {
      dotsContainer.style.display = 'none';
    }
    
    const buttons = slider.querySelectorAll('.flickity-prev-next-button');
    buttons.forEach(button => {
      button.style.display = 'none';
    });

    // Equalize slide heights after Flickity is ready
    setTimeout(() => equalizeSlideHeights(slider), 100);
  });

  // Also equalize immediately after initialization
  setTimeout(() => equalizeSlideHeights(slider), 200);

  // Re-equalize on window resize
  flkty.on('resize', () => equalizeSlideHeights(slider));

  // Re-equalize when images load (important for slides with background images)
  const images = slider.querySelectorAll('img');
  let loadedImages = 0;
  const totalImages = images.length;

  if (totalImages > 0) {
    images.forEach(img => {
      if (img.complete) {
        loadedImages++;
        if (loadedImages === totalImages) {
          setTimeout(() => equalizeSlideHeights(slider), 50);
        }
      } else {
        img.addEventListener('load', function () {
          loadedImages++;
          if (loadedImages === totalImages) {
            setTimeout(() => equalizeSlideHeights(slider), 50);
          }
        });
      }
    });
  }

  // Handle slide change events
  flkty.on('change', (index) => {
    const currentSlide = slider.querySelector('.slider-cell.is-selected');
    if (currentSlide) {
      const submenuColor = currentSlide.getAttribute('data-submenu-color');
      updateSubmenuColor(submenuColor);
    }
  });

  // Set initial color on load
  setTimeout(() => {
    const initialSlide = slider.querySelector('.slider-cell.is-selected');
    if (initialSlide) {
      const submenuColor = initialSlide.getAttribute('data-submenu-color');
      updateSubmenuColor(submenuColor);
    }
  }, 100);

  return flkty;
};

const destroyAllHomeSliderInstances = () => {
  homeSliderInstances.forEach(instance => {
    if (instance.flkty && typeof instance.flkty.destroy === 'function') {
      instance.flkty.destroy();
    }
  });
  homeSliderInstances = [];
};

const initializeHomeSliders = () => {
  const sliders = document.querySelectorAll('.gutenberg-home-slider');

  sliders.forEach(slider => {
    // Wait a bit to ensure Flickity is fully loaded
    setTimeout(() => {
      const flkty = createHomeSliderInstance(slider);

      homeSliderInstances.push({
        element: slider,
        flkty: flkty,
      });
    }, 50);
  });
};

const gutenbergHomeSlider = () => {
  initializeHomeSliders();

  // Debounced resize handler
  let resizeTimeout;
  const handleResize = () => {
    clearTimeout(resizeTimeout);
    resizeTimeout = setTimeout(() => {
      destroyAllHomeSliderInstances();
      initializeHomeSliders();
      
      // Re-equalize heights after resize
      setTimeout(() => {
        const sliders = document.querySelectorAll('.gutenberg-home-slider');
        sliders.forEach(slider => {
          equalizeSlideHeights(slider);
        });
      }, 100);
    }, 250);
  };

  window.addEventListener('resize', handleResize);
};

// Wait for both DOM and window load to ensure everything is ready
document.addEventListener('DOMContentLoaded', () => {
  // Additional delay to ensure Flickity library is fully loaded
  setTimeout(gutenbergHomeSlider, 100);
});

// Fallback for window load
window.addEventListener('load', () => {
  // Only initialize if not already done
  if (homeSliderInstances.length === 0) {
    gutenbergHomeSlider();
  }
  
  // Additional check when window is fully loaded (including images)
  setTimeout(() => {
    const sliders = document.querySelectorAll('.gutenberg-home-slider');
    sliders.forEach(slider => {
      equalizeSlideHeights(slider);
    });
  }, 100);
});
