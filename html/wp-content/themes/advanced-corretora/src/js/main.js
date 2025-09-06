import '../sass/style.scss';

// Main entry point for JavaScript
import './modules/menu';
import './modules/form';

// HMR Support
if (import.meta.hot) {
  import.meta.hot.accept();
}
