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



