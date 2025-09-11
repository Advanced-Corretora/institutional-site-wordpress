import { defineConfig } from 'vite';
import path from 'path';
import { pathToFileURL } from 'url';
import fullReload from 'vite-plugin-full-reload';
import liveReload from 'vite-plugin-live-reload';

// Configuração de caminhos
const abstractsPath = pathToFileURL(
  path.resolve(__dirname, 'src/sass/abstracts/abstracts')
).href;

// Configuração de ambiente
const isProduction = process.env.NODE_ENV === 'production';

export default defineConfig({
  root: 'src',
  base: '/',
  
  // Configurações do servidor de desenvolvimento
  server: {
    host: 'localhost',
    port: 5173,
    strictPort: true,
    https: false,
    proxy: {
      '^/(?!dist|@vite|resources|src).*': {
        target: 'http://localhost:8443',
        changeOrigin: true,
        secure: false,
      },
    },
  },

  // Plugins
  plugins: [
    fullReload(['../**/*.php']),
    liveReload(['src/sass/**/*.scss'])
  ],

  // Configurações de build
  build: {
    outDir: '../dist',
    emptyOutDir: true,
    sourcemap: false,
    minify: isProduction ? 'esbuild' : false,
    
    // Configurações do Rollup
    rollupOptions: {
      input: {
        main: path.resolve(__dirname, 'src/js/main.js'),
        carousel: path.resolve(__dirname, 'src/js/modules/carousel.js'),
        homeSlider: path.resolve(__dirname, 'src/js/modules/homeSlider.js'),
        timeline: path.resolve(__dirname, 'src/js/modules/timeline.js'),
        style: path.resolve(__dirname, 'src/sass/style.scss'),
        flickityStyle: path.resolve(__dirname, 'src/sass/flickity.scss'),
      },
      output: {
        assetFileNames: 'css/[name].css',
        entryFileNames: 'js/[name].js',
        sourcemap: true, // Garante que os source maps sejam gerados
      },
    },
  },

  // Configurações de CSS/SCSS
  css: {
    // Habilita source maps para desenvolvimento
    devSourcemap: true,
    
    // Configurações do módulo CSS
    modules: {
      generateScopedName: isProduction 
        ? '[hash:base64:5]' 
        : '[local]__[hash:base64:5]',
    },
    
    // Configurações do pré-processador SCSS
    preprocessorOptions: {
      scss: {
        sourceMap: true, // Habilita source maps para SCSS
        additionalData: `@use "${abstractsPath}" as *;`,
      },
    },
    
    // Configurações do PostCSS
    postcss: {
      plugins: [
        require('autoprefixer')
      ],
    },
  },
  
  // Configurações de resolução
  resolve: {
    alias: {
      '@': path.resolve(__dirname, 'src'),
    },
  },
});
