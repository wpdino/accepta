<?php
/**
 * Accepta Theme Custom Functions
 *
 * This file contains custom functions specific to the Accepta theme.
 * These are theme-specific features and functionality that extend beyond
 * the standard WordPress theme requirements.
 *
 * @package Accepta
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Add sample widgets to footer areas on theme activation
 *
 * This is a custom Accepta theme feature that automatically populates
 * the footer widget areas with sample content when the theme is activated.
 */
function accepta_add_sample_footer_widgets() {
	// Check if widgets are already populated to avoid duplicates
	if ( get_option( 'accepta_sample_widgets_added' ) ) {
		return;
	}

	// Sample widget content
	$sample_widgets = array(
		'footer-1' => array(
			'type' => 'text',
			'title' => __( 'About Accepta', 'accepta' ),
			'content' => __( 'Accepta is a modern, responsive WordPress theme designed for businesses, portfolios, and blogs.', 'accepta' ),
		),
		'footer-2' => array(
			'type' => 'text',
			'title' => __( 'About WPDINO', 'accepta' ),
			'content' => __( 'WPDINO is a WordPress development company. We create beautiful, functional themes that help businesses grow online.', 'accepta' ),
		),
		'footer-3' => array(
			'type' => 'nav_menu_or_pages',
			'title' => __( 'Quick Links', 'accepta' ),
			'menu' => 'sample-footer-menu',
		),
		'footer-4' => array(
			'type' => 'search',
			'title' => __( 'Search', 'accepta' ),
		),
	);

	// Create or get sample menu for footer-3
	$menu_name = esc_html__( 'Sample Footer Menu', 'accepta' );
	$menu_id = null;
	
	// Check if menu already exists
	$menus = wp_get_nav_menus();
	foreach ( $menus as $menu ) {
		if ( $menu->name === $menu_name ) {
			$menu_id = $menu->term_id;
			break;
		}
	}
	
	// Create menu if it doesn't exist
	if ( ! $menu_id ) {
		$menu_result = wp_create_nav_menu( $menu_name );
		if ( ! is_wp_error( $menu_result ) ) {
			$menu_id = $menu_result;
		}
	}
	
	// Add sample menu items if we have a valid menu ID
	if ( $menu_id && is_numeric( $menu_id ) ) {
		// Check if menu already has items to avoid duplicates
		$menu_items = wp_get_nav_menu_items( $menu_id );
		if ( empty( $menu_items ) ) {
			$sample_pages = array(
				esc_html__( 'Home', 'accepta' ) => home_url(),
				esc_html__( 'About', 'accepta' ) => '#',
				esc_html__( 'Services', 'accepta' ) => '#',
				esc_html__( 'Contact', 'accepta' ) => '#',
				esc_html__( 'Privacy Policy', 'accepta' ) => '#',
			);

			foreach ( $sample_pages as $title => $url ) {
				wp_update_nav_menu_item( $menu_id, 0, array(
					'menu-item-title' => $title,
					'menu-item-url' => $url,
					'menu-item-status' => 'publish',
					'menu-item-type' => 'custom',
				) );
			}
		}
	}

	// Add widgets to sidebars
	// Get sidebars_widgets once before the loop
	$sidebar_widgets = get_option( 'sidebars_widgets', array() );
	
	foreach ( $sample_widgets as $sidebar_id => $widget_data ) {
		if ( ! isset( $sidebar_widgets[ $sidebar_id ] ) ) {
			$sidebar_widgets[ $sidebar_id ] = array();
		}
		
		// Skip if sidebar already has widgets to avoid duplicates
		if ( ! empty( $sidebar_widgets[ $sidebar_id ] ) ) {
			continue;
		}

		switch ( $widget_data['type'] ) {
			case 'text':
				// Get existing text widgets to find next available ID
				$text_widgets = get_option( 'widget_text', array() );
				$text_widget_id = 1;
				if ( ! empty( $text_widgets ) && is_array( $text_widgets ) ) {
					// Find the highest numeric ID
					$numeric_keys = array_filter( array_keys( $text_widgets ), 'is_numeric' );
					if ( ! empty( $numeric_keys ) ) {
						$max_id = max( $numeric_keys );
						if ( $max_id ) {
							$text_widget_id = $max_id + 1;
						}
					}
				}
				$widget_id = 'text-' . $text_widget_id;
				$text_widgets[ $text_widget_id ] = array(
					'title' => $widget_data['title'],
					'text' => $widget_data['content'],
					'filter' => false,
				);
				update_option( 'widget_text', $text_widgets );
				$sidebar_widgets[ $sidebar_id ][] = $widget_id;
				break;

			case 'nav_menu':
				// Only create nav menu widget if we have a valid menu ID
				if ( $menu_id && is_numeric( $menu_id ) ) {
					// Get existing nav menu widgets to find next available ID
					$nav_menu_widgets = get_option( 'widget_nav_menu', array() );
					$nav_menu_widget_id = 1;
					if ( ! empty( $nav_menu_widgets ) && is_array( $nav_menu_widgets ) ) {
						// Find the highest numeric ID
						$numeric_keys = array_filter( array_keys( $nav_menu_widgets ), 'is_numeric' );
						if ( ! empty( $numeric_keys ) ) {
							$max_id = max( $numeric_keys );
							if ( $max_id ) {
								$nav_menu_widget_id = $max_id + 1;
							}
						}
					}
					$widget_id = 'nav_menu-' . $nav_menu_widget_id;
					$nav_menu_widgets[ $nav_menu_widget_id ] = array(
						'title' => $widget_data['title'],
						'nav_menu' => (int) $menu_id,
					);
					update_option( 'widget_nav_menu', $nav_menu_widgets );
					$sidebar_widgets[ $sidebar_id ][] = $widget_id;
				}
				break;

			case 'nav_menu_or_pages':
				// Try to use nav menu widget first, fallback to pages widget
				if ( $menu_id && is_numeric( $menu_id ) ) {
					// Use nav menu widget
					$nav_menu_widgets = get_option( 'widget_nav_menu', array() );
					$nav_menu_widget_id = 1;
					if ( ! empty( $nav_menu_widgets ) && is_array( $nav_menu_widgets ) ) {
						$numeric_keys = array_filter( array_keys( $nav_menu_widgets ), 'is_numeric' );
						if ( ! empty( $numeric_keys ) ) {
							$max_id = max( $numeric_keys );
							if ( $max_id ) {
								$nav_menu_widget_id = $max_id + 1;
							}
						}
					}
					$widget_id = 'nav_menu-' . $nav_menu_widget_id;
					$nav_menu_widgets[ $nav_menu_widget_id ] = array(
						'title' => $widget_data['title'],
						'nav_menu' => (int) $menu_id,
					);
					update_option( 'widget_nav_menu', $nav_menu_widgets );
					$sidebar_widgets[ $sidebar_id ][] = $widget_id;
				} else {
					// Fallback to pages widget (first level pages only)
					$pages_widgets = get_option( 'widget_pages', array() );
					$pages_widget_id = 1;
					if ( ! empty( $pages_widgets ) && is_array( $pages_widgets ) ) {
						$numeric_keys = array_filter( array_keys( $pages_widgets ), 'is_numeric' );
						if ( ! empty( $numeric_keys ) ) {
							$max_id = max( $numeric_keys );
							if ( $max_id ) {
								$pages_widget_id = $max_id + 1;
							}
						}
					}
					$widget_id = 'pages-' . $pages_widget_id;
					$pages_widgets[ $pages_widget_id ] = array(
						'title' => $widget_data['title'],
						'sortby' => 'menu_order',
						'exclude' => '',
					);
					update_option( 'widget_pages', $pages_widgets );
					$sidebar_widgets[ $sidebar_id ][] = $widget_id;
				}
				break;

			case 'search':
				// Get existing search widgets to find next available ID
				$search_widgets = get_option( 'widget_search', array() );
				$search_widget_id = 1;
				if ( ! empty( $search_widgets ) && is_array( $search_widgets ) ) {
					// Find the highest numeric ID
					$numeric_keys = array_filter( array_keys( $search_widgets ), 'is_numeric' );
					if ( ! empty( $numeric_keys ) ) {
						$max_id = max( $numeric_keys );
						if ( $max_id ) {
							$search_widget_id = $max_id + 1;
						}
					}
				}
				$widget_id = 'search-' . $search_widget_id;
				$search_widgets[ $search_widget_id ] = array(
					'title' => $widget_data['title'],
				);
				update_option( 'widget_search', $search_widgets );
				$sidebar_widgets[ $sidebar_id ][] = $widget_id;
				break;
		}
	}
	
	// Update sidebars_widgets once after all widgets are added
	update_option( 'sidebars_widgets', $sidebar_widgets );

	// Mark that sample widgets have been added
	update_option( 'accepta_sample_widgets_added', true );
}
add_action( 'after_switch_theme', 'accepta_add_sample_footer_widgets' );

/**
 * Return default social media items for header/footer (same structure).
 *
 * @return array Default social media items.
 */
function accepta_get_default_social_media_items() {
	return array(
		array(
			'label'       => 'Facebook',
			'url'         => 'https://facebook.com/yourpage',
			'icon_type'   => 'fontawesome',
			'icon'        => 'fab fa-facebook-f',
			'custom_icon' => '',
		),
		array(
			'label'       => 'Twitter',
			'url'         => 'https://twitter.com/yourusername',
			'icon_type'   => 'fontawesome',
			'icon'        => 'fab fa-twitter',
			'custom_icon' => '',
		),
		array(
			'label'       => 'Instagram',
			'url'         => 'https://instagram.com/yourusername',
			'icon_type'   => 'fontawesome',
			'icon'        => 'fab fa-instagram',
			'custom_icon' => '',
		),
		array(
			'label'       => 'LinkedIn',
			'url'         => 'https://linkedin.com/in/yourprofile',
			'icon_type'   => 'fontawesome',
			'icon'        => 'fab fa-linkedin-in',
			'custom_icon' => '',
		),
	);
}

/**
 * Set default social icon theme mods (used on activation and one-time migration).
 */
function accepta_set_default_social_icons_on_activation() {
	$default_social = wp_json_encode( accepta_get_default_social_media_items() );

	// Header: if no social data is stored, set defaults and enable display.
	$header_social = get_theme_mod( 'accepta_header_social_media', '' );
	$header_decoded = is_string( $header_social ) ? json_decode( $header_social, true ) : $header_social;
	if ( empty( $header_decoded ) || ! is_array( $header_decoded ) ) {
		set_theme_mod( 'accepta_display_header_social_icons', true );
		set_theme_mod( 'accepta_header_social_media', $default_social );
	}

	// Footer: if no social data is stored, set defaults and enable display.
	$footer_social = get_theme_mod( 'accepta_social_media', '' );
	$footer_decoded = is_string( $footer_social ) ? json_decode( $footer_social, true ) : $footer_social;
	if ( empty( $footer_decoded ) || ! is_array( $footer_decoded ) ) {
		set_theme_mod( 'accepta_display_social_icons', true );
		set_theme_mod( 'accepta_social_media', $default_social );
	}
}
add_action( 'after_switch_theme', 'accepta_set_default_social_icons_on_activation' );

/**
 * One-time migration: set default social icons if theme was already activated with empty social.
 */
function accepta_maybe_set_default_social_icons() {
	if ( get_option( 'accepta_social_defaults_applied' ) ) {
		return;
	}

	$header_social = get_theme_mod( 'accepta_header_social_media', '' );
	$footer_social = get_theme_mod( 'accepta_social_media', '' );
	$header_decoded = is_string( $header_social ) ? json_decode( $header_social, true ) : $header_social;
	$footer_decoded = is_string( $footer_social ) ? json_decode( $footer_social, true ) : $footer_social;
	$header_empty = empty( $header_decoded ) || ! is_array( $header_decoded );
	$footer_empty = empty( $footer_decoded ) || ! is_array( $footer_decoded );

	if ( $header_empty || $footer_empty ) {
		accepta_set_default_social_icons_on_activation();
	}

	update_option( 'accepta_social_defaults_applied', true );
}
add_action( 'init', 'accepta_maybe_set_default_social_icons', 5 );

/**
 * Display social media icons.
 *
 * This is a custom Accepta theme feature that displays social media icons
 * based on the repeater control in customizer settings.
 *
 * @param string $location Location where icons are displayed ('footer' or 'header').
 */
function accepta_social_icons( $location = 'footer' ) {
	// Get social media data from repeater control based on location
	if ( $location === 'header' ) {
		$social_media_json = get_theme_mod( 'accepta_header_social_media', '' );
	} else {
		$social_media_json = get_theme_mod( 'accepta_social_media', '' );
	}
	$social_media = json_decode( $social_media_json, true );
	
	// Return early if no social media data
	if ( empty( $social_media ) || ! is_array( $social_media ) ) {
		return;
	}
	
	// Start output
	$output = '<div class="social-icons">';
	
	// Loop through each social media item
	foreach ( $social_media as $index => $item ) {
		// Skip if required fields are missing
		if ( empty( $item['label'] ) || empty( $item['url'] ) ) {
			continue;
		}
		
		$label = sanitize_text_field( $item['label'] );
		$url = esc_url( $item['url'] );
		$icon_type = ! empty( $item['icon_type'] ) ? sanitize_text_field( $item['icon_type'] ) : 'fontawesome';
		$icon_class = ! empty( $item['icon'] ) ? sanitize_text_field( $item['icon'] ) : '';
		$custom_icon = ! empty( $item['custom_icon'] ) ? esc_url( $item['custom_icon'] ) : '';
		
		// Create CSS class from label
		$css_class = sanitize_html_class( strtolower( $label ) );
		
		// Build the icon HTML based on icon type
		$icon_html = '';
		if ( $icon_type === 'custom' && ! empty( $custom_icon ) ) {
			// Use custom SVG icon
			$icon_html = sprintf( '<img src="%s" alt="%s" class="social-icon-svg" />', $custom_icon, esc_attr( $label ) );
		} elseif ( $icon_type === 'fontawesome' && ! empty( $icon_class ) ) {
			// Use Font Awesome icon
			$icon_html = sprintf( '<i class="%s" aria-hidden="true"></i>', esc_attr( $icon_class ) );
		} else {
			// Fallback to first letter of label
			$icon_html = '<span class="social-icon-text">' . esc_html( substr( $label, 0, 1 ) ) . '</span>';
		}
		
		$output .= sprintf(
			'<a href="%1$s" class="social-icon %2$s" target="_blank" rel="noopener noreferrer" title="%3$s">%4$s<span class="screen-reader-text">%3$s</span></a>',
			$url,
			esc_attr( $css_class ),
			esc_attr( $label ),
			$icon_html
		);
	}
	
	$output .= '</div>';
	
	// Only output if we have at least one social icon
	if ( empty( $output ) || $output === '<div class="social-icons"></div>' ) {
		return;
	}
	
	echo $output; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
}

/**
 * Check if header social icons should be displayed
 *
 * @return bool True if social icons should be displayed in header, false otherwise.
 */
function accepta_should_display_header_social_icons() {
	// Check if display is enabled
	$display_enabled = get_theme_mod( 'accepta_display_header_social_icons', true );
	if ( ! $display_enabled ) {
		return false;
	}
	
	// Get header social media data from repeater control
	$social_media_json = get_theme_mod( 'accepta_header_social_media', '' );
	$social_media = json_decode( $social_media_json, true );
	
	// Return false if no social media data
	if ( empty( $social_media ) || ! is_array( $social_media ) ) {
		return false;
	}
	
	// Check if at least one valid social media item exists
	$has_valid_item = false;
	foreach ( $social_media as $item ) {
		if ( ! empty( $item['label'] ) && ! empty( $item['url'] ) ) {
			$has_valid_item = true;
			break;
		}
	}
	
	return $has_valid_item;
}

/**
 * Check if social icons should be displayed
 * 
 * This is a helper function for the Accepta theme's social media feature.
 * 
 * @return bool True if social icons should be displayed, false otherwise
 */
function accepta_should_display_social_icons() {
	// Check if display is enabled
	$display_enabled = get_theme_mod( 'accepta_display_social_icons', true );
	if ( ! $display_enabled ) {
		return false;
	}
	
	// Get social media data from repeater control
	$social_media_json = get_theme_mod( 'accepta_social_media', '' );
	$social_media = json_decode( $social_media_json, true );
	
	// Return false if no social media data
	if ( empty( $social_media ) || ! is_array( $social_media ) ) {
		return false;
	}
	
	// Check if any social media item has both label and URL
	foreach ( $social_media as $item ) {
		if ( ! empty( $item['label'] ) && ! empty( $item['url'] ) ) {
			return true; // At least one valid social media item found
		}
	}
	
	return false; // No valid social media items found
}

/**
 * Convert hex color to rgba
 *
 * @param string $color Hex color code (with or without #)
 * @param float  $opacity Opacity value (0-1)
 * @return string RGBA color string
 */
if ( ! function_exists( 'accepta_hex_to_rgba' ) ) {
	function accepta_hex_to_rgba( $color, $opacity = 1 ) {
		$color = str_replace( '#', '', $color );
		if ( strlen( $color ) === 3 ) {
			$color = $color[0] . $color[0] . $color[1] . $color[1] . $color[2] . $color[2];
		}
		$r = hexdec( substr( $color, 0, 2 ) );
		$g = hexdec( substr( $color, 2, 2 ) );
		$b = hexdec( substr( $color, 4, 2 ) );
		return "rgba($r, $g, $b, $opacity)";
	}
}
