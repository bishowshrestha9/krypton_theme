<?php
/**
 * The template for displaying all single posts.
 *
 * @package Krypton
 */

get_header();
?>

<main id="primary" class="site-main single-post-wrapper">
	<?php
	while ( have_posts() ) :
		the_post();

		// Breadcrumbs Data
		$categories       = get_the_category();
		$primary_category = ! empty( $categories ) ? $categories[0] : null;

		// Supplement Name / Product Name fallback
		$supplement_name = get_post_meta( get_the_ID(), '_krypton_supplement_name', true );
		if ( empty( $supplement_name ) ) {
			$supplement_name = get_post_meta( get_the_ID(), 'supplement_name', true );
		}
		if ( empty( $supplement_name ) ) {
			$supplement_name = get_the_title();
		}

		// Dates
		$pub_date = get_the_date( 'M j, Y' );
		$mod_date = get_the_modified_date( 'M j, Y' );

		// Author Info (Concise title/role under author name)
		$author_id    = get_the_author_meta( 'ID' );
		$author_name  = get_the_author();
		$author_title = get_the_author_meta( 'author_title', $author_id );
		if ( empty( $author_title ) ) {
			$raw_bio = get_the_author_meta( 'description' );
			if ( ! empty( $raw_bio ) ) {
				$clean_bio = trim( wp_strip_all_tags( $raw_bio ) );
				if ( count( preg_split( '/\s+/', $clean_bio ) ) <= 6 ) {
					$author_bio = $clean_bio;
				} elseif ( preg_match( '/\b(?:is|as)\s+an?\s+([^,.]+?)(?:\s+(?:with|who|and|\.)|,)/i', $clean_bio, $matches ) ) {
					$author_bio = wp_trim_words( trim( $matches[1] ), 6, '...' );
				} else {
					$author_bio = wp_trim_words( $clean_bio, 5, '...' );
				}
			} else {
				$author_bio = __( 'Medical Researcher & Contributor', 'krypton' );
			}
		} else {
			$author_bio = $author_title;
		}

		// Reviewer Info: Dynamic Post Author Name (with custom override support if set)
		$custom_reviewer = get_post_meta( get_the_ID(), '_krypton_reviewed_by', true );
		if ( empty( $custom_reviewer ) ) {
			$custom_reviewer = get_post_meta( get_the_ID(), 'reviewed_by', true );
		}
		$reviewed_by = ! empty( $custom_reviewer ) ? $custom_reviewer : $author_name;

		// Reading Time
		$reading_mins = function_exists( 'krypton_reading_time' ) ? krypton_reading_time( get_the_ID() ) : 10;

		// Extract genuine H2 headings from post content
		$h2_headings    = function_exists( 'krypton_get_post_h2_headings' ) ? krypton_get_post_h2_headings( get_the_ID() ) : array();
		$total_sections = count( $h2_headings );

		// Distribute headings evenly across up to 3 columns if headings exist
		$columns       = array();
		$column_titles = array(
			0 => __( 'OVERVIEW', 'krypton' ),
			1 => __( 'THE FORMULA', 'krypton' ),
			2 => __( 'BUYING GUIDE', 'krypton' ),
		);
		if ( $total_sections > 0 ) {
			$columns_count = min( 3, max( 1, (int) ceil( $total_sections / 7 ) ) );
			$per_col       = (int) ceil( $total_sections / $columns_count );
			$columns       = array_chunk( $h2_headings, $per_col > 0 ? $per_col : 1 );
		}
		?>
		<article id="post-<?php the_ID(); ?>" <?php post_class( 'krypton-single-post' ); ?>>
			
			<!-- Top Header Section (Exact Design Matched) -->
			<header class="entry-header single-post-header">
				<div class="krypton-container single-header-container">
					
					<!-- 1. Post Title (h1) -->
					<?php the_title( '<h1 class="entry-title single-post-title font-montserrat">', '</h1>' ); ?>

					<!-- 3. Combined Author & Editorial Trust Bar (Bounded by Top & Bottom Divider Lines) -->
					<div class="single-author-meta-bar">
						
						<!-- Top Row: Author Profile on Left + Trust Badges on Right -->
						<div class="single-author-top-row">
							<div class="single-author-info">
								<a href="<?php echo esc_url( get_author_posts_url( $author_id ) ); ?>" class="author-avatar-link" aria-label="<?php echo esc_attr( $author_name ); ?>">
									<div class="author-avatar-container">
										<?php
										$avatar_html = get_avatar( $author_id, 44, '', esc_attr( $author_name ), array( 'class' => 'author-avatar-img' ) );
										echo $avatar_html;
										?>
									</div>
								</a>
								<div class="author-text-meta">
									<a href="<?php echo esc_url( get_author_posts_url( $author_id ) ); ?>" class="author-name-link font-montserrat">
										<span class="author-name"><?php echo esc_html( $author_name ); ?></span>
									</a>
									<span class="author-bio-title font-inter"><?php echo esc_html( $author_bio ); ?></span>
								</div>
							</div>

							<!-- Combined Editorial / Trust Badges (Disclaimer, Fact Check, Review Process) -->
							<div class="single-trust-badges" role="region" aria-label="<?php esc_attr_e( 'Editorial transparency', 'krypton' ); ?>">
								<!-- Disclaimer Badge (Linked to Disclaimer Page) -->
								<a href="<?php echo esc_url( home_url( '/disclaimer/' ) ); ?>" class="trust-badge trust-badge-link" aria-label="<?php esc_attr_e( 'Read our Disclaimer', 'krypton' ); ?>">
									<svg class="trust-badge-icon" width="14" height="14" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
										<circle cx="10" cy="10" r="8.5" stroke="#68D391" stroke-width="1.6" />
										<path d="M6.5 10.2L8.8 12.5L13.8 7.5" stroke="#38A169" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" />
									</svg>
									<span class="trust-badge-label font-inter"><?php esc_html_e( 'Disclaimer', 'krypton' ); ?></span>
								</a>

								<span class="trust-badge-divider" aria-hidden="true"></span>

								<!-- Fact Check Badge (Triggers Fact Check Modal Popup) -->
								<button type="button" class="trust-badge trust-badge-btn" data-modal-target="factCheckModal" aria-haspopup="dialog" aria-expanded="false" aria-controls="factCheckModal" aria-label="<?php esc_attr_e( 'View our Fact-Checking Standards', 'krypton' ); ?>">
									<svg class="trust-badge-icon" width="14" height="14" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
										<circle cx="10" cy="10" r="8.5" stroke="#68D391" stroke-width="1.6" />
										<path d="M6.5 10.2L8.8 12.5L13.8 7.5" stroke="#38A169" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" />
									</svg>
									<span class="trust-badge-label font-inter"><?php esc_html_e( 'Fact Check', 'krypton' ); ?></span>
								</button>

								<span class="trust-badge-divider" aria-hidden="true"></span>

								<!-- Review Process Badge (Triggers Review Process Modal Popup) -->
								<button type="button" class="trust-badge trust-badge-btn" data-modal-target="reviewProcessModal" aria-haspopup="dialog" aria-expanded="false" aria-controls="reviewProcessModal" aria-label="<?php esc_attr_e( 'View our Review Process Methodology', 'krypton' ); ?>">
									<svg class="trust-badge-icon" width="14" height="14" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
										<circle cx="10" cy="10" r="8.5" stroke="#68D391" stroke-width="1.6" />
										<path d="M6.5 10.2L8.8 12.5L13.8 7.5" stroke="#38A169" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" />
									</svg>
									<span class="trust-badge-label font-inter"><?php esc_html_e( 'Review Process', 'krypton' ); ?></span>
								</button>
							</div>
						</div>

						<!-- Bottom Row: Post Metadata -->
						<div class="single-author-bottom-row">
							<div class="single-meta-details font-inter">
								<!-- 1. Publication Date -->
								<time class="meta-item meta-date" datetime="<?php echo esc_attr( get_the_date( 'c' ) ); ?>">
									<?php echo esc_html( $pub_date ); ?>
								</time>

								<!-- 2. Last Modified Date (if available) -->
								<?php if ( $mod_date && ( $mod_date !== $pub_date || get_the_modified_time( 'U' ) > get_the_time( 'U' ) ) ) : ?>
									<span class="meta-item meta-modified">
										(<?php printf( esc_html__( 'Updated on: %s', 'krypton' ), esc_html( $mod_date ) ); ?>)
									</span>
								<?php endif; ?>

								<span class="meta-slash" aria-hidden="true">/</span>

								<!-- 3. 'Reviewed By' Indicator -->
								<span class="meta-item meta-reviewed">
									<?php
									/* translators: %s: Reviewer link or name */
									printf(
										esc_html__( 'Reviewed By %s', 'krypton' ),
										'<a href="' . esc_url( get_author_posts_url( $author_id ) ) . '" class="author-reviewer-link font-montserrat">' . esc_html( $reviewed_by ) . '</a>'
									);
									?>
								</span>

								<span class="meta-slash" aria-hidden="true">/</span>

								<!-- 4. Estimated Reading Time -->
								<span class="meta-item meta-reading-time">
									<?php printf( esc_html__( '%d min read', 'krypton' ), (int) $reading_mins ); ?>
								</span>
							</div>
						</div>

					</div>

				</div>
			</header>

			<?php if ( ! empty( $h2_headings ) ) : ?>
			<!-- 4. Table of Contents Section (In-Page Static Card - Collapsed at First) -->
			<section class="krypton-toc-section krypton-container" id="kryptonTocSection" aria-label="<?php esc_attr_e( 'Table of Contents', 'krypton' ); ?>">
				<div id="krypton-toc" class="krypton-toc-card is-collapsed">
					
					<!-- Header Bar -->
					<div class="toc-header-bar">
						<div class="toc-title-group">
							<h2 class="toc-main-title font-montserrat"><?php esc_html_e( 'Table of Content', 'krypton' ); ?></h2>
							<span class="toc-meta-info font-inter">
								<?php echo sprintf( esc_html__( '%d sections · %d min read', 'krypton' ), $total_sections, (int) $reading_mins ); ?>
							</span>
						</div>
						<button type="button" class="toc-toggle-btn font-inter" id="tocToggleBtn" aria-expanded="false" aria-controls="tocColumnsGrid">
							<span class="toc-toggle-text"><?php esc_html_e( 'Show', 'krypton' ); ?></span>
							<svg class="toc-toggle-icon" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
								<polyline points="18 15 12 9 6 15"></polyline>
							</svg>
						</button>
					</div>

					<!-- TOC 3-Columns Grid (Smooth Height Transition on Manual Toggle) -->
					<div class="toc-columns-wrapper" id="tocColumnsWrapper">
						<div class="toc-columns-grid" id="tocColumnsGrid">
							<?php foreach ( $columns as $col_index => $col_items ) : ?>
								<div class="toc-column">
									<div class="toc-column-header font-inter">
										<?php echo esc_html( isset( $column_titles[ $col_index ] ) ? $column_titles[ $col_index ] : sprintf( __( 'SECTION %d', 'krypton' ), $col_index + 1 ) ); ?>
									</div>
									<nav class="toc-items-nav">
										<ul class="toc-items-list">
											<?php foreach ( $col_items as $item ) : 
												$is_first = ( 1 === (int) $item['index'] );
											?>
												<li class="toc-item">
													<a href="#<?php echo esc_attr( $item['id'] ); ?>" class="toc-item-link font-inter <?php echo $is_first ? 'is-active' : ''; ?>" data-target="<?php echo esc_attr( $item['id'] ); ?>" data-index="<?php echo sprintf( '%02d', $item['index'] ); ?>" data-title="<?php echo esc_attr( $item['title'] ); ?>">
														<span class="toc-item-number"><?php echo sprintf( '%02d', $item['index'] ); ?></span>
														<span class="toc-item-text"><?php echo esc_html( $item['title'] ); ?></span>
														<svg class="toc-item-chevron" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
															<polyline points="9 18 15 12 9 6"></polyline>
														</svg>
													</a>
													<?php if ( ! empty( $item['children'] ) ) : ?>
														<ul class="toc-subitems-list">
															<?php foreach ( $item['children'] as $child ) : ?>
																<li class="toc-subitem">
																	<a href="#<?php echo esc_attr( $child['id'] ); ?>" class="toc-item-link toc-subitem-link font-inter" data-target="<?php echo esc_attr( $child['id'] ); ?>" data-index="<?php echo esc_attr( $child['index'] ); ?>" data-title="<?php echo esc_attr( $child['title'] ); ?>">
																		<span class="toc-subitem-bullet" aria-hidden="true">↳</span>
																		<span class="toc-subitem-text"><?php echo esc_html( $child['title'] ); ?></span>
																	</a>
																</li>
															<?php endforeach; ?>
														</ul>
													<?php endif; ?>
												</li>
											<?php endforeach; ?>
										</ul>
									</nav>
								</div>
							<?php endforeach; ?>
						</div>
					</div>

				</div>
			</section>

			<!-- 5. Floating Sticky Table of Contents Bar (Fixed Docked Below Site Header on Scroll) -->
			<aside id="kryptonStickyTocBar" class="krypton-sticky-toc-bar font-inter" aria-label="<?php esc_attr_e( 'Sticky Table of Contents', 'krypton' ); ?>" aria-hidden="true">
				<div class="krypton-container sticky-toc-container">
					<div class="sticky-toc-pill" id="stickyTocPill">
						
						<!-- Sticky Bar Top Row (CONTENTS | 01 / 21 Section Title ... All sections ⌵) -->
						<div class="sticky-toc-bar-content">
							<div class="sticky-toc-left">
								<span class="toc-collapsed-badge font-inter"><?php esc_html_e( 'CONTENTS', 'krypton' ); ?></span>
								<span class="toc-collapsed-v-divider" aria-hidden="true"></span>
								<span class="toc-collapsed-counter font-inter">
									<span id="stickyTocActiveNumber">01</span> / <?php echo sprintf( '%02d', $total_sections ); ?>
								</span>
								<span id="stickyTocActiveSectionTitle" class="toc-collapsed-section-title font-inter">
									<?php echo esc_html( ! empty( $h2_headings[0]['title'] ) ? $h2_headings[0]['title'] : __( 'Overview', 'krypton' ) ); ?>
								</span>
							</div>
							<div class="sticky-toc-right">
								<button type="button" class="toc-all-sections-btn font-inter" id="stickyTocDropdownBtn" aria-expanded="false" aria-controls="stickyTocDropdown">
									<span class="toc-all-sections-text"><?php esc_html_e( 'All sections', 'krypton' ); ?></span>
									<svg class="toc-all-sections-chevron" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
										<polyline points="6 9 12 15 18 9"></polyline>
									</svg>
								</button>
							</div>
						</div>

						<!-- Bottom Accent Line in Brand Green -->
						<div class="sticky-toc-accent-line" aria-hidden="true"></div>

						<!-- Sticky Sections Dropdown Panel (Overlay) -->
						<div class="sticky-toc-dropdown-panel" id="stickyTocDropdown">
							<div class="toc-columns-grid">
								<?php foreach ( $columns as $col_index => $col_items ) : ?>
									<div class="toc-column">
										<div class="toc-column-header font-inter">
											<?php echo esc_html( isset( $column_titles[ $col_index ] ) ? $column_titles[ $col_index ] : sprintf( __( 'SECTION %d', 'krypton' ), $col_index + 1 ) ); ?>
										</div>
										<nav class="toc-items-nav">
											<ul class="toc-items-list">
												<?php foreach ( $col_items as $item ) : 
													$is_first = ( 1 === (int) $item['index'] );
												?>
													<li class="toc-item">
														<a href="#<?php echo esc_attr( $item['id'] ); ?>" class="toc-item-link font-inter <?php echo $is_first ? 'is-active' : ''; ?>" data-target="<?php echo esc_attr( $item['id'] ); ?>" data-index="<?php echo sprintf( '%02d', $item['index'] ); ?>" data-title="<?php echo esc_attr( $item['title'] ); ?>">
															<span class="toc-item-number"><?php echo sprintf( '%02d', $item['index'] ); ?></span>
															<span class="toc-item-text"><?php echo esc_html( $item['title'] ); ?></span>
															<svg class="toc-item-chevron" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
																<polyline points="9 18 15 12 9 6"></polyline>
															</svg>
														</a>
														<?php if ( ! empty( $item['children'] ) ) : ?>
															<ul class="toc-subitems-list">
																<?php foreach ( $item['children'] as $child ) : ?>
																	<li class="toc-subitem">
																		<a href="#<?php echo esc_attr( $child['id'] ); ?>" class="toc-item-link toc-subitem-link font-inter" data-target="<?php echo esc_attr( $child['id'] ); ?>" data-index="<?php echo esc_attr( $child['index'] ); ?>" data-title="<?php echo esc_attr( $child['title'] ); ?>">
																			<span class="toc-subitem-bullet" aria-hidden="true">↳</span>
																			<span class="toc-subitem-text"><?php echo esc_html( $child['title'] ); ?></span>
																		</a>
																	</li>
																<?php endforeach; ?>
															</ul>
														<?php endif; ?>
													</li>
												<?php endforeach; ?>
											</ul>
										</nav>
									</div>
								<?php endforeach; ?>
							</div>
						</div>

					</div>
				</div>
			</aside>
			<?php endif; ?>

			<!-- Single Post Body Content -->
			<div class="krypton-container single-body-container">
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

				<?php if ( has_tag() ) : ?>
					<footer class="single-post-footer">
						<div class="single-tags-wrap font-inter">
							<span class="tags-label font-montserrat"><?php esc_html_e( 'Tags:', 'krypton' ); ?></span>
							<?php the_tags( '<div class="tag-list">', '', '</div>' ); ?>
						</div>
					</footer>
				<?php endif; ?>

				<?php
				// Comments Section
				if ( comments_open() || get_comments_number() ) :
					comments_template();
				endif;
				?>
			</div>

		</article>
		<?php
	endwhile;
	?>
</main>

<!-- Table of Contents Toggle, Sticky Bar, and ScrollSpy Script -->
<script>
document.addEventListener('DOMContentLoaded', function() {
	var inPageTocSection   = document.getElementById('kryptonTocSection');
	var inPageTocCard      = document.getElementById('krypton-toc');
	var inPageToggleBtn    = document.getElementById('tocToggleBtn');
	var inPageToggleText   = inPageToggleBtn ? inPageToggleBtn.querySelector('.toc-toggle-text') : null;

	var stickyBar          = document.getElementById('kryptonStickyTocBar');
	var stickyPill         = document.getElementById('stickyTocPill');
	var stickyDropdownBtn  = document.getElementById('stickyTocDropdownBtn');
	var stickyNumberEl     = document.getElementById('stickyTocActiveNumber');
	var stickyTitleEl      = document.getElementById('stickyTocActiveSectionTitle');

	var articleContent     = document.querySelector('.single-article-content');

	// All TOC links across both in-page and sticky dropdown
	var allTocLinks        = document.querySelectorAll('.toc-item-link');

	// 1. Get Top Offset (Accounts for WordPress admin bar if user is logged in)
	function getTopOffset() {
		var adminBar = document.getElementById('wpadminbar');
		return (adminBar && window.getComputedStyle(adminBar).position === 'fixed') ? adminBar.offsetHeight : 0;
	}

	// 2. In-Page TOC Manual Toggle (Hide / Show)
	if (inPageToggleBtn && inPageTocCard) {
		inPageToggleBtn.addEventListener('click', function() {
			var isCollapsed = inPageTocCard.classList.toggle('is-collapsed');
			inPageToggleBtn.setAttribute('aria-expanded', isCollapsed ? 'false' : 'true');
			if (inPageToggleText) {
				inPageToggleText.textContent = isCollapsed ? '<?php echo esc_js( __( 'Show', 'krypton' ) ); ?>' : '<?php echo esc_js( __( 'Hide', 'krypton' ) ); ?>';
			}
		});
	}

	// 3. Sticky Bar Visibility & Scroll Management (Zero layout shift, 100% flicker-free)
	if (stickyBar && inPageTocSection) {
		var isTicking = false;

		function updateStickyBarVisibility() {
			var topOffset = getTopOffset();
			var tocRect = inPageTocSection.getBoundingClientRect();
			var contentRect = articleContent ? articleContent.getBoundingClientRect() : null;

			// Trigger sticky bar as soon as the in-page TOC bottom scrolls past the top of the viewport
			var isPastInPageToc = (tocRect.bottom < topOffset + 10);
			// Hide sticky bar when user reaches the bottom of the article
			var isBeforeArticleEnd = contentRect ? (contentRect.bottom > topOffset + 80) : true;

			if (isPastInPageToc && isBeforeArticleEnd) {
				stickyBar.classList.add('is-visible');
				stickyBar.setAttribute('aria-hidden', 'false');
			} else {
				stickyBar.classList.remove('is-visible');
				stickyBar.setAttribute('aria-hidden', 'true');
				// Close dropdown if open
				if (stickyPill) stickyPill.classList.remove('is-dropdown-open');
				if (stickyDropdownBtn) stickyDropdownBtn.setAttribute('aria-expanded', 'false');
			}
			isTicking = false;
		}

		window.addEventListener('scroll', function() {
			if (!isTicking) {
				window.requestAnimationFrame(updateStickyBarVisibility);
				isTicking = true;
			}
		}, { passive: true });

		window.addEventListener('load', updateStickyBarVisibility);
		updateStickyBarVisibility();
	}

	// 4. Sticky Bar Dropdown Toggle (All sections ⌵)
	if (stickyDropdownBtn && stickyPill) {
		stickyDropdownBtn.addEventListener('click', function(e) {
			e.stopPropagation();
			var isOpen = stickyPill.classList.toggle('is-dropdown-open');
			stickyDropdownBtn.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
		});

		// Close dropdown when clicking outside
		document.addEventListener('click', function(e) {
			if (stickyPill.classList.contains('is-dropdown-open') && !stickyPill.contains(e.target)) {
				stickyPill.classList.remove('is-dropdown-open');
				stickyDropdownBtn.setAttribute('aria-expanded', 'false');
			}
		});

		// Close on Escape key
		document.addEventListener('keydown', function(e) {
			if (e.key === 'Escape' && stickyPill.classList.contains('is-dropdown-open')) {
				stickyPill.classList.remove('is-dropdown-open');
				stickyDropdownBtn.setAttribute('aria-expanded', 'false');
			}
		});
	}

	// 5. Smooth Scroll to Heading on Link Click (Both In-Page and Sticky Dropdown)
	allTocLinks.forEach(function(link) {
		link.addEventListener('click', function(e) {
			var targetId = this.getAttribute('data-target');
			if (!targetId) return;
			var targetEl = document.getElementById(targetId);
			if (targetEl) {
				e.preventDefault();
				
				// Close sticky dropdown if open
				if (stickyPill) {
					stickyPill.classList.remove('is-dropdown-open');
					if (stickyDropdownBtn) stickyDropdownBtn.setAttribute('aria-expanded', 'false');
				}

				var topOffset = getTopOffset();
				var stickyH = stickyPill ? stickyPill.offsetHeight : 52;
				// Offset takes into account top/admin bar + sticky pill + breathing room
				var totalOffset = topOffset + stickyH + 16;
				var elementPosition = targetEl.getBoundingClientRect().top;
				var offsetPosition = elementPosition + window.pageYOffset - totalOffset;

				window.scrollTo({
					top: Math.max(0, offsetPosition),
					behavior: 'smooth'
				});

				// Highlight link
				var itemIndex = this.getAttribute('data-index');
				var itemTitle = this.getAttribute('data-title');
				setActiveHeading(targetId, itemIndex, itemTitle);

				// Update URL hash smoothly
				if (history.pushState) {
					history.pushState(null, null, '#' + targetId);
				}
			}
		});
	});

	// Helper: Set Active Heading State across both in-page and sticky bar
	function setActiveHeading(activeId, index, title) {
		allTocLinks.forEach(function(link) {
			if (link.getAttribute('data-target') === activeId) {
				link.classList.add('is-active');
			} else {
				link.classList.remove('is-active');
			}
		});
		if (stickyNumberEl && index) stickyNumberEl.textContent = index;
		if (stickyTitleEl && title) stickyTitleEl.textContent = title;
	}

	// 6. ScrollSpy: Highlight active H2 & update sticky bar indicator as reader scrolls
	var headingElements = [];
	allTocLinks.forEach(function(link) {
		var id = link.getAttribute('data-target');
		if (id) {
			var el = document.getElementById(id);
			if (el && headingElements.indexOf(el) === -1) {
				headingElements.push(el);
			}
		}
	});

	if (headingElements.length > 0 && 'IntersectionObserver' in window) {
		var observer = new IntersectionObserver(function(entries) {
			entries.forEach(function(entry) {
				if (entry.isIntersecting) {
					var activeId = entry.target.id;
					var matchedLink = document.querySelector('.toc-item-link[data-target="' + activeId + '"]');
					if (matchedLink) {
						var itemIndex = matchedLink.getAttribute('data-index');
						var itemTitle = matchedLink.getAttribute('data-title');
						setActiveHeading(activeId, itemIndex, itemTitle);
					}
				}
			});
		}, {
			rootMargin: '-70px 0px -60% 0px',
			threshold: 0
		});

		headingElements.forEach(function(el) {
			observer.observe(el);
		});
	}

	// 7. Trust Badge Modals Controller (Fact Check & Review Process Popups)
	var modalTriggers = document.querySelectorAll('[data-modal-target]');
	var activeModal = null;
	var lastActiveTrigger = null;

	function openModal(modalId, triggerEl) {
		var modal = document.getElementById(modalId);
		if (!modal) return;
		activeModal = modal;
		lastActiveTrigger = triggerEl;
		modal.classList.add('is-open');
		modal.setAttribute('aria-hidden', 'false');
		if (triggerEl) triggerEl.setAttribute('aria-expanded', 'true');
		document.body.classList.add('modal-open');

		var focusable = modal.querySelector('.krypton-modal-close-btn, button, [href]');
		if (focusable) focusable.focus();
	}

	function closeModal() {
		if (!activeModal) return;
		activeModal.classList.remove('is-open');
		activeModal.setAttribute('aria-hidden', 'true');
		document.body.classList.remove('modal-open');
		if (lastActiveTrigger) {
			lastActiveTrigger.setAttribute('aria-expanded', 'false');
			lastActiveTrigger.focus();
		}
		activeModal = null;
		lastActiveTrigger = null;
	}

	modalTriggers.forEach(function(btn) {
		btn.addEventListener('click', function(e) {
			e.preventDefault();
			var targetModalId = this.getAttribute('data-modal-target');
			openModal(targetModalId, this);
		});
	});

	document.addEventListener('click', function(e) {
		if (e.target.closest('[data-close-modal]')) {
			e.preventDefault();
			closeModal();
		}
	});

	document.addEventListener('keydown', function(e) {
		if (e.key === 'Escape' && activeModal) {
			closeModal();
		}
	});
});
</script>

<!-- Fact-Checking Standards Modal Popup -->
<div id="factCheckModal" class="krypton-modal-overlay" role="dialog" aria-modal="true" aria-labelledby="factCheckModalTitle" aria-hidden="true">
	<div class="krypton-modal-backdrop" data-close-modal></div>
	<div class="krypton-modal-card">
		<div class="krypton-modal-header">
			<div class="krypton-modal-title-group">
				<span class="krypton-modal-badge font-inter">
					<svg width="15" height="15" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
						<circle cx="10" cy="10" r="8.5" stroke="#68D391" stroke-width="1.6" />
						<path d="M6.5 10.2L8.8 12.5L13.8 7.5" stroke="#38A169" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" />
					</svg>
					<?php esc_html_e( 'Editorial Standard', 'krypton' ); ?>
				</span>
				<h2 id="factCheckModalTitle" class="krypton-modal-title font-montserrat"><?php esc_html_e( 'Fact-Checking & Verification Standards', 'krypton' ); ?></h2>
			</div>
			<button type="button" class="krypton-modal-close-btn" data-close-modal aria-label="<?php esc_attr_e( 'Close modal', 'krypton' ); ?>">
				<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
					<line x1="18" y1="6" x2="6" y2="18"></line>
					<line x1="6" y1="6" x2="18" y2="18"></line>
				</svg>
			</button>
		</div>

		<div class="krypton-modal-body font-inter">
			<p class="krypton-modal-intro">
				<?php esc_html_e( 'Our editorial integrity is anchored in verified scientific data, peer-reviewed clinical research, and strict fact-checking protocols. Here is how we ensure each claim is accurate and unbiased:', 'krypton' ); ?>
			</p>

			<div class="krypton-modal-pillars">
				<div class="krypton-modal-pillar">
					<div class="pillar-icon-box">
						<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#2E7D5B" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
							<path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path>
							<path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"></path>
						</svg>
					</div>
					<div class="pillar-content">
						<h3 class="pillar-title font-montserrat"><?php esc_html_e( 'Primary & Peer-Reviewed Sources', 'krypton' ); ?></h3>
						<p class="pillar-text"><?php esc_html_e( 'Every health or scientific claim is cross-referenced with peer-reviewed medical publications (PubMed, NIH, Lancet) and regulatory guidelines (FDA, FTC).', 'krypton' ); ?></p>
					</div>
				</div>

				<div class="krypton-modal-pillar">
					<div class="pillar-icon-box">
						<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#2E7D5B" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
							<path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path>
						</svg>
					</div>
					<div class="pillar-content">
						<h3 class="pillar-title font-montserrat"><?php esc_html_e( 'Direct Policy & Lab Verification', 'krypton' ); ?></h3>
						<p class="pillar-text"><?php esc_html_e( 'We independently verify manufacturer claims, certificates of analysis (CoA), return terms, and consumer complaints across neutral forums before publishing.', 'krypton' ); ?></p>
					</div>
				</div>

				<div class="krypton-modal-pillar">
					<div class="pillar-icon-box">
						<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#2E7D5B" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
							<circle cx="12" cy="12" r="10"></circle>
							<polyline points="12 6 12 12 14 14"></polyline>
						</svg>
					</div>
					<div class="pillar-content">
						<h3 class="pillar-title font-montserrat"><?php esc_html_e( 'Regular Re-Checking & Updates', 'krypton' ); ?></h3>
						<p class="pillar-text"><?php esc_html_e( 'Our editorial team routinely monitors and audits published articles when formulations, pricing structures, or scientific consensus shifts.', 'krypton' ); ?></p>
					</div>
				</div>
			</div>
		</div>

		<div class="krypton-modal-footer">
			<button type="button" class="krypton-modal-btn font-inter" data-close-modal><?php esc_html_e( 'Understood', 'krypton' ); ?></button>
		</div>
	</div>
</div>

<!-- Review Process Modal Popup -->
<div id="reviewProcessModal" class="krypton-modal-overlay" role="dialog" aria-modal="true" aria-labelledby="reviewProcessModalTitle" aria-hidden="true">
	<div class="krypton-modal-backdrop" data-close-modal></div>
	<div class="krypton-modal-card">
		<div class="krypton-modal-header">
			<div class="krypton-modal-title-group">
				<span class="krypton-modal-badge font-inter">
					<svg width="15" height="15" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
						<circle cx="10" cy="10" r="8.5" stroke="#68D391" stroke-width="1.6" />
						<path d="M6.5 10.2L8.8 12.5L13.8 7.5" stroke="#38A169" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" />
					</svg>
					<?php esc_html_e( 'Review Methodology', 'krypton' ); ?>
				</span>
				<h2 id="reviewProcessModalTitle" class="krypton-modal-title font-montserrat"><?php esc_html_e( 'Our Independent Review Process', 'krypton' ); ?></h2>
			</div>
			<button type="button" class="krypton-modal-close-btn" data-close-modal aria-label="<?php esc_attr_e( 'Close modal', 'krypton' ); ?>">
				<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
					<line x1="18" y1="6" x2="6" y2="18"></line>
					<line x1="6" y1="6" x2="18" y2="18"></line>
				</svg>
			</button>
		</div>

		<div class="krypton-modal-body font-inter">
			<p class="krypton-modal-intro">
				<?php esc_html_e( 'We believe consumers deserve 100% genuine, uncompromised product evaluations. Our testing process follows four rigorous criteria:', 'krypton' ); ?>
			</p>

			<div class="krypton-modal-pillars">
				<div class="krypton-modal-pillar">
					<div class="pillar-icon-box">
						<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#2E7D5B" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
							<rect x="2" y="7" width="20" height="14" rx="2" ry="2"></rect>
							<path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"></path>
						</svg>
					</div>
					<div class="pillar-content">
						<h3 class="pillar-title font-montserrat"><?php esc_html_e( '1. We Purchase Every Product', 'krypton' ); ?></h3>
						<p class="pillar-text"><?php esc_html_e( 'All tested items are purchased with editorial funds like an everyday consumer. We do not accept free review units, sponsored gifts, or pre-approved brand copy.', 'krypton' ); ?></p>
					</div>
				</div>

				<div class="krypton-modal-pillar">
					<div class="pillar-icon-box">
						<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#2E7D5B" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
							<path d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77-3.77a6 6 0 0 1-7.94 7.94l-6.91 6.91a2.12 2.12 0 0 1-3-3l6.91-6.91a6 6 0 0 1 7.94-7.94l-3.76 3.76z"></path>
						</svg>
					</div>
					<div class="pillar-content">
						<h3 class="pillar-title font-montserrat"><?php esc_html_e( '2. Real-World Hands-On Testing', 'krypton' ); ?></h3>
						<p class="pillar-text"><?php esc_html_e( 'Our researchers use products for at least 14 to 30 days under realistic daily conditions to assess ease of use, taste, texture, durability, and effectiveness.', 'krypton' ); ?></p>
					</div>
				</div>

				<div class="krypton-modal-pillar">
					<div class="pillar-icon-box">
						<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#2E7D5B" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
							<polyline points="22 12 18 12 15 21 9 3 6 12 2 12"></polyline>
						</svg>
					</div>
					<div class="pillar-content">
						<h3 class="pillar-title font-montserrat"><?php esc_html_e( '3. Multi-Pillar Objective Scoring', 'krypton' ); ?></h3>
						<p class="pillar-text"><?php esc_html_e( 'Scores are calculated across 5 clear pillars: Ingredient Quality, Efficacy, Cost-to-Value, Safety, and Manufacturer Transparency.', 'krypton' ); ?></p>
					</div>
				</div>
			</div>
		</div>

		<div class="krypton-modal-footer">
			<button type="button" class="krypton-modal-btn font-inter" data-close-modal><?php esc_html_e( 'Understood', 'krypton' ); ?></button>
		</div>
	</div>
</div>

<?php
get_footer();
