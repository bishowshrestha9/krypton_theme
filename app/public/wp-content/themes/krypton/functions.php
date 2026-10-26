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
 * Enqueue scripts and styles.
 */
function krypton_scripts() {
	wp_enqueue_style( 'krypton-style', get_stylesheet_uri(), array(), '1.0.0' );
}
add_action( 'wp_enqueue_scripts', 'krypton_scripts' );
