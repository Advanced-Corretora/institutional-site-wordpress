<?php
/**
 * Template part for displaying featured posts (sticky posts)
 *
 * @package advanced-corretora
 */

// Query for sticky posts
$featured_posts = new WP_Query(array(
    'post__in' => get_option('sticky_posts'),
    'post__not_in' => array(get_the_ID()), // Exclude current post
    'posts_per_page' => 2,
    'orderby' => 'date',
    'order' => 'DESC',
    'ignore_sticky_posts' => 1
));

if (!$featured_posts->have_posts() || empty(get_option('sticky_posts'))) {
    return;
}
?>

<section class="featured-posts">
    <div class="container">
        <h2 class="featured-posts__title">Posts <span>em Destaque</span></h2>
        
        <div class="card-grid">
            <?php while ($featured_posts->have_posts()) : $featured_posts->the_post(); ?>
                <article class="card-post">
                    <div class="card-post__image">
                        <?php if (has_post_thumbnail()) : ?>
                            <a href="<?php the_permalink(); ?>">
                                <?php the_post_thumbnail('large'); ?>
                            </a>
                        <?php endif; ?>
                        <div class="card-post__overlay"></div>
                        <div class="card-post__content">
                            <?php
                            // Get primary category from Yoast SEO or first category
                            $primary_category = '';
                            if (class_exists('WPSEO_Primary_Term')) {
                                $wpseo_primary_term = new WPSEO_Primary_Term('category', get_the_ID());
                                $primary_term_id = $wpseo_primary_term->get_primary_term();
                                if ($primary_term_id) {
                                    $primary_category = get_term($primary_term_id);
                                }
                            }

                            // Fallback to first category if no primary category
                            if (empty($primary_category)) {
                                $categories = get_the_category();
                                if (!empty($categories)) {
                                    $primary_category = $categories[0];
                                }
                            }

                            if (!empty($primary_category)) : ?>
                                <div class="card-post__category">
                                    <a href="<?php echo esc_url(get_category_link($primary_category->term_id)); ?>">
                                        <?php echo esc_html($primary_category->name); ?>
                                    </a>
                                </div>
                            <?php endif; ?>

                            <h3 class="card-post__title">
                                <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                            </h3>

                            <div class="card-post__excerpt">
                                <?php
                                $excerpt = get_the_excerpt();
                                $excerpt = wp_trim_words($excerpt, 20, '...');
                                echo esc_html($excerpt);
                                ?>
                            </div>
                        </div>
                    </div>
                </article>
            <?php endwhile; ?>
        </div>
    </div>
</section>

<?php wp_reset_postdata(); ?>
