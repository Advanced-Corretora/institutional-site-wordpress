<?php

use Carbon_Fields\Block;
use Carbon_Fields\Field;

function timeline_block()
{
    Block::make('Timeline')
        ->add_fields([
            Field::make('text', 'initial_slide', 'Slide Inicial (começar em qual item?)')
                ->set_default_value('1')
                ->set_attribute('type', 'number')
                ->set_attribute('min', '1')
                ->help_text('Defina em qual item o carrossel deve começar (ex: 3 para começar no terceiro item)'),
            Field::make('complex', 'timeline_items', 'Itens da Timeline')
                ->add_fields([
                    Field::make('text', 'year', 'Ano')
                        ->set_required(true)
                        ->help_text('Ex: 2014, 2015, 2016...'),
                    Field::make('textarea', 'description', 'Descrição')
                        ->set_rows(4),
                ])
                ->set_layout('tabbed-horizontal')
                ->set_min(2)
                ->help_text('Adicione pelo menos 2 itens para criar a timeline'),
        ])
        ->set_render_callback(function ($block, $attributes) {
            if (empty($block['timeline_items'])) {
                return;
            }

            // Get additional classes and anchor from Gutenberg
            $className = isset($attributes['className']) ? $attributes['className'] : '';
            $anchor = isset($attributes['anchor']) ? $attributes['anchor'] : '';
            
            // Build classes array
            $classes = ['wp-block-timeline'];
            if (!empty($className)) {
                $classes[] = $className;
            }
            
            // Build attributes array
            $block_attributes = [];
            if (!empty($anchor)) {
                $block_attributes[] = 'id="' . esc_attr($anchor) . '"';
            }
            $block_attributes[] = 'class="' . esc_attr(implode(' ', $classes)) . '"';

            // Get initial slide (convert to 0-based index)
            $initial_slide = isset($block['initial_slide']) ? max(1, intval($block['initial_slide'])) - 1 : 0;
            $total_items = count($block['timeline_items']);
            
            // Ensure initial slide doesn't exceed available items
            if ($initial_slide >= $total_items) {
                $initial_slide = 0;
            }

           $return = ob_start();
?>
        <div <?php echo implode(' ', $block_attributes); ?>>
            <hr class="timeline-hr">
            <!-- Years Navigation Carousel -->
            <div class="timeline-years-wrapper">
                <div class="timeline-years">
                    <?php foreach ($block['timeline_items'] as $index => $item) : ?>
                        <div class="timeline-year-cell" data-index="<?php echo $index; ?>">
                            <div class="year-item <?php echo $index === $initial_slide ? 'is-active' : ''; ?>">
                                <div class="year-circle"></div>
                                <span class="year"><?php echo esc_html($item['year']); ?></span>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- Content Carousel -->
            <div class="timeline-content-wrapper">
                <div class="timeline-content">
                    <?php foreach ($block['timeline_items'] as $index => $item) : ?>
                        <div class="timeline-content-cell" data-index="<?php echo $index; ?>">
                            <div class="timeline-item">
                                <?php if (!empty($item['image'])) : ?>
                                    <div class="timeline-image">
                                        <?php echo wp_get_attachment_image($item['image'], 'large'); ?>
                                    </div>
                                <?php endif; ?>
                                
                                <div class="timeline-text">
                                    <?php if (!empty($item['description'])) : ?>
                                        <div class="timeline-description">
                                            <?php echo wp_kses_post(wpautop($item['description'])); ?>
                                        </div>
                                    <?php endif; ?>
                                </div>
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
add_action('carbon_fields_register_fields', 'timeline_block');

function timeline_enqueue_assets()
{
    $should_enqueue = false;

    // 1. Verifica no post_content
    if (is_singular() && has_block('carbon-fields/timeline')) {
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
                                has_block('carbon-fields/timeline', $block['content'])
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
            'advanced-corretora-timeline',
            get_template_directory_uri() . '/dist/js/timeline.js',
            array(),
            "?nocache=" . time(),
            array(
                'strategy' => 'defer',
                'in_footer' => true,
            )
        );
        wp_enqueue_script('advanced-corretora-timeline');
        
        // Add type="module" to timeline script
        add_filter('script_loader_tag', function($tag, $handle, $src) {
            if ('advanced-corretora-timeline' === $handle) {
                $tag = str_replace('<script ', '<script type="module" ', $tag);
            }
            return $tag;
        }, 10, 3);

        // Enqueue Flickity CSS (global function prevents duplicates)
        enqueue_flickity_css_once();
    }
}
add_action('wp_enqueue_scripts', 'timeline_enqueue_assets');
