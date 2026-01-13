<?php

use Carbon_Fields\Block;
use Carbon_Fields\Field;

function diferenciais_carrossel_block()
{
    Block::make('Carrossel Diferenciais')
        ->add_fields([
            Field::make('text', 'titulo', 'Título da Seção')
                ->set_required(true)
                ->help_text('Título que aparecerá na coluna da esquerda'),
            Field::make('complex', 'diferenciais', 'Diferenciais')
                ->add_fields([
                    Field::make('select', 'tipo_visual', 'Tipo de Visual')
                        ->set_options([
                            'numero' => 'Número',
                            'icone' => 'Ícone'
                        ])
                        ->set_default_value('numero')
                        ->set_required(false)
                        ->help_text('Escolha se deseja exibir um número ou um ícone'),
                    Field::make('text', 'numero', 'Número')
                        ->help_text('Ex: 01, 02, 03...')
                        ->set_conditional_logic([
                            [
                                'field' => 'tipo_visual',
                                'value' => 'numero',
                            ]
                        ]),
                    Field::make('image', 'icone', 'Ícone')
                        ->help_text('Faça upload do ícone para este diferencial')
                        ->set_conditional_logic([
                            [
                                'field' => 'tipo_visual',
                                'value' => 'icone',
                            ]
                        ]),
                    Field::make('text', 'titulo', 'Título')
                        ->set_required(true),
                    Field::make('textarea', 'descricao', 'Descrição')
                        ->set_rows(4),
                ])
                ->set_layout('tabbed-horizontal')
                ->set_min(2)
                ->help_text('Adicione pelo menos 2 diferenciais para criar o carrossel'),
        ])
        ->set_render_callback(function ($block, $attributes) {
            if (empty($block['diferenciais'])) {
                return;
            }

            // Get additional classes and anchor from Gutenberg
            $className = isset($attributes['className']) ? $attributes['className'] : '';
            $anchor = isset($attributes['anchor']) ? $attributes['anchor'] : '';

            // Build classes array
            $classes = ['wp-block-diferenciais-carousel'];
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
            <div class="diferenciais-container">
                <!-- Left Column: Title and Navigation -->
                <div class="diferenciais-left">
                    <div class="diferenciais-title">
                        <h2><?php echo wp_kses_post($block['titulo']); ?></h2>
                    </div>
                    <div class="diferenciais-navigation">
                        <button class="diferenciais-prev" aria-label="Anterior">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none">
                                <path d="M15 18L9 12L15 6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                        </button>
                        <button class="diferenciais-next" aria-label="Próximo">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none">
                                <path d="M9 18L15 12L9 6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                        </button>
                    </div>
                </div>

                <!-- Right Column: Carousel -->
                <div class="diferenciais-right">
                    <div class="diferenciais-carousel">
                        <?php foreach ($block['diferenciais'] as $diferencial) : ?>
                            <div class="diferencial-cell <?php echo $diferencial['tipo_visual'] === 'numero' ? 'diferencial-numero' : 'diferencial-icone'; ?>">
                                <div class="diferencial-item">
                                    <?php if ($diferencial['tipo_visual'] === 'icone' && !empty($diferencial['icone'])) : ?>
                                        <div class="diferencial-icone">
                                            <?php echo wp_get_attachment_image($diferencial['icone'], 'medium', false, array('alt' => esc_attr($diferencial['titulo']))); ?>
                                        </div>
                                    <?php elseif ($diferencial['tipo_visual'] === 'numero' && !empty($diferencial['numero'])) : ?>
                                        <div class="diferencial-numero">
                                            <?php echo esc_html($diferencial['numero']); ?>
                                        </div>
                                    <?php endif; ?>
                                    <div class="diferencial-content">
                                        <?php if (!empty($diferencial['titulo'])) : ?>
                                            <h3 class="diferencial-titulo">
                                                <?php echo wp_kses_post($diferencial['titulo']); ?>
                                            </h3>
                                        <?php endif; ?>
                                        <?php if (!empty($diferencial['descricao'])) : ?>
                                            <p class="diferencial-descricao">
                                                <?php echo $diferencial['descricao']; //phpcs:ignore ?>
                                            </p>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>

<?php
            return ob_get_flush();
        });
}
add_action('carbon_fields_register_fields', 'diferenciais_carrossel_block');

function diferenciais_enqueue_assets()
{
    $should_enqueue = false;

    // 1. Verifica no post_content
    if (is_singular() && has_block('carbon-fields/carrossel-diferenciais')) {
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
                                has_block('carbon-fields/carrossel-diferenciais', $block['content'])
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
            'advanced-corretora-diferenciais',
            get_template_directory_uri() . '/dist/js/diferenciais.js',
            array(),
            "?nocache=" . time(),
            array(
                'strategy' => 'defer',
                'in_footer' => true,
            )
        );
        wp_enqueue_script('advanced-corretora-diferenciais');

        // Add type="module" to diferenciais script
        add_filter('script_loader_tag', function ($tag, $handle, $src) {
            if ('advanced-corretora-diferenciais' === $handle) {
                $tag = str_replace('<script ', '<script type="module" ', $tag);
            }
            return $tag;
        }, 10, 3);

        // Enqueue Flickity CSS (global function prevents duplicates)
        enqueue_flickity_css_once();
    }
}
add_action('wp_enqueue_scripts', 'diferenciais_enqueue_assets');
