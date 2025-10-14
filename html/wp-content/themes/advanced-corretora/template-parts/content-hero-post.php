<?php
/**
 * Template part for displaying hero post (featured post)
 *
 * @package advanced-corretora
 */

?>

<article id="post-<?php the_ID(); ?>" <?php post_class('hero-post'); ?>>
	<div class="hero-post__background">
		<?php if (has_post_thumbnail()) : ?>
			<div class="hero-post__image">
				<?php the_post_thumbnail('full'); ?>
			</div>
		<?php endif; ?>
		
		<div class="hero-post__overlay">
			<div class="container">
				<div class="hero-post__content">
					<?php
					// Get primary category from Yoast SEO or first category
					$primary_category = '';
					if (class_exists('WPSEO_Primary_Term')) {
						$wpseo_primary_term = new WPSEO_Primary_Term('category', get_the_ID());
						$primary_term_id = $wpseo_primary_term->get_primary_term();
						if ($primary_term_id) {
							$primary_category = get_term($primary_term_id);
						}
					}
					
					// Fallback to first category if no primary category
					if (empty($primary_category)) {
						$categories = get_the_category();
						if (!empty($categories)) {
							$primary_category = $categories[0];
						}
					}
					
					if (!empty($primary_category)) : ?>
						<div class="hero-post__category">
							<a href="<?php echo esc_url(get_category_link($primary_category->term_id)); ?>">
								<?php echo esc_html($primary_category->name); ?>
							</a>
						</div>
					<?php endif; ?>
					
					<h1 class="hero-post__title">
						<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
					</h1>
					
					<?php if (has_excerpt()) : ?>
						<div class="hero-post__excerpt">
							<?php the_excerpt(); ?>
						</div>
					<?php endif; ?>
					
					<div class="hero-post__meta">
						<time class="hero-post__date" datetime="<?php echo esc_attr(get_the_date('c')); ?>">
							<?php echo get_the_date(); ?>
						</time>
						<span class="hero-post__author">
							por <?php the_author(); ?>
						</span>
					</div>
				</div>
			</div>
		</div>
	</div>
</article>
