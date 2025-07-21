<?php
/**
 * Menu related functions for Advanced Corretora theme
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

/**
 * Custom Walker class for dropdown menus
 */
class Advanced_Corretora_Walker_Nav_Menu extends Walker_Nav_Menu {
    function start_lvl(&$output, $depth = 0, $args = null) {
        $indent = str_repeat("\t", $depth);
        $output .= "\n$indent<ul class='sub-menu'>\n";
    }
}

/**
 * Register navigation menus
 */
function advanced_corretora_register_menus() {
    register_nav_menus(
        array(
            'top-menu' => esc_html__('Menu Principal', 'advanced-corretora'),
            'menu-1' => esc_html__('Primary', 'advanced-corretora'),
        )
    );
}
add_action('after_setup_theme', 'advanced_corretora_register_menus');

