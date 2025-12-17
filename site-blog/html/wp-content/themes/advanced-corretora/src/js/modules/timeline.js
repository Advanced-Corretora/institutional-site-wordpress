import Flickity from 'flickity';

document.addEventListener('DOMContentLoaded', function () {
  // Initialize all timeline blocks
  const timelineBlocks = document.querySelectorAll('.wp-block-timeline');

  timelineBlocks.forEach(initializeTimeline);
});

function initializeTimeline(timelineBlock) {
  const yearsCarousel = timelineBlock.querySelector('.timeline-years');
  const contentCarousel = timelineBlock.querySelector('.timeline-content');

  if (!yearsCarousel || !contentCarousel) {
    console.warn('Timeline: Missing carousel elements');
    return;
  }

  // Calculate middle slide index
  const totalSlides = yearsCarousel.querySelectorAll('.timeline-year-cell').length;
  const middleIndex = Math.floor(totalSlides / 2);

  // Initialize Flickity instances
  const yearsFlickity = new Flickity(yearsCarousel, {
    wrapAround: false,
    pageDots: false,
    prevNextButtons: false,
    cellAlign: 'center',
    contain: true,
    draggable: true,
    freeScroll: true,
    groupCells: false,
    initialIndex: middleIndex,
  });

  const contentFlickity = new Flickity(contentCarousel, {
    wrapAround: false,
    pageDots: false,
    prevNextButtons: true,
    cellAlign: 'center',
    contain: true,
    draggable: false,
    fade: true, // Smooth transition between content
    initialIndex: middleIndex,
  });

  // Sync carousels and show only active content
  yearsFlickity.on('change', function (index) {
    // Update content carousel
    contentFlickity.select(index, false, true);

    // Update active states
    updateActiveStates(timelineBlock, index);

    // Show only active content slide
    showActiveContentSlide(timelineBlock, index);
  });

  // Also sync when content carousel changes (via navigation buttons)
  contentFlickity.on('change', function (index) {
    // Update years carousel
    yearsFlickity.select(index, false, true);

    // Update active states
    updateActiveStates(timelineBlock, index);

    // Show only active content slide
    showActiveContentSlide(timelineBlock, index);
  });

  // Handle direct clicks on year items
  const yearItems = timelineBlock.querySelectorAll('.year-item');
  yearItems.forEach((yearItem, index) => {
    yearItem.addEventListener('click', function () {
      yearsFlickity.select(index);
    });
  });

  // Set initial active state using the calculated middle index
  // Use setTimeout to ensure Flickity is fully initialized
  setTimeout(() => {
    updateActiveStates(timelineBlock, middleIndex);
    showActiveContentSlide(timelineBlock, middleIndex);
    
    // Force Flickity to recalculate dimensions
    contentFlickity.resize();
    yearsFlickity.resize();
  }, 100);
  
  // Additional resize after a longer delay to ensure proper height calculation
  setTimeout(() => {
    contentFlickity.resize();
  }, 300);
}

function updateActiveStates(timelineBlock, activeIndex) {
  // Remove all active states
  const allYearItems = timelineBlock.querySelectorAll('.year-item');
  allYearItems.forEach(item => item.classList.remove('is-active'));

  // Add active state to current item
  const activeYearItem = timelineBlock.querySelector(
    `.timeline-year-cell[data-index="${activeIndex}"] .year-item`
  );
  if (activeYearItem) {
    activeYearItem.classList.add('is-active');
  }
}

function showActiveContentSlide(timelineBlock, activeIndex) {
  // Remove is-selected class from all content slides
  const allContentSlides = timelineBlock.querySelectorAll('.timeline-content-cell');
  allContentSlides.forEach(slide => slide.classList.remove('is-selected'));

  // Add is-selected class to active slide
  const activeSlide = timelineBlock.querySelector(
    `.timeline-content-cell[data-index="${activeIndex}"]`
  );
  if (activeSlide) {
    activeSlide.classList.add('is-selected');
  }
}
