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
    console.log(`🔍 Testando correção: ${url}`);
    
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
      publishedDate: '',
      originalDateText: '',
      status: 'error',
      error: error.message,
      scrapedAt: new Date().toISOString()
    };
  }
}

// Função principal para teste
async function testFix() {
  console.log('🔧 Testando correção da data...');
  
  // URL de teste
  const testUrl = 'https://blog.advancedcorretora.com.br/moeda-estrangeira';

  // Configurar Puppeteer
  const browser = await puppeteer.launch({
    headless: 'new',
    args: ['--no-sandbox', '--disable-setuid-sandbox']
  });

  const page = await browser.newPage();
  await page.setUserAgent('Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36');
  await page.setViewport({ width: 1920, height: 1080 });

  const result = await scrapePage(page, testUrl);
  
  await browser.close();

  console.log('\n📊 Resultado do teste:');
  console.log(`   Título: ${result.title}`);
  console.log(`   Categoria: ${result.category}`);
  console.log(`   Data original: "${result.originalDateText}"`);
  console.log(`   Data convertida: "${result.publishedDate}"`);
  console.log(`   Status: ${result.status}`);

  // Simular estrutura do WordPress
  const wordpressPost = {
    title: result.title,
    content: result.content,
    publishedDate: result.publishedDate,
    originalDateText: result.originalDateText,
    categories: result.category ? [result.category] : [],
    featuredImage: result.featuredImage,
    meta: {
      originalUrl: result.url,
      scrapedAt: result.scrapedAt
    }
  };

  // Salvar resultado
  fs.writeFileSync('test-fix-result.json', JSON.stringify(wordpressPost, null, 2), 'utf8');
  console.log('\n💾 Resultado salvo em: test-fix-result.json');
  
  // Verificar se a data está sendo passada corretamente
  if (result.publishedDate && result.publishedDate !== result.scrapedAt) {
    console.log('\n✅ CORREÇÃO FUNCIONANDO! Data original sendo usada.');
  } else {
    console.log('\n❌ PROBLEMA: Ainda usando data atual ao invés da original.');
  }
}

// Executar teste
testFix().catch(console.error);
