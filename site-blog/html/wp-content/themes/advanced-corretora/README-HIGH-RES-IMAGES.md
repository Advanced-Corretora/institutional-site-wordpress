# Sistema de Imagens de Alta Resolução

## Visão Geral

Este sistema foi implementado para garantir que em resoluções maiores que 1366px, as imagens mantenham sua qualidade original, evitando a compressão automática do WordPress através do sistema srcset.

## Como Funciona

### 1. Detecção de Resolução
- Um script JavaScript detecta automaticamente a largura da tela do usuário
- A informação é armazenada em um cookie (`screen_width`) válido por 24 horas
- Para telas > 1366px, o sistema desabilita o srcset automaticamente

### 2. Tratamento no Backend (PHP)

#### Funções Implementadas:

**`advanced_corretora_disable_srcset_high_res()`**
- Desabilita srcset para telas > 1366px
- Força o uso da imagem original (tamanho 'full')
- Aplica-se a todas as tags `<img>` geradas pelo WordPress

**`advanced_corretora_disable_responsive_images()`**
- Desabilita completamente o sistema de imagens responsivas do WordPress
- Remove os filtros `wp_calculate_image_srcset` e `wp_calculate_image_sizes`

**`advanced_corretora_force_full_bg_images()`**
- Força imagens de tamanho completo para elementos com classes de background
- Detecta classes que contenham 'bg-' ou 'background'

**`advanced_corretora_get_full_image_url()`**
- Função helper para obter URLs de imagem em alta resolução
- Retorna 'full' para telas > 1366px, 'large' para outras

### 3. Tratamento no Frontend (JavaScript)

#### Funcionalidades:
- **Tags IMG**: Remove srcset e substitui por imagem de maior resolução disponível
- **Background Images**: Detecta e substitui URLs de imagem em estilos inline
- **Regex de Limpeza**: Remove sufixos de redimensionamento (-300x200) das URLs

### 4. Estilos CSS Adicionais

```css
@media (min-width: 1367px) {
    img,
    .wp-block-cover,
    [class*="bg-"],
    [class*="background"] {
        image-rendering: -webkit-optimize-contrast;
        image-rendering: crisp-edges;
    }
}
```

## Como Usar

### Para Imagens Regulares
O sistema funciona automaticamente para todas as imagens geradas pelo WordPress.

### Para Background Images via PHP
```php
// Use a função helper para obter a URL correta
$image_url = advanced_corretora_get_full_image_url($attachment_id);
echo '<div style="background-image: url(' . $image_url . ')"></div>';
```

### Para Forçar Alta Resolução em Classes Específicas
Adicione classes que contenham 'bg-' ou 'background':
```html
<div class="hero-bg-section">...</div>
<div class="background-banner">...</div>
```

### Para Desabilitar Srcset em Imagens Específicas
Use a classe `no-srcset` (funcionalidade já existente):
```html
<img src="image.jpg" class="no-srcset" />
```

## Compatibilidade

- **WordPress**: 5.0+
- **Navegadores**: Todos os navegadores modernos
- **Dispositivos**: Desktop/Laptop com resolução > 1366px
- **Mobile/Tablet**: Mantém comportamento padrão (srcset ativo)

## Benefícios

1. **Qualidade Superior**: Imagens originais em alta resolução
2. **Automático**: Não requer configuração manual
3. **Inteligente**: Só aplica em resoluções que se beneficiam
4. **Performance**: Mantém otimização para dispositivos móveis
5. **Flexível**: Permite controle granular via classes CSS

## Monitoramento

Para verificar se o sistema está funcionando:

1. Abra o DevTools do navegador
2. Vá para Application > Cookies
3. Verifique se existe o cookie `screen_width`
4. Inspecione elementos `<img>` - não devem ter atributo `srcset`
5. Verifique se as URLs das imagens não contêm sufixos de redimensionamento

## Troubleshooting

**Problema**: Imagens ainda aparecem comprimidas
- Verifique se o cookie `screen_width` foi definido
- Limpe o cache do navegador
- Verifique se a resolução é realmente > 1366px

**Problema**: Performance lenta
- Normal para primeira visita (cookie sendo definido)
- Considere implementar cache de imagens no servidor

**Problema**: Background images não melhoram
- Verifique se as classes contêm 'bg-' ou 'background'
- Use a função helper `advanced_corretora_get_full_image_url()`
