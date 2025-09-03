import Flickity from 'flickity';

let homeSliderInstances = [];

const createHomeSliderInstance = slider => {
  return new Flickity(slider, {
    wrapAround: true,
    pageDots: false,
    prevNextButtons: false,
    contain: false,
    cellAlign: 'center',
    initialIndex: 0,
    percentPosition: false,
    // autoPlay: 5000, // Auto-play a cada 5 segundos
    pauseAutoPlayOnHover: true,
    // Configurações específicas para full width
    adaptiveHeight: false,
    setGallerySize: true,
  });
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
