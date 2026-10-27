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
				?>
				<div class="category-pagination-wrapper">
					<?php
					the_posts_pagination(
						array(
							'mid_size'  => 2,
							'prev_text' => sprintf(
								'%s <span>%s</span>',
								'<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><polyline points="15 18 9 12 15 6"></polyline></svg>',
								esc_html__( 'Previous', 'krypton' )
							),
							'next_text' => sprintf(
								'<span>%s</span> %s',
								esc_html__( 'Next', 'krypton' ),
								'<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><polyline points="9 18 15 12 9 6"></polyline></svg>'
							),
						)
					);
					?>
				</div>
				<?php
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
