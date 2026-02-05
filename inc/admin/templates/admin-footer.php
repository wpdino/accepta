<?php
/**
 * Admin Footer Template
 *
 * @package Accepta
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Get the admin class instance
$accepta_admin = Accepta_Admin::instance();
$theme_info = $accepta_admin->get_theme_info();

// Get current page info from screen
$screen = get_current_screen();
$current_page = '';
if ($screen && strpos($screen->id, 'accepta-') !== false) {
    $current_page = sanitize_title(str_replace('accepta-', '', $screen->id));
}
?>

<div class="accepta-admin-footer">
    <div class="accepta-admin-footer-content">
        <div class="accepta-admin-footer-left">
            <p>
                <?php 
                printf( 
                    /* translators: Accepta version and WPDINO link */
                    esc_html__( 'Accepta v%1$s by %2$s', 'accepta' ), 
                    esc_attr( $theme_info['version'] ),
                    '<a href="https://wpdino.com" target="_blank">WPDINO</a>' 
                ); ?>
            </p>
        </div>
        <div class="accepta-admin-footer-right">
            <a href="<?php echo esc_url( get_template_directory_uri() . '/readme.txt' ); ?>" target="_blank"><?php esc_html_e( 'Documentation', 'accepta' ); ?></a>
            <span>|</span>
            <a href="<?php echo esc_url( 'https://wordpress.org/support/themes/' ); ?>" target="_blank"><?php esc_html_e( 'Support', 'accepta' ); ?></a>
            <span>|</span>
            <a href="<?php echo esc_url( admin_url( 'customize.php' ) ); ?>"><?php esc_html_e( 'Customize', 'accepta' ); ?></a>
        </div>
    </div>
</div> 