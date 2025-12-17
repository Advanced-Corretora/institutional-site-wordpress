# Blog Scraper - Advanced Corretora

Sistema completo para extrair conteúdo do blog da Advanced Corretora e preparar para importação no WordPress.

## 📋 Funcionalidades

- ✅ Extração de URLs do sitemap
- ✅ Scraping de conteúdo das páginas
- ✅ Geração de arquivos JSON e XML para WordPress
- ✅ Extração de: título, categoria, imagem destacada, conteúdo completo

## 🚀 Como usar

### 1. Instalar dependências
```bash
npm install
```

### 2. Extrair URLs do sitemap
```bash
npm run convert
```
Gera: `urls.json` com todas as URLs do blog

### 3. Testar scraping (recomendado)
```bash
npm run test-scrape
```
Testa com apenas 3 URLs para verificar se está funcionando

### 4. Fazer scraping completo
```bash
npm run scrape
```
Processa todas as 221 URLs e gera os arquivos finais

## 📁 Arquivos gerados

### `urls.json`
Lista de URLs extraídas do sitemap
```json
{
  "urls": ["https://blog.advancedcorretora.com.br/..."],
  "total": 221,
  "generated_at": "2025-10-07T21:55:02.872Z"
}
```

### `blog-posts-scraped.json`
Dados completos extraídos (formato estruturado)
```json
{
  "meta": {
    "totalPosts": 221,
    "successfulScrapes": 215,
    "errors": 6
  },
  "posts": [
    {
      "title": "Título do post",
      "content": "Conteúdo HTML completo",
      "categories": ["Categoria"],
      "featuredImage": "https://...",
      "meta": {
        "originalUrl": "https://...",
        "scrapedAt": "2025-10-07T..."
      }
    }
  ]
}
```

### `blog-posts-wordpress.xml`
Arquivo XML compatível com WordPress Importer
- Formato WXR (WordPress eXtended RSS)
- Pronto para importar via Ferramentas > Importar
- Posts importados como rascunho

## 🎯 Dados extraídos

### Título
```html
<h1><span id="hs_cos_wrapper_name">Título do post</span></h1>
```

### Categoria
```html
<div class="categories">
  <a href="...">Nome da Categoria</a>
</div>
```

### Imagem destacada
```html
<div class="banner" style="background-image:url('https://...');">
```

### Conteúdo
```html
<div class="hs_cos_wrapper_post_body">
  <!-- Todo o conteúdo HTML -->
</div>
```

## ⚙️ Configurações

### Puppeteer
- User-Agent: Chrome Windows
- Viewport: 1920x1080
- Timeout: 30 segundos por página
- Processamento em lotes de 5 URLs

### Rate Limiting
- 1 segundo entre páginas
- 3 segundos entre lotes
- Evita sobrecarga do servidor

## 🔧 Troubleshooting

### Erro de timeout
- Aumente o timeout em `scrapeBlogPosts.js`
- Reduza o tamanho do lote (`batchSize`)

### Muitos erros 403/429
- Aumente as pausas entre requests
- Verifique se o site não está bloqueando

### Conteúdo não extraído
- Verifique se os seletores CSS ainda estão corretos
- Teste com `npm run test-scrape` primeiro

## 📊 Estatísticas esperadas

- **Total de URLs**: 221
- **Taxa de sucesso esperada**: ~95%
- **Tempo estimado**: 15-20 minutos
- **Tamanho do arquivo final**: ~50-100MB

## 🔄 Importação no WordPress

1. Acesse **Ferramentas > Importar**
2. Escolha **WordPress**
3. Faça upload do arquivo `blog-posts-wordpress.xml`
4. Configure as opções de importação
5. Execute a importação

### Pós-importação
- Posts são importados como **rascunho**
- Imagens destacadas ficam como meta `_featured_image_url`
- URL original salva em `_original_url`
- Categorias são criadas automaticamente

## 📝 Scripts disponíveis

- `npm run convert` - Converte sitemap para JSON
- `npm run test-scrape` - Testa com 3 URLs
- `npm run scrape` - Scraping completo
- `node getSitemapUrls.js` - Extrai URLs do sitemap
