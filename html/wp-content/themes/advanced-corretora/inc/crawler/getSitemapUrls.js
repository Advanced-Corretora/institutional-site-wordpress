import https from 'https';
import xml2js from 'xml2js';

const sitemapUrl = 'https://blog.advancedcorretora.com.br/sitemap.xml';

https
  .get(sitemapUrl, res => {
    let data = '';

    res.on('data', chunk => {
      data += chunk;
    });

    res.on('end', async () => {
      try {
        const parser = new xml2js.Parser();
        const result = await parser.parseStringPromise(data);

        // Lida com casos de sitemap index (vários sitemaps dentro de um)
        const sitemaps = result.sitemapindex?.sitemap || [];
        const urls = result.urlset?.url || [];

        if (sitemaps.length > 0) {
          console.log('O sitemap principal contém outros sitemaps:\n');
          for (const sm of sitemaps) {
            console.log(sm.loc[0]);
          }
        } else {
          console.log('URLs encontradas no sitemap:\n');
          for (const u of urls) {
            console.log(u.loc[0]);
          }
        }
      } catch (err) {
        console.error('Erro ao processar o XML:', err);
      }
    });
  })
  .on('error', err => {
    console.error('Erro ao acessar o sitemap:', err.message);
  });
