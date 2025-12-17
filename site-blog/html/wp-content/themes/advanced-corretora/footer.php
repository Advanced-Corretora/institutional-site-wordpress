<?php

/**
 * The template for displaying the footer
 *
 * Contains the closing of the #content div and all content after.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package advanced-corretora
 */

?>

<footer class="site-footer">
	<div class="container">
		<div class="footer-section footer-menu-section">
			<?php
			// Seção 1: Menu Hierárquico
			if (has_nav_menu('footer-menu')) : ?>
				<div class="footer-menu-columns">
					<?php
					wp_nav_menu(array(
						'theme_location' => 'footer-menu',
						'menu_class'     => 'footer-menu',
						'container'      => false,
						'depth'          => 2,
						'walker'         => new Footer_Menu_Walker(),
					));
					?>
				</div>
			<?php endif; ?>
			<?php
			// Seção 2: Redes Sociais
			$social_networks = carbon_get_theme_option('footer_social_networks');
			if (! empty($social_networks)) : ?>
				<div class="footer-section footer-social-section">
					<p class="footer-social-title">Siga nas redes sociais</p>
					<div class="social-networks">
						<?php foreach ($social_networks as $social) :
							if (! empty($social['social_icon']) && ! empty($social['social_link'])) :
								$social_icon_url = wp_get_attachment_image_url($social['social_icon'], 'full');
								if ($social_icon_url) : ?>
									<a href="<?php echo esc_url($social['social_link']); ?>"
										target="_blank"
										rel="noopener noreferrer"
										class="social-link">
										<img src="<?php echo esc_url($social_icon_url); ?>"
											alt="<?php echo esc_attr($social['social_name'] ?? 'Rede Social'); ?>"
											class="social-icon">
									</a>
						<?php endif;
							endif;
						endforeach; ?>
					</div>
				</div>
			<?php endif; ?>
		</div>


		<!-- Linha divisória -->
		<div class="footer-divider"></div>

		<?php
		// Seção 3: Unidades
		$units = carbon_get_theme_option('footer_units');
		if (! empty($units)) : ?>
			<div class="footer-section footer-units-section">
				<p class="footer-units-title">Unidades de atendimento</p>
				<div class="units-container">
					<?php foreach ($units as $unit) :
						if (! empty($unit['unit_title']) && ! empty($unit['unit_phone'])) : ?>
							<div class="unit-item">
								<div class="unit-title">
									<?php echo wp_kses_post($unit['unit_title']); ?>
								</div>
								<div class="unit-phone">
									<?php echo wp_kses_post($unit['unit_phone']); ?>
								</div>
							</div>
					<?php endif;
					endforeach; ?>
				</div>
			</div>
		<?php endif; ?>

		<div class="footer-divider"></div>

		<?php
		// Seção 4: Selos e Política de Privacidade
		$seals = carbon_get_theme_option('footer_seals');
		$privacy_btn1_text = carbon_get_theme_option('privacy_button1_text');
		$privacy_btn1_link = carbon_get_theme_option('privacy_button1_link');
		$privacy_btn2_text = carbon_get_theme_option('privacy_button2_text');
		$privacy_btn2_link = carbon_get_theme_option('privacy_button2_link');

		if (! empty($seals) || ! empty($privacy_btn1_text) || ! empty($privacy_btn2_text)) : ?>
			<div class="footer-section footer-seals-section">

				<?php if (! empty($seals)) : ?>
					<div class="seals-container">
						<?php foreach ($seals as $seal) :
							if (! empty($seal['seal_image'])) :
								$seal_image_url = wp_get_attachment_image_url($seal['seal_image'], 'full');
								if ($seal_image_url) : ?>
									<div class="seal-item">
										<?php if (! empty($seal['seal_title'])) : ?>
											<p class="seal-title"><?php echo esc_html($seal['seal_title']); ?></p>
										<?php endif; ?>
										<?php if (! empty($seal['seal_link'])) : ?>
											<a href="<?php echo esc_url($seal['seal_link']); ?>" target="_blank" rel="noopener noreferrer">
											<?php endif; ?>

											<img src="<?php echo esc_url($seal_image_url); ?>"
												alt="<?php echo esc_attr($seal['seal_title'] ?? 'Selo'); ?>"
												class="seal-image">

											<?php if (! empty($seal['seal_link'])) : ?>
											</a>
										<?php endif; ?>
									</div>
						<?php endif;
							endif;
						endforeach; ?>
						<?php if (! empty($privacy_btn1_text) || ! empty($privacy_btn2_text)) : ?>
							<div class="privacy-policy-section">
								<p class="privacy-policy-title">Política de Privacidade</p>
								<?php if (! empty($privacy_btn1_text)) : ?>
									<a href="<?php echo esc_url($privacy_btn1_link); ?>" class="privacy-button">
										<?php echo esc_html($privacy_btn1_text); ?>
									</a>
								<?php endif; ?>

								<?php if (! empty($privacy_btn2_text)) : ?>
									<a href="<?php echo esc_url($privacy_btn2_link); ?>" class="privacy-button">
										<?php echo esc_html($privacy_btn2_text); ?>
									</a>
								<?php endif; ?>
							</div>
						<?php endif; ?>
					</div>
				<?php endif; ?>

			</div>
		<?php endif; ?>

		<div class="footer-divider"></div>

		<?php
		// Seção 5: Logos Advanced Grupo
		$advanced_logos = carbon_get_theme_option('footer_advanced_logos');
		if (! empty($advanced_logos)) : ?>
			<div class="footer-section footer-logos-section">
				<div class="advanced-logos-container">
					<?php foreach ($advanced_logos as $logo) :
						if (! empty($logo['logo_image'])) :
							$logo_image_url = wp_get_attachment_image_url($logo['logo_image'], 'full');
							if ($logo_image_url) :
								$mobile_class = ! empty($logo['logo_mobile_enabled']) ? 'show-mobile' : 'hide-mobile';
					?>
								<div class="advanced-logo-item <?php echo esc_attr($mobile_class); ?>">
									<img src="<?php echo esc_url($logo_image_url); ?>"
										alt="Logo Advanced Grupo"
										class="advanced-logo">
								</div>
					<?php endif;
						endif;
					endforeach; ?>
				</div>
			</div>
		<?php endif; ?>

		<?php
		// Seção 6: Informações Finais
		$copyright = carbon_get_theme_option('footer_copyright');
		$cnpj = carbon_get_theme_option('footer_cnpj');
		$address = carbon_get_theme_option('footer_address');

		if (! empty($copyright) || ! empty($cnpj) || ! empty($address)) : ?>
			<div class="footer-section footer-final-section">
				<div class="footer-final-info">
					<div class="footer-final-info-left">

						<?php if (! empty($copyright)) : ?>
							<div class="footer-copyright">
								<?php echo esc_html($copyright); ?>
							</div>
						<?php endif; ?>

						<?php if (! empty($cnpj)) : ?>
							<div class="footer-cnpj">
								<?php echo esc_html($cnpj); ?>
							</div>
						<?php endif; ?>

					</div>
					<div class="footer-final-info-right">
						<?php if (! empty($address)) : ?>
							<div class="footer-address">
								<?php echo wp_kses_post($address); ?>
							</div>
						<?php endif; ?>
					</div>
				</div>
			<?php endif; ?>

			<!-- Back to Top Button (Mobile) -->
			<div class="back-to-top-container">
				<button id="back-to-top" class="back-to-top-btn" aria-label="Voltar ao topo">
					<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
						<path d="M12 19V5M5 12L12 5L19 12" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
					</svg>
					<span class="back-to-top-text">Voltar ao topo</span>
				</button>
			</div>

		</div><!-- .container -->
</footer><!-- #colophon -->

<!-- Back to Top Button (Desktop Fixed) -->
<button id="back-to-top-desktop" class="back-to-top-btn back-to-top-desktop" aria-label="Voltar ao topo">
	<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
		<path d="M12 19V5M5 12L12 5L19 12" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
	</svg>
</button>
</div><!-- #page -->

<?php wp_footer(); ?>

</body>

</html>