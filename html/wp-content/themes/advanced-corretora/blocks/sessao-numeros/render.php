<?php
/**
 * Sessão Números Block Template
 *
 * @see https://github.com/WordPress/gutenberg/blob/trunk/docs/reference-guides/block-api/block-metadata.md#render
 */

// Get block attributes
$title = $attributes['title'] ?? 'Título da Seção';
$subtitle = $attributes['subtitle'] ?? 'Subtítulo da seção';
$numbers = $attributes['numbers'] ?? [
    ['number' => '100+', 'label' => 'Clientes Satisfeitos'],
    ['number' => '50+', 'label' => 'Projetos Concluídos'],
    ['number' => '5+', 'label' => 'Anos de Experiência']
];
$text_alignment = $attributes['textAlignment'] ?? 'center';
$background_color = $attributes['backgroundColor'] ?? '#f8f9fa';
$text_color = $attributes['textColor'] ?? '#333333';
$number_color = $attributes['numberColor'] ?? '#007cba';
$background_image = $attributes['backgroundImage'] ?? null;
$overlay_opacity = $attributes['overlayOpacity'] ?? 0.5;
$overlay_color = $attributes['overlayColor'] ?? '#000000';

$inline_styles = [];
$inline_styles[] = 'color: ' . esc_attr($text_color);
// $inline_styles[] = 'padding: 60px 20px';
// $inline_styles[] = 'text-align: ' . esc_attr($text_alignment);
$inline_styles[] = 'position: relative';

if ($background_image && isset($background_image['url'])) {
    $inline_styles[] = 'background-image: url(' . esc_url($background_image['url']) . ')';
    $inline_styles[] = 'background-size: cover';
    $inline_styles[] = 'background-position: center';
    $inline_styles[] = 'background-repeat: no-repeat';
    $inline_styles[] = 'background-color: transparent';
} else {
    $inline_styles[] = 'background-color: ' . esc_attr($background_color);
}

$wrapper_attributes = get_block_wrapper_attributes([
    'class' => 'sessao-numeros-block align-' . $text_alignment,
    'style' => implode('; ', $inline_styles) . ';'
]);
?>

<div <?php echo $wrapper_attributes; ?>>
    <?php if ($background_image && isset($background_image['url'])) : ?>
        <div style="position: absolute; top: 0; left: 0; right: 0; bottom: 0; background-color: <?php echo esc_attr($overlay_color); ?>; opacity: <?php echo esc_attr($overlay_opacity); ?>; pointer-events: none;"></div>
    <?php endif; ?>
    <div class="container">
        <?php if (!empty($title)) : ?>
            <h2 class="section-title" style="color: <?php echo esc_attr($text_color); ?>;">
                <?php echo wp_kses_post($title); ?>
            </h2>
        <?php endif; ?>
        
        <?php if (!empty($subtitle)) : ?>
            <p class="section-subtitle" style="color: <?php echo esc_attr($text_color); ?>;">
                <?php echo wp_kses_post($subtitle); ?>
            </p>
        <?php endif; ?>
        
        <?php if (!empty($numbers) && is_array($numbers)) : ?>
            <div class="numbers-grid">
                <?php foreach ($numbers as $index => $item) : ?>
                    <?php if (isset($item['number']) && isset($item['label'])) : ?>
                        <div class="number-item" style="animation-delay: <?php echo esc_attr(($index + 1) * 0.1); ?>s; animation-fill-mode: both;">
                            <div class="number" style="color: <?php echo esc_attr($number_color); ?>; display: block;">
                                <?php echo esc_html($item['number']); ?>
                            </div>
                            <div class="label" style="color: <?php echo esc_attr($text_color); ?>;">
                                <?php echo esc_html($item['label']); ?>
                            </div>
                        </div>
                    <?php endif; ?>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</div>
