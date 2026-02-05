<?php
/**
 * Accepta Theme Demo Import Page
 *
 * @package Accepta
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Define the demo sites
$accepta_demos = array(
    'main' => array(
        'name' => esc_html__( 'Main Demo', 'accepta' ),
        'description' => esc_html__( 'Our main demo showcases all the features of Accepta theme with beautiful Elementor layouts.', 'accepta' ),
        'screenshot' => 'demo-main.jpg',
        'preview_url' => '',
    ),
    'business' => array(
        'name' => esc_html__( 'Business Demo', 'accepta' ),
        'description' => esc_html__( 'Ideal for corporate websites with professional Elementor templates and business-focused sections.', 'accepta' ),
        'screenshot' => 'demo-business.jpg',
        'preview_url' => '',
    ),
    'portfolio' => array(
        'name' => esc_html__( 'Portfolio Demo', 'accepta' ),
        'description' => esc_html__( 'Perfect for creatives with custom Elementor gallery layouts and portfolio showcases.', 'accepta' ),
        'screenshot' => 'demo-portfolio.jpg',
        'preview_url' => '',
    ),
    'shop' => array(
        'name' => esc_html__( 'Shop Demo', 'accepta' ),
        'description' => esc_html__( 'Complete e-commerce setup with Elementor product layouts and WooCommerce integration.', 'accepta' ),
        'screenshot' => 'demo-shop.jpg',
        'preview_url' => '',
    ),
);

// Check if One Click Demo Import plugin is installed and active
$ocdi_installed = class_exists( 'OCDI_Plugin' );
?>

<div class="wrap accepta-admin-wrap">
    <div class="accepta-header">
        <h1><?php esc_html_e( 'Demo Content Import', 'accepta' ); ?></h1>
        <p class="about-description"><?php esc_html_e( 'Import demo content to get a head start with your website. Choose from our pre-built Elementor layouts and customize them to fit your needs.', 'accepta' ); ?></p>
    </div>

    <?php if ( ! $ocdi_installed ) : ?>
        <div class="accepta-notice notice-warning">
            <p>
                <?php 
                printf(
                    esc_html__( 'To import demo content, please install and activate the %sOne Click Demo Import%s plugin.', 'accepta' ),
                    '<a href="' . esc_url( admin_url( 'admin.php?page=accepta-plugins' ) ) . '">',
                    '</a>'
                ); 
                ?>
            </p>
        </div>
    <?php endif; ?>

    <div class="accepta-demos-wrap">
        <div class="accepta-demos-grid">
            <?php 
            // Define placeholder image
            $placeholder_image = get_template_directory_uri() . '/inc/admin/assets/images/placeholder-demo.jpg';
            if (!file_exists(get_template_directory() . '/inc/admin/assets/images/placeholder-demo.jpg')) {
                $placeholder_image = 'data:image/svg+xml;charset=UTF-8,%3Csvg%20width%3D%22500%22%20height%3D%22300%22%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20viewBox%3D%220%200%20500%20300%22%20preserveAspectRatio%3D%22none%22%3E%3Cdefs%3E%3Cstyle%20type%3D%22text%2Fcss%22%3E%23holder_1%20text%20%7B%20fill%3A%231abc9c%3Bfont-weight%3Abold%3Bfont-family%3AArial%2C%20Helvetica%2C%20sans-serif%3Bfont-size%3A25pt%20%7D%20%3C%2Fstyle%3E%3C%2Fdefs%3E%3Cg%20id%3D%22holder_1%22%3E%3Crect%20width%3D%22500%22%20height%3D%22300%22%20fill%3D%22%23e8f8f5%22%3E%3C%2Frect%3E%3Cg%3E%3Ctext%20x%3D%22155%22%20y%3D%22160%22%3EAccepta%20Demo%3C%2Ftext%3E%3C%2Fg%3E%3C%2Fg%3E%3C%2Fsvg%3E';
            }
            
            foreach ( $accepta_demos as $demo_id => $demo ) : 
            ?>
                <div class="accepta-demo-item">
                    <div class="accepta-demo-image">
                        <?php if ( file_exists( get_template_directory() . '/inc/admin/assets/images/demos/' . $demo['screenshot'] ) ) : ?>
                            <img src="<?php echo esc_url( get_template_directory_uri() . '/inc/admin/assets/images/demos/' . $demo['screenshot'] ); ?>" alt="<?php echo esc_attr( $demo['name'] ); ?>">
                        <?php else : ?>
                            <img src="<?php echo esc_url( $placeholder_image ); ?>" alt="<?php echo esc_attr( $demo['name'] ); ?>" class="placeholder-image">
                        <?php endif; ?>
                    </div>
                    
                    <div class="accepta-demo-content">
                        <h3 class="accepta-demo-title"><?php echo esc_html( $demo['name'] ); ?></h3>
                        <p class="accepta-demo-desc"><?php echo esc_html( $demo['description'] ); ?></p>
                        
                        <div class="accepta-demo-actions">
                            <a href="<?php echo esc_url( $demo['preview_url'] ); ?>" class="button button-secondary" target="_blank">
                                <?php esc_html_e( 'Preview', 'accepta' ); ?>
                            </a>
                            
                            <?php if ( $ocdi_installed ) : ?>
                                <a href="<?php echo esc_url( admin_url( 'themes.php?page=pt-one-click-demo-import' ) ); ?>" class="button button-primary">
                                    <?php esc_html_e( 'Import', 'accepta' ); ?>
                                </a>
                            <?php else : ?>
                                <button class="button button-primary" disabled>
                                    <?php esc_html_e( 'Import', 'accepta' ); ?>
                                </button>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
    
    <div class="accepta-notice notice-warning accepta-demos-warning">
        <h3><span class="dashicons dashicons-warning"></span> <?php esc_html_e( 'Important Notice', 'accepta' ); ?></h3>
        <p>
            <?php esc_html_e( 'Before importing a demo, we recommend the following:', 'accepta' ); ?>
        </p>
        <ul>
            <li><?php esc_html_e( 'Make sure you have Elementor and all other required plugins installed and activated.', 'accepta' ); ?></li>
            <li><?php esc_html_e( 'Import on a fresh WordPress installation to avoid conflicts with existing content.', 'accepta' ); ?></li>
            <li><?php esc_html_e( 'The import process may take several minutes depending on your server speed.', 'accepta' ); ?></li>
            <li><?php esc_html_e( 'Images and media from the demo site will be imported as placeholders due to copyright restrictions.', 'accepta' ); ?></li>
            <li><?php esc_html_e( 'After import, you may need to reconfigure some Elementor settings to match your preferences.', 'accepta' ); ?></li>
        </ul>
    </div>
	<?php include_once get_template_directory() . '/inc/admin/templates/admin-footer.php'; ?>
</div> 