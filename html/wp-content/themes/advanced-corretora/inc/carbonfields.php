<?php
/**
 * Arquivo principal de configuração do Carbon Fields
 * 
 * Este arquivo carrega as configurações de campos do Carbon Fields de forma modular.
 * Os campos são organizados em arquivos separados dentro do diretório /inc/carbonfields/.
 */

// Carrega o autoloader do Composer
require_once get_template_directory() . '/vendor/autoload.php';

use Carbon_Fields\Carbon_Fields;
use Carbon_Fields\Container;
use Carbon_Fields\Block;
use Carbon_Fields\Field;

// Inicializa o Carbon Fields após o tema estar pronto
add_action( 'after_setup_theme', function() {
    Carbon_Fields::boot();
});

/**
 * Carrega os campos de um arquivo específico
 * 
 * @param string $file Caminho para o arquivo de campos
 * @return array Array de campos ou array vazio se o arquivo não existir
 */
function load_carbon_fields_from_file( $file ) {
    if ( file_exists( $file ) ) {
        return include $file;
    }
    return [];
}

// theme options hook
add_action( 'carbon_fields_register_fields', function() {
    $container = Container::make( 'theme_options', __( 'Opções do Tema' ) );
    $header_cta_fields = load_carbon_fields_from_file( 
        get_template_directory() . '/inc/carbonfields/cabecalho/cta.php' 
    );
    
    if ( ! empty( $header_cta_fields ) ) {
        $container->add_tab( 'Cabeçalho', $header_cta_fields );
    }
    
    // other options example
    $container->add_tab( 'Outras Opções', [
        // Outros campos podem ser adicionados aqui
    ]);
});

// blocks hook
require_once get_template_directory() . '/inc/blocks/carousel-block.php';