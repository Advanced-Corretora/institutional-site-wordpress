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

/**
 * Add support for wide alignment on Gutenberg editor
 */
add_theme_support( 'align-wide' );

/**
 * Add support for custom spacing (padding/margin) controls in Gutenberg
 */
add_theme_support( 'custom-spacing' );

/**
 * Add support for custom units (px, em, rem, %, vw, vh)
 */
add_theme_support( 'custom-units' );

/**
 * Add responsive spacing support with breakpoints
 */
add_theme_support( 'responsive-embeds' );

/**
 * Define custom spacing sizes for consistent responsive design
 */
function advanced_corretora_spacing_sizes() {
    add_theme_support( 'editor-spacing-sizes', array(
        array(
            'name' => __( 'Pequeno', 'advanced-corretora' ),
            'size' => '0.5rem',
            'slug' => 'small'
        ),
        array(
            'name' => __( 'Médio', 'advanced-corretora' ),
            'size' => '1rem',
            'slug' => 'medium'
        ),
        array(
            'name' => __( 'Grande', 'advanced-corretora' ),
            'size' => '2rem',
            'slug' => 'large'
        ),
        array(
            'name' => __( 'Extra Grande', 'advanced-corretora' ),
            'size' => '3rem',
            'slug' => 'x-large'
        )
    ));
}
add_action( 'after_setup_theme', 'advanced_corretora_spacing_sizes' );

/**
 * Enable spacing controls for core blocks including columns
 */
function advanced_corretora_block_supports() {
    // Add spacing support to core/column block
    add_filter( 'block_type_metadata', function( $metadata ) {
        if ( isset( $metadata['name'] ) && $metadata['name'] === 'core/column' ) {
            if ( ! isset( $metadata['supports'] ) ) {
                $metadata['supports'] = array();
            }
            if ( ! isset( $metadata['supports']['spacing'] ) ) {
                $metadata['supports']['spacing'] = array();
            }
            
            // Enable padding controls
            $metadata['supports']['spacing']['padding'] = true;
            // Enable margin controls (optional)
            $metadata['supports']['spacing']['margin'] = true;
            // Enable block gap controls (optional)
            $metadata['supports']['spacing']['blockGap'] = true;
            
            // Add custom attributes for responsive spacing
            if ( ! isset( $metadata['attributes'] ) ) {
                $metadata['attributes'] = array();
            }
            
        }
        return $metadata;
    });
}
add_action( 'init', 'advanced_corretora_block_supports' );
