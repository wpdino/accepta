<?php
/**
 * Accepta Theme Admin Plugins Class
 *
 * @package Accepta
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Admin Plugins Class
 * 
 * This class handles plugin recommendations and related functionality
 */
class Accepta_Admin_Plugins {
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
     * Recommended plugins
     *
     * @var array
     */
    private $recommended_plugins = array();

    /**
     * Main Plugins Instance
     *
     * Ensures only one instance of the plugins class is loaded or can be loaded.
     *
     * @param Accepta_Admin $parent Parent admin class instance.
     * @return Accepta_Admin_Plugins - Main instance
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

        add_action( 'wp_ajax_accepta_install_activate_plugin', array( $this, 'ajax_install_activate_plugin' ) );
        add_action( 'admin_enqueue_scripts', array( $this, 'localize_plugins_script' ), 20 );
        
        // Define recommended plugins
        $this->recommended_plugins = array(
            'elementor' => array(
                'name' => 'Elementor Page Builder',
                'slug' => 'elementor',
                'description' => esc_html__( 'Create beautiful designs with a powerful, responsive page builder. Elementor is essential for the full Accepta theme experience.', 'accepta' ),
                'icon' => 'elementor.png',
                'required' => false,
            ),
            'dinopack-for-elementor' => array(
                'name' => 'DinoPack for Elementor',
                'slug' => 'dinopack-for-elementor',
                'description' => esc_html__( 'Advanced Elementor widgets: animated headings, blog displays, galleries, WooCommerce products, and more.', 'accepta' ),
                'icon' => '',
                'required' => false,
            ),
            'contact-form-7' => array(
                'name' => 'Contact Form 7',
                'slug' => 'contact-form-7',
                'description' => esc_html__( 'Create customizable contact forms and manage multiple contact forms.', 'accepta' ),
                'icon' => 'cf7.png',
                'required' => false,
            ),
            'woocommerce' => array(
                'name' => 'WooCommerce',
                'slug' => 'woocommerce',
                'description' => esc_html__( 'Add eCommerce functionality to your website with the world\'s favorite online shop solution.', 'accepta' ),
                'icon' => 'woo.png',
                'required' => false,
            ),
        );
        
        // Hook into admin notices for plugin recommendations
        add_action( 'admin_notices', array( $this, 'display_plugin_recommendations' ) );
    }
    
    /**
     * Get all recommended plugins
     *
     * @return array Recommended plugins
     */
    public function get_recommended_plugins() {
        return $this->recommended_plugins;
    }
    
    /**
     * Get a specific recommended plugin
     *
     * @param string $slug Plugin slug.
     * @return array|null Plugin data or null if not found.
     */
    public function get_plugin( $slug ) {
        return isset( $this->recommended_plugins[ $slug ] ) ? $this->recommended_plugins[ $slug ] : null;
    }

    /**
     * Get plugin information from the WordPress.org API (cached one day).
     *
     * @param string $slug Plugin slug.
     * @return array|null Associative array with name, description, icon, version, homepage, or null on failure.
     */
    public function get_plugin_info_from_repo( $slug ) {
        $api = $this->get_plugins_api( $slug );
        if ( is_wp_error( $api ) || ! $api ) {
            return null;
        }
        $icon = '';
        if ( ! empty( $api->icons['2x'] ) ) {
            $icon = $api->icons['2x'];
        } elseif ( ! empty( $api->icons['1x'] ) ) {
            $icon = $api->icons['1x'];
        } elseif ( ! empty( $api->icons['default'] ) ) {
            $icon = $api->icons['default'];
        }
        return array(
            'name'        => isset( $api->name ) ? $api->name : '',
            'description' => isset( $api->short_description ) ? $api->short_description : '',
            'icon'        => $icon,
            'version'     => isset( $api->version ) ? $api->version : '',
            'homepage'    => isset( $api->homepage ) ? $api->homepage : '',
        );
    }

    /**
     * Get plugin info from WordPress.org API (raw). Cached for one day.
     *
     * @param string $slug Plugin slug.
     * @return object|WP_Error|null Plugin info object, WP_Error, or null.
     */
    protected function get_plugins_api( $slug ) {
        $cache_key = 'accepta_plugins_api_' . $slug;
        $cached    = get_transient( $cache_key );
        if ( false !== $cached && ( is_object( $cached ) || is_wp_error( $cached ) ) ) {
            return $cached;
        }
        if ( ! function_exists( 'plugins_api' ) ) {
            require_once ABSPATH . 'wp-admin/includes/plugin-install.php';
        }
        $response = plugins_api(
            'plugin_information',
            array(
                'slug'   => $slug,
                'fields' => array(
                    'sections'            => false,
                    'icons'               => true,
                    'banners'              => false,
                    'short_description'   => true,
                    'rating'              => false,
                    'num_ratings'         => false,
                    'active_installs'     => false,
                ),
            )
        );
        if ( ! is_wp_error( $response ) ) {
            set_transient( $cache_key, $response, DAY_IN_SECONDS );
        }
        return $response;
    }

    /**
     * Get download URL for a plugin from WordPress.org.
     *
     * @param string $slug Plugin slug.
     * @return string Download URL or empty string.
     */
    protected function get_wp_repo_download_url( $slug ) {
        $api = $this->get_plugins_api( $slug );
        if ( is_wp_error( $api ) || ! isset( $api->download_link ) ) {
            return '';
        }
        return $api->download_link;
    }

    /**
     * AJAX handler: install and/or activate a plugin.
     */
    public function ajax_install_activate_plugin() {
        if ( ! isset( $_POST['security'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['security'] ) ), 'accepta_install_activate_plugin' ) ) {
            wp_send_json_error( __( 'Security check failed. Please refresh and try again.', 'accepta' ) );
        }
        if ( ! current_user_can( 'install_plugins' ) ) {
            wp_send_json_error( __( 'You do not have permission to install plugins.', 'accepta' ) );
        }
        $slug = isset( $_POST['slug'] ) ? sanitize_key( wp_unslash( $_POST['slug'] ) ) : '';
        if ( empty( $slug ) ) {
            wp_send_json_error( __( 'Plugin slug is missing.', 'accepta' ) );
        }
        if ( ! isset( $this->recommended_plugins[ $slug ] ) ) {
            wp_send_json_error( __( 'This plugin is not in the recommended list.', 'accepta' ) );
        }

        $status = $this->is_plugin_installed( $slug );
        if ( $status['active'] ) {
            wp_send_json_success( __( 'Plugin is already active.', 'accepta' ) );
        }
        if ( $status['installed'] && ! empty( $status['path'] ) ) {
            $result = activate_plugin( $status['path'] );
            if ( is_wp_error( $result ) ) {
                wp_send_json_error( $result->get_error_message() );
            }
            wp_send_json_success( __( 'Plugin activated.', 'accepta' ) );
        }

        // Install from WordPress.org.
        remove_action( 'upgrader_process_complete', array( 'Language_Pack_Upgrader', 'async_upgrade' ), 20 );
        $source = $this->get_wp_repo_download_url( $slug );
        if ( empty( $source ) ) {
            wp_send_json_error( __( 'Could not get plugin download URL.', 'accepta' ) );
        }
        $api = $this->get_plugins_api( $slug );
        if ( is_wp_error( $api ) ) {
            wp_send_json_error( $api->get_error_message() );
        }

        require_once get_template_directory() . '/inc/admin/class-accepta-plugin-installer-skin.php';
        if ( ! class_exists( 'Plugin_Upgrader', false ) ) {
            require_once ABSPATH . 'wp-admin/includes/class-wp-upgrader.php';
        }
        $extra = array( 'slug' => $slug );
        $skin  = new Accepta_Plugin_Installer_Skin( array(
            'type'   => 'web',
            'plugin' => '',
            'api'    => $api,
            'extra'  => $extra,
        ) );
        $upgrader = new Plugin_Upgrader( $skin );
        $upgrader->install( $source );
        wp_cache_flush();

        $plugin_basename = $upgrader->plugin_info();
        if ( ! $plugin_basename ) {
            wp_send_json_error( __( 'Installation failed.', 'accepta' ) );
        }
        $result = activate_plugin( $plugin_basename );
        if ( is_wp_error( $result ) ) {
            wp_send_json_error( $result->get_error_message() );
        }
        wp_send_json_success( __( 'Plugin installed and activated.', 'accepta' ) );
    }

    /**
     * Localize script for the plugins page (nonce and strings).
     *
     * @param string $hook Current admin page hook.
     */
    public function localize_plugins_script( $hook ) {
        if ( strpos( $hook, 'accepta-plugins' ) === false ) {
            return;
        }
        wp_localize_script( 'accepta-admin-script', 'accepta_plugins_vars', array(
            'nonce'          => wp_create_nonce( 'accepta_install_activate_plugin' ),
            'installing'     => __( 'Installing...', 'accepta' ),
            'activating'     => __( 'Activating...', 'accepta' ),
            'active'         => __( 'Active', 'accepta' ),
            'install'        => __( 'Install', 'accepta' ),
            'activate'       => __( 'Activate', 'accepta' ),
            'installSelected' => __( 'Install & Activate Selected Plugins', 'accepta' ),
        ) );
    }
    
    /**
     * Check if a plugin is installed and its status
     *
     * @param string $slug Plugin slug.
     * @return array Plugin status.
     */
    public function is_plugin_installed( $slug ) {
        if ( ! function_exists( 'get_plugins' ) ) {
            require_once ABSPATH . 'wp-admin/includes/plugin.php';
        }
        
        $all_plugins = get_plugins();
        
        foreach ( $all_plugins as $plugin_path => $plugin ) {
            $plugin_file = basename( $plugin_path, '.php' );
            if ( $plugin_file === $slug || strpos( $plugin_path, $slug . '/' ) === 0 ) {
                return array(
                    'installed' => true,
                    'active' => is_plugin_active( $plugin_path ),
                    'path' => $plugin_path,
                );
            }
        }
        
        return array(
            'installed' => false,
            'active' => false,
            'path' => '',
        );
    }
    
    /**
     * Display plugin recommendations in admin notices
     */
    public function display_plugin_recommendations() {

        // Only show on dashboard
        $screen = get_current_screen();

		// Only show on dashboard
        if ( $screen->id !== 'dashboard' ) {
            return;
        }		

        // Check if the notice has been dismissed
        if ( get_user_meta( get_current_user_id(), 'accepta_plugin_notice_dismissed', true ) ) {
            return;
        }
        
        // Count inactive recommended plugins
        $recommended_count = 0;
        foreach ( $this->recommended_plugins as $slug => $plugin ) {
            if ( $plugin['required'] && ! $this->is_plugin_installed( $slug )['active'] ) {
                $recommended_count++;
            }
        }
        
        // If there are required plugins that are not active, display notice
        if ( $recommended_count > 0 ) {
            ?>
            <div class="notice notice-info is-dismissible accepta-plugin-notice">
                <p>
                    <?php 
                    if ( $recommended_count === 1 ) {
                        esc_html_e( 'Accepta Theme recommends one plugin to enhance your site.', 'accepta' );
                    } else {
                        printf( 
                            esc_html__( 'Accepta Theme recommends %d plugins to enhance your site.', 'accepta' ),
                            $recommended_count
                        );
                    }
                    ?>
                    <a href="<?php echo esc_url( admin_url( 'admin.php?page=accepta-plugins' ) ); ?>" class="button button-primary" style="margin-left: 10px;">
                        <?php esc_html_e( 'View Recommended Plugins', 'accepta' ); ?>
                    </a>
                </p>
            </div>
            <script>
                jQuery(document).ready(function($) {
                    $(document).on('click', '.accepta-plugin-notice .notice-dismiss', function() {
                        $.ajax({
                            url: ajaxurl,
                            data: {
                                action: 'accepta_dismiss_plugin_notice'
                            }
                        });
                    });
                });
            </script>
            <?php
        }
    }
    
    /**
     * Render the plugins page
     */
    public function plugins_page() {
        require_once get_template_directory() . '/inc/admin/pages/plugins.php';
    }
} 