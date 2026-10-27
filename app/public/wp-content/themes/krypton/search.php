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

<div class="krypton-category-page-wrapper krypton-search-results-page">
	<div class="krypton-container category-page-container">
		
		<main id="primary" class="category-main-content search-main-content" role="main" style="width: 100%;">
			
			<!-- Breadcrumb Navigation -->
			<nav class="single-breadcrumbs font-inter" aria-label="<?php esc_attr_e( 'Breadcrumb', 'krypton' ); ?>" style="margin-bottom: 20px;">
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

			<!-- Search Results Header -->
			<div class="category-content-header search-results-header">
				<div>
					<h1 class="category-heading-title font-montserrat">
						<?php
						/* translators: %s: Search term */
						printf( esc_html__( 'Search results for: “%s”', 'krypton' ), esc_html( $search_query ) );
						?>
					</h1>
					<p class="search-results-meta font-inter" style="margin: 6px 0 0; color: #64748B; font-size: 14px;">
						<?php
						global $wp_query;
						$total_results = $wp_query->found_posts;
						printf(
							/* translators: %d: number of results */
							esc_html( _n( 'Found %d article matching your query', 'Found %d articles matching your query', $total_results, 'krypton' ) ),
							(int) $total_results
						);
						?>
					</p>
				</div>
			</div>

			<!-- Divider Line -->
			<div class="category-header-divider" aria-hidden="true"></div>

			<!-- Search Results Loop -->
			<?php if ( have_posts() ) : ?>
				<div class="category-articles-grid">
					<?php
					$card_counter = 0;
					while ( have_posts() ) :
						the_post();
						$tint_color = $tint_colors[ $card_counter % count( $tint_colors ) ];
						$card_counter++;
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
								<?php
								$cats = get_the_category();
								if ( ! empty( $cats ) ) :
									?>
									<span class="post-card-category-badge font-inter" style="display: inline-block; font-size: 11px; font-weight: 700; color: var(--color-primary); text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 6px;">
										<?php echo esc_html( $cats[0]->name ); ?>
									</span>
								<?php endif; ?>

								<h2 class="post-card-title">
									<a href="<?php the_permalink(); ?>">
										<?php the_title(); ?>
									</a>
								</h2>

								<div class="post-card-excerpt font-inter" style="font-size: 13.5px; color: #4B5563; line-height: 1.5; margin-bottom: 12px;">
									<?php echo esc_html( wp_trim_words( get_the_excerpt(), 18, '...' ) ); ?>
								</div>

								<div class="post-card-meta-row font-inter">
									<span class="post-card-author">
										<?php echo esc_html( get_the_author() ); ?>
									</span>
									<span aria-hidden="true">·</span>
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
							'prev_text' => __( '&larr; Previous', 'krypton' ),
							'next_text' => __( 'Next &rarr;', 'krypton' ),
						)
					);
					?>
				</div>

			<?php else : ?>
				<!-- Friendly Empty State -->
				<div class="search-no-results font-inter" style="padding: 48px 24px; text-align: center; background: #FFFFFF; border-radius: 12px; border: 1px solid #E2E8F0; margin: 30px 0;">
					<svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="#94A3B8" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" style="margin: 0 auto 16px; display: block;">
						<circle cx="11" cy="11" r="8"></circle>
						<line x1="21" y1="21" x2="16.65" y2="16.65"></line>
						<line x1="8" y1="11" x2="14" y2="11"></line>
					</svg>
					<h2 class="font-montserrat" style="font-size: 20px; font-weight: 700; color: #1F2937; margin-bottom: 8px;">
						<?php esc_html_e( 'No matching articles found', 'krypton' ); ?>
					</h2>
					<p style="color: #64748B; max-width: 480px; margin: 0 auto 24px; line-height: 1.6;">
						<?php printf( esc_html__( 'We couldn’t find any articles matching “%s”. Try checking for typos or searching with different keywords.', 'krypton' ), esc_html( $search_query ) ); ?>
					</p>
					
					<div style="max-width: 420px; margin: 0 auto;">
						<?php get_search_form(); ?>
					</div>
				</div>
			<?php endif; ?>

		</main>

	</div>
</div>

<?php
get_footer();
