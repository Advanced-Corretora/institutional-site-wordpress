<?php

use Carbon_Fields\Block;
use Carbon_Fields\Field;

function home_slider_block()
{
    Block::make('Home Slider')
        ->add_fields([
            Field::make('complex', 'slides', 'Slides')
                ->add_fields([
                    Field::make('image', 'imagem_fundo', 'Imagem de Fundo'),
                    Field::make('text', 'titulo', 'Título'),
                    Field::make('text', 'subtitulo', 'Subtítulo'),
                    Field::make('textarea', 'texto', 'Texto'),
                    Field::make('text', 'cta_texto', 'Texto do Botão'),
                    Field::make('text', 'cta_link', 'Link do Botão'),
                    Field::make('text', 'disclaimer', 'Disclaimer'),
                    Field::make('select', 'tema', 'Tema do Slide (dark ou light)')
                        ->set_options([
                            'dark' => 'Dark',
                            'light' => 'Light',
                        ])
                        ->set_default_value('dark'),
                    Field::make('color', 'cor_submenu', 'Cor do Submenu')
                        ->set_help_text('Cor que será aplicada ao submenu do header quando este slide estiver ativo'),
                ])
                ->set_layout('tabbed-horizontal'), // melhora visual no editor
        ])
        ->set_render_callback(function ($block, $attributes) {
            if (empty($block['slides'])) {
                return;
            }

            // Get additional classes and anchor from Gutenberg
            $className = isset($attributes['className']) ? $attributes['className'] : '';
            $anchor = isset($attributes['anchor']) ? $attributes['anchor'] : '';
            
            // Build classes array
            $classes = ['wp-block-home-slider'];
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
            <div class="gutenberg-home-slider" data-flickity='{ "wrapAround": true, "pageDots": true, "prevNextButtons": true, "cellAlign": "center", "contain": false }'>
                <?php foreach ($block['slides'] as $index => $slide) : ?>
                    <div class="slider-cell" <?php echo !empty($slide['cor_submenu']) ? 'data-submenu-color="' . esc_attr($slide['cor_submenu']) . '"' : ''; ?>>
                        <div class="slide" style="background-image: url('<?php echo wp_get_attachment_image_url($slide['imagem_fundo'], 'full'); ?>');">
                            <div class="slide-content <?php echo (!empty($slide['tema']) && $slide['tema'] === 'light') ? 'light' : 'dark'; ?>">
                                <div class="container">
                                    <div class="content-wrapper">
                                        <?php if (!empty($slide['titulo'])) : ?>
                                            <?php if ($index === 0) : ?>
                                                <h1 class="slide-title"><?php echo ($slide['titulo']); //phpcs:ignore 
                                                                        ?></h1>
                                            <?php else : ?>
                                                <h2 class="slide-title"><?php echo ($slide['titulo']); //phpcs:ignore 
                                                                        ?></h2>
                                            <?php endif; ?>
                                        <?php endif; ?>
                                        <?php if (!empty($slide['subtitulo'])) : ?>
                                            <p class="slide-subtitle"><?php echo ($slide['subtitulo']); //phpcs:ignore 
                                                                        ?></p>
                                        <?php endif; ?>
                                        <?php if (!empty($slide['texto'])) : ?>
                                            <p class="slide-text"><?php echo ($slide['texto']); ?></p>
                                        <?php endif; ?>
                                        <?php if (!empty($slide['cta_texto']) && !empty($slide['cta_link'])) : ?>
                                            <div class="slide-cta">
                                                <a href="<?php echo esc_url($slide['cta_link']); ?>" class="button slide-button">
                                                    <?php echo ($slide['cta_texto']); ?>
                                                </a>
                                            </div>
                                        <?php endif; ?>
                                        <?php if (!empty($slide['disclaimer'])) : ?>
                                            <p class="slide-disclaimer"><?php echo ($slide['disclaimer']); ?></p>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>

            </div>
        </div>


<?php
            return ob_get_flush();
        });
}
add_action('carbon_fields_register_fields', 'home_slider_block');


function home_slider_enqueue_assets()
{

    $should_enqueue = false;

    // 1. Verifica no post_content
    if (is_singular() && has_block('carbon-fields/home-slider')) {
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
                                has_block('carbon-fields/home-slider', $block['content'])
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
            'advanced-corretora-home-slider',
            get_template_directory_uri() . '/dist/js/homeSlider.js',
            array(),  // dependências aqui se tiver (ex: jquery)
            "?nocache=" . time(),
            array(
                'strategy' => 'defer',
                'in_footer' => true,
            )
        );
        wp_enqueue_script('advanced-corretora-home-slider');
        
        // Add type="module" to home slider script
        add_filter('script_loader_tag', function($tag, $handle, $src) {
            if ('advanced-corretora-home-slider' === $handle) {
                $tag = str_replace('<script ', '<script type="module" ', $tag);
            }
            return $tag;
        }, 10, 3);

        // Enqueue Flickity CSS (global function prevents duplicates)
        enqueue_flickity_css_once();
    }
}
add_action('wp_enqueue_scripts', 'home_slider_enqueue_assets');
