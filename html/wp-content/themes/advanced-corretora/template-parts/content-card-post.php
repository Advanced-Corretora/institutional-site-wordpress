<?php
/**
 * Template part for displaying card posts (2 posts at 50% each)
 *
 * @package advanced-corretora
 */

?>

<article id="post-<?php the_ID(); ?>" <?php post_class('card-post'); ?> onclick="window.location='<?php the_permalink(); ?>'">
	<div class="card-post__image">
		<?php the_post_thumbnail('medium_large'); ?>
		<div class="card-post__overlay"></div>

		<div class="card-post__content">
			<div class="card-post__category">
				<?php
					$category = get_the_category();
					if ($category) {
						$category_link = get_category_link($category[0]->term_id);
						echo '<a href="' . esc_url($category_link) . '">' . esc_html($category[0]->name) . '</a>';
					}
				?>
			</div>
			<h2 class="card-post__title">
				<?php the_title(); ?>
			</h2>
			<div class="card-post__excerpt">
				<?php
					$excerpt = get_the_excerpt();
					$excerpt = wp_trim_words($excerpt, 25, '...');
					echo esc_html($excerpt);
				?>
			</div>
		</div>
	</div>
</article>

