<?php
/**
 * Template Name: Categories Directory
 * Description: The template for displaying all categories with their latest articles.
 *
 * @package Krypton
 */

get_header();

// Helper to resolve category terms from slug or title
if ( ! function_exists( 'krypton_resolve_category_terms' ) ) {
	function krypton_resolve_category_terms( $slug, $title = '' ) {
		$slug = trim( $slug, '/' );
		if ( strpos( $slug, '/' ) !== false ) {
			$parts = explode( '/', $slug );
			$slug  = end( $parts );
		}

		// 1. Direct slug match
		$term = get_term_by( 'slug', $slug, 'category' );
		if ( $term && ! is_wp_error( $term ) ) {
			return array( $term );
		}

		// 2. Known alias mappings
		$aliases = array(
			'fashion-apparel'      => array( 'fashion', 'accessories', 'footwear' ),
			'footwear-accessories' => array( 'footwear', 'accessories' ),
			'pets-pet-care'        => array( 'pet-care' ),
			'tech-gadgets'         => array( 'electronics-gadgets' ),
			'home-kitchen'         => array( 'home-kitchen', 'kitchen' ),
		);
		if ( isset( $aliases[ $slug ] ) ) {
			$terms = array();
			foreach ( $aliases[ $slug ] as $s ) {
				$t = get_term_by( 'slug', $s, 'category' );
				if ( $t && ! is_wp_error( $t ) ) {
					$terms[] = $t;
				}
			}
			if ( ! empty( $terms ) ) {
				return $terms;
			}
		}

		// 3. Match by name
		if ( $title ) {
			$t = get_term_by( 'name', $title, 'category' );
			if ( $t && ! is_wp_error( $t ) ) {
				return array( $t );
			}
		}

		// 4. Fallback search
		$search_terms = get_terms(
			array(
				'taxonomy'   => 'category',
				'search'     => str_replace( array( '-', 'category' ), ' ', $slug ),
				'hide_empty' => true,
			)
		);
		return ! empty( $search_terms ) && ! is_wp_error( $search_terms ) ? $search_terms : array();
	}
}

// Background tint array for default bottle cards
$card_tint_colors = array( '#1f1b34', '#36391d', '#1a273e', '#2c1e2f', '#1b322c' );

while ( have_posts() ) :
	the_post();

	// Parse Gutenberg blocks to extract intro paragraphs and categories
	$blocks          = parse_blocks( get_the_content() );
	$categories_data = array();
	$intro_html      = '';
	$current_cat     = null;

	foreach ( $blocks as $b ) {
		if ( empty( $b['blockName'] ) ) {
			continue;
		}

		if ( 'core/paragraph' === $b['blockName'] && empty( $categories_data ) && empty( $current_cat ) ) {
			$intro_html .= render_block( $b );
		} elseif ( 'core/heading' === $b['blockName'] ) {
			if ( $current_cat ) {
				$categories_data[] = $current_cat;
			}

			preg_match( '/<a\s+href=[\"\']([^\"\']+)[\"\'][^>]*>(.*?)<\/a>/is', $b['innerHTML'], $link_match );
			$url   = ! empty( $link_match[1] ) ? $link_match[1] : '';
			$title = ! empty( $link_match[2] ) ? strip_tags( $link_match[2] ) : strip_tags( $b['innerHTML'] );

			$slug = '';
			if ( $url ) {
				$path  = trim( (string) parse_url( $url, PHP_URL_PATH ), '/' );
				$parts = explode( '/', $path );
				$slug  = end( $parts );
			}
			if ( ! $slug ) {
				$slug = sanitize_title( $title );
			}

			$current_cat = array(
				'title' => html_entity_decode( $title ),
				'url'   => $url,
				'slug'  => $slug,
				'desc'  => '',
				'terms' => krypton_resolve_category_terms( $slug, $title ),
			);
		} elseif ( 'core/paragraph' === $b['blockName'] && $current_cat ) {
			$current_cat['desc'] = trim( strip_tags( $b['innerHTML'] ) );
			$categories_data[]   = $current_cat;
			$current_cat         = null;
		}
	}
	if ( $current_cat ) {
		$categories_data[] = $current_cat;
	}

	// Fallback: If no categories were parsed from page content, query all published categories dynamically
	if ( empty( $categories_data ) ) {
		$all_cats = get_categories(
			array(
				'taxonomy'   => 'category',
				'hide_empty' => true,
				'exclude'    => array( 1 ),
				'orderby'    => 'count',
				'order'      => 'DESC',
			)
		);
		foreach ( $all_cats as $ac ) {
			$categories_data[] = array(
				'title' => $ac->name,
				'url'   => get_category_link( $ac->term_id ),
				'slug'  => $ac->slug,
				'desc'  => $ac->description,
				'terms' => array( $ac ),
			);
		}
	}
	?>

	<main id="primary" class="site-main category-directory-page">
		<article id="post-<?php the_ID(); ?>" <?php post_class( 'krypton-category-directory' ); ?>>

			<div class="krypton-container category-directory-container">
				
				<!-- Page Header -->
				<header class="category-directory-header">
					<h1 class="category-directory-title font-montserrat">
						<?php the_title(); ?>
					</h1>

					<?php if ( ! empty( $intro_html ) ) : ?>
						<div class="category-directory-intro font-inter">
							<?php echo wp_kses_post( $intro_html ); ?>
						</div>
					<?php endif; ?>
				</header>

				<!-- Quick Jump Category Navigation Pills -->
				<?php if ( ! empty( $categories_data ) ) : ?>
					<nav class="category-pills-nav" aria-label="<?php esc_attr_e( 'Quick Category Navigation', 'krypton' ); ?>">
						<div class="category-pills-list">
							<?php foreach ( $categories_data as $cat_pill ) : ?>
								<a href="#cat-<?php echo esc_attr( $cat_pill['slug'] ); ?>" class="category-pill-link font-inter">
									<?php echo esc_html( $cat_pill['title'] ); ?>
								</a>
							<?php endforeach; ?>
						</div>
					</nav>
				<?php endif; ?>

				<!-- Category Sections -->
				<div class="category-showcase-list">
					<?php
					$global_card_counter = 0;
					foreach ( $categories_data as $cat_item ) :
						$term_ids = ! empty( $cat_item['terms'] ) ? wp_list_pluck( $cat_item['terms'], 'term_id' ) : array();

						// Determine primary category URL
						$cat_url = $cat_item['url'];
						if ( empty( $cat_url ) && ! empty( $cat_item['terms'][0] ) ) {
							$cat_url = get_category_link( $cat_item['terms'][0]->term_id );
						}

						// Query latest 3 posts
						$cat_posts = array();
						if ( ! empty( $term_ids ) ) {
							$cat_posts = get_posts(
								array(
									'category__in'   => $term_ids,
									'posts_per_page' => 3,
									'post_status'    => 'publish',
									'orderby'        => 'date',
									'order'          => 'DESC',
								)
							);
						}
						?>
						<section class="category-showcase-section" id="cat-<?php echo esc_attr( $cat_item['slug'] ); ?>">
							
							<div class="category-showcase-header">
								<div class="category-showcase-info">
									<h2 class="category-showcase-title font-montserrat">
										<?php if ( ! empty( $cat_url ) ) : ?>
											<a href="<?php echo esc_url( $cat_url ); ?>" class="category-title-link">
												<?php echo esc_html( $cat_item['title'] ); ?>
											</a>
										<?php else : ?>
											<?php echo esc_html( $cat_item['title'] ); ?>
										<?php endif; ?>
									</h2>

									<?php if ( ! empty( $cat_item['desc'] ) ) : ?>
										<p class="category-showcase-desc font-inter">
											<?php echo esc_html( $cat_item['desc'] ); ?>
										</p>
									<?php endif; ?>
								</div>

								<?php if ( ! empty( $cat_url ) ) : ?>
									<div class="category-showcase-action">
										<a href="<?php echo esc_url( $cat_url ); ?>" class="category-view-all-btn font-inter">
											<span><?php esc_html_e( 'View all reviews', 'krypton' ); ?></span>
											<svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
												<line x1="5" y1="12" x2="19" y2="12"></line>
												<polyline points="12 5 19 12 12 19"></polyline>
											</svg>
										</a>
									</div>
								<?php endif; ?>
							</div>

							<!-- Articles Grid -->
							<?php if ( ! empty( $cat_posts ) ) : ?>
								<div class="category-articles-grid">
									<?php
									foreach ( $cat_posts as $post ) :
										setup_postdata( $post );
										$tint_color = $card_tint_colors[ $global_card_counter % count( $card_tint_colors ) ];
										$global_card_counter++;
										?>
										<article id="post-<?php the_ID(); ?>" <?php post_class( 'category-post-card' ); ?>>
											<!-- Card Thumbnail Container -->
											<div class="post-card-media-wrapper<?php echo has_post_thumbnail() ? ' has-thumbnail' : ' is-fallback'; ?>"<?php echo ! has_post_thumbnail() ? ' style="background-color: ' . esc_attr( $tint_color ) . ';"' : ''; ?>>
												<a href="<?php the_permalink(); ?>" class="post-card-media-link" tabindex="-1" aria-hidden="true">
													<?php if ( has_post_thumbnail() ) : ?>
														<?php the_post_thumbnail( 'medium_large', array( 'class' => 'post-card-thumbnail' ) ); ?>
													<?php else : ?>
														<!-- Clean Supplement Bottle Graphic Fallback -->
														<div class="post-card-bottle-fallback">
															<div class="fallback-bottle">
																<div class="bottle-cap"></div>
																<div class="bottle-body">
																	<div class="bottle-label">
																		<span class="bottle-icon">
																			<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path></svg>
																		</span>
																		<span class="bottle-brand"><?php bloginfo( 'name' ); ?></span>
																	</div>
																</div>
															</div>
														</div>
													<?php endif; ?>
												</a>
											</div>

											<!-- Post Card Content -->
											<div class="post-card-content">
												<h3 class="post-card-title">
													<a href="<?php the_permalink(); ?>">
														<?php the_title(); ?>
													</a>
												</h3>

												<div class="post-card-meta-row">
													<span class="post-card-author">
														<?php echo esc_html( get_the_author() ); ?>
													</span>
													<time class="post-card-date" datetime="<?php echo esc_attr( get_the_date( 'c' ) ); ?>">
														<?php echo esc_html( get_the_date( 'F j, Y' ) ); ?>
													</time>
												</div>
											</div>
										</article>
									<?php endforeach; wp_reset_postdata(); ?>
								</div>
							<?php else : ?>
								<p class="category-no-posts font-inter">
									<?php esc_html_e( 'No published articles found in this category yet.', 'krypton' ); ?>
								</p>
							<?php endif; ?>

							<div class="category-showcase-divider" aria-hidden="true"></div>

						</section>
					<?php endforeach; ?>
				</div>

			</div>
		</article>
	</main>

	<?php
endwhile;

get_footer();
