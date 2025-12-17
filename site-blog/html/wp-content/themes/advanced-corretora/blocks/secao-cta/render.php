<?php
/**
 * Render template for Seção CTA block
 *
 * @package advanced-corretora
 */

// Get block attributes
$title = $attributes['title'] ?? '';
$subtitle = $attributes['subtitle'] ?? '';
$cta_text = $attributes['ctaText'] ?? '';
$cta_link = $attributes['ctaLink'] ?? '#';
$image = $attributes['image'] ?? null;
$text_alignment = $attributes['textAlignment'] ?? 'left';
$background_color = $attributes['backgroundColor'] ?? '#525151';
$text_color = $attributes['textColor'] ?? '#333333';
$button_color = $attributes['buttonColor'] ?? 'transparent';
$button_text_color = $attributes['buttonTextColor'] ?? '#ffffff';
$background_image = $attributes['backgroundImage'] ?? null;
$overlay_opacity = $attributes['overlayOpacity'] ?? 0.5;
$overlay_color = $attributes['overlayColor'] ?? '#000000';

// Build inline styles
$inline_styles = [];
$inline_styles[] = 'color: ' . esc_attr($text_color);
$inline_styles[] = 'position: relative';

if ($background_image && isset($background_image['url'])) {
    $inline_styles[] = 'background-color: ' . esc_attr($background_color);
    $inline_styles[] = 'background-image: url(' . esc_url($background_image['url']) . ')';
    $inline_styles[] = 'background-size: auto 100%';
    $inline_styles[] = 'background-position: right';
    $inline_styles[] = 'background-repeat: no-repeat';
} else {
    $inline_styles[] = 'background-color: ' . esc_attr($background_color);
}

$style_attr = implode('; ', $inline_styles);
?>

<div class="wp-block-advanced-corretora-secao-cta align-<?php echo esc_attr($text_alignment); ?>" style="<?php echo esc_attr($style_attr); ?>">
    <?php /*if ($background_image && isset($background_image['url'])) : ?>
        <div style="position: absolute; top: 0; left: 0; right: 0; bottom: 0; background-color: <?php echo esc_attr($overlay_color); ?>; opacity: <?php echo esc_attr($overlay_opacity); ?>; pointer-events: none;"></div>
    <?php endif; */?>
    
    <div class="container">
        <div class="cta-content">
            <div class="cta-text">
                <?php if (!empty($title)) : ?>
                    <h2 class="cta-title" style="color: <?php echo esc_attr($text_color); ?>;">
                        <?php echo wp_kses_post($title); ?>
                    </h2>
                <?php endif; ?>

                <?php if (!empty($subtitle)) : ?>
                    <p class="cta-subtitle" style="color: <?php echo esc_attr($text_color); ?>;">
                        <?php echo wp_kses_post($subtitle); ?>
                    </p>
                <?php endif; ?>

                <?php if (!empty($cta_text)) : ?>
                    <div class="cta-button-wrapper">
                        <a href="<?php echo esc_url($cta_link); ?>" 
                           class="cta-button" 
                           style="background-color: <?php echo esc_attr($button_color); ?>; color: <?php echo esc_attr($button_text_color); ?>;">
                            <?php echo esc_html($cta_text); ?>
                        </a>
                    </div>
                <?php endif; ?>
            </div>

            <div class="cta-image">
                <?php if ($image && isset($image['url'])) : ?>
                    <img src="<?php echo esc_url($image['url']); ?>" 
                         alt="<?php echo esc_attr($image['alt'] ?? ''); ?>" 
                         class="cta-image-element">
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
