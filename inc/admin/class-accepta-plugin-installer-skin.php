<?php
/**
 * Accepta Plugin Installer Skin
 *
 * Silent skin for AJAX plugin installation (no HTML output).
 *
 * @package Accepta
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'Plugin_Upgrader', false ) ) {
	require_once ABSPATH . 'wp-admin/includes/class-wp-upgrader.php';
}

/**
 * WordPress upgrader skin extended for silent plugin installation via AJAX.
 */
class Accepta_Plugin_Installer_Skin extends WP_Upgrader_Skin {

	/**
	 * Empty header.
	 */
	public function header() {}

	/**
	 * Empty footer.
	 */
	public function footer() {}

	/**
	 * Suppress feedback output.
	 *
	 * @param string $string
	 * @param mixed  ...$args Optional text replacements.
	 */
	public function feedback( $string, ...$args ) {}

	/**
	 * Suppress decrement_update_count output.
	 *
	 * @param string $type Type of update count to decrement.
	 */
	public function decrement_update_count( $type ) {}

	/**
	 * Send JSON error on failure.
	 *
	 * @param string|WP_Error $errors Errors.
	 */
	public function error( $errors ) {
		if ( empty( $errors ) ) {
			return;
		}
		$message = __( 'Installation failed.', 'accepta' );
		if ( is_wp_error( $errors ) && $errors->has_errors() ) {
			$message = $errors->get_error_message();
		} elseif ( is_string( $errors ) ) {
			$message = $errors;
		}
		wp_send_json_error( $message );
	}
}
