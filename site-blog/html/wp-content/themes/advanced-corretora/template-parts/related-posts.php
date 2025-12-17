<?php

/**
 * Template part for displaying related posts
 *
 * @package advanced-corretora
 */

// Get current post categories
$categories = get_the_category();
if (empty($categories)) {
    return;
}

$category_ids = array();
foreach ($categories as $category) {
    $category_ids[] = $category->term_id;
}

// Query for related posts
$related_posts = new WP_Query(array(
    'category__in' => $category_ids,
    'post__not_in' => array(get_the_ID()),
    'posts_per_page' => 4,
    'orderby' => 'rand'
));

if (!$related_posts->have_posts()) {
    return;
}
?>

<section class="related-posts related-posts--single">
    <div class="container">
        <h2 class="related-posts__title">Posts <span>Relacionados</span></h2>

        <div class="card-grid--related">
            <?php while ($related_posts->have_posts()) : $related_posts->the_post(); ?>
                <article id="post-<?php the_ID(); ?>" <?php post_class('list-post'); ?>>
                    <div class="list-post__container">
                        <?php if (has_post_thumbnail()) : ?>
                            <div class="list-post__image">
                                <a href="<?php the_permalink(); ?>">
                                    <?php the_post_thumbnail('medium'); ?>
                                </a>
                            </div>
                        <?php endif; ?>

                        <div class="list-post__content">
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
                                <div class="list-post__category">
                                    <a href="<?php echo esc_url(get_category_link($primary_category->term_id)); ?>">
                                        <?php echo esc_html($primary_category->name); ?>
                                    </a>
                                </div>
                            <?php endif; ?>

                            <h3 class="list-post__title">
                                <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                            </h3>

                            <div class="list-post__excerpt">
                                <?php
                                $excerpt = get_the_excerpt();
                                $excerpt = wp_trim_words($excerpt, 25, '...');
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