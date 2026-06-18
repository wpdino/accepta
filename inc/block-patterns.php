<?php
/**
 * Block pattern support for Accepta.
 *
 * Patterns are auto-discovered from /patterns/*.php (WordPress core).
 *
 * @package Accepta
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Register Accepta block pattern categories.
 */
function accepta_register_block_pattern_categories() {
	$categories = array(
		'accepta'       => __( 'Accepta', 'accepta' ),
		'accepta-pages' => __( 'Accepta Pages', 'accepta' ),
	);

	foreach ( $categories as $slug => $label ) {
		if ( ! WP_Block_Pattern_Categories_Registry::get_instance()->is_registered( $slug ) ) {
			register_block_pattern_category(
				$slug,
				array(
					'label' => $label,
				)
			);
		}
	}
}
add_action( 'init', 'accepta_register_block_pattern_categories', 9 );

/**
 * Flush theme pattern cache when pattern files change (dev-friendly).
 */
function accepta_maybe_flush_pattern_cache() {
	if ( ! is_admin() && ! ( defined( 'REST_REQUEST' ) && REST_REQUEST ) ) {
		return;
	}

	$dir = get_template_directory() . '/patterns';
	if ( ! is_dir( $dir ) ) {
		return;
	}

	$files = glob( $dir . '/*.php' );
	if ( ! is_array( $files ) ) {
		return;
	}

	$fingerprint = md5( implode( '|', array_map( 'basename', $files ) ) );
	$stored      = get_option( 'accepta_patterns_fingerprint' );

	if ( $stored === $fingerprint ) {
		return;
	}

	$theme = wp_get_theme();
	if ( method_exists( $theme, 'delete_pattern_cache' ) ) {
		$theme->delete_pattern_cache();
	}

	update_option( 'accepta_patterns_fingerprint', $fingerprint, false );
}
add_action( 'init', 'accepta_maybe_flush_pattern_cache', 1 );

/**
 * Disable core block patterns so Accepta patterns stay focused.
 */
function accepta_disable_core_block_patterns() {
	remove_theme_support( 'core-block-patterns' );
}
add_action( 'after_setup_theme', 'accepta_disable_core_block_patterns', 11 );
