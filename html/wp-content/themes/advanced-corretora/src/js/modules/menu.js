class MobileMenu {
  constructor() {
    this.menuItems = document.querySelectorAll('.menu-item-has-children > a');
    this.hamburgerButton = document.querySelector('.hamburger-menu');
    this.menuContainer = document.querySelector('.menu-container');
    this.closeButton = document.querySelector('.close-icon');
    this.body = document.body;
    this.init();
  }

  init() {
    // Inicializa o menu mobile se o botão hambúrguer existir
    if (this.hamburgerButton) {
      this.setupHamburgerMenu();
    }

    // Inicializa os itens do menu com submenus
    if (this.menuItems.length > 0) {
      this.setupMenuItems();
    }
  }

  setupHamburgerMenu() {
    this.hamburgerButton.addEventListener('click', () => {
      const isExpanded = this.hamburgerButton.getAttribute('aria-expanded') === 'true';
      this.toggleMenu(!isExpanded);
    });

    // Fecha o menu ao clicar em um link no mobile
    const menuLinks = document.querySelectorAll('.menu a');
    menuLinks.forEach(link => {
      link.addEventListener('click', e => {
        // Se for um link que não tem submenu, fecha o menu
        if (!link.parentElement.classList.contains('menu-item-has-children')) {
          this.toggleMenu(false);
        }
      });
    });

    // Fecha o menu ao clicar no botão de fechar
    if (this.closeButton) {
      this.closeButton.addEventListener('click', () => {
        this.toggleMenu(false);
      });
    }
  }

  setupMenuItems() {
    this.menuItems.forEach(menuItem => {
      // Adiciona um botão de toggle para os itens com submenu no mobile
      if (window.innerWidth <= 1024) {
        menuItem.addEventListener('click', e => {
          // Só previne o comportamento padrão se for um item com submenu
          if (menuItem.parentElement.classList.contains('menu-item-has-children')) {
            e.preventDefault();
            this.toggleSubmenu(menuItem.parentElement);
          }
        });
      } else {
        // Comportamento original para desktop
        menuItem.addEventListener('click', this.handleMenuItemClick.bind(this));
      }
    });

    // Adiciona um listener para redimensionamento da janela
    window.addEventListener('resize', this.handleResize.bind(this));
  }

  toggleMenu(show) {
    this.hamburgerButton.setAttribute('aria-expanded', show);

    if (show) {
      this.menuContainer.classList.add('active');
      this.body.style.overflow = 'hidden'; // Impede o scroll do body
    } else {
      this.menuContainer.classList.remove('active');
      this.body.style.overflow = ''; // Restaura o scroll do body

      // Fecha todos os submenus abertos
      document.querySelectorAll('.menu-item-has-children').forEach(item => {
        item.classList.remove('active');
      });
    }
  }

  toggleSubmenu(parentItem) {
    const isActive = parentItem.classList.contains('active');

    // Fecha outros submenus abertos
    document.querySelectorAll('.menu-item-has-children').forEach(item => {
      if (item !== parentItem) {
        item.classList.remove('active');
      }
    });

    // Alterna o estado do submenu clicado
    if (isActive) {
      parentItem.classList.remove('active');
    } else {
      parentItem.classList.add('active');
    }
  }

  handleMenuItemClick(e) {
    // Comportamento original para desktop
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
      // Fecha outros submenus abertos
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

      // Abre o submenu clicado
      parent.classList.remove('closing');
      parent.classList.add('active');
    }
  }

  handleResize() {
    // Se a largura da tena for maior que 1024px, garante que o menu esteja visível
    if (window.innerWidth > 1024) {
      this.menuContainer.classList.remove('active');
      this.hamburgerButton.setAttribute('aria-expanded', 'false');
      this.body.style.overflow = ''; // Restaura o scroll do body
    }
  }
}

// Inicialização
let mobileMenu;
document.addEventListener('DOMContentLoaded', () => {
  mobileMenu = new MobileMenu();
});

// Exporta a instância para uso global, se necessário
window.mobileMenu = mobileMenu;

export default MobileMenu;
