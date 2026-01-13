import '../sass/style.scss';

// Main entry point for JavaScript
import './modules/menu';
import './modules/form';
import './modules/backToTop';

// HMR Support
if (import.meta.hot) {
  import.meta.hot.accept();
}
