import Flickity from 'flickity';

function equalizeCarouselHeights(carouselEl) {
  const products = carouselEl.querySelectorAll('.carousel-cell .product');
  if (!products.length) return;
  // reset to natural height before measuring
  products.forEach(el => (el.style.height = 'auto'));
  let max = 0;
  products.forEach(el => {
    const h = el.offsetHeight;
    if (h > max) max = h;
  });
  if (max > 0) {
    products.forEach(el => (el.style.height = max + 'px'));
  }
}

function setupEqualize(flkty, carouselEl) {
  const debounced = (() => {
    let frame;
    return () => {
      cancelAnimationFrame(frame);
      frame = requestAnimationFrame(() => equalizeCarouselHeights(carouselEl));
    };
  })();

  flkty.on('ready', debounced);
  flkty.on('change', debounced);
  flkty.on('settle', debounced);
  flkty.on('resize', debounced);

  // Recalculate after images load
  const imgs = carouselEl.querySelectorAll('img');
  imgs.forEach(img => {
    if (img.complete) return; // skip already-loaded
    img.addEventListener('load', debounced, { once: true });
    img.addEventListener('error', debounced, { once: true });
  });

  // Initial run
  debounced();
  // After window load as a fallback
  window.addEventListener('load', debounced, { once: true });
}

let flickityInstances = [];

const createFlickityInstance = carousel => {
  const flkty = new Flickity(carousel, {
    wrapAround: true,
    pageDots: false,
    prevNextButtons: true,
    contain: false,
    cellAlign: 'center',
    initialIndex: 0,
    percentPosition: false,
    // add other options here if needed
  });

  // Force hide dots and ensure buttons are visible after initialization
  flkty.on('ready', () => {
    const dotsContainer = carousel.querySelector('.flickity-page-dots');
    if (dotsContainer) {
      dotsContainer.style.display = 'none';
    }
    
    const buttons = carousel.querySelectorAll('.flickity-prev-next-button');
    buttons.forEach(button => {
      button.style.display = 'block';
    });
  });

  return flkty;
};

const destroyAllInstances = () => {
  flickityInstances.forEach(instance => {
    if (instance.flkty && typeof instance.flkty.destroy === 'function') {
      instance.flkty.destroy();
    }
  });
  flickityInstances = [];
};

const initializeCarousels = () => {
  const carousels = document.querySelectorAll('.gutenberg-flickity');

  carousels.forEach(carousel => {
    // Wait a bit to ensure Flickity is fully loaded
    setTimeout(() => {
      const flkty = createFlickityInstance(carousel);
      setupEqualize(flkty, carousel);

      flickityInstances.push({
        element: carousel,
        flkty: flkty,
      });
    }, 50);
  });
};

const gutenbergCarousel = () => {
  initializeCarousels();

  // Debounced resize handler
  let resizeTimeout;
  const handleResize = () => {
    clearTimeout(resizeTimeout);
    resizeTimeout = setTimeout(() => {
      destroyAllInstances();
      initializeCarousels();
    }, 250);
  };

  window.addEventListener('resize', handleResize);
};

// Wait for both DOM and window load to ensure everything is ready
document.addEventListener('DOMContentLoaded', () => {
  // Additional delay to ensure Flickity library is fully loaded
  setTimeout(gutenbergCarousel, 100);
});

// Fallback for window load
window.addEventListener('load', () => {
  // Only initialize if not already done
  if (flickityInstances.length === 0) {
    gutenbergCarousel();
  }
});
