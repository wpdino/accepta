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
			'accepta-footer-about' => array(
				'text',
				array(
					'title'  => esc_html_x( 'About Accepta', 'Theme starter content', 'accepta' ),
					'text'   => esc_html_x( 'Accepta is a modern, responsive WordPress theme designed for businesses, portfolios, and blogs.', 'Theme starter content', 'accepta' ),
					'filter' => false,
				),
			),
			'accepta-footer-wpdino' => array(
				'text',
				array(
					'title'  => esc_html_x( 'About WPDINO', 'Theme starter content', 'accepta' ),
					'text'   => esc_html_x( 'WPDINO is a WordPress development company. We create beautiful, functional themes that help businesses grow online.', 'Theme starter content', 'accepta' ),
					'filter' => false,
				),
			),
			'sidebar-1' => array(
				'search',
				'recent-posts',
				'archives',
			),
			'footer-1'  => array(
				'accepta-footer-about',
			),
			'footer-2'  => array(
				'accepta-footer-wpdino',
			),
			'footer-3'  => array(
				'categories',
			),
			'footer-4'  => array(
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
					<!-- wp:paragraph -->
				<p>Accepta is a modern WordPress theme created for businesses, agencies, freelancers, and online brands that need a clean, flexible, and professional website. Built with Elementor support and Gutenberg compatibility, Accepta helps users create polished pages without starting from scratch.</p>
				<!-- /wp:paragraph -->

				<!-- wp:heading -->
				<h2 class="wp-block-heading">'. esc_html_x( 'Our Mission', 'Theme starter content', 'accepta' ) .'</h2>
				<!-- /wp:heading -->

				<!-- wp:paragraph -->
				<p>'. esc_html_x( 'Our goal is simple: make website building faster, easier, and more enjoyable. Accepta gives you a solid design foundation, flexible layout options, and carefully crafted starter pages that can be adapted to many types of websites.', 'Theme starter content', 'accepta' ) .'</p>
				<!-- /wp:paragraph -->

				<!-- wp:heading -->
				<h2 class="wp-block-heading">'. esc_html_x( 'Why Choose Accepta?', 'Theme starter content', 'accepta' ) .'</h2>
				<!-- /wp:heading -->

				<!-- wp:paragraph -->
				<p>'. esc_html_x( 'Accepta combines clean design, responsive layouts, fast performance, and practical customization options. Whether you are building a company website, service landing page, portfolio, or small online shop, Accepta gives you the structure you need to launch with confidence.', 'Theme starter content', 'accepta' ) .'</p>
				<!-- /wp:paragraph -->

				<!-- wp:spacer {"height":"10px"} -->
				<div style="height:10px" aria-hidden="true" class="wp-block-spacer"></div>
				<!-- /wp:spacer -->

				<!-- wp:columns -->
				<div class="wp-block-columns"><!-- wp:column -->
				<div class="wp-block-column"><!-- wp:heading {"level":3,"textColor":"primary"} -->
				<h3 class="wp-block-heading has-primary-color has-text-color">'. esc_html_x( 'Flexible Design', 'Theme starter content', 'accepta' ) .'</h3>
				<!-- /wp:heading -->

				<!-- wp:paragraph -->
				<p>'. esc_html_x( 'Customize your website layout, colors, typography, header, footer, and page sections.', 'Theme starter content', 'accepta' ) .'</p>
				<!-- /wp:paragraph --></div>
				<!-- /wp:column -->

				<!-- wp:column -->
				<div class="wp-block-column"><!-- wp:heading {"level":3,"textColor":"primary"} -->
				<h3 class="wp-block-heading has-primary-color has-text-color">'. esc_html_x( 'Elementor Ready', 'Theme starter content', 'accepta' ) .'</h3>
				<!-- /wp:heading -->

				<!-- wp:paragraph -->
				<p>'. esc_html_x( 'Build pages visually using Elementor and reusable content sections.', 'Theme starter content', 'accepta' ) .'</p>
				<!-- /wp:paragraph --></div>
				<!-- /wp:column -->

				<!-- wp:column -->
				<div class="wp-block-column"><!-- wp:heading {"level":3,"textColor":"primary"} -->
				<h3 class="wp-block-heading has-primary-color has-text-color">'. esc_html_x( 'Performance Focused', 'Theme starter content', 'accepta' ) .'</h3>
				<!-- /wp:heading -->

				<!-- wp:paragraph -->
				<p>'. esc_html_x( 'Lightweight structure and clean code help your site load faster.', 'Theme starter content', 'accepta' ) .'</p>
				<!-- /wp:paragraph --></div>
				<!-- /wp:column -->

				<!-- wp:column -->
				<div class="wp-block-column"><!-- wp:heading {"level":3,"textColor":"primary"} -->
				<h3 class="wp-block-heading has-primary-color has-text-color">'. esc_html_x( 'Business Friendly', 'Theme starter content', 'accepta' ) .'</h3>
				<!-- /wp:heading -->

				<!-- wp:paragraph -->
				<p>'. esc_html_x( 'Perfect for agencies, startups, consultants, creatives, and service-based companies.', 'Theme starter content', 'accepta' ) .'</p>
				<!-- /wp:paragraph --></div>
				<!-- /wp:column --></div>
				<!-- /wp:columns -->

				<!-- wp:paragraph -->
				<p>'. esc_html_x( '', 'Theme starter content', 'accepta' ) .'</p>
				<!-- /wp:paragraph -->
				',
			),
			'contact' => array(
				'post_type'    => 'page',
				'post_title'   => esc_html_x( 'Contact', 'Theme starter content', 'accepta' ),
				'post_content' => '
					<!-- wp:paragraph -->
					<p>'. esc_html_x( 'Have a question, need help, or want to discuss your next project? Send us a message and our team will get back to you as soon as possible.', 'Theme starter content', 'accepta' ) .'</p>
					<!-- /wp:paragraph -->

					<!-- wp:spacer {"height":"20px"} -->
					<div style="height:20px" aria-hidden="true" class="wp-block-spacer"></div>
					<!-- /wp:spacer -->

					<!-- wp:columns -->
					<div class="wp-block-columns"><!-- wp:column -->
					<div class="wp-block-column"><!-- wp:heading -->
					<h2 class="wp-block-heading">'. esc_html_x( 'Contact Info', 'Theme starter content', 'accepta' ) .'</h2>
					<!-- /wp:heading -->

					<!-- wp:paragraph -->
					<p><strong>Email:</strong> <a>'. esc_html_x( 'hello@example.com', 'Theme starter content', 'accepta' ) .'</a><br><strong>Phone:</strong> '. esc_html_x( '+1 234 567 890', 'Theme starter content', 'accepta' ) .'<br><strong>Location:</strong> '. esc_html_x( '123 Business Street, Creative City', 'Theme starter content', 'accepta' ) .'</p>
					<!-- /wp:paragraph --></div>
					<!-- /wp:column -->

					<!-- wp:column -->
					<div class="wp-block-column"><!-- wp:heading -->
					<h2 class="wp-block-heading">'. esc_html_x( 'Need support?', 'Theme starter content', 'accepta' ) .'</h2>
					<!-- /wp:heading -->

					<!-- wp:paragraph -->
					<p>'. esc_html_x( 'If you are already using Accepta, please include your website URL and a short description of the issue so we can help you faster.', 'Theme starter content', 'accepta' ) .'</p>
					<!-- /wp:paragraph --></div>
					<!-- /wp:column --></div>
					<!-- /wp:columns -->

					<!-- wp:shortcode -->
					[contact-form-7 id="f9f4471" title="Contact form 1"]
					<!-- /wp:shortcode -->

					<!-- wp:paragraph -->
					<p>'. esc_html_x( '', 'Theme starter content', 'accepta' ) .'</p>
					<!-- /wp:paragraph -->
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
