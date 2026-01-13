import Flickity from 'flickity';

document.addEventListener('DOMContentLoaded', function () {
  // Initialize all diferenciais carousel blocks
  const diferenciaisBlocks = document.querySelectorAll('.wp-block-diferenciais-carousel');

  diferenciaisBlocks.forEach(initializeDiferenciais);

  // Additional check when window is fully loaded (including images)
  window.addEventListener('load', function () {
    diferenciaisBlocks.forEach(block => {
      const carousel = block.querySelector('.diferenciais-carousel');
      if (carousel) {
        equalizeCardHeights(carousel);
      }
    });
  });
});

// Global function to equalize heights for any carousel
function equalizeCardHeights(carousel) {
  const cards = carousel.querySelectorAll('.diferencial-item');
  if (cards.length === 0) return;

  // Reset heights to auto to get natural heights
  cards.forEach(card => {
    card.style.height = 'auto';
  });

  // Get the tallest card height
  let maxHeight = 0;
  cards.forEach(card => {
    const cardHeight = card.offsetHeight;
    if (cardHeight > maxHeight) {
      maxHeight = cardHeight;
    }
  });

  // Apply the max height to all cards
  cards.forEach(card => {
    card.style.height = maxHeight + 'px';
  });
}

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

  // Equalize heights after Flickity is ready
  flickity.on('ready', function () {
    setTimeout(() => equalizeCardHeights(carousel), 100); // Small delay to ensure DOM is ready
  });

  // Also equalize immediately after initialization
  setTimeout(() => equalizeCardHeights(carousel), 200);

  // Re-equalize on window resize
  flickity.on('resize', () => equalizeCardHeights(carousel));

  // Re-equalize when images load (important for cards with images)
  const images = carousel.querySelectorAll('img');
  let loadedImages = 0;
  const totalImages = images.length;

  if (totalImages > 0) {
    images.forEach(img => {
      if (img.complete) {
        loadedImages++;
        if (loadedImages === totalImages) {
          setTimeout(() => equalizeCardHeights(carousel), 50);
        }
      } else {
        img.addEventListener('load', function () {
          loadedImages++;
          if (loadedImages === totalImages) {
            setTimeout(() => equalizeCardHeights(carousel), 50);
          }
        });
      }
    });
  }

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

      // Re-equalize heights after recreating
      setTimeout(() => equalizeCardHeights(carousel), 100);
    } else {
      flickity.resize();
      // Re-equalize heights after resize
      setTimeout(() => equalizeCardHeights(carousel), 100);
    }
  }

  window.addEventListener('resize', handleResize);
}
