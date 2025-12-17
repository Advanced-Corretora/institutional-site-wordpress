<?php
/**
 * The template for displaying archive pages
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package advanced-corretora
 */

get_header();
?>

<main id="primary" class="site-main blog-layout archive-layout">

	<?php if ( have_posts() ) : ?>

		<header class="archive-header">
			<div class="container">
				<?php
				the_archive_title( '<h1 class="page-title">', '</h1>' );
				the_archive_description( '<div class="archive-description">', '</div>' );
				?>
			</div>
		</header><!-- .page-header -->

		<?php
		// Get all posts for custom layout
		$paged = get_query_var('paged') ? get_query_var('paged') : 1;
		
		// Use the current query for archives
		global $wp_query;
		$all_posts = $wp_query;

		if ($all_posts->have_posts()) :
			$post_count = 0;
			
			// Hero Post (First/Featured Post) - Full Width
			if ($all_posts->have_posts()) :
				$all_posts->the_post();
				$post_count++;
				?>
				<section class="blog-hero">
					<?php get_template_part('template-parts/content', 'hero-post'); ?>
				</section>
				<?php
			endif;

			// Two Card Posts (50% each)
			if ($all_posts->have_posts()) :
				?>
				<section class="blog-cards">
					<div class="container">
						<div class="blog-cards__grid">
							<?php
							$card_count = 0;
							while ($all_posts->have_posts() && $card_count < 2) :
								$all_posts->the_post();
								$post_count++;
								$card_count++;
								get_template_part('template-parts/content', 'card-post');
							endwhile;
							?>
						</div>
					</div>
				</section>
				<?php
			endif;

			// Remaining Posts with Sidebar
			if ($all_posts->have_posts()) :
				?>
				<section class="blog-feed">
					<div class="container">
						<div class="blog-feed__layout">
							<div class="blog-feed__content">
								<?php
								while ($all_posts->have_posts()) :
									$all_posts->the_post();
									$post_count++;
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
			endif;

		else :
			get_template_part( 'template-parts/content', 'none' );
		endif;
		?>

	<?php else : ?>

		<?php get_template_part( 'template-parts/content', 'none' ); ?>

	<?php endif; ?>

</main><!-- #main -->

<?php
get_footer();
