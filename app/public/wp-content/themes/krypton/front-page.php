<?php
/**
 * The template for displaying the front page.
 *
 * @package Krypton
 */

get_header();

// Retrieve Customizer settings with defaults.
$hero_headline    = get_theme_mod( 'krypton_hero_headline', __( 'Evidence-Based Supplement Guides & Real-World Diaries', 'krypton' ) );
$hero_subheadline = get_theme_mod( 'krypton_hero_subheadline', __( 'Explore science-backed supplement reviews, ingredient deconstructions, and clinical insights reviewed by medical professionals.', 'krypton' ) );
$hero_bg_image    = get_theme_mod( 'krypton_hero_bg_image', '' );
?>

<section class="krypton-hero-section">
	<div class="krypton-container hero-container-inner">
		<!-- Left Column: Content, Search & Features -->
		<div class="hero-left-column">
			<h1 class="hero-headline">
				<?php echo esc_html( $hero_headline ); ?>
			</h1>

			<p class="hero-subheadline">
				<?php echo esc_html( $hero_subheadline ); ?>
			</p>

			<div class="hero-search-wrapper">
				<?php get_search_form(); ?>
			</div>

			<!-- Three Feature Blocks with Divider Lines -->
			<div class="hero-features-row">
				<!-- Feature 1 -->
				<div class="hero-feature-item">
					<div class="feature-icon-circle">
						<svg class="feature-svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
							<path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path>
							<polyline points="9 12 11 14 15 10"></polyline>
						</svg>
					</div>
					<span class="feature-label"><?php esc_html_e( 'Find the best products', 'krypton' ); ?></span>
				</div>

				<div class="hero-feature-divider" aria-hidden="true"></div>

				<!-- Feature 2 -->
				<div class="hero-feature-item">
					<div class="feature-icon-circle">
						<svg class="feature-svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
							<polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon>
						</svg>
					</div>
					<span class="feature-label"><?php esc_html_e( 'Up-to-Date Reviews', 'krypton' ); ?></span>
				</div>

				<div class="hero-feature-divider" aria-hidden="true"></div>

				<!-- Feature 3 -->
				<div class="hero-feature-item">
					<div class="feature-icon-circle">
						<svg class="feature-svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
							<path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"></path>
							<path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"></path>
						</svg>
					</div>
					<span class="feature-label"><?php esc_html_e( 'Stay Informed', 'krypton' ); ?></span>
				</div>
			</div>
		</div>

		<!-- Right Column: Background Container & Latest Post Card -->
		<div class="hero-right-column">
			<!-- Background Image Frame -->
			<div class="hero-right-bg<?php echo $hero_bg_image ? ' has-custom-bg' : ''; ?>"<?php echo $hero_bg_image ? ' style="background-image: url(' . esc_url( $hero_bg_image ) . ');"' : ''; ?>>
			</div>

			<!-- Dynamic Latest Post Card Overlay -->
			<?php
			$latest_post_args = array(
				'posts_per_page'      => 1,
				'post_status'         => 'publish',
				'ignore_sticky_posts' => 1,
			);
			$latest_post_query = new WP_Query( $latest_post_args );

			if ( $latest_post_query->have_posts() ) :
				while ( $latest_post_query->have_posts() ) :
					$latest_post_query->the_post();
					$post_thumbnail_id = get_post_thumbnail_id();
					$read_time         = function_exists( 'krypton_reading_time' ) ? krypton_reading_time( get_the_ID() ) : 15;
					?>
					<article class="hero-featured-card">
						<div class="hero-card-media-wrapper">
							<a href="<?php the_permalink(); ?>" class="hero-card-img-link" tabindex="-1" aria-hidden="true">
								<?php if ( has_post_thumbnail() ) : ?>
									<?php the_post_thumbnail( 'medium_large', array( 'class' => 'hero-card-thumbnail', 'alt' => the_title_attribute( array( 'echo' => false ) ) ) ); ?>
								<?php else : ?>
									<!-- Clean Fallback Product Visual Matching Mockup -->
									<div class="hero-card-placeholder-media">
										<div class="hero-card-placeholder-bottle">
											<span class="bottle-badge">100% Natural</span>
											<span class="bottle-title">COLLAGEN</span>
										</div>
									</div>
								<?php endif; ?>
							</a>
						</div>

						<div class="hero-card-content">
							<div class="hero-card-meta-row">
								<span class="hero-meta-date">
									<svg class="meta-icon" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
										<rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
										<line x1="16" y1="2" x2="16" y2="6"></line>
										<line x1="8" y1="2" x2="8" y2="6"></line>
										<line x1="3" y1="10" x2="21" y2="10"></line>
									</svg>
									<?php echo esc_html( get_the_date( 'F j, Y' ) ); ?>
								</span>

								<span class="hero-meta-readtime">
									<svg class="meta-icon" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
										<circle cx="12" cy="12" r="10"></circle>
										<polyline points="12 6 12 12 16 14"></polyline>
									</svg>
									<?php echo esc_html( sprintf( __( '%d min read', 'krypton' ), $read_time ) ); ?>
								</span>
							</div>

							<h3 class="hero-card-title">
								<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
							</h3>

							<div class="hero-card-footer">
								<span class="hero-card-author">
									<?php echo esc_html( get_the_author() ); ?>
								</span>
								<a href="<?php the_permalink(); ?>" class="hero-card-read-link">
									<?php esc_html_e( 'Read Article', 'krypton' ); ?>
								</a>
							</div>
						</div>
					</article>
					<?php
				endwhile;
				wp_reset_postdata();
			else :
				// Fallback Card Matching the Figma Mockup when no post exists yet
				?>
				<article class="hero-featured-card">
					<div class="hero-card-media-wrapper">
						<a href="#" class="hero-card-img-link" tabindex="-1" aria-hidden="true">
							<div class="hero-card-placeholder-media">
								<div class="hero-card-placeholder-bottle">
									<span class="bottle-badge">100% Natural</span>
									<span class="bottle-title">COLLAGEN</span>
								</div>
							</div>
						</a>
					</div>

					<div class="hero-card-content">
						<div class="hero-card-meta-row">
							<span class="hero-meta-date">
								<svg class="meta-icon" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
									<rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
									<line x1="16" y1="2" x2="16" y2="6"></line>
									<line x1="8" y1="2" x2="8" y2="6"></line>
									<line x1="3" y1="10" x2="21" y2="10"></line>
								</svg>
								<?php echo esc_html( gmdate( 'F j, Y' ) ); ?>
							</span>

							<span class="hero-meta-readtime">
								<svg class="meta-icon" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
									<circle cx="12" cy="12" r="10"></circle>
									<polyline points="12 6 12 12 16 14"></polyline>
								</svg>
								<?php esc_html_e( '20 min read', 'krypton' ); ?>
							</span>
						</div>

						<h3 class="hero-card-title">
							<a href="#"><?php esc_html_e( 'Lipo Flow Review: A Positive, Evidence-Aware Look at This Liquid Metabolism Formula', 'krypton' ); ?></a>
						</h3>

						<div class="hero-card-footer">
							<span class="hero-card-author">
								<?php esc_html_e( 'Dr. Emma Myers', 'krypton' ); ?>
							</span>
							<a href="#" class="hero-card-read-link">
								<?php esc_html_e( 'Read Article', 'krypton' ); ?>
							</a>
						</div>
					</div>
				</article>
				<?php
			endif;
			?>
		</div>
	</div>
</section>

<?php
get_footer();
