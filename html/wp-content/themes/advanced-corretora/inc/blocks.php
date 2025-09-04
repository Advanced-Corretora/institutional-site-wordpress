<?php
/**
 * Custom Blocks Registration
 *
 * @package advanced-corretora
 */

/**
 * Register custom block category
 */
function advanced_corretora_block_categories($categories, $post) {
    return array_merge(
        $categories,
        array(
            array(
                'slug'  => 'advanced-corretora',
                'title' => __('Advanced Corretora', 'advanced-corretora'),
                'icon'  => 'chart-bar',
            ),
        )
    );
}
add_filter('block_categories_all', 'advanced_corretora_block_categories', 10, 2);

/**
 * Register custom blocks
 */
function advanced_corretora_register_blocks() {
    // Register Sessão Números block
    $sessao_result = register_block_type(get_template_directory() . '/blocks/sessao-numeros');
    error_log('Sessão Números registration result: ' . ($sessao_result ? 'SUCCESS' : 'FAILED'));
    
    // Register Seção CTA block
    $cta_result = register_block_type(get_template_directory() . '/blocks/secao-cta');
    error_log('Seção CTA registration result: ' . ($cta_result ? 'SUCCESS' : 'FAILED'));
    
    // Debug: Check if files exist
    $sessao_path = get_template_directory() . '/blocks/sessao-numeros/block.json';
    $cta_path = get_template_directory() . '/blocks/secao-cta/block.json';
    error_log('Sessão Números block.json exists: ' . (file_exists($sessao_path) ? 'YES' : 'NO') . ' - ' . $sessao_path);
    error_log('Seção CTA block.json exists: ' . (file_exists($cta_path) ? 'YES' : 'NO') . ' - ' . $cta_path);
}
add_action('init', 'advanced_corretora_register_blocks');

/**
 * Enqueue block assets
 */
function advanced_corretora_enqueue_block_assets() {
    // Dependencies are now handled automatically via index.asset.php files
}
add_action('enqueue_block_assets', 'advanced_corretora_enqueue_block_assets');

/**
 * Compile SCSS to CSS for blocks
 */
function advanced_corretora_compile_block_scss() {
    $blocks = ['sessao-numeros', 'secao-cta'];
    
    foreach ($blocks as $block) {
       
        $scss_file = get_template_directory() . '/blocks/' . $block . '/editor.scss';
        $css_file = get_template_directory() . '/blocks/' . $block . '/editor.css';
        
        if (file_exists($scss_file)) {
            // For now, we'll use the CSS directly
            // In a production environment, you might want to use a proper SCSS compiler
            if (!file_exists($css_file)) {
                // Create a basic CSS file from SCSS content
                $scss_content = file_get_contents($scss_file);
                // Simple SCSS to CSS conversion (basic)
                $css_content = str_replace(array('&:hover', '&:focus'), array(':hover', ':focus'), $scss_content);
                $css_content = preg_replace('/\.([a-zA-Z0-9_-]+)\s*{\s*\.([a-zA-Z0-9_-]+)/', '.${1} .${2}', $css_content);
                file_put_contents($css_file, $css_content);
            }
        }
    }
}
add_action('init', 'advanced_corretora_compile_block_scss');
