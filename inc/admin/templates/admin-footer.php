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
                    '<a href="' . esc_url( $accepta_admin->add_utm_params( 'https://wpdino.com', 'footer_brand_link' ) ) . '" target="_blank">WPDINO</a>' 
                ); ?>
            </p>
        </div>
        <div class="accepta-admin-footer-right">
			<a href="https://wordpress.org/support/theme/accepta/" target="_blank"><?php esc_html_e( 'Support', 'accepta' ); ?></a> <span>|</span>
            <a href="<?php echo esc_url( admin_url( 'customize.php' ) ); ?>"><?php esc_html_e( 'Customize', 'accepta' ); ?></a>


			<?php
			// Social links (Facebook, X, Instagram).
			// X uses an inline SVG because Dashicons ships no X brand mark.
			printf(
				'<div class="accepta-admin-footer-social">
					<a href="%1$s" target="_blank" aria-label="%4$s"><span class="dashicons dashicons-facebook-alt"></span></a>
					<a href="%2$s" target="_blank" aria-label="%5$s"><svg class="accepta-admin-social-svg" viewBox="0 0 512 512" width="16" height="16" fill="currentColor" aria-hidden="true" focusable="false"><path d="M389.2 48h70.6L305.6 224.2 487 464H345L233.7 318.6 106.5 464H35.8L200.7 275.5 26.8 48H172.4L273.9 180.9 389.2 48zM364.4 421.8h39.1L151.1 88h-42l255.3 333.8z"/></svg></a>
					<a href="%3$s" target="_blank" aria-label="%6$s"><span class="dashicons dashicons-camera"></span></a>
				</div>',
				esc_url( 'https://www.facebook.com/wpdinocom' ),
				esc_url( 'https://x.com/wpdinocom' ),
				esc_url( 'https://www.instagram.com/_wpdino_/' ),
				esc_attr__( 'Follow WPDINO on Facebook', 'accepta' ),
				esc_attr__( 'Follow WPDINO on X (Twitter)', 'accepta' ),
				esc_attr__( 'Follow WPDINO on Instagram', 'accepta' )
			);
			?>
        </div>
    </div>
</div> 