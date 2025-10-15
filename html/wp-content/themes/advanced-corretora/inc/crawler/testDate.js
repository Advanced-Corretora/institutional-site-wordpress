// Função para converter data brasileira para formato MySQL
function parsePortugueseDate(dateText) {
  if (!dateText) return new Date().toISOString();
  
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

// Testes de conversão de data
function testDateConversion() {
  console.log('🧪 Testando conversão de datas brasileiras...\n');
  
  const testDates = [
    'Por Redação - 6 de Março de 2025',
    'Por Redação - 15 de janeiro de 2024',
    'Por Redação - 1 de dezembro de 2023',
    'Por Redação - 25 de jul de 2024',
    '10 de setembro de 2024',
    'Por Advanced Team - 3 de fevereiro de 2025',
    '', // Data vazia
    'Data inválida'
  ];
  
  testDates.forEach((dateText, index) => {
    const converted = parsePortugueseDate(dateText);
    console.log(`${index + 1}. Original: "${dateText}"`);
    console.log(`   Convertido: ${converted}`);
    console.log('');
  });
  
  console.log('✅ Teste de conversão concluído!');
}

// Executar teste
testDateConversion();
