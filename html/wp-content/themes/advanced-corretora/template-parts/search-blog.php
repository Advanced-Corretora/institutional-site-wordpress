<?php
/**
 * Template part for blog search results
 * Usado quando a URL contém /blog2/ ou blog.
 * Segue o mesmo layout dos archives: hero + feed com sidebar
 *
 * @package advanced-corretora
 */

// Detectar tipo de busca
$search_type = isset($_GET['search_type']) ? sanitize_text_field($_GET['search_type']) : 'default';
?>

<main id="primary" class="site-main search-main search-blog-context search-type-<?php echo esc_attr($search_type); ?> blog-layout">

		<?php if (have_posts()) : ?>

		<!-- Hero Section com informações da busca -->
		<section class="search-hero blog-hero">
			<?php 
			// Obter imagem de fundo personalizada do Carbon Fields
			$hero_bg_image = carbon_get_theme_option('blog_search_hero_background');
			$hero_overlay = carbon_get_theme_option('blog_search_hero_overlay') ?: 'medium';
			$gradient_start = carbon_get_theme_option('blog_search_hero_gradient_start') ?: '#003366';
			$gradient_end = carbon_get_theme_option('blog_search_hero_gradient_end') ?: '#00B3E8';
			
			// Definir intensidade do overlay
			$overlay_opacity = [
				'no-overlay' => '0',
				'light' => '0.2',
				'medium' => '0.4', 
				'dark' => '0.6',
				'heavy' => '0.8'
			][$hero_overlay];
			?>
			<div class="search-hero__background" 
				 style="<?php echo $hero_bg_image ? 'background-image: url(' . esc_url($hero_bg_image) . ');' : 'background: linear-gradient(135deg, ' . esc_attr($gradient_start) . ' 0%, ' . esc_attr($gradient_end) . ' 100%);'; ?>">
				<div class="search-hero__overlay" style="background: rgba(0, 0, 0, <?php echo esc_attr($overlay_opacity); ?>);"></div>
				<div class="search-hero__content">
					<div class="container">
						<div class="hero-post__content">
							<div class="hero-post__category">
								<a href="#semlink">Pesquisa</a>
							</div>
							
							<h1 class="hero-post__title">
								<a href="#">
									<?php 
									$search_query = get_search_query();
									printf('Resultado de busca - %s', esc_html($search_query));
									?>
								</a>
							</h1>
						</div>
					</div>
				</div>
			</div>
		</section>

		<!-- Filtros de busca -->
		<section class="search-filters">
			<div class="container">
				<div class="search-type-filters search-type-filters--blog">
					<?php $current_search = get_search_query(); ?>
					<a href="<?php echo esc_url(home_url('/?s=' . urlencode($current_search))); ?>" 
					   class="search-filter <?php echo $search_type === 'default' ? 'active' : ''; ?>">
						📝 Todos os Posts
					</a>
					<a href="<?php echo esc_url(home_url('/?s=' . urlencode($current_search) . '&search_type=posts')); ?>" 
					   class="search-filter <?php echo $search_type === 'posts' ? 'active' : ''; ?>">
						📰 Artigos
					</a>
				</div>
			</div>
		</section>

		<!-- Feed de Posts com Sidebar (mesmo layout do archive) -->
		<section class="blog-feed search-feed">
			<div class="container">
				<div class="blog-feed__layout">
					<div class="blog-feed__content">
						<!-- Header de busca (mesmo do institucional) -->
						<header class="search-header">
							<!-- Formulário de busca -->
							<div class="search-form-wrapper">
								<form role="search" method="get" class="search-form" action="<?php echo esc_url(home_url('/')); ?>">
									<div class="search-input-wrapper">
										<input type="search" class="search-field" 
											   placeholder="Buscar no blog..." 
											   value="<?php echo get_search_query(); ?>" 
											   name="s" />
										<button type="submit" class="search-submit">
											<svg width="20" height="20" viewBox="0 0 24 24" fill="none">
												<path d="M21 21L16.514 16.506M19 10.5C19 15.194 15.194 19 10.5 19S2 15.194 2 10.5 5.806 2 10.5 2 19 5.806 19 10.5Z" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
											</svg>
										</button>
									</div>
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
									printf('1 resultado encontrado para "<strong>%s</strong>"', esc_html($search_query));
								} else {
									printf('%d resultados encontrados para "<strong>%s</strong>"', $total_results, esc_html($search_query));
								}
								?>
							</div>
						</header><!-- .search-header -->
						
						<?php
						/* Start the Loop */
						while (have_posts()) :
							the_post();
							
							// Usa o mesmo template do feed do blog
							get_template_part('template-parts/content', 'list-post');

						endwhile;
						?>
						
						<?php
						// Paginação (mesmo padrão dos archives)
						$pagination_args = array(
							'total' => $wp_query->max_num_pages,
							'current' => max(1, get_query_var('paged')),
							'format' => '?paged=%#%&s=' . urlencode(get_search_query()),
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

	<?php else : ?>

		<!-- Hero Section para nenhum resultado -->
		<section class="search-hero blog-hero search-hero--no-results">
			<?php 
			// Usar mesmas configurações do hero com resultados
			$hero_bg_image = carbon_get_theme_option('blog_search_hero_background');
			$hero_overlay = carbon_get_theme_option('blog_search_hero_overlay') ?: 'medium';
			$gradient_start = carbon_get_theme_option('blog_search_hero_gradient_start') ?: '#003366';
			$gradient_end = carbon_get_theme_option('blog_search_hero_gradient_end') ?: '#00B3E8';
			
			// Overlay mais escuro para nenhum resultado
			$overlay_opacity = [
				'light' => '0.4',
				'medium' => '0.6', 
				'dark' => '0.8',
				'heavy' => '0.9'
			][$hero_overlay];
			?>
			<div class="search-hero__background" 
				 style="<?php echo $hero_bg_image ? 'background-image: url(' . esc_url($hero_bg_image) . ');' : 'background: linear-gradient(135deg, #6c757d 0%, #495057 100%);'; ?>">
				<div class="search-hero__overlay" style="background: rgba(0, 0, 0, <?php echo esc_attr($overlay_opacity); ?>);"></div>
				<div class="search-hero__content">
					<div class="container">
						<div class="hero-post__content">
							<div class="hero-post__category">
								<a href="#semlink">Pesquisa</a>
							</div>
							
							<h1 class="hero-post__title">
								<a href="#">
									<?php 
									$search_query = get_search_query();
									printf('Resultado de busca - %s', esc_html($search_query));
									?>
								</a>
							</h1>
						</div>
					</div>
				</div>
			</div>
		</section>

		<!-- Seção de sugestões -->
		<section class="search-suggestions-section">
			<div class="container">
				<div class="search-suggestions">
					<h2>💡 Sugestões para melhorar sua busca:</h2>
					<div class="suggestions-grid">
						<div class="suggestion-item">
							<h3>🔤 Verifique a ortografia</h3>
							<p>Certifique-se de que todas as palavras estão escritas corretamente.</p>
						</div>
						<div class="suggestion-item">
							<h3>🎯 Use termos mais gerais</h3>
							<p>Tente usar palavras-chave mais amplas ou sinônimos.</p>
						</div>
						<div class="suggestion-item">
							<h3>🔍 Nova busca</h3>
							<form role="search" method="get" class="search-form" action="<?php echo esc_url(home_url('/')); ?>">
								<div class="search-input-wrapper">
									<input type="search" class="search-field" 
										   placeholder="Tente outros termos..." 
										   name="s" />
									<button type="submit" class="search-submit">
										Buscar
									</button>
								</div>
								<?php if ($search_type !== 'default') : ?>
									<input type="hidden" name="search_type" value="<?php echo esc_attr($search_type); ?>" />
								<?php endif; ?>
							</form>
						</div>
						<div class="suggestion-item">
							<h3>🏠 Explorar o blog</h3>
							<p><a href="<?php echo home_url('/'); ?>" class="btn-back-blog">Voltar ao blog</a></p>
						</div>
					</div>
				</div>
			</div>
		</section>

		<?php endif; ?>

</main><!-- #main -->
