import Swiper from 'swiper';
import 'swiper/swiper-bundle.css';

console.log('carousel.js loaded');
const gutenbergCarousel = () => {
  const carousels = document.querySelectorAll('.gutenberg-swiper');

  carousels.forEach((carousel, index) => {
    // Define seletores únicos dentro de cada carrossel
    const pagination = carousel.querySelector('.swiper-pagination');
    const next = carousel.querySelector('.swiper-button-next');
    const prev = carousel.querySelector('.swiper-button-prev');
    const scrollbar = carousel.querySelector('.swiper-scrollbar');

    new Swiper(carousel, {
      direction: 'horizontal',
      loop: true,
      slidesPerView: 4,
      spaceBetween: 10,
      pagination: {
        el: pagination,
        clickable: true,
      },
      navigation: {
        nextEl: next,
        prevEl: prev,
      },
      scrollbar: {
        el: scrollbar,
      },
    });
  });
};

document.addEventListener('DOMContentLoaded', gutenbergCarousel);
