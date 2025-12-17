<?php

/**
 * The template for displaying tag archive pages
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package advanced-corretora
 */

get_header();
?>

<main id="primary" class="site-main blog-layout tag-layout">

	<?php if (have_posts()) : ?>

		<!-- Hero Section da Tag -->
		<section class="blog-hero">
			<?php
			$tag = get_queried_object();
			$posts_count = $tag->count;

			// Campos do Carbon Fields
			$hero_bg_image_id = carbon_get_term_meta($tag->term_id, 'tag_hero_background');
			$hero_bg_image = $hero_bg_image_id ? wp_get_attachment_image_url($hero_bg_image_id, 'full') : '';
			$hero_overlay = carbon_get_term_meta($tag->term_id, 'tag_hero_overlay') ?: 'medium';
			$gradient_start = carbon_get_term_meta($tag->term_id, 'tag_hero_gradient_start') ?: '#003366';
			$gradient_end = carbon_get_term_meta($tag->term_id, 'tag_hero_gradient_end') ?: '#00B3E8';
			$custom_description = carbon_get_term_meta($tag->term_id, 'tag_custom_description');
			$posts_count_text = carbon_get_term_meta($tag->term_id, 'tag_posts_count_text') ?: 'posts';

			// Overlay opacity
			$overlay_opacity = [
				'light' => '0.2',
				'medium' => '0.4',
				'dark' => '0.6',
				'heavy' => '0.8'
			][$hero_overlay];
			?>

			<div class="hero-post" style="height: 550px;">
				<div class="hero-post__background"
					style="<?php echo $hero_bg_image ? 'background-image: url(' . esc_url($hero_bg_image) . ');' : 'background: linear-gradient(135deg, ' . esc_attr($gradient_start) . ' 0%, ' . esc_attr($gradient_end) . ' 100%);'; ?>">
					<div class="hero-post__overlay" style="background: rgba(0, 0, 0, <?php echo esc_attr($overlay_opacity); ?>);"></div>
				</div>

				<div class="container">
					<div class="hero-post__content">
						<div class="hero-post__category">
							<a href="<?php echo esc_url(get_tag_link($tag->term_id)); ?>">Tag</a>
						</div>

						<h1 class="hero-post__title">
							<a href="<?php echo esc_url(get_tag_link($tag->term_id)); ?>">
								<?php echo esc_html($tag->name); ?>
							</a>
						</h1>

						<?php if ($custom_description || $tag->description) : ?>
							<div class="hero-post__excerpt">
								<?php echo wp_kses_post($custom_description ?: $tag->description); ?>
							</div>
						<?php endif; ?>
						<?php
						/*
						<div class="hero-post__meta">
							<?php echo $posts_count; ?> <?php echo $posts_count_text; ?> <?php echo $posts_count != 1 ? 'com esta tag' : 'com esta tag'; ?>
						</div>
						*/
						?>
					</div>
				</div>
			</div>
		</section>

		<?php
		// Get all posts for feed layout
		$paged = get_query_var('paged') ? get_query_var('paged') : 1;

		// Use the current query for tag archives
		global $wp_query;
		$all_posts = $wp_query;

		if ($all_posts->have_posts()) :
		?>
			<section class="blog-feed">
				<div class="container">
					<div class="blog-feed__layout">
						<div class="blog-feed__content">
							<?php
							while ($all_posts->have_posts()) :
								$all_posts->the_post();
								get_template_part('template-parts/content', 'list-post');
							endwhile;
							?>

							<?php
							// Custom pagination
							$pagination_args = array(
								'total' => $all_posts->max_num_pages,
								'current' => max(1, get_query_var('paged')),
								'format' => '?paged=%#%',
								'show_all' => false,
								'end_size' => 1,
								'mid_size' => 2,
								'prev_next' => true,
								'prev_text' => '<',
								'next_text' => '>',
								'type' => 'plain',
								'add_args' => false,
								'add_fragment' => '',
							);
							?>
							<div class="blog-pagination">
								<?php echo paginate_links($pagination_args); ?>
							</div>
						</div>

						<aside class="blog-feed__sidebar">
							<?php get_sidebar(); ?>
						</aside>
					</div>
				</div>
			</section>
		<?php

		else :
			get_template_part('template-parts/content', 'none');
		endif;
		?>

	<?php else : ?>

		<?php get_template_part('template-parts/content', 'none'); ?>

	<?php endif; ?>

</main><!-- #main -->

<?php
get_footer();
