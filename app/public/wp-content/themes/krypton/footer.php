<?php
/**
 * The template for displaying the footer.
 *
 * Contains the closing of the #content div and all content up until </html>
 *
 * @package Krypton
 */

?>
	</div><!-- #content -->

	<footer id="colophon" class="site-footer" role="contentinfo">
		<div class="krypton-container footer-container-inner">
			
			<!-- Top Row: Big Headline, Pill CTA Button & Mission Statement -->
			<div class="footer-top-row">
				<div class="footer-top-left">
					<h2 class="footer-headline">
						<?php echo wp_kses_post( __( 'Know What<br>You’re Taking', 'krypton' ) ); ?>
					</h2>
					<a href="<?php echo esc_url( home_url( '/#special-offer' ) ); ?>" class="footer-pill-btn">
						<span class="pill-btn-label"><?php esc_html_e( 'Get your - 50% off', 'krypton' ); ?></span>
						<span class="pill-btn-icon" aria-hidden="true">
							<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
								<line x1="7" y1="17" x2="17" y2="7"></line>
								<polyline points="7 7 17 7 17 17"></polyline>
							</svg>
						</span>
					</a>
				</div>

				<div class="footer-top-right">
					<p class="footer-mission-statement">
						<?php esc_html_e( 'We investigate supplements, break down their claims, and help you understand what the evidence really says.', 'krypton' ); ?>
					</p>
				</div>
			</div>

			<!-- Subtle Divider Line -->
			<div class="footer-divider-rule" aria-hidden="true"></div>

			<!-- Bottom Grid: Brand Info, Links, Categories, Socials & Back to Top -->
			<div class="footer-main-grid">
				
				<!-- Column 1: Site Name & Contact Info -->
				<div class="footer-col footer-col-brand">
					<h3 class="footer-col-heading"><?php bloginfo( 'name' ); ?></h3>
					<ul class="footer-contact-details">
						<li>
							<span class="contact-lbl"><?php esc_html_e( 'Call:', 'krypton' ); ?></span>
							<a href="tel:+919288579300" class="contact-val">+91 - 9288579300</a>
						</li>
						<li>
							<span class="contact-lbl"><?php esc_html_e( 'WhatsApp:', 'krypton' ); ?></span>
							<a href="https://wa.me/919288579300" target="_blank" rel="noopener" class="contact-val">+91 - 9288579300</a>
						</li>
						<li>
							<span class="contact-lbl"><?php esc_html_e( 'Customer Support Time:', 'krypton' ); ?></span>
							<span class="contact-val"><?php esc_html_e( '24 / 7', 'krypton' ); ?></span>
						</li>
						<li>
							<span class="contact-lbl"><?php esc_html_e( 'Email:', 'krypton' ); ?></span>
							<a href="mailto:support@<?php echo esc_attr( wp_parse_url( home_url(), PHP_URL_HOST ) ?: 'sitename.com' ); ?>" class="contact-val">support@<?php echo esc_html( wp_parse_url( home_url(), PHP_URL_HOST ) ?: 'sitename.com' ); ?></a>
						</li>
					</ul>
				</div>

				<!-- Column 2: Links -->
				<div class="footer-col footer-col-links">
					<h3 class="footer-col-heading"><?php esc_html_e( 'Links', 'krypton' ); ?></h3>
					<ul class="footer-nav-menu">
						<li><a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Home', 'krypton' ); ?></a></li>
						<li><a href="<?php echo esc_url( get_permalink( get_option( 'page_for_posts' ) ) ?: home_url( '/#latest' ) ); ?>"><?php esc_html_e( 'Latest Articles', 'krypton' ); ?></a></li>
						<li><a href="<?php echo esc_url( home_url( '/ingredients/' ) ); ?>"><?php esc_html_e( 'Ingredients', 'krypton' ); ?></a></li>
						<li><a href="<?php echo esc_url( home_url( '/research/' ) ); ?>"><?php esc_html_e( 'Research', 'krypton' ); ?></a></li>
						<li><a href="<?php echo esc_url( home_url( '/about/' ) ); ?>"><?php esc_html_e( 'About Us', 'krypton' ); ?></a></li>
					</ul>
				</div>

				<!-- Column 3: Categories -->
				<div class="footer-col footer-col-categories">
					<h3 class="footer-col-heading"><?php esc_html_e( 'Categories', 'krypton' ); ?></h3>
					<ul class="footer-nav-menu">
						<?php
						$footer_categories = get_categories(
							array(
								'taxonomy'   => 'category',
								'hide_empty' => false,
								'exclude'    => array( 1 ),
								'number'     => 8,
							)
						);

						if ( ! empty( $footer_categories ) ) :
							foreach ( $footer_categories as $f_cat ) :
								?>
								<li>
									<a href="<?php echo esc_url( get_category_link( $f_cat->term_id ) ); ?>">
										<?php echo esc_html( $f_cat->name ); ?>
									</a>
								</li>
								<?php
							endforeach;
						else :
							?>
							<li><a href="<?php echo esc_url( home_url( '/category/male-enhancement/' ) ); ?>"><?php esc_html_e( 'Male Enhancement', 'krypton' ); ?></a></li>
							<li><a href="<?php echo esc_url( home_url( '/category/weight-loss/' ) ); ?>"><?php esc_html_e( 'Weight Loss', 'krypton' ); ?></a></li>
							<li><a href="<?php echo esc_url( home_url( '/category/brain-enhancement/' ) ); ?>"><?php esc_html_e( 'Brain Enhancement', 'krypton' ); ?></a></li>
							<li><a href="<?php echo esc_url( home_url( '/category/joint-pain-relief/' ) ); ?>"><?php esc_html_e( 'Joint Pain Relief', 'krypton' ); ?></a></li>
						<?php endif; ?>
					</ul>
				</div>

				<!-- Column 4: Socials -->
				<div class="footer-col footer-col-socials">
					<h3 class="footer-col-heading"><?php esc_html_e( 'Socials', 'krypton' ); ?></h3>
					<ul class="footer-nav-menu">
						<li><a href="https://facebook.com" target="_blank" rel="noopener"><?php esc_html_e( 'Facebook', 'krypton' ); ?></a></li>
						<li><a href="https://instagram.com" target="_blank" rel="noopener"><?php esc_html_e( 'Instagram', 'krypton' ); ?></a></li>
					</ul>
				</div>

				<!-- Floating Back to Top Button -->
				<div class="footer-scroll-top-wrapper">
					<button type="button" class="footer-scroll-top-btn" id="footerScrollTopBtn" aria-label="<?php esc_attr_e( 'Scroll to top', 'krypton' ); ?>">
						<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
							<line x1="12" y1="19" x2="12" y2="5"></line>
							<polyline points="5 12 12 5 19 12"></polyline>
						</svg>
					</button>
				</div>

			</div>

			<!-- Bottom Legal Bar Divider -->
			<div class="footer-bottom-divider" aria-hidden="true"></div>

			<!-- Bottom Legal Bar Row -->
			<div class="footer-legal-bar">
				<p class="footer-copyright-text">
					&copy; <?php echo esc_html( gmdate( 'Y' ) ); ?> <?php bloginfo( 'name' ); ?>. <?php esc_html_e( 'All rights reserved. Evidence-based supplement reviews & health journals.', 'krypton' ); ?>
				</p>

				<nav class="footer-legal-nav" aria-label="<?php esc_attr_e( 'Legal Links', 'krypton' ); ?>">
					<a href="<?php echo esc_url( home_url( '/privacy-policy/' ) ); ?>"><?php esc_html_e( 'Privacy Policy', 'krypton' ); ?></a>
					<a href="<?php echo esc_url( home_url( '/terms-of-service/' ) ); ?>"><?php esc_html_e( 'Terms of Service', 'krypton' ); ?></a>
					<a href="<?php echo esc_url( home_url( '/disclaimer/' ) ); ?>"><?php esc_html_e( 'Disclaimer', 'krypton' ); ?></a>
					<a href="<?php echo esc_url( home_url( '/contact/' ) ); ?>"><?php esc_html_e( 'Contact Us', 'krypton' ); ?></a>
				</nav>
			</div>

		</div>
	</footer>
</div><!-- #page -->

<script>
document.addEventListener('DOMContentLoaded', function() {
	var scrollBtn = document.getElementById('footerScrollTopBtn');
	if (scrollBtn) {
		scrollBtn.addEventListener('click', function() {
			window.scrollTo({
				top: 0,
				behavior: 'smooth'
			});
		});
	}
});
</script>

<?php wp_footer(); ?>
</body>
</html>
