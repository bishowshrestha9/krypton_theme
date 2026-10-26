<?php
/**
 * The header template for Krypton theme.
 *
 * Displays all of the <head> section and everything up until <div id="content">
 *
 * @package Krypton
 */

?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="profile" href="https://gmpg.org/xfn/11">
	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<div id="page" class="site-wrapper">
	<a class="skip-link screen-reader-text" href="#primary"><?php esc_html_e( 'Skip to content', 'krypton' ); ?></a>

	<!-- Top Announcement Bar -->
	<div class="top-announcement-bar" role="region" aria-label="<?php esc_attr_e( 'Top Announcement', 'krypton' ); ?>">
		<div class="krypton-container top-announcement-inner">
			<span class="announcement-text"><?php esc_html_e( 'Special Christmas Offer - 50%', 'krypton' ); ?></span>
		</div>
	</div>

	<!-- Main Header Container (Sticky / Fixed-top) -->
	<header id="masthead" class="site-header" role="banner">
		<div class="krypton-container site-header-inner">
			<!-- Site Logo / Brand -->
			<div class="site-branding">
				<?php
				if ( has_custom_logo() ) :
					the_custom_logo();
				else :
					?>
					<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="site-logo" rel="home">
						<?php bloginfo( 'name' ); ?>
					</a>
					<?php
					$krypton_description = get_bloginfo( 'description', 'display' );
					if ( $krypton_description || is_customize_preview() ) :
						?>
						<p class="site-description screen-reader-text"><?php echo esc_html( $krypton_description ); ?></p>
						<?php
					endif;
				endif;
				?>
			</div>

			<!-- Primary Navigation Menu -->
			<nav id="site-navigation" class="main-navigation" role="navigation" aria-label="<?php esc_attr_e( 'Primary Menu', 'krypton' ); ?>">
				<?php
				wp_nav_menu(
					array(
						'theme_location' => 'primary',
						'menu_id'        => 'primary-menu',
						'menu_class'     => 'primary-menu-nav',
						'container'      => 'div',
						'container_class'=> 'primary-menu-container',
						'fallback_cb'    => 'wp_page_menu',
						'depth'          => 3,
					)
				);
				?>
			</nav>

			<!-- Utility Actions: Search Trigger -->
			<div class="header-utility-actions">
				<a href="<?php echo esc_url( home_url( '/?s=' ) ); ?>" class="search-trigger-btn" aria-label="<?php esc_attr_e( 'Search website', 'krypton' ); ?>" title="<?php esc_attr_e( 'Search', 'krypton' ); ?>">
					<svg class="search-icon" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false">
						<circle cx="11" cy="11" r="8"></circle>
						<line x1="21" y1="21" x2="16.65" y2="16.65"></line>
					</svg>
				</a>
			</div>
		</div>
	</header>

	<div id="content" class="site-content">
