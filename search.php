<?php
/**
 * The template for displaying search results pages
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#search-result
 *
 * @package Accepta
 */

get_header();

// Include the content-sidebar-wrap-search template part
get_template_part( 'template-parts/content-sidebar-wrap', 'search' );

get_footer();
