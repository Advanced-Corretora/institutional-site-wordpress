import { defineConfig } from 'vite';
import path from 'path';
import { pathToFileURL } from 'url';
import fullReload from 'vite-plugin-full-reload';
import liveReload from 'vite-plugin-live-reload';

// Configuração de caminhos
const abstractsPath = pathToFileURL(
  path.resolve(__dirname, 'src/sass/abstracts/abstracts')
).href;

export default defineConfig({
  root: 'src',
  base: '/',
  
  // Configurações do servidor de desenvolvimento
  server: {
    host: 'localhost',
    port: 5173,
    strictPort: true,
    https: false,
    cors: true,
    headers: {
      'Access-Control-Allow-Origin': '*',
      'Access-Control-Allow-Methods': 'GET, OPTIONS',
      'Access-Control-Allow-Headers': 'X-Requested-With, content-type, Authorization'
    },
    proxy: {
      '^/(?!dist|@vite|resources|src|wp-content|assets).*': {
        target: 'http://localhost:8443',
        changeOrigin: true,
        secure: false,
      },
    },
  },

  // Plugins do Vite
  plugins: [
    // Recarrega a página quando arquivos PHP são alterados
    fullReload(['../**/*.php'], { delay: 200 }),
    // Recarrega a página quando arquivos HTML são alterados
    liveReload(['../**/*.php', '../**/*.html']),
  ],

  // Configurações de build específicas para desenvolvimento
  build: {
    outDir: '../dist',
    emptyOutDir: true,
    sourcemap: true, // Gera arquivos .map separados
    minify: false, // Desativa minificação para facilitar o debug
    cssCodeSplit: false, // Mantém o CSS em um único arquivo
    target: 'esnext', // Melhora a geração de source maps
    cssTarget: 'chrome61', // Melhora a compatibilidade com source maps
    
    // Configurações do Rollup
    rollupOptions: {
      input: {
        main: path.resolve(__dirname, 'src/js/main.js'),
        carousel: path.resolve(__dirname, 'src/js/modules/carousel.js'),
        style: path.resolve(__dirname, 'src/sass/style.scss'),
        font: path.resolve(__dirname, 'src/sass/base/_fonts.scss'),
      },
      output: {
        assetFileNames: (assetInfo) => {
          // Garante que o arquivo .css.map seja gerado corretamente
          if (assetInfo.name.endsWith('.css')) {
            return 'css/[name][extname]';
          } else if (assetInfo.name.endsWith('.map')) {
            return 'css/[name][extname]'; // Garante que os .map fiquem na pasta css
          }
          return 'assets/[name]-[hash][extname]';
        },
        entryFileNames: 'js/[name].js'
      },
    },
  },

  // Configurações de CSS/SCSS
  css: {
    devSourcemap: true, // Habilita source maps para desenvolvimento
    
    // Configurações do módulo CSS
    modules: {
      generateScopedName: '[local]__[hash:base64:5]',
    },
    
    // Configurações do pré-processador SCSS
    preprocessorOptions: {
      scss: {
        sourceMap: true,
        sourceMapContents: true,
        sourceMapEmbed: true,
        additionalData: `@use "${abstractsPath}" as *;`,
      },
    },
    
    // Configurações adicionais para source maps
    postcss: {
      map: {
        inline: true,
        annotation: true,
      },
    },
    
    // Força a geração de source maps para SCSS
    sourceMap: true,
  },
  
  // Configurações de resolução
  resolve: {
    alias: {
      '@': path.resolve(__dirname, 'src'),
    },
  },
});
