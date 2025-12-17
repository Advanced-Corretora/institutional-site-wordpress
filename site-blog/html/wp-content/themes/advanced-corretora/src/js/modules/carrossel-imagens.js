import Flickity from 'flickity';

document.addEventListener('DOMContentLoaded', function () {
  // Initialize all carrossel imagens blocks
  const carrosselImagensBlocks = document.querySelectorAll('.wp-block-carrossel-imagens');

  carrosselImagensBlocks.forEach(initializeCarrosselImagens);
});

function initializeCarrosselImagens(carrosselImagensBlock) {
  const carousel = carrosselImagensBlock.querySelector('.carrossel-imagens-carousel');

  if (!carousel) {
    console.warn('Carrossel Imagens: Missing carousel element');
    return;
  }

  // Check screen size for responsive configuration
  const isMobile = window.innerWidth <= 768;
  const isTablet = window.innerWidth > 768 && window.innerWidth <= 1024;

  // Initialize Flickity instance with responsive settings
  const flickity = new Flickity(carousel, {
    wrapAround: true,
    pageDots: false,
    prevNextButtons: true,
    contain: false, // Allow infinite scroll
    cellAlign: 'left',
    initialIndex: 0,
    percentPosition: false,
    groupCells: isMobile ? 1 : isTablet ? 2 : 4, // 1 on mobile, 2 on tablet, 4 on desktop
  });

  // Handle responsive behavior on window resize
  function handleResize() {
    const newIsMobile = window.innerWidth <= 768;
    const newIsTablet = window.innerWidth > 768 && window.innerWidth <= 1024;

    // Destroy and recreate with new settings if screen size category changed
    const currentGroupCells = isMobile ? 1 : isTablet ? 2 : 4;
    const newGroupCells = newIsMobile ? 1 : newIsTablet ? 2 : 4;

    if (currentGroupCells !== newGroupCells) {
      flickity.destroy();
      const newFlickity = new Flickity(carousel, {
        wrapAround: true,
        pageDots: false,
        prevNextButtons: true,
        contain: false, // Allow infinite scroll
        cellAlign: 'left',
        initialIndex: 0,
        percentPosition: false,
        groupCells: newGroupCells,
      });

      // Update reference
      Object.assign(flickity, newFlickity);
    } else {
      flickity.resize();
    }
  }

  window.addEventListener('resize', handleResize);
}
