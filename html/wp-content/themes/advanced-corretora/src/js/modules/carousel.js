import Flickity from 'flickity';
import 'flickity/css/flickity.css';

console.log('carousel.js loaded2');
const gutenbergCarousel = () => {
  const carousels = document.querySelectorAll('.gutenberg-flickity');

  carousels.forEach(carousel => {
    new Flickity(carousel, {
      wrapAround: false,
      pageDots: true,
      prevNextButtons: true,
      contain: true,
      cellAlign: 'left',
      // add other options here if needed
    });
  });
};

document.addEventListener('DOMContentLoaded', gutenbergCarousel);
