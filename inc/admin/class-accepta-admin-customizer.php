<?php
/**
 * Accepta Theme Admin Customizer Class
 *
 * @package Accepta
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Admin Customizer Class
 * 
 * This class handles customizer-specific functionality for the admin interface
 */
class Accepta_Admin_Customizer {
    /**
     * Instance of this class
     *
     * @var object
     */
    private static $instance = null;
    
    /**
     * Parent admin instance
     *
     * @var Accepta_Admin
     */
    private $parent = null;

    /**
     * Main Customizer Instance
     *
     * Ensures only one instance of the customizer class is loaded or can be loaded.
     *
     * @param Accepta_Admin $parent Parent admin class instance.
     * @return Accepta_Admin_Customizer - Main instance
     */
    public static function instance( $parent = null ) {
        if ( is_null( self::$instance ) ) {
            self::$instance = new self( $parent );
        }
        return self::$instance;
    }

    /**
     * Constructor
     * 
     * @param Accepta_Admin $parent Parent admin class instance.
     */
    public function __construct( $parent = null ) {
        $this->parent = $parent;
        
        // Add customizer preview script
        add_action( 'customize_preview_init', array( $this, 'customizer_preview_script' ) );
        
        // Ensure Font Awesome loads in customizer preview
        add_action( 'wp_enqueue_scripts', array( $this, 'ensure_fontawesome_in_preview' ), 5 );
        
        // Add customizer controls script
        add_action( 'customize_controls_enqueue_scripts', array( $this, 'customizer_controls_script' ) );
        
        // Add link to customizer in the admin bar
        add_action( 'admin_bar_menu', array( $this, 'add_admin_bar_customizer_link' ), 999 );
    }
    
    /**
     * Add customizer preview script
     */
    public function customizer_preview_script() {
        wp_enqueue_script(
            'accepta-customizer-preview',
            get_template_directory_uri() . '/inc/admin/assets/js/customizer-preview.js',
            array( 'jquery', 'customize-preview' ),
            filemtime( get_template_directory() . '/inc/admin/assets/js/customizer-preview.js' ),
            true
        );
    }
    
    /**
     * Ensure Font Awesome is loaded in customizer preview
     */
    public function ensure_fontawesome_in_preview() {
        if ( is_customize_preview() ) {
            // Ensure Font Awesome is enqueued in preview
            if ( ! wp_style_is( 'font-awesome', 'enqueued' ) ) {
                wp_enqueue_style(
                    'font-awesome',
                    get_template_directory_uri() . '/assets/fonts/fontawesome/all.min.css',
                    array(),
                    '6.4.0'
                );
            }
        }
    }
    
    /**
     * Add customizer controls script
     */
    public function customizer_controls_script() {
        wp_enqueue_script(
            'accepta-customizer-controls',
            get_template_directory_uri() . '/inc/admin/assets/js/customizer-controls.js',
            array( 'jquery', 'customize-controls' ),
            filemtime( get_template_directory() . '/inc/admin/assets/js/customizer-controls.js' ),
            true
        );
        
        wp_enqueue_style(
            'accepta-customizer-controls',
            get_template_directory_uri() . '/inc/admin/assets/css/customizer-controls.css',
            array(),
            filemtime( get_template_directory() . '/inc/admin/assets/css/customizer-controls.css' )
        );
        
        // Enqueue background control scripts globally to ensure they load
        // Load the control class file if not already loaded
        $background_control_file = get_template_directory() . '/inc/customizer-controls/class-accepta-background-control.php';
        if ( file_exists( $background_control_file ) && ! class_exists( 'Accepta_Background_Control' ) ) {
            require_once $background_control_file;
        }
        
        // Always enqueue the scripts if the file exists (don't check for class since it might not be instantiated yet)
        $version = defined( '_ACCEPTA_VERSION' ) ? _ACCEPTA_VERSION : wp_get_theme()->get( 'Version' );
        
        $script_path = get_template_directory_uri() . '/inc/customizer-controls/js/background-control.js';
        $script_file = get_template_directory() . '/inc/customizer-controls/js/background-control.js';
        
        // Check if script is already registered
        if ( ! wp_script_is( 'accepta-background-control', 'registered' ) ) {
            wp_register_script(
                'accepta-background-control',
                $script_path,
                array( 'jquery', 'customize-controls', 'wp-color-picker', 'jquery-ui-slider', 'media-views' ),
                $version,
                true
            );
        }
        
        // Always enqueue (even if already registered)
        wp_enqueue_script( 'accepta-background-control' );

        // Check if style is already registered
        if ( ! wp_style_is( 'accepta-background-control', 'registered' ) ) {
            wp_register_style(
                'accepta-background-control',
                get_template_directory_uri() . '/inc/customizer-controls/css/background-control.css',
                array( 'wp-color-picker' ),
                $version
            );
        }
        
        wp_enqueue_style( 'accepta-background-control' );
        
    }
    
    /**
     * Add customizer link in the admin bar
     * 
     * @param WP_Admin_Bar $wp_admin_bar Admin bar instance.
     */
    public function add_admin_bar_customizer_link( $wp_admin_bar ) {
        if ( ! is_admin() || ! is_admin_bar_showing() ) {
            return;
        }
        
        // Add the customizer link
        $wp_admin_bar->add_node(
            array(
                'id'    => 'accepta-customizer',
                'title' => esc_html__( 'Accepta Customizer', 'accepta' ),
                'href'  => admin_url( 'customize.php' ),
                'parent' => 'appearance',
            )
        );
    }
} 