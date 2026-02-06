<?php
/**
 * Accepta Theme Admin Class
 *
 * @package Accepta
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Load additional admin classes
require_once get_template_directory() . '/inc/admin/class-accepta-admin-customizer.php';
require_once get_template_directory() . '/inc/admin/class-accepta-admin-menus.php';
require_once get_template_directory() . '/inc/admin/class-accepta-admin-plugins.php';

/**
 * Main Admin Class
 */
class Accepta_Admin {
    /**
     * Instance of this class
     *
     * @var object
     */
    private static $instance = null;

    /**
     * Theme info 
     *
     * @var array
     */
    private $theme_info = array();
    
    /**
     * Admin modules
     *
     * @var array
     */
    private $modules = array();

    /**
     * Main Admin Instance
     *
     * Ensures only one instance of the admin class is loaded or can be loaded.
     *
     * @return Accepta_Admin - Main instance
     */
    public static function instance() {
        if ( is_null( self::$instance ) ) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Constructor
     */
    public function __construct() {
        
		// Store theme info
        $theme = wp_get_theme();
        
		$this->theme_info = array(
            'name'        => $theme->get( 'Name' ),
            'version'     => $theme->get( 'Version' ),
            'description' => $theme->get( 'Description' ),
            'author'      => $theme->get( 'Author' ),
            'author_uri'  => $theme->get( 'AuthorURI' ),
            'theme_uri'   => $theme->get( 'ThemeURI' ),
        );
        
        // Initialize admin modules
        $this->init_modules();
        
        // Enqueue admin scripts and styles
        add_action( 'admin_enqueue_scripts', array( $this, 'admin_scripts' ) );
        
        // Add dashboard widget
        //add_action( 'wp_dashboard_setup', array( $this, 'add_dashboard_widget' ) );
        
        // Handle AJAX for dismissing plugin notice
        add_action( 'wp_ajax_accepta_dismiss_plugin_notice', array( $this, 'dismiss_plugin_notice' ) );
        
        // Add admin footer to theme pages
        add_action( 'accepta_admin_footer', array( $this, 'render_admin_footer' ) );
    }
    
    /**
     * Initialize admin modules
     */
    private function init_modules() {
        // Initialize the customizer module
        $this->modules['customizer'] = Accepta_Admin_Customizer::instance( $this );
        
        // Initialize the menus module
        $this->modules['menus'] = Accepta_Admin_Menus::instance( $this );
        
        // Initialize the plugins module
        $this->modules['plugins'] = Accepta_Admin_Plugins::instance( $this );
    }

    /**
     * Enqueue scripts and styles for the admin pages
     */
    public function admin_scripts( $hook ) {
        // Always load admin styles (needed for menu icon on all admin pages)
        wp_enqueue_style(
            'accepta-admin-style',
            get_template_directory_uri() . '/inc/admin/assets/css/admin.css',
            array(),
            filemtime( get_template_directory() . '/inc/admin/assets/css/admin.css' )
        );
        
        // Add inline CSS for menu icon with correct SVG path and positioning
        $icon_url = get_template_directory_uri() . '/inc/admin/assets/images/accepta-icon.svg';
        $custom_css = "
        #toplevel_page_accepta .wp-menu-image,
        #toplevel_page_accepta.wp-has-current-submenu .wp-menu-image,
        #toplevel_page_accepta.wp-menu-open .wp-menu-image,
        #toplevel_page_accepta:hover .wp-menu-image,
        #toplevel_page_accepta a .wp-menu-image {
            width: 36px !important;
            height: 34px !important;
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
            background-image: url('" . esc_url( $icon_url ) . "') !important;
            background-size: 20px 20px !important;
            background-position: center center !important;
        }";
        wp_add_inline_style( 'accepta-admin-style', $custom_css );
        
        // Only load scripts on Accepta theme pages
        if ( strpos( $hook, 'accepta' ) === false ) {
            return;
        }
        
        // Admin scripts
        wp_enqueue_script(
            'accepta-admin-script',
            get_template_directory_uri() . '/inc/admin/assets/js/admin.js',
            array( 'jquery' ),
            filemtime( get_template_directory() . '/inc/admin/assets/js/admin.js' ),
            true
        );
    }

    /**
     * Add a dashboard widget with theme information
     */
    public function add_dashboard_widget() {
        wp_add_dashboard_widget(
            'accepta_dashboard_widget',
            esc_html__( 'Accepta Theme Information', 'accepta' ),
            array( $this, 'render_dashboard_widget' )
        );
    }

    /**
     * Render the dashboard widget content
     */
    public function render_dashboard_widget() {
        ?>
        <div class="accepta-dashboard-widget">
            <div class="accepta-dashboard-widget-header">
                <h3><?php echo esc_html( $this->theme_info['name'] ); ?> <?php echo esc_html__( 'Theme', 'accepta' ); ?></h3>
                <p><?php echo esc_html( $this->theme_info['description'] ); ?></p>
            </div>
            
            <ul class="accepta-dashboard-info">
                <li>
                    <span class="label"><?php esc_html_e( 'v.', 'accepta' ); ?>:</span>
                    <span class="value"><?php echo esc_html( $this->theme_info['version'] ); ?></span>
                </li>
                <li>
                    <span class="label"><?php esc_html_e( 'Author', 'accepta' ); ?>:</span>
                    <span class="value">
                        <a href="<?php echo esc_url( $this->theme_info['author_uri'] ); ?>" target="_blank">
                            <?php echo esc_html( $this->theme_info['author'] ); ?>
                        </a>
                    </span>
                </li>
            </ul>
            
            <div class="accepta-dashboard-actions">
                <a href="<?php echo esc_url( admin_url( 'admin.php?page=accepta' ) ); ?>" class="button button-primary">
                    <?php esc_html_e( 'Theme Dashboard', 'accepta' ); ?>
                </a>
                <a href="<?php echo esc_url( admin_url( 'customize.php' ) ); ?>" class="button">
                    <?php esc_html_e( 'Customize', 'accepta' ); ?>
                </a>
            </div>
        </div>
        <style>
            .accepta-dashboard-widget {
                margin: -12px;
            }
            .accepta-dashboard-widget-header {
                padding: 12px 12px 0;
            }
            .accepta-dashboard-widget-header h3 {
                margin-top: 0;
                color: #1abc9c;
            }
            .accepta-dashboard-info {
                margin: 15px 0;
                padding: 0 12px;
            }
            .accepta-dashboard-info li {
                margin-bottom: 8px;
                display: flex;
            }
            .accepta-dashboard-info .label {
                font-weight: 600;
                min-width: 70px;
            }
            .accepta-dashboard-actions {
                padding: 12px;
                background: #f8f8f8;
                display: flex;
                justify-content: space-between;
            }
        </style>
        <?php
    }
    
    /**
     * Dismiss plugin recommendation notice
     */
    public function dismiss_plugin_notice() {
        update_user_meta( get_current_user_id(), 'accepta_plugin_notice_dismissed', true );
        wp_die();
    }
    
    /**
     * Get theme information
     *
     * @return array Theme information
     */
    public function get_theme_info() {
        return $this->theme_info;
    }
    
    /**
     * Get module instance
     *
     * @param string $module Module name.
     * @return object|null Module instance or null if not found.
     */
    public function get_module( $module ) {
        return isset( $this->modules[ $module ] ) ? $this->modules[ $module ] : null;
    }

    /**
     * Render the admin footer on theme pages
     */
    public function render_admin_footer() {
        // Only show on Accepta theme pages
        $screen = get_current_screen();
        if ( strpos( $screen->id, 'accepta' ) === false ) {
            return;
        }
        
        // Don't display on the welcome page as it includes the footer directly
        if ( $screen->id === 'toplevel_page_accepta' ) {
            return;
        }
        
        echo '<div class="accepta-admin-footer-standalone">';
        include_once get_template_directory() . '/inc/admin/templates/admin-footer.php';
        echo '</div>';
    }
}

// Initialize the admin class on init hook (after textdomain is loaded)
add_action( 'init', function() {
    Accepta_Admin::instance();
}, 11 ); 