<?php
/**
 * Accepta Theme Customizer
 *
 * @package Accepta
 */

// Include custom control classes only when in customizer context
	if ( class_exists( 'WP_Customize_Control' ) ) {
		require_once get_template_directory() . '/inc/customizer-controls/class-accepta-spacing-control.php';
		require_once get_template_directory() . '/inc/customizer-controls/class-accepta-copyright-control.php';
		require_once get_template_directory() . '/inc/customizer-controls/class-accepta-repeater-control.php';
		require_once get_template_directory() . '/inc/customizer-controls/class-accepta-layout-control.php';
		require_once get_template_directory() . '/inc/customizer-controls/class-accepta-range-control.php';
		require_once get_template_directory() . '/inc/customizer-controls/class-accepta-typography-control.php';
		require_once get_template_directory() . '/inc/customizer-controls/class-accepta-background-control.php';
		require_once get_template_directory() . '/inc/customizer-controls/class-accepta-hero-background-control.php';
		require_once get_template_directory() . '/inc/customizer-controls/class-accepta-tab-control.php';
		require_once get_template_directory() . '/inc/customizer-controls/class-accepta-alignment-control.php';
	}

/**
 * Add postMessage support for site title and description for the Theme Customizer.
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function accepta_customize_register( $wp_customize ) {
	// Register custom control types
	if ( class_exists( 'Accepta_Tab_Control' ) ) {
		$wp_customize->register_control_type( 'Accepta_Tab_Control' );
	}
	if ( class_exists( 'Accepta_Hero_Background_Control' ) ) {
		$wp_customize->register_control_type( 'Accepta_Hero_Background_Control' );
	}
	if ( class_exists( 'Accepta_Range_Control' ) ) {
		$wp_customize->register_control_type( 'Accepta_Range_Control' );
	}
	// Note: Alignment control doesn't need to be registered as a control type
	// It works fine without registration, similar to other controls
	// if ( class_exists( 'Accepta_Alignment_Control' ) ) {
	// 	$wp_customize->register_control_type( 'Accepta_Alignment_Control' );
	// }

	$wp_customize->get_setting( 'blogname' )->transport         = 'postMessage';
	$wp_customize->get_setting( 'blogdescription' )->transport  = 'postMessage';
	$wp_customize->get_setting( 'header_textcolor' )->transport = 'postMessage';

	// Move homepage settings section to be right after site identity
	if ( $wp_customize->get_section( 'static_front_page' ) ) {
		$wp_customize->get_section( 'static_front_page' )->priority = 25;
	}

	// Add Layout Section
	$wp_customize->add_section(
		'accepta_layout_section',
		array(
			'title'       => __( 'Layout', 'accepta' ),
			'description' => __( 'Configure site layout, container width, sidebar position, and content spacing.', 'accepta' ),
			'priority'    => 30,
		)
	);

	// Add Header Section
	$wp_customize->add_section(
		'accepta_header_section',
		array(
			'title'       => __( 'Header', 'accepta' ),
			'description' => __( 'Configure header settings and behavior.', 'accepta' ),
			'priority'    => 31,
		)
	);

	// Header Width Setting
	$wp_customize->add_setting(
		'accepta_header_width',
		array(
			'default'           => 'boxed',
			'sanitize_callback' => function( $input ) {
				$valid_widths = array( 'boxed', 'fullwidth' );
				return in_array( $input, $valid_widths, true ) ? $input : 'boxed';
			},
			'transport'         => 'postMessage',
		)
	);

	$wp_customize->add_control(
		new Accepta_Layout_Control(
			$wp_customize,
			'accepta_header_width',
			array(
				'label'       => __( 'Header Width', 'accepta' ),
				'description' => __( 'Choose whether the header should be boxed (within container) or fullwidth.', 'accepta' ),
				'section'     => 'accepta_header_section',
				'priority'    => 5,
				'layouts'     => array(
					'boxed' => array(
						'label' => __( 'Boxed', 'accepta' ),
					),
					'fullwidth' => array(
						'label' => __( 'Full Width', 'accepta' ),
					),
				),
			)
		)
	);

	// Header Layout Setting
	$wp_customize->add_setting(
		'accepta_header_layout',
		array(
			'default'           => 'layout-3',
			'sanitize_callback' => function( $input ) {
				$valid_layouts = array( 'layout-1', 'layout-2', 'layout-3' );
				return in_array( $input, $valid_layouts, true ) ? $input : 'layout-3';
			},
			'transport'         => 'postMessage',
		)
	);

	$wp_customize->add_control(
		new Accepta_Layout_Control(
			$wp_customize,
			'accepta_header_layout',
			array(
				'label'       => __( 'Header Layout', 'accepta' ),
				'description' => __( 'Select the header layout. On mobile devices, the hamburger menu icon appears and the main menu is displayed in the sidebar.', 'accepta' ),
				'section'     => 'accepta_header_section',
				'priority'    => 6,
				'layouts'     => array(
					'layout-1' => array(
						'label' => __( 'Layout 1', 'accepta' ),
					),
					'layout-2' => array(
						'label' => __( 'Layout 2', 'accepta' ),
					),
					'layout-3' => array(
						'label' => __( 'Layout 3', 'accepta' ),
					),
				),
			)
		)
	);

	// Sticky Header Setting
	$wp_customize->add_setting(
		'accepta_sticky_header',
		array(
			'default'           => true,
			'sanitize_callback' => 'wp_validate_boolean',
			'transport'         => 'refresh',
		)
	);

	$wp_customize->add_control(
		'accepta_sticky_header',
		array(
			'label'       => __( 'Enable Sticky Header', 'accepta' ),
			'description' => __( 'Make the header stick to the top when scrolling down the page.', 'accepta' ),
			'section'     => 'accepta_header_section',
			'type'        => 'checkbox',
			'priority'    => 10,
		)
	);

	// Overlay Header Setting (default on)
	$wp_customize->add_setting(
		'accepta_transparent_header',
		array(
			'default'           => true,
			'sanitize_callback' => 'wp_validate_boolean',
			'transport'         => 'postMessage',
		)
	);

	$wp_customize->add_control(
		'accepta_transparent_header',
		array(
			'label'       => __( 'Overlay', 'accepta' ),
			'description' => __( 'Make the header overlay the hero section. Background will appear on scroll.', 'accepta' ),
			'section'     => 'accepta_header_section',
			'type'        => 'checkbox',
			'priority'    => 15,
		)
	);

	// Overlay Header Text Color Setting
	$wp_customize->add_setting(
		'accepta_transparent_header_text_color',
		array(
			'default'           => '#ffffff',
			'sanitize_callback' => 'sanitize_hex_color',
			'transport'         => 'postMessage',
		)
	);

	$wp_customize->add_control(
		new WP_Customize_Color_Control(
			$wp_customize,
			'accepta_transparent_header_text_color',
			array(
				'label'       => __( 'Overlay Text Color', 'accepta' ),
				'description' => __( 'Text color for header content when overlay is enabled.', 'accepta' ),
				'section'     => 'accepta_header_section',
				'priority'    => 20,
				'active_callback' => function() {
					return get_theme_mod( 'accepta_transparent_header', true );
				},
			)
		)
	);

	// Display Header Social Icons Toggle
	$wp_customize->add_setting(
		'accepta_display_header_social_icons',
		array(
			'default'           => false,
			'sanitize_callback' => 'wp_validate_boolean',
			'transport'         => 'refresh',
		)
	);

	$wp_customize->add_control(
		'accepta_display_header_social_icons',
		array(
			'label'       => __( 'Display Social Icons in Header', 'accepta' ),
			'description' => __( 'Enable or disable the display of social media icons in the header.', 'accepta' ),
			'section'     => 'accepta_header_section',
			'type'        => 'checkbox',
			'priority'    => 40,
		)
	);

	// Header Social Media Repeater Control
	$wp_customize->add_setting(
		'accepta_header_social_media',
		array(
			'default'           => json_encode( array(
				array(
					'label' => 'Facebook',
					'url'   => 'https://facebook.com/yourpage',
					'icon_type' => 'fontawesome',
					'icon'  => 'fab fa-facebook-f',
					'custom_icon' => '',
				),
				array(
					'label' => 'Twitter',
					'url'   => 'https://twitter.com/yourusername',
					'icon_type' => 'fontawesome',
					'icon'  => 'fab fa-twitter',
					'custom_icon' => '',
				),
				array(
					'label' => 'Instagram',
					'url'   => 'https://instagram.com/yourusername',
					'icon_type' => 'fontawesome',
					'icon'  => 'fab fa-instagram',
					'custom_icon' => '',
				),
				array(
					'label' => 'LinkedIn',
					'url'   => 'https://linkedin.com/in/yourprofile',
					'icon_type' => 'fontawesome',
					'icon'  => 'fab fa-linkedin-in',
					'custom_icon' => '',
				),
			) ),
			'sanitize_callback' => 'accepta_sanitize_repeater',
			'transport'         => 'postMessage',
		)
	);

	$wp_customize->add_control(
		new Accepta_Repeater_Control(
			$wp_customize,
			'accepta_header_social_media',
			array(
				'label'       => __( 'Header Social Media Links', 'accepta' ),
				'description' => __( 'Add your social media profiles for the header. You can use Font Awesome icons or upload custom images for icons.', 'accepta' ),
				'section'     => 'accepta_header_section',
				'priority'    => 45,
				'max_items'   => 8,
				'active_callback' => function() {
					return get_theme_mod( 'accepta_display_header_social_icons', true );
				},
				'fields'      => array(
					'label' => array(
						'type'        => 'text',
						'label'       => __( 'Label', 'accepta' ),
						'placeholder' => __( 'e.g., Facebook', 'accepta' ),
					),
					'url' => array(
						'type'        => 'url',
						'label'       => __( 'URL', 'accepta' ),
						'placeholder' => __( 'https://example.com', 'accepta' ),
					),
					'icon_type' => array(
						'type'    => 'select',
						'label'   => __( 'Icon Type', 'accepta' ),
						'options' => array(
							'fontawesome' => __( 'Font Awesome', 'accepta' ),
							'custom'      => __( 'Custom Icon', 'accepta' ),
						),
					),
					'icon' => array(
						'type'        => 'fontawesome',
						'label'       => __( 'Font Awesome Icon', 'accepta' ),
						'placeholder' => __( 'fab fa-facebook-f', 'accepta' ),
					),
					'custom_icon' => array(
						'type'  => 'media',
						'label' => __( 'Custom Icon', 'accepta' ),
					),
				),
			)
		)
	);

	// Display Header Search Button Setting
	$wp_customize->add_setting(
		'accepta_display_header_search',
		array(
			'default'           => true,
			'sanitize_callback' => 'wp_validate_boolean',
			'transport'         => 'refresh',
		)
	);

	$wp_customize->add_control(
		'accepta_display_header_search',
		array(
			'label'       => __( 'Display Search Button in Header', 'accepta' ),
			'description' => __( 'Enable or disable the search button in the header. Clicking it will open a search form overlay.', 'accepta' ),
			'section'     => 'accepta_header_section',
			'type'        => 'checkbox',
			'priority'    => 50,
		)
	);

	// Scrolled Header Background Color Setting
	$wp_customize->add_setting(
		'accepta_scrolled_header_bg',
		array(
			'default'           => '#ffffff',
			'sanitize_callback' => 'sanitize_hex_color',
			'transport'         => 'postMessage',
		)
	);

	$wp_customize->add_control(
		new WP_Customize_Color_Control(
			$wp_customize,
			'accepta_scrolled_header_bg',
			array(
				'label'       => __( 'Scrolled Header Background', 'accepta' ),
				'description' => __( 'Background color for the header when scrolled (only applies if sticky header is enabled).', 'accepta' ),
				'section'     => 'accepta_header_section',
				'priority'    => 25,
				'active_callback' => function() {
					return get_theme_mod( 'accepta_sticky_header', true );
				},
			)
		)
	);

	// Scrolled Header Background Opacity Setting
	$wp_customize->add_setting(
		'accepta_scrolled_header_bg_opacity',
		array(
			'default'           => '1',
			'sanitize_callback' => function( $input ) {
				$float = floatval( $input );
				$float = min( max( 0, $float ), 1 ); // Clamp between 0 and 1
				// Return as string to preserve decimal precision
				return (string) $float;
			},
			'transport'         => 'postMessage',
		)
	);

	// Add opacity control as a custom control that appears nested under the color control
	// We'll use a simple control with custom HTML to match the overlay pattern
	$wp_customize->add_control(
		'accepta_scrolled_header_bg_opacity',
		array(
			'label'       => '',
			'description' => '',
			'section'     => 'accepta_header_section',
			'type'        => 'hidden',
			'priority'    => 25.5,
		)
	);

	// Add custom HTML control for opacity slider (similar to overlay opacity in background control)
	$wp_customize->add_setting(
		'accepta_scrolled_header_bg_opacity_display',
		array(
			'default'           => 1,
			'sanitize_callback' => '__return_false', // Don't save this, it's just for display
			'transport'         => 'postMessage',
		)
	);

	$wp_customize->add_control(
		new WP_Customize_Control(
			$wp_customize,
			'accepta_scrolled_header_bg_opacity_display',
			array(
				'section'     => 'accepta_header_section',
				'type'        => 'hidden',
				'priority'    => 25.5,
			)
		)
	);

	// Scrolled Header Text Color Setting
	$wp_customize->add_setting(
		'accepta_scrolled_header_text_color',
		array(
			'default'           => '#2c3e50',
			'sanitize_callback' => 'sanitize_hex_color',
			'transport'         => 'postMessage',
		)
	);

	$wp_customize->add_control(
		new WP_Customize_Color_Control(
			$wp_customize,
			'accepta_scrolled_header_text_color',
			array(
				'label'       => __( 'Scrolled Header Text Color', 'accepta' ),
				'description' => __( 'Text color for header content when scrolled (only applies if sticky header is enabled).', 'accepta' ),
				'section'     => 'accepta_header_section',
				'priority'    => 30,
				'active_callback' => function() {
					return get_theme_mod( 'accepta_sticky_header', true );
				},
			)
		)
	);

	// Add Homepage Hero Section Panel
	$wp_customize->add_panel(
		'accepta_hero_panel',
		array(
			'title'       => __( 'Homepage Hero Section', 'accepta' ),
			'description' => __( 'Configure the homepage hero section with background, content, and styling options.', 'accepta' ),
			'priority'    => 32,
		)
	);

	// Hero General Settings Section
	$wp_customize->add_section(
		'accepta_hero_general_section',
		array(
			'title'       => __( 'General Settings', 'accepta' ),
			'description' => __( 'Enable and configure basic hero section settings.', 'accepta' ),
			'panel'       => 'accepta_hero_panel',
			'priority'    => 10,
		)
	);

	// Hero Enabled (default on)
	$wp_customize->add_setting(
		'accepta_hero_enabled',
		array(
			'default'           => true,
			'sanitize_callback' => 'wp_validate_boolean',
			'transport'         => 'refresh',
		)
	);

	$wp_customize->add_control(
		'accepta_hero_enabled',
		array(
			'label'       => __( 'Enable Hero Section', 'accepta' ),
			'description' => __( 'Display hero section on the homepage.', 'accepta' ),
			'section'     => 'accepta_hero_general_section',
			'type'        => 'checkbox',
			'priority'    => 10,
		)
	);

	// Hero Height Type (default fullscreen)
	$wp_customize->add_setting(
		'accepta_hero_height',
		array(
			'default'           => 'fullscreen',
			'sanitize_callback' => 'accepta_sanitize_hero_height',
			'transport'         => 'postMessage',
		)
	);

	$wp_customize->add_control(
		'accepta_hero_height',
		array(
			'label'       => __( 'Height Type', 'accepta' ),
			'description' => __( 'Choose how the hero section height is determined.', 'accepta' ),
			'section'     => 'accepta_hero_general_section',
			'type'        => 'select',
			'choices'     => array(
				'min-height'  => __( 'Minimum Height', 'accepta' ),
				'custom'      => __( 'Custom Height', 'accepta' ),
				'fullscreen'  => __( 'Fullscreen', 'accepta' ),
			),
			'priority'    => 20,
		)
	);

	// Hero Minimum Height
	$wp_customize->add_setting(
		'accepta_hero_min_height',
		array(
			'default'           => 500,
			'sanitize_callback' => 'absint',
			'transport'         => 'postMessage',
		)
	);

	$wp_customize->add_control(
		'accepta_hero_min_height',
		array(
			'label'       => __( 'Minimum Height (px)', 'accepta' ),
			'description' => __( 'Set the minimum height for the hero section.', 'accepta' ),
			'section'     => 'accepta_hero_general_section',
			'type'        => 'number',
			'input_attrs' => array(
				'min'  => 200,
				'max'  => 2000,
				'step' => 10,
			),
			'priority'    => 30,
			'active_callback' => function( $control ) {
				$height = $control->manager->get_setting( 'accepta_hero_height' )->value();
				return in_array( $height, array( 'min-height', 'custom' ) );
			},
		)
	);

	// Hero Width Setting (default fullwidth for fullscreen)
	$wp_customize->add_setting(
		'accepta_hero_width',
		array(
			'default'           => 'fullwidth',
			'sanitize_callback' => 'sanitize_text_field',
			'transport'         => 'postMessage',
		)
	);

	$wp_customize->add_control(
		new Accepta_Layout_Control(
			$wp_customize,
			'accepta_hero_width',
			array(
				'label'       => __( 'Width', 'accepta' ),
				'description' => __( 'Choose the width layout for the hero section.', 'accepta' ),
				'section'     => 'accepta_hero_general_section',
				'priority'    => 35,
				'layouts'     => array(
					'boxed' => array(
						'label' => __( 'Boxed', 'accepta' ),
						'image' => 'boxed.svg',
					),
					'fullwidth' => array(
						'label' => __( 'Full Width', 'accepta' ),
						'image' => 'fullwidth.svg',
					),
				),
			)
		)
	);

	// Hero Background Setting (default: image with overlay)
	$default_hero_bg = array(
		'type'              => 'image',
		'color'             => '#6F9C50',
		'gradient_type'     => 'linear',
		'gradient_angle'     => '90',
		'gradient_start'     => '#6F9C50',
		'gradient_end'       => '#568F0C',
		'image'             => get_template_directory_uri() . '/assets/images/accepta-hero-bg.jpg',
		'size'              => 'cover',
		'repeat'            => 'no-repeat',
		'position'          => 'center',
		'attachment'        => 'parallax',
		'overlay_enabled'   => true,
		'overlay_color'     => '#6F9C50',
		'overlay_opacity'    => '0.2',
		'video_type'        => 'youtube',
		'video_url'         => '',
		'video_mp4'         => '',
		'video_autoplay'    => true,
		'video_loop'        => true,
		'video_muted'       => true,
		'video_controls'    => false,
	);
	$wp_customize->add_setting(
		'accepta_hero_background',
		array(
			'default'           => json_encode( $default_hero_bg ),
			'sanitize_callback' => 'accepta_sanitize_background',
			'transport'         => 'postMessage',
		)
	);

	// Hero Background Control (moved to General Settings)
	$wp_customize->add_control(
		new Accepta_Background_Control(
			$wp_customize,
			'accepta_hero_background',
			array(
				'label'       => __( 'Background', 'accepta' ),
				'description' => __( 'Choose a solid color, gradient, image, or video background for the hero section.', 'accepta' ),
				'section'     => 'accepta_hero_general_section',
				'priority'    => 15,
			)
		)
	);

	// Hero Content Section
	$wp_customize->add_section(
		'accepta_hero_content_section',
		array(
			'title'       => __( 'Content', 'accepta' ),
			'description' => __( 'Configure hero section content (heading, text, button).', 'accepta' ),
			'panel'       => 'accepta_hero_panel',
			'priority'    => 40,
		)
	);

	// Hero Heading
	$wp_customize->add_setting(
		'accepta_hero_heading',
		array(
			'default'           => 'Build Bold! Build Beautiful!',
			'sanitize_callback' => 'wp_kses_post',
			'transport'         => 'postMessage',
		)
	);

	$wp_customize->add_control(
		'accepta_hero_heading',
		array(
			'label'       => __( 'Heading', 'accepta' ),
			'description' => __( 'Enter the hero section heading text.', 'accepta' ),
			'section'     => 'accepta_hero_content_section',
			'type'        => 'text',
			'priority'    => 10,
		)
	);

	// Hero Text
	$wp_customize->add_setting(
		'accepta_hero_text',
		array(
			'default'           => 'Accepta is a modern WordPress theme built to stand out: fullscreen hero, smooth parallax backgrounds, and an overlay header that appears as you scroll. Change layout, colors, and fonts in the Customizer—no coding required.',
			'sanitize_callback' => 'wp_kses_post',
			'transport'         => 'postMessage',
		)
	);

	$wp_customize->add_control(
		'accepta_hero_text',
		array(
			'label'       => __( 'Text', 'accepta' ),
			'description' => __( 'Enter the hero section description text.', 'accepta' ),
			'section'     => 'accepta_hero_content_section',
			'type'        => 'textarea',
			'priority'    => 20,
		)
	);

	// Hero Button Text
	$wp_customize->add_setting(
		'accepta_hero_button_text',
		array(
			'default'           => 'Check Now',
			'sanitize_callback' => 'sanitize_text_field',
			'transport'         => 'postMessage',
		)
	);

	$wp_customize->add_control(
		'accepta_hero_button_text',
		array(
			'label'       => __( 'Button Text', 'accepta' ),
			'description' => __( 'Enter the button label text.', 'accepta' ),
			'section'     => 'accepta_hero_content_section',
			'type'        => 'text',
			'priority'    => 30,
		)
	);

	// Hero Button URL
	$wp_customize->add_setting(
		'accepta_hero_button_url',
		array(
			'default'           => 'https://wpdino.com',
			'sanitize_callback' => 'esc_url_raw',
			'transport'         => 'postMessage',
		)
	);

	$wp_customize->add_control(
		'accepta_hero_button_url',
		array(
			'label'       => __( 'Button URL', 'accepta' ),
			'description' => __( 'Enter the button link URL.', 'accepta' ),
			'section'     => 'accepta_hero_content_section',
			'type'        => 'url',
			'priority'    => 40,
		)
	);

	// Hero Button Style (outline by default)
	$wp_customize->add_setting(
		'accepta_hero_button_style',
		array(
			'default'           => 'outline',
			'sanitize_callback' => 'accepta_sanitize_button_style',
			'transport'         => 'postMessage',
		)
	);

	$wp_customize->add_control(
		'accepta_hero_button_style',
		array(
			'label'       => __( 'Button Style', 'accepta' ),
			'description' => __( 'Choose the button style.', 'accepta' ),
			'section'     => 'accepta_hero_content_section',
			'type'        => 'select',
			'choices'     => array(
				'primary'   => __( 'Primary', 'accepta' ),
				'secondary' => __( 'Secondary', 'accepta' ),
				'outline'   => __( 'Outline', 'accepta' ),
			),
			'priority'    => 50,
		)
	);

	// Hero Content Styling Section
	$wp_customize->add_section(
		'accepta_hero_styling_section',
		array(
			'title'       => __( 'Content Styling', 'accepta' ),
			'description' => __( 'Style the hero section content (colors, sizes).', 'accepta' ),
			'panel'       => 'accepta_hero_panel',
			'priority'    => 50,
		)
	);

	// Heading Color
	$wp_customize->add_setting(
		'accepta_hero_heading_color',
		array(
			'default'           => '#ffffff',
			'sanitize_callback' => 'sanitize_hex_color',
			'transport'         => 'postMessage',
		)
	);

	$wp_customize->add_control(
		new WP_Customize_Color_Control(
			$wp_customize,
			'accepta_hero_heading_color',
			array(
				'label'       => __( 'Heading Color', 'accepta' ),
				'description' => __( 'Choose the heading text color.', 'accepta' ),
				'section'     => 'accepta_hero_styling_section',
				'priority'    => 10,
			)
		)
	);

	// Heading Size
	$wp_customize->add_setting(
		'accepta_hero_heading_size',
		array(
			'default'           => 48,
			'sanitize_callback' => 'absint',
			'transport'         => 'postMessage',
		)
	);

	$wp_customize->add_control(
		'accepta_hero_heading_size',
		array(
			'label'       => __( 'Heading Size (px)', 'accepta' ),
			'description' => __( 'Set the heading font size.', 'accepta' ),
			'section'     => 'accepta_hero_styling_section',
			'type'        => 'number',
			'input_attrs' => array(
				'min'  => 16,
				'max'  => 120,
				'step' => 1,
			),
			'priority'    => 20,
		)
	);

	// Text Color
	$wp_customize->add_setting(
		'accepta_hero_text_color',
		array(
			'default'           => '#ffffff',
			'sanitize_callback' => 'sanitize_hex_color',
			'transport'         => 'postMessage',
		)
	);

	$wp_customize->add_control(
		new WP_Customize_Color_Control(
			$wp_customize,
			'accepta_hero_text_color',
			array(
				'label'       => __( 'Text Color', 'accepta' ),
				'description' => __( 'Choose the text color.', 'accepta' ),
				'section'     => 'accepta_hero_styling_section',
				'priority'    => 30,
			)
		)
	);

	// Text Size
	$wp_customize->add_setting(
		'accepta_hero_text_size',
		array(
			'default'           => 18,
			'sanitize_callback' => 'absint',
			'transport'         => 'postMessage',
		)
	);

	$wp_customize->add_control(
		'accepta_hero_text_size',
		array(
			'label'       => __( 'Text Size (px)', 'accepta' ),
			'description' => __( 'Set the text font size.', 'accepta' ),
			'section'     => 'accepta_hero_styling_section',
			'type'        => 'number',
			'input_attrs' => array(
				'min'  => 12,
				'max'  => 48,
				'step' => 1,
			),
			'priority'    => 40,
		)
	);

	// Content Alignment Section
	$wp_customize->add_section(
		'accepta_hero_alignment_section',
		array(
			'title'       => __( 'Content Alignment', 'accepta' ),
			'description' => __( 'Control the alignment of hero section content.', 'accepta' ),
			'panel'       => 'accepta_hero_panel',
			'priority'    => 60,
		)
	);

	// Horizontal Alignment (with responsive tabs)
	$wp_customize->add_setting(
		'accepta_hero_align_horizontal',
		array(
			'default'           => json_encode( array( 'desktop' => 'center', 'tablet' => 'center', 'mobile' => 'center' ) ),
			'sanitize_callback' => 'accepta_sanitize_responsive_alignment',
			'transport'         => 'postMessage',
		)
	);

	$wp_customize->add_control(
		new Accepta_Alignment_Control(
			$wp_customize,
			'accepta_hero_align_horizontal',
			array(
				'label'       => __( 'Horizontal Alignment', 'accepta' ),
				'description' => __( 'Align content horizontally across different screen sizes.', 'accepta' ),
				'section'     => 'accepta_hero_alignment_section',
				'responsive'  => true,
				'alignments'  => array(
					'flex-start' => array( 'label' => __( 'Start', 'accepta' ) ),
					'center' => array( 'label' => __( 'Center', 'accepta' ) ),
					'flex-end' => array( 'label' => __( 'End', 'accepta' ) ),
				),
				'priority'    => 10,
			)
		)
	);

	// Vertical Alignment (with responsive tabs)
	$wp_customize->add_setting(
		'accepta_hero_align_vertical',
		array(
			'default'           => json_encode( array( 'desktop' => 'center', 'tablet' => 'center', 'mobile' => 'center' ) ),
			'sanitize_callback' => 'accepta_sanitize_responsive_alignment',
			'transport'         => 'postMessage',
		)
	);

	$wp_customize->add_control(
		new Accepta_Alignment_Control(
			$wp_customize,
			'accepta_hero_align_vertical',
			array(
				'label'       => __( 'Vertical Alignment', 'accepta' ),
				'description' => __( 'Align content vertically across different screen sizes.', 'accepta' ),
				'section'     => 'accepta_hero_alignment_section',
				'responsive'  => true,
				'alignments'  => array(
					'flex-start' => array( 'label' => __( 'Start', 'accepta' ) ),
					'center' => array( 'label' => __( 'Center', 'accepta' ) ),
					'flex-end' => array( 'label' => __( 'End', 'accepta' ) ),
				),
				'priority'    => 20,
			)
		)
	);

	// Container Width Setting
	$wp_customize->add_setting(
		'accepta_container_width',
		array(
			'default'           => 1200,
			'sanitize_callback' => 'absint',
			'transport'         => 'postMessage',
		)
	);

	$wp_customize->add_control(
		new Accepta_Range_Control(
			$wp_customize,
			'accepta_container_width',
			array(
				'label'       => __( 'Container Width', 'accepta' ),
				'description' => __( 'Set the maximum width of the site container. Range: 800px - 1600px.', 'accepta' ),
				'section'     => 'accepta_layout_section',
				'priority'    => 10,
				'min'         => 800,
				'max'         => 1600,
				'step'        => 10,
				'unit'        => 'px',
			)
		)
	);

	// Sidebar Layout Setting
	$wp_customize->add_setting(
		'accepta_sidebar_layout',
		array(
			'default'           => 'none',
			'sanitize_callback' => 'accepta_sanitize_sidebar_layout',
			'transport'         => 'postMessage',
		)
	);

	$wp_customize->add_control(
		new Accepta_Layout_Control(
			$wp_customize,
			'accepta_sidebar_layout',
			array(
				'label'       => __( 'Sidebar Layout', 'accepta' ),
				'description' => __( 'Choose the sidebar position for your site layout.', 'accepta' ),
				'section'     => 'accepta_layout_section',
				'priority'    => 20,
				'layouts'     => array(
					'none' => array(
						'label' => __( 'No Sidebar', 'accepta' ),
					),
					'left' => array(
						'label' => __( 'Left Sidebar', 'accepta' ),
					),
					'right' => array(
						'label' => __( 'Right Sidebar', 'accepta' ),
					),
				),
			)
		)
	);

	// Content Box Shadow Setting
	$wp_customize->add_setting(
		'accepta_content_box_shadow',
		array(
			'default'           => 'default',
			'sanitize_callback' => 'accepta_sanitize_content_box_shadow',
			'transport'         => 'postMessage',
		)
	);

	$wp_customize->add_control(
		'accepta_content_box_shadow',
		array(
			'label'       => __( 'Content Box Shadow', 'accepta' ),
			'description' => __( 'Control when box shadow appears on the content container.', 'accepta' ),
			'section'     => 'accepta_layout_section',
			'type'        => 'select',
			'priority'    => 25,
			'choices'     => array(
				'default'          => __( 'All Layouts', 'accepta' ),
				'only-with-sidebar' => __( 'Only With Sidebars', 'accepta' ),
				'none'             => __( 'None', 'accepta' ),
			),
		)
	);

	// Content Padding Setting
	$wp_customize->add_setting(
		'accepta_content_padding',
		array(
			'default'           => json_encode( array(
				'desktop' => array( 'top' => '0', 'right' => '0', 'bottom' => '0', 'left' => '0', 'unit' => 'px' ),
				'tablet'  => array( 'top' => '0', 'right' => '0', 'bottom' => '0', 'left' => '0', 'unit' => 'px' ),
				'mobile'  => array( 'top' => '0', 'right' => '0', 'bottom' => '0', 'left' => '0', 'unit' => 'px' ),
			) ),
			'sanitize_callback' => 'accepta_sanitize_spacing',
			'transport'         => 'postMessage',
		)
	);

	$wp_customize->add_control(
		new Accepta_Spacing_Control(
			$wp_customize,
			'accepta_content_padding',
			array(
				'label'       => __( 'Content Padding', 'accepta' ),
				'description' => __( 'Set padding around the main content area.', 'accepta' ),
				'section'     => 'accepta_layout_section',
				'priority'    => 30,
				'responsive'  => true,
				'units'       => array( 'px', 'em', 'rem', '%' ),
				'default_unit' => 'px',
			)
		)
	);

	// Content Margin Setting
	$wp_customize->add_setting(
		'accepta_content_margin',
		array(
			'default'           => json_encode( array(
				'desktop' => array( 'top' => '0', 'right' => '0', 'bottom' => '0', 'left' => '0', 'unit' => 'px' ),
				'tablet'  => array( 'top' => '0', 'right' => '0', 'bottom' => '0', 'left' => '0', 'unit' => 'px' ),
				'mobile'  => array( 'top' => '0', 'right' => '0', 'bottom' => '0', 'left' => '0', 'unit' => 'px' ),
			) ),
			'sanitize_callback' => 'accepta_sanitize_spacing',
			'transport'         => 'postMessage',
		)
	);

	$wp_customize->add_control(
		new Accepta_Spacing_Control(
			$wp_customize,
			'accepta_content_margin',
			array(
				'label'       => __( 'Content Margin', 'accepta' ),
				'description' => __( 'Set margin around the main content area.', 'accepta' ),
				'section'     => 'accepta_layout_section',
				'priority'    => 40,
				'responsive'  => true,
				'units'       => array( 'px', 'em', 'rem', '%' ),
				'default_unit' => 'px',
			)
		)
	);

	// Global Primary Color
	$wp_customize->add_setting(
		'accepta_primary_color',
		array(
			'default'           => '#0073aa',
			'sanitize_callback' => 'sanitize_hex_color',
			'transport'         => 'postMessage',
		)
	);

	$wp_customize->add_control(
		new WP_Customize_Color_Control(
			$wp_customize,
			'accepta_primary_color',
			array(
				'label'       => __( 'Global Primary Color', 'accepta' ),
				'description' => __( 'Set the main brand color used throughout the site for buttons, links, and accents.', 'accepta' ),
				'section'     => 'colors',
				'priority'    => 10,
			)
		)
	);

	// Background Color
	$wp_customize->add_setting(
		'accepta_background_color',
		array(
			'default'           => '#ffffff',
			'sanitize_callback' => 'sanitize_hex_color',
			'transport'         => 'postMessage',
		)
	);

	$wp_customize->add_control(
		new WP_Customize_Color_Control(
			$wp_customize,
			'accepta_background_color',
			array(
				'label'       => __( 'Background Color', 'accepta' ),
				'description' => __( 'Set the main background color for the site content areas.', 'accepta' ),
				'section'     => 'colors',
				'priority'    => 20,
			)
		)
	);

	// Text Color
	$wp_customize->add_setting(
		'accepta_text_color',
		array(
			'default'           => '#333333',
			'sanitize_callback' => 'sanitize_hex_color',
			'transport'         => 'postMessage',
		)
	);

	$wp_customize->add_control(
		new WP_Customize_Color_Control(
			$wp_customize,
			'accepta_text_color',
			array(
				'label'       => __( 'Text Color', 'accepta' ),
				'description' => __( 'Set the main text color used for content and paragraphs.', 'accepta' ),
				'section'     => 'colors',
				'priority'    => 30,
			)
		)
	);

	// Link Color
	$wp_customize->add_setting(
		'accepta_link_color',
		array(
			'default'           => '#0073aa',
			'sanitize_callback' => 'sanitize_hex_color',
			'transport'         => 'postMessage',
		)
	);

	$wp_customize->add_control(
		new WP_Customize_Color_Control(
			$wp_customize,
			'accepta_link_color',
			array(
				'label'       => __( 'Link Color', 'accepta' ),
				'description' => __( 'Set the color for links in content.', 'accepta' ),
				'section'     => 'colors',
				'priority'    => 40,
			)
		)
	);

	// Link Hover Color
	$wp_customize->add_setting(
		'accepta_link_hover_color',
		array(
			'default'           => '#005a87',
			'sanitize_callback' => 'sanitize_hex_color',
			'transport'         => 'postMessage',
		)
	);

	$wp_customize->add_control(
		new WP_Customize_Color_Control(
			$wp_customize,
			'accepta_link_hover_color',
			array(
				'label'       => __( 'Link Hover Color', 'accepta' ),
				'description' => __( 'Set the color for links when hovered or focused.', 'accepta' ),
				'section'     => 'colors',
				'priority'    => 50,
			)
		)
	);

	// Typography Panel
	$wp_customize->add_panel(
		'accepta_typography_panel',
		array(
			'title'       => __( 'Typography', 'accepta' ),
			'description' => __( 'Customize fonts, sizes, and typography settings for your site.', 'accepta' ),
			'priority'    => 60,
		)
	);

	// Body Typography Section
	$wp_customize->add_section(
		'accepta_body_typography_section',
		array(
			'title'       => __( 'Body Typography', 'accepta' ),
			'description' => __( 'Set typography for body text and paragraphs.', 'accepta' ),
			'panel'       => 'accepta_typography_panel',
			'priority'    => 10,
		)
	);

	// All Headings Typography Section (Default)
	$wp_customize->add_section(
		'accepta_all_headings_section',
		array(
			'title'       => __( 'All Headings (Default)', 'accepta' ),
			'description' => __( 'Set default typography for all headings (font family, weight, line height, etc.). Font sizes are set individually for each heading level below with responsive options.', 'accepta' ),
			'panel'       => 'accepta_typography_panel',
			'priority'    => 20,
		)
	);

	// Post/Page Title Typography Section
	$wp_customize->add_section(
		'accepta_post_title_typography_section',
		array(
			'title'       => __( 'Post/Page Title', 'accepta' ),
			'description' => __( 'Set typography for post and page titles.', 'accepta' ),
			'panel'       => 'accepta_typography_panel',
			'priority'    => 20.5,
		)
	);

	// H1 Typography Section
	$wp_customize->add_section(
		'accepta_h1_typography_section',
		array(
			'title'       => __( 'H1 Typography', 'accepta' ),
			'description' => __( 'Set typography for H1 headings and entry titles.', 'accepta' ),
			'panel'       => 'accepta_typography_panel',
			'priority'    => 21,
		)
	);

	// H2 Typography Section
	$wp_customize->add_section(
		'accepta_h2_typography_section',
		array(
			'title'       => __( 'H2 Typography', 'accepta' ),
			'description' => __( 'Set typography for H2 headings.', 'accepta' ),
			'panel'       => 'accepta_typography_panel',
			'priority'    => 22,
		)
	);

	// H3 Typography Section
	$wp_customize->add_section(
		'accepta_h3_typography_section',
		array(
			'title'       => __( 'H3 Typography', 'accepta' ),
			'description' => __( 'Set typography for H3 headings.', 'accepta' ),
			'panel'       => 'accepta_typography_panel',
			'priority'    => 23,
		)
	);

	// H4 Typography Section
	$wp_customize->add_section(
		'accepta_h4_typography_section',
		array(
			'title'       => __( 'H4 Typography', 'accepta' ),
			'description' => __( 'Set typography for H4 headings.', 'accepta' ),
			'panel'       => 'accepta_typography_panel',
			'priority'    => 24,
		)
	);

	// H5 Typography Section
	$wp_customize->add_section(
		'accepta_h5_typography_section',
		array(
			'title'       => __( 'H5 Typography', 'accepta' ),
			'description' => __( 'Set typography for H5 headings.', 'accepta' ),
			'panel'       => 'accepta_typography_panel',
			'priority'    => 25,
		)
	);

	// H6 Typography Section
	$wp_customize->add_section(
		'accepta_h6_typography_section',
		array(
			'title'       => __( 'H6 Typography', 'accepta' ),
			'description' => __( 'Set typography for H6 headings.', 'accepta' ),
			'panel'       => 'accepta_typography_panel',
			'priority'    => 26,
		)
	);

	// Button Typography Section
	$wp_customize->add_section(
		'accepta_button_typography_section',
		array(
			'title'       => __( 'Button Typography', 'accepta' ),
			'description' => __( 'Set typography for buttons and form elements.', 'accepta' ),
			'panel'       => 'accepta_typography_panel',
			'priority'    => 30,
		)
	);

	// Body Typography
	$wp_customize->add_setting(
		'accepta_body_typography',
		array(
		'default'           => json_encode( array(
			'font_family' => 'Outfit',
			'font_size' => '16',
			'font_size_desktop' => '16',
			'font_size_tablet' => '16',
			'font_size_mobile' => '15',
			'font_weight' => '300',
			'line_height' => '1.6',
			'letter_spacing' => '0',
		) ),
			'sanitize_callback' => 'accepta_sanitize_typography',
			'transport'         => 'postMessage',
		)
	);

	$wp_customize->add_control(
		new Accepta_Typography_Control(
			$wp_customize,
			'accepta_body_typography',
			array(
				'label'       => __( 'Body Typography', 'accepta' ),
				'description' => __( 'Set typography for body text and paragraphs.', 'accepta' ),
				'section'     => 'accepta_body_typography_section',
				'priority'    => 10,
			)
		)
	);

	// All Headings Typography Control (Default)
	$wp_customize->add_setting(
		'accepta_all_headings_typography',
		array(
			'default'           => json_encode( array(
				'font_family' => '',
				'font_weight' => '400',
				'line_height' => '1.2',
				'letter_spacing' => '0',
			) ),
			'sanitize_callback' => 'accepta_sanitize_typography',
			'transport'         => 'postMessage',
		)
	);

	$wp_customize->add_control(
		new Accepta_Typography_Control(
			$wp_customize,
			'accepta_all_headings_typography',
			array(
				'label'       => __( 'All Headings Typography', 'accepta' ),
				'description' => __( 'Set default typography for all headings (font family, weight, line height, etc.). Font sizes should be set individually for each heading level below.', 'accepta' ),
				'section'     => 'accepta_all_headings_section',
				'priority'    => 10,
				'show_font_size' => false, // Hide font size - individual headings have responsive font sizes
			)
		)
	);

	// Post/Page Title Typography Control
	$wp_customize->add_setting(
		'accepta_post_title_typography',
		array(
			'default'           => json_encode( array(
				'font_family' => '',
				'font_size' => '32',
				'font_size_desktop' => '32',
				'font_size_tablet' => '28',
				'font_size_mobile' => '24',
				'font_weight' => '700',
				'line_height' => '1.3',
				'letter_spacing' => '0',
			) ),
			'sanitize_callback' => 'accepta_sanitize_typography',
			'transport'         => 'postMessage',
		)
	);

	$wp_customize->add_control(
		new Accepta_Typography_Control(
			$wp_customize,
			'accepta_post_title_typography',
			array(
				'label'       => __( 'Post/Page Title Typography', 'accepta' ),
				'description' => __( 'Set typography for post and page titles (.entry-title).', 'accepta' ),
				'section'     => 'accepta_post_title_typography_section',
				'priority'    => 10,
			)
		)
	);

	// Individual Heading Typography Controls

	// H1 Typography Control
	$wp_customize->add_setting(
		'accepta_h1_typography',
		array(
			'default'           => json_encode( array(
				'font_family' => '',
				'font_size' => '32',
				'font_size_desktop' => '32',
				'font_size_tablet' => '28',
				'font_size_mobile' => '24',
				'font_weight' => '400',
				'line_height' => '1.2',
				'letter_spacing' => '0',
			) ),
			'sanitize_callback' => 'accepta_sanitize_typography',
			'transport'         => 'postMessage',
		)
	);

	$wp_customize->add_control(
		new Accepta_Typography_Control(
			$wp_customize,
			'accepta_h1_typography',
			array(
				'label'       => __( 'H1 Typography', 'accepta' ),
				'description' => __( 'Set typography for H1 headings and entry titles.', 'accepta' ),
				'section'     => 'accepta_h1_typography_section',
				'priority'    => 10,
			)
		)
	);

	// H2 Typography Control
	$wp_customize->add_setting(
		'accepta_h2_typography',
		array(
			'default'           => json_encode( array(
				'font_family' => '',
				'font_size' => '24',
				'font_size_desktop' => '24',
				'font_size_tablet' => '22',
				'font_size_mobile' => '20',
				'font_weight' => '400',
				'line_height' => '1.2',
				'letter_spacing' => '0',
			) ),
			'sanitize_callback' => 'accepta_sanitize_typography',
			'transport'         => 'postMessage',
		)
	);

	$wp_customize->add_control(
		new Accepta_Typography_Control(
			$wp_customize,
			'accepta_h2_typography',
			array(
				'label'       => __( 'H2 Typography', 'accepta' ),
				'description' => __( 'Set typography for H2 headings.', 'accepta' ),
				'section'     => 'accepta_h2_typography_section',
				'priority'    => 10,
			)
		)
	);

	// H3 Typography Control
	$wp_customize->add_setting(
		'accepta_h3_typography',
		array(
			'default'           => json_encode( array(
				'font_family' => '',
				'font_size' => '19',
				'font_size_desktop' => '19',
				'font_size_tablet' => '18',
				'font_size_mobile' => '17',
				'font_weight' => '400',
				'line_height' => '1.2',
				'letter_spacing' => '0',
			) ),
			'sanitize_callback' => 'accepta_sanitize_typography',
			'transport'         => 'postMessage',
		)
	);

	$wp_customize->add_control(
		new Accepta_Typography_Control(
			$wp_customize,
			'accepta_h3_typography',
			array(
				'label'       => __( 'H3 Typography', 'accepta' ),
				'description' => __( 'Set typography for H3 headings.', 'accepta' ),
				'section'     => 'accepta_h3_typography_section',
				'priority'    => 10,
			)
		)
	);

	// H4 Typography Control
	$wp_customize->add_setting(
		'accepta_h4_typography',
		array(
			'default'           => json_encode( array(
				'font_family' => '',
				'font_size' => '16',
				'font_size_desktop' => '16',
				'font_size_tablet' => '15',
				'font_size_mobile' => '15',
				'font_weight' => '400',
				'line_height' => '1.2',
				'letter_spacing' => '0',
			) ),
			'sanitize_callback' => 'accepta_sanitize_typography',
			'transport'         => 'postMessage',
		)
	);

	$wp_customize->add_control(
		new Accepta_Typography_Control(
			$wp_customize,
			'accepta_h4_typography',
			array(
				'label'       => __( 'H4 Typography', 'accepta' ),
				'description' => __( 'Set typography for H4 headings.', 'accepta' ),
				'section'     => 'accepta_h4_typography_section',
				'priority'    => 10,
			)
		)
	);

	// H5 Typography Control
	$wp_customize->add_setting(
		'accepta_h5_typography',
		array(
			'default'           => json_encode( array(
				'font_family' => '',
				'font_size' => '13',
				'font_size_desktop' => '13',
				'font_size_tablet' => '13',
				'font_size_mobile' => '12',
				'font_weight' => '400',
				'line_height' => '1.2',
				'letter_spacing' => '0',
			) ),
			'sanitize_callback' => 'accepta_sanitize_typography',
			'transport'         => 'postMessage',
		)
	);

	$wp_customize->add_control(
		new Accepta_Typography_Control(
			$wp_customize,
			'accepta_h5_typography',
			array(
				'label'       => __( 'H5 Typography', 'accepta' ),
				'description' => __( 'Set typography for H5 headings.', 'accepta' ),
				'section'     => 'accepta_h5_typography_section',
				'priority'    => 10,
			)
		)
	);

	// H6 Typography Control
	$wp_customize->add_setting(
		'accepta_h6_typography',
		array(
			'default'           => json_encode( array(
				'font_family' => '',
				'font_size' => '11',
				'font_size_desktop' => '11',
				'font_size_tablet' => '11',
				'font_size_mobile' => '10',
				'font_weight' => '400',
				'line_height' => '1.2',
				'letter_spacing' => '0',
			) ),
			'sanitize_callback' => 'accepta_sanitize_typography',
			'transport'         => 'postMessage',
		)
	);

	$wp_customize->add_control(
		new Accepta_Typography_Control(
			$wp_customize,
			'accepta_h6_typography',
			array(
				'label'       => __( 'H6 Typography', 'accepta' ),
				'description' => __( 'Set typography for H6 headings.', 'accepta' ),
				'section'     => 'accepta_h6_typography_section',
				'priority'    => 10,
			)
		)
	);


	// Button Typography
	$wp_customize->add_setting(
		'accepta_button_typography',
		array(
			'default'           => json_encode( array(
				'font_family' => '',
				'font_size' => '14',
				'font_size_desktop' => '14',
				'font_size_tablet' => '14',
				'font_size_mobile' => '13',
				'font_weight' => '500',
				'line_height' => '1.4',
				'letter_spacing' => '0.5',
				'text_transform' => 'uppercase',
			) ),
			'sanitize_callback' => 'accepta_sanitize_typography',
			'transport'         => 'postMessage',
		)
	);

	$wp_customize->add_control(
		new Accepta_Typography_Control(
			$wp_customize,
			'accepta_button_typography',
			array(
				'label'       => __( 'Button Typography', 'accepta' ),
				'description' => __( 'Set typography for buttons and form elements.', 'accepta' ),
				'section'     => 'accepta_button_typography_section',
				'priority'    => 10,
			)
		)
	);

	if ( isset( $wp_customize->selective_refresh ) ) {
		$wp_customize->selective_refresh->add_partial(
			'blogname',
			array(
				'selector'        => '.site-title a',
				'render_callback' => 'accepta_customize_partial_blogname',
			)
		);
		$wp_customize->selective_refresh->add_partial(
			'blogdescription',
			array(
				'selector'        => '.site-description',
				'render_callback' => 'accepta_customize_partial_blogdescription',
			)
		);
	}

	// WooCommerce Panel (only when WooCommerce is active)
	if ( class_exists( 'WooCommerce' ) ) {
		$wp_customize->add_panel(
			'accepta_woocommerce_panel',
			array(
				'title'       => __( 'WooCommerce', 'accepta' ),
				'description' => __( 'Customize how WooCommerce is displayed in the theme: header minicart, shop, and single product.', 'accepta' ),
				'priority'    => 125,
			)
		);

		// Header Minicart Section
		$wp_customize->add_section(
			'accepta_woo_header_minicart',
			array(
				'title'       => __( 'Header Minicart', 'accepta' ),
				'description' => __( 'Settings for the cart icon and minicart in the header.', 'accepta' ),
				'panel'       => 'accepta_woocommerce_panel',
				'priority'    => 10,
			)
		);

		$wp_customize->add_setting(
			'accepta_woo_display_header_cart',
			array(
				'default'           => true,
				'sanitize_callback' => 'wp_validate_boolean',
				'transport'         => 'refresh',
			)
		);
		$wp_customize->add_control(
			'accepta_woo_display_header_cart',
			array(
				'label'       => __( 'Display cart icon in header', 'accepta' ),
				'description' => __( 'Show the WooCommerce cart icon and item count in the header.', 'accepta' ),
				'section'     => 'accepta_woo_header_minicart',
				'type'        => 'checkbox',
				'priority'    => 10,
			)
		);

		// Shop Section
		$wp_customize->add_section(
			'accepta_woo_shop',
			array(
				'title'       => __( 'Shop', 'accepta' ),
				'description' => __( 'Settings for the main shop and product archive pages.', 'accepta' ),
				'panel'       => 'accepta_woocommerce_panel',
				'priority'    => 20,
			)
		);

		$wp_customize->add_setting(
			'accepta_woo_shop_columns',
			array(
				'default'           => 4,
				'sanitize_callback' => 'absint',
				'transport'         => 'refresh',
			)
		);
		$wp_customize->add_control(
			'accepta_woo_shop_columns',
			array(
				'label'       => __( 'Products per row', 'accepta' ),
				'description' => __( 'Number of products to show per row on shop and archive pages.', 'accepta' ),
				'section'     => 'accepta_woo_shop',
				'type'        => 'select',
				'choices'     => array(
					'2' => '2',
					'3' => '3',
					'4' => '4',
				),
				'priority'    => 10,
			)
		);

		// Single Product Section
		$wp_customize->add_section(
			'accepta_woo_single_product',
			array(
				'title'       => __( 'Single Product', 'accepta' ),
				'description' => __( 'Settings for the single product page layout and display.', 'accepta' ),
				'panel'       => 'accepta_woocommerce_panel',
				'priority'    => 30,
			)
		);

		$wp_customize->add_setting(
			'accepta_woo_single_sidebar',
			array(
				'default'           => 'none',
				'sanitize_callback' => function( $input ) {
					$valid = array( 'none', 'left', 'right' );
					return in_array( $input, $valid, true ) ? $input : 'none';
				},
				'transport'         => 'refresh',
			)
		);
		$wp_customize->add_control(
			'accepta_woo_single_sidebar',
			array(
				'label'       => __( 'Sidebar on single product', 'accepta' ),
				'description' => __( 'Show a sidebar on single product pages.', 'accepta' ),
				'section'     => 'accepta_woo_single_product',
				'type'        => 'select',
				'choices'     => array(
					'none'  => __( 'No sidebar', 'accepta' ),
					'left'  => __( 'Sidebar left', 'accepta' ),
					'right' => __( 'Sidebar right', 'accepta' ),
				),
				'priority'    => 10,
			)
		);
	}
	
	// Add Main Footer Panel
	$wp_customize->add_panel(
		'accepta_footer_panel',
		array(
			'title'       => __( 'Footer', 'accepta' ),
			'description' => __( 'Customize all aspects of your footer including layout, social media, and styling.', 'accepta' ),
			'priority'    => 130,
		)
	);

	// Footer Layouts Section
	$wp_customize->add_section(
		'accepta_footer_layouts',
		array(
			'title'       => __( 'Layouts', 'accepta' ),
			'description' => __( 'Configure footer layout and column structure.', 'accepta' ),
			'panel'       => 'accepta_footer_panel',
			'priority'    => 10,
		)
	);

	// Footer Columns Setting
	$wp_customize->add_setting(
		'accepta_footer_columns',
		array(
			'default'           => '4',
			'sanitize_callback' => 'accepta_sanitize_footer_columns',
			'transport'         => 'refresh',
		)
	);

	$wp_customize->add_control(
		new Accepta_Layout_Control(
			$wp_customize,
			'accepta_footer_columns',
			array(
				'label'       => __( 'Footer Columns', 'accepta' ),
				'description' => __( 'Choose how many columns to display in the footer.', 'accepta' ),
				'section'     => 'accepta_footer_layouts',
				'priority'    => 10,
				'layouts'     => array(
					'0' => array(
						'label' => __( 'No Columns', 'accepta' ),
					),
					'1' => array(
						'label' => __( '1 Column', 'accepta' ),
					),
					'2' => array(
						'label' => __( '2 Columns', 'accepta' ),
					),
					'3' => array(
						'label' => __( '3 Columns', 'accepta' ),
					),
					'4' => array(
						'label' => __( '4 Columns', 'accepta' ),
					),
				),
			)
		)
	);

	// Footer Socials Section
	$wp_customize->add_section(
		'accepta_footer_socials',
		array(
			'title'       => __( 'Socials', 'accepta' ),
			'description' => __( 'Add your social media profile links. They will appear in the site footer.', 'accepta' ),
			'panel'       => 'accepta_footer_panel',
			'priority'    => 20,
		)
	);

	// Display Social Icons Toggle
	$wp_customize->add_setting(
		'accepta_display_social_icons',
		array(
			'default'           => true,
			'sanitize_callback' => 'wp_validate_boolean',
			'transport'         => 'refresh',
		)
	);

	$wp_customize->add_control(
		'accepta_display_social_icons',
		array(
			'label'       => __( 'Display Social Icons', 'accepta' ),
			'description' => __( 'Enable or disable the display of social media icons in the footer.', 'accepta' ),
			'section'     => 'accepta_footer_socials',
			'type'        => 'checkbox',
			'priority'    => 5,
		)
	);

	// Footer Styling Section
	$wp_customize->add_section(
		'accepta_footer_styling',
		array(
			'title'       => __( 'Styling', 'accepta' ),
			'description' => __( 'Customize footer spacing and visual appearance. All changes are previewed live with responsive controls.', 'accepta' ),
			'panel'       => 'accepta_footer_panel',
			'priority'    => 30,
		)
	);

	// Footer Background
	$wp_customize->add_setting(
		'accepta_footer_background',
		array(
			'default'           => json_encode( array(
				'type' => 'solid',
				'color' => '#2c3e50',
				'gradient_type' => 'linear',
				'gradient_angle' => '90',
				'gradient_start' => '#2c3e50',
				'gradient_end' => '#34495e',
				'image' => '',
				'size' => 'cover',
				'repeat' => 'no-repeat',
				'position' => 'center',
				'attachment' => 'scroll',
				'overlay_enabled' => false,
				'overlay_color' => '#000000',
				'overlay_opacity' => '0.5',
			) ),
			'sanitize_callback' => 'accepta_sanitize_background',
			'transport'         => 'postMessage',
		)
	);

	$wp_customize->add_control(
		new Accepta_Background_Control(
			$wp_customize,
			'accepta_footer_background',
			array(
				'label'       => __( 'Background', 'accepta' ),
				'description' => __( 'Choose a solid color, gradient, or image background for the footer area.', 'accepta' ),
				'section'     => 'accepta_footer_styling',
				'priority'    => 10,
			)
		)
	);

	// Footer Text Color
	$wp_customize->add_setting(
		'accepta_footer_text_color',
		array(
			'default'           => '#ffffff',
			'sanitize_callback' => 'sanitize_hex_color',
			'transport'         => 'postMessage',
		)
	);

	$wp_customize->add_control(
		new WP_Customize_Color_Control(
			$wp_customize,
			'accepta_footer_text_color',
			array(
				'label'       => __( 'Text Color', 'accepta' ),
				'description' => __( 'Set the text color for the footer area.', 'accepta' ),
				'section'     => 'accepta_footer_styling',
				'priority'    => 20,
			)
		)
	);

	// Footer Padding Control
	$wp_customize->add_setting(
		'accepta_footer_padding',
		array(
			'default'           => json_encode(array(
				'desktop' => array( 'top' => '60', 'right' => '0', 'bottom' => '60', 'left' => '0', 'unit' => 'px' ),
				'tablet'  => array( 'top' => '', 'right' => '', 'bottom' => '', 'left' => '', 'unit' => 'px' ),
				'mobile'  => array( 'top' => '', 'right' => '', 'bottom' => '', 'left' => '', 'unit' => 'px' ),
			)),
			'sanitize_callback' => 'accepta_sanitize_spacing',
			'transport'         => 'postMessage',
		)
	);

	$wp_customize->add_control(
		new Accepta_Spacing_Control(
			$wp_customize,
			'accepta_footer_padding',
			array(
				'label'       => __( 'Footer Padding', 'accepta' ),
				'description' => __( 'Set internal spacing for the footer area. Use responsive controls for different devices.', 'accepta' ),
				'section'     => 'accepta_footer_styling',
				'priority'    => 30,
				'responsive'  => true,
				'units'       => array( 'px', 'em', 'rem', '%' ),
				'default_unit' => 'px',
			)
		)
	);

	// Footer Margin Control
	$wp_customize->add_setting(
		'accepta_footer_margin',
		array(
			'default'           => json_encode(array(
				'desktop' => array( 'top' => '0', 'right' => '0', 'bottom' => '0', 'left' => '0', 'unit' => 'px' ),
				'tablet'  => array( 'top' => '', 'right' => '', 'bottom' => '', 'left' => '', 'unit' => 'px' ),
				'mobile'  => array( 'top' => '', 'right' => '', 'bottom' => '', 'left' => '', 'unit' => 'px' ),
			)),
			'sanitize_callback' => 'accepta_sanitize_spacing',
			'transport'         => 'postMessage',
		)
	);

	$wp_customize->add_control(
		new Accepta_Spacing_Control(
			$wp_customize,
			'accepta_footer_margin',
			array(
				'label'       => __( 'Footer Margin', 'accepta' ),
				'description' => __( 'Set external spacing around the footer area. Use responsive controls for different devices.', 'accepta' ),
				'section'     => 'accepta_footer_styling',
				'priority'    => 40,
				'responsive'  => true,
				'units'       => array( 'px', 'em', 'rem', '%' ),
				'default_unit' => 'px',
			)
		)
	);

	// Footer Copyright Text
	$wp_customize->add_setting(
		'accepta_footer_copyright',
		array(
			'default'           => __( '{copyright} {current-year} {site-title}. Powered by {wordpress}.', 'accepta' ),
			'sanitize_callback' => 'wp_kses_post',
			'transport'         => 'postMessage',
		)
	);

	$wp_customize->add_control(
		new Accepta_Copyright_Control(
			$wp_customize,
			'accepta_footer_copyright',
			array(
				'label'       => __( 'Copyright Text', 'accepta' ),
				'description' => __( 'Create your copyright text using dynamic tags. Click the tags below to insert them into your text.', 'accepta' ),
				'section'     => 'accepta_footer_layouts',
				'priority'    => 20,
			)
		)
	);

	// Social Media Repeater Control
	$wp_customize->add_setting(
		'accepta_social_media',
		array(
			'default'           => json_encode( array(
				array(
					'label' => 'Facebook',
					'url'   => 'https://facebook.com/yourpage',
					'icon_type' => 'fontawesome',
					'icon'  => 'fab fa-facebook-f',
					'custom_icon' => '',
				),
				array(
					'label' => 'Twitter',
					'url'   => 'https://twitter.com/yourusername',
					'icon_type' => 'fontawesome',
					'icon'  => 'fab fa-twitter',
					'custom_icon' => '',
				),
				array(
					'label' => 'Instagram',
					'url'   => 'https://instagram.com/yourusername',
					'icon_type' => 'fontawesome',
					'icon'  => 'fab fa-instagram',
					'custom_icon' => '',
				),
				array(
					'label' => 'LinkedIn',
					'url'   => 'https://linkedin.com/in/yourprofile',
					'icon_type' => 'fontawesome',
					'icon'  => 'fab fa-linkedin-in',
					'custom_icon' => '',
				),
			) ),
			'sanitize_callback' => 'accepta_sanitize_repeater',
			'transport'         => 'postMessage',
		)
	);

	$wp_customize->add_control(
		new Accepta_Repeater_Control(
			$wp_customize,
			'accepta_social_media',
			array(
				'label'       => __( 'Social Media Links', 'accepta' ),
				'description' => __( 'Add your social media profiles. You can use Font Awesome icons or upload custom images for icons.', 'accepta' ),
				'section'     => 'accepta_footer_socials',
				'priority'    => 10,
				'max_items'   => 8,
				'fields'      => array(
					'label' => array(
						'type'        => 'text',
						'label'       => __( 'Label', 'accepta' ),
						'placeholder' => __( 'e.g., Facebook', 'accepta' ),
					),
					'url' => array(
						'type'        => 'url',
						'label'       => __( 'URL', 'accepta' ),
						'placeholder' => __( 'https://example.com', 'accepta' ),
					),
					'icon_type' => array(
						'type'    => 'select',
						'label'   => __( 'Icon Type', 'accepta' ),
						'options' => array(
							'fontawesome' => __( 'Font Awesome', 'accepta' ),
							'custom'      => __( 'Custom Icon', 'accepta' ),
						),
					),
					'icon' => array(
						'type'        => 'fontawesome',
						'label'       => __( 'Font Awesome Icon', 'accepta' ),
						'placeholder' => __( 'fab fa-facebook-f', 'accepta' ),
					),
					'custom_icon' => array(
						'type'  => 'media',
						'label' => __( 'Custom Icon', 'accepta' ),
					),
				),
			)
		)
	);
}
add_action( 'customize_register', 'accepta_customize_register' );

/**
 * Sanitize checkbox values
 */
function accepta_sanitize_checkbox( $checked ) {
	return ( ( isset( $checked ) && true == $checked ) ? true : false );
}

/**
 * Sanitize sidebar layout values
 */
function accepta_sanitize_sidebar_layout( $input ) {
	$valid_layouts = array( 'none', 'left', 'right' );
	return in_array( $input, $valid_layouts, true ) ? $input : 'none';
}

/**
 * Sanitize content box shadow values
 */
function accepta_sanitize_content_box_shadow( $input ) {
	$valid_values = array( 'default', 'only-with-sidebar', 'none' );
	return in_array( $input, $valid_values, true ) ? $input : 'default';
}

/**
 * Sanitize hero height values
 */
function accepta_sanitize_hero_height( $input ) {
	$valid_heights = array( 'min-height', 'custom', 'fullscreen' );
	return in_array( $input, $valid_heights, true ) ? $input : 'min-height';
}

/**
 * Sanitize hero background type
 */
function accepta_sanitize_hero_bg_type( $input ) {
	$valid_types = array( 'color', 'gradient', 'image', 'video' );
	return in_array( $input, $valid_types, true ) ? $input : 'color';
}

/**
 * Sanitize video type
 */
function accepta_sanitize_video_type( $input ) {
	$valid_types = array( 'youtube', 'vimeo', 'mp4' );
	return in_array( $input, $valid_types, true ) ? $input : 'youtube';
}

/**
 * Sanitize gradient
 */
function accepta_sanitize_gradient( $input ) {
	if ( is_string( $input ) ) {
		$input = json_decode( $input, true );
	}
	if ( ! is_array( $input ) ) {
		return json_encode( array(
			'type'  => 'linear',
			'angle' => 90,
			'start' => '#6F9C50',
			'end'   => '#568F0C',
		) );
	}
	return json_encode( $input );
}

/**
 * Sanitize button style
 */
/**
 * Sanitize alignment value
 */
function accepta_sanitize_alignment( $input ) {
	$valid = array( 'flex-start', 'center', 'flex-end', 'space-between', 'space-around', 'space-evenly', 'stretch' );
	if ( in_array( $input, $valid, true ) ) {
		return $input;
	}
	return 'center';
}

/**
 * Sanitize responsive alignment (JSON with desktop/tablet/mobile keys)
 */
function accepta_sanitize_responsive_alignment( $input ) {
	$valid_alignments = array( 'flex-start', 'center', 'flex-end' );
	
	// If it's a JSON string, decode it
	if ( is_string( $input ) ) {
		$decoded = json_decode( $input, true );
		if ( is_array( $decoded ) ) {
			$sanitized = array();
			foreach ( array( 'desktop', 'tablet', 'mobile' ) as $device ) {
				$value = isset( $decoded[ $device ] ) ? $decoded[ $device ] : 'center';
				$sanitized[ $device ] = in_array( $value, $valid_alignments, true ) ? $value : 'center';
			}
			return json_encode( $sanitized );
		}
	}
	
	// If it's already an array
	if ( is_array( $input ) ) {
		$sanitized = array();
		foreach ( array( 'desktop', 'tablet', 'mobile' ) as $device ) {
			$value = isset( $input[ $device ] ) ? $input[ $device ] : 'center';
			$sanitized[ $device ] = in_array( $value, $valid_alignments, true ) ? $value : 'center';
		}
		return json_encode( $sanitized );
	}
	
	// Default fallback
	return json_encode( array( 'desktop' => 'center', 'tablet' => 'center', 'mobile' => 'center' ) );
}

function accepta_sanitize_button_style( $input ) {
	$valid_styles = array( 'primary', 'secondary', 'outline' );
	return in_array( $input, $valid_styles, true ) ? $input : 'primary';
}

/**
 * Sanitize repeater control values
 */
function accepta_sanitize_repeater( $input ) {
	$input = json_decode( $input, true );
	
	if ( ! is_array( $input ) ) {
		return json_encode( array() );
	}
	
	$sanitized = array();
	
	foreach ( $input as $item ) {
		if ( ! is_array( $item ) ) {
			continue;
		}
		
		$sanitized_item = array();
		
		// Sanitize each field
		if ( isset( $item['label'] ) ) {
			$sanitized_item['label'] = sanitize_text_field( $item['label'] );
		}
		
		if ( isset( $item['url'] ) ) {
			$sanitized_item['url'] = esc_url_raw( $item['url'] );
		}
		
		if ( isset( $item['icon_type'] ) ) {
			$sanitized_item['icon_type'] = in_array( $item['icon_type'], array( 'fontawesome', 'custom' ) ) ? $item['icon_type'] : 'fontawesome';
		}
		
		if ( isset( $item['icon'] ) ) {
			$sanitized_item['icon'] = sanitize_text_field( $item['icon'] );
		}
		
		if ( isset( $item['custom_icon'] ) ) {
			$sanitized_item['custom_icon'] = esc_url_raw( $item['custom_icon'] );
		}
		
		// Only add item if it has required fields
		if ( ! empty( $sanitized_item['label'] ) && ! empty( $sanitized_item['url'] ) ) {
			$sanitized[] = $sanitized_item;
		}
	}
	
	return json_encode( $sanitized );
}

/**
 * Sanitize typography settings
 *
 * @param string $input The typography JSON string.
 * @return string
 */
function accepta_sanitize_typography( $input ) {
	// If input is already an array, encode it
	if ( is_array( $input ) ) {
		$input = json_encode( $input );
	}
	
	// Decode JSON
	$typography = json_decode( $input, true );
	
	// If decoding failed, return empty JSON object
	if ( ! is_array( $typography ) ) {
		return json_encode( array() );
	}
	
	$sanitized = array();
	
	// Sanitize font family
	if ( isset( $typography['font_family'] ) ) {
		$sanitized['font_family'] = sanitize_text_field( $typography['font_family'] );
	}
	
	// Sanitize font size (legacy)
	if ( isset( $typography['font_size'] ) ) {
		$sanitized['font_size'] = absint( $typography['font_size'] );
	}
	
	// Sanitize responsive font sizes
	if ( isset( $typography['font_size_desktop'] ) && $typography['font_size_desktop'] !== '' ) {
		$sanitized['font_size_desktop'] = absint( $typography['font_size_desktop'] );
	}
	if ( isset( $typography['font_size_tablet'] ) && $typography['font_size_tablet'] !== '' ) {
		$sanitized['font_size_tablet'] = absint( $typography['font_size_tablet'] );
	}
	if ( isset( $typography['font_size_mobile'] ) && $typography['font_size_mobile'] !== '' ) {
		$sanitized['font_size_mobile'] = absint( $typography['font_size_mobile'] );
	}
	
	// Sanitize font weight
	if ( isset( $typography['font_weight'] ) ) {
		$valid_weights = array( '100', '200', '300', '400', '500', '600', '700', '800', '900' );
		$weight = sanitize_text_field( $typography['font_weight'] );
		$sanitized['font_weight'] = in_array( $weight, $valid_weights, true ) ? $weight : '';
	}
	
	// Sanitize line height
	if ( isset( $typography['line_height'] ) ) {
		$line_height = floatval( $typography['line_height'] );
		$sanitized['line_height'] = ( $line_height >= 0.5 && $line_height <= 5 ) ? $line_height : '';
	}
	
	// Sanitize letter spacing
	if ( isset( $typography['letter_spacing'] ) ) {
		$letter_spacing = floatval( $typography['letter_spacing'] );
		$sanitized['letter_spacing'] = ( $letter_spacing >= -5 && $letter_spacing <= 10 ) ? $letter_spacing : '';
	}
	
	// Sanitize text transform
	if ( isset( $typography['text_transform'] ) ) {
		$valid_transforms = array( 'none', 'uppercase', 'lowercase', 'capitalize' );
		$transform = sanitize_text_field( $typography['text_transform'] );
		$sanitized['text_transform'] = in_array( $transform, $valid_transforms, true ) ? $transform : '';
	}
	
	return json_encode( $sanitized );
}

/**
 * Get font choices for select controls
 *
 * @return array
 */
function accepta_get_font_choices() {
	$fonts = array(
		'' => __( 'Default', 'accepta' ),
	);
	
	// Add system fonts
	$system_fonts = array(
		'Arial, sans-serif' => 'Arial',
		'Helvetica, Arial, sans-serif' => 'Helvetica',
		'"Times New Roman", Times, serif' => 'Times New Roman',
		'Georgia, serif' => 'Georgia',
		'"Courier New", Courier, monospace' => 'Courier New',
		'Verdana, Geneva, sans-serif' => 'Verdana',
		'Tahoma, Geneva, sans-serif' => 'Tahoma',
		'"Trebuchet MS", Helvetica, sans-serif' => 'Trebuchet MS',
		'"Arial Black", Gadget, sans-serif' => 'Arial Black',
		'Impact, Charcoal, sans-serif' => 'Impact',
	);
	
	// Add Google Fonts from JSON
	$google_fonts = accepta_get_google_fonts_list();
	
	// Merge all fonts
	$fonts = array_merge( $fonts, $system_fonts, $google_fonts );
	
	return $fonts;
}

/**
 * Get Google Fonts list from JSON file
 *
 * @return array
 */
function accepta_get_google_fonts_list() {
	$fonts_file = get_template_directory() . '/inc/customizer-controls/google-fonts.json';
	
	if ( ! file_exists( $fonts_file ) ) {
		return array();
	}
	
	$fonts_json = file_get_contents( $fonts_file );
	$fonts_data = json_decode( $fonts_json, true );
	
	if ( ! isset( $fonts_data['items'] ) || ! is_array( $fonts_data['items'] ) ) {
		return array();
	}
	
	$fonts = array();
	foreach ( $fonts_data['items'] as $font ) {
		if ( isset( $font['family'] ) ) {
			$fonts[ $font['family'] ] = $font['family'];
		}
	}
	
	return $fonts;
}

/**
 * Sanitize footer columns
 */
function accepta_sanitize_footer_columns( $input ) {
	$valid_columns = array( '0', '1', '2', '3', '4' );
	return in_array( $input, $valid_columns ) ? $input : '3';
}

/**
 * Sanitize background control values
 */
function accepta_sanitize_background( $input ) {
	if ( is_string( $input ) ) {
		$input = json_decode( $input, true );
	}
	
	if ( ! is_array( $input ) ) {
		return json_encode( array(
			'type' => 'solid',
			'color' => '#2c3e50',
		) );
	}
	
	$sanitized = array();
	
	// Sanitize type
	$valid_types = array( 'solid', 'gradient', 'image', 'video' );
	$sanitized['type'] = isset( $input['type'] ) && in_array( $input['type'], $valid_types, true ) ? $input['type'] : 'solid';
	
	// Sanitize solid color
	if ( isset( $input['color'] ) ) {
		$sanitized['color'] = sanitize_hex_color( $input['color'] );
		if ( empty( $sanitized['color'] ) ) {
			$sanitized['color'] = '#2c3e50';
		}
	} else {
		$sanitized['color'] = '#2c3e50';
	}
	
	// Sanitize gradient options
	$sanitized['gradient_type'] = isset( $input['gradient_type'] ) && in_array( $input['gradient_type'], array( 'linear', 'radial' ), true ) ? $input['gradient_type'] : 'linear';
	$sanitized['gradient_angle'] = isset( $input['gradient_angle'] ) ? absint( $input['gradient_angle'] ) : 90;
	if ( $sanitized['gradient_angle'] > 360 ) {
		$sanitized['gradient_angle'] = 360;
	}
	$sanitized['gradient_start'] = isset( $input['gradient_start'] ) ? sanitize_hex_color( $input['gradient_start'] ) : '#2c3e50';
	if ( empty( $sanitized['gradient_start'] ) ) {
		$sanitized['gradient_start'] = '#2c3e50';
	}
	$sanitized['gradient_end'] = isset( $input['gradient_end'] ) ? sanitize_hex_color( $input['gradient_end'] ) : '#34495e';
	if ( empty( $sanitized['gradient_end'] ) ) {
		$sanitized['gradient_end'] = '#34495e';
	}
	
	// Sanitize image options
	$sanitized['image'] = isset( $input['image'] ) ? esc_url_raw( $input['image'] ) : '';
	$valid_sizes = array( 'auto', 'cover', 'contain', '100% 100%' );
	$sanitized['size'] = isset( $input['size'] ) && in_array( $input['size'], $valid_sizes, true ) ? $input['size'] : 'cover';
	$valid_repeats = array( 'no-repeat', 'repeat', 'repeat-x', 'repeat-y' );
	$sanitized['repeat'] = isset( $input['repeat'] ) && in_array( $input['repeat'], $valid_repeats, true ) ? $input['repeat'] : 'no-repeat';
	$valid_positions = array( 'left top', 'left center', 'left bottom', 'center top', 'center center', 'center bottom', 'right top', 'right center', 'right bottom', 'center' );
	$sanitized['position'] = isset( $input['position'] ) && in_array( $input['position'], $valid_positions, true ) ? $input['position'] : 'center';
	$valid_attachments = array( 'scroll', 'fixed', 'parallax' );
	$sanitized['attachment'] = isset( $input['attachment'] ) && in_array( $input['attachment'], $valid_attachments, true ) ? $input['attachment'] : 'scroll';
	
	// Sanitize overlay options
	$sanitized['overlay_enabled'] = isset( $input['overlay_enabled'] ) ? (bool) $input['overlay_enabled'] : false;
	$sanitized['overlay_color'] = isset( $input['overlay_color'] ) ? sanitize_hex_color( $input['overlay_color'] ) : '#000000';
	if ( empty( $sanitized['overlay_color'] ) ) {
		$sanitized['overlay_color'] = '#000000';
	}
	$overlay_opacity = isset( $input['overlay_opacity'] ) ? floatval( $input['overlay_opacity'] ) : 0.5;
	if ( $overlay_opacity < 0 ) {
		$overlay_opacity = 0;
	} elseif ( $overlay_opacity > 1 ) {
		$overlay_opacity = 1;
	}
	$sanitized['overlay_opacity'] = number_format( $overlay_opacity, 1, '.', '' );
	
	// Sanitize video options (for hero section)
	$valid_video_types = array( 'youtube', 'vimeo', 'mp4' );
	$sanitized['video_type'] = isset( $input['video_type'] ) && in_array( $input['video_type'], $valid_video_types, true ) ? $input['video_type'] : 'youtube';
	$sanitized['video_url'] = isset( $input['video_url'] ) ? esc_url_raw( $input['video_url'] ) : '';
	$sanitized['video_mp4'] = isset( $input['video_mp4'] ) ? esc_url_raw( $input['video_mp4'] ) : '';
	$sanitized['video_autoplay'] = isset( $input['video_autoplay'] ) ? (bool) $input['video_autoplay'] : true;
	$sanitized['video_loop'] = isset( $input['video_loop'] ) ? (bool) $input['video_loop'] : true;
	$sanitized['video_muted'] = isset( $input['video_muted'] ) ? (bool) $input['video_muted'] : true;
	$sanitized['video_controls'] = isset( $input['video_controls'] ) ? (bool) $input['video_controls'] : false;
	
	return json_encode( $sanitized );
}

/**
 * Sanitize spacing control values
 */
function accepta_sanitize_spacing( $input ) {
	if ( is_string( $input ) ) {
		$input = json_decode( $input, true );
	}
	
	if ( ! is_array( $input ) ) {
		return json_encode( array() );
	}
	
	$sanitized = array();
	$devices = array( 'desktop', 'tablet', 'mobile' );
	$sides = array( 'top', 'right', 'bottom', 'left' );
	$allowed_units = array( 'px', 'em', 'rem', '%', 'vh', 'vw' );
	
	foreach ( $devices as $device ) {
		if ( isset( $input[ $device ] ) && is_array( $input[ $device ] ) ) {
			$sanitized[ $device ] = array();
			
			// Sanitize spacing values
			foreach ( $sides as $side ) {
				if ( isset( $input[ $device ][ $side ] ) ) {
					$value = sanitize_text_field( $input[ $device ][ $side ] );
					$sanitized[ $device ][ $side ] = is_numeric( $value ) ? absint( $value ) : '';
				}
			}
			
			// Sanitize unit
			if ( isset( $input[ $device ]['unit'] ) ) {
				$unit = sanitize_text_field( $input[ $device ]['unit'] );
				$sanitized[ $device ]['unit'] = in_array( $unit, $allowed_units ) ? $unit : 'px';
			}
		}
	}
	
	return json_encode( $sanitized );
}

/**
 * Process dynamic tags in copyright text
 *
 * @param string $text The copyright text with tags
 * @return string Processed text with tags replaced
 */
function accepta_process_copyright_tags( $text ) {
	$replacements = array(
		'{copyright}' => '©',
		'{current-year}' => date( 'Y' ),
		'{site-title}' => get_bloginfo( 'name' ),
		'{site-url}' => '<a href="' . esc_url( home_url() ) . '">' . get_bloginfo( 'name' ) . '</a>',
		'{theme-name}' => wp_get_theme()->get( 'Name' ),
		'{theme-author}' => '<a href="' . esc_url( wp_get_theme()->get( 'AuthorURI' ) ) . '" target="_blank">' . wp_get_theme()->get( 'Author' ) . '</a>',
		'{wordpress}' => '<a href="https://wordpress.org/" target="_blank">WordPress</a>',
	);

	return str_replace( array_keys( $replacements ), array_values( $replacements ), $text );
}

/**
 * Generate dynamic CSS for footer spacing
 */
function accepta_footer_spacing_css() {
	// Get padding and margin values
	$padding_json = get_theme_mod( 'accepta_footer_padding', '' );
	$margin_json = get_theme_mod( 'accepta_footer_margin', '' );
	
	$padding = json_decode( $padding_json, true );
	$margin = json_decode( $margin_json, true );
	
	if ( ! is_array( $padding ) ) {
		$padding = array();
	}
	if ( ! is_array( $margin ) ) {
		$margin = array();
	}

	$css = '';
	
	// Desktop styles (base styles, no media query)
	$desktop_css = '';
	if ( isset( $padding['desktop'] ) && is_array( $padding['desktop'] ) ) {
		$p = $padding['desktop'];
		$unit = isset( $p['unit'] ) ? $p['unit'] : 'px';
		
		if ( isset( $p['top'] ) && $p['top'] !== '' ) {
			$desktop_css .= 'padding-top: ' . esc_attr( $p['top'] ) . $unit . ';';
		}
		if ( isset( $p['right'] ) && $p['right'] !== '' ) {
			$desktop_css .= 'padding-right: ' . esc_attr( $p['right'] ) . $unit . ';';
		}
		if ( isset( $p['bottom'] ) && $p['bottom'] !== '' ) {
			$desktop_css .= 'padding-bottom: ' . esc_attr( $p['bottom'] ) . $unit . ';';
		}
		if ( isset( $p['left'] ) && $p['left'] !== '' ) {
			$desktop_css .= 'padding-left: ' . esc_attr( $p['left'] ) . $unit . ';';
		}
	}
	
	if ( isset( $margin['desktop'] ) && is_array( $margin['desktop'] ) ) {
		$m = $margin['desktop'];
		$unit = isset( $m['unit'] ) ? $m['unit'] : 'px';
		
		if ( isset( $m['top'] ) && $m['top'] !== '' ) {
			$desktop_css .= 'margin-top: ' . esc_attr( $m['top'] ) . $unit . ';';
		}
		if ( isset( $m['right'] ) && $m['right'] !== '' ) {
			$desktop_css .= 'margin-right: ' . esc_attr( $m['right'] ) . $unit . ';';
		}
		if ( isset( $m['bottom'] ) && $m['bottom'] !== '' ) {
			$desktop_css .= 'margin-bottom: ' . esc_attr( $m['bottom'] ) . $unit . ';';
		}
		if ( isset( $m['left'] ) && $m['left'] !== '' ) {
			$desktop_css .= 'margin-left: ' . esc_attr( $m['left'] ) . $unit . ';';
		}
	}
	
	if ( ! empty( $desktop_css ) ) {
		$css .= '.site-footer {' . $desktop_css . '}';
	}
	
	// Tablet styles
	$tablet_css = '';
	if ( isset( $padding['tablet'] ) && is_array( $padding['tablet'] ) ) {
		$p = $padding['tablet'];
		$unit = isset( $p['unit'] ) ? $p['unit'] : 'px';
		
		if ( isset( $p['top'] ) && $p['top'] !== '' ) {
			$tablet_css .= 'padding-top: ' . esc_attr( $p['top'] ) . $unit . ';';
		}
		if ( isset( $p['right'] ) && $p['right'] !== '' ) {
			$tablet_css .= 'padding-right: ' . esc_attr( $p['right'] ) . $unit . ';';
		}
		if ( isset( $p['bottom'] ) && $p['bottom'] !== '' ) {
			$tablet_css .= 'padding-bottom: ' . esc_attr( $p['bottom'] ) . $unit . ';';
		}
		if ( isset( $p['left'] ) && $p['left'] !== '' ) {
			$tablet_css .= 'padding-left: ' . esc_attr( $p['left'] ) . $unit . ';';
		}
	}
	
	if ( isset( $margin['tablet'] ) && is_array( $margin['tablet'] ) ) {
		$m = $margin['tablet'];
		$unit = isset( $m['unit'] ) ? $m['unit'] : 'px';
		
		if ( isset( $m['top'] ) && $m['top'] !== '' ) {
			$tablet_css .= 'margin-top: ' . esc_attr( $m['top'] ) . $unit . ';';
		}
		if ( isset( $m['right'] ) && $m['right'] !== '' ) {
			$tablet_css .= 'margin-right: ' . esc_attr( $m['right'] ) . $unit . ';';
		}
		if ( isset( $m['bottom'] ) && $m['bottom'] !== '' ) {
			$tablet_css .= 'margin-bottom: ' . esc_attr( $m['bottom'] ) . $unit . ';';
		}
		if ( isset( $m['left'] ) && $m['left'] !== '' ) {
			$tablet_css .= 'margin-left: ' . esc_attr( $m['left'] ) . $unit . ';';
		}
	}
	
	if ( ! empty( $tablet_css ) ) {
		$css .= '@media (min-width: 600px) and (max-width: 782px) { .site-footer {' . $tablet_css . '} }';
	}
	
	// Mobile styles
	$mobile_css = '';
	if ( isset( $padding['mobile'] ) && is_array( $padding['mobile'] ) ) {
		$p = $padding['mobile'];
		$unit = isset( $p['unit'] ) ? $p['unit'] : 'px';
		
		if ( isset( $p['top'] ) && $p['top'] !== '' ) {
			$mobile_css .= 'padding-top: ' . esc_attr( $p['top'] ) . $unit . ';';
		}
		if ( isset( $p['right'] ) && $p['right'] !== '' ) {
			$mobile_css .= 'padding-right: ' . esc_attr( $p['right'] ) . $unit . ';';
		}
		if ( isset( $p['bottom'] ) && $p['bottom'] !== '' ) {
			$mobile_css .= 'padding-bottom: ' . esc_attr( $p['bottom'] ) . $unit . ';';
		}
		if ( isset( $p['left'] ) && $p['left'] !== '' ) {
			$mobile_css .= 'padding-left: ' . esc_attr( $p['left'] ) . $unit . ';';
		}
	}
	
	if ( isset( $margin['mobile'] ) && is_array( $margin['mobile'] ) ) {
		$m = $margin['mobile'];
		$unit = isset( $m['unit'] ) ? $m['unit'] : 'px';
		
		if ( isset( $m['top'] ) && $m['top'] !== '' ) {
			$mobile_css .= 'margin-top: ' . esc_attr( $m['top'] ) . $unit . ';';
		}
		if ( isset( $m['right'] ) && $m['right'] !== '' ) {
			$mobile_css .= 'margin-right: ' . esc_attr( $m['right'] ) . $unit . ';';
		}
		if ( isset( $m['bottom'] ) && $m['bottom'] !== '' ) {
			$mobile_css .= 'margin-bottom: ' . esc_attr( $m['bottom'] ) . $unit . ';';
		}
		if ( isset( $m['left'] ) && $m['left'] !== '' ) {
			$mobile_css .= 'margin-left: ' . esc_attr( $m['left'] ) . $unit . ';';
		}
	}
	
	if ( ! empty( $mobile_css ) ) {
		$css .= '@media (max-width: 599px) { .site-footer {' . $mobile_css . '} }';
	}

	return $css;
}

/**
 * Generate footer column CSS
 */
function accepta_footer_column_css() {
	$columns = get_theme_mod( 'accepta_footer_columns', '4' );
	
	$css = '';
	
	// Generate CSS based on column count
	switch ( $columns ) {
		case '0':
			$css .= '.footer-widgets { display: none; }';
			break;
		case '1':
			$css .= '.footer-widgets { display: block; }';
			$css .= '.footer-widget-area { width: 100%; margin-bottom: 30px; }';
			$css .= '.footer-widget-area:nth-child(n+2) { display: none; }';
			break;
		case '2':
			$css .= '.footer-widgets { display: grid; grid-template-columns: 1fr 1fr; gap: 30px; }';
			$css .= '.footer-widget-area:nth-child(n+3) { display: none; }';
			$css .= '@media (max-width: 599px) { .footer-widgets { grid-template-columns: 1fr; } }';
			break;
		case '3':
			$css .= '.footer-widgets { display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 30px; }';
			$css .= '.footer-widget-area:nth-child(n+4) { display: none; }';
			$css .= '@media (max-width: 599px) { .footer-widgets { grid-template-columns: 1fr; } }';
			break;
		case '4':
			$css .= '.footer-widgets { display: grid; grid-template-columns: 1fr 1fr 1fr 1fr; gap: 30px; }';
			$css .= '@media (min-width: 600px) and (max-width: 782px) { .footer-widgets { grid-template-columns: 1fr 1fr; } }';
			$css .= '@media (max-width: 599px) { .footer-widgets { grid-template-columns: 1fr; } }';
			break;
	}
	
	return $css;
}

/**
 * Generate footer styling CSS (background, text color)
 */
function accepta_footer_styling_css() {
	$css = '';
	
	// Footer background
	$footer_bg_json = get_theme_mod( 'accepta_footer_background', '' );
	$footer_bg = json_decode( $footer_bg_json, true );
	
	if ( is_array( $footer_bg ) ) {
		$bg_css = '';
		
		if ( $footer_bg['type'] === 'solid' && ! empty( $footer_bg['color'] ) ) {
			$bg_css .= 'background-color: ' . esc_attr( $footer_bg['color'] ) . ';';
			$bg_css .= 'background-image: none;';
		} elseif ( $footer_bg['type'] === 'gradient' ) {
			$gradient_type = isset( $footer_bg['gradient_type'] ) ? $footer_bg['gradient_type'] : 'linear';
			$gradient_angle = isset( $footer_bg['gradient_angle'] ) ? absint( $footer_bg['gradient_angle'] ) : 90;
			$gradient_start = isset( $footer_bg['gradient_start'] ) ? esc_attr( $footer_bg['gradient_start'] ) : '#2c3e50';
			$gradient_end = isset( $footer_bg['gradient_end'] ) ? esc_attr( $footer_bg['gradient_end'] ) : '#34495e';
			
			if ( $gradient_type === 'linear' ) {
				$bg_css .= 'background-image: linear-gradient(' . $gradient_angle . 'deg, ' . $gradient_start . ', ' . $gradient_end . ');';
			} else {
				$bg_css .= 'background-image: radial-gradient(circle, ' . $gradient_start . ', ' . $gradient_end . ');';
			}
			$bg_css .= 'background-color: transparent;';
		} elseif ( $footer_bg['type'] === 'image' && ! empty( $footer_bg['image'] ) ) {
			$bg_css .= 'background-image: url(' . esc_url( $footer_bg['image'] ) . ');';
			$bg_css .= 'background-size: ' . esc_attr( isset( $footer_bg['size'] ) ? $footer_bg['size'] : 'cover' ) . ';';
			$bg_css .= 'background-repeat: ' . esc_attr( isset( $footer_bg['repeat'] ) ? $footer_bg['repeat'] : 'no-repeat' ) . ';';
			$bg_css .= 'background-position: ' . esc_attr( isset( $footer_bg['position'] ) ? $footer_bg['position'] : 'center' ) . ';';
			$bg_css .= 'background-attachment: ' . esc_attr( isset( $footer_bg['attachment'] ) ? $footer_bg['attachment'] : 'scroll' ) . ';';
		}
		
		if ( ! empty( $bg_css ) ) {
			$css .= '.site-footer { ' . $bg_css . ' }';
		}
		
		// Overlay - only show if image is selected and overlay is enabled
		if ( isset( $footer_bg['type'] ) && $footer_bg['type'] === 'image' && isset( $footer_bg['overlay_enabled'] ) && $footer_bg['overlay_enabled'] ) {
			$overlay_color = isset( $footer_bg['overlay_color'] ) ? $footer_bg['overlay_color'] : '#000000';
			$overlay_opacity = isset( $footer_bg['overlay_opacity'] ) ? floatval( $footer_bg['overlay_opacity'] ) : 0.5;
			
			// Convert hex to rgba
			$rgb = array();
			$hex = str_replace( '#', '', $overlay_color );
			if ( strlen( $hex ) === 6 ) {
				$rgb[] = hexdec( substr( $hex, 0, 2 ) );
				$rgb[] = hexdec( substr( $hex, 2, 2 ) );
				$rgb[] = hexdec( substr( $hex, 4, 2 ) );
			} elseif ( strlen( $hex ) === 3 ) {
				$rgb[] = hexdec( $hex[0] . $hex[0] );
				$rgb[] = hexdec( $hex[1] . $hex[1] );
				$rgb[] = hexdec( $hex[2] . $hex[2] );
			}
			
			if ( ! empty( $rgb ) ) {
				$rgba = 'rgba(' . implode( ', ', $rgb ) . ', ' . $overlay_opacity . ')';
				$css .= '.site-footer::before { content: ""; position: absolute; top: 0; left: 0; right: 0; bottom: 0; background-color: ' . esc_attr( $rgba ) . '; z-index: 0; display: block; }';
				$css .= '.site-footer { position: relative; }';
				$css .= '.site-footer > * { position: relative; z-index: 1; }';
			}
		} elseif ( isset( $footer_bg['type'] ) && $footer_bg['type'] === 'image' ) {
			// On image tab but overlay is disabled - explicitly hide it
			$css .= '.site-footer::before { display: none; }';
		} elseif ( isset( $footer_bg['type'] ) && $footer_bg['type'] !== 'image' ) {
			// Not on image tab - hide overlay
			$css .= '.site-footer::before { display: none; }';
		}
	}
	
	// Footer text color
	$footer_text_color = get_theme_mod( 'accepta_footer_text_color', '#ffffff' );
	if ( ! empty( $footer_text_color ) ) {
		$css .= '.site-footer { color: ' . esc_attr( $footer_text_color ) . '; }';
		$css .= '.site-footer a { color: ' . esc_attr( $footer_text_color ) . '; }';
	}
	
	return $css;
}

/**
 * Generate sticky header CSS
 */
function accepta_sticky_header_css() {
	$sticky_header = get_theme_mod( 'accepta_sticky_header', true );
	$transparent_header = get_theme_mod( 'accepta_transparent_header', true );
	$scrolled_bg = get_theme_mod( 'accepta_scrolled_header_bg', '#ffffff' );
	$header_layout = get_theme_mod( 'accepta_header_layout', 'layout-3' );
	$header_width = get_theme_mod( 'accepta_header_width', 'boxed' );
	$css = '';
	
	// Header width CSS
	if ( $header_width === 'fullwidth' ) {
		$css .= '.site-header .container { max-width: 100%; padding-left: 20px; padding-right: 20px; }';
		$css .= '.site-header { width: 100%; }';
	} else {
		// Boxed (default) - use container max-width from layout settings, keep padding to align with footer
		$container_width = get_theme_mod( 'accepta_container_width', 1200 );
		$css .= '.site-header .container { max-width: ' . absint( $container_width ) . 'px; padding-left: 20px; padding-right: 20px; }';
		$css .= '.site-header { width: auto; }';
	}
	
	// Header layout CSS - add class to header-content
	$css .= '.header-content { position: relative; }';
	
	// Layout 1: Default - LOGO | Menu | Social | Search (space-between)
	if ( $header_layout === 'layout-1' ) {
		$css .= '.header-content.header-layout-1 { justify-content: space-between; align-items: center; width: 100%; min-width: 0; }';
		$css .= '.header-content.header-layout-1 .site-branding { order: 1; flex: 0 0 auto; min-width: 0; }';
		$css .= '.header-content.header-layout-1 .main-navigation { order: 2; margin-left: 0; margin-right: 20px; justify-content: flex-end; flex: 1 1 auto; min-width: 0; max-width: 100%; }';
		$css .= '.header-content.header-layout-1 .main-navigation ul { justify-content: flex-end; margin-left: 0; flex-wrap: wrap; min-width: 0; }';
		$css .= '.header-content.header-layout-1 .header-social-icons { order: 3; flex: 0 0 auto; min-width: 0; flex-shrink: 0; }';
		$css .= '.header-content.header-layout-1 .header-search-toggle { order: 4; margin-left: 10px; flex: 0 0 auto; flex-shrink: 0; }';
		$css .= '.header-content.header-layout-1 .header-cart-link { order: 5; margin-left: 10px; flex: 0 0 auto; flex-shrink: 0; }';
	}
	
	// Layout 2: Menu near logo - LOGO Menu | Social | Search
	if ( $header_layout === 'layout-2' ) {
		$css .= '.header-content.header-layout-2 { justify-content: space-between; align-items: center; width: 100%; min-width: 0; box-sizing: border-box; }';
		$css .= '.header-content.header-layout-2 .site-branding { order: 1; margin-right: 0; flex: 0 0 auto; min-width: 0; flex-shrink: 0; }';
		$css .= '.header-content.header-layout-2 .main-navigation { order: 1; margin-left: 20px; margin-right: 0; justify-content: flex-start; flex: 1 1 auto; min-width: 0; max-width: none; position: relative; z-index: 1; }';
		$css .= '.header-content.header-layout-2 .main-navigation ul { justify-content: flex-start; margin-left: 0; flex-wrap: wrap; min-width: 0; }';
		$css .= '.header-content.header-layout-2 .header-social-icons { order: 2; margin-left: auto; flex: 0 0 auto; min-width: 0; flex-shrink: 0; position: relative; z-index: 2; max-width: 100%; }';
		$css .= '.header-content.header-layout-2 .header-search-toggle { order: 3; margin-left: 10px; flex: 0 0 auto; flex-shrink: 0; position: relative; z-index: 3; max-width: 100%; }';
		$css .= '.header-content.header-layout-2 .header-cart-link { order: 4; margin-left: 10px; flex: 0 0 auto; flex-shrink: 0; position: relative; z-index: 3; max-width: 100%; }';
	}
	
	// Layout 3: Menu centered - LOGO | Menu (center) | Social | Search
	if ( $header_layout === 'layout-3' ) {
		$css .= '.header-content.header-layout-3 { justify-content: space-between; position: relative; align-items: center; min-height: 60px; }';
		$css .= '.header-content.header-layout-3 .site-branding { order: 1; }';
		$css .= '.header-content.header-layout-3 .main-navigation { order: 2; position: absolute; left: 50%; top: 50%; transform: translate(-50%, -50%); margin-left: 0; margin-right: 0; justify-content: center; flex: 0 0 auto; width: auto; }';
		$css .= '@media screen and (min-width: 768px) { .header-content.header-layout-3 .main-navigation { display: flex; align-items: center; justify-content: center; margin-left: 0; flex: 0 0 auto; } }';
		$css .= '.header-content.header-layout-3 .main-navigation ul { justify-content: center; margin-left: 0; }';
		$css .= '@media screen and (min-width: 768px) { .header-content.header-layout-3 .main-navigation ul { display: flex; justify-content: center; margin-left: 0; } }';
		$css .= '.header-content.header-layout-3 .header-social-icons { order: 3; margin-left: auto; }';
		$css .= '.header-content.header-layout-3 .header-search-toggle { order: 4; margin-left: 10px; }';
		$css .= '.header-content.header-layout-3:not(:has(.header-social-icons)) .header-search-toggle { margin-left: auto; }';
		$css .= '.header-content.header-layout-3 .header-cart-link { order: 5; margin-left: 10px; }';
	}
	
	if ( ! $sticky_header ) {
		// If sticky header is disabled, set position to relative and remove sticky-related styles
		$css .= '.site-header { position: relative; top: auto; }';
		//$css .= '.admin-bar .site-header { top: auto; }';
		$css .= 'body.has-sticky-header { padding-top: 0; }';
		$css .= 'body { padding-top: 0; }';
		// Remove any scrolled class effects
		$css .= '.site-header.scrolled { padding: 0.8rem 0; }';
	} elseif ( $sticky_header && ! $transparent_header ) {
		// If sticky is enabled but overlay is disabled, ensure sticky positioning works
		// Don't add top value for admin bar when not scrolled - let it be in normal flow
		$css .= '.site-header { position: sticky; top: 0; z-index: 1000; }';
	}
	
	// Scrolled header styles (when sticky is enabled, regardless of overlay)
	if ( $sticky_header ) {
		$scrolled_text_color = get_theme_mod( 'accepta_scrolled_header_text_color', '#2c3e50' );
		$scrolled_bg_opacity = get_theme_mod( 'accepta_scrolled_header_bg_opacity', '1' );
		$scrolled_bg_opacity = floatval( $scrolled_bg_opacity );
		$scrolled_bg_opacity = min( max( 0, $scrolled_bg_opacity ), 1 ); // Clamp between 0 and 1
		
		// Convert hex to rgba with opacity
		$scrolled_bg_rgba = accepta_hex_to_rgba( $scrolled_bg, $scrolled_bg_opacity );
		
		// When overlay is disabled, scrolled header should be fixed
		if ( ! $transparent_header ) {
			$css .= '.site-header.scrolled { position: fixed; top: 0; left: 0; right: 0; background-color: ' . esc_attr( $scrolled_bg_rgba ) . '; box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1); padding: 0.5rem 0; }';
			$css .= '.admin-bar .site-header.scrolled { top: var(--wp-admin--admin-bar--height, 32px); }';
			$css .= '@media screen and (max-width: 782px) { .admin-bar .site-header.scrolled { top: var(--wp-admin--admin-bar--height, 46px); } }';
		} else {
			// When overlay is enabled, scrolled styles are handled in overlay section
			$css .= '.site-header.scrolled { background-color: ' . esc_attr( $scrolled_bg_rgba ) . '; box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1); padding: 0.5rem 0; }';
		}
		
		$css .= '.site-header.scrolled .site-title a { color: ' . esc_attr( $scrolled_text_color ) . '; }';
		$css .= '.site-header.scrolled .site-description { color: ' . esc_attr( $scrolled_text_color ) . '; opacity: 0.7; }';
		$css .= '.site-header.scrolled .main-navigation a { color: ' . esc_attr( $scrolled_text_color ) . '; }';
		$css .= '.site-header.scrolled .menu-toggle { color: ' . esc_attr( $scrolled_text_color ) . '; }';
		$css .= '.site-header.scrolled .menu-toggle .icon-bar { background-color: ' . esc_attr( $scrolled_text_color ) . '; }';
	}
	
	// Overlay header styles (only on pages that have the hero, e.g. front page with hero enabled)
	if ( $transparent_header ) {
		$transparent_text_color = get_theme_mod( 'accepta_transparent_header_text_color', '#ffffff' );
		$scrolled_text_color = get_theme_mod( 'accepta_scrolled_header_text_color', '#2c3e50' );
		// Convert text color to rgba with 0.3 opacity for borders
		$transparent_border_color = accepta_hex_to_rgba( $transparent_text_color, 0.3 );
		$overlay_prefix = 'body.accepta-has-hero ';

		// On hero pages: make header absolute to overlay hero section
		$css .= $overlay_prefix . '.site-header { position: absolute; top: 0; left: 0; right: 0; background-color: transparent; box-shadow: none; z-index: 1001; }';
		$css .= $overlay_prefix . '.admin-bar .site-header { top: var(--wp-admin--admin-bar--height, 32px); }';
		$css .= '@media screen and (max-width: 782px) { ' . $overlay_prefix . '.admin-bar .site-header { top: var(--wp-admin--admin-bar--height, 46px); } }';

		// When scrolled on hero page, make it fixed with background (only if sticky is also enabled)
		if ( $sticky_header ) {
			$scrolled_bg_opacity = get_theme_mod( 'accepta_scrolled_header_bg_opacity', '1' );
			$scrolled_bg_opacity = floatval( $scrolled_bg_opacity );
			$scrolled_bg_opacity = min( max( 0, $scrolled_bg_opacity ), 1 ); // Clamp between 0 and 1
			$scrolled_bg_rgba = accepta_hex_to_rgba( $scrolled_bg, $scrolled_bg_opacity );

			$css .= $overlay_prefix . '.site-header.scrolled { position: fixed; background-color: ' . esc_attr( $scrolled_bg_rgba ) . '; box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1); }';
			$css .= $overlay_prefix . '.admin-bar .site-header.scrolled { top: var(--wp-admin--admin-bar--height, 32px); }';
			$css .= '@media screen and (max-width: 782px) { ' . $overlay_prefix . '.admin-bar .site-header.scrolled { top: var(--wp-admin--admin-bar--height, 46px); } }';
		}

		// Overlay header text colors (when not scrolled, on hero page only)
		// Nav link colors only on desktop so mobile dropdown keeps dark text on white panel.
		$css .= $overlay_prefix . '.site-header:not(.scrolled) .site-title a { color: ' . esc_attr( $transparent_text_color ) . '; }';
		$css .= $overlay_prefix . '.site-header:not(.scrolled) .site-description { color: ' . esc_attr( $transparent_text_color ) . '; opacity: 0.8; }';
		$css .= '@media screen and (min-width: 768px) { ';
		$css .= $overlay_prefix . '.site-header:not(.scrolled) .main-navigation > ul > li > a, ';
		$css .= $overlay_prefix . '.site-header:not(.scrolled) .main-navigation .nav-menu > li > a { color: ' . esc_attr( $transparent_text_color ) . ' !important; }';
		$css .= $overlay_prefix . '.site-header:not(.scrolled) .main-navigation ul ul a { color: #000 !important; }';
		$css .= $overlay_prefix . '.site-header:not(.scrolled) .main-navigation ul ul a:hover { color: #6F9C50; }';
		$css .= ' }';
		$css .= $overlay_prefix . '.site-header:not(.scrolled) .menu-toggle { color: ' . esc_attr( $transparent_text_color ) . '; }';
		$css .= $overlay_prefix . '.site-header:not(.scrolled) .menu-toggle .icon-bar { background-color: ' . esc_attr( $transparent_text_color ) . '; }';
		$css .= $overlay_prefix . '.site-header:not(.scrolled) .header-social-icons .social-icon { color: ' . esc_attr( $transparent_text_color ) . '; }';
		$css .= $overlay_prefix . '.site-header:not(.scrolled) .header-search-toggle { color: ' . esc_attr( $transparent_text_color ) . '; }';
		$css .= $overlay_prefix . '.site-header:not(.scrolled) .header-search-toggle svg { color: ' . esc_attr( $transparent_text_color ) . '; stroke: ' . esc_attr( $transparent_text_color ) . '; }';
		$css .= $overlay_prefix . '.site-header:not(.scrolled) .header-cart-link { color: ' . esc_attr( $transparent_text_color ) . '; }';
		$css .= $overlay_prefix . '.site-header:not(.scrolled) .header-cart-link svg { color: ' . esc_attr( $transparent_text_color ) . '; stroke: ' . esc_attr( $transparent_text_color ) . '; }';
		$css .= $overlay_prefix . '.site-header:not(.scrolled) .header-search-close { color: ' . esc_attr( $transparent_text_color ) . '; }';
		$css .= $overlay_prefix . '.site-header:not(.scrolled) .header-social-icons .social-icon { border-color: ' . esc_attr( $transparent_border_color ) . '; }';
		$css .= $overlay_prefix . '.site-header:not(.scrolled) .header-social-icons .social-icon .social-icon-svg { filter: brightness(0) invert(1); }';
		$css .= $overlay_prefix . '.site-header:not(.scrolled) .header-search-toggle { border-color: ' . esc_attr( $transparent_border_color ) . '; }';
		$css .= $overlay_prefix . '.site-header:not(.scrolled) .header-cart-link { border-color: ' . esc_attr( $transparent_border_color ) . '; }';
		$css .= $overlay_prefix . '.site-header:not(.scrolled) .custom-logo-link img { filter: brightness(0) invert(1); }';
		$css .= $overlay_prefix . '.site-header.scrolled .custom-logo-link img { filter: none; }';

		// On hero page only: remove top padding and let hero sit under header
		$css .= 'body.accepta-has-hero:not(.has-sticky-header) { padding-top: 0; }';
		$css .= 'body.accepta-has-hero .accepta-hero-section { margin-top: 0; padding-top: 0; }';
		$css .= 'body.accepta-has-hero .site-content { margin-top: 0; }';

		// When overlay is enabled but we're NOT on a hero page: use normal header (sticky or relative) so content isn't under the header
		if ( $sticky_header ) {
			$css .= 'body:not(.accepta-has-hero) .site-header { position: sticky; top: 0; z-index: 1000; }';
			// On non-hero pages, scrolled header should still be fixed with background
			$scrolled_bg_opacity = get_theme_mod( 'accepta_scrolled_header_bg_opacity', '1' );
			$scrolled_bg_opacity = floatval( $scrolled_bg_opacity );
			$scrolled_bg_opacity = min( max( 0, $scrolled_bg_opacity ), 1 );
			$scrolled_bg_rgba = accepta_hex_to_rgba( $scrolled_bg, $scrolled_bg_opacity );
			$css .= 'body:not(.accepta-has-hero) .site-header.scrolled { position: fixed; top: 0; left: 0; right: 0; background-color: ' . esc_attr( $scrolled_bg_rgba ) . '; box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1); }';
			$css .= 'body:not(.accepta-has-hero).admin-bar .site-header.scrolled { top: var(--wp-admin--admin-bar--height, 32px); }';
			$css .= '@media screen and (max-width: 782px) { body:not(.accepta-has-hero).admin-bar .site-header.scrolled { top: var(--wp-admin--admin-bar--height, 46px); } }';
		} else {
			$css .= 'body:not(.accepta-has-hero) .site-header { position: relative; top: auto; }';
		}
	} else {
		// When overlay is disabled, ensure dark colors for social icons and search button
		$css .= '.site-header:not(.transparent-header) .header-social-icons .social-icon { color: #2c3e50; border-color: rgba(44, 62, 80, 0.2); }';
		$css .= '.site-header:not(.transparent-header) .header-social-icons .social-icon .social-icon-svg { filter: none; }';
		$css .= '.site-header:not(.transparent-header) .header-search-toggle { color: #2c3e50; border-color: rgba(44, 62, 80, 0.2); }';
		$css .= '.site-header:not(.transparent-header) .header-search-toggle svg { color: #2c3e50; stroke: #2c3e50; }';
		$css .= '.site-header:not(.transparent-header) .header-cart-link { color: #2c3e50; border-color: rgba(44, 62, 80, 0.2); }';
		$css .= '.site-header:not(.transparent-header) .header-cart-link svg { color: #2c3e50; stroke: #2c3e50; }';
	}
	
	if ( $sticky_header && ! $transparent_header ) {
		// Scrolled header styles when sticky is enabled but overlay is disabled
		$scrolled_text_color = get_theme_mod( 'accepta_scrolled_header_text_color', '#2c3e50' );
		$scrolled_bg_opacity = get_theme_mod( 'accepta_scrolled_header_bg_opacity', '1' );
		$scrolled_bg_opacity = floatval( $scrolled_bg_opacity );
		$scrolled_bg_opacity = min( max( 0, $scrolled_bg_opacity ), 1 ); // Clamp between 0 and 1
		$scrolled_bg_rgba = accepta_hex_to_rgba( $scrolled_bg, $scrolled_bg_opacity );
		
		$css .= '.site-header.scrolled { background-color: ' . esc_attr( $scrolled_bg_rgba ) . '; box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1); }';
		$css .= '.site-header.scrolled .site-title a { color: ' . esc_attr( $scrolled_text_color ) . '; }';
		$css .= '.site-header.scrolled .site-description { color: ' . esc_attr( $scrolled_text_color ) . '; opacity: 0.7; }';
		$css .= '.site-header.scrolled .main-navigation a { color: ' . esc_attr( $scrolled_text_color ) . '; }';
		$css .= '.site-header.scrolled .menu-toggle { color: ' . esc_attr( $scrolled_text_color ) . '; }';
		$css .= '.site-header.scrolled .menu-toggle .icon-bar { background-color: ' . esc_attr( $scrolled_text_color ) . '; }';
	}
	
	return $css;
}

/**
 * Generate hero section CSS
 */
function accepta_hero_section_css() {
	$css = '';
	$hero_enabled = get_theme_mod( 'accepta_hero_enabled', true );
	
	if ( ! $hero_enabled ) {
		return $css;
	}
	
	// Width settings
	$hero_width = get_theme_mod( 'accepta_hero_width', 'boxed' );
	$container_width = get_theme_mod( 'accepta_container_width', 1200 );
	
	if ( $hero_width === 'fullwidth' ) {
		// Full width - extends to viewport edges
		$css .= '.accepta-hero-section.accepta-hero-fullwidth { width: 100vw; max-width: 100vw; margin-left: calc(-50vw + 50%); position: relative; left: 0; }';
		$css .= '.accepta-hero-section.accepta-hero-fullwidth .accepta-hero-content-wrapper { width: 100%; }';
	} else {
		// Boxed - constrains hero and background to container width, centered
		$css .= '.accepta-hero-section.accepta-hero-boxed { width: 100%; max-width: ' . absint( $container_width ) . 'px; margin-left: auto; margin-right: auto; }';
	}
	
	// Height settings
	$hero_height = get_theme_mod( 'accepta_hero_height', 'min-height' );
	$hero_min_height = get_theme_mod( 'accepta_hero_min_height', 500 );
	
	if ( $hero_height === 'fullscreen' ) {
		$css .= '.accepta-hero-section.accepta-hero-fullscreen { min-height: 100vh; }';
	} elseif ( $hero_height === 'min-height' ) {
		$css .= '.accepta-hero-section.accepta-hero-min-height { min-height: ' . absint( $hero_min_height ) . 'px; }';
	} elseif ( $hero_height === 'custom' ) {
		$css .= '.accepta-hero-section.accepta-hero-custom-height { min-height: ' . absint( $hero_min_height ) . 'px; }';
	}
	
	// Content Alignment - Horizontal (responsive)
	$align_horizontal = get_theme_mod( 'accepta_hero_align_horizontal', json_encode( array( 'desktop' => 'center', 'tablet' => 'center', 'mobile' => 'center' ) ) );
	$align_h = json_decode( $align_horizontal, true );
	if ( ! is_array( $align_h ) ) {
		$align_h = array( 'desktop' => 'center', 'tablet' => 'center', 'mobile' => 'center' );
	}
	
	// Content Alignment - Vertical (responsive)
	$align_vertical = get_theme_mod( 'accepta_hero_align_vertical', json_encode( array( 'desktop' => 'center', 'tablet' => 'center', 'mobile' => 'center' ) ) );
	$align_v = json_decode( $align_vertical, true );
	if ( ! is_array( $align_v ) ) {
		$align_v = array( 'desktop' => 'center', 'tablet' => 'center', 'mobile' => 'center' );
	}
	
	// Hero content wrapper - make it a flex container for vertical alignment
	$css .= '.accepta-hero-section { display: flex; flex-direction: column; }';
	$css .= '.accepta-hero-content-wrapper { display: flex; flex-direction: column; flex: 1; min-height: 100%; }';
	$css .= '.accepta-hero-content-wrapper .container { display: flex; flex-direction: column; flex: 1; }';
	$css .= '.accepta-hero-content { display: flex; flex-direction: column; }';
	
	// Desktop
	$css .= '.accepta-hero-content-wrapper .container { justify-content: ' . esc_attr( $align_v['desktop'] ) . '; }';
	$css .= '.accepta-hero-content { align-items: ' . esc_attr( $align_h['desktop'] ) . '; }';
	
	// Tablet
	$css .= '@media (min-width: 600px) and (max-width: 782px) {';
	$css .= '.accepta-hero-content-wrapper .container { justify-content: ' . esc_attr( $align_v['tablet'] ) . '; }';
	$css .= '.accepta-hero-content { align-items: ' . esc_attr( $align_h['tablet'] ) . '; }';
	$css .= '}';
	
	// Mobile
	$css .= '@media (max-width: 599px) {';
	$css .= '.accepta-hero-content-wrapper .container { justify-content: ' . esc_attr( $align_v['mobile'] ) . '; }';
	$css .= '.accepta-hero-content { align-items: ' . esc_attr( $align_h['mobile'] ) . '; }';
	$css .= '}';
	
	// Get hero background settings from the unified control
	$hero_background_json = get_theme_mod( 'accepta_hero_background', '' );
	$hero_background = json_decode( $hero_background_json, true );
	
	// Default values (image with overlay when nothing saved)
	if ( ! is_array( $hero_background ) ) {
		$hero_background = array(
			'type'           => 'image',
			'color'          => '#6F9C50',
			'gradient_type'  => 'linear',
			'gradient_angle' => '90',
			'gradient_start' => '#6F9C50',
			'gradient_end'   => '#568F0C',
			'image'          => get_template_directory_uri() . '/assets/images/accepta-hero-bg.jpg',
			'size'           => 'cover',
			'repeat'         => 'no-repeat',
			'position'       => 'center',
			'attachment'     => 'parallax',
		);
	}
	
	// Extract background type and values
	$bg_type = isset( $hero_background['type'] ) ? $hero_background['type'] : 'solid';
	
	// Apply background styles based on type
	if ( $bg_type === 'solid' ) {
		$bg_color = isset( $hero_background['color'] ) ? $hero_background['color'] : '#6F9C50';
		if ( ! empty( $bg_color ) ) {
			$css .= '.accepta-hero-section { background-color: ' . esc_attr( $bg_color ) . '; }';
		}
	} elseif ( $bg_type === 'gradient' ) {
		$gradient_type = isset( $hero_background['gradient_type'] ) ? $hero_background['gradient_type'] : 'linear';
		$gradient_angle = isset( $hero_background['gradient_angle'] ) ? absint( $hero_background['gradient_angle'] ) : 90;
		$gradient_start = isset( $hero_background['gradient_start'] ) ? esc_attr( $hero_background['gradient_start'] ) : '#6F9C50';
		$gradient_end = isset( $hero_background['gradient_end'] ) ? esc_attr( $hero_background['gradient_end'] ) : '#568F0C';
		
		if ( $gradient_type === 'linear' ) {
			$css .= '.accepta-hero-section { background-image: linear-gradient(' . $gradient_angle . 'deg, ' . $gradient_start . ', ' . $gradient_end . '); }';
		} else {
			$css .= '.accepta-hero-section { background-image: radial-gradient(circle, ' . $gradient_start . ', ' . $gradient_end . '); }';
		}
	} elseif ( $bg_type === 'image' ) {
		$bg_image = isset( $hero_background['image'] ) ? $hero_background['image'] : '';
		$bg_attachment = isset( $hero_background['attachment'] ) ? $hero_background['attachment'] : 'scroll';
		if ( ! empty( $bg_image ) ) {
			$bg_size = isset( $hero_background['size'] ) ? esc_attr( $hero_background['size'] ) : 'cover';
			$bg_repeat = isset( $hero_background['repeat'] ) ? esc_attr( $hero_background['repeat'] ) : 'no-repeat';
			$bg_position = isset( $hero_background['position'] ) ? esc_attr( $hero_background['position'] ) : 'center';
			if ( $bg_attachment === 'parallax' ) {
				// Parallax: background is on .accepta-hero-parallax-bg (template), add structure CSS only
				$css .= '.accepta-hero-section.accepta-hero-has-parallax { position: relative; overflow: hidden; }';
				$css .= '.accepta-hero-section .accepta-hero-parallax-bg { position: absolute; top: -20%; left: 0; right: 0; height: 140%; z-index: 0; will-change: transform; }';
				$css .= '.accepta-hero-section.accepta-hero-has-parallax .accepta-hero-overlay { position: absolute; top: 0; left: 0; width: 100%; height: 100%; z-index: 1; }';
				$css .= '.accepta-hero-section .accepta-hero-content-wrapper { position: relative; z-index: 2; }';
			} else {
				// Scroll or fixed: apply background directly to section
				$css .= '.accepta-hero-section { background-image: url(' . esc_url( $bg_image ) . '); background-size: ' . $bg_size . '; background-repeat: ' . $bg_repeat . '; background-position: ' . $bg_position . '; background-attachment: ' . esc_attr( $bg_attachment ) . '; }';
			}
		}
	}
	// Note: Video backgrounds are handled via inline styles in the template
	
	return $css;
}

/**
 * Output dynamic CSS in head
 */
function accepta_footer_styles() {
	$spacing_css = accepta_footer_spacing_css();
	$column_css = accepta_footer_column_css();
	$footer_styling_css = accepta_footer_styling_css();
	$layout_css = accepta_layout_css();
	$colors_css = accepta_global_colors_css();
	$typography_css = accepta_typography_css();
	$sticky_header_css = accepta_sticky_header_css();
	$hero_section_css = accepta_hero_section_css();
	
	$css = $colors_css . $typography_css . $spacing_css . $column_css . $footer_styling_css . $layout_css . $sticky_header_css . $hero_section_css;
	
	if ( ! empty( $css ) ) {
		echo '<style type="text/css" id="accepta-dynamic-css">' . $css . '</style>';
	}
}
add_action( 'wp_head', 'accepta_footer_styles' );

/**
 * Render the site title for the selective refresh partial.
 *
 * @return void
 */
function accepta_customize_partial_blogname() {
	bloginfo( 'name' );
}

/**
 * Render the site tagline for the selective refresh partial.
 *
 * @return void
 */
function accepta_customize_partial_blogdescription() {
	bloginfo( 'description' );
}

/**
 * Binds JS handlers to make Theme Customizer preview reload changes asynchronously.
 */
function accepta_customize_preview_js() {
	wp_enqueue_script( 
		'accepta-customizer', 
		get_template_directory_uri() . '/assets/js/customizer.js', 
		array( 'customize-preview' ),
		_ACCEPTA_VERSION, 
		true 
	);
}
add_action( 'customize_preview_init', 'accepta_customize_preview_js' );

/**
 * Generate dynamic CSS for global colors
 */
function accepta_global_colors_css() {
	$css = '';
	
	// Get color values
	$primary_color = get_theme_mod( 'accepta_primary_color', '#0073aa' );
	$background_color = get_theme_mod( 'accepta_background_color', '#ffffff' );
	$text_color = get_theme_mod( 'accepta_text_color', '#333333' );
	$link_color = get_theme_mod( 'accepta_link_color', '#0073aa' );
	$link_hover_color = get_theme_mod( 'accepta_link_hover_color', '#005a87' );
	
	// Primary Color Applications
	if ( $primary_color && $primary_color !== '#0073aa' ) {
		$css .= ':root { --accepta-primary-color: ' . esc_attr( $primary_color ) . '; }';
		$css .= 'button, .button, input[type="button"], input[type="reset"], input[type="submit"] { background-color: ' . esc_attr( $primary_color ) . '; }';
		$css .= '.site-title a:hover, .site-title a:focus { color: ' . esc_attr( $primary_color ) . '; }';
		$css .= '.main-navigation a:hover, .main-navigation a:focus { color: ' . esc_attr( $primary_color ) . '; }';
		$css .= '.entry-title a:hover, .entry-title a:focus { color: ' . esc_attr( $primary_color ) . '; }';
		$css .= '.widget-title { border-left-color: ' . esc_attr( $primary_color ) . '; }';
		$css .= '.social-icon:hover { background-color: ' . esc_attr( $primary_color ) . '; }';
		$css .= 'blockquote { border-left-color: ' . esc_attr( $primary_color ) . '; }';
		$css .= '.page-numbers.current, .page-numbers:hover { background-color: ' . esc_attr( $primary_color ) . '; }';
	}
	
	// Background Color Applications
	if ( $background_color && $background_color !== '#ffffff' ) {
		$css .= ':root { --accepta-background-color: ' . esc_attr( $background_color ) . '; }';
		$css .= 'body, .site { background-color: ' . esc_attr( $background_color ) . '; }';
		$css .= '.site-main, .widget-area { background-color: ' . esc_attr( $background_color ) . '; }';
		$css .= '.entry-content, .entry-summary { background-color: ' . esc_attr( $background_color ) . '; }';
		$css .= 'input[type="text"], input[type="email"], input[type="url"], input[type="password"], input[type="search"], input[type="number"], input[type="tel"], input[type="range"], input[type="date"], input[type="month"], input[type="week"], input[type="time"], input[type="datetime"], input[type="datetime-local"], input[type="color"], textarea, select { background-color: ' . esc_attr( $background_color ) . '; }';
	}
	
	// Text Color Applications
	if ( $text_color && $text_color !== '#333333' ) {
		$css .= ':root { --accepta-text-color: ' . esc_attr( $text_color ) . '; }';
		$css .= 'body, p, .entry-content, .entry-summary, .widget { color: ' . esc_attr( $text_color ) . '; }';
		$css .= 'h1, h2, h3, h4, h5, h6 { color: ' . esc_attr( $text_color ) . '; }';
		$css .= '.site-title a { color: ' . esc_attr( $text_color ) . '; }';
		$css .= '.main-navigation a { color: ' . esc_attr( $text_color ) . '; }';
		$css .= '.entry-title a { color: ' . esc_attr( $text_color ) . '; }';
		$css .= '.widget-title { color: ' . esc_attr( $text_color ) . '; }';
		$css .= '.entry-meta, .entry-meta a { color: ' . esc_attr( $text_color ) . '; }';
	}
	
	// Link Color Applications
	if ( $link_color && $link_color !== '#0073aa' ) {
		$css .= ':root { --accepta-link-color: ' . esc_attr( $link_color ) . '; }';
		$css .= 'a { color: ' . esc_attr( $link_color ) . '; }';
		$css .= '.entry-content a, .entry-summary a, .widget a { color: ' . esc_attr( $link_color ) . '; }';
		$css .= '.comment-content a { color: ' . esc_attr( $link_color ) . '; }';
	}
	
	// Link Hover Color Applications
	if ( $link_hover_color && $link_hover_color !== '#005a87' ) {
		$css .= ':root { --accepta-link-hover-color: ' . esc_attr( $link_hover_color ) . '; }';
		$css .= 'a:hover, a:focus { color: ' . esc_attr( $link_hover_color ) . '; }';
		$css .= '.entry-content a:hover, .entry-content a:focus, .entry-summary a:hover, .entry-summary a:focus, .widget a:hover, .widget a:focus { color: ' . esc_attr( $link_hover_color ) . '; }';
		$css .= '.comment-content a:hover, .comment-content a:focus { color: ' . esc_attr( $link_hover_color ) . '; }';
		$css .= 'button:hover, .button:hover, input[type="button"]:hover, input[type="reset"]:hover, input[type="submit"]:hover { background-color: ' . esc_attr( $link_hover_color ) . '; }';
	}
	
	return $css;
}

/**
 * Generate dynamic CSS for layout settings
 */
function accepta_layout_css() {
	$css = '';
	
	// Container width - apply to all container elements for consistent alignment
	$container_width = get_theme_mod( 'accepta_container_width', 1200 );
	if ( $container_width ) {
		$css .= '.container, .site-content { max-width: ' . absint( $container_width ) . 'px; }';
		$css .= '.site-header .site-header-container, .site-footer .site-header-container { max-width: ' . absint( $container_width ) . 'px; }';
		$css .= '.site-header .site-info, .site-footer .site-info { max-width: ' . absint( $container_width ) . 'px; }';
		$css .= '.content-sidebar-wrap { max-width: ' . absint( $container_width ) . 'px; }';
	}
	
	// Sidebar layout
	$sidebar_layout = get_theme_mod( 'accepta_sidebar_layout', 'none' );

	// Full-width page template (no sidebar).
	$css .= 'body.accepta-page-template-full-width .content-sidebar-wrap,';
	$css .= 'body.accepta-page-template-full-width .content-sidebar-wrap--no-sidebar { display: block; }';
	$css .= 'body.accepta-page-template-full-width .content-sidebar-wrap .site-main,';
	$css .= 'body.accepta-page-template-full-width .content-sidebar-wrap--no-sidebar .site-main { width: 100%; max-width: 100%; }';
	$css .= 'body.accepta-page-template-full-width .content-sidebar-wrap .widget-area { display: none; }';

	switch ( $sidebar_layout ) {
		case 'none':
			$css .= '.content-sidebar-wrap { display: block; }';
			$css .= '.site-main { width: 100%; max-width: 100%; }';
			$css .= '.widget-area { display: none; }';
			break;
		case 'left':
			$css .= '.content-sidebar-wrap { display: grid; grid-template-columns: 300px 1fr; gap: 30px; flex-wrap: nowrap; }';
			$css .= '.content-sidebar-wrap .site-main { order: 2; grid-column: 2; }';
			$css .= '.content-sidebar-wrap .widget-area { order: 1; grid-column: 1; width: 300px; }';
			break;
		case 'right':
		default:
			$css .= '.content-sidebar-wrap { display: grid; grid-template-columns: 1fr 300px; gap: 30px; flex-wrap: nowrap; }';
			$css .= '.content-sidebar-wrap .site-main { order: 1; grid-column: 1; }';
			$css .= '.content-sidebar-wrap .widget-area { order: 2; grid-column: 2; width: 300px; }';
			break;
	}

	// Content Box Shadow (uses body class for full coverage including front page)
	$box_shadow_option = get_theme_mod( 'accepta_content_box_shadow', 'default' );
	$article_sel = 'body:not(.accepta-page-template-full-width) .site-main > article:not(.sticky)';
	switch ( $box_shadow_option ) {
		case 'default':
			// Always show box shadow on articles (all pages including front page)
			$css .= $article_sel . ' { box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1); background: #fff; border-radius: 6px; }';
			break;
		case 'only-with-sidebar':
			// Only show box shadow when sidebar layout is left or right
			if ( $sidebar_layout === 'left' || $sidebar_layout === 'right' ) {
				$css .= $article_sel . ' { box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1); background: #fff; border-radius: 6px; padding: 1.75em 2em; }';
			} else {
				$css .= 'body.accepta-content-box-shadow-only-with-sidebar .site-main > article:not(.sticky) { box-shadow: none !important; background: transparent !important; border-radius: 0 !important; padding: 1.75em 0 !important; }';
			}
			break;
		case 'none':
			// Never show box shadow – remove left/right padding
			$css .= 'body.accepta-content-box-shadow-none .site-main > article:not(.sticky) { box-shadow: none !important; background: transparent !important; border-radius: 0 !important; padding: 1.75em 0 !important; }';
			break;
	}

	// WooCommerce and any template using content-sidebar-wrap--no-sidebar: full width, no sidebar.
	$css .= '.content-sidebar-wrap--no-sidebar { display: block; }';
	$css .= '.content-sidebar-wrap--no-sidebar .site-main { width: 100%; max-width: 100%; }';
	$css .= '.content-sidebar-wrap--no-sidebar .widget-area { display: none; }';

	// Content padding
	$padding_json = get_theme_mod( 'accepta_content_padding', '' );
	$padding = json_decode( $padding_json, true );
	
	if ( is_array( $padding ) ) {
		$breakpoints = array(
			'desktop' => '@media (min-width: 783px)',
			'tablet'  => '@media (min-width: 600px) and (max-width: 782px)',
			'mobile'  => '@media (max-width: 599px)'
		);

		foreach ( $breakpoints as $device => $media_query ) {
			if ( isset( $padding[ $device ] ) && is_array( $padding[ $device ] ) ) {
				$device_css = '';
				$p = $padding[ $device ];
				$unit = isset( $p['unit'] ) ? $p['unit'] : 'px';
				
				foreach ( array( 'top', 'right', 'bottom', 'left' ) as $side ) {
					if ( ! empty( $p[ $side ] ) ) {
						$device_css .= 'padding-' . $side . ': ' . esc_attr( $p[ $side ] ) . $unit . ';';
					}
				}
				
				if ( ! empty( $device_css ) ) {
					$css .= $media_query . ' { .site-main { ' . $device_css . ' } }';
				}
			}
		}
	}
	
	// Content margin
	$margin_json = get_theme_mod( 'accepta_content_margin', '' );
	$margin = json_decode( $margin_json, true );
	
	if ( is_array( $margin ) ) {
		$breakpoints = array(
			'desktop' => '@media (min-width: 783px)',
			'tablet'  => '@media (min-width: 600px) and (max-width: 782px)',
			'mobile'  => '@media (max-width: 599px)'
		);

		foreach ( $breakpoints as $device => $media_query ) {
			if ( isset( $margin[ $device ] ) && is_array( $margin[ $device ] ) ) {
				$device_css = '';
				$m = $margin[ $device ];
				$unit = isset( $m['unit'] ) ? $m['unit'] : 'px';
				
				foreach ( array( 'top', 'right', 'bottom', 'left' ) as $side ) {
					if ( ! empty( $m[ $side ] ) ) {
						$device_css .= 'margin-' . $side . ': ' . esc_attr( $m[ $side ] ) . $unit . ';';
					}
				}
				
				if ( ! empty( $device_css ) ) {
					$css .= $media_query . ' { .site-main { ' . $device_css . ' } }';
				}
			}
		}
	}
	
	return $css;
}

/**
 * Generate typography CSS
 *
 * @return string
 */
/**
 * Helper function to generate proper font-family CSS
 */
function accepta_generate_font_family_css( $font_family ) {
	if ( empty( $font_family ) ) {
		return '';
	}
	
	// Check if font family already contains fallbacks (has commas)
	if ( strpos( $font_family, ',' ) !== false ) {
		// Font family already has fallbacks, use as-is but ensure proper escaping
		return 'font-family: ' . wp_strip_all_tags( $font_family ) . ';';
	} else {
		// Single font name, add fallback
		return 'font-family: "' . esc_attr( $font_family ) . '", sans-serif;';
	}
}

/**
 * Helper function to generate responsive font-size CSS
 */
function accepta_generate_responsive_font_size_css( $typography_data, $selector ) {
	if ( ! is_array( $typography_data ) ) {
		return '';
	}
	
	$css = '';
	
	// Desktop font size (default) - check if value exists and is not empty string
	if ( isset( $typography_data['font_size_desktop'] ) && $typography_data['font_size_desktop'] !== '' && $typography_data['font_size_desktop'] !== null ) {
		$desktop_size = absint( $typography_data['font_size_desktop'] );
		if ( $desktop_size > 0 ) {
			$css .= $selector . ' { font-size: ' . $desktop_size . 'px; }' . "\n";
		}
	} elseif ( isset( $typography_data['font_size'] ) && $typography_data['font_size'] !== '' && $typography_data['font_size'] !== null ) {
		// Fallback to legacy font_size
		$legacy_size = absint( $typography_data['font_size'] );
		if ( $legacy_size > 0 ) {
			$css .= $selector . ' { font-size: ' . $legacy_size . 'px; }' . "\n";
		}
	}
	
	// Tablet font size
	if ( isset( $typography_data['font_size_tablet'] ) && $typography_data['font_size_tablet'] !== '' && $typography_data['font_size_tablet'] !== null ) {
		$tablet_size = absint( $typography_data['font_size_tablet'] );
		if ( $tablet_size > 0 ) {
			$css .= '@media (max-width: 782px) { ' . $selector . ' { font-size: ' . $tablet_size . 'px; } }' . "\n";
		}
	}
	
	// Mobile font size
	if ( isset( $typography_data['font_size_mobile'] ) && $typography_data['font_size_mobile'] !== '' && $typography_data['font_size_mobile'] !== null ) {
		$mobile_size = absint( $typography_data['font_size_mobile'] );
		if ( $mobile_size > 0 ) {
			$css .= '@media (max-width: 600px) { ' . $selector . ' { font-size: ' . $mobile_size . 'px; } }' . "\n";
		}
	}
	
	return $css;
}

function accepta_typography_css() {
	$css = "";
	
	// Body Typography
	$body_typography = get_theme_mod( "accepta_body_typography", "" );
	if ( $body_typography ) {
		$body_typography = json_decode( $body_typography, true );
		if ( is_array( $body_typography ) ) {
			$body_css = "";
			
			if ( ! empty( $body_typography["font_family"] ) ) {
				$body_css .= accepta_generate_font_family_css( $body_typography["font_family"] );
			}
			// Handle responsive font sizes separately
			$css .= accepta_generate_responsive_font_size_css( $body_typography, "body, p, .entry-content, .entry-summary, .widget" );
			if ( ! empty( $body_typography["font_weight"] ) ) {
				$body_css .= "font-weight: " . esc_attr( $body_typography["font_weight"] ) . ";";
			}
			if ( ! empty( $body_typography["line_height"] ) ) {
				$body_css .= "line-height: " . esc_attr( $body_typography["line_height"] ) . ";";
			}
			if ( ! empty( $body_typography["letter_spacing"] ) ) {
				$body_css .= "letter-spacing: " . esc_attr( $body_typography["letter_spacing"] ) . "px;";
			}
			if ( ! empty( $body_typography["text_transform"] ) ) {
				$body_css .= "text-transform: " . esc_attr( $body_typography["text_transform"] ) . ";";
			}
			
			if ( ! empty( $body_css ) ) {
				$css .= "body, p, .entry-content, .entry-summary, .widget { " . $body_css . " }";
			}
		}
	}
	
	// All Headings Typography (Default)
	$all_headings_typography = get_theme_mod( 'accepta_all_headings_typography', '' );
	$all_headings_css = '';
	
	if ( ! empty( $all_headings_typography ) ) {
		$all_headings_data = json_decode( $all_headings_typography, true );
		if ( is_array( $all_headings_data ) ) {
			if ( ! empty( $all_headings_data['font_family'] ) ) {
				$all_headings_css .= accepta_generate_font_family_css( $all_headings_data['font_family'] );
			}
			// Handle responsive font sizes separately
			$css .= accepta_generate_responsive_font_size_css( $all_headings_data, 'h1, h2, h3, h4, h5, h6, .entry-title' );
			if ( ! empty( $all_headings_data['font_weight'] ) ) {
				$all_headings_css .= 'font-weight: ' . esc_attr( $all_headings_data['font_weight'] ) . ';';
			}
			if ( ! empty( $all_headings_data['line_height'] ) ) {
				$all_headings_css .= 'line-height: ' . esc_attr( $all_headings_data['line_height'] ) . ';';
			}
			if ( ! empty( $all_headings_data['letter_spacing'] ) ) {
				$all_headings_css .= 'letter-spacing: ' . esc_attr( $all_headings_data['letter_spacing'] ) . 'px;';
			}
			if ( ! empty( $all_headings_data['text_transform'] ) ) {
				$all_headings_css .= 'text-transform: ' . esc_attr( $all_headings_data['text_transform'] ) . ';';
			}
			
			if ( ! empty( $all_headings_css ) ) {
				$css .= 'h1, h2, h3, h4, h5, h6, .entry-title { ' . $all_headings_css . ' }';
			}
		}
	}
	
	// Post/Page Title Typography
	$post_title_typography = get_theme_mod( 'accepta_post_title_typography', '' );
	
	if ( ! empty( $post_title_typography ) ) {
		$post_title_data = json_decode( $post_title_typography, true );
		if ( is_array( $post_title_data ) ) {
			$post_title_css = '';
			
			if ( ! empty( $post_title_data['font_family'] ) ) {
				$post_title_css .= accepta_generate_font_family_css( $post_title_data['font_family'] );
			}
			// Handle responsive font sizes separately (generates its own CSS with selector)
			$css .= accepta_generate_responsive_font_size_css( $post_title_data, '.entry-title' );
			
			if ( ! empty( $post_title_data['font_weight'] ) ) {
				$post_title_css .= 'font-weight: ' . esc_attr( $post_title_data['font_weight'] ) . ';';
			}
			if ( ! empty( $post_title_data['line_height'] ) ) {
				$post_title_css .= 'line-height: ' . esc_attr( $post_title_data['line_height'] ) . ';';
			}
			if ( ! empty( $post_title_data['letter_spacing'] ) ) {
				$post_title_css .= 'letter-spacing: ' . esc_attr( $post_title_data['letter_spacing'] ) . 'px;';
			}
			if ( ! empty( $post_title_data['text_transform'] ) ) {
				$post_title_css .= 'text-transform: ' . esc_attr( $post_title_data['text_transform'] ) . ';';
			}
			
			if ( ! empty( $post_title_css ) ) {
				$css .= '.entry-title { ' . $post_title_css . ' }';
			}
		}
	}
	
	// Individual Heading Typography (Overrides)
	$heading_selectors = array(
		'h1' => 'h1, .entry-title',
		'h2' => 'h2',
		'h3' => 'h3',
		'h4' => 'h4',
		'h5' => 'h5',
		'h6' => 'h6',
	);
	
	foreach ( $heading_selectors as $heading => $selector ) {
		$heading_typography = get_theme_mod( 'accepta_' . $heading . '_typography', '' );
		
		if ( ! empty( $heading_typography ) ) {
			$heading_data = json_decode( $heading_typography, true );
			if ( is_array( $heading_data ) ) {
				$heading_css = '';
				
				if ( ! empty( $heading_data['font_family'] ) ) {
					$heading_css .= accepta_generate_font_family_css( $heading_data['font_family'] );
				}
				// Handle responsive font sizes separately
				$css .= accepta_generate_responsive_font_size_css( $heading_data, $selector );
				if ( ! empty( $heading_data['font_weight'] ) ) {
					$heading_css .= 'font-weight: ' . esc_attr( $heading_data['font_weight'] ) . ';';
				}
				if ( ! empty( $heading_data['line_height'] ) ) {
					$heading_css .= 'line-height: ' . esc_attr( $heading_data['line_height'] ) . ';';
				}
				if ( ! empty( $heading_data['letter_spacing'] ) ) {
					$heading_css .= 'letter-spacing: ' . esc_attr( $heading_data['letter_spacing'] ) . 'px;';
				}
				if ( ! empty( $heading_data['text_transform'] ) ) {
					$heading_css .= 'text-transform: ' . esc_attr( $heading_data['text_transform'] ) . ';';
				}
				
				if ( ! empty( $heading_css ) ) {
					$css .= $selector . ' { ' . $heading_css . ' }';
				}
			}
		}
	}
	
	// Button Typography
	$button_typography = get_theme_mod( "accepta_button_typography", "" );
	if ( $button_typography ) {
		$button_typography = json_decode( $button_typography, true );
		if ( is_array( $button_typography ) ) {
			$button_css = "";
			
			if ( ! empty( $button_typography["font_family"] ) ) {
				$button_css .= accepta_generate_font_family_css( $button_typography["font_family"] );
			}
			// Handle responsive font sizes separately
			$css .= accepta_generate_responsive_font_size_css( $button_typography, "button, .button, input[type=\"button\"], input[type=\"reset\"], input[type=\"submit\"]" );
			if ( ! empty( $button_typography["font_weight"] ) ) {
				$button_css .= "font-weight: " . esc_attr( $button_typography["font_weight"] ) . ";";
			}
			if ( ! empty( $button_typography["line_height"] ) ) {
				$button_css .= "line-height: " . esc_attr( $button_typography["line_height"] ) . ";";
			}
			if ( ! empty( $button_typography["letter_spacing"] ) ) {
				$button_css .= "letter-spacing: " . esc_attr( $button_typography["letter_spacing"] ) . "px;";
			}
			if ( ! empty( $button_typography["text_transform"] ) ) {
				$button_css .= "text-transform: " . esc_attr( $button_typography["text_transform"] ) . ";";
			}
			
			if ( ! empty( $button_css ) ) {
				$css .= "button, .button, input[type=\"button\"], input[type=\"reset\"], input[type=\"submit\"] { " . $button_css . " }";
			}
		}
	}
	
	return $css;
}

/**
 * Theme mod values used for starter content preview (Customizer preview when viewing front page).
 * When user saves starter content, these are applied via customize_save_after.
 *
 * @return array Key => value for theme_mod.
 */
/**
 * When Customizer is saved: if the front page has starter content, set full-width option only.
 * Hero, header overlay, and other Customizer options are controlled by the user.
 */
function accepta_starter_content_on_customize_save() {
	$front_page_id = (int) get_option( 'page_on_front', 0 );
	if ( ! $front_page_id ) {
		delete_option( 'accepta_front_page_full_width' );
		return;
	}
	$post = get_post( $front_page_id );
	if ( ! $post || $post->post_type !== 'page' ) {
		delete_option( 'accepta_front_page_full_width' );
		return;
	}
	$has_starter = ( strpos( $post->post_content, 'accepta-icon-box-icon' ) !== false );
	if ( $has_starter ) {
		update_option( 'accepta_front_page_full_width', '1' );
	} else {
		delete_option( 'accepta_front_page_full_width' );
	}
}
add_action( 'customize_save_after', 'accepta_starter_content_on_customize_save' );
