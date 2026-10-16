<?php
/**
 * Register optional block style variations.
 *
 * These only apply when a user selects the style in the editor.
 * Existing content is unchanged.
 *
 * @package Accepta
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Register Accepta block styles.
 *
 * @return void
 */
function accepta_register_block_styles() {
	if ( ! function_exists( 'register_block_style' ) ) {
		return;
	}

	register_block_style(
		'core/button',
		array(
			'name'  => 'accepta-outline',
			'label' => __( 'Outline', 'accepta' ),
		)
	);

	register_block_style(
		'core/button',
		array(
			'name'  => 'accepta-soft',
			'label' => __( 'Soft', 'accepta' ),
		)
	);

	register_block_style(
		'core/quote',
		array(
			'name'  => 'accepta-accent',
			'label' => __( 'Accent', 'accepta' ),
		)
	);

	register_block_style(
		'core/separator',
		array(
			'name'  => 'accepta-wide',
			'label' => __( 'Wide', 'accepta' ),
		)
	);
}
add_action( 'init', 'accepta_register_block_styles' );
