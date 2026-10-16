<?php
/**
 * Back to top button.
 *
 * Disabled by default so existing installs are unchanged.
 *
 * @package Accepta
 */

/**
 * Whether the back to top control is enabled.
 *
 * @return bool
 */
function accepta_back_to_top_enabled() {
	return (bool) get_theme_mod( 'accepta_back_to_top', false );
}

/**
 * Enqueue back to top script when enabled.
 *
 * @return void
 */
function accepta_back_to_top_scripts() {
	if ( ! accepta_back_to_top_enabled() ) {
		return;
	}

	wp_enqueue_script(
		'accepta-back-to-top',
		accepta_get_asset_uri( 'assets/js/back-to-top.js' ),
		array(),
		_ACCEPTA_VERSION,
		true
	);
}
add_action( 'wp_enqueue_scripts', 'accepta_back_to_top_scripts', 30 );

/**
 * Print the back to top button markup.
 *
 * @return void
 */
function accepta_back_to_top_markup() {
	if ( ! accepta_back_to_top_enabled() ) {
		return;
	}
	?>
	<button
		type="button"
		class="accepta-back-to-top"
		data-accepta-back-to-top
		hidden
		aria-label="<?php esc_attr_e( 'Back to top', 'accepta' ); ?>"
	>
		<span aria-hidden="true">↑</span>
	</button>
	<?php
}
add_action( 'wp_footer', 'accepta_back_to_top_markup', 40 );
