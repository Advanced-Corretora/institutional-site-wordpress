import fs from 'fs';
import path from 'path';

// Lê o arquivo atual com URLs linha por linha
const urlsFile = path.join(process.cwd(), 'urls.json');
const content = fs.readFileSync(urlsFile, 'utf8');

// Converte as linhas em array, removendo linhas vazias
const urls = content
  .split('\n')
  .map(line => line.trim())
  .filter(line => line.length > 0);

// Cria o objeto JSON
const jsonData = {
  "urls": urls,
  "total": urls.length,
  "generated_at": new Date().toISOString(),
  "source": "sitemap"
};

// Salva o novo arquivo JSON
fs.writeFileSync(urlsFile, JSON.stringify(jsonData, null, 2), 'utf8');

console.log(`✅ Convertido com sucesso! ${urls.length} URLs encontradas.`);
console.log(`📁 Arquivo salvo: ${urlsFile}`);
