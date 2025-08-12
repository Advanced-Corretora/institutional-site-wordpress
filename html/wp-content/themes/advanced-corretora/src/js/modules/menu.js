class MobileMenu {
  constructor() {
    this.menuItems = document.querySelectorAll('.menu-item-has-children > a');
    this.hamburgerButton = document.querySelector('.hamburger-menu');
    this.menuContainer = document.querySelector('.menu-container');
    this.closeButton = document.querySelector('.close-icon');
    this.body = document.body;
    this.headerEl = document.querySelector('.header');
    this.overlay = document.querySelector('.submenu-overlay');
    this.handleMenuItemClickBound = this.handleMenuItemClick.bind(this);
    this.closeSubmenuBound = this.closeSubmenu.bind(this);
    this.init();
  }

  init() {

    if (!this.overlay) {
      this.overlay = document.createElement('div');
      this.overlay.className = 'submenu-overlay';
      (this.headerEl || document.body).appendChild(this.overlay);
    } else {
      if (this.headerEl && this.overlay.parentElement !== this.headerEl) {
        this.headerEl.appendChild(this.overlay);
      }
    }
    
    this.overlay.addEventListener('click', () => {
      document.querySelectorAll('.menu-item-has-children.active').forEach(item => {
        this.closeSubmenu(item);
      });
    });

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
    this.applyDesktopSubmenuEvents();

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
      this.updateOverlayState();
    } else {
      this.menuContainer.classList.remove('active');
      this.body.style.overflow = '';

      document.querySelectorAll('.menu-item-has-children').forEach(item => {
        item.classList.remove('active');
        item.classList.remove('closing');
      });
      this.updateOverlayState();
    }
  }

  updateOverlayState() {
    const anyOpenSubmenu = document.querySelector(
      '.menu-item-has-children.active, .menu-item-has-children.closing'
    );
    const mobileMenuOpen = this.menuContainer && this.menuContainer.classList.contains('active');
    document.body.classList.toggle('submenu-open', !!anyOpenSubmenu || mobileMenuOpen);
  }

  closeSubmenu(item) {
    if (!item || !item.classList.contains('active')) return;
    item.classList.add('closing');
    item.classList.remove('active');

    const submenuArea = item.querySelector('.submenu-area');
    if (submenuArea) {
      const onAnimationEnd = e => {
        if (e.target !== submenuArea) return;
        item.classList.remove('closing');
        submenuArea.removeEventListener('animationend', onAnimationEnd);
        this.updateOverlayState();
      };
      submenuArea.addEventListener('animationend', onAnimationEnd, { once: true });
    } else {
      item.classList.remove('closing');
    }
    this.updateOverlayState();
  }

  handleMenuItemClick(e) {
    e.preventDefault();
    const parent = e.currentTarget.parentElement;

    if (parent.classList.contains('active')) {
      this.closeSubmenu(parent);
    } else {
      document.querySelectorAll('.menu-item-has-children.active').forEach(item => {
        if (item !== parent) {
          this.closeSubmenu(item);
        }
      });

      parent.classList.remove('closing');
      parent.classList.add('active');
      this.updateOverlayState();
    }
  }
}

let mobileMenu;
document.addEventListener('DOMContentLoaded', () => {
  mobileMenu = new MobileMenu();
});
window.mobileMenu = mobileMenu;

export default MobileMenu;
