<?php
/**
 * The main template file
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * E.g., it puts together the home page when no home.php file exists.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package advanced-corretora
 */

get_header();
?>

<main id="primary" class="site-main blog-layout">

	<?php
	if ( have_posts() ) :

		if ( is_home() && ! is_front_page() ) :
			?>
			<header class="blog-header">
				<div class="container">
					<h1 class="page-title"><?php single_post_title(); ?></h1>
				</div>
			</header>
			<?php
		endif;

		// Get all posts for custom layout
		$all_posts = new WP_Query(array(
			'post_type' => 'post',
			'posts_per_page' => get_option('posts_per_page'),
			'paged' => get_query_var('paged') ? get_query_var('paged') : 1,
			'post_status' => 'publish'
		));

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
						<div class="card-grid">
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

			wp_reset_postdata();

		else :
			get_template_part( 'template-parts/content', 'none' );
		endif;

	else :
		get_template_part( 'template-parts/content', 'none' );
	endif;
	?>

</main><!-- #main -->

<?php
get_footer();
