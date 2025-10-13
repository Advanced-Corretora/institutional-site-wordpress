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
add_filter( 'wpcf7_autop_or_not', '__return_false' );

function disable_srcset_for_class($attr, $attachment, $size) {
    if (!empty($attr['class']) && strpos($attr['class'], 'no-srcset') !== false) {
        unset($attr['srcset']);
        unset($attr['sizes']);
    }
    return $attr;
}
add_filter('wp_get_attachment_image_attributes', 'disable_srcset_for_class', 10, 3);

/**
 * Disable srcset globally for high resolution screens (>1366px width)
 * This ensures original image quality on larger screens
 */
function advanced_corretora_disable_srcset_high_res($attr, $attachment, $size) {
    // Check if we should disable srcset based on screen resolution
    // This will be handled by JavaScript on the frontend
    if (isset($_COOKIE['screen_width']) && intval($_COOKIE['screen_width']) > 1366) {
        unset($attr['srcset']);
        unset($attr['sizes']);
        
        // Force original image URL for better quality
        $image_url = wp_get_attachment_image_url($attachment->ID, 'full');
        if ($image_url) {
            $attr['src'] = $image_url;
        }
    }
    
    return $attr;
}
add_filter('wp_get_attachment_image_attributes', 'advanced_corretora_disable_srcset_high_res', 20, 3);

/**
 * Disable responsive images completely for high resolution screens
 */
function advanced_corretora_disable_responsive_images() {
    if (isset($_COOKIE['screen_width']) && intval($_COOKIE['screen_width']) > 1366) {
        add_filter('wp_calculate_image_srcset', '__return_false');
        add_filter('wp_calculate_image_sizes', '__return_false');
    }
}
add_action('init', 'advanced_corretora_disable_responsive_images');

/**
 * Enqueue script to detect screen resolution and set cookie
 */
function advanced_corretora_screen_detection_script() {
    ?>
    <script>
    (function() {
        // Get screen width
        var screenWidth = window.screen.width;
        
        // Set cookie with screen width (expires in 1 day)
        document.cookie = 'screen_width=' + screenWidth + '; path=/; max-age=86400';
        
        // For high resolution screens, replace srcset images with full size
        if (screenWidth > 1366) {
            document.addEventListener('DOMContentLoaded', function() {
                // Handle regular img tags
                var images = document.querySelectorAll('img[srcset]');
                images.forEach(function(img) {
                    // Get the original src or the largest image from srcset
                    var srcset = img.getAttribute('srcset');
                    if (srcset) {
                        var sources = srcset.split(',');
                        var largestSrc = '';
                        var largestWidth = 0;
                        
                        sources.forEach(function(source) {
                            var parts = source.trim().split(' ');
                            if (parts.length >= 2) {
                                var width = parseInt(parts[1].replace('w', ''));
                                if (width > largestWidth) {
                                    largestWidth = width;
                                    largestSrc = parts[0];
                                }
                            }
                        });
                        
                        if (largestSrc) {
                            img.src = largestSrc;
                            img.removeAttribute('srcset');
                            img.removeAttribute('sizes');
                        }
                    }
                });
                
                // Handle background images in CSS
                var elementsWithBg = document.querySelectorAll('[style*="background-image"]');
                elementsWithBg.forEach(function(element) {
                    var style = element.getAttribute('style');
                    if (style && style.includes('background-image')) {
                        // Force reload to get full resolution if needed
                        var bgMatch = style.match(/background-image:\s*url\(['"]?([^'"]+)['"]?\)/);
                        if (bgMatch && bgMatch[1]) {
                            var imageUrl = bgMatch[1];
                            // Replace any size parameters with full size
                            var fullSizeUrl = imageUrl.replace(/-\d+x\d+(\.[a-zA-Z]+)$/, '$1');
                            if (fullSizeUrl !== imageUrl) {
                                element.style.backgroundImage = 'url(' + fullSizeUrl + ')';
                            }
                        }
                    }
                });
            });
        }
    })();
    </script>
    <?php
}
add_action('wp_head', 'advanced_corretora_screen_detection_script', 1);

/**
 * Force full size images for background images on high resolution screens
 */
function advanced_corretora_force_full_bg_images($html, $post_id, $post_thumbnail_id, $size, $attr) {
    if (isset($_COOKIE['screen_width']) && intval($_COOKIE['screen_width']) > 1366) {
        // If this is being used for background, force full size
        if (isset($attr['class']) && (strpos($attr['class'], 'bg-') !== false || strpos($attr['class'], 'background') !== false)) {
            $full_image = wp_get_attachment_image($post_thumbnail_id, 'full', false, $attr);
            return $full_image;
        }
    }
    return $html;
}
add_filter('post_thumbnail_html', 'advanced_corretora_force_full_bg_images', 10, 5);

/**
 * Helper function to get full size image URL for high resolution screens
 */
function advanced_corretora_get_full_image_url($attachment_id) {
    if (isset($_COOKIE['screen_width']) && intval($_COOKIE['screen_width']) > 1366) {
        return wp_get_attachment_image_url($attachment_id, 'full');
    }
    return wp_get_attachment_image_url($attachment_id, 'large');
}

/**
 * Add CSS to improve image quality on high resolution screens
 */
function advanced_corretora_high_res_image_styles() {
    ?>
    <style>
    @media (min-width: 1367px) {
        /* Force high quality for background images */
        .wp-block-cover,
        .wp-block-media-text__media,
        [class*="bg-"],
        [class*="background"],
        .hero-section,
        .banner-section {
            image-rendering: -webkit-optimize-contrast;
            image-rendering: crisp-edges;
        }
        
        /* Ensure images are not compressed */
        img {
            image-rendering: -webkit-optimize-contrast;
            image-rendering: crisp-edges;
        }
    }
    </style>
    <?php
}
add_action('wp_head', 'advanced_corretora_high_res_image_styles', 5);