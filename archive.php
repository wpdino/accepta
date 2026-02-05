<?php
/**
 * The template for displaying archive pages
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Accepta
 */

get_header();

// Include the content-sidebar-wrap-archive template part
get_template_part( 'template-parts/content-sidebar-wrap', 'archive' );

get_footer();
