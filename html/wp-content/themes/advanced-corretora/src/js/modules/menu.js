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
    e.preventDefault();
    const parent = e.currentTarget.parentElement;

    if (parent.classList.contains('active')) {
      // Se já está aberto, começa a fechar
      parent.classList.add('closing');
      parent.classList.remove('active');

      // Aguarda o fim da animação de fechamento para remover 'closing'
      const submenu = parent.querySelector('.sub-menu');
      if (submenu) {
        const onAnimationEnd = () => {
          parent.classList.remove('closing');
          submenu.removeEventListener('animationend', onAnimationEnd);
        };
        submenu.addEventListener('animationend', onAnimationEnd);
      } else {
        // Se não tiver submenu, remove direto
        parent.classList.remove('closing');
      }
    } else {
      // Abrir submenu
      // Fecha outros abertos primeiro
      document.querySelectorAll('.menu-item-has-children.active').forEach(item => {
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
      });

      // Agora abre o atual
      parent.classList.remove('closing');
      parent.classList.add('active');
    }
  }
}

// Self-initialization
document.addEventListener('DOMContentLoaded', () => {
  new MobileMenu();
});

export default MobileMenu;
