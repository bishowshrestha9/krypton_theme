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

<!-- ==========================================================================
     1. Hero Section
     ========================================================================== -->
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

				<div class="hero-feature-item">
					<div class="feature-icon-circle">
						<svg class="feature-svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
							<polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon>
						</svg>
					</div>
					<span class="feature-label"><?php esc_html_e( 'Up-to-Date Reviews', 'krypton' ); ?></span>
				</div>

				<div class="hero-feature-divider" aria-hidden="true"></div>

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

		<!-- Right Column: Model Image (old.svg) & Dynamic Latest Post Card -->
		<div class="hero-right-column">
			<div class="hero-model-scene">
				<img 
					src="<?php echo esc_url( $hero_bg_image ? $hero_bg_image : get_theme_file_uri( 'images/old.svg' ) ); ?>" 
					alt="<?php echo esc_attr( $hero_headline ); ?>" 
					class="hero-model-img"
				/>

				<?php
				$latest_post_query = new WP_Query(
					array(
						'posts_per_page'      => 1,
						'post_status'         => 'publish',
						'ignore_sticky_posts' => 1,
					)
				);

				if ( $latest_post_query->have_posts() ) :
					while ( $latest_post_query->have_posts() ) :
						$latest_post_query->the_post();
						$read_time = function_exists( 'krypton_reading_time' ) ? krypton_reading_time( get_the_ID() ) : 5;
						?>
						<article class="hero-featured-card">
							<div class="hero-card-media-wrapper">
								<a href="<?php the_permalink(); ?>" class="hero-card-img-link" tabindex="-1" aria-hidden="true">
									<?php if ( has_post_thumbnail() ) : ?>
										<?php the_post_thumbnail( 'medium_large', array( 'class' => 'hero-card-thumbnail' ) ); ?>
									<?php else : ?>
										<div class="hero-card-placeholder-media">
											<div class="hero-card-placeholder-bottle">
												<div class="bottle-inner-cap"></div>
												<div class="bottle-inner-body">
													<div class="bottle-inner-label">
														<span class="bottle-inner-badge"><?php bloginfo( 'name' ); ?></span>
														<span class="bottle-inner-name"><?php esc_html_e( 'TRIPLE COLLAGEN', 'krypton' ); ?></span>
														<span class="bottle-inner-sub"><?php esc_html_e( '100% Natural Formula', 'krypton' ); ?></span>
													</div>
												</div>
											</div>
										</div>
									<?php endif; ?>
								</a>
							</div>

							<div class="hero-card-content">
								<div class="hero-card-meta-row">
									<span class="hero-meta-date">
										<svg class="meta-icon" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg>
										<?php echo esc_html( get_the_date( 'F j, Y' ) ); ?>
									</span>
									<span class="hero-meta-readtime">
										<svg class="meta-icon" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>
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
				endif;
				?>
			</div>
		</div>
	</div>
</section>

<!-- ==========================================================================
     2. Clinical Categories Section (100% Dynamic from WordPress Categories)
     ========================================================================== -->
<?php
// Query WordPress categories dynamically
$clinical_categories = get_categories(
	array(
		'taxonomy'   => 'category',
		'hide_empty' => false,
		'exclude'    => array( 1 ), // Exclude default Uncategorized
		'orderby'    => 'id',
		'order'      => 'ASC',
		'number'     => 12,
	)
);

// Fallback to all categories if custom categories are empty
if ( empty( $clinical_categories ) ) {
	$clinical_categories = get_categories(
		array(
			'taxonomy'   => 'category',
			'hide_empty' => false,
			'number'     => 12,
		)
	);
}

// View all URL linking to posts page or categories
$posts_page_id = (int) get_option( 'page_for_posts' );
$view_all_link = $posts_page_id ? get_permalink( $posts_page_id ) : home_url( '/category/' );

if ( ! empty( $clinical_categories ) ) :
?>
<section id="categories" class="krypton-categories-section">
	<div class="krypton-container">
		<!-- Section Header -->
		<div class="categories-section-header">
			<div class="header-title-group">
				<h2 class="categories-section-title">
					<?php echo wp_kses_post( __( 'Explore Our<br>Clinical Categories', 'krypton' ) ); ?>
				</h2>
			</div>

			<a href="<?php echo esc_url( $view_all_link ); ?>" class="categories-view-all-link">
				<?php esc_html_e( 'View All', 'krypton' ); ?>
			</a>
		</div>

		<!-- 4-Column Responsive Grid -->
		<div class="clinical-categories-grid">
			<?php foreach ( $clinical_categories as $index => $category ) : ?>
				<a href="<?php echo esc_url( get_category_link( $category->term_id ) ); ?>" class="clinical-category-card<?php echo ( 0 === $index ) ? ' is-highlighted' : ''; ?>">
					<!-- Circular Icon / Avatar Wrapper -->
					<div class="category-icon-wrapper">
						<?php
						if ( function_exists( 'krypton_render_category_icon' ) ) {
							krypton_render_category_icon( $category );
						}
						?>
					</div>

					<!-- Dynamic Category Name -->
					<h3 class="category-card-title">
						<?php echo esc_html( $category->name ); ?>
					</h3>

					<!-- Dynamic Category Description -->
					<?php if ( ! empty( $category->description ) ) : ?>
						<p class="category-card-description">
							<?php echo esc_html( $category->description ); ?>
						</p>
					<?php endif; ?>

					<!-- Action Arrow at Bottom Right -->
					<div class="category-card-footer">
						<span class="category-action-arrow" aria-hidden="true">
							<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
								<line x1="5" y1="12" x2="19" y2="12"></line>
								<polyline points="12 5 19 12 12 19"></polyline>
							</svg>
						</span>
					</div>
				</a>
			<?php endforeach; ?>
		</div>
	</div>
</section>
<?php endif; ?>

<?php
get_footer();
