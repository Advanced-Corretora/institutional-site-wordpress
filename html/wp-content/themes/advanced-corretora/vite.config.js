import { defineConfig } from 'vite';
import path from 'path';

export default defineConfig({
  root: 'src',
  base: '/',
  server: {
    host: 'localhost',
    port: 5173,
    strictPort: true,
    https: false,
    proxy: {
      // Redireciona tudo para o seu WP Docker
      '^/(?!dist|@vite|resources|src).*': {
        target: 'http://localhost:8443',
        changeOrigin: true,
        secure: false,
      },
    },
  },
  build: {
    outDir: '../dist',
    emptyOutDir: true,
    rollupOptions: {
      input: {
        main: path.resolve(__dirname, 'src/js/main.js'),
        style: path.resolve(__dirname, 'src/sass/style.scss'),
      },
      output: {
        assetFileNames: 'css/[name].css',
        entryFileNames: 'js/[name].js',
      },
    },
  },
  css: {
    postcss: {
      plugins: [require('autoprefixer')],
    },
  },
});
