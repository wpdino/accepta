<?php
/**
 * Accepta Starter Content
 *
 * Displays sample pages, menu, and content in the Customizer preview before the theme is activated.
 *
 * @link https://make.wordpress.org/core/2016/11/30/starter-content-for-themes-in-4-7/
 *
 * @package Accepta
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Return the front page starter content (block markup).
 * Uses theme hero image (accepta-hero-bg.jpg – free to use, from https://pxhere.com/en/photo/306 ).
 * Add assets/images/accepta-hero-bg-portrait.jpg for a portrait crop in the media-text section.
 *
 * @return string Block markup for the front page.
 */
function accepta_get_front_page_starter_content() {
	$hero_img = get_template_directory_uri() . '/assets/images/accepta-hero-bg.jpg';
	$portrait_img = get_template_directory_uri() . '/assets/images/accepta-hero-bg-portrait.jpg';
	$media_img = file_exists( get_template_directory() . '/assets/images/accepta-hero-bg-portrait.jpg' ) ? $portrait_img : $hero_img;

	return '
					<!-- wp:group {"layout":{"type":"constrained"}} -->
					<div class="wp-block-group">

					<!-- wp:heading {"textAlign":"center","fontSize":"large"} -->
					<h2 class="wp-block-heading has-text-align-center has-large-font-size">' . esc_html_x( 'Build Bold. Build Beautiful.', 'Theme starter content', 'accepta' ) . '</h2>
					<!-- /wp:heading -->

					<!-- wp:paragraph {"align":"center"} -->
					<p class="has-text-align-center">' . esc_html_x( 'Accepta is a flexible, modern WordPress theme. Use the hero section above and this content to showcase your business. Customize everything in the Theme Customizer.', 'Theme starter content', 'accepta' ) . '</p>
					<!-- /wp:paragraph -->

					<!-- wp:spacer {"height":"30px"} -->
					<div style="height:30px" aria-hidden="true" class="wp-block-spacer"></div>
					<!-- /wp:spacer -->

					<!-- wp:heading {"level":3,"fontSize":"medium"} -->
					<h3 class="wp-block-heading has-medium-font-size">' . esc_html_x( 'Get started', 'Theme starter content', 'accepta' ) . '</h3>
					<!-- /wp:heading -->

					<!-- wp:paragraph -->
					<p>' . esc_html_x( 'Edit this page or add new pages from the dashboard. Enable the hero section and overlay header in Customize to get the full look.', 'Theme starter content', 'accepta' ) . '</p>
					<!-- /wp:paragraph -->

					<!-- wp:spacer {"height":"50px"} -->
					<div style="height:50px" aria-hidden="true" class="wp-block-spacer"></div>
					<!-- /wp:spacer -->

					<!-- wp:heading {"textAlign":"center","level":2,"fontSize":"large"} -->
					<h2 class="wp-block-heading has-text-align-center has-large-font-size">' . esc_html_x( 'Why Choose Accepta', 'Theme starter content', 'accepta' ) . '</h2>
					<!-- /wp:heading -->

					<!-- wp:spacer {"height":"24px"} -->
					<div style="height:24px" aria-hidden="true" class="wp-block-spacer"></div>
					<!-- /wp:spacer -->

					<!-- wp:columns {"style":{"spacing":{"blockGap":{"left":"2rem"}}}} -->
					<div class="wp-block-columns">
					<!-- wp:column -->
					<div class="wp-block-column">
					<!-- wp:html -->
					<div class="accepta-icon-box-icon"><i class="fas fa-th-large" aria-hidden="true"></i></div>
					<!-- /wp:html -->
					<!-- wp:heading {"level":3,"fontSize":"medium"} -->
					<h3 class="wp-block-heading has-medium-font-size">' . esc_html_x( 'Flexible &amp; Modern', 'Theme starter content', 'accepta' ) . '</h3>
					<!-- /wp:heading -->
					<!-- wp:paragraph -->
					<p>' . esc_html_x( 'Clean layout and responsive design that works on any device. Built with the block editor so you can rearrange and style content easily.', 'Theme starter content', 'accepta' ) . '</p>
					<!-- /wp:paragraph -->
					</div>
					<!-- /wp:column -->

					<!-- wp:column -->
					<div class="wp-block-column">
					<!-- wp:html -->
					<div class="accepta-icon-box-icon"><i class="fas fa-cubes" aria-hidden="true"></i></div>
					<!-- /wp:html -->
					<!-- wp:heading {"level":3,"fontSize":"medium"} -->
					<h3 class="wp-block-heading has-medium-font-size">' . esc_html_x( 'Elementor Ready', 'Theme starter content', 'accepta' ) . '</h3>
					<!-- /wp:heading -->
					<!-- wp:paragraph -->
					<p>' . esc_html_x( 'Use Elementor for drag-and-drop page building. The theme integrates with popular page builders so you can create custom layouts without code.', 'Theme starter content', 'accepta' ) . '</p>
					<!-- /wp:paragraph -->
					</div>
					<!-- /wp:column -->

					<!-- wp:column -->
					<div class="wp-block-column">
					<!-- wp:html -->
					<div class="accepta-icon-box-icon"><i class="fas fa-sliders-h" aria-hidden="true"></i></div>
					<!-- /wp:html -->
					<!-- wp:heading {"level":3,"fontSize":"medium"} -->
					<h3 class="wp-block-heading has-medium-font-size">' . esc_html_x( 'Fully Customizable', 'Theme starter content', 'accepta' ) . '</h3>
					<!-- /wp:heading -->
					<!-- wp:paragraph -->
					<p>' . esc_html_x( 'Control hero, header, colors, and typography from the Theme Customizer. No coding required to match your brand.', 'Theme starter content', 'accepta' ) . '</p>
					<!-- /wp:paragraph -->
					</div>
					<!-- /wp:column -->
					</div>
					<!-- /wp:columns -->

					<!-- wp:spacer {"height":"50px"} -->
					<div style="height:50px" aria-hidden="true" class="wp-block-spacer"></div>
					<!-- /wp:spacer -->

					<!-- wp:media-text {"mediaPosition":"right","mediaLink":"' . esc_url( $media_img ) . '","mediaType":"image","mediaWidth":45} -->
					<div class="wp-block-media-text has-media-on-the-right is-stacked-on-mobile" style="grid-template-columns:auto 45%">
					<div class="wp-block-media-text__content">
					<!-- wp:heading {"level":2,"fontSize":"large"} -->
					<h2 class="wp-block-heading has-large-font-size">' . esc_html_x( 'Designed for Your Success', 'Theme starter content', 'accepta' ) . '</h2>
					<!-- /wp:heading -->
					<!-- wp:paragraph -->
					<p>' . esc_html_x( 'Accepta gives you a professional starting point for your website. Customize the hero image, add your own content, and launch with confidence. The theme is built for clarity and performance.', 'Theme starter content', 'accepta' ) . '</p>
					<!-- /wp:paragraph -->
					</div>
					<figure class="wp-block-media-text__media"><img src="' . esc_url( $media_img ) . '" alt="' . esc_attr_x( 'Accepta theme', 'Theme starter content image alt', 'accepta' ) . '"/></figure>
					</div>
					<!-- /wp:media-text -->

					<!-- wp:spacer {"height":"50px"} -->
					<div style="height:50px" aria-hidden="true" class="wp-block-spacer"></div>
					<!-- /wp:spacer -->

					<!-- wp:group {"style":{"spacing":{"padding":{"top":"2rem","bottom":"2rem","left":"2rem","right":"2rem"}}},"layout":{"type":"constrained"}} -->
					<div class="wp-block-group" style="padding-top:2rem;padding-right:2rem;padding-bottom:2rem;padding-left:2rem">
					<!-- wp:quote {"className":"is-style-default"} -->
					<blockquote class="wp-block-quote is-style-default">
					<!-- wp:paragraph -->
					<p>' . esc_html_x( 'Accepta made it easy to launch our site. Clean design, easy to customize, and it looks great on every device.', 'Theme starter content', 'accepta' ) . '</p>
					<!-- /wp:paragraph -->
					<cite>' . esc_html_x( '— Happy Customer', 'Theme starter content testimonial citation', 'accepta' ) . '</cite>
					</blockquote>
					<!-- /wp:quote -->
					</div>
					<!-- /wp:group -->

					<!-- wp:spacer {"height":"40px"} -->
					<div style="height:40px" aria-hidden="true" class="wp-block-spacer"></div>
					<!-- /wp:spacer -->
					</div>
					<!-- /wp:group -->
				';
}

/**
 * Return the array of starter content for the theme.
 *
 * Passes it through the `accepta_starter_content` filter before returning.
 *
 * @return array Filtered array of args for the starter content.
 */
function accepta_get_starter_content() {

	$starter_content = array(

		'widgets'     => array(
			'sidebar-1' => array(
				'search',
				'recent-posts',
				'archives',
			),
			'footer-1'  => array(
				'text_business_info',
			),
			'footer-2'  => array(
				'recent-posts',
			),
			'footer-3'  => array(
				'search',
			),
		),

		'posts'       => array(
			'front'  => array(
				'post_type'    => 'page',
				'post_title'   => esc_html_x( 'Homepage', 'Theme starter content', 'accepta' ),
				'post_content' => accepta_get_front_page_starter_content(),
			),
			'blog'   => array(
				'post_type'  => 'page',
				'post_title' => esc_html_x( 'Blog', 'Theme starter content', 'accepta' ),
			),
			'about'  => array(
				'post_type'    => 'page',
				'post_title'   => esc_html_x( 'About', 'Theme starter content', 'accepta' ),
				'post_content' => '
					<!-- wp:heading -->
					<h2 class="wp-block-heading">' . esc_html_x( 'About Us', 'Theme starter content', 'accepta' ) . '</h2>
					<!-- /wp:heading -->

					<!-- wp:paragraph -->
					<p>' . esc_html_x( 'This is a sample About page. Replace this text with your own story.', 'Theme starter content', 'accepta' ) . '</p>
					<!-- /wp:paragraph -->
				',
			),
			'contact' => array(
				'post_type'    => 'page',
				'post_title'   => esc_html_x( 'Contact', 'Theme starter content', 'accepta' ),
				'post_content' => '
					<!-- wp:heading -->
					<h2 class="wp-block-heading">' . esc_html_x( 'Contact', 'Theme starter content', 'accepta' ) . '</h2>
					<!-- /wp:heading -->

					[contact-form-7 id="f9f4471" title="Contact form 1"]
				',
			),
		),

		'options'     => array(
			'show_on_front'  => 'page',
			'page_on_front'  => '{{front}}',
			'page_for_posts' => '{{blog}}',
		),

		'theme_mods'  => array(
			'accepta_sidebar_layout' => 'none',
		),

		'nav_menus'   => array(
			'menu-1' => array(
				'name'  => esc_html__( 'Primary', 'accepta' ),
				'items' => array(
					'link_home',
					'page_about',
					'page_blog',
					'page_contact',
				),
			),
		),
	);

	return apply_filters( 'accepta_starter_content', $starter_content );
}
