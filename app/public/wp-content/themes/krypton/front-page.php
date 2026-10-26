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

<!-- ==========================================================================
     Clinical Categories Section
     ========================================================================== -->
<?php
// Dynamic Clinical Categories Query
$registered_cats = get_categories(
	array(
		'taxonomy'   => 'category',
		'orderby'    => 'count',
		'order'      => 'DESC',
		'hide_empty' => false,
		'exclude'    => array( 1 ), // Exclude Uncategorized
		'number'     => 8,
	)
);

// Fallback metadata dictionary for supplement categories
$category_definitions = array(
	'male-enhancement'  => array(
		'name'        => "Male's Enhancement",
		'description' => 'Testosterone boosters, stamina enhancers, and prostate vitality protocols evaluated by clinical research.',
		'icon'        => 'vitality',
	),
	'weight-loss'       => array(
		'name'        => 'Weight Loss',
		'description' => 'Science-backed fat oxidizers, thermogenic agents, and appetite suppressants evaluated for metabolic efficacy.',
		'icon'        => 'flame',
	),
	'brain-enhancement' => array(
		'name'        => 'Brain Enhancement',
		'description' => 'Cognitive enhancers, memory compounds, and nootropics tested for neuroplasticity and mental clarity.',
		'icon'        => 'brain',
	),
	'joint-pain-relief' => array(
		'name'        => 'Joint Pain Relief',
		'description' => 'Collagen peptides, anti-inflammatory herbs, and cartilage renewal formulas for active flexibility.',
		'icon'        => 'joint',
	),
	'skin-hair-nails'   => array(
		'name'        => 'Skin & Cellular Health',
		'description' => 'Bioactive marine collagen, hyaluronic acid, and antioxidant complexes for cellular rejuvenation.',
		'icon'        => 'sparkle',
	),
	'sleep-recovery'    => array(
		'name'        => 'Sleep & Recovery',
		'description' => 'Non-habit-forming sleep aids, magnesium glycinate, and circadian rhythm balance formulas.',
		'icon'        => 'moon',
	),
	'gut-health'        => array(
		'name'        => 'Gut & Microbiome',
		'description' => 'Spore-forming probiotics, digestive enzymes, and prebiotic fibers for optimal nutrient assimilation.',
		'icon'        => 'gut',
	),
	'heart-circulation' => array(
		'name'        => 'Heart & Blood Flow',
		'description' => 'Nitric oxide precursors, CoQ10, and high-potency Omega-3s for cardiovascular longevity.',
		'icon'        => 'heart',
	),
);

// Assemble cards from database or presets
$cards_to_render = array();

if ( ! empty( $registered_cats ) ) {
	foreach ( $registered_cats as $rcat ) {
		$slug = $rcat->slug;
		// Determine best matching icon and description
		$matched_key = '';
		foreach ( array_keys( $category_definitions ) as $preset_key ) {
			if ( strpos( $slug, $preset_key ) !== false || strpos( $preset_key, $slug ) !== false ) {
				$matched_key = $preset_key;
				break;
			}
		}

		$icon_type   = $matched_key ? $category_definitions[ $matched_key ]['icon'] : 'vitality';
		$desc_text   = ( ! empty( $rcat->description ) && strpos( $rcat->description, 'Jazz' ) === false ) ? $rcat->description : '';
		if ( empty( $desc_text ) && $matched_key ) {
			$desc_text = $category_definitions[ $matched_key ]['description'];
		} elseif ( empty( $desc_text ) ) {
			$desc_text = sprintf( __( 'Explore evidence-based research and clinical guides for %s supplements.', 'krypton' ), $rcat->name );
		}

		$cards_to_render[] = array(
			'name'        => $rcat->name,
			'description' => $desc_text,
			'url'         => get_category_link( $rcat->term_id ),
			'icon'        => $icon_type,
		);
	}
}

// Fill up with preset defaults if fewer than 4 categories exist
if ( count( $cards_to_render ) < 4 ) {
	foreach ( $category_definitions as $preset_key => $preset_data ) {
		// Avoid duplicate names
		$already_exists = false;
		foreach ( $cards_to_render as $existing ) {
			if ( strtolower( $existing['name'] ) === strtolower( $preset_data['name'] ) ) {
				$already_exists = true;
				break;
			}
		}

		if ( ! $already_exists ) {
			$cards_to_render[] = array(
				'name'        => $preset_data['name'],
				'description' => $preset_data['description'],
				'url'         => home_url( '/category/' . $preset_key . '/' ),
				'icon'        => $preset_data['icon'],
			);
		}

		if ( count( $cards_to_render ) >= 8 ) {
			break;
		}
	}
}

// View all URL
$blog_page_id  = (int) get_option( 'page_for_posts' );
$view_all_href = $blog_page_id ? get_permalink( $blog_page_id ) : home_url( '/#categories' );
?>

<section id="categories" class="krypton-categories-section">
	<div class="krypton-container">
		<!-- Section Header -->
		<div class="categories-section-header">
			<div class="header-title-group">
				<h2 class="categories-section-title">
					<?php esc_html_e( 'Explore Our Clinical Categories', 'krypton' ); ?>
				</h2>
			</div>

			<a href="<?php echo esc_url( $view_all_href ); ?>" class="categories-view-all-link">
				<span><?php esc_html_e( 'View All', 'krypton' ); ?></span>
				<svg class="view-all-arrow-icon" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
					<line x1="5" y1="12" x2="19" y2="12"></line>
					<polyline points="12 5 19 12 12 19"></polyline>
				</svg>
			</a>
		</div>

		<!-- 4-Column Responsive Grid -->
		<div class="clinical-categories-grid">
			<?php foreach ( $cards_to_render as $card ) : ?>
				<a href="<?php echo esc_url( $card['url'] ); ?>" class="clinical-category-card">
					<!-- Icon in Soft Sage Green Circular Wrapper -->
					<div class="category-icon-wrapper">
						<?php if ( 'flame' === $card['icon'] ) : ?>
							<svg class="category-svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
								<path d="M8.5 14.5A2.5 2.5 0 0 0 11 12c0-1.38-.5-2-1-3-1.072-2.143-.224-4.054 2-6 .5 2.5 2 4.9 4 6.5 2 1.6 3 3.5 3 5.5a7 7 0 1 1-14 0c0-1.153.433-2.294 1-3a2.5 2.5 0 0 0 2.5 2.5z"></path>
							</svg>
						<?php elseif ( 'brain' === $card['icon'] ) : ?>
							<svg class="category-svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
								<path d="M9.5 2A2.5 2.5 0 0 1 12 4.5v15a2.5 2.5 0 0 1-4.96.44 2.5 2.5 0 0 1-2.96-3.08 3 3 0 0 1-.34-5.58 2.5 2.5 0 0 1 1.32-4.24 2.5 2.5 0 0 1 4.44-5.04z"></path>
								<path d="M14.5 2A2.5 2.5 0 0 0 12 4.5v15a2.5 2.5 0 0 0 4.96.44 2.5 2.5 0 0 0 2.96-3.08 3 3 0 0 0 .34-5.58 2.5 2.5 0 0 0-1.32-4.24 2.5 2.5 0 0 0-4.44-5.04z"></path>
							</svg>
						<?php elseif ( 'joint' === $card['icon'] ) : ?>
							<svg class="category-svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
								<circle cx="12" cy="12" r="3"></circle>
								<path d="M12 2a4 4 0 0 0-4 4c0 1.5.8 2.8 2 3.5V11a2 2 0 0 0-2 2v2a4 4 0 0 0 8 0v-2a2 2 0 0 0-2-2V9.5c1.2-.7 2-2 2-3.5a4 4 0 0 0-4-4z"></path>
							</svg>
						<?php elseif ( 'sparkle' === $card['icon'] ) : ?>
							<svg class="category-svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
								<path d="m12 3-1.912 5.813a2 2 0 0 1-1.275 1.275L3 12l5.813 1.912a2 2 0 0 1 1.275 1.275L12 21l1.912-5.813a2 2 0 0 1 1.275-1.275L21 12l-5.813-1.912a2 2 0 0 1-1.275-1.275L12 3Z"></path>
							</svg>
						<?php elseif ( 'moon' === $card['icon'] ) : ?>
							<svg class="category-svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
								<path d="M12 3a6 6 0 0 0 9 9 9 9 0 1 1-9-9Z"></path>
							</svg>
						<?php elseif ( 'heart' === $card['icon'] ) : ?>
							<svg class="category-svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
								<path d="M19 14c1.49-1.46 3-3.21 3-5.5A5.5 5.5 0 0 0 16.5 3c-1.76 0-3 .5-4.5 2-1.5-1.5-2.74-2-4.5-2A5.5 5.5 0 0 0 2 8.5c0 2.3 1.5 4.05 3 5.5l7 7Z"></path>
							</svg>
						<?php elseif ( 'gut' === $card['icon'] ) : ?>
							<svg class="category-svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
								<path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path>
								<path d="M12 8v8"></path>
								<path d="m9 11 3-3 3 3"></path>
							</svg>
						<?php else : ?>
							<!-- Vitality / Shield with check / bolt -->
							<svg class="category-svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
								<path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path>
								<polygon points="13 8 9 13 12 13 11 17 15 12 12 12 13 8"></polygon>
							</svg>
						<?php endif; ?>
					</div>

					<!-- Category Title -->
					<h3 class="category-card-title"><?php echo esc_html( $card['name'] ); ?></h3>

					<!-- Category Description -->
					<p class="category-card-description"><?php echo esc_html( $card['description'] ); ?></p>

					<!-- Action Arrow at Bottom Right -->
					<div class="category-card-footer">
						<span class="category-action-arrow" aria-hidden="true">
							<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
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

<?php
get_footer();

