<?php
/**
 * Accepta Theme Admin Welcome Notice Class
 *
 * @package Accepta
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Main Admin Welcome Notice Class
 */
class Accepta_Admin_Welcome_Notice {
    /**
     * Instance of this class
     *
     * @var object
     */
    private static $instance = null;

    /**
     * Notice option key
     *
     * @var string
     */
    private $notice_option_key = 'accepta_welcome_notice_dismissed';

    /**
     * Main Admin Instance
     *
     * Ensures only one instance of the admin class is loaded or can be loaded.
     *
     * @return Accepta_Admin_Welcome_Notice - Main instance
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
        // Hook into admin notices
        add_action( 'admin_notices', array( $this, 'display_welcome_notice' ) );
        
        // Handle AJAX for dismissing notice
        add_action( 'wp_ajax_accepta_dismiss_welcome_notice', array( $this, 'dismiss_welcome_notice' ) );
        
        // Enqueue admin scripts for notice
        add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_notice_scripts' ) );
    }

    /**
     * Check if the notice should be displayed
     *
     * @return bool
     */
    private function should_display_notice() {
        // Don't show if already dismissed
        if ( get_option( $this->notice_option_key, false ) ) {
            return false;
        }

        // Only show on admin pages
        if ( ! is_admin() ) {
            return false;
        }

        // Don't show on Accepta admin pages (they have their own welcome content)
        $screen = get_current_screen();
        if ( $screen && strpos( $screen->id, 'accepta' ) !== false ) {
            return false;
        }
        
        // Don't show on theme-install pages
        if ( $screen && in_array( $screen->id, array( 'theme-install', 'theme-install-network' ) ) ) {
            return false;
        }
        
        // On themes.php page, only show if theme was just activated
        if ( $screen && $screen->id === 'themes' ) {
            // Check if theme was just activated via URL parameter
            if ( ! isset( $_GET['activated'] ) || $_GET['activated'] !== 'true' ) {
                return false;
            }
            
            // Verify that the activated theme is actually Accepta
            $current_theme = wp_get_theme();
            if ( $current_theme->get_template() !== 'accepta' ) {
                return false;
            }
        }

        // Show only to users who can manage themes
        if ( ! current_user_can( 'manage_options' ) ) {
            return false;
        }

        return true;
    }

    /**
     * Display the welcome notice
     */
    public function display_welcome_notice() {
        if ( ! $this->should_display_notice() ) {
            return;
        }

        $theme = wp_get_theme();
        $theme_name = $theme->get( 'Name' );
        $theme_version = $theme->get( 'Version' );
        ?>
        <div class="notice notice-success is-dismissible accepta-welcome-notice" data-notice="accepta-welcome">
            <div class="accepta-notice-content">
                <div class="accepta-notice-header">
                    <h2>
                        <span class="dashicons dashicons-welcome-learn-more"></span>
                        <?php echo sprintf( esc_html__( 'Welcome to %s!', 'accepta' ), esc_html( $theme_name ) ); ?>
                        <span class="accepta-notice-version">v<?php echo esc_html( $theme_version ); ?></span>
                    </h2>
                </div>
                
                <div class="accepta-notice-body">
                    <p class="accepta-notice-description">
                        <?php esc_html_e( 'Thank you for choosing Accepta! This modern and flexible WordPress theme is designed to help you create beautiful websites with ease. Get started by exploring the customization options and helpful resources below.', 'accepta' ); ?>
                    </p>
                    
                    <div class="accepta-notice-actions">
                        <div class="accepta-notice-primary-actions">
                            <a href="<?php echo esc_url( admin_url( 'admin.php?page=accepta' ) ); ?>" class="button button-primary button-hero">
                                <span class="dashicons dashicons-admin-home"></span>
                                <?php esc_html_e( 'Theme Dashboard', 'accepta' ); ?>
                            </a>
                            
                            <a href="<?php echo esc_url( admin_url( 'customize.php' ) ); ?>" class="button button-secondary">
                                <span class="dashicons dashicons-admin-customizer"></span>
                                <?php esc_html_e( 'Customize', 'accepta' ); ?>
                            </a>
                        </div>
                        
                        <div class="accepta-notice-secondary-actions">
                            <div class="accepta-starter-site-notice">
                                <h4>
                                    <span class="dashicons dashicons-download"></span>
                                    <?php esc_html_e( 'Quick Start with Demo Content', 'accepta' ); ?>
                                </h4>
                                <p><?php esc_html_e( 'Install the Accepta Starter Site plugin to import demo content and get your site up and running quickly.', 'accepta' ); ?></p>
                                <a href="<?php echo esc_url( $this->get_plugin_install_url( 'accepta-starter-site' ) ); ?>" class="button button-secondary">
                                    <span class="dashicons dashicons-plus-alt2"></span>
                                    <?php esc_html_e( 'Install Starter Site Plugin', 'accepta' ); ?>
                                </a>
                            </div>
                        </div>
                    </div>
                    
                    <div class="accepta-notice-links">
                        <a href="<?php echo esc_url( 'https://wordpress.org/support/themes/' ); ?>" target="_blank">
                            <span class="dashicons dashicons-sos"></span>
                            <?php esc_html_e( 'Support', 'accepta' ); ?>
                        </a>
                        <a href="<?php echo esc_url( 'https://wordpress.org/support/themes/' ); ?>" target="_blank">
                            <span class="dashicons dashicons-star-filled"></span>
                            <?php esc_html_e( 'Rate Theme', 'accepta' ); ?>
                        </a>
                        <a href="<?php echo esc_url( get_template_directory_uri() . '/README.md' ); ?>" target="_blank">
                            <span class="dashicons dashicons-media-document"></span>
                            <?php esc_html_e( 'Documentation', 'accepta' ); ?>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <style>
        .accepta-welcome-notice {
            border-left-color: #2271b1 !important;
            padding: 0 !important;
            position: relative;
            margin: 5px 0 15px;
            box-shadow: 0 1px 1px rgba(0, 0, 0, 0.04);
        }
        
        .accepta-notice-content {
            padding: 12px;
        }
        
        .accepta-notice-header h2 {
            margin: 0 0 12px 0;
            font-size: 13px;
            font-weight: 600;
            color: #1d2327;
            display: flex;
            align-items: center;
            gap: 6px;
            line-height: 1.4;
        }
        
        .accepta-notice-header h2 .dashicons {
            color: #2271b1;
            font-size: 18px;
            width: 18px;
            height: 18px;
        }
        
        .accepta-notice-version {
            background: #f0f0f1;
            padding: 2px 6px;
            border-radius: 3px;
            font-size: 11px;
            font-weight: 500;
            color: #646970;
            margin-left: auto;
        }
        
        .accepta-notice-description {
            font-size: 13px;
            line-height: 1.5;
            margin: 0 0 12px 0;
            color: #646970;
        }
        
        .accepta-notice-actions {
            display: flex;
            flex-wrap: wrap;
            gap: 12px;
            margin-bottom: 12px;
            align-items: flex-start;
        }
        
        .accepta-notice-primary-actions {
            display: flex;
            gap: 8px;
            align-items: center;
            flex-wrap: wrap;
        }
        
        .accepta-notice-secondary-actions {
            flex: 1;
            min-width: 280px;
        }
        
        .accepta-starter-site-notice {
            background: #f6f7f7;
            padding: 12px;
            border-radius: 2px;
            border-left: 4px solid #2271b1;
        }
        
        .accepta-starter-site-notice h4 {
            margin: 0 0 6px 0;
            font-size: 13px;
            font-weight: 600;
            color: #1d2327;
            display: flex;
            align-items: center;
            gap: 6px;
            line-height: 1.4;
        }
        
        .accepta-starter-site-notice h4 .dashicons {
            color: #2271b1;
            font-size: 16px;
            width: 16px;
            height: 16px;
        }
        
        .accepta-starter-site-notice p {
            margin: 0 0 8px 0;
            font-size: 12px;
            color: #646970;
            line-height: 1.5;
        }
        
        .accepta-notice-links {
            display: flex;
            gap: 16px;
            padding-top: 12px;
            border-top: 1px solid #dcdcde;
            margin-top: 12px;
        }
        
        .accepta-notice-links a {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            color: #2271b1;
            text-decoration: none;
            font-size: 12px;
            transition: color 0.15s ease;
        }
        
        .accepta-notice-links a:hover {
            color: #135e96;
        }
        
        .accepta-notice-links a .dashicons {
            font-size: 16px;
            width: 16px;
            height: 16px;
        }
        
        .accepta-welcome-notice .button {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            text-decoration: none;
            font-size: 13px;
            height: auto;
            padding: 6px 12px;
            line-height: 1.5;
        }
        
        .accepta-welcome-notice .button-primary {
            background: #2271b1;
            border-color: #2271b1;
            color: #fff;
        }
        
        .accepta-welcome-notice .button-primary:hover {
            background: #135e96;
            border-color: #135e96;
            color: #fff;
        }
        
        .accepta-welcome-notice .button-secondary {
            background: #f6f7f7;
            border-color: #dcdcde;
            color: #2c3338;
        }
        
        .accepta-welcome-notice .button-secondary:hover {
            background: #f0f0f1;
            border-color: #8c8f94;
            color: #1d2327;
        }
        
        .accepta-welcome-notice .button-hero {
            padding: 8px 16px;
            font-size: 14px;
            font-weight: 500;
        }
        
        .accepta-welcome-notice .button .dashicons {
            font-size: 16px;
            width: 16px;
            height: 16px;
        }
        
        @media (max-width: 782px) {
            .accepta-notice-actions {
                flex-direction: column;
                gap: 10px;
            }
            
            .accepta-notice-secondary-actions {
                min-width: auto;
            }
            
            .accepta-notice-links {
                flex-wrap: wrap;
                gap: 12px;
            }
        }
        </style>
        <?php
    }

    /**
     * Get plugin installation URL
     *
     * @param string $plugin_slug
     * @return string
     */
    private function get_plugin_install_url( $plugin_slug ) {
        return wp_nonce_url(
            add_query_arg(
                array(
                    'action' => 'install-plugin',
                    'plugin' => $plugin_slug,
                ),
                admin_url( 'update.php' )
            ),
            'install-plugin_' . $plugin_slug
        );
    }

    /**
     * Enqueue scripts for the notice
     */
    public function enqueue_notice_scripts() {
        if ( ! $this->should_display_notice() ) {
            return;
        }

        wp_enqueue_script( 'jquery' );
        wp_add_inline_script( 'jquery', '
            jQuery(document).ready(function($) {
                $(document).on("click", ".accepta-welcome-notice .notice-dismiss", function() {
                    $.ajax({
                        url: ajaxurl,
                        type: "POST",
                        data: {
                            action: "accepta_dismiss_welcome_notice",
                            nonce: "' . wp_create_nonce( 'accepta_dismiss_welcome_notice' ) . '"
                        }
                    });
                });
            });
        ' );
    }

    /**
     * Handle AJAX request to dismiss the notice
     */
    public function dismiss_welcome_notice() {
        // Verify nonce
        if ( ! wp_verify_nonce( $_POST['nonce'], 'accepta_dismiss_welcome_notice' ) ) {
            wp_die( 'Security check failed' );
        }

        // Check user capabilities
        if ( ! current_user_can( 'manage_options' ) ) {
            wp_die( 'Insufficient permissions' );
        }

        // Update option to mark notice as dismissed
        update_option( $this->notice_option_key, true );
        
        wp_die();
    }
     
}

// Initialize the admin class
Accepta_Admin_Welcome_Notice::instance(); 