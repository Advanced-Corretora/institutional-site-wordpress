<?php
/**
 * Campos do Carbon Fields para configurações do Blog
 * 
 * @package advanced-corretora
 */

use Carbon_Fields\Field;

return [
    // Seção Hero da Busca
    Field::make( 'separator', 'blog_search_hero_separator', __( 'Hero da Busca do Blog' ) )
        ->set_help_text( 'Configurações para a seção hero da página de busca do blog' ),
    
    Field::make( 'image', 'blog_search_hero_background', __( 'Imagem de Fundo do Hero' ) )
        ->set_help_text( 'Imagem de fundo para a seção hero da busca do blog. Recomendado: 1920x400px ou maior' )
        ->set_value_type( 'url' ),
    
    Field::make( 'select', 'blog_search_hero_overlay', __( 'Intensidade do Overlay' ) )
        ->set_help_text( 'Controla a intensidade do overlay escuro sobre a imagem' )
        ->set_options( [
            'no-overlay' => 'Sem Overlay',
            'light' => 'Leve (20%)',
            'medium' => 'Médio (40%)',
            'dark' => 'Escuro (60%)',
            'heavy' => 'Pesado (80%)'
        ] )
        ->set_default_value( 'medium' ),
    
    Field::make( 'color', 'blog_search_hero_gradient_start', __( 'Cor Inicial do Gradiente' ) )
        ->set_help_text( 'Cor inicial do gradiente quando não há imagem de fundo' )
        ->set_default_value( '#003366' ),
    
    Field::make( 'color', 'blog_search_hero_gradient_end', __( 'Cor Final do Gradiente' ) )
        ->set_help_text( 'Cor final do gradiente quando não há imagem de fundo' )
        ->set_default_value( '#00B3E8' ),
    
    // Seção de Configurações Gerais do Blog
    Field::make( 'separator', 'blog_general_separator', __( 'Configurações Gerais do Blog' ) ),
    
    Field::make( 'text', 'blog_search_placeholder', __( 'Placeholder da Busca' ) )
        ->set_help_text( 'Texto que aparece no campo de busca do blog' )
        ->set_default_value( 'Buscar no blog...' ),
    
    Field::make( 'textarea', 'blog_no_results_message', __( 'Mensagem de Nenhum Resultado' ) )
        ->set_help_text( 'Mensagem exibida quando não há resultados na busca' )
        ->set_default_value( 'Nenhum post encontrado para sua busca. Tente usar termos diferentes ou mais gerais.' )
        ->set_rows( 3 ),
    
    // Seção de Cores do Blog
    Field::make( 'separator', 'blog_colors_separator', __( 'Cores do Blog' ) ),
    
    Field::make( 'color', 'blog_primary_color', __( 'Cor Primária do Blog' ) )
        ->set_help_text( 'Cor principal usada nos elementos do blog' )
        ->set_default_value( '#00B3E8' ),
    
    Field::make( 'color', 'blog_secondary_color', __( 'Cor Secundária do Blog' ) )
        ->set_help_text( 'Cor secundária usada nos elementos do blog' )
        ->set_default_value( '#003366' ),
    
    Field::make( 'color', 'blog_background_color', __( 'Cor de Fundo do Blog' ) )
        ->set_help_text( 'Cor de fundo das páginas do blog' )
        ->set_default_value( '#f8f9fa' ),
    
    // Seção de Layout
    Field::make( 'separator', 'blog_layout_separator', __( 'Layout do Blog' ) ),
    
    Field::make( 'checkbox', 'blog_show_reading_time', __( 'Mostrar Tempo de Leitura' ) )
        ->set_help_text( 'Exibe o tempo estimado de leitura nos posts' )
        ->set_default_value( true ),
    
    Field::make( 'checkbox', 'blog_show_categories', __( 'Mostrar Categorias' ) )
        ->set_help_text( 'Exibe as categorias nos cards dos posts' )
        ->set_default_value( true ),
    
    Field::make( 'checkbox', 'blog_show_excerpt', __( 'Mostrar Excerpt' ) )
        ->set_help_text( 'Exibe o excerpt/resumo nos cards dos posts' )
        ->set_default_value( true ),
    
    Field::make( 'select', 'blog_posts_per_page', __( 'Posts por Página' ) )
        ->set_help_text( 'Número de posts exibidos por página no blog' )
        ->set_options( [
            '6' => '6 posts',
            '9' => '9 posts',
            '12' => '12 posts',
            '15' => '15 posts',
            '18' => '18 posts'
        ] )
        ->set_default_value( '9' ),
];
