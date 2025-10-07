<?php
/**
 * Template part for displaying card posts (2 posts at 50% each)
 *
 * @package advanced-corretora
 */

?>

<article id="post-<?php the_ID(); ?>" <?php post_class('card-post'); ?>>
	<?php if (has_post_thumbnail()) : ?>
		<div class="card-post__image">
			<a href="<?php the_permalink(); ?>">
				<?php the_post_thumbnail('medium_large'); ?>
			</a>
		</div>
	<?php endif; ?>
	
	<div class="card-post__content">
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
			<div class="card-post__category">
				<a href="<?php echo esc_url(get_category_link($primary_category->term_id)); ?>">
					<?php echo esc_html($primary_category->name); ?>
				</a>
			</div>
		<?php endif; ?>
		
		<h2 class="card-post__title">
			<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
		</h2>
		
		<?php if (has_excerpt()) : ?>
			<div class="card-post__excerpt">
				<?php the_excerpt(); ?>
			</div>
		<?php endif; ?>
		
		<div class="card-post__meta">
			<time class="card-post__date" datetime="<?php echo esc_attr(get_the_date('c')); ?>">
				<?php echo get_the_date(); ?>
			</time>
		</div>
	</div>
</article>
