<?php
/**
 * Template part for displaying search results as cards
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package advanced-corretora
 */

// Detectar tipo de busca
$search_type = isset($_GET['search_type']) ? sanitize_text_field($_GET['search_type']) : 'default';
$post_type = get_post_type();

// Adicionar classe específica do tipo de post
$card_classes = ['search-card', 'search-card--' . $post_type];
if ($search_type !== 'default') {
    $card_classes[] = 'search-card--filtered';
}
?>

<article id="post-<?php the_ID(); ?>" <?php post_class($card_classes); ?>>
	<div class="search-card-content">
		<div class="search-card-left">
			<div class="search-card-meta">
				<div class="search-card-type">
					<?php 
					switch($post_type) {
						case 'post':
							echo '<span class="post-type-badge post-type-badge--post">📝 Post</span>';
							break;
						case 'page':
							echo '<span class="post-type-badge post-type-badge--page">📄 Página</span>';
							break;
						case 'product':
							echo '<span class="post-type-badge post-type-badge--product">🛍️ Produto</span>';
							break;
						default:
							echo '<span class="post-type-badge post-type-badge--default">' . esc_html(get_post_type_object($post_type)->labels->singular_name) . '</span>';
					}
					?>
				</div>
				
				<div class="search-card-slug">
					<?php 
					// Exibe o slug da URL
					$permalink = get_permalink();
					$home_url = home_url('/');
					$slug = str_replace($home_url, '', $permalink);
					$slug = rtrim($slug, '/'); // Remove trailing slash
					echo esc_html($slug ? $slug : 'home');
					?>
				</div>
			</div>
			
			<h2 class="search-card-title">
				<a href="<?php echo esc_url(get_permalink()); ?>" rel="bookmark">
					<?php the_title(); ?>
				</a>
			</h2>
			
			<?php if (has_excerpt() || get_the_content()) : ?>
				<div class="search-card-excerpt">
					<?php 
					if (has_excerpt()) {
						the_excerpt();
					} else {
						echo wp_trim_words(get_the_content(), 20, '...');
					}
					?>
				</div>
			<?php endif; ?>
		</div>
		
		<div class="search-card-right">
			<a href="<?php echo esc_url(get_permalink()); ?>" class="search-card-cta">
				Ver mais
				<svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
					<path d="M5 12H19M19 12L12 5M19 12L12 19" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
				</svg>
			</a>
		</div>
	</div>
</article><!-- #post-<?php the_ID(); ?> -->
