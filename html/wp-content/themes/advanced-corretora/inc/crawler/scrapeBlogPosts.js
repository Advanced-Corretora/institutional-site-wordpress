import fs from 'fs';
import path from 'path';
import puppeteer from 'puppeteer';

// Função para extrair URL da imagem do style background-image
function extractImageUrl(styleString) {
  if (!styleString) return null;
  const match = styleString.match(/url\(['"]?([^'"]+)['"]?\)/);
  return match ? match[1] : null;
}

// Função para limpar e formatar o conteúdo HTML
function cleanContent(html) {
  if (!html) return '';
  // Remove scripts e styles
  return html
    .replace(/<script[^>]*>[\s\S]*?<\/script>/gi, '')
    .replace(/<style[^>]*>[\s\S]*?<\/style>/gi, '')
    .trim();
}

// Função para converter data brasileira para formato MySQL
function parsePortugueseDate(dateText) {
  if (!dateText) return new Date().toISOString().replace('T', ' ').substring(0, 19);
  
  // Remover "Por Redação - " do início se existir
  const cleanDate = dateText.replace(/^Por\s+.*?\s*-\s*/, '').trim();
  
  // Mapeamento de meses em português
  const monthMap = {
    'janeiro': '01', 'jan': '01',
    'fevereiro': '02', 'fev': '02',
    'março': '03', 'mar': '03',
    'abril': '04', 'abr': '04',
    'maio': '05', 'mai': '05',
    'junho': '06', 'jun': '06',
    'julho': '07', 'jul': '07',
    'agosto': '08', 'ago': '08',
    'setembro': '09', 'set': '09',
    'outubro': '10', 'out': '10',
    'novembro': '11', 'nov': '11',
    'dezembro': '12', 'dez': '12'
  };
  
  try {
    // Padrão: "6 de Março de 2025" ou "6 de mar de 2025"
    const match = cleanDate.match(/(\d{1,2})\s+de\s+(\w+)\s+de\s+(\d{4})/i);
    
    if (match) {
      const day = match[1].padStart(2, '0');
      const monthName = match[2].toLowerCase();
      const year = match[3];
      const month = monthMap[monthName] || '01';
      
      // Retornar no formato MySQL: YYYY-MM-DD HH:MM:SS
      return `${year}-${month}-${day} 10:00:00`;
    }
    
    // Se não conseguir fazer parse, usar data atual
    return new Date().toISOString().replace('T', ' ').substring(0, 19);
    
  } catch (error) {
    console.warn(`Erro ao converter data: ${dateText}`, error);
    return new Date().toISOString().replace('T', ' ').substring(0, 19);
  }
}

// Função para fazer scraping de uma URL
async function scrapePage(page, url) {
  try {
    console.log(`🔍 Processando: ${url}`);
    
    await page.goto(url, { 
      waitUntil: 'networkidle2',
      timeout: 30000 
    });

    // Extrair dados da página
    const pageData = await page.evaluate(() => {
      // Título
      const titleElement = document.querySelector('#hs_cos_wrapper_name');
      const title = titleElement ? titleElement.textContent.trim() : '';

      // Categoria
      const categoryElement = document.querySelector('.categories a');
      const category = categoryElement ? categoryElement.textContent.trim() : '';
      const categoryUrl = categoryElement ? categoryElement.href : '';

      // Imagem destacada
      const bannerElement = document.querySelector('.banner');
      const bannerStyle = bannerElement ? bannerElement.getAttribute('style') : '';
      
      // Conteúdo
      const contentElement = document.querySelector('#hs_cos_wrapper_post_body');
      const content = contentElement ? contentElement.innerHTML : '';

      // Data de publicação
      const dateElement = document.querySelector('.date-author');
      const dateText = dateElement ? dateElement.textContent.trim() : '';

      return {
        title,
        category,
        categoryUrl,
        bannerStyle,
        content,
        dateText
      };
    });

    // Processar imagem destacada
    const featuredImage = extractImageUrl(pageData.bannerStyle);

    // Limpar conteúdo
    const cleanedContent = cleanContent(pageData.content);

    // Processar data de publicação
    const publishedDate = parsePortugueseDate(pageData.dateText);

    return {
      url,
      title: pageData.title,
      category: pageData.category,
      categoryUrl: pageData.categoryUrl,
      featuredImage,
      content: cleanedContent,
      publishedDate,
      originalDateText: pageData.dateText,
      status: 'success',
      scrapedAt: new Date().toISOString()
    };

  } catch (error) {
    console.error(`❌ Erro ao processar ${url}:`, error.message);
    return {
      url,
      title: '',
      category: '',
      categoryUrl: '',
      featuredImage: null,
      content: '',
      status: 'error',
      error: error.message,
      scrapedAt: new Date().toISOString()
    };
  }
}

// Função principal
async function main() {
  console.log('🚀 Iniciando scraping do blog...');
  
  // Ler URLs do arquivo JSON
  const urlsFile = path.join(process.cwd(), 'urls.json');
  const urlsData = JSON.parse(fs.readFileSync(urlsFile, 'utf8'));
  const urls = urlsData.urls;

  console.log(`📋 Total de URLs para processar: ${urls.length}`);

  // Configurar Puppeteer
  const browser = await puppeteer.launch({
    headless: 'new',
    args: [
      '--no-sandbox',
      '--disable-setuid-sandbox',
      '--disable-dev-shm-usage',
      '--disable-accelerated-2d-canvas',
      '--no-first-run',
      '--no-zygote',
      '--disable-gpu'
    ]
  });

  const page = await browser.newPage();
  
  // Configurar user agent e viewport
  await page.setUserAgent('Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.124 Safari/537.36');
  await page.setViewport({ width: 1920, height: 1080 });

  const results = [];
  let processed = 0;
  let errors = 0;

  // Processar URLs em lotes para evitar sobrecarga
  const batchSize = 5;
  for (let i = 0; i < urls.length; i += batchSize) {
    const batch = urls.slice(i, i + batchSize);
    
    console.log(`\n📦 Processando lote ${Math.floor(i/batchSize) + 1}/${Math.ceil(urls.length/batchSize)}`);
    
    for (const url of batch) {
      const result = await scrapePage(page, url);
      results.push(result);
      
      processed++;
      if (result.status === 'error') errors++;
      
      console.log(`✅ Progresso: ${processed}/${urls.length} (${errors} erros)`);
      
      // Pequena pausa entre requests
      await new Promise(resolve => setTimeout(resolve, 1000));
    }
    
    // Pausa maior entre lotes
    if (i + batchSize < urls.length) {
      console.log('⏸️  Pausa entre lotes...');
      await new Promise(resolve => setTimeout(resolve, 3000));
    }
  }

  await browser.close();

  // Preparar dados para WordPress
  const wordpressData = {
    meta: {
      totalPosts: results.length,
      successfulScrapes: results.filter(r => r.status === 'success').length,
      errors: results.filter(r => r.status === 'error').length,
      scrapedAt: new Date().toISOString(),
      source: 'Advanced Corretora Blog'
    },
    posts: results.map(post => ({
      // Dados básicos do post
      title: post.title,
      content: post.content,
      excerpt: '', // Será gerado pelo WordPress
      status: 'draft', // Importar como rascunho
      
      // Data de publicação
      publishedDate: post.publishedDate,
      originalDateText: post.originalDateText,
      
      // Taxonomias
      categories: post.category ? [post.category] : [],
      tags: [],
      
      // Mídia
      featuredImage: post.featuredImage,
      
      // Meta dados
      meta: {
        originalUrl: post.url,
        originalCategory: post.category,
        originalCategoryUrl: post.categoryUrl,
        scrapedAt: post.scrapedAt,
        scrapeStatus: post.status
      },
      
      // Dados para importação
      import: {
        postType: 'post',
        postStatus: 'draft',
        commentStatus: 'open',
        pingStatus: 'open'
      }
    }))
  };

  // Salvar resultados
  const outputFile = path.join(process.cwd(), 'blog-posts-scraped.json');
  fs.writeFileSync(outputFile, JSON.stringify(wordpressData, null, 2), 'utf8');

  // Salvar também em formato XML para WordPress
  const xmlContent = generateWordPressXML(wordpressData);
  const xmlFile = path.join(process.cwd(), 'blog-posts-wordpress.xml');
  fs.writeFileSync(xmlFile, xmlContent, 'utf8');

  // Relatório final
  console.log('\n🎉 Scraping concluído!');
  console.log(`📊 Estatísticas:`);
  console.log(`   • Total processado: ${processed}`);
  console.log(`   • Sucessos: ${wordpressData.meta.successfulScrapes}`);
  console.log(`   • Erros: ${wordpressData.meta.errors}`);
  console.log(`📁 Arquivos gerados:`);
  console.log(`   • JSON: ${outputFile}`);
  console.log(`   • XML: ${xmlFile}`);
}

// Função para gerar XML compatível com WordPress
function generateWordPressXML(data) {
  const posts = data.posts.filter(post => post.title && post.content);
  
  let xml = `<?xml version="1.0" encoding="UTF-8" ?>
<rss version="2.0"
  xmlns:excerpt="http://wordpress.org/export/1.2/excerpt/"
  xmlns:content="http://purl.org/rss/1.0/modules/content/"
  xmlns:wfw="http://wellformedweb.org/CommentAPI/"
  xmlns:dc="http://purl.org/dc/elements/1.1/"
  xmlns:wp="http://wordpress.org/export/1.2/">

<channel>
  <title>Advanced Corretora Blog Import</title>
  <link>https://blog.advancedcorretora.com.br</link>
  <description>Imported blog posts from Advanced Corretora</description>
  <pubDate>${new Date().toUTCString()}</pubDate>
  <language>pt-BR</language>
  <wp:wxr_version>1.2</wp:wxr_version>
  <wp:base_site_url>https://blog.advancedcorretora.com.br</wp:base_site_url>
  <wp:base_blog_url>https://blog.advancedcorretora.com.br</wp:base_blog_url>

`;

  posts.forEach((post, index) => {
    const postId = index + 1;
    const slug = post.title.toLowerCase()
      .replace(/[^a-z0-9\s-]/g, '')
      .replace(/\s+/g, '-')
      .substring(0, 50);

    // Usar data de publicação original ou data atual como fallback
    const postDate = post.publishedDate || new Date().toISOString().replace('T', ' ').substring(0, 19);
    const postDateObj = new Date(postDate.replace(' ', 'T'));

    xml += `  <item>
    <title><![CDATA[${post.title}]]></title>
    <link>${post.meta.originalUrl}</link>
    <pubDate>${postDateObj.toUTCString()}</pubDate>
    <dc:creator><![CDATA[admin]]></dc:creator>
    <guid isPermaLink="false">${post.meta.originalUrl}</guid>
    <description></description>
    <content:encoded><![CDATA[${post.content}]]></content:encoded>
    <excerpt:encoded><![CDATA[]]></excerpt:encoded>
    <wp:post_id>${postId}</wp:post_id>
    <wp:post_date>${postDate}</wp:post_date>
    <wp:post_date_gmt>${postDate}</wp:post_date_gmt>
    <wp:comment_status>open</wp:comment_status>
    <wp:ping_status>open</wp:ping_status>
    <wp:post_name>${slug}</wp:post_name>
    <wp:status>draft</wp:status>
    <wp:post_parent>0</wp:post_parent>
    <wp:post_type>post</wp:post_type>
    <wp:post_password></wp:post_password>
    <wp:is_sticky>0</wp:is_sticky>`;

    // Adicionar categoria se existir
    if (post.categories.length > 0) {
      xml += `
    <category domain="category" nicename="${post.categories[0].toLowerCase().replace(/\s+/g, '-')}"><![CDATA[${post.categories[0]}]]></category>`;
    }

    // Adicionar meta dados customizados
    xml += `
    <wp:postmeta>
      <wp:meta_key>_original_url</wp:meta_key>
      <wp:meta_value><![CDATA[${post.meta.originalUrl}]]></wp:meta_value>
    </wp:postmeta>
    <wp:postmeta>
      <wp:meta_key>_scraped_at</wp:meta_key>
      <wp:meta_value><![CDATA[${post.meta.scrapedAt}]]></wp:meta_value>
    </wp:postmeta>`;

    if (post.featuredImage) {
      xml += `
    <wp:postmeta>
      <wp:meta_key>_featured_image_url</wp:meta_key>
      <wp:meta_value><![CDATA[${post.featuredImage}]]></wp:meta_value>
    </wp:postmeta>`;
    }

    xml += `
  </item>
`;
  });

  xml += `</channel>
</rss>`;

  return xml;
}

// Executar script
main().catch(console.error);
