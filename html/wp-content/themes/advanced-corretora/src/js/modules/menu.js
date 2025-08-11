class MobileMenu {
  constructor() {
    this.menuItems = document.querySelectorAll('.menu-item-has-children > a');
    this.hamburgerButton = document.querySelector('.hamburger-menu');
    this.menuContainer = document.querySelector('.menu-container');
    this.closeButton = document.querySelector('.close-icon');
    this.body = document.body;
    this.handleMenuItemClickBound = this.handleMenuItemClick.bind(this);
    this.init();
  }

  init() {
    if (this.hamburgerButton) {
      this.setupHamburgerMenu();
    }

    if (this.menuItems.length > 0) {
      this.setupMenuItems();
    }
  }

  setupHamburgerMenu() {
    this.hamburgerButton.addEventListener('click', () => {
      const isExpanded = this.hamburgerButton.getAttribute('aria-expanded') === 'true';
      this.toggleMenu(!isExpanded);
    });

    const menuLinks = document.querySelectorAll('.menu a');
    menuLinks.forEach(link => {
      link.addEventListener('click', e => {
        if (!link.parentElement.classList.contains('menu-item-has-children')) {
          this.toggleMenu(false);
        }
      });
    });

    if (this.closeButton) {
      this.closeButton.addEventListener('click', () => {
        this.toggleMenu(false);
      });
    }
  }

  setupMenuItems() {
    // Aplica eventos iniciais
    this.applyDesktopSubmenuEvents();

    // Reaplica ao redimensionar
    window.addEventListener('resize', () => {
      this.removeDesktopSubmenuEvents();
      this.applyDesktopSubmenuEvents();
    });
  }

  applyDesktopSubmenuEvents() {
    if (window.innerWidth > 1024) {
      this.menuItems.forEach(menuItem => {
        menuItem.addEventListener('click', this.handleMenuItemClickBound);
      });
    }
  }

  removeDesktopSubmenuEvents() {
    this.menuItems.forEach(menuItem => {
      menuItem.removeEventListener('click', this.handleMenuItemClickBound);
    });
  }

  toggleMenu(show) {
    this.hamburgerButton.setAttribute('aria-expanded', show);

    if (show) {
      this.menuContainer.classList.add('active');
      this.body.style.overflow = 'hidden';
    } else {
      this.menuContainer.classList.remove('active');
      this.body.style.overflow = '';

      document.querySelectorAll('.menu-item-has-children').forEach(item => {
        item.classList.remove('active');
      });
    }
  }

  handleMenuItemClick(e) {
    e.preventDefault();
    const parent = e.currentTarget.parentElement;

    if (parent.classList.contains('active')) {
      parent.classList.add('closing');
      parent.classList.remove('active');

      const submenu = parent.querySelector('.sub-menu');
      if (submenu) {
        const onAnimationEnd = () => {
          parent.classList.remove('closing');
          submenu.removeEventListener('animationend', onAnimationEnd);
        };
        submenu.addEventListener('animationend', onAnimationEnd);
      } else {
        parent.classList.remove('closing');
      }
    } else {
      document.querySelectorAll('.menu-item-has-children.active').forEach(item => {
        if (item !== parent) {
          item.classList.add('closing');
          item.classList.remove('active');
          const submenu = item.querySelector('.sub-menu');
          if (submenu) {
            const onAnimationEnd = () => {
              item.classList.remove('closing');
              submenu.removeEventListener('animationend', onAnimationEnd);
            };
            submenu.addEventListener('animationend', onAnimationEnd);
          } else {
            item.classList.remove('closing');
          }
        }
      });

      parent.classList.remove('closing');
      parent.classList.add('active');
    }
  }
}

// Inicialização
let mobileMenu;
document.addEventListener('DOMContentLoaded', () => {
  mobileMenu = new MobileMenu();
});
window.mobileMenu = mobileMenu;

export default MobileMenu;
