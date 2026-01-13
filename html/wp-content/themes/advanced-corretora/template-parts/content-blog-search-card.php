<?php
/**
 * Template part for blog search result cards
 * Layout específico para resultados de busca do blog
 *
 * @package advanced-corretora
 */

$post_type = get_post_type();
?>

<article id="post-<?php the_ID(); ?>" <?php post_class('blog-search-card'); ?>>
	<?php if (has_post_thumbnail()) : ?>
		<div class="blog-search-card__image">
			<a href="<?php echo esc_url(get_permalink()); ?>">
				<?php the_post_thumbnail('medium', array('loading' => 'lazy')); ?>
			</a>
		</div>
	<?php endif; ?>
	
	<div class="blog-search-card__content">
		<!-- Meta informações -->
		<div class="blog-search-card__meta">
			<span class="blog-search-card__date">
				📅 <?php echo get_the_date('d/m/Y'); ?>
			</span>
			
			<?php 
			// Categoria primária (Yoast SEO) ou primeira categoria
			$primary_category = '';
			if (class_exists('WPSEO_Primary_Term')) {
				$wpseo_primary_term = new WPSEO_Primary_Term('category', get_the_id());
				$primary_category_id = $wpseo_primary_term->get_primary_term();
				if ($primary_category_id) {
					$primary_category = get_term($primary_category_id);
				}
			}
			
			if (!$primary_category) {
				$categories = get_the_category();
				if (!empty($categories)) {
					$primary_category = $categories[0];
				}
			}
			
			if ($primary_category) : ?>
				<span class="blog-search-card__category">
					🏷️ <a href="<?php echo esc_url(get_category_link($primary_category->term_id)); ?>">
						<?php echo esc_html($primary_category->name); ?>
					</a>
				</span>
			<?php endif; ?>
		</div>
		
		<!-- Título -->
		<h2 class="blog-search-card__title">
			<a href="<?php echo esc_url(get_permalink()); ?>" rel="bookmark">
				<?php the_title(); ?>
			</a>
		</h2>
		
		<!-- Excerpt -->
		<?php if (has_excerpt() || get_the_content()) : ?>
			<div class="blog-search-card__excerpt">
				<?php 
				if (has_excerpt()) {
					echo wp_trim_words(get_the_excerpt(), 20, '...');
				} else {
					echo wp_trim_words(get_the_content(), 20, '...');
				}
				?>
			</div>
		<?php endif; ?>
		
		<!-- CTA -->
		<div class="blog-search-card__footer">
			<a href="<?php echo esc_url(get_permalink()); ?>" class="blog-search-card__cta">
				Ler mais
				<svg width="16" height="16" viewBox="0 0 24 24" fill="none">
					<path d="M5 12H19M19 12L12 5M19 12L12 19" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
				</svg>
			</a>
			
			<div class="blog-search-card__reading-time">
				⏱️ <?php echo ceil(str_word_count(get_the_content()) / 200); ?> min de leitura
			</div>
		</div>
	</div>
</article><!-- #post-<?php the_ID(); ?> -->
