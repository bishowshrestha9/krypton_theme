<?php
/**
 * The template for displaying Category Archive pages.
 *
 * @package Krypton
 */

get_header();

// Get current category data
$current_cat    = get_queried_object();
$current_cat_id = isset( $current_cat->term_id ) ? (int) $current_cat->term_id : 0;
$current_title  = single_cat_title( '', false );

// Query all categories dynamically (excluding Uncategorized ID 1)
$all_categories = get_categories(
	array(
		'taxonomy'   => 'category',
		'hide_empty' => false,
		'exclude'    => array( 1 ),
		'orderby'    => 'name',
		'order'      => 'ASC',
	)
);

// If custom categories are empty, fallback to all categories
if ( empty( $all_categories ) ) {
	$all_categories = get_categories(
		array(
			'taxonomy'   => 'category',
			'hide_empty' => false,
			'orderby'    => 'name',
			'order'      => 'ASC',
		)
	);
}

// Popular categories: sort by post count descending, limit 4
$popular_categories = $all_categories;
usort(
	$popular_categories,
	function( $a, $b ) {
		return $b->count - $a->count;
	}
);
$popular_categories = array_slice( $popular_categories, 0, 4 );

// Background tint array for default bottle cards
$card_tint_colors = array( '#1f1b34', '#36391d', '#1a273e', '#2c1e2f', '#1b322c' );
?>

<div class="krypton-category-page-wrapper">
	<div class="krypton-container category-page-container">
		
		<!-- Left Sidebar (Search & Categories Navigation) -->
		<aside class="category-sidebar" aria-label="<?php esc_attr_e( 'Categories Navigation', 'krypton' ); ?>">
			
			<!-- Category Search Box with Live Filtering -->
			<div class="category-search-widget">
				<div class="category-search-input-wrapper">
					<input 
						type="search" 
						id="categoryFilterInput" 
						class="category-search-field" 
						placeholder="<?php esc_attr_e( 'Search for a category', 'krypton' ); ?>"
						aria-label="<?php esc_attr_e( 'Search for a category', 'krypton' ); ?>"
						autocomplete="off"
					/>
					<span class="category-search-icon" aria-hidden="true">
						<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
							<circle cx="11" cy="11" r="8"></circle>
							<line x1="21" y1="21" x2="16.65" y2="16.65"></line>
						</svg>
					</span>
				</div>
			</div>

			<!-- Popular Categories Box -->
			<?php if ( ! empty( $popular_categories ) ) : ?>
			<div class="category-widget-card popular-categories-box">
				<h3 class="category-widget-title"><?php esc_html_e( 'Popular Categories', 'krypton' ); ?></h3>
				<ul class="category-widget-list" id="popularCategoryList">
					<?php foreach ( $popular_categories as $p_cat ) : 
						$is_active = ( $p_cat->term_id === $current_cat_id );
					?>
						<li class="category-widget-item<?php echo $is_active ? ' is-active' : ''; ?>" data-cat-name="<?php echo esc_attr( strtolower( $p_cat->name ) ); ?>">
							<a href="<?php echo esc_url( get_category_link( $p_cat->term_id ) ); ?>" class="category-widget-link">
								<?php echo esc_html( $p_cat->name ); ?>
							</a>
						</li>
					<?php endforeach; ?>
				</ul>
			</div>
			<?php endif; ?>

			<!-- All Categories Box -->
			<?php if ( ! empty( $all_categories ) ) : ?>
			<div class="category-widget-card all-categories-box">
				<h3 class="category-widget-title"><?php esc_html_e( 'All Categories', 'krypton' ); ?></h3>
				<ul class="category-widget-list" id="allCategoryList">
					<?php foreach ( $all_categories as $a_cat ) : 
						$is_active = ( $a_cat->term_id === $current_cat_id );
					?>
						<li class="category-widget-item<?php echo $is_active ? ' is-active' : ''; ?>" data-cat-name="<?php echo esc_attr( strtolower( $a_cat->name ) ); ?>">
							<a href="<?php echo esc_url( get_category_link( $a_cat->term_id ) ); ?>" class="category-widget-link">
								<?php echo esc_html( $a_cat->name ); ?>
							</a>
						</li>
					<?php endforeach; ?>
				</ul>
				<p class="category-no-match-message" id="categoryNoMatch" style="display: none;">
					<?php esc_html_e( 'No matching categories found.', 'krypton' ); ?>
				</p>
			</div>
			<?php endif; ?>

		</aside>

		<!-- Right Column: Articles from Category -->
		<main id="primary" class="category-main-content" role="main">
			
			<!-- Category Page Header -->
			<div class="category-content-header">
				<h1 class="category-heading-title">
					<?php
					/* translators: %s: Category Name */
					printf( esc_html__( 'Article from “%s” Category', 'krypton' ), esc_html( $current_title ) );
					?>
				</h1>

				<!-- Category Switcher Dropdown -->
				<?php if ( ! empty( $all_categories ) ) : ?>
				<div class="category-dropdown-wrapper">
					<label for="categoryQuickSelect" class="screen-reader-text"><?php esc_html_e( 'Switch Category', 'krypton' ); ?></label>
					<div class="custom-select-container">
						<select id="categoryQuickSelect" class="category-quick-select" onchange="if (this.value) window.location.href=this.value;">
							<option value=""><?php esc_html_e( 'Select Category', 'krypton' ); ?></option>
							<?php foreach ( $all_categories as $dropdown_cat ) : ?>
								<option value="<?php echo esc_url( get_category_link( $dropdown_cat->term_id ) ); ?>" <?php selected( $dropdown_cat->term_id, $current_cat_id ); ?>>
									<?php echo esc_html( $dropdown_cat->name ); ?>
								</option>
							<?php endforeach; ?>
						</select>
						<span class="select-chevron-icon" aria-hidden="true">
							<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
								<polyline points="6 9 12 15 18 9"></polyline>
							</svg>
						</span>
					</div>
				</div>
				<?php endif; ?>
			</div>

			<!-- Divider line -->
			<div class="category-header-divider" aria-hidden="true"></div>

			<!-- Dynamic Post Loop -->
			<?php if ( have_posts() ) : ?>
				<div class="category-articles-grid">
					<?php
					$card_counter = 0;
					while ( have_posts() ) :
						the_post();
						$tint_color = $card_tint_colors[ $card_counter % count( $card_tint_colors ) ];
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
								<h2 class="post-card-title">
									<a href="<?php the_permalink(); ?>">
										<?php the_title(); ?>
									</a>
								</h2>

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
				<div class="category-empty-state">
					<div class="empty-state-icon">
						<svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="#6EA590" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round">
							<circle cx="12" cy="12" r="10"></circle>
							<line x1="12" y1="8" x2="12" y2="12"></line>
							<line x1="12" y1="16" x2="12.01" y2="16"></line>
						</svg>
					</div>
					<h2 class="empty-state-title"><?php esc_html_e( 'No articles found in this category yet.', 'krypton' ); ?></h2>
					<p class="empty-state-text">
						<?php esc_html_e( 'We are actively preparing new clinical research and reviews. Check back soon or browse our other categories in the sidebar.', 'krypton' ); ?>
					</p>
				</div>
			<?php endif; ?>

		</main>

	</div>
</div>

<!-- Inline Live Category Filtering Script -->
<script>
document.addEventListener('DOMContentLoaded', function() {
	var filterInput = document.getElementById('categoryFilterInput');
	if (!filterInput) return;

	var allItems = document.querySelectorAll('.category-widget-item');
	var noMatchMsg = document.getElementById('categoryNoMatch');

	filterInput.addEventListener('input', function(e) {
		var query = e.target.value.toLowerCase().trim();
		var visibleCount = 0;

		allItems.forEach(function(item) {
			var catName = item.getAttribute('data-cat-name') || '';
			if (!query || catName.indexOf(query) !== -1) {
				item.style.display = '';
				visibleCount++;
			} else {
				item.style.display = 'none';
			}
		});

		if (noMatchMsg) {
			noMatchMsg.style.display = (visibleCount === 0 && query.length > 0) ? 'block' : 'none';
		}
	});
});
</script>

<?php
get_footer();
