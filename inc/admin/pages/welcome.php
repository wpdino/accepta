<?php
/**
 * Accepta Theme Welcome Page
 *
 * @package Accepta
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Get the admin class instance for reference if needed
$admin = Accepta_Admin::instance();
$theme_info = $admin->get_theme_info();
$menus = $admin->get_module('menus');
?>

<div class="wrap accepta-admin-wrap">
	<h1 class="notices-hook"></h1>
    <div class="accepta-header">
        <div class="accepta-header-content">
			<div class="accepta-header-content-left">
            <h1>
                <?php 
					/* translators: %s: theme name. */
					printf( esc_html__( 'Welcome to %s!', 'accepta' ), $theme_info['name'] ); 
				?>
            </h1>
				<p class="accepta-subtitle">
					<?php esc_html_e( 'Thank you for choosing Accepta. You’re just a few steps away from building a fast, modern website.', 'accepta' ); ?>
				</p>
			</div>
			<div class="accepta-header-content-right">
				<span class="accepta-theme-version">
					<?php
					/* translators: %s: theme version number. */
					printf( esc_html__( 'v. %s', 'accepta' ), $theme_info['version'] );
					?>
				</span>
			</div>
        </div>
    </div>

    <div class="accepta-welcome-content">
        <div class="accepta-welcome-section">
            <div class="accepta-welcome-column">
                <h2><?php esc_html_e( 'Getting Started', 'accepta' ); ?></h2>
                <p><?php esc_html_e( 'Thank you for choosing Accepta! This modern and versatile WordPress theme is designed to make your website stand out. Explore the features and options below to get started.', 'accepta' ); ?></p>
                
                <div class="accepta-feature-section">
                    <h3><?php esc_html_e( 'Theme Customization', 'accepta' ); ?></h3>
                    <p><?php esc_html_e( 'Customize your site\'s appearance through the WordPress Customizer. Change colors, layouts, and more to match your brand.', 'accepta' ); ?></p>
                    <a href="<?php echo esc_url( admin_url( 'customize.php' ) ); ?>" class="button button-primary"><?php esc_html_e( 'Customize Your Site', 'accepta' ); ?></a>
                </div>

                <div class="accepta-feature-section accepta-pro-promo">
                    <h3><span class="dashicons dashicons-star-filled" aria-hidden="true"></span><?php esc_html_e( 'Accepta PRO — Coming Soon', 'accepta' ); ?></h3>
                    <p><?php esc_html_e( 'More premium features, extra layouts, and dedicated support are on the way. Stay tuned!', 'accepta' ); ?></p>
                    <p class="accepta-pro-newsletter">
                        <?php
                        echo wp_kses_post(
                            sprintf(
                                /* translators: %s: link to WPDINO newsletter signup */
                                __( 'Be the first to know when Accepta PRO launches! %s for exclusive updates, tips, and early-access offers.', 'accepta' ),
                                '<a href="' . esc_url( $admin->add_utm_params( 'https://wpdino.com', 'pro_newsletter_cta' ) ) . '" target="_blank" rel="noopener noreferrer" class="accepta-pro-newsletter-link">' . esc_html__( 'Subscribe to our newsletter', 'accepta' ) . '</a>'
                            )
                        );
                        ?>
                    </p>
                </div>

            </div>
            
            <div class="accepta-welcome-column">
                <img src="<?php echo esc_url( get_template_directory_uri() . '/screenshot.png' ); ?>" alt="<?php esc_attr_e( 'Accepta Theme', 'accepta' ); ?>" class="accepta-welcome-screenshot">
            </div>
        </div>
        
        <div class="accepta-welcome-features">
            <h2><?php esc_html_e( 'Theme Features', 'accepta' ); ?></h2>
            
            <div class="accepta-features-grid">
                <div class="accepta-feature-item">
                    <span class="dashicons dashicons-admin-appearance"></span>
                    <h3><?php esc_html_e( 'Responsive Design', 'accepta' ); ?></h3>
                    <p><?php esc_html_e( 'Looks great on all devices, from mobile phones to desktop computers.', 'accepta' ); ?></p>
                </div>
                
                <div class="accepta-feature-item">
                    <span class="dashicons dashicons-performance"></span>
                    <h3><?php esc_html_e( 'Fast Loading', 'accepta' ); ?></h3>
                    <p><?php esc_html_e( 'Optimized code for better performance and faster page loading times.', 'accepta' ); ?></p>
                </div>
                
                <div class="accepta-feature-item">
                    <span class="dashicons dashicons-welcome-widgets-menus"></span>
                    <h3><?php esc_html_e( 'Widget Areas', 'accepta' ); ?></h3>
                    <p><?php esc_html_e( 'Multiple widget areas to customize your site\'s sidebar and footer.', 'accepta' ); ?></p>
                </div>
                
                <div class="accepta-feature-item">
                    <span class="dashicons dashicons-admin-customizer"></span>
                    <h3><?php esc_html_e( 'Customization Options', 'accepta' ); ?></h3>
                    <p><?php esc_html_e( 'Extensive options in the WordPress Customizer to personalize your site.', 'accepta' ); ?></p>
                </div>
                
                <div class="accepta-feature-item">
                    <span class="dashicons dashicons-translation"></span>
                    <h3><?php esc_html_e( 'Translation Ready', 'accepta' ); ?></h3>
                    <p><?php esc_html_e( 'Fully translatable to create a website in your language.', 'accepta' ); ?></p>
                </div>
                
                <div class="accepta-feature-item">
                    <span class="dashicons dashicons-cart"></span>
                    <h3><?php esc_html_e( 'WooCommerce Compatible', 'accepta' ); ?></h3>
                    <p><?php esc_html_e( 'Ready for e-commerce with WooCommerce integration.', 'accepta' ); ?></p>
                </div>

                <div class="accepta-feature-item">
                    <span class="dashicons dashicons-layout"></span>
                    <h3><?php esc_html_e( 'Advanced Layouts', 'accepta' ); ?></h3>
                    <p><?php esc_html_e( 'Flexible layout options with various header and footer styles.', 'accepta' ); ?></p>
                </div>
                
                <div class="accepta-feature-item">
                    <span class="dashicons dashicons-shield"></span>
                    <h3><?php esc_html_e( 'SEO Optimized', 'accepta' ); ?></h3>
                    <p><?php esc_html_e( 'Built with best SEO practices to help your site rank higher in search engines.', 'accepta' ); ?></p>
                </div>
                
                <div class="accepta-feature-item">
                    <span class="dashicons dashicons-editor-table"></span>
                    <h3><?php esc_html_e( 'Elementor Integration', 'accepta' ); ?></h3>
                    <p><?php esc_html_e( 'Full compatibility with Elementor Page Builder for a powerful and intuitive design experience.', 'accepta' ); ?></p>
                </div>

                <div class="accepta-feature-item">
                    <span class="dashicons dashicons-universal-access"></span>
                    <h3><?php esc_html_e( 'Accessibility Ready', 'accepta' ); ?></h3>
                    <p><?php esc_html_e( 'Designed with accessibility in mind so your site is usable for everyone.', 'accepta' ); ?></p>
                </div>
            </div>
        </div>
        
        <div class="accepta-welcome-actions">
            <a href="<?php echo esc_url( admin_url( 'admin.php?page=accepta-plugins' ) ); ?>" class="button button-primary"><?php esc_html_e( 'Install Recommended Plugins', 'accepta' ); ?></a>
        </div>
        
        <div class="accepta-next-steps">
            <h2><?php esc_html_e( 'Next Steps', 'accepta' ); ?></h2>
            <p><?php esc_html_e( 'Follow these steps to get the most out of your new Accepta theme:', 'accepta' ); ?></p>
            
            <div class="accepta-steps-grid">
                <div class="accepta-step-item">
                    <span class="step-number">1</span>
                    <h3><?php esc_html_e( 'Essential Setup', 'accepta' ); ?></h3>
                    <p><?php esc_html_e( 'Configure your site logo, colors, and basic settings to match your brand.', 'accepta' ); ?></p>
                    <a href="<?php echo esc_url( admin_url( 'customize.php?autofocus[section]=title_tagline' ) ); ?>" class="button"><?php esc_html_e( 'Site Identity', 'accepta' ); ?></a>
                </div>
                
                <div class="accepta-step-item">
                    <span class="step-number">2</span>
                    <h3><?php esc_html_e( 'Navigation Setup', 'accepta' ); ?></h3>
                    <p><?php esc_html_e( 'Create and configure your site\'s navigation menus for optimal user experience.', 'accepta' ); ?></p>
                    <a href="<?php echo esc_url( admin_url( 'nav-menus.php' ) ); ?>" class="button"><?php esc_html_e( 'Manage Menus', 'accepta' ); ?></a>
                </div>
                
                <div class="accepta-step-item">
                    <span class="step-number">3</span>
                    <h3><?php esc_html_e( 'Widget Areas', 'accepta' ); ?></h3>
                    <p><?php esc_html_e( 'Add content to your sidebar and footer areas through the Widgets screen.', 'accepta' ); ?></p>
                    <a href="<?php echo esc_url( admin_url( 'widgets.php' ) ); ?>" class="button"><?php esc_html_e( 'Add Widgets', 'accepta' ); ?></a>
                </div>
                
                <div class="accepta-step-item">
                    <span class="step-number">4</span>
                    <h3><?php esc_html_e( 'Advanced Options', 'accepta' ); ?></h3>
                    <p><?php esc_html_e( 'Explore all customization options to fine-tune your site to perfection.', 'accepta' ); ?></p>
                    <a href="<?php echo esc_url( admin_url( 'customize.php' ) ); ?>" class="button button-primary button-hero"><?php esc_html_e( 'Customize Your Site', 'accepta' ); ?></a>
                </div>
            </div>
        </div>
    </div>
    
    <?php include_once get_template_directory() . '/inc/admin/templates/admin-footer.php'; ?>
</div> 