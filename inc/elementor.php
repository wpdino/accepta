<?php
/**
 * Elementor Compatibility File
 *
 * @package Accepta
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Elementor setup function
 */
function accepta_elementor_setup() {
    // Add theme support for Elementor.
    add_theme_support( 'elementor' );
    
    // Add theme support for Elementor Pro.
    add_theme_support( 'elementor-pro' );
    
    // Add support for default header and footer.
    add_theme_support( 'elementor-header-footer' );
}
add_action( 'after_setup_theme', 'accepta_elementor_setup' );

/**
 * Add Elementor Page Templates
 *
 * @param array $templates An array of page templates.
 * @return array Modified templates array.
 */
function accepta_add_elementor_page_templates( $templates ) {
    $templates['elementor_header_footer'] = esc_html__( 'Elementor Full Width', 'accepta' );
    $templates['elementor_canvas'] = esc_html__( 'Elementor Canvas', 'accepta' );
    
    return $templates;
}
add_filter( 'theme_page_templates', 'accepta_add_elementor_page_templates' );

/**
 * Register Elementor locations for Elementor Pro
 *
 * @param \ElementorPro\Modules\ThemeBuilder\Classes\Locations_Manager $locations_manager Elementor locations manager.
 */
function accepta_register_elementor_locations( $locations_manager ) {
    if ( method_exists( $locations_manager, 'register_all' ) ) {
        $locations_manager->register_all();
    }
}
add_action( 'elementor/theme/register_locations', 'accepta_register_elementor_locations' );

/**
 * Add custom Elementor widgets categories
 *
 * @param \Elementor\Elements_Manager $elements_manager Elementor elements manager.
 */
function accepta_add_elementor_widget_categories( $elements_manager ) {
    $elements_manager->add_category(
        'accepta-elements',
        [
            'title' => esc_html__( 'Accepta Elements', 'accepta' ),
            'icon' => 'fa fa-plug',
        ]
    );
}
add_action( 'elementor/elements/categories_registered', 'accepta_add_elementor_widget_categories' );

/**
 * Add default Elementor color scheme.
 *
 * @param array $schemes An array of Elementor color schemes.
 * @return array Modified schemes array.
 */
function accepta_elementor_default_colors( $schemes ) {
    // Get the existing scheme 1
    $schemes_obj = new \Elementor\Core\Schemes\Color();
    $scheme_1 = $schemes_obj->get_scheme( 'color' );
    
    // Define your custom colors
    $custom_colors = [
        1 => '#1abc9c', // Primary
        2 => '#333333', // Secondary
        3 => '#555555', // Text
        4 => '#f5f5f5', // Accent
    ];
    
    // Merge the custom colors with existing 
    foreach( $custom_colors as $index => $color ) {
        if ( isset( $scheme_1[ $index ] ) ) {
            $schemes[ $index ] = $color;
        }
    }
    
    return $schemes;
}
add_filter( 'elementor/schemes/default_color', 'accepta_elementor_default_colors' ); 