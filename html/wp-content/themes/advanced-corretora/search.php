<?php

/**
 * The template for displaying search results pages
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#search-result
 *
 * @package advanced-corretora
 */

get_header();
?>

<div class="container">
	<main id="primary" class="site-main search-main">

		<?php if (have_posts()) : ?>

			<header class="search-header">
				<h1 class="search-title">Resultado de busca</h1>

				<!-- Formulário de busca -->
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

				<!-- Contador de resultados -->
				<div class="search-results-count">
					<?php
					global $wp_query;
					$total_results = $wp_query->found_posts;
					$search_query = get_search_query();

					if ($total_results == 1) {
						printf('Exibindo 1 de 1 resultado para "<strong>%s</strong>"', esc_html($search_query));
					} else {
						printf('Exibindo %d resultados para "<strong>%s</strong>"', $total_results, esc_html($search_query));
					}
					?>
				</div>
			</header><!-- .search-header -->

			<div class="search-results">
				<?php
				/* Start the Loop */
				while (have_posts()) :
					the_post();

					/**
					 * Run the loop for the search to output the results.
					 * Using a custom search card template.
					 */
					get_template_part('template-parts/content', 'search-card');

				endwhile;
				?>
			</div><!-- .search-results -->

			<?php
			// Paginação
			the_posts_navigation(array(
				'prev_text' => __('&larr; Anterior', 'advanced-corretora'),
				'next_text' => __('Próximo &rarr;', 'advanced-corretora'),
			));
			?>

		<?php else : ?>

			<header class="search-header">
				<h1 class="search-title">Resultado de busca</h1>

				<!-- Formulário de busca -->
				<div class="search-form-wrapper">
					<form role="search" method="get" class="search-form" action="<?php echo esc_url(home_url('/')); ?>">
						<label>
							<span class="screen-reader-text"><?php echo _x('Search for:', 'label', 'advanced-corretora'); ?></span>
							<input type="search" class="search-field" placeholder="<?php echo esc_attr_x('Buscar...', 'placeholder', 'advanced-corretora'); ?>" value="<?php echo get_search_query(); ?>" name="s" />
						</label>
						<button type="submit" class="search-submit">
							<?php
							echo file_get_contents(get_template_directory() . '/assets/icons/icon_lupa.svg');
							?>
						</button>
					</form>
				</div>

				<div class="search-results-count">
					<?php
					$search_query = get_search_query();
					printf('Nenhum resultado encontrado para "<strong>%s</strong>"', esc_html($search_query));
					?>
				</div>
			</header><!-- .search-header -->

			<?php get_template_part('template-parts/content', 'none'); ?>

		<?php endif; ?>

	</main><!-- #main -->
</div><!-- .container -->

<?php
get_footer();
