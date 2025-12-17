<?php
/**
 * Exemplo de configuração de plugins obrigatórios e recomendados
 * 
 * Este arquivo mostra como personalizar a lista de plugins no arquivo required-plugins.php
 * 
 * @package advanced-corretora
 */

// ESTE É APENAS UM ARQUIVO DE EXEMPLO - NÃO INCLUIR NO functions.php

/**
 * Exemplos de configurações de plugins:
 */

// 1. Plugin obrigatório do repositório WordPress.org
array(
    'name'      => 'Nome do Plugin',
    'slug'      => 'slug-do-plugin',
    'required'  => true,  // true = obrigatório, false = recomendado
    'version'   => '1.0.0', // versão mínima (opcional)
),

// 2. Plugin recomendado
array(
    'name'      => 'Plugin Recomendado',
    'slug'      => 'plugin-recomendado',
    'required'  => false,
),

// 3. Plugin personalizado (arquivo ZIP)
array(
    'name'               => 'Meu Plugin Personalizado',
    'slug'               => 'meu-plugin-personalizado',
    'source'             => get_template_directory() . '/plugins/meu-plugin.zip',
    'required'           => true,
    'version'            => '1.0.0',
    'force_activation'   => false, // forçar ativação
    'force_deactivation' => false, // forçar desativação quando tema for trocado
    'external_url'       => 'https://meusite.com/plugin', // URL externa
),

// 4. Plugin de URL externa
array(
    'name'         => 'Plugin Externo',
    'slug'         => 'plugin-externo',
    'source'       => 'https://downloads.wordpress.org/plugin/plugin-externo.zip',
    'required'     => true,
    'external_url' => 'https://exemplo.com/plugin-externo',
),

/**
 * Configurações disponíveis no array $config:
 */
$config = array(
    'id'           => 'advanced-corretora',           // ID único do tema
    'default_path' => '',                            // Caminho padrão para plugins bundled
    'menu'         => 'tgmpa-install-plugins',       // Slug do menu
    'parent_slug'  => 'themes.php',                  // Menu pai (themes.php, plugins.php, etc.)
    'capability'   => 'edit_theme_options',          // Capacidade necessária
    'has_notices'  => true,                          // Mostrar avisos
    'dismissable'  => true,                          // Permitir dispensar avisos
    'dismiss_msg'  => '',                            // Mensagem quando não dispensável
    'is_automatic' => false,                         // Instalação automática
    'message'      => '',                            // Mensagem personalizada
);

/**
 * Plugins mais comuns para temas WordPress:
 */

// SEO
array('name' => 'Yoast SEO', 'slug' => 'wordpress-seo', 'required' => false),
array('name' => 'RankMath', 'slug' => 'seo-by-rankmath', 'required' => false),

// Formulários
array('name' => 'Contact Form 7', 'slug' => 'contact-form-7', 'required' => false),
array('name' => 'WPForms', 'slug' => 'wpforms-lite', 'required' => false),

// Cache
array('name' => 'WP Super Cache', 'slug' => 'wp-super-cache', 'required' => false),
array('name' => 'W3 Total Cache', 'slug' => 'w3-total-cache', 'required' => false),

// Segurança
array('name' => 'Wordfence Security', 'slug' => 'wordfence', 'required' => false),
array('name' => 'Sucuri Security', 'slug' => 'sucuri-scanner', 'required' => false),

// Campos Personalizados
array('name' => 'Advanced Custom Fields', 'slug' => 'advanced-custom-fields', 'required' => true),
array('name' => 'Carbon Fields', 'slug' => 'carbon-fields', 'required' => true),

// Page Builders
array('name' => 'Elementor', 'slug' => 'elementor', 'required' => false),
array('name' => 'Beaver Builder', 'slug' => 'beaver-builder-lite-version', 'required' => false),

// E-commerce
array('name' => 'WooCommerce', 'slug' => 'woocommerce', 'required' => false),

// Backup
array('name' => 'UpdraftPlus', 'slug' => 'updraftplus', 'required' => false),

// Performance
array('name' => 'Smush', 'slug' => 'wp-smushit', 'required' => false),
array('name' => 'Autoptimize', 'slug' => 'autoptimize', 'required' => false),
