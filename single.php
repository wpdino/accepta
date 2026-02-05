<?php
/**
 * The template for displaying all single posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package Accepta
 */

get_header();

// Include the content-sidebar-wrap-single template part
get_template_part( 'template-parts/content-sidebar-wrap', 'single' );

get_footer();
