<?php

/**
 * Global Flickity CSS enqueue handler
 * Prevents duplicate imports of flickityStyle.css across multiple blocks
 */

// Global flag to track if Flickity CSS has been enqueued
global $flickity_css_enqueued;
$flickity_css_enqueued = false;

/**
 * Enqueue Flickity CSS only once per page load
 * 
 * @return bool True if CSS was enqueued, false if already enqueued
 */
function enqueue_flickity_css_once() {
    global $flickity_css_enqueued;
    
    // If already enqueued, return false
    if ($flickity_css_enqueued) {
        return false;
    }
    
    // Check if the CSS file exists
    $flickity_style_path = get_template_directory() . '/dist/css/flickityStyle.css';
    if (!file_exists($flickity_style_path)) {
        return false;
    }
    
    // Enqueue the CSS
    $flickity_style_uri = get_template_directory_uri() . '/dist/css/flickityStyle.css';
    $flickity_style_ver = filemtime($flickity_style_path);
    
    wp_enqueue_style(
        'advanced-corretora-flickity-style',
        $flickity_style_uri,
        array('advanced-corretora-style'),
        $flickity_style_ver
    );
    
    // Mark as enqueued
    $flickity_css_enqueued = true;
    
    return true;
}

/**
 * Check if any Flickity-based blocks are present on the current page
 * 
 * @return bool True if any Flickity blocks are found
 */
function has_flickity_blocks() {
    $flickity_blocks = [
        'carbon-fields/produtos-carrossel',
        'carbon-fields/home-slider',
        'carbon-fields/timeline'
    ];
    
    // Check in post content
    if (is_singular()) {
        foreach ($flickity_blocks as $block_name) {
            if (has_block($block_name)) {
                return true;
            }
        }
    }
    
    // Check in widget areas
    $sidebars_widgets = wp_get_sidebars_widgets();
    foreach ($sidebars_widgets as $sidebar) {
        foreach ($sidebar as $widget_id) {
            if (strpos($widget_id, 'block-') === 0) {
                $widget_data = get_option('widget_block');
                if ($widget_data) {
                    foreach ($widget_data as $block) {
                        if (isset($block['content'])) {
                            foreach ($flickity_blocks as $block_name) {
                                if (has_block($block_name, $block['content'])) {
                                    return true;
                                }
                            }
                        }
                    }
                }
            }
        }
    }
    
    return false;
}
