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

  // Initialize Flickity instance
  const flickity = new Flickity(carousel, {
    wrapAround: true,
    pageDots: false,
    prevNextButtons: false,
    cellAlign: 'left',
    contain: true,
    draggable: true,
    freeScroll: false,
    groupCells: false,
  });

  // Custom navigation buttons
  if (prevButton && nextButton) {
    prevButton.addEventListener('click', function() {
      flickity.previous();
    });

    nextButton.addEventListener('click', function() {
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

  // Force resize after initialization to ensure proper layout
  setTimeout(() => {
    flickity.resize();
  }, 100);
}
