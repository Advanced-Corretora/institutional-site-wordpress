class MobileMenu {
  constructor() {
    this.menuItems = document.querySelectorAll('.menu .list > .menu-item-has-children > a');
    this.hamburgerButton = document.querySelector('.hamburger-menu');
    this.menuContainer = document.querySelector('.menu-container');
    this.closeButton = document.querySelector('.close-icon');
    this.body = document.body;
    this.headerEl = document.querySelector('.header');
    this.overlay = document.querySelector('.submenu-overlay');
    this.closeSubmenuBound = this.closeSubmenu.bind(this);
    this.hoverTimeout = null;

    // Search elements
    this.searchButton = document.querySelector('.deskButtonArea .search-button');
    this.searchOverlay = document.querySelector('.deskButtonArea .search-overlay');
    this.searchInput = document.querySelector('.deskButtonArea .search-input');
    this.searchClose = document.querySelector('.deskButtonArea .search-close');

    // Mobile search elements
    this.mobileSearchInput = document.querySelector('.mobile-search-input');

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

    if (this.searchButton && this.searchOverlay) {
      this.setupSearchFunctionality();
    }

    if (this.mobileSearchInput) {
      this.setupMobileSearchFunctionality();
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
        const parent = menuItem.parentElement;
        const submenu = parent.querySelector('.submenu-area');

        // Add hover to menu item
        menuItem.addEventListener('mouseenter', () => this.handleMenuItemHover(menuItem, true));

        // Add hover to submenu if it exists
        if (submenu) {
          submenu.addEventListener('mouseenter', () => this.handleMenuItemHover(menuItem, true));
        }

        // Handle mouse leave on parent (menu item + submenu)
        parent.addEventListener('mouseleave', e => {
          // Check if mouse is moving to the submenu
          if (submenu && !submenu.contains(e.relatedTarget)) {
            this.handleMenuItemHover(menuItem, false);
          } else if (!submenu) {
            this.handleMenuItemHover(menuItem, false);
          }
        });
      });
    }
  }

  removeDesktopSubmenuEvents() {
    this.menuItems.forEach(menuItem => {
      const parent = menuItem.parentElement;
      const submenu = parent.querySelector('.submenu-area');

      // Remove all event listeners
      menuItem.removeEventListener('mouseenter', () => this.handleMenuItemHover(menuItem, true));

      if (submenu) {
        submenu.removeEventListener('mouseenter', () => this.handleMenuItemHover(menuItem, true));
      }

      parent.removeEventListener('mouseleave', () => this.handleMenuItemHover(menuItem, false));
    });
  }

  toggleMenu(show) {
    this.hamburgerButton.setAttribute('aria-expanded', show);

    if (show) {
      this.menuContainer.classList.remove('closing');
      this.menuContainer.classList.add('active');
      this.body.style.overflow = 'hidden';
      this.updateOverlayState();
    } else {
      // Adiciona classe closing para animar a saída
      this.menuContainer.classList.add('closing');
      this.menuContainer.classList.remove('active');

      // Remove overflow após a animação
      setTimeout(() => {
        this.body.style.overflow = '';
        this.menuContainer.classList.remove('closing');
      }, 300); // 300ms = duração da animação CSS

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
    const mobileMenuOpen =
      this.menuContainer &&
      (this.menuContainer.classList.contains('active') ||
        this.menuContainer.classList.contains('closing'));
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

  handleMenuItemHover(menuItem, show, immediate = false) {
    const parent = menuItem.parentElement;

    // Clear any pending timeouts
    if (this.hoverTimeout) {
      clearTimeout(this.hoverTimeout);
      this.hoverTimeout = null;
    }

    if (show) {
      // Open menu immediately on hover in
      document.querySelectorAll('.menu-item-has-children.active').forEach(item => {
        if (item !== parent) {
          this.closeSubmenu(item);
        }
      });
      parent.classList.remove('closing');
      parent.classList.add('active');
      this.updateOverlayState();
    } else {
      // Add delay before closing menu on hover out
      this.hoverTimeout = setTimeout(
        () => {
          const submenu = parent.querySelector('.submenu-area');
          const isHoveringSubmenu =
            submenu && (submenu.matches(':hover') || submenu.matches(':active'));
          const isHoveringMenuItem = menuItem.matches(':hover') || menuItem.matches(':active');

          if (!isHoveringSubmenu && !isHoveringMenuItem) {
            this.closeSubmenu(parent);
            this.updateOverlayState();
          }
        },
        immediate ? 0 : 200
      ); // 200ms delay before closing
    }
  }

  setupSearchFunctionality() {
    // Open search overlay
    this.searchButton.addEventListener('click', e => {
      e.preventDefault();
      this.toggleSearch(true);
    });

    // Close search overlay
    if (this.searchClose) {
      this.searchClose.addEventListener('click', e => {
        e.preventDefault();
        this.toggleSearch(false);
      });
    }

    // Close search on Escape key
    document.addEventListener('keydown', e => {
      if (e.key === 'Escape' && this.searchOverlay.classList.contains('active')) {
        this.toggleSearch(false);
      }
    });

    // Handle search form submission
    if (this.searchInput) {
      this.searchInput.addEventListener('keydown', e => {
        if (e.key === 'Enter') {
          e.preventDefault();
          this.performSearch();
        }
      });
    }
  }

  toggleSearch(show) {
    if (show) {
      // Hide menu container immediately
      this.menuContainer.style.display = 'none';

      // Show search overlay with fade in
      this.searchOverlay.style.display = 'flex';
      setTimeout(() => {
        this.searchOverlay.classList.add('active');
      }, 10);

      // Focus input after animation
      setTimeout(() => {
        if (this.searchInput) {
          this.searchInput.focus();
        }
      }, 100);
    } else {
      // Fade out search overlay
      this.searchOverlay.classList.remove('active');

      // Show menu container after search fade out
      setTimeout(() => {
        this.searchOverlay.style.display = 'none';
        this.menuContainer.style.display = '';
      }, 100);

      if (this.searchInput) {
        this.searchInput.value = '';
      }
    }
  }

  performSearch(searchType = 'default') {
    const query = this.searchInput.value.trim();
    if (query) {
      // Diferentes tipos de busca
      let searchUrl;
      switch(searchType) {
        case 'posts':
          searchUrl = `${window.location.origin}/?s=${encodeURIComponent(query)}&search_type=posts`;
          break;
        case 'pages':
          searchUrl = `${window.location.origin}/?s=${encodeURIComponent(query)}&search_type=pages`;
          break;
        case 'products':
          searchUrl = `${window.location.origin}/?s=${encodeURIComponent(query)}&search_type=products`;
          break;
        default:
          searchUrl = `${window.location.origin}/?s=${encodeURIComponent(query)}`;
      }
      window.location.href = searchUrl;
    }
  }

  setupMobileSearchFunctionality() {
    // Handle mobile search form submission
    this.mobileSearchInput.addEventListener('keydown', e => {
      if (e.key === 'Enter') {
        e.preventDefault();
        this.performMobileSearch();
      }
    });
  }

  performMobileSearch() {
    const query = this.mobileSearchInput.value.trim();
    if (query) {
      // Redirect to WordPress search results
      window.location.href = `${window.location.origin}/?s=${encodeURIComponent(query)}`;
    }
  }
}

let mobileMenu;
document.addEventListener('DOMContentLoaded', () => {
  mobileMenu = new MobileMenu();
});
window.mobileMenu = mobileMenu;

export default MobileMenu;
