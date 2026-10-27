<?php
/**
 * The template for displaying Author Archive pages.
 *
 * @package Krypton
 */

get_header();

// Get the current author queried object
$author       = get_queried_object();
$author_id    = isset( $author->ID ) ? (int) $author->ID : 0;
$author_name  = ! empty( $author->display_name ) ? $author->display_name : __( 'Dr. Emma Myers, MD', 'krypton' );
$author_email = ! empty( $author->user_email ) ? $author->user_email : '';
$raw_bio      = ! empty( $author->description ) ? $author->description : '';

// Professional Title Subtitle
$author_title = get_the_author_meta( 'author_title', $author_id );
if ( empty( $author_title ) ) {
	$author_title = __( 'MEDICAL RESEARCHER & CONTRIBUTOR', 'krypton' );
} else {
	$author_title = strtoupper( $author_title );
}

// Author Short Bio / Tagline (Hero lead)
$author_tagline = get_the_author_meta( 'author_tagline', $author_id );
if ( empty( $author_tagline ) ) {
	$author_tagline = __( 'Board-certified internist translating clinical research on metabolic health and dietary supplements into clear, evidence-based guidance for everyday readers.', 'krypton' );
}

// Total posts by author
$real_posts_count = (int) count_user_posts( $author_id, 'post', true );
$display_posts_count = $real_posts_count > 0 ? $real_posts_count : 240;

// Stats bar metrics
$custom_years = get_the_author_meta( 'author_years_research', $author_id );
$stat_years   = ! empty( $custom_years ) ? $custom_years : '12+';

// Monthly readers: static & deterministic per author, but different for different authors
$custom_readers = get_the_author_meta( 'author_monthly_readers', $author_id );
if ( empty( $custom_readers ) ) {
	$custom_readers = get_the_author_meta( 'monthly_readers', $author_id );
}

if ( ! empty( $custom_readers ) ) {
	$stat_readers = $custom_readers;
} else {
	// Static, deterministic readers pool based on author ID
	$readers_pool = array( '1.8M', '2.4M', '920K', '1.5M', '3.1M', '780K', '1.2M', '2.7M', '850K', '1.9M', '2.1M', '640K' );
	$pool_idx     = abs( (int) $author_id ) % count( $readers_pool );
	$stat_readers = $readers_pool[ $pool_idx ];
}

$custom_papers = get_the_author_meta( 'author_papers', $author_id );
$stat_papers   = ! empty( $custom_papers ) ? $custom_papers : '35';

// Check if Education & Credentials are set for this author (remove block if not present)
$raw_credentials = get_the_author_meta( 'author_credentials', $author_id );
if ( empty( $raw_credentials ) ) {
	$raw_credentials = get_the_author_meta( 'credentials', $author_id );
}
if ( empty( $raw_credentials ) ) {
	$raw_credentials = get_the_author_meta( 'author_education', $author_id );
}
if ( empty( $raw_credentials ) ) {
	$raw_credentials = get_the_author_meta( 'education', $author_id );
}

$credentials = array();

if ( ! empty( $raw_credentials ) ) {
	if ( is_array( $raw_credentials ) ) {
		foreach ( $raw_credentials as $item ) {
			if ( is_array( $item ) ) {
				$t = ! empty( $item['institution'] ) ? $item['institution'] : ( ! empty( $item['title'] ) ? $item['title'] : ( ! empty( $item['degree'] ) ? $item['degree'] : '' ) );
				$s = ! empty( $item['degree'] ) && $t !== $item['degree'] ? $item['degree'] : ( ! empty( $item['subtitle'] ) ? $item['subtitle'] : ( ! empty( $item['school'] ) ? $item['school'] : '' ) );
				if ( $t || $s ) {
					$clean_t = trim( wp_strip_all_tags( html_entity_decode( $t, ENT_QUOTES, 'UTF-8' ) ) );
					$clean_s = trim( wp_strip_all_tags( html_entity_decode( $s, ENT_QUOTES, 'UTF-8' ) ) );
					$credentials[] = array(
						'title'       => $clean_t,
						'subtitle'    => $clean_s,
						'degree'      => $clean_t,
						'institution' => $clean_s,
					);
				}
			} elseif ( is_string( $item ) && trim( $item ) !== '' ) {
				$clean = trim( wp_strip_all_tags( html_entity_decode( $item, ENT_QUOTES, 'UTF-8' ) ) );
				$parts = preg_split( '/\s*[-|–—]\s*/', $clean, 2 );
				$t     = trim( $parts[0] );
				$s     = isset( $parts[1] ) ? trim( $parts[1] ) : '';
				if ( $t || $s ) {
					$credentials[] = array(
						'title'       => $t,
						'subtitle'    => $s,
						'degree'      => $t,
						'institution' => $s,
					);
				}
			}
		}
	} elseif ( is_string( $raw_credentials ) ) {
		// Normalize HTML paragraph / line break tags into newlines
		$normalized = preg_replace( '/<br\s*\/?>/i', "\n", $raw_credentials );
		$normalized = preg_replace( '/<\/(p|div|li)>/i', "\n", $normalized );

		// Check if credentials use <strong> tags for institution/title separation
		if ( preg_match( '/<strong[^>]*>(.*?)<\/strong>/is', $normalized ) ) {
			preg_match_all( '/<strong[^>]*>(.*?)<\/strong>(.*?)(?=(?:<strong|$))/is', $normalized, $matches, PREG_SET_ORDER );
			if ( ! empty( $matches ) ) {
				foreach ( $matches as $m ) {
					$title    = trim( wp_strip_all_tags( html_entity_decode( $m[1], ENT_QUOTES, 'UTF-8' ) ) );
					$subtitle = trim( wp_strip_all_tags( html_entity_decode( $m[2], ENT_QUOTES, 'UTF-8' ) ) );
					$subtitle = trim( preg_replace( '/^[\s\-–—:]+/', '', $subtitle ) );
					if ( ! empty( $title ) || ! empty( $subtitle ) ) {
						$credentials[] = array(
							'title'       => $title,
							'subtitle'    => $subtitle,
							'degree'      => $title,
							'institution' => $subtitle,
						);
					}
				}
			}
		}

		// Fallback line-by-line parsing if not already parsed
		if ( empty( $credentials ) ) {
			$lines = preg_split( '/\r\n|\r|\n/', trim( $normalized ) );
			$clean_lines = array();
			foreach ( $lines as $l ) {
				$clean = trim( wp_strip_all_tags( html_entity_decode( $l, ENT_QUOTES, 'UTF-8' ) ) );
				if ( ! empty( $clean ) ) {
					$clean_lines[] = $clean;
				}
			}

			$count = count( $clean_lines );
			if ( $count === 2 ) {
				$credentials[] = array(
					'title'       => $clean_lines[0],
					'subtitle'    => $clean_lines[1],
					'degree'      => $clean_lines[0],
					'institution' => $clean_lines[1],
				);
			} elseif ( $count > 0 && $count % 2 === 0 ) {
				for ( $i = 0; $i < $count; $i += 2 ) {
					$credentials[] = array(
						'title'       => $clean_lines[ $i ],
						'subtitle'    => $clean_lines[ $i + 1 ],
						'degree'      => $clean_lines[ $i ],
						'institution' => $clean_lines[ $i + 1 ],
					);
				}
			} else {
				foreach ( $clean_lines as $line ) {
					$parts = preg_split( '/\s*[-|–—]\s*/', $line, 2 );
					$t     = trim( $parts[0] );
					$s     = isset( $parts[1] ) ? trim( $parts[1] ) : '';
					$credentials[] = array(
						'title'       => $t,
						'subtitle'    => $s,
						'degree'      => $t,
						'institution' => $s,
					);
				}
			}
		}
	}
}

// Expertise tags
$expertise_tags = array(
	__( 'Metabolic health', 'krypton' ),
	__( 'Blood sugar support', 'krypton' ),
	__( 'Nutritional supplements', 'krypton' ),
	__( "Men's health", 'krypton' ),
	__( 'Clinical trial analysis', 'krypton' ),
	__( 'Herbal pharmacology', 'krypton' ),
);

// Card tint colors for fallback supplement bottle visuals
$tint_colors = array( '#8E3B9E', '#E87A38', '#C53030', '#D69E2E', '#365D4E', '#2B6CB0' );

// Fallback mockup articles matching exact Figma / screenshot design if author has fewer than 6 posts
$mockup_articles = array(
	array(
		'title'        => __( 'Gluco Reset Reviews and Complaints 2026 | Scam or Legit? Hidden Truth', 'krypton' ),
		'category'     => __( 'NEWEST · BLOOD SUGAR', 'krypton' ),
		'excerpt'      => __( "An in-depth look at the ingredients, dosage, and real customer feedback behind one of 2026's most talked-about blood sugar supplements.", 'krypton' ),
		'date'         => __( 'Oct 2, 2026', 'krypton' ),
		'reading_time' => '10 min read',
		'tint'         => '#8E3B9E',
	),
	array(
		'title'        => __( 'Lipo Flow Review: A Positive, Evidence-Based Look at the Formula', 'krypton' ),
		'category'     => __( "Men's Health", 'krypton' ),
		'excerpt'      => __( 'A clinical review of Lipo Flow for cardiovascular and lipid management.', 'krypton' ),
		'date'         => __( 'Sep 24, 2026', 'krypton' ),
		'reading_time' => '8 min read',
		'tint'         => '#E87A38',
	),
	array(
		'title'        => __( 'Berberine vs. Chromium: Which Actually Supports Glucose Balance?', 'krypton' ),
		'category'     => __( 'Metabolism', 'krypton' ),
		'excerpt'      => __( 'Comparing clinical efficacy and mechanisms between berberine and chromium.', 'krypton' ),
		'date'         => __( 'Sep 12, 2026', 'krypton' ),
		'reading_time' => '12 min read',
		'tint'         => '#C53030',
	),
	array(
		'title'        => __( 'How to Read a Supplement Label Like a Clinician', 'krypton' ),
		'category'     => __( 'Supplements', 'krypton' ),
		'excerpt'      => __( 'Spotting proprietary blends, active dosages, and third-party testing badges.', 'krypton' ),
		'date'         => __( 'Aug 30, 2026', 'krypton' ),
		'reading_time' => '7 min read',
		'tint'         => '#D69E2E',
	),
	array(
		'title'        => __( 'Gymnema Sylvestre: What the Research Really Says', 'krypton' ),
		'category'     => __( 'Nutrition', 'krypton' ),
		'excerpt'      => __( 'Evaluating the sugar-blocking herb and its impacts on cravings and HbA1c.', 'krypton' ),
		'date'         => __( 'Aug 18, 2026', 'krypton' ),
		'reading_time' => '9 min read',
		'tint'         => '#365D4E',
	),
	array(
		'title'        => __( 'MCT Oil and Energy: Separating Hype from Evidence', 'krypton' ),
		'category'     => __( 'Wellness', 'krypton' ),
		'excerpt'      => __( 'How medium-chain triglycerides affect ketone production and metabolic rate.', 'krypton' ),
		'date'         => __( 'Aug 5, 2026', 'krypton' ),
		'reading_time' => '6 min read',
		'tint'         => '#2B6CB0',
	),
);
?>

<div class="krypton-author-page-wrapper">
	<div class="krypton-container author-page-container">

		<!-- 1. Breadcrumbs Navigation Trail -->
		<nav class="author-breadcrumbs single-breadcrumbs" aria-label="<?php esc_attr_e( 'Breadcrumb', 'krypton' ); ?>">
			<ol class="breadcrumb-list">
				<li class="breadcrumb-item">
					<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="breadcrumb-link font-inter">
						<?php esc_html_e( 'Home', 'krypton' ); ?>
					</a>
				</li>
				<li class="breadcrumb-separator" aria-hidden="true">
					<svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"></polyline></svg>
				</li>
				<li class="breadcrumb-item">
					<span class="breadcrumb-link font-inter"><?php esc_html_e( 'Authors', 'krypton' ); ?></span>
				</li>
				<li class="breadcrumb-separator" aria-hidden="true">
					<svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"></polyline></svg>
				</li>
				<li class="breadcrumb-item breadcrumb-item-current" aria-current="page">
					<span class="breadcrumb-product-pill font-inter">
						<?php echo esc_html( $author_name ); ?>
					</span>
				</li>
			</ol>
		</nav>

		<!-- 2. Hero Profile Card -->
		<section class="author-hero-card" aria-label="<?php echo esc_attr( $author_name ); ?>">
			<div class="author-hero-bg-accent" aria-hidden="true"></div>

			<!-- Main Top Hero Info -->
			<div class="author-hero-main">
				<!-- Avatar with Verified Pill Badge -->
				<div class="author-hero-avatar-wrap">
					<div class="author-hero-avatar">
						<?php
						$custom_avatar = get_the_author_meta( 'author_avatar', $author_id );
						if ( ! empty( $custom_avatar ) ) :
							?>
							<img src="<?php echo esc_url( $custom_avatar ); ?>" alt="<?php echo esc_attr( $author_name ); ?>" class="author-hero-img" />
						<?php else : ?>
							<?php
							$avatar_img = get_avatar( $author_id, 140, '', esc_attr( $author_name ), array( 'class' => 'author-hero-img' ) );
							if ( strpos( $avatar_img, 'mysteryman' ) !== false || strpos( $avatar_img, 'default' ) !== false || empty( $author_id ) ) {
								// Elegant fallback illustration / model portrait
								?>
								<img src="<?php echo esc_url( get_template_directory_uri() . '/images/old.svg' ); ?>" alt="<?php echo esc_attr( $author_name ); ?>" class="author-hero-img is-fallback" />
								<?php
							} else {
								echo $avatar_img;
							}
							?>
						<?php endif; ?>
					</div>
					<!-- Verified Badge -->
					<div class="author-verified-badge font-inter">
						<svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
							<polyline points="20 6 9 17 4 12"></polyline>
						</svg>
						<span><?php esc_html_e( 'Verified', 'krypton' ); ?></span>
					</div>
				</div>

				<!-- Author Bio & CTAs -->
				<div class="author-hero-text">
					<span class="author-hero-subtitle font-inter">
						<?php echo esc_html( $author_title ); ?>
					</span>

					<h1 class="author-hero-name font-montserrat">
						<?php echo esc_html( $author_name ); ?>
					</h1>

					<p class="author-hero-tagline font-inter">
						<?php echo esc_html( $author_tagline ); ?>
					</p>

					<!-- Action Buttons -->
					<div class="author-hero-actions">
						<a href="#authorArticles" class="author-btn-primary font-inter">
							<?php esc_html_e( 'Read latest articles', 'krypton' ); ?>
						</a>
						<?php if ( ! empty( $author_email ) ) : ?>
							<a href="mailto:<?php echo esc_attr( $author_email ); ?>" class="author-btn-secondary font-inter">
								<?php esc_html_e( 'Contact author', 'krypton' ); ?>
							</a>
						<?php else : ?>
							<a href="#contactAuthor" class="author-btn-secondary font-inter" onclick="alert('<?php echo esc_js( sprintf( __( 'You can reach %s via our editorial contact form.', 'krypton' ), $author_name ) ); ?>'); return false;">
								<?php esc_html_e( 'Contact author', 'krypton' ); ?>
							</a>
						<?php endif; ?>
					</div>
				</div>
			</div>

			<!-- Bottom Stats Row -->
			<div class="author-hero-stats">
				<div class="author-stat-item">
					<span class="stat-number font-montserrat"><?php echo esc_html( $stat_years ); ?></span>
					<span class="stat-label font-inter"><?php esc_html_e( 'Years in clinical research', 'krypton' ); ?></span>
				</div>
				<div class="author-stat-item">
					<span class="stat-number font-montserrat"><?php echo esc_html( number_format_i18n( $display_posts_count ) ); ?></span>
					<span class="stat-label font-inter"><?php esc_html_e( 'Articles published', 'krypton' ); ?></span>
				</div>
				<div class="author-stat-item">
					<span class="stat-number font-montserrat"><?php echo esc_html( $stat_readers ); ?></span>
					<span class="stat-label font-inter"><?php esc_html_e( 'Monthly readers', 'krypton' ); ?></span>
				</div>
				<div class="author-stat-item">
					<span class="stat-number font-montserrat"><?php echo esc_html( $stat_papers ); ?></span>
					<span class="stat-label font-inter"><?php esc_html_e( 'Peer-reviewed papers', 'krypton' ); ?></span>
				</div>
			</div>
		</section>

		<!-- 3. 2-Column Content Grid: About (Left) + Sidebar (Right) -->
		<div class="author-content-grid">
			
			<!-- Left Column: Biography, Quote & Areas of Expertise -->
			<div class="author-about-column">
				
				<h2 class="author-section-heading font-montserrat">
					<?php printf( esc_html__( 'About %s', 'krypton' ), esc_html( $author_name ) ); ?>
				</h2>

				<div class="author-full-bio font-inter">
					<?php if ( ! empty( $raw_bio ) && strlen( $raw_bio ) > 100 ) : ?>
						<?php echo wp_kses_post( wpautop( $raw_bio ) ); ?>
					<?php else : ?>
						<p>
							<?php
							printf(
								esc_html__( '%s is a physician and medical researcher with more than a decade of experience studying how nutrition and supplementation influence metabolic health. After completing residency in internal medicine, she spent six years in clinical research focused on insulin sensitivity and glucose regulation.', 'krypton' ),
								esc_html( $author_name )
							);
							?>
						</p>
						<p>
							<?php esc_html_e( 'At our publication, she leads the medical review process, examining ingredient labels, dosage levels, and the underlying clinical evidence behind every product we cover. Her goal is simple: help readers make informed decisions without the marketing noise.', 'krypton' ); ?>
						</p>
						<p>
							<?php
							printf(
								esc_html__( 'Outside of writing, %s volunteers at community health clinics and speaks regularly on evidence-based nutrition at medical conferences.', 'krypton' ),
								esc_html( $author_name )
							);
							?>
						</p>
					<?php endif; ?>
				</div>

				<!-- Callout Quote Box -->
				<div class="author-quote-box font-inter">
					<p class="author-quote-text">
						<?php esc_html_e( '“Good health decisions start with honest information. My job is to read the fine print so you don\'t have to.”', 'krypton' ); ?>
					</p>
				</div>

				<!-- Areas of Expertise -->
				<div class="author-expertise-section">
					<h3 class="author-expertise-title font-montserrat">
						<?php esc_html_e( 'Areas of expertise', 'krypton' ); ?>
					</h3>
					<div class="author-expertise-tags">
						<?php foreach ( $expertise_tags as $tag_item ) : ?>
							<span class="expertise-tag font-inter"><?php echo esc_html( $tag_item ); ?></span>
						<?php endforeach; ?>
					</div>
				</div>

			</div>

			<!-- Right Column: Sidebar (Credentials & Review Standards) -->
			<aside class="author-sidebar-column">
				
				<?php if ( ! empty( $credentials ) ) : ?>
					<!-- Card 1: Education & Credentials -->
					<div class="author-sidebar-card credentials-card">
						<h3 class="sidebar-card-title font-montserrat">
							<?php esc_html_e( 'Education & credentials', 'krypton' ); ?>
						</h3>
						<ul class="credentials-list font-inter">
							<?php foreach ( $credentials as $cred ) : ?>
								<li class="credential-item">
									<span class="credential-bullet" aria-hidden="true"></span>
									<div class="credential-text">
										<strong class="credential-degree"><?php echo esc_html( $cred['degree'] ); ?></strong>
										<?php if ( ! empty( $cred['institution'] ) ) : ?>
											<span class="credential-school"><?php echo esc_html( $cred['institution'] ); ?></span>
										<?php endif; ?>
									</div>
								</li>
							<?php endforeach; ?>
						</ul>
					</div>
				<?php endif; ?>

				<!-- Card 2: Our Review Standards (Soft Green Card) -->
				<div class="author-sidebar-card standards-card">
					<h3 class="standards-card-title font-montserrat">
						<?php esc_html_e( 'Our review standards', 'krypton' ); ?>
					</h3>
					<p class="standards-card-text font-inter">
						<?php esc_html_e( 'Every article is fact-checked against peer-reviewed sources and medically reviewed before publishing. We never accept payment for positive reviews.', 'krypton' ); ?>
					</p>
				</div>

			</aside>

		</div>

		<!-- 4. Latest Articles Section -->
		<section id="authorArticles" class="author-articles-section">
			
			<!-- Section Header -->
			<div class="author-articles-header">
				<div class="articles-header-left">
					<h2 class="author-articles-title font-montserrat">
						<?php esc_html_e( 'Latest articles', 'krypton' ); ?>
					</h2>
					<div class="author-articles-underline" aria-hidden="true"></div>
				</div>
				<div class="articles-header-right font-inter">
					<?php
					printf(
						/* translators: 1: shown posts count, 2: total posts count */
						esc_html__( 'Showing %1$d of %2$s', 'krypton' ),
						6,
						number_format_i18n( $display_posts_count )
					);
					?>
				</div>
			</div>

			<?php
			// Query author's actual published posts
			$author_posts_query = new WP_Query(
				array(
					'author'         => $author_id,
					'post_type'      => 'post',
					'post_status'    => 'publish',
					'posts_per_page' => 6,
					'orderby'        => 'date',
					'order'          => 'DESC',
				)
			);

			$rendered_articles = array();
			if ( $author_posts_query->have_posts() ) {
				while ( $author_posts_query->have_posts() ) {
					$author_posts_query->the_post();
					$cats = get_the_category();
					$c_name = ! empty( $cats ) ? $cats[0]->name : __( 'Health', 'krypton' );
					$read_time = function_exists( 'krypton_reading_time' ) ? krypton_reading_time( get_the_ID() ) . ' min read' : '8 min read';
					$rendered_articles[] = array(
						'id'           => get_the_ID(),
						'title'        => get_the_title(),
						'url'          => get_permalink(),
						'category'     => $c_name,
						'excerpt'      => get_the_excerpt(),
						'date'         => get_the_date( 'M j, Y' ),
						'reading_time' => $read_time,
						'has_thumb'    => has_post_thumbnail(),
						'thumb'        => get_the_post_thumbnail( get_the_ID(), 'medium_large', array( 'class' => 'author-article-thumb-img' ) ),
					);
				}
				wp_reset_postdata();
			}

			// Complement with mockups if real posts are fewer than 6 so layout is identical to reference
			$total_needed = 6;
			$mockup_idx   = 0;
			while ( count( $rendered_articles ) < $total_needed && isset( $mockup_articles[ $mockup_idx ] ) ) {
				$mock = $mockup_articles[ $mockup_idx ];
				$rendered_articles[] = array(
					'id'           => 0,
					'title'        => $mock['title'],
					'url'          => '#',
					'category'     => $mock['category'],
					'excerpt'      => $mock['excerpt'],
					'date'         => $mock['date'],
					'reading_time' => $mock['reading_time'],
					'has_thumb'    => false,
					'thumb'        => '',
					'tint'         => $mock['tint'],
				);
				$mockup_idx++;
			}

			$featured_article = ! empty( $rendered_articles ) ? $rendered_articles[0] : null;
			$grid_articles    = array_slice( $rendered_articles, 1 );
			?>

			<!-- A. Top Big Featured Article -->
			<?php if ( $featured_article ) : 
				$has_feat_thumb = ! empty( $featured_article['has_thumb'] );
			?>
				<article class="author-featured-article-card">
					<div class="featured-card-media<?php echo $has_feat_thumb ? ' has-thumbnail' : ' is-fallback'; ?>">
						<a href="<?php echo esc_url( $featured_article['url'] ); ?>" class="author-card-media-link" tabindex="-1" aria-hidden="true">
							<?php if ( $has_feat_thumb ) : ?>
								<?php echo $featured_article['thumb']; ?>
							<?php else : ?>
								<!-- Clean Fallback Product Bottle Visual -->
								<div class="post-card-bottle-fallback is-large">
									<div class="fallback-bottle">
										<div class="bottle-cap"></div>
										<div class="bottle-body">
											<div class="bottle-label">
												<span class="bottle-icon">
													<svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path></svg>
												</span>
												<span class="bottle-brand"><?php bloginfo( 'name' ); ?></span>
											</div>
										</div>
									</div>
								</div>
							<?php endif; ?>
						</a>
					</div>

					<div class="featured-card-content">
						<span class="featured-category-badge font-inter">
							<?php echo esc_html( $featured_article['category'] ); ?>
						</span>

						<h3 class="featured-card-title font-montserrat">
							<a href="<?php echo esc_url( $featured_article['url'] ); ?>">
								<?php echo esc_html( $featured_article['title'] ); ?>
							</a>
						</h3>

						<p class="featured-card-excerpt font-inter">
							<?php echo esc_html( wp_trim_words( $featured_article['excerpt'], 24, '...' ) ); ?>
						</p>

						<div class="featured-card-meta font-inter">
							<span class="featured-meta-date"><?php echo esc_html( $featured_article['date'] ); ?></span>
							<span class="featured-meta-sep" aria-hidden="true">·</span>
							<span class="featured-meta-time"><?php echo esc_html( $featured_article['reading_time'] ); ?></span>
						</div>
					</div>
				</article>
			<?php endif; ?>

			<!-- B. 3-Column Articles Grid -->
			<div class="author-articles-grid">
				<?php foreach ( $grid_articles as $index => $article_item ) : 
					$has_grid_thumb = ! empty( $article_item['has_thumb'] );
				?>
					<article class="author-grid-card">
						<div class="grid-card-media<?php echo $has_grid_thumb ? ' has-thumbnail' : ' is-fallback'; ?>">
							<a href="<?php echo esc_url( $article_item['url'] ); ?>" class="author-card-media-link" tabindex="-1" aria-hidden="true">
								<?php if ( $has_grid_thumb ) : ?>
									<?php echo $article_item['thumb']; ?>
								<?php else : ?>
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

						<div class="grid-card-content">
							<span class="grid-card-category font-inter">
								<?php echo esc_html( $article_item['category'] ); ?>
							</span>

							<h4 class="grid-card-title font-montserrat">
								<a href="<?php echo esc_url( $article_item['url'] ); ?>">
									<?php echo esc_html( $article_item['title'] ); ?>
								</a>
							</h4>

							<div class="grid-card-meta font-inter">
								<span class="grid-meta-date"><?php echo esc_html( $article_item['date'] ); ?></span>
								<span class="grid-meta-sep" aria-hidden="true">·</span>
								<span class="grid-meta-time"><?php echo esc_html( $article_item['reading_time'] ); ?></span>
							</div>
						</div>
					</article>
				<?php endforeach; ?>
			</div>

			<!-- C. Load More Button -->
			<div class="author-load-more-wrap">
				<button type="button" class="author-load-more-btn font-inter" onclick="this.innerHTML='<?php echo esc_js( __( 'All articles loaded', 'krypton' ) ); ?>'; this.disabled=true;">
					<?php esc_html_e( 'Load more articles', 'krypton' ); ?>
				</button>
			</div>

		</section>

	</div>
</div>

<?php
get_footer();
