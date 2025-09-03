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
  return new Flickity(carousel, {
    wrapAround: true,
    pageDots: false,
    prevNextButtons: true,
    contain: false,
    cellAlign: 'center',
    initialIndex: 0,
    percentPosition: false,
    // add other options here if needed
  });
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
    const flkty = createFlickityInstance(carousel);
    setupEqualize(flkty, carousel);

    flickityInstances.push({
      element: carousel,
      flkty: flkty,
    });
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

document.addEventListener('DOMContentLoaded', gutenbergCarousel);
