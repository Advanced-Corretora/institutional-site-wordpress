import fs from 'fs';
import puppeteer from 'puppeteer';

// Script simples para debug de conteúdo
async function debugContent() {
  console.log('🔍 Debug de conteúdo - Uma URL');
  
  const testUrl = 'https://blog.advancedcorretora.com.br/moeda-estrangeira';
  console.log(`URL: ${testUrl}`);

  const browser = await puppeteer.launch({
    headless: 'new',
    args: ['--no-sandbox', '--disable-setuid-sandbox']
  });

  const page = await browser.newPage();
  await page.setUserAgent('Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36');

  try {
    await page.goto(testUrl, { waitUntil: 'networkidle2', timeout: 30000 });

    const result = await page.evaluate(() => {
      const titleElement = document.querySelector('#hs_cos_wrapper_name');
      const categoryElement = document.querySelector('.categories a');
      const bannerElement = document.querySelector('.banner');
      const contentElement = document.querySelector('#hs_cos_wrapper_post_body');
      const dateElement = document.querySelector('.date-author');
      
      return {
        title: titleElement ? titleElement.textContent.trim() : '',
        category: categoryElement ? categoryElement.textContent.trim() : '',
        banner: bannerElement ? bannerElement.getAttribute('style') : '',
        content: contentElement ? contentElement.innerHTML : '',
        dateText: dateElement ? dateElement.textContent.trim() : '',
        contentExists: !!contentElement,
        allElements: {
          titleExists: !!titleElement,
          categoryExists: !!categoryElement,
          bannerExists: !!bannerElement,
          contentExists: !!contentElement,
          dateExists: !!dateElement
        }
      };
    });

    console.log('\n✅ Resultados:');
    console.log(`Título: ${result.title}`);
    console.log(`Categoria: ${result.category}`);
    console.log(`Data encontrada: ${result.allElements.dateExists}`);
    console.log(`Data original: ${result.dateText}`);
    console.log(`Banner encontrado: ${result.allElements.bannerExists}`);
    console.log(`Conteúdo encontrado: ${result.allElements.contentExists}`);
    console.log(`Tamanho do conteúdo: ${result.content.length} caracteres`);

    if (result.content.length > 0) {
      console.log('\n📄 Primeiros 500 caracteres do conteúdo:');
      console.log(result.content.substring(0, 500));
      console.log('\n...');
      
      // Salvar conteúdo completo em arquivo
      const debugFile = 'debug-content-full.html';
      fs.writeFileSync(debugFile, result.content, 'utf8');
      console.log(`\n💾 Conteúdo completo salvo em: ${debugFile}`);
      
      // Salvar dados estruturados
      const debugData = {
        url: testUrl,
        title: result.title,
        category: result.category,
        banner: result.banner,
        content: result.content,
        contentLength: result.content.length,
        extractedAt: new Date().toISOString()
      };
      
      fs.writeFileSync('debug-content-data.json', JSON.stringify(debugData, null, 2), 'utf8');
      console.log(`📊 Dados estruturados salvos em: debug-content-data.json`);
    } else {
      console.log('\n❌ Nenhum conteúdo foi extraído!');
    }

  } catch (error) {
    console.error('❌ Erro:', error.message);
  }

  await browser.close();
}

debugContent().catch(console.error);
