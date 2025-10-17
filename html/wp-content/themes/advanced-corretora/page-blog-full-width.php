<?php
/**
 * Template Name: Blog Full Width
 * 
 * Template para páginas genéricas com layout similar ao de categoria,
 * mas sem sidebar e com título da página no hero.
 *
 * @package advanced-corretora
 */

get_header();
?>

<main id="primary" class="site-main blog-layout page-blog-full-width">

	<?php while (have_posts()) : the_post(); ?>

		<!-- Hero Section da Página -->
		<section class="blog-hero">
			<?php
			// Campos do Carbon Fields para páginas (se existirem)
			$hero_bg_image_id = carbon_get_post_meta(get_the_ID(), 'page_hero_background');
			$hero_bg_image = $hero_bg_image_id ? wp_get_attachment_image_url($hero_bg_image_id, 'full') : '';
			$hero_overlay = carbon_get_post_meta(get_the_ID(), 'page_hero_overlay') ?: 'medium';
			$gradient_start = carbon_get_post_meta(get_the_ID(), 'page_hero_gradient_start') ?: '#003366';
			$gradient_end = carbon_get_post_meta(get_the_ID(), 'page_hero_gradient_end') ?: '#00B3E8';
			$custom_description = carbon_get_post_meta(get_the_ID(), 'page_custom_description');

			// Se não há campos personalizados, usa valores padrão
			if (empty($hero_bg_image) && empty($custom_description)) {
				$gradient_start = '#003366';
				$gradient_end = '#00B3E8';
				$hero_overlay = 'medium';
			}

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
							<span>Página</span>
						</div>

						<h1 class="hero-post__title">
							<?php the_title(); ?>
						</h1>
					</div>
				</div>
			</div>
		</section>

		<!-- Conteúdo da Página -->
		<section class="page-content-section">
			<div class="container">
				<div class="page-content-full-width">
					<article id="post-<?php the_ID(); ?>" <?php post_class('page-content'); ?>>
						<div class="single-post-content">
							<?php
							the_content();

							wp_link_pages(array(
								'before' => '<div class="page-links">' . esc_html__('Pages:', 'advanced-corretora'),
								'after'  => '</div>',
							));
							?>
						</div><!-- .entry-content -->
					</article><!-- #post-<?php the_ID(); ?> -->

					<?php
					// Se os comentários estão abertos ou temos pelo menos um comentário, carrega o template de comentários
					if (comments_open() || get_comments_number()) :
						comments_template();
					endif;
					?>
				</div>
			</div>
		</section>

	<?php endwhile; // End of the loop. ?>

</main><!-- #main -->

<?php
get_footer();
