# Sistema de Plugins Obrigatórios e Recomendados

Este tema implementa um sistema para recomendar e/ou exigir plugins específicos para funcionamento correto.

## Como Funciona

O sistema utiliza a biblioteca **TGM Plugin Activation** para:

1. **Mostrar avisos** quando plugins obrigatórios não estão instalados/ativos
2. **Criar uma página de instalação** de plugins no admin do WordPress
3. **Verificar dependências** e mostrar alertas de funcionalidade limitada
4. **Facilitar a instalação** de plugins diretamente do admin

## Arquivos Envolvidos

- `inc/required-plugins.php` - Configuração principal dos plugins
- `inc/tgm-plugin-activation/class-tgm-plugin-activation.php` - Biblioteca TGM
- `inc/plugin-config-example.php` - Exemplos de configuração

## Plugins Atualmente Configurados

### Obrigatórios
- **Advanced Custom Fields** - Para campos personalizados básicos

### Recomendados
- **Yoast SEO** - Otimização para motores de busca
- **Contact Form 7** - Formulários de contato

## Como Personalizar

### 1. Adicionar Novo Plugin

Edite o arquivo `inc/required-plugins.php` e adicione no array `$plugins`:

```php
array(
    'name'      => 'Nome do Plugin',
    'slug'      => 'slug-do-plugin',
    'required'  => true, // true = obrigatório, false = recomendado
    'version'   => '1.0.0', // versão mínima (opcional)
),
```

### 2. Plugin Personalizado (ZIP)

```php
array(
    'name'     => 'Meu Plugin',
    'slug'     => 'meu-plugin',
    'source'   => get_template_directory() . '/plugins/meu-plugin.zip',
    'required' => true,
),
```

### 3. Plugin de URL Externa

```php
array(
    'name'         => 'Plugin Externo',
    'slug'         => 'plugin-externo',
    'source'       => 'https://exemplo.com/plugin.zip',
    'required'     => true,
    'external_url' => 'https://exemplo.com/plugin-info',
),
```

## Funcionalidades Implementadas

### 1. Avisos Administrativos
- Avisos na área admin quando plugins obrigatórios estão inativos
- Avisos de funcionalidade limitada quando dependências não estão disponíveis
- Links diretos para instalação/ativação

### 2. Página de Instalação
- Acessível em **Aparência > Instalar Plugins**
- Interface amigável para instalar/ativar múltiplos plugins
- Suporte a plugins do repositório WordPress.org e externos

### 3. Verificações de Dependência
- Verifica se Carbon Fields está ativo (`class_exists('Carbon_Fields\\Container')`)
- Verifica se ACF está ativo (`function_exists('get_field')`)
- Mostra avisos específicos para cada dependência faltante

## Configurações Avançadas

### Forçar Ativação/Desativação
```php
array(
    'name'               => 'Plugin Crítico',
    'slug'               => 'plugin-critico',
    'required'           => true,
    'force_activation'   => true,  // Força ativação
    'force_deactivation' => true,  // Desativa quando tema muda
),
```

### Personalizar Mensagens
As mensagens podem ser personalizadas no array `$config['strings']` em `required-plugins.php`.

### Desabilitar Avisos
```php
$config = array(
    'has_notices'  => false, // Remove todos os avisos
    'dismissable'  => false, // Impede dispensar avisos
);
```

## Testando a Implementação

1. **Desative** um plugin obrigatório (ex: Carbon Fields)
2. **Acesse** o admin do WordPress
3. **Verifique** se aparecem os avisos
4. **Clique** no link "Instalar/Ativar Plugins"
5. **Teste** a instalação através da interface

## Manutenção

- **Atualize** a lista de plugins conforme necessário
- **Teste** sempre após adicionar novos plugins
- **Verifique** se as versões mínimas estão corretas
- **Mantenha** a biblioteca TGM atualizada

## Suporte

Para mais informações sobre a biblioteca TGM Plugin Activation:
- [Documentação Oficial](http://tgmpluginactivation.com/)
- [GitHub](https://github.com/TGMPA/TGM-Plugin-Activation)
