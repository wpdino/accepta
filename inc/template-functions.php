<?php
/**
 * Functions which enhance the theme by hooking into WordPress
 *
 * @package Accepta
 */

/**
 * Adds custom classes to the array of body classes.
 *
 * @param array $classes Classes for the body element.
 * @return array
 */
function accepta_body_classes( $classes ) {
	// Adds a class of hfeed to non-singular pages.
	if ( ! is_singular() ) {
		$classes[] = 'hfeed';
	}

	// Adds a class of no-sidebar when there is no sidebar present.
	if ( ! is_active_sidebar( 'sidebar-1' ) ) {
		$classes[] = 'no-sidebar';
	}

	// Overlay header: apply when front page has customizer hero enabled.
	if ( is_front_page() && get_theme_mod( 'accepta_hero_enabled', true ) ) {
		$classes[] = 'accepta-has-hero';
	}

	// Full-width page templates (no sidebar).
	if ( is_page_template( 'page-templates/full-width.php' ) || is_page_template( 'page-templates/full-width-no-paddings.php' ) ) {
		$classes[] = 'accepta-page-template-full-width';
	}

	// Content box shadow option (for Customizer override).
	$box_shadow = get_theme_mod( 'accepta_content_box_shadow', 'default' );
	$classes[] = 'accepta-content-box-shadow-' . sanitize_html_class( $box_shadow );

	// Sidebar active (for "only with sidebars" box shadow logic).
	if ( accepta_has_sidebar() ) {
		$classes[] = 'accepta-has-sidebar';
	}

	return $classes;
}
add_filter( 'body_class', 'accepta_body_classes' );

/**
 * Add a pingback url auto-discovery header for single posts, pages, or attachments.
 */
/**
 * Whether the current view is the starter front page (full-width, no sidebar).
 * True when: (1) Customizer preview showing the front page, or (2) front page and user previously saved starter content.
 *
 * @return bool
 */
function accepta_is_starter_front_page() {
	if ( ! is_front_page() ) {
		return false;
	}
	// In Customizer preview, show full width when viewing the front page (starter content preview).
	if ( is_customize_preview() ) {
		return true;
	}
	// On the live site, full width only if user saved starter content.
	return (bool) get_option( 'accepta_front_page_full_width', false );
}

/**
 * Check if sidebar should be displayed based on layout settings.
 *
 * @return bool True if sidebar should be displayed, false otherwise.
 */
function accepta_has_sidebar() {
	// In Customizer preview, always load sidebar HTML for live preview to work
	if ( is_customize_preview() ) {
		// Only respect full-width templates
		if ( is_page_template( 'page-templates/full-width.php' ) || is_page_template( 'page-templates/full-width-no-paddings.php' ) ) {
			return false;
		}
		// Otherwise always return true in Customizer so JavaScript can control visibility
		return true;
	}
	
	// Full-width page templates never show sidebar
	if ( is_page_template( 'page-templates/full-width.php' ) || is_page_template( 'page-templates/full-width-no-paddings.php' ) ) {
		return false;
	}
	
	// Check sidebar layout setting from Customizer
	$sidebar_layout = get_theme_mod( 'accepta_sidebar_layout', 'none' );
	
	// Return true only if sidebar layout is 'left' or 'right'
	return in_array( $sidebar_layout, array( 'left', 'right' ), true );
}

/**
 * Add a pingback url auto-discovery header for single posts, pages, and attachments.
 */
function accepta_pingback_header() {
	if ( is_singular() && pings_open() ) {
		printf( '<link rel="pingback" href="%s">', esc_url( get_bloginfo( 'pingback_url' ) ) );
	}
}
add_action( 'wp_head', 'accepta_pingback_header' );
