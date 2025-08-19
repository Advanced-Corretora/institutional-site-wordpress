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

const gutenbergCarousel = () => {
  const carousels = document.querySelectorAll('.gutenberg-flickity');

  carousels.forEach(carousel => {
    const flkty = new Flickity(carousel, {
      wrapAround: false,
      pageDots: true,
      prevNextButtons: true,
      contain: true,
      cellAlign: 'left',
      // add other options here if needed
    });

    setupEqualize(flkty, carousel);
  });
};

document.addEventListener('DOMContentLoaded', gutenbergCarousel);
