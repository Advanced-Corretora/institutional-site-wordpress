<?php
/**
 * Sistema de Sidebar Conteúdo Dinâmica
 * 
 * @package advanced-corretora
 */

/**
 * Registra a sidebar "Sidebar Conteúdo" no sistema de widgets
 */
function advanced_corretora_register_content_sidebar() {
    register_sidebar(array(
        'name'          => __('Sidebar Conteúdo', 'advanced-corretora'),
        'id'            => 'sidebar-conteudo',
        'description'   => __('Sidebar que aparece dinamicamente dentro do conteúdo dos posts/páginas.', 'advanced-corretora'),
        'before_widget' => '<div id="%1$s" class="widget %2$s">',
        'after_widget'  => '</div>',
        'before_title'  => '<h3 class="widget-title">',
        'after_title'   => '</h3>',
    ));
}
add_action('widgets_init', 'advanced_corretora_register_content_sidebar');

/**
 * Insere a sidebar conteúdo dinamicamente no the_content
 */
function advanced_corretora_insert_content_sidebar($content) {
    // Verifica se estamos em um post ou página individual
    if (!is_single() && !is_page()) {
        return $content;
    }
    
    // Obtém as configurações globais do tema
    $enable_sidebar = carbon_get_theme_option('enable_content_sidebar');
    $paragraph_position = carbon_get_theme_option('content_sidebar_paragraph');
    
    // Se a sidebar não está ativada, retorna o conteúdo original
    if (!$enable_sidebar) {
        return $content;
    }
    
    // Se não há posição definida, usa padrão (2º parágrafo)
    if (empty($paragraph_position)) {
        $paragraph_position = 2;
    }
    
    // Verifica se a sidebar tem widgets
    if (!is_active_sidebar('sidebar-conteudo')) {
        return $content;
    }
    
    // Insere a sidebar na posição especificada
    $modified_content = advanced_corretora_insert_sidebar_at_paragraph($content, $paragraph_position);
    
    return $modified_content;
}
add_filter('the_content', 'advanced_corretora_insert_content_sidebar');

/**
 * Insere a sidebar após o parágrafo especificado
 */
function advanced_corretora_insert_sidebar_at_paragraph($content, $paragraph_number) {
    // Divide o conteúdo em parágrafos usando regex
    $paragraphs = preg_split('/(<\/p>)/i', $content, -1, PREG_SPLIT_DELIM_CAPTURE);
    
    // Remove elementos vazios e reindexar
    $paragraphs = array_values(array_filter($paragraphs, function($p) {
        return !empty(trim($p));
    }));
    
    // Conta quantos parágrafos reais temos (tags </p>)
    $paragraph_count = 0;
    $insert_position = -1;
    
    foreach ($paragraphs as $index => $paragraph) {
        if (trim($paragraph) === '</p>') {
            $paragraph_count++;
            if ($paragraph_count == $paragraph_number) {
                $insert_position = $index + 1;
                break;
            }
        }
    }
    
    // Se não encontrou a posição ou não há parágrafos suficientes, adiciona no final
    if ($insert_position === -1) {
        $insert_position = count($paragraphs);
    }
    
    // Gera o HTML da sidebar
    $sidebar_html = advanced_corretora_get_content_sidebar_html();
    
    // Insere a sidebar na posição calculada
    array_splice($paragraphs, $insert_position, 0, $sidebar_html);
    
    return implode('', $paragraphs);
}

/**
 * Gera o HTML da sidebar conteúdo
 */
function advanced_corretora_get_content_sidebar_html() {
    ob_start();
    ?>
    <div class="content-sidebar-wrapper">
        <aside class="content-sidebar">
            <?php dynamic_sidebar('sidebar-conteudo'); ?>
        </aside>
    </div>
    <?php
    return ob_get_clean();
}
