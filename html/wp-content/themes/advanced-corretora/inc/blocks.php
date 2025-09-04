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
    register_block_type(get_template_directory() . '/blocks/sessao-numeros');
}
add_action('init', 'advanced_corretora_register_blocks');

/**
 * Enqueue block assets
 */
function advanced_corretora_enqueue_block_assets() {
    // Enqueue block editor assets
    if (is_admin()) {
        wp_enqueue_script(
            'advanced-corretora-blocks-editor',
            get_template_directory_uri() . '/blocks/sessao-numeros/index.js',
            array('wp-blocks', 'wp-i18n', 'wp-element', 'wp-block-editor', 'wp-components'),
            filemtime(get_template_directory() . '/blocks/sessao-numeros/index.js'),
            true
        );

        wp_enqueue_style(
            'advanced-corretora-blocks-editor-style',
            get_template_directory_uri() . '/blocks/sessao-numeros/editor.css',
            array(),
            filemtime(get_template_directory() . '/blocks/sessao-numeros/editor.css')
        );
    }

    // Frontend styles are now handled by the main theme SCSS compilation
    // No need to enqueue style-index.css as styles are in /src/sass/blocks/_sessao-numeros.scss
}
add_action('enqueue_block_assets', 'advanced_corretora_enqueue_block_assets');

/**
 * Compile SCSS to CSS for blocks
 */
function advanced_corretora_compile_block_scss() {
    $scss_file = get_template_directory() . '/blocks/sessao-numeros/editor.scss';
    $css_file = get_template_directory() . '/blocks/sessao-numeros/editor.css';
    
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
add_action('init', 'advanced_corretora_compile_block_scss');
