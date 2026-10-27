<?php
/**
 * Main template file for Krypton theme.
 *
 * @package Krypton
 */

get_header();
?>

<main id="primary" class="site-main single-post-wrapper">
	<div class="krypton-container single-body-container page-body-container">
		<div class="entry-content single-article-content font-inter">
			<?php
			if ( have_posts() ) :
				while ( have_posts() ) :
					the_post();
					the_content();
				endwhile;
			else :
				?>
				<p><?php esc_html_e( 'No content found.', 'krypton' ); ?></p>
				<?php
			endif;
			?>
		</div>
	</div>
</main>

<?php
get_footer();
