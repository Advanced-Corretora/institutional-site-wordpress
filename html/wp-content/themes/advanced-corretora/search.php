<?php

/**
 * The template for displaying search results pages
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#search-result
 *
 * @package advanced-corretora
 */

get_header();

// Detectar tipo de busca
$search_type = isset($_GET['search_type']) ? sanitize_text_field($_GET['search_type']) : 'default';

// Modificar query baseado no tipo de busca
if ($search_type !== 'default') {
    add_action('pre_get_posts', function($query) use ($search_type) {
        if (!is_admin() && $query->is_main_query() && $query->is_search()) {
            switch($search_type) {
                case 'posts':
                    $query->set('post_type', 'post');
                    break;
                case 'pages':
                    $query->set('post_type', 'page');
                    break;
                case 'products':
                    $query->set('post_type', 'product'); // Se usar WooCommerce
                    break;
            }
        }
    });
}
?>

<div class="container">
	<main id="primary" class="site-main search-main search-type-<?php echo esc_attr($search_type); ?>">

		<?php if (have_posts()) : ?>

			<header class="search-header">
				<h1 class="search-title">
					<?php 
					switch($search_type) {
						case 'posts':
							echo 'Resultado de busca - Posts';
							break;
						case 'pages':
							echo 'Resultado de busca - Páginas';
							break;
						case 'products':
							echo 'Resultado de busca - Produtos';
							break;
						default:
							echo 'Resultado de busca';
					}
					?>
				</h1>

				<!-- Filtros de tipo de busca -->
				<div class="search-type-filters">
					<?php $current_search = get_search_query(); ?>
					<a href="<?php echo esc_url(home_url('/?s=' . urlencode($current_search))); ?>" 
					   class="search-filter <?php echo $search_type === 'default' ? 'active' : ''; ?>">
						Todos
					</a>
					<a href="<?php echo esc_url(home_url('/?s=' . urlencode($current_search) . '&search_type=posts')); ?>" 
					   class="search-filter <?php echo $search_type === 'posts' ? 'active' : ''; ?>">
						Posts
					</a>
					<a href="<?php echo esc_url(home_url('/?s=' . urlencode($current_search) . '&search_type=pages')); ?>" 
					   class="search-filter <?php echo $search_type === 'pages' ? 'active' : ''; ?>">
						Páginas
					</a>
				</div>

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
						<?php if ($search_type !== 'default') : ?>
							<input type="hidden" name="search_type" value="<?php echo esc_attr($search_type); ?>" />
						<?php endif; ?>

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
