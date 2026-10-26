<?php
/**
 * Search form template for Krypton theme.
 *
 * @package Krypton
 */
?>
<form role="search" method="get" class="krypton-search-form" action="<?php echo esc_url( home_url( '/' ) ); ?>">
	<div class="search-input-wrapper">
		<svg class="search-field-icon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false">
			<circle cx="11" cy="11" r="8"></circle>
			<line x1="21" y1="21" x2="16.65" y2="16.65"></line>
		</svg>
		<label class="screen-reader-text" for="krypton-search-input"><?php esc_html_e( 'Search for article', 'krypton' ); ?></label>
		<input type="search" id="krypton-search-input" class="search-field-input" placeholder="<?php esc_attr_e( 'Search for article', 'krypton' ); ?>" value="<?php echo get_search_query(); ?>" name="s" autocomplete="off" />
	</div>
	<button type="submit" class="search-submit-btn"><?php esc_html_e( 'Search', 'krypton' ); ?></button>
</form>
