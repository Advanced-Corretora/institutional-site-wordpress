<?php
/**
 * The template for displaying all single posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package advanced-corretora
 */

get_header();
?>

<?php while ( have_posts() ) : the_post(); ?>

	<!-- Hero Banner -->
	<section class="single-hero">
		<div class="single-hero__container">
			<?php if ( has_post_thumbnail() ) : ?>
				<img src="<?php echo esc_url( get_the_post_thumbnail_url( get_the_ID(), 'full' ) ); ?>" 
					 alt="<?php echo esc_attr( get_the_title() ); ?>" 
					 class="single-hero__image">
			<?php endif; ?>
			<div class="single-hero__overlay"></div>
			<div class="single-hero__content">
				<div class="container">
					<div class="single-hero__info">
						<?php 
						// Categoria primária (Yoast SEO) ou primeira categoria
						$primary_category = '';
						if ( class_exists( 'WPSEO_Primary_Term' ) ) {
							$wpseo_primary_term = new WPSEO_Primary_Term( 'category', get_the_ID() );
							$primary_term_id = $wpseo_primary_term->get_primary_term();
							if ( $primary_term_id ) {
								$primary_category = get_term( $primary_term_id );
							}
						}
						
						if ( ! $primary_category ) {
							$categories = get_the_category();
							if ( ! empty( $categories ) ) {
								$primary_category = $categories[0];
							}
						}
						
						if ( $primary_category ) : ?>
							<span class="single-hero__category">
								<a href="<?php echo esc_url( get_category_link( $primary_category->term_id ) ); ?>">
									<?php echo esc_html( $primary_category->name ); ?>
								</a>
							</span>
						<?php endif; ?>
						
						<h1 class="single-hero__title"><?php the_title(); ?></h1>
						
						<?php if ( has_excerpt() ) : ?>
							<div class="single-hero__excerpt">
								<?php the_excerpt(); ?>
							</div>
						<?php endif; ?>
					</div>
				</div>
			</div>
		</div>
	</section>

	<!-- Content Area -->
	<main class="single-main">
		<div class="container">
			<div class="single-content-wrapper">
				
				<!-- Content Area -->
				<div class="single-content">
					
					<!-- Author and Date + Share Area -->
					<div class="single-meta-row">
						<div class="single-author-date">
							<span class="single-author">
								Por <?php echo esc_html( get_the_author() ); ?>
							</span>
							-
							<span class="single-date">
								<?php echo get_the_date( 'd \d\e F \d\e Y' ); ?>
							</span>
						</div>
						
						<div class="single-share-top">
							<?php get_template_part( 'template-parts/social-share' ); ?>
						</div>
					</div>
					
					<!-- Post Content -->
					<div class="single-post-content">
						<?php the_content(); ?>
					</div>
					
					<!-- Share Area Bottom -->
					<div class="single-share-bottom">
						<?php get_template_part( 'template-parts/social-share' ); ?>
					</div>
					
				</div>
				
				<!-- Sidebar -->
				<aside class="single-sidebar">
					<?php get_sidebar(); ?>
				</aside>
				
			</div>
		</div>
	</main>

<?php endwhile; ?>

<?php get_footer();
