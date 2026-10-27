<?php
/**
 * The template for displaying all pages.
 *
 * @package Krypton
 */

get_header();
?>

<main id="primary" class="site-main page-wrapper single-post-wrapper">
	<?php
	while ( have_posts() ) :
		the_post();
		?>
		<article id="post-<?php the_ID(); ?>" <?php post_class( 'krypton-page' ); ?>>
			<header class="entry-header page-header single-post-header">
				<div class="krypton-container single-header-container">
					<?php the_title( '<h1 class="entry-title page-title single-post-title font-montserrat">', '</h1>' ); ?>
				</div>
			</header>

			<div class="krypton-container single-body-container page-body-container">
				<?php if ( has_post_thumbnail() ) : ?>
					<div class="single-featured-image-wrapper page-featured-image">
						<?php the_post_thumbnail( 'large', array( 'class' => 'single-featured-img' ) ); ?>
					</div>
				<?php endif; ?>

				<div class="entry-content single-article-content font-inter">
					<?php
					the_content();

					wp_link_pages(
						array(
							'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'krypton' ),
							'after'  => '</div>',
						)
					);
					?>
				</div>
			</div>
		</article>
		<?php
	endwhile;
	?>
</main>

<?php
get_footer();
