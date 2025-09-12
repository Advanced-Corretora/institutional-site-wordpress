import Flickity from 'flickity';

document.addEventListener('DOMContentLoaded', function () {
  // Initialize all diferenciais carousel blocks
  const diferenciaisBlocks = document.querySelectorAll('.wp-block-diferenciais-carousel');

  diferenciaisBlocks.forEach(initializeDiferenciais);
});

function initializeDiferenciais(diferenciaisBlock) {
  const carousel = diferenciaisBlock.querySelector('.diferenciais-carousel');
  const prevButton = diferenciaisBlock.querySelector('.diferenciais-prev');
  const nextButton = diferenciaisBlock.querySelector('.diferenciais-next');

  if (!carousel) {
    console.warn('Diferenciais: Missing carousel element');
    return;
  }

  // Check if mobile for responsive configuration
  const isMobile = window.innerWidth <= 768;

  // Initialize Flickity instance with responsive settings
  const flickity = new Flickity(carousel, {
    wrapAround: true,
    pageDots: false,
    prevNextButtons: true,
    contain: isMobile ? false : true,
    cellAlign: isMobile ? 'center' : 'left',
    initialIndex: 0,
    percentPosition: false,
  });

  // Custom navigation buttons
  if (prevButton && nextButton) {
    prevButton.addEventListener('click', function () {
      flickity.previous();
    });

    nextButton.addEventListener('click', function () {
      flickity.next();
    });

    // Update button states based on current slide
    function updateButtonStates() {
      if (!flickity.options.wrapAround) {
        prevButton.disabled = flickity.selectedIndex === 0;
        nextButton.disabled = flickity.selectedIndex === flickity.slides.length - 1;
      }
    }

    // Listen for slide changes to update button states
    flickity.on('change', updateButtonStates);

    // Initial button state
    updateButtonStates();
  }

  // Handle responsive behavior on window resize
  function handleResize() {
    const newIsMobile = window.innerWidth <= 768;
    if (newIsMobile !== isMobile) {
      // Destroy and recreate with new settings
      flickity.destroy();
      const newFlickity = new Flickity(carousel, {
        wrapAround: true,
        pageDots: false,
        prevNextButtons: false,
        contain: newIsMobile ? false : true,
        cellAlign: newIsMobile ? 'center' : 'left',
        initialIndex: 0,
        percentPosition: false,
      });

      // Update reference
      Object.assign(flickity, newFlickity);
    } else {
      flickity.resize();
    }
  }

  window.addEventListener('resize', handleResize);
}
