<?php
/**
 * Accepta Theme Admin Initialization
 *
 * @package Accepta
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Require the admin class
 * 
 * The main admin class handles all the functionality previously contained
 * in separate procedural functions.
 */
require_once get_template_directory() . '/inc/admin/class-accepta-admin.php';

// The Accepta_Admin class is automatically instantiated via Accepta_Admin::instance()
// at the end of the class-accepta-admin.php file.
