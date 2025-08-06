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
class Advanced_Corretora_Walker_Nav_Menu extends Walker_Nav_Menu

{
    private $current_parent_id = 0;
    private $is_first_level = false;

    function start_lvl(&$output, $depth = 0, $args = null)
    {
        $indent = str_repeat("\t", $depth);

        // Só adiciona a estrutura especial para o primeiro nível de submenu
        if ($depth === 0) {
            $this->is_first_level = true;
        
            // Busca imagem do item pai (campo personalizado)
            $image_id = get_post_meta($this->current_parent_id, '_menu_item_image_id', true);
            $image_url = $image_id ? wp_get_attachment_image_url($image_id, 'large') : get_template_directory_uri() . "/assets/img/menu-thumb.jpg";
        
            $output .= "\n$indent<div class='submenu-area'>\n";
            $output .= "$indent\t<div class='submenu-inner'>\n";
            $output .= "$indent\t\t<div class='submenu-left'>\n";
            $output .= "$indent\t\t\t<img class='submenu-image' src='" . esc_url($image_url) . "' alt='' />\n";
            $output .= "$indent\t\t\t<h3>" . esc_html(get_the_title($this->current_parent_id)) . "</h3>\n";
            $output .= "$indent\t\t</div>\n";
            $output .= "$indent\t\t<div class='submenu-divider'></div>\n";
            $output .= "$indent\t\t<div class='submenu-links'>\n";
        }

        // Cada coluna começa com um wrapper (coluna) e a <ul>
        $output .= "$indent\t\t\t<div class='submenu-column'>\n";
        $output .= "$indent\t\t\t\t<ul class='sub-menu'>\n";
    }

    function end_lvl(&$output, $depth = 0, $args = null)
    {
        $indent = str_repeat("\t", $depth);

        // Fecha a coluna
        $output .= "$indent\t\t\t\t</ul>\n";
        $output .= "$indent\t\t\t</div>\n";

        if ($depth === 0) {
            $output .= "$indent\t\t</div>\n"; // submenu-links
            $output .= "$indent\t</div>\n";   // submenu-inner
            $output .= "$indent</div>\n";     // submenu-area
            $this->is_first_level = false;
        }
    }

    function start_el(&$output, $item, $depth = 0, $args = null, $id = 0)
    {
        if ($depth === 0) {
            $this->current_parent_id = $item->ID;
        }

        $classes = empty($item->classes) ? [] : (array) $item->classes;
        $classes[] = 'menu-item-' . $item->ID;
        $class_names = join(' ', array_filter($classes));

        $output .= "<li class=\"$class_names\">";

        $atts = [
            'title'  => !empty($item->attr_title) ? $item->attr_title : '',
            'target' => !empty($item->target) ? $item->target : '',
            'rel'    => !empty($item->xfn) ? $item->xfn : '',
            'href'   => !empty($item->url) ? $item->url : '',
        ];

        $attributes = '';
        foreach ($atts as $attr => $value) {
            if (!empty($value)) {
                $attributes .= " $attr=\"" . esc_attr($value) . "\"";
            }
        }

        $title = apply_filters('the_title', $item->title, $item->ID);

        $output .= "<a{$attributes}>{$title}</a>";
    }

    function end_el(&$output, $item, $depth = 0, $args = null)
    {
        $output .= "</li>\n";
    }
}


add_action('wp_nav_menu_item_custom_fields', function ($item_id, $item, $depth, $args) {
    $image_id = get_post_meta($item_id, '_menu_item_image_id', true);
    $image_url = $image_id ? wp_get_attachment_image_url($image_id, 'thumbnail') : '';

?>
    <p class="description description-wide">
        <label for="edit-menu-item-image-<?php echo $item_id; ?>">
            <?php _e('Imagem do menu'); ?><br>
            <input type="hidden" class="menu-item-image-id" id="menu-item-image-<?php echo $item_id; ?>" name="menu-item-image[<?php echo $item_id; ?>]" value="<?php echo esc_attr($image_id); ?>" />
            <button class="button upload-menu-item-image" data-item-id="<?php echo $item_id; ?>">
                <?php echo $image_url ? 'Trocar imagem' : 'Selecionar imagem'; ?>
            </button>
            <div class="menu-item-image-preview" id="menu-item-image-preview-<?php echo $item_id; ?>">
                <?php if ($image_url): ?>
                    <img src="<?php echo esc_url($image_url); ?>" style="max-width: 100px;" />
                <?php endif; ?>
            </div>
        </label>
    </p>
<?php
}, 10, 4);


add_action('wp_update_nav_menu_item', function ($menu_id, $menu_item_db_id, $args) {
    if (isset($_POST['menu-item-image'][$menu_item_db_id])) {
        update_post_meta($menu_item_db_id, '_menu_item_image_id', intval($_POST['menu-item-image'][$menu_item_db_id]));
    }
}, 10, 3);


//enable media uploader to menu
add_action('admin_footer', function () {
?>
    <script>
        jQuery(function($) {
            var mediaUploader;

            $('.upload-menu-item-image').on('click', function(e) {
                e.preventDefault();
                var button = $(this);
                var itemId = button.data('item-id');
                var input = $('#menu-item-image-' + itemId);
                var preview = $('#menu-item-image-preview-' + itemId);

                if (mediaUploader) {
                    mediaUploader.open();
                    return;
                }

                mediaUploader = wp.media({
                    title: 'Selecionar imagem',
                    button: {
                        text: 'Usar imagem'
                    },
                    multiple: false
                });

                mediaUploader.on('select', function() {
                    var attachment = mediaUploader.state().get('selection').first().toJSON();
                    input.val(attachment.id);
                    preview.html('<img src="' + attachment.url + '" style="max-width:100px;" />');
                    button.text('Trocar imagem');
                });

                mediaUploader.open();
            });
        });
    </script>
<?php
});


/**
 * Register navigation menus
 */
function advanced_corretora_register_menus()
{
    register_nav_menus(
        array(
            'top-menu' => esc_html__('Menu Principal', 'advanced-corretora'),
            'menu-1' => esc_html__('Primary', 'advanced-corretora'),
        )
    );
}
add_action('after_setup_theme', 'advanced_corretora_register_menus');
