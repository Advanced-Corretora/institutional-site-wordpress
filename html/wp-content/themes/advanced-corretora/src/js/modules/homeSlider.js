import Flickity from 'flickity';

let homeSliderInstances = [];

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
    const flkty = createHomeSliderInstance(slider);

    homeSliderInstances.push({
      element: slider,
      flkty: flkty,
    });
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
    }, 250);
  };

  window.addEventListener('resize', handleResize);
};

document.addEventListener('DOMContentLoaded', gutenbergHomeSlider);
