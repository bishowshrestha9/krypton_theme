<?php
/**
 * Krypton theme functions and definitions.
 *
 * @package Krypton
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! function_exists( 'krypton_setup' ) ) :
	/**
	 * Sets up theme defaults and registers support for various WordPress features.
	 */
	function krypton_setup() {
		// Make theme available for translation.
		load_theme_textdomain( 'krypton', get_template_directory() . '/languages' );

		// Let WordPress manage the document title.
		add_theme_support( 'title-tag' );

		// Enable support for Post Thumbnails.
		add_theme_support( 'post-thumbnails' );

		// Register Primary Navigation Menu.
		register_nav_menus(
			array(
				'primary' => esc_html__( 'Primary Menu', 'krypton' ),
			)
		);

		// Switch default core markup to output valid HTML5.
		add_theme_support(
			'html5',
			array(
				'search-form',
				'comment-form',
				'comment-list',
				'gallery',
				'caption',
				'style',
				'script',
			)
		);

		// Custom logo support.
		add_theme_support(
			'custom-logo',
			array(
				'height'      => 80,
				'width'       => 260,
				'flex-height' => true,
				'flex-width'  => true,
			)
		);
	}
endif;
add_action( 'after_setup_theme', 'krypton_setup' );

/**
 * Fallback menu that renders a clean horizontal unordered list when no menu is assigned in WP Admin.
 */
function krypton_menu_fallback() {
	echo '<div class="primary-menu-container"><ul id="primary-menu" class="primary-menu-nav">';
	wp_list_pages(
		array(
			'title_li' => '',
			'depth'    => 2,
		)
	);
	echo '</ul></div>';
}

/**
 * Estimated reading time helper.
 *
 * @param int|null $post_id Post ID.
 * @return int Minutes to read.
 */
function krypton_reading_time( $post_id = null ) {
	$content = get_post_field( 'post_content', $post_id );
	$word_count = str_word_count( wp_strip_all_tags( $content ) );
	$reading_time = ceil( $word_count / 200 );
	return ( $reading_time > 0 ) ? (int) $reading_time : 5;
}

/**
 * Extract H2 headings from post content.
 *
 * @param int|null $post_id Post ID.
 * @return array Array of heading items.
 */
function krypton_get_post_h2_headings( $post_id = null ) {
	if ( ! $post_id ) {
		$post_id = get_the_ID();
	}
	$content = get_post_field( 'post_content', $post_id );
	$headings = array();

	if ( preg_match_all( '/<h2(.*?)>(.*?)<\/h2>/i', $content, $matches, PREG_SET_ORDER ) ) {
		$index = 0;
		foreach ( $matches as $match ) {
			$index++;
			$attrs = $match[1];
			$title = wp_strip_all_tags( $match[2] );
			if ( preg_match( '/id=["\'](.*?)["\']/i', $attrs, $id_m ) ) {
				$id = $id_m[1];
			} else {
				$id = sanitize_title( $title );
				if ( empty( $id ) ) {
					$id = 'section-' . $index;
				}
			}
			$headings[] = array(
				'index' => $index,
				'id'    => $id,
				'title' => $title,
			);
		}
	}

	return $headings;
}

/**
 * Automatically inject unique IDs into <h2> headings inside post content for Table of Contents navigation.
 *
 * @param string $content Post content.
 * @return string Modified content with heading IDs.
 */
function krypton_add_ids_to_h2( $content ) {
	if ( is_singular( 'post' ) ) {
		$index = 0;
		$content = preg_replace_callback(
			'/<h2(.*?)>(.*?)<\/h2>/i',
			function( $matches ) use ( &$index ) {
				$index++;
				$attrs = $matches[1];
				$title = wp_strip_all_tags( $matches[2] );
				if ( ! preg_match( '/id=["\'](.*?)["\']/i', $attrs ) ) {
					$id = sanitize_title( $title );
					if ( empty( $id ) ) {
						$id = 'section-' . $index;
					}
					$attrs .= ' id="' . esc_attr( $id ) . '"';
				}
				return '<h2' . $attrs . '>' . $matches[2] . '</h2>';
			},
			$content
		);
	}
	return $content;
}
add_filter( 'the_content', 'krypton_add_ids_to_h2', 20 );

/**
 * Register Krypton Hero Customizer settings.
 *
 * @param WP_Customize_Manager $wp_customize Customizer manager.
 */
function krypton_customize_register( $wp_customize ) {
	// Hero Section Panel/Section
	$wp_customize->add_section(
		'krypton_hero_section',
		array(
			'title'       => esc_html__( 'Hero Section Settings', 'krypton' ),
			'priority'    => 30,
			'description' => esc_html__( 'Customize the front page hero section headline, subheadline, and background.', 'krypton' ),
		)
	);

	// Headline Setting
	$wp_customize->add_setting(
		'krypton_hero_headline',
		array(
			'default'           => 'Evidence-Based Supplement Guides & Real-World Diaries',
			'sanitize_callback' => 'sanitize_text_field',
			'transport'         => 'refresh',
		)
	);

	$wp_customize->add_control(
		'krypton_hero_headline',
		array(
			'label'    => esc_html__( 'Hero Headline', 'krypton' ),
			'section'  => 'krypton_hero_section',
			'type'     => 'text',
		)
	);

	// Sub-headline Setting
	$wp_customize->add_setting(
		'krypton_hero_subheadline',
		array(
			'default'           => 'Explore science-backed supplement reviews, ingredient deconstructions, and clinical insights reviewed by medical professionals.',
			'sanitize_callback' => 'sanitize_textarea_field',
			'transport'         => 'refresh',
		)
	);

	$wp_customize->add_control(
		'krypton_hero_subheadline',
		array(
			'label'    => esc_html__( 'Hero Sub-headline', 'krypton' ),
			'section'  => 'krypton_hero_section',
			'type'     => 'textarea',
		)
	);

	// Right Column Background Image Setting
	$wp_customize->add_setting(
		'krypton_hero_bg_image',
		array(
			'default'           => '',
			'sanitize_callback' => 'esc_url_raw',
		)
	);

	$wp_customize->add_control(
		new WP_Customize_Image_Control(
			$wp_customize,
			'krypton_hero_bg_image',
			array(
				'label'    => esc_html__( 'Right Column Background Image', 'krypton' ),
				'section'  => 'krypton_hero_section',
				'settings' => 'krypton_hero_bg_image',
			)
		)
	);
}
add_action( 'customize_register', 'krypton_customize_register' );

/**
 * Add preconnect resource hints for Google Fonts.
 *
 * @param array  $urls          URLs to print for resource hints.
 * @param string $relation_type The relation type the URLs are printed for.
 * @return array Difference between the two arrays.
 */
function krypton_resource_hints( $urls, $relation_type ) {
	if ( wp_dependencies_unique_hosts() ) {
		if ( 'preconnect' === $relation_type ) {
			$urls[] = array(
				'href' => 'https://fonts.googleapis.com',
			);
			$urls[] = array(
				'href'        => 'https://fonts.gstatic.com',
				'crossorigin' => 'anonymous',
			);
		}
	}
	return $urls;
}
add_filter( 'wp_resource_hints', 'krypton_resource_hints', 10, 2 );

/**
 * Enqueue scripts and styles including Google Fonts.
 */
function krypton_scripts() {
	// Enqueue Google Fonts: Montserrat (600, 700, 800) and Inter (400, 500, 600, 700).
	wp_enqueue_style(
		'krypton-google-fonts',
		'https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Montserrat:wght@600;700;800;900&display=swap',
		array(),
		null
	);

	// Enqueue Main Theme Stylesheet.
	wp_enqueue_style( 'krypton-style', get_stylesheet_uri(), array( 'krypton-google-fonts' ), time() );
}
add_action( 'wp_enqueue_scripts', 'krypton_scripts' );

/**
 * Render dynamic icon or image for a category card.
 *
 * @param WP_Term $category Category term object.
 */
function krypton_render_category_icon( $category ) {
	$slug = strtolower( $category->slug . ' ' . $category->name );

	// Check if a category thumbnail ID is stored in term meta
	$thumbnail_id = get_term_meta( $category->term_id, 'thumbnail_id', true );
	if ( $thumbnail_id ) {
		echo wp_get_attachment_image( $thumbnail_id, 'thumbnail', false, array( 'class' => 'category-circle-img' ) );
		return;
	}

	// Render dynamic matching SVG icon based on category keywords
	if ( strpos( $slug, 'weight' ) !== false || strpos( $slug, 'fat' ) !== false || strpos( $slug, 'metabol' ) !== false ) {
		// Metabolism / Flame icon
		echo '<svg class="category-svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M8.5 14.5A2.5 2.5 0 0 0 11 12c0-1.38-.5-2-1-3-1.072-2.143-.224-4.054 2-6 .5 2.5 2 4.9 4 6.5 2 1.6 3 3.5 3 5.5a7 7 0 1 1-14 0c0-1.153.433-2.294 1-3a2.5 2.5 0 0 0 2.5 2.5z"></path></svg>';
	} elseif ( strpos( $slug, 'brain' ) !== false || strpos( $slug, 'mind' ) !== false || strpos( $slug, 'nootrop' ) !== false || strpos( $slug, 'cognit' ) !== false ) {
		// Brain icon
		echo '<svg class="category-svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M9.5 2A2.5 2.5 0 0 1 12 4.5v15a2.5 2.5 0 0 1-4.96.44 2.5 2.5 0 0 1-2.96-3.08 3 3 0 0 1-.34-5.58 2.5 2.5 0 0 1 1.32-4.24 2.5 2.5 0 0 1 4.44-5.04z"></path><path d="M14.5 2A2.5 2.5 0 0 0 12 4.5v15a2.5 2.5 0 0 0 4.96.44 2.5 2.5 0 0 0 2.96-3.08 3 3 0 0 0 .34-5.58 2.5 2.5 0 0 0-1.32-4.24 2.5 2.5 0 0 0-4.44-5.04z"></path></svg>';
	} elseif ( strpos( $slug, 'joint' ) !== false || strpos( $slug, 'bone' ) !== false || strpos( $slug, 'flex' ) !== false ) {
		// Joint / Bone icon
		echo '<svg class="category-svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="12" cy="12" r="3"></circle><path d="M12 2a4 4 0 0 0-4 4c0 1.5.8 2.8 2 3.5V11a2 2 0 0 0-2 2v2a4 4 0 0 0 8 0v-2a2 2 0 0 0-2-2V9.5c1.2-.7 2-2 2-3.5a4 4 0 0 0-4-4z"></path></svg>';
	} elseif ( strpos( $slug, 'skin' ) !== false || strpos( $slug, 'beauty' ) !== false ) {
		// Skin / Beauty icon
		echo '<svg class="category-svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="m12 3-1.912 5.813a2 2 0 0 1-1.275 1.275L3 12l5.813 1.912a2 2 0 0 1 1.275 1.275L12 21l1.912-5.813a2 2 0 0 1 1.275-1.275L21 12l-5.813-1.912a2 2 0 0 1-1.275-1.275L12 3Z"></path></svg>';
	} elseif ( strpos( $slug, 'dental' ) !== false || strpos( $slug, 'oral' ) !== false || strpos( $slug, 'tooth' ) !== false ) {
		// Dental / Tooth icon
		echo '<svg class="category-svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M12 2C8 2 6 5 6 9c0 4 1 9 3 13 1 1 2 0 3-3 1 3 2 4 3 3 2-4 3-9 3-13 0-4-2-7-6-7z"></path></svg>';
	} elseif ( strpos( $slug, 'male' ) !== false || strpos( $slug, 'vitality' ) !== false || strpos( $slug, 'men' ) !== false ) {
		// Male vitality icon
		echo '<svg class="category-svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path><polygon points="13 8 9 13 12 13 11 17 15 12 12 12 13 8"></polygon></svg>';
	} else {
		// General Health / Heart Vitality icon
		echo '<svg class="category-svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M19 14c1.49-1.46 3-3.21 3-5.5A5.5 5.5 0 0 0 16.5 3c-1.76 0-3 .5-4.5 2-1.5-1.5-2.74-2-4.5-2A5.5 5.5 0 0 0 2 8.5c0 2.3 1.5 4.05 3 5.5l7 7Z"></path></svg>';
	}
}




