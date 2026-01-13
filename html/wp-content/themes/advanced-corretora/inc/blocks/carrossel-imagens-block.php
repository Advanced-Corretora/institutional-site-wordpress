<?php

use Carbon_Fields\Block;
use Carbon_Fields\Field;

function carrossel_imagens_block()
{
    Block::make('Carrossel de Imagens')
        ->add_fields([
            Field::make('complex', 'imagens', 'Imagens')
                ->add_fields([
                    Field::make('image', 'imagem', 'Imagem')
                        ->set_required(true)
                        ->help_text('Selecione a imagem para o carrossel'),
                ])
                ->set_layout('tabbed-horizontal')
                ->set_min(1)
                ->help_text('Adicione as imagens para criar o carrossel'),
        ])
        ->set_render_callback(function ($block, $attributes) {
            if (empty($block['imagens'])) {
                return;
            }

            // Get additional classes and anchor from Gutenberg
            $className = isset($attributes['className']) ? $attributes['className'] : '';
            $anchor = isset($attributes['anchor']) ? $attributes['anchor'] : '';
            
            // Build classes array
            $classes = ['wp-block-carrossel-imagens'];
            if (!empty($className)) {
                $classes[] = $className;
            }
            
            // Build attributes array
            $block_attributes = [];
            if (!empty($anchor)) {
                $block_attributes[] = 'id="' . esc_attr($anchor) . '"';
            }
            $block_attributes[] = 'class="' . esc_attr(implode(' ', $classes)) . '"';

            ob_start();
?>
        <div <?php echo implode(' ', $block_attributes); ?>>
            <div class="carrossel-imagens-container">
                <div class="carrossel-imagens-carousel">
                    <?php foreach ($block['imagens'] as $item) : ?>
                        <div class="imagem-cell">
                            <div class="imagem-item">
                                <?php if (!empty($item['imagem'])) : ?>
                                    <div class="imagem-wrapper">
                                        <?php echo wp_get_attachment_image($item['imagem'], 'large', false, array('alt' => 'Imagem do carrossel')); ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>

<?php
            return ob_get_flush();
        });
}
add_action('carbon_fields_register_fields', 'carrossel_imagens_block');

function carrossel_imagens_enqueue_assets()
{
    $should_enqueue = false;

    // 1. Verifica no post_content
    if (is_singular() && has_block('carbon-fields/carrossel-de-imagens')) {
        $should_enqueue = true;
    }

    // 2. Verifica nas áreas de widget
    if (!$should_enqueue) {
        $sidebars_widgets = wp_get_sidebars_widgets();
        foreach ($sidebars_widgets as $sidebar) {
            foreach ($sidebar as $widget_id) {
                if (strpos($widget_id, 'block-') === 0) {
                    $widget_data = get_option('widget_block');
                    if ($widget_data) {
                        foreach ($widget_data as $block) {
                            if (
                                isset($block['content']) &&
                                has_block('carbon-fields/carrossel-de-imagens', $block['content'])
                            ) {
                                $should_enqueue = true;
                                break 2;
                            }
                        }
                    }
                }
            }
        }
    }
    
    if ($should_enqueue) {
        wp_register_script(
            'advanced-corretora-carrossel-imagens',
            get_template_directory_uri() . '/dist/js/carrosselImagens.js',
            array(),
            "?nocache=" . time(),
            array(
                'strategy' => 'defer',
                'in_footer' => true,
            )
        );
        wp_enqueue_script('advanced-corretora-carrossel-imagens');
        
        // Add type="module" to carrossel-imagens script
        add_filter('script_loader_tag', function($tag, $handle, $src) {
            if ('advanced-corretora-carrossel-imagens' === $handle) {
                $tag = str_replace('<script ', '<script type="module" ', $tag);
            }
            return $tag;
        }, 10, 3);

        // Enqueue Flickity CSS (global function prevents duplicates)
        enqueue_flickity_css_once();
    }
}
add_action('wp_enqueue_scripts', 'carrossel_imagens_enqueue_assets');
