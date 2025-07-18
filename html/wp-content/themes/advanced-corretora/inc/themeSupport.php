<?php
/**
 * Register menus
 */
function advanced_corretora_menus() {
    register_nav_menus(
        array(
            'top-menu' => esc_html__('Top Menu', 'advanced-corretora'),
            'menu-1' => esc_html__('Primary Menu', 'advanced-corretora'),
        )
    );
}
add_action('after_setup_theme', 'advanced_corretora_menus');

/**
 * Enable SVG uploads by adding SVG to allowed mime types
 */
function enable_svg_upload($mimes): mixed {
    $mimes['svg'] = 'image/svg+xml';
    $mimes['svgz'] = 'image/svg+xml';
    return $mimes;
}
add_filter('upload_mimes', 'enable_svg_upload');