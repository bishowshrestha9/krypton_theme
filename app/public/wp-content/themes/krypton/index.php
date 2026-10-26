<?php
/**
 * Main template file for Krypton theme.
 *
 * @package Krypton
 */

get_header();
?>

<main id="primary" class="site-main krypton-container">
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
</main>

<?php
get_footer();
