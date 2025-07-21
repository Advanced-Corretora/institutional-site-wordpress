<?php

/**
 * Top header template
 */
?>
<header class="header">
    <div class="container">
        <div class="content">
            <div class="logo">
                <?php
                if (has_custom_logo()) {
                    echo '<div class="logo-image">';
                    the_custom_logo();
                    echo '</div>';
                } else {
                    if (is_front_page() && is_home()) : ?>
                        <h1 class="logo-text">
                            <a href="<?php echo esc_url(home_url('/')); ?>" rel="home" class="header__logo__link"><?php bloginfo('name'); ?></a>
                        </h1>
                    <?php else : ?>
                        <div class="logo-text">
                            <a href="<?php echo esc_url(home_url('/')); ?>" rel="home" class="header__logo__link"><?php bloginfo('name'); ?></a>
                        </div>
                    <?php endif; ?>
                <?php } ?>
            </div>

            <div class="menu">
                <?php
                if (has_nav_menu('top-menu')) {
                    wp_nav_menu(
                        array(
                            'theme_location' => 'top-menu',
                            'menu_class' => 'list',
                            'container' => false,
                            'fallback_cb' => false,
                            'items_wrap' => '<ul id="%1$s" class="%2$s">%3$s</ul>',
                            'link_class' => 'item__link',
                            'walker' => new Advanced_Corretora_Walker_Nav_Menu(),
                            'depth' => 2
                        )
                    );
                }
                ?>
            </div>
        </div>
</header>