<?php
/**
 * The template for displaying all pages
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site may use a
 * different template.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Accepta
 */

get_header();

if ( is_front_page() ) {
	get_template_part( 'template-parts/hero-section' );
}

// Include the content-sidebar-wrap-page template part
get_template_part( 'template-parts/content-sidebar-wrap', 'page' );

get_footer();
