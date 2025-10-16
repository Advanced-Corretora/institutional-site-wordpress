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
}, 1);

// Hook para garantir que o Carbon Fields seja inicializado antes dos campos
add_action( 'init', function() {
    if ( ! Carbon_Fields::is_booted() ) {
        Carbon_Fields::boot();
    }
}, 1);

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
    
    // Campos do Rodapé
    $footer_fields = load_carbon_fields_from_file( 
        get_template_directory() . '/inc/carbonfields/rodape/footer-fields.php' 
    );
    
    if ( ! empty( $footer_fields ) ) {
        $container->add_tab( 'Rodapé', $footer_fields );
    }
    
    // Campos do Blog
    $blog_fields = load_carbon_fields_from_file( 
        get_template_directory() . '/inc/carbonfields/blog/blog-fields.php' 
    );
    
    if ( ! empty( $blog_fields ) ) {
        $container->add_tab( 'Blog', $blog_fields );
    }
    
    // other options example
    $container->add_tab( 'Outras Opções', [
        // Outros campos podem ser adicionados aqui
    ]);
    
    // Campos para Taxonomias (Categorias e Tags) - Versão simplificada
    // Campos para Categorias
    Container::make('term_meta', __('Configurações da Categoria', 'advanced-corretora'))
        ->where('term_taxonomy', '=', 'category')
        ->add_fields(array(
            Field::make('image', 'category_hero_background', __('Imagem de Fundo do Hero', 'advanced-corretora'))
                ->set_help_text('Imagem que aparecerá como fundo no hero da categoria. Recomendado: 1920x550px'),
            
            Field::make('text', 'category_hero_overlay', __('Intensidade do Overlay', 'advanced-corretora'))
                ->set_default_value('medium')
                ->set_help_text('Intensidade da sobreposição escura (light, medium, dark, heavy)'),
            
            Field::make('color', 'category_hero_gradient_start', __('Cor Inicial do Gradiente', 'advanced-corretora'))
                ->set_default_value('#003366'),
            
            Field::make('color', 'category_hero_gradient_end', __('Cor Final do Gradiente', 'advanced-corretora'))
                ->set_default_value('#00B3E8'),
            
            Field::make('textarea', 'category_custom_description', __('Descrição Personalizada', 'advanced-corretora'))
                ->set_help_text('Descrição que aparecerá no hero'),
            
            Field::make('text', 'category_posts_count_text', __('Texto do Contador', 'advanced-corretora'))
                ->set_default_value('posts'),
        ));
    
    // Campos para Tags
    Container::make('term_meta', __('Configurações da Tag', 'advanced-corretora'))
        ->where('term_taxonomy', '=', 'post_tag')
        ->add_fields(array(
            Field::make('image', 'tag_hero_background', __('Imagem de Fundo do Hero', 'advanced-corretora'))
                ->set_help_text('Imagem que aparecerá como fundo no hero da tag. Recomendado: 1920x550px'),
            
            Field::make('text', 'tag_hero_overlay', __('Intensidade do Overlay', 'advanced-corretora'))
                ->set_default_value('medium')
                ->set_help_text('Intensidade da sobreposição escura (light, medium, dark, heavy)'),
            
            Field::make('color', 'tag_hero_gradient_start', __('Cor Inicial do Gradiente', 'advanced-corretora'))
                ->set_default_value('#003366'),
            
            Field::make('color', 'tag_hero_gradient_end', __('Cor Final do Gradiente', 'advanced-corretora'))
                ->set_default_value('#00B3E8'),
            
            Field::make('textarea', 'tag_custom_description', __('Descrição Personalizada', 'advanced-corretora'))
                ->set_help_text('Descrição que aparecerá no hero'),
            
            Field::make('text', 'tag_posts_count_text', __('Texto do Contador', 'advanced-corretora'))
                ->set_default_value('posts'),
        ));
});

// blocks hook
require_once get_template_directory() . '/inc/blocks/carousel-block.php';
require_once get_template_directory() . '/inc/blocks/homeSlider-block.php';