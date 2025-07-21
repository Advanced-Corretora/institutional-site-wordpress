class MobileMenu {
  constructor() {
    this.menuItems = document.querySelectorAll('.menu-item-has-children > a');
    this.init();
  }

  init() {
    if (this.menuItems.length === 0) return;
    this.menuItems.forEach(menuItem => {
      menuItem.addEventListener('click', this.handleMenuItemClick.bind(this));
    });
  }

  handleMenuItemClick(e) {
    if (window.innerWidth > 768) return;
    e.preventDefault();
    const parent = e.currentTarget.parentElement;
    parent.classList.toggle('active');
    // Close other open submenus
    document.querySelectorAll('.menu-item-has-children').forEach(item => {
      if (item !== parent && item.classList.contains('active')) {
        item.classList.remove('active');
      }
    });
  }
}

// Self-initialization
document.addEventListener('DOMContentLoaded', () => {
  new MobileMenu();
});

export default MobileMenu;
