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

		// Extract H2 headings from post content
		$h2_headings = function_exists( 'krypton_get_post_h2_headings' ) ? krypton_get_post_h2_headings( get_the_ID() ) : array();
		$is_demo_toc = false;

		// If post has no H2 headings in content yet, provide the 21 mockup sections
		if ( empty( $h2_headings ) ) {
			$is_demo_toc = true;
			$demo_headings = array(
				'Overview',
				'What is Gluco Reset',
				'Rating',
				'How We Tested',
				'Who Should Buy',
				'Manufacturer',
				'Product Specifications',
				'How Does It Work',
				'How We Evaluated',
				'Ingredients',
				'Benefits',
				'Safety',
				'Side Effects',
				'Uniqueness',
				'How Effective is it?',
				'How Much Does It Cost?',
				'Customer Review',
				"FAQ's",
				'Contact Service',
				'Final Verdict',
				'Where To Buy?',
			);
			$h2_headings = array();
			foreach ( $demo_headings as $idx => $dh ) {
				$h2_headings[] = array(
					'index' => $idx + 1,
					'id'    => sanitize_title( $dh ),
					'title' => $dh,
				);
			}
		}

		$total_sections = count( $h2_headings );

		// Distribute headings evenly across up to 3 columns
		$columns_count = min( 3, max( 1, (int) ceil( $total_sections / 7 ) ) );
		$per_col       = (int) ceil( $total_sections / $columns_count );
		$columns       = array_chunk( $h2_headings, $per_col > 0 ? $per_col : 1 );

		// Column Header Labels
		$column_titles = array(
			0 => __( 'OVERVIEW', 'krypton' ),
			1 => __( 'THE FORMULA', 'krypton' ),
			2 => __( 'BUYING GUIDE', 'krypton' ),
		);
		?>
		<article id="post-<?php the_ID(); ?>" <?php post_class( 'krypton-single-post' ); ?>>
			
			<!-- Top Header Section (Exact Design Matched) -->
			<header class="entry-header single-post-header">
				<div class="krypton-container single-header-container">
					
					<!-- 1. Breadcrumbs Navigation Trail -->
					<nav class="single-breadcrumbs" aria-label="<?php esc_attr_e( 'Breadcrumb', 'krypton' ); ?>">
						<ol class="breadcrumb-list">
							<li class="breadcrumb-item">
								<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="breadcrumb-link font-inter">
									<?php esc_html_e( 'Home', 'krypton' ); ?>
								</a>
							</li>
							
							<?php if ( $primary_category ) : ?>
								<li class="breadcrumb-separator" aria-hidden="true">
									<svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"></polyline></svg>
								</li>
								<li class="breadcrumb-item">
									<a href="<?php echo esc_url( get_category_link( $primary_category->term_id ) ); ?>" class="breadcrumb-category-link font-inter">
										<?php echo esc_html( $primary_category->name ); ?>
									</a>
								</li>
							<?php endif; ?>

							<li class="breadcrumb-separator" aria-hidden="true">
								<svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"></polyline></svg>
							</li>
							<li class="breadcrumb-item breadcrumb-item-current" aria-current="page">
								<span class="breadcrumb-product-pill font-inter">
									<?php echo esc_html( $supplement_name ); ?>
								</span>
							</li>
						</ol>
					</nav>

					<!-- 2. Post Title (h1) -->
					<?php the_title( '<h1 class="entry-title single-post-title font-montserrat">', '</h1>' ); ?>

					<!-- 3. Editorial / Trust Badges -->
					<div class="single-trust-badges" role="region" aria-label="<?php esc_attr_e( 'Editorial transparency', 'krypton' ); ?>">
						
						<!-- Disclaimer Badge -->
						<span class="trust-badge">
							<svg class="trust-badge-icon" width="16" height="16" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
								<circle cx="10" cy="10" r="8.5" stroke="#68D391" stroke-width="1.6" />
								<path d="M6.5 10.2L8.8 12.5L13.8 7.5" stroke="#38A169" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" />
							</svg>
							<span class="trust-badge-label font-inter"><?php esc_html_e( 'Disclaimer', 'krypton' ); ?></span>
						</span>

						<span class="trust-badge-divider" aria-hidden="true"></span>

						<!-- Fact Check Badge -->
						<span class="trust-badge">
							<svg class="trust-badge-icon" width="16" height="16" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
								<circle cx="10" cy="10" r="8.5" stroke="#68D391" stroke-width="1.6" />
								<path d="M6.5 10.2L8.8 12.5L13.8 7.5" stroke="#38A169" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" />
							</svg>
							<span class="trust-badge-label font-inter"><?php esc_html_e( 'Fact Check', 'krypton' ); ?></span>
						</span>

						<span class="trust-badge-divider" aria-hidden="true"></span>

						<!-- Review Process Badge -->
						<span class="trust-badge">
							<svg class="trust-badge-icon" width="16" height="16" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
								<circle cx="10" cy="10" r="8.5" stroke="#68D391" stroke-width="1.6" />
								<path d="M6.5 10.2L8.8 12.5L13.8 7.5" stroke="#38A169" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" />
							</svg>
							<span class="trust-badge-label font-inter"><?php esc_html_e( 'Review Process', 'krypton' ); ?></span>
						</span>
					</div>

					<!-- 4. Author & Meta Bar (Bounded by Top & Bottom Divider Lines) -->
					<div class="single-author-meta-bar">
						
						<!-- Left Side: Author Information -->
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

						<!-- Right Side: Post Metadata -->
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
			</header>

			<!-- 5. Table of Contents Section (Sticky & Scannable H2 Headings) -->
			<section class="krypton-toc-section krypton-container" aria-label="<?php esc_attr_e( 'Table of Contents', 'krypton' ); ?>">
				<div id="krypton-toc" class="krypton-toc-card">
					
					<!-- Header Containers with Smooth Cross-Fade -->
					<div class="toc-headers-container">
						<!-- 1. Full Expanded Header Bar -->
						<div class="toc-header-bar toc-expanded-header">
							<div class="toc-title-group">
								<h2 class="toc-main-title font-montserrat"><?php esc_html_e( 'Table of Content', 'krypton' ); ?></h2>
								<span class="toc-meta-info font-inter">
									<?php echo sprintf( esc_html__( '%d sections · %d min read', 'krypton' ), $total_sections, (int) $reading_mins ); ?>
								</span>
							</div>
							<button type="button" class="toc-toggle-btn font-inter" id="tocToggleBtn" aria-expanded="true" aria-controls="tocColumnsGrid">
								<span class="toc-toggle-text"><?php esc_html_e( 'Hide', 'krypton' ); ?></span>
								<svg class="toc-toggle-icon" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
									<polyline points="18 15 12 9 6 15"></polyline>
								</svg>
							</button>
						</div>

						<!-- 2. Collapsed Sticky Header Bar (Shown when hidden/sticky as in design mockup) -->
						<div class="toc-header-bar toc-collapsed-header">
							<div class="toc-collapsed-left">
								<span class="toc-collapsed-badge font-inter"><?php esc_html_e( 'CONTENTS', 'krypton' ); ?></span>
								<span class="toc-collapsed-v-divider" aria-hidden="true"></span>
								<span class="toc-collapsed-counter font-inter">
									<span id="tocActiveNumber">01</span> / <?php echo sprintf( '%02d', $total_sections ); ?>
								</span>
								<span id="tocActiveSectionTitle" class="toc-collapsed-section-title font-inter">
									<?php echo esc_html( ! empty( $h2_headings[0]['title'] ) ? $h2_headings[0]['title'] : __( 'Overview', 'krypton' ) ); ?>
								</span>
							</div>
							<div class="toc-collapsed-right">
								<button type="button" class="toc-all-sections-btn font-inter" id="tocAllSectionsBtn" aria-expanded="false" aria-controls="tocColumnsGrid">
									<span class="toc-all-sections-text"><?php esc_html_e( 'All sections', 'krypton' ); ?></span>
									<svg class="toc-all-sections-chevron" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
										<polyline points="6 9 12 15 18 9"></polyline>
									</svg>
								</button>
							</div>
						</div>
					</div>

					<!-- 3. TOC 3-Columns Grid (Smooth Grid Height Transition) -->
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
												</li>
											<?php endforeach; ?>
										</ul>
									</nav>
								</div>
							<?php endforeach; ?>
						</div>
					</div>

					<!-- 4. Bottom Green Accent Line -->
					<div class="toc-bottom-accent-line" aria-hidden="true"></div>

				</div>
			</section>

			<!-- Single Post Body Content -->
			<div class="krypton-container single-body-container">
				<div class="entry-content single-article-content font-inter">
					<?php
					the_content();

					// If the post has no H2 headings in content, render demo H2 sections so TOC anchor links work immediately
					if ( $is_demo_toc ) :
						?>
						<div class="krypton-demo-sections">
							<?php foreach ( $h2_headings as $h2_item ) : ?>
								<div class="krypton-article-section" id="<?php echo esc_attr( $h2_item['id'] ); ?>">
									<h2 class="font-montserrat"><?php echo esc_html( $h2_item['title'] ); ?></h2>
									<p>
										<?php
										printf(
											esc_html__( 'Comprehensive analysis and clinical evaluation regarding %s. In this section, our medical researchers and editorial contributors review published clinical trials, efficacy data, and real-world consumer experiences.', 'krypton' ),
											esc_html( $h2_item['title'] )
										);
										?>
									</p>
								</div>
							<?php endforeach; ?>
						</div>
						<?php
					endif;

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

<!-- Table of Contents Sticky, Collapse, and ScrollSpy Script -->
<script>
document.addEventListener('DOMContentLoaded', function() {
	var tocSection         = document.querySelector('.krypton-toc-section');
	var tocCard            = document.getElementById('krypton-toc');
	var toggleBtn          = document.getElementById('tocToggleBtn');
	var allSectionsBtn     = document.getElementById('tocAllSectionsBtn');
	var activeNumberEl     = document.getElementById('tocActiveNumber');
	var activeSectionTitle = document.getElementById('tocActiveSectionTitle');
	var tocLinks           = document.querySelectorAll('.toc-item-link');

	// Helper: Expand TOC
	function expandTOC() {
		if (!tocCard) return;
		tocCard.classList.remove('is-collapsed');
		tocCard.classList.add('is-manually-expanded');
		if (toggleBtn) toggleBtn.setAttribute('aria-expanded', 'true');
		if (allSectionsBtn) allSectionsBtn.setAttribute('aria-expanded', 'true');
	}

	// Helper: Collapse / Hide TOC into compact bar
	function collapseTOC() {
		if (!tocCard) return;
		tocCard.classList.add('is-collapsed');
		tocCard.classList.remove('is-manually-expanded');
		tocCard.classList.remove('is-dropdown-open');
		if (toggleBtn) toggleBtn.setAttribute('aria-expanded', 'false');
		if (allSectionsBtn) allSectionsBtn.setAttribute('aria-expanded', 'false');
	}

	// 1. Hide Button Trigger (in expanded header)
	if (toggleBtn) {
		toggleBtn.addEventListener('click', function() {
			collapseTOC();
		});
	}

	// 2. All Sections Button Trigger (in collapsed bar)
	if (allSectionsBtn && tocCard) {
		allSectionsBtn.addEventListener('click', function(e) {
			e.stopPropagation();
			var isOpen = tocCard.classList.contains('is-dropdown-open');
			if (isOpen) {
				tocCard.classList.remove('is-dropdown-open');
				allSectionsBtn.setAttribute('aria-expanded', 'false');
			} else {
				tocCard.classList.add('is-dropdown-open');
				allSectionsBtn.setAttribute('aria-expanded', 'true');
			}
		});
	}

	// 3. Smooth Scroll to Heading on TOC link click
	tocLinks.forEach(function(link) {
		link.addEventListener('click', function(e) {
			var targetId = this.getAttribute('data-target');
			if (!targetId) return;
			var targetEl = document.getElementById(targetId);
			if (targetEl) {
				e.preventDefault();
				var headerOffset = 110;
				var elementPosition = targetEl.getBoundingClientRect().top;
				var offsetPosition = elementPosition + window.pageYOffset - headerOffset;

				window.scrollTo({
					top: offsetPosition,
					behavior: 'smooth'
				});

				// Update active state in list
				tocLinks.forEach(function(l) { l.classList.remove('is-active'); });
				this.classList.add('is-active');

				// Update collapsed bar indicators
				var itemIndex = this.getAttribute('data-index');
				var itemTitle = this.getAttribute('data-title');
				if (activeNumberEl && itemIndex) activeNumberEl.textContent = itemIndex;
				if (activeSectionTitle && itemTitle) activeSectionTitle.textContent = itemTitle;

				// Close dropdown if open
				if (tocCard) {
					tocCard.classList.remove('is-dropdown-open');
					if (allSectionsBtn) allSectionsBtn.setAttribute('aria-expanded', 'false');
				}

				// Update URL hash without jumping
				if (history.pushState) {
					history.pushState(null, null, '#' + targetId);
				}
			}
		});
	});

	// 4. Automatically Hide TOC when entering Sticky position; restore when scrolled back up
	if (tocSection && tocCard) {
		var tocInitialOffset = 0;
		var wasSticky = false;

		function getInitialOffset() {
			if (!tocInitialOffset || window.pageYOffset < 10) {
				var rect = tocSection.getBoundingClientRect();
				tocInitialOffset = rect.top + window.pageYOffset;
			}
			return tocInitialOffset;
		}

		function updateStickyState() {
			var initialTop = getInitialOffset();
			var scrollY = window.pageYOffset;

			// Sticky enter at 85px header threshold; 35px buffer exit threshold to prevent jitter/oscillation
			var stickyEnter = initialTop - 85;
			var stickyExit  = initialTop - 120;

			if (scrollY > stickyEnter && !wasSticky) {
				tocSection.classList.add('is-scrolled-sticky');
				collapseTOC();
				wasSticky = true;
			} else if (scrollY < stickyExit && wasSticky) {
				tocSection.classList.remove('is-scrolled-sticky');
				expandTOC();
				wasSticky = false;
			}
		}

		window.addEventListener('scroll', updateStickyState, { passive: true });
		window.addEventListener('resize', function() {
			tocInitialOffset = 0;
			updateStickyState();
		}, { passive: true });
		window.requestAnimationFrame(updateStickyState);
	}

	// 5. ScrollSpy: Highlight active H2 and update collapsed bar as user reads down the page
	var headingIds = [];
	tocLinks.forEach(function(link) {
		var id = link.getAttribute('data-target');
		if (id && document.getElementById(id)) {
			headingIds.push(id);
		}
	});

	if (headingIds.length > 0 && 'IntersectionObserver' in window) {
		var observer = new IntersectionObserver(function(entries) {
			entries.forEach(function(entry) {
				if (entry.isIntersecting) {
					var activeId = entry.target.id;
					tocLinks.forEach(function(link) {
						if (link.getAttribute('data-target') === activeId) {
							link.classList.add('is-active');
							var itemIndex = link.getAttribute('data-index');
							var itemTitle = link.getAttribute('data-title');
							if (activeNumberEl && itemIndex) activeNumberEl.textContent = itemIndex;
							if (activeSectionTitle && itemTitle) activeSectionTitle.textContent = itemTitle;
						} else {
							link.classList.remove('is-active');
						}
					});
				}
			});
		}, {
			rootMargin: '-100px 0px -55% 0px',
			threshold: 0
		});

		headingIds.forEach(function(id) {
			var el = document.getElementById(id);
			if (el) observer.observe(el);
		});
	}
});
</script>

<?php
get_footer();
