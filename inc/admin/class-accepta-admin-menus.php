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
     * Get the Accepta menu icon
     * 
     * Returns empty string - icon is handled via CSS for security compliance
     * 
     * @return string Empty string (icon handled in CSS)
     */
    private function get_menu_icon() {
        // Return empty string - icon will be displayed via CSS
        return '';
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