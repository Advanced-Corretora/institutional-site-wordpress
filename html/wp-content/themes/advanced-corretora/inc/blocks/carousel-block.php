<?php

use Carbon_Fields\Block;
use Carbon_Fields\Field;

function produtos_carrossel_block()
{
    Block::make('Produtos Carrossel')
        ->add_fields([
            Field::make('complex', 'produtos', 'Produtos')
                ->add_fields([
                    Field::make('image', 'imagem', 'Imagem do Produto'),
                    Field::make('text', 'titulo', 'Título'),
                    Field::make('text', 'cta_texto', 'Texto do Botão'),
                    Field::make('text', 'cta_link', 'Link do Botão'),
                ])
                ->set_layout('tabbed-horizontal'), // melhora visual no editor
        ])
        ->set_render_callback(function ($block) {
            if (empty($block['produtos'])) {
                return;
            }

            ob_start();
?>
        <div class="wp-block-products-carousel gutenberg-swiper">
            <div class="swiper-wrapper">
                <?php foreach ($block['produtos'] as $produto) : ?>
                    <div class="swiper-slide">
                        <div class="product">
                            <div class="image">
                                <?php echo wp_get_attachment_image($produto['imagem'], 'medium'); ?>
                            </div>
                            <div class="title">
                                <h3><?php echo esc_html($produto['titulo']); ?></h3>
                            </div>
                            <div class="cta">
                                <a href="<?php echo esc_url($produto['cta_link']); ?>" class="button">
                                    <?php echo esc_html($produto['cta_texto']); ?>
                                </a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
            <div class="swiper-pagination"></div>
            <div class="swiper-button-next"></div>
            <div class="swiper-button-prev"></div>
            <div class="swiper-scrollbar"></div>
        </div>

<?php
            return ob_get_flush();
        });
}
add_action('carbon_fields_register_fields', 'produtos_carrossel_block');


function gutenberg_enqueue_assets()
{

    $should_enqueue = false;

    // 1. Verifica no post_content
    if (is_singular() && has_block('carbon-fields/produtos-carrossel')) {
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
                                has_block('carbon-fields/produtos-carrossel', $block['content'])
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
            'advanced-corretora-carousel',
            get_template_directory_uri() . '/dist/js/carousel.js',
            array(),  // dependências aqui se tiver (ex: jquery)
            null,
            array(
                'strategy' => 'defer',
                'in_footer' => true,
            )
        );
        wp_enqueue_script('advanced-corretora-carousel');


        wp_enqueue_style(
            'swiper-style',
            'https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css'
        );
    }
}
add_action('wp_enqueue_scripts', 'gutenberg_enqueue_assets');
