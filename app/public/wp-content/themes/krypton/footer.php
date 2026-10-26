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
		<div class="krypton-container site-footer-inner">
			<div class="footer-bottom">
				<p class="copyright-text">
					&copy; <?php echo esc_html( gmdate( 'Y' ) ); ?> <?php bloginfo( 'name' ); ?>. <?php esc_html_e( 'All rights reserved.', 'krypton' ); ?>
				</p>
			</div>
		</div>
	</footer>
</div><!-- #page -->

<?php wp_footer(); ?>
</body>
</html>
