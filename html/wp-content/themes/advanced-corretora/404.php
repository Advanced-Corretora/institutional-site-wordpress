<?php

/**
 * The template for displaying 404 pages (not found)
 *
 * @link https://codex.wordpress.org/Creating_an_Error_404_Page
 *
 * @package advanced-corretora
 */

get_header();
?>

<main id="primary" class="site-main blog-layout page-404-error">

	<!-- Hero Section da 404 -->
	<section class="blog-hero">
		<?php
		// Campos do Carbon Fields para 404
		$hero_bg_image_id = carbon_get_theme_option('404_hero_background');
		$hero_bg_image = $hero_bg_image_id ? wp_get_attachment_image_url($hero_bg_image_id, 'full') : '';
		$hero_overlay = carbon_get_theme_option('404_hero_overlay') ?: 'medium';
		$gradient_start = carbon_get_theme_option('404_hero_gradient_start') ?: '#003366';
		$gradient_end = carbon_get_theme_option('404_hero_gradient_end') ?: '#00B3E8';

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
						<span>Erro 404</span>
					</div>

					<h1 class="hero-post__title">
						Página não encontrada
					</h1>
				</div>
			</div>
		</div>
	</section>

	<!-- Conteúdo da 404 -->
	<section class="page-content-section">
		<div class="container">
			<div class="page-content-full-width">
				<div class="error-404-content">
					<div class="error-404-message">
						<p>Parece que não conseguimos encontrar o que você estava procurando. Talvez a pesquisa possa ajudar.</p>
					</div>

					<div class="search-form-wrapper">
						<form role="search" method="get" class="search-form" action="<?php echo esc_url(home_url('/')); ?>">
							<button type="submit" class="search-submit">
								<?php
								echo file_get_contents(get_template_directory() . '/assets/icons/icon_lupa.svg');
								?>
							</button>
							<label>
								<span class="screen-reader-text"><?php echo _x('Search for:', 'label', 'advanced-corretora'); ?></span>
								<input type="search" class="search-field" placeholder="<?php echo esc_attr_x('Buscar...', 'placeholder', 'advanced-corretora'); ?>" value="<?php echo get_search_query(); ?>" name="s" />
							</label>

						</form>
					</div>

					<div class="error-404-actions">
						<a href="<?php echo esc_url(home_url('/')); ?>" class="btn-home">
							Retornar para a página inicial
						</a>
					</div>
				</div>
			</div>
		</div>
	</section>

</main><!-- #main -->

<?php
get_footer();
