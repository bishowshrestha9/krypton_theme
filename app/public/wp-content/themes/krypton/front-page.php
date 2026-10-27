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
											<div class="hero-card-blank-placeholder">
												<svg class="placeholder-icon" width="38" height="38" viewBox="0 0 24 24" fill="none" stroke="#CBD5E1" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
													<rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect>
													<circle cx="8.5" cy="8.5" r="1.5"></circle>
													<polyline points="21 15 16 10 5 21"></polyline>
												</svg>
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
					<!-- Dynamic Category Name -->
					<h3 class="category-card-title">
						<?php echo esc_html( $category->name ); ?>
					</h3>

					<!-- Dynamic Category Description -->
					<?php
					$cat_desc = $category->description;
					if ( ! empty( $cat_desc ) ) {
						// Decode HTML entities if stored encoded (e.g. &lt;p&gt;)
						if ( false !== strpos( $cat_desc, '&lt;' ) && false !== strpos( $cat_desc, '&gt;' ) ) {
							$cat_desc = html_entity_decode( $cat_desc, ENT_QUOTES, 'UTF-8' );
						}
						// Strip all HTML tags completely (e.g. ChatGPT pasted markup)
						$cat_desc = wp_strip_all_tags( $cat_desc );
						// Normalize whitespace and line breaks
						$cat_desc = trim( preg_replace( '/\s+/', ' ', $cat_desc ) );
						// Limit words cleanly
						$cat_desc = wp_trim_words( $cat_desc, 15, '&hellip;' );
					}
					?>
					<?php if ( ! empty( $cat_desc ) ) : ?>
						<p class="category-card-description">
							<?php echo esc_html( $cat_desc ); ?>
						</p>
					<?php endif; ?>

					<!-- Read More Button at Bottom -->
					<div class="category-card-footer">
						<span class="category-read-more-btn">
							<?php esc_html_e( 'Read More', 'krypton' ); ?>
						</span>
					</div>
				</a>
			<?php endforeach; ?>
		</div>
	</div>
</section>
<?php endif; ?>

<!-- ==========================================================================
     3. Spotlight & Latest Posts Section (Popular in Left/Middle, Latest in Right)
     ========================================================================== -->
<?php
// Query Popular Posts (Top 3 for Left + Middle)
$popular_query = new WP_Query(
	array(
		'posts_per_page'      => 3,
		'post_status'         => 'publish',
		'ignore_sticky_posts' => 1,
		'orderby'             => 'comment_count',
		'order'               => 'DESC',
	)
);

// Fallback to recent posts if popular query has fewer than 3 posts
if ( ! $popular_query->have_posts() || $popular_query->post_count < 3 ) {
	$popular_query = new WP_Query(
		array(
			'posts_per_page'      => 3,
			'post_status'         => 'publish',
			'ignore_sticky_posts' => 1,
			'orderby'             => 'date',
			'order'               => 'DESC',
		)
	);
}

// Query Latest Posts (5 posts for Right sidebar list)
$latest_list_query = new WP_Query(
	array(
		'posts_per_page'      => 5,
		'post_status'         => 'publish',
		'ignore_sticky_posts' => 1,
		'orderby'             => 'date',
		'order'               => 'DESC',
	)
);

$popular_posts = $popular_query->posts;
$center_post   = isset( $popular_posts[0] ) ? $popular_posts[0] : null; // Top spotlight centerpiece
$left_post_1   = isset( $popular_posts[1] ) ? $popular_posts[1] : null;
$left_post_2   = isset( $popular_posts[2] ) ? $popular_posts[2] : null;

// Rich background tints for product cards
$popular_tints = array( '#1f1838', '#333718', '#1b2940', '#4a1816', '#9e4e1a', '#cf3838' );
?>
<section class="krypton-spotlight-section">
	<div class="krypton-container spotlight-container-inner">
		
		<!-- Left Column: 2 Stacked Popular Posts (or Skeletons) -->
		<div class="spotlight-left-column">
			<?php if ( $left_post_1 ) : 
				$post_cats = get_the_category( $left_post_1->ID );
				$cat_name  = ! empty( $post_cats ) ? $post_cats[0]->name : '';
			?>
				<article class="spotlight-stacked-card">
					<div class="spotlight-card-media" style="background-color: <?php echo esc_attr( $popular_tints[0] ); ?>;">
						<a href="<?php echo esc_url( get_permalink( $left_post_1->ID ) ); ?>" class="spotlight-media-link" tabindex="-1" aria-hidden="true">
							<?php if ( has_post_thumbnail( $left_post_1->ID ) ) : ?>
								<?php echo get_the_post_thumbnail( $left_post_1->ID, 'medium_large', array( 'class' => 'spotlight-thumb-img' ) ); ?>
							<?php else : ?>
								<div class="spotlight-placeholder-box">
									<svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="#CBD5E1" stroke-width="1.5"><rect x="3" y="3" width="18" height="18" rx="2"></rect><circle cx="8.5" cy="8.5" r="1.5"></circle><polyline points="21 15 16 10 5 21"></polyline></svg>
								</div>
							<?php endif; ?>
						</a>
					</div>
					<div class="spotlight-card-body">
						<?php if ( $cat_name ) : ?>
							<span class="spotlight-category-tag"><?php echo esc_html( $cat_name ); ?></span>
						<?php endif; ?>
						<h3 class="spotlight-card-title">
							<a href="<?php echo esc_url( get_permalink( $left_post_1->ID ) ); ?>">
								<?php echo esc_html( get_the_title( $left_post_1->ID ) ); ?>
							</a>
						</h3>
						<time class="spotlight-card-date" datetime="<?php echo esc_attr( get_the_date( 'c', $left_post_1->ID ) ); ?>">
							<?php echo esc_html( get_the_date( 'F j, Y', $left_post_1->ID ) ); ?>
						</time>
					</div>
				</article>
			<?php else : ?>
				<div class="spotlight-stacked-card is-skeleton" aria-hidden="true">
					<div class="spotlight-card-media skeleton-media">
						<div class="skeleton-shimmer" style="width: 100%; height: 100%;"></div>
					</div>
					<div class="spotlight-card-body">
						<div class="skeleton-tag skeleton-shimmer"></div>
						<div class="skeleton-line is-title skeleton-shimmer"></div>
						<div class="skeleton-line is-date skeleton-shimmer"></div>
					</div>
				</div>
			<?php endif; ?>

			<?php if ( $left_post_2 ) : 
				$post_cats = get_the_category( $left_post_2->ID );
				$cat_name  = ! empty( $post_cats ) ? $post_cats[0]->name : '';
			?>
				<article class="spotlight-stacked-card">
					<div class="spotlight-card-media" style="background-color: <?php echo esc_attr( $popular_tints[1] ); ?>;">
						<a href="<?php echo esc_url( get_permalink( $left_post_2->ID ) ); ?>" class="spotlight-media-link" tabindex="-1" aria-hidden="true">
							<?php if ( has_post_thumbnail( $left_post_2->ID ) ) : ?>
								<?php echo get_the_post_thumbnail( $left_post_2->ID, 'medium_large', array( 'class' => 'spotlight-thumb-img' ) ); ?>
							<?php else : ?>
								<div class="spotlight-placeholder-box">
									<svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="#CBD5E1" stroke-width="1.5"><rect x="3" y="3" width="18" height="18" rx="2"></rect><circle cx="8.5" cy="8.5" r="1.5"></circle><polyline points="21 15 16 10 5 21"></polyline></svg>
								</div>
							<?php endif; ?>
						</a>
					</div>
					<div class="spotlight-card-body">
						<?php if ( $cat_name ) : ?>
							<span class="spotlight-category-tag"><?php echo esc_html( $cat_name ); ?></span>
						<?php endif; ?>
						<h3 class="spotlight-card-title">
							<a href="<?php echo esc_url( get_permalink( $left_post_2->ID ) ); ?>">
								<?php echo esc_html( get_the_title( $left_post_2->ID ) ); ?>
							</a>
						</h3>
						<time class="spotlight-card-date" datetime="<?php echo esc_attr( get_the_date( 'c', $left_post_2->ID ) ); ?>">
							<?php echo esc_html( get_the_date( 'F j, Y', $left_post_2->ID ) ); ?>
						</time>
					</div>
				</article>
			<?php else : ?>
				<div class="spotlight-stacked-card is-skeleton" aria-hidden="true">
					<div class="spotlight-card-media skeleton-media">
						<div class="skeleton-shimmer" style="width: 100%; height: 100%;"></div>
					</div>
					<div class="spotlight-card-body">
						<div class="skeleton-tag skeleton-shimmer"></div>
						<div class="skeleton-line is-title skeleton-shimmer"></div>
						<div class="skeleton-line is-date skeleton-shimmer"></div>
					</div>
				</div>
			<?php endif; ?>
		</div>

		<!-- Middle Column: Big Centerpiece Spotlight Popular Post (or Skeleton) -->
		<div class="spotlight-center-column">
			<?php if ( $center_post ) : 
				$post_cats   = get_the_category( $center_post->ID );
				$cat_name    = ! empty( $post_cats ) ? $post_cats[0]->name : '';
				$author_name = get_the_author_meta( 'display_name', $center_post->post_author );
				$excerpt     = get_the_excerpt( $center_post->ID );
			?>
				<article class="spotlight-main-card">
					<div class="spotlight-main-media" style="background-color: <?php echo esc_attr( $popular_tints[2] ); ?>;">
						<a href="<?php echo esc_url( get_permalink( $center_post->ID ) ); ?>" class="spotlight-media-link" tabindex="-1" aria-hidden="true">
							<?php if ( has_post_thumbnail( $center_post->ID ) ) : ?>
								<?php echo get_the_post_thumbnail( $center_post->ID, 'large', array( 'class' => 'spotlight-main-img' ) ); ?>
							<?php else : ?>
								<div class="spotlight-placeholder-box is-large">
									<svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="#CBD5E1" stroke-width="1.5"><rect x="3" y="3" width="18" height="18" rx="2"></rect><circle cx="8.5" cy="8.5" r="1.5"></circle><polyline points="21 15 16 10 5 21"></polyline></svg>
								</div>
							<?php endif; ?>
						</a>
					</div>
					<div class="spotlight-main-body">
						<?php if ( $cat_name ) : ?>
							<span class="spotlight-category-tag"><?php echo esc_html( $cat_name ); ?></span>
						<?php endif; ?>
						<h2 class="spotlight-main-title">
							<a href="<?php echo esc_url( get_permalink( $center_post->ID ) ); ?>">
								<?php echo esc_html( get_the_title( $center_post->ID ) ); ?>
							</a>
						</h2>
						<?php if ( $excerpt ) : ?>
							<p class="spotlight-main-excerpt">
								<?php echo esc_html( wp_trim_words( $excerpt, 24, '...' ) ); ?>
							</p>
						<?php endif; ?>
						<div class="spotlight-main-meta">
							<span class="spotlight-meta-author"><?php echo esc_html( $author_name ); ?></span>
							<span class="spotlight-meta-separator" aria-hidden="true">/</span>
							<time class="spotlight-meta-date" datetime="<?php echo esc_attr( get_the_date( 'c', $center_post->ID ) ); ?>">
								<?php echo esc_html( get_the_date( 'M j, Y', $center_post->ID ) ); ?>
							</time>
						</div>
					</div>
				</article>
			<?php else : ?>
				<div class="spotlight-main-card is-skeleton" aria-hidden="true">
					<div class="spotlight-main-media skeleton-media is-main">
						<div class="skeleton-shimmer" style="width: 100%; height: 100%;"></div>
					</div>
					<div class="spotlight-main-body">
						<div class="skeleton-tag skeleton-shimmer"></div>
						<div class="skeleton-line is-main-title skeleton-shimmer"></div>
						<div class="skeleton-line is-excerpt-1 skeleton-shimmer"></div>
						<div class="skeleton-line is-excerpt-2 skeleton-shimmer"></div>
						<div class="skeleton-line is-meta skeleton-shimmer"></div>
					</div>
				</div>
			<?php endif; ?>
		</div>

		<!-- Right Column: 5 Latest Posts List (or Skeletons) -->
		<div class="spotlight-right-column">
			<div class="spotlight-latest-header">
				<h3 class="spotlight-latest-title"><?php esc_html_e( 'Latest', 'krypton' ); ?></h3>
				<div class="spotlight-latest-divider" aria-hidden="true"></div>
			</div>

			<div class="spotlight-latest-list">
				<?php
				$rendered_count = 0;
				if ( $latest_list_query->have_posts() ) :
					while ( $latest_list_query->have_posts() ) :
						$latest_list_query->the_post();
						$tint = $popular_tints[ ( $rendered_count + 3 ) % count( $popular_tints ) ];
						$rendered_count++;
						?>
						<article class="spotlight-latest-item">
							<div class="latest-item-text">
								<h4 class="latest-item-title">
									<a href="<?php the_permalink(); ?>">
										<?php the_title(); ?>
									</a>
								</h4>
								<time class="latest-item-date" datetime="<?php echo esc_attr( get_the_date( 'c' ) ); ?>">
									<?php echo esc_html( get_the_date( 'F j, Y' ) ); ?>
								</time>
							</div>

							<div class="latest-item-thumb-box" style="background-color: <?php echo esc_attr( $tint ); ?>;">
								<a href="<?php the_permalink(); ?>" class="latest-thumb-link" tabindex="-1" aria-hidden="true">
									<?php if ( has_post_thumbnail() ) : ?>
										<?php the_post_thumbnail( 'thumbnail', array( 'class' => 'latest-thumb-img' ) ); ?>
									<?php else : ?>
										<div class="latest-placeholder-box">
											<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#CBD5E1" stroke-width="1.5"><rect x="3" y="3" width="18" height="18" rx="2"></rect><circle cx="8.5" cy="8.5" r="1.5"></circle><polyline points="21 15 16 10 5 21"></polyline></svg>
										</div>
									<?php endif; ?>
								</a>
							</div>
						</article>
						<?php
					endwhile;
					wp_reset_postdata();
				endif;

				// Fill remaining slots up to 5 with shimmering skeletons
				for ( $s = $rendered_count; $s < 5; $s++ ) :
				?>
					<div class="spotlight-latest-item is-skeleton" aria-hidden="true">
						<div class="latest-item-text">
							<div class="skeleton-line is-latest-title skeleton-shimmer"></div>
							<div class="skeleton-line is-latest-date skeleton-shimmer"></div>
						</div>
						<div class="latest-item-thumb-box skeleton-shimmer"></div>
					</div>
				<?php endfor; ?>
			</div>
		</div>

	</div>
</section>

<?php
get_footer();

