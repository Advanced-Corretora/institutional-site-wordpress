<?php

/**
 * Top header template
 */
?>
<header class="header">

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

        <div class="menu-container">
            <div class="mobile-search">
                <div class="search-input-container">
                    <input type="search" class="mobile-search-input" placeholder="">
                    <span class="mobile-search-icon">
                        <?php
                        echo file_get_contents(get_template_directory() . '/assets/icons/icon_lupa.svg');
                        ?>
                    </span>
                </div>
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
                            'depth' => 3
                        )
                    );
                }
                ?>
                <?php
                $cta_text = carbon_get_theme_option('crb_header_cta_text');
                $cta_url = carbon_get_theme_option('crb_header_cta_url');
                $cta_target = carbon_get_theme_option('crb_header_cta_target') ?: '_self';
                if ($cta_text && $cta_url) {
                ?>
                    <div class="cta">
                        <a href="<?php echo esc_url($cta_url); ?>" class="link" target="<?php echo esc_attr($cta_target); ?>"><?php echo esc_attr($cta_text); ?></a>
                    </div>
                <?php
                }
                ?>
            </div>
        </div>



        <div class="deskButtonArea">
            <div class="search">
                <button class="search-button">
                    <span class="search-icon">
                        <?php
                        echo file_get_contents(get_template_directory() . '/assets/icons/icon_lupa.svg');
                        ?>
                    </span>
                </button>
                <div class="search-overlay">
                    <div class="search-container">
                        <input type="text" class="search-input" placeholder="Digite sua busca...">
                        <button class="search-close" aria-label="Fechar busca">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <line x1="18" y1="6" x2="6" y2="18"></line>
                                <line x1="6" y1="6" x2="18" y2="18"></line>
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
            <div class="cta">
                <a href="<?php echo esc_url($cta_url); ?>" class="link" target="<?php echo esc_attr($cta_target); ?>"><?php echo esc_attr($cta_text); ?></a>
            </div>
        </div>

        <button class="hamburger-menu" aria-label="Menu" aria-expanded="false">
            <span class="hamburger-icon">
                <?php
                echo file_get_contents(get_template_directory() . '/assets/icons/icon_menu.svg');
                ?>
            </span>
        </button>
    </div>

</header>