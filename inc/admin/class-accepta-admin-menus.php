<?php
/**
 * Accepta Theme Admin Menus Class
 *
 * @package Accepta
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Admin Menus Class
 * 
 * This class handles admin menu registration and page rendering
 */
class Accepta_Admin_Menus {
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
     * Main Menus Instance
     *
     * Ensures only one instance of the menus class is loaded or can be loaded.
     *
     * @param Accepta_Admin $parent Parent admin class instance.
     * @return Accepta_Admin_Menus - Main instance
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
        
        // Hook into the admin menu
        add_action( 'admin_menu', array( $this, 'register_admin_menu' ) );
    }
    
    /**
     * Get the Accepta menu icon as a base64 SVG (WordPress native admin menu support).
     *
     * @return string Data URI for the menu icon.
     */
    private function get_menu_icon() {
        $svg = '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20"><path d="M10 2L3 18h2.8l1.4-3.5h5.6l1.4 3.5H17L10 2zm0 4.5l3.5 7.5H6.5L10 6.5z" fill="#ffffff"/></svg>';

        return 'data:image/svg+xml;base64,' . base64_encode( $svg );
    }

    /**
     * Register the Accepta admin menu and subpages
     */
    public function register_admin_menu() {
        // Main menu page
        add_menu_page(
            esc_html__( 'Accepta', 'accepta' ),
            esc_html__( 'Accepta', 'accepta' ),
            'manage_options',
            'accepta',
            array( $this, 'welcome_page' ),
            $this->get_menu_icon(),
            60
        );
        
        // Welcome/About subpage
        add_submenu_page(
            'accepta',
            esc_html__( 'Welcome to Accepta', 'accepta' ),
            esc_html__( 'Dashboard', 'accepta' ),
            'manage_options',
            'accepta',
            array( $this, 'welcome_page' )
        );
        
        // Install Plugins subpage
        add_submenu_page(
            'accepta',
            esc_html__( 'Install Plugins', 'accepta' ),
            esc_html__( 'Install Plugins', 'accepta' ),
            'manage_options',
            'accepta-plugins',
            array( $this, 'plugins_page' )
        );
    }

    /**
     * Render the Welcome/About page content
     */
    public function welcome_page() {
        require_once get_template_directory() . '/inc/admin/pages/welcome.php';
    }

    /**
     * Render the Install Plugins page content
     */
    public function plugins_page() {
        require_once get_template_directory() . '/inc/admin/pages/plugins.php';
    }
} 