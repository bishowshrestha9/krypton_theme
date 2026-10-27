<?php
/**
 * The template for displaying Search Results pages.
 *
 * @package Krypton
 */

get_header();

$search_query = get_search_query();
$tint_colors  = array( '#1f1b34', '#36391d', '#1a273e', '#2c1e2f', '#1b322c' );
?>

<div class="krypton-search-page-wrapper">
	<div class="krypton-container search-page-container">
		
		<main id="primary" class="search-main-content" role="main">
			
			<!-- Breadcrumb Navigation -->
			<nav class="single-breadcrumbs font-inter" aria-label="<?php esc_attr_e( 'Breadcrumb', 'krypton' ); ?>" style="margin-bottom: 24px;">
				<ol class="breadcrumb-list">
					<li class="breadcrumb-item">
						<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="breadcrumb-link font-inter">
							<?php esc_html_e( 'Home', 'krypton' ); ?>
						</a>
					</li>
					<li class="breadcrumb-separator" aria-hidden="true">
						<svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"></polyline></svg>
					</li>
					<li class="breadcrumb-item breadcrumb-item-current" aria-current="page">
						<span class="breadcrumb-product-pill font-inter">
							<?php esc_html_e( 'Search Results', 'krypton' ); ?>
						</span>
					</li>
				</ol>
			</nav>

			<!-- Search Results Header Card -->
			<header class="search-results-header">
				<div class="search-header-info">
					<h1 class="search-heading-title font-montserrat">
						<?php
						/* translators: %s: Search term */
						printf( esc_html__( 'Search results for: “%s”', 'krypton' ), esc_html( $search_query ) );
						?>
					</h1>
					<p class="search-results-meta font-inter">
						<?php
						global $wp_query;
						$total_results = (int) $wp_query->found_posts;
						printf(
							/* translators: %s: number of results */
							esc_html( _n( 'Found %s article matching your query', 'Found %s articles matching your query', $total_results, 'krypton' ) ),
							number_format_i18n( $total_results )
						);
						?>
					</p>
				</div>

				<!-- Embedded Inline Search Box for quick search refinement -->
				<div class="search-header-form-wrapper">
					<form role="search" method="get" class="search-header-form" action="<?php echo esc_url( home_url( '/' ) ); ?>">
						<div class="search-header-input-group">
							<span class="search-header-icon" aria-hidden="true">
								<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
									<circle cx="11" cy="11" r="8"></circle>
									<line x1="21" y1="21" x2="16.65" y2="16.65"></line>
								</svg>
							</span>
							<input 
								type="search" 
								class="search-header-input font-inter" 
								name="s" 
								value="<?php echo esc_attr( $search_query ); ?>" 
								placeholder="<?php esc_attr_e( 'Search articles, reviews...', 'krypton' ); ?>" 
								aria-label="<?php esc_attr_e( 'Search', 'krypton' ); ?>" 
							/>
							<button type="submit" class="search-header-submit-btn font-inter">
								<?php esc_html_e( 'Search', 'krypton' ); ?>
							</button>
						</div>
					</form>
				</div>
			</header>

			<!-- Divider line -->
			<div class="search-header-divider" aria-hidden="true"></div>

			<!-- Search Results Loop -->
			<?php if ( have_posts() ) : ?>
				<div class="search-articles-grid">
					<?php
					$card_counter = 0;
					while ( have_posts() ) :
						the_post();
						$tint_color = $tint_colors[ $card_counter % count( $tint_colors ) ];
						$card_counter++;
						?>
						<article id="post-<?php the_ID(); ?>" <?php post_class( 'search-post-card' ); ?>>
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
								<?php
								$cats = get_the_category();
								if ( ! empty( $cats ) ) :
									?>
									<span class="post-card-category-badge font-inter">
										<a href="<?php echo esc_url( get_category_link( $cats[0]->term_id ) ); ?>">
											<?php echo esc_html( $cats[0]->name ); ?>
										</a>
									</span>
								<?php endif; ?>

								<h2 class="post-card-title font-montserrat">
									<a href="<?php the_permalink(); ?>">
										<?php the_title(); ?>
									</a>
								</h2>

								<div class="post-card-excerpt font-inter">
									<?php echo esc_html( wp_trim_words( get_the_excerpt(), 18, '...' ) ); ?>
								</div>

								<div class="post-card-meta-row font-inter">
									<span class="post-card-author">
										<?php echo esc_html( get_the_author() ); ?>
									</span>
									<span class="meta-sep" aria-hidden="true">·</span>
									<time class="post-card-date" datetime="<?php echo esc_attr( get_the_date( 'c' ) ); ?>">
										<?php echo esc_html( get_the_date( 'M j, Y' ) ); ?>
									</time>
								</div>
							</div>
						</article>
					<?php endwhile; ?>
				</div>

				<!-- Pagination -->
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

			<?php else : ?>
				<!-- Friendly Empty State -->
				<div class="search-no-results font-inter">
					<div class="search-no-results-icon" aria-hidden="true">
						<svg width="56" height="56" viewBox="0 0 24 24" fill="none" stroke="var(--color-primary, #6EA590)" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round">
							<circle cx="11" cy="11" r="8"></circle>
							<line x1="21" y1="21" x2="16.65" y2="16.65"></line>
							<line x1="8" y1="11" x2="14" y2="11"></line>
						</svg>
					</div>
					<h2 class="search-empty-title font-montserrat">
						<?php esc_html_e( 'No matching articles found', 'krypton' ); ?>
					</h2>
					<p class="search-empty-desc">
						<?php printf( esc_html__( 'We couldn’t find any articles matching “%s”. Try checking for typos or searching with different keywords.', 'krypton' ), esc_html( $search_query ) ); ?>
					</p>
					
					<div class="search-empty-form-wrap">
						<?php get_search_form(); ?>
					</div>
				</div>
			<?php endif; ?>

		</main>

	</div>
</div>

<?php
get_footer();
