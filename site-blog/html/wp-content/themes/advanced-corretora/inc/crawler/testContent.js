import puppeteer from 'puppeteer';

// Teste específico para extração de conteúdo
async function testContentExtraction() {
  console.log('🧪 Testando extração de conteúdo...');
  
  // URL de exemplo (primeira do JSON)
  const testUrl = 'https://blog.advancedcorretora.com.br/moeda-estrangeira';
  
  console.log(`🔍 Testando URL: ${testUrl}`);

  const browser = await puppeteer.launch({
    headless: false, // Mostrar browser para debug
    args: ['--no-sandbox', '--disable-setuid-sandbox']
  });

  const page = await browser.newPage();
  await page.setUserAgent('Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36');
  await page.setViewport({ width: 1920, height: 1080 });

  try {
    await page.goto(testUrl, { 
      waitUntil: 'networkidle2',
      timeout: 30000 
    });

    // Aguardar um pouco para garantir que tudo carregou
    await new Promise(resolve => setTimeout(resolve, 3000));

    // Verificar se os elementos existem
    const elementsCheck = await page.evaluate(() => {
      const titleElement = document.querySelector('#hs_cos_wrapper_name');
      const categoryElement = document.querySelector('.categories a');
      const bannerElement = document.querySelector('.banner');
      const contentElement = document.querySelector('#hs_cos_wrapper_post_body');
      
      return {
        titleExists: !!titleElement,
        titleText: titleElement ? titleElement.textContent.trim() : 'NÃO ENCONTRADO',
        categoryExists: !!categoryElement,
        categoryText: categoryElement ? categoryElement.textContent.trim() : 'NÃO ENCONTRADO',
        bannerExists: !!bannerElement,
        bannerStyle: bannerElement ? bannerElement.getAttribute('style') : 'NÃO ENCONTRADO',
        contentExists: !!contentElement,
        contentLength: contentElement ? contentElement.innerHTML.length : 0,
        contentPreview: contentElement ? contentElement.innerHTML.substring(0, 200) + '...' : 'NÃO ENCONTRADO'
      };
    });

    console.log('\n📊 Resultado da verificação:');
    console.log(`   ✅ Título encontrado: ${elementsCheck.titleExists}`);
    console.log(`   📝 Título: ${elementsCheck.titleText}`);
    console.log(`   ✅ Categoria encontrada: ${elementsCheck.categoryExists}`);
    console.log(`   🏷️  Categoria: ${elementsCheck.categoryText}`);
    console.log(`   ✅ Banner encontrado: ${elementsCheck.bannerExists}`);
    console.log(`   🖼️  Banner style: ${elementsCheck.bannerStyle.substring(0, 100)}...`);
    console.log(`   ✅ Conteúdo encontrado: ${elementsCheck.contentExists}`);
    console.log(`   📄 Tamanho do conteúdo: ${elementsCheck.contentLength} caracteres`);
    console.log(`   📖 Preview do conteúdo: ${elementsCheck.contentPreview}`);

    // Se o conteúdo não foi encontrado, vamos investigar
    if (!elementsCheck.contentExists) {
      console.log('\n🔍 Investigando elementos disponíveis...');
      
      const availableElements = await page.evaluate(() => {
        const elements = [];
        
        // Procurar por elementos que contenham "post_body"
        const postBodyElements = document.querySelectorAll('[id*="post_body"], [class*="post_body"]');
        postBodyElements.forEach(el => {
          elements.push({
            type: 'post_body',
            tagName: el.tagName,
            id: el.id,
            className: el.className,
            contentLength: el.innerHTML.length
          });
        });
        
        // Procurar por elementos que contenham "hs_cos_wrapper"
        const hsElements = document.querySelectorAll('[class*="hs_cos_wrapper"]');
        Array.from(hsElements).slice(0, 5).forEach(el => {
          elements.push({
            type: 'hs_cos_wrapper',
            tagName: el.tagName,
            id: el.id,
            className: el.className,
            contentLength: el.innerHTML.length
          });
        });
        
        return elements;
      });
      
      console.log('   Elementos encontrados:');
      availableElements.forEach((el, index) => {
        console.log(`   ${index + 1}. ${el.tagName} - ID: "${el.id}" - Class: "${el.className}" - Conteúdo: ${el.contentLength} chars`);
      });
    }

  } catch (error) {
    console.error('❌ Erro:', error.message);
  }

  await browser.close();
}

// Executar teste
testContentExtraction().catch(console.error);
