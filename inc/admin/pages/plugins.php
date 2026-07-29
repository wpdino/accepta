<?php
/**
 * Accepta Theme Plugins Page
 *
 * @package Accepta
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$admin           = Accepta_Admin::instance();
$plugins_module  = $admin->get_module( 'plugins' );
$accepta_plugins = $plugins_module->get_recommended_plugins();

// Merge WP.org repo info for each plugin (name, description, icon). Cached for one day.
foreach ( $accepta_plugins as $slug => $plugin ) {
	$repo = $plugins_module->get_plugin_info_from_repo( $slug );
	if ( $repo ) {
		if ( ! empty( $repo['name'] ) ) {
			$accepta_plugins[ $slug ]['name'] = $repo['name'];
		}
		if ( ! empty( $repo['description'] ) ) {
			$accepta_plugins[ $slug ]['description'] = $repo['description'];
		}
		if ( ! empty( $repo['icon'] ) ) {
			$accepta_plugins[ $slug ]['icon_url'] = $repo['icon'];
		}
		$accepta_plugins[ $slug ]['homepage'] = isset( $repo['homepage'] ) ? $repo['homepage'] : '';
	} else {
		$accepta_plugins[ $slug ]['icon_url'] = '';
		$accepta_plugins[ $slug ]['homepage'] = '';
	}
}
?>

<div class="wrap accepta-admin-wrap">
	<h1 class="notices-hook"></h1>
	<div class="accepta-header">
		<div class="accepta-header-content">
			<div class="accepta-header-content-left">
			<h1><?php esc_html_e( 'Recommended Plugins', 'accepta' ); ?></h1>
				<p class="about-description"><?php esc_html_e( 'Enhance your Accepta theme with these recommended plugins. Install or activate them with one click.', 'accepta' ); ?></p>
			</div>
		</div>
	</div>

	<div class="accepta-plugins-toolbar">
		<div class="accepta-plugins-toolbar-primary">
			<p class="accepta-plugins-toolbar-desc"><?php esc_html_e( 'Select the plugins you want to install, then click the button below to install and activate them.', 'accepta' ); ?></p>
			<button type="button" class="button button-primary js-accepta-install-selected" id="accepta-install-selected-btn">
				<?php esc_html_e( 'Install & Activate Selected Plugins', 'accepta' ); ?>
			</button>
		</div>
	</div>

	<div class="accepta-card">
		<div class="accepta-plugins-wrap">
			<div class="accepta-plugins-grid">
				<?php
				foreach ( $accepta_plugins as $plugin_slug => $plugin ) :
					$plugin_status = $plugins_module->is_plugin_installed( $plugin_slug );
					$is_active     = $plugin_status['active'];
					$is_installed  = $plugin_status['installed'];
					$button_text   = $is_installed ? esc_html__( 'Activate', 'accepta' ) : esc_html__( 'Install', 'accepta' );

					if ( $is_active ) {
						$status_class = 'accepta-plugin-state--active';
						$status_label = esc_html__( 'Active', 'accepta' );
						$item_state   = 'accepta-plugin-item--active';
					} elseif ( $is_installed ) {
						$status_class = 'accepta-plugin-state--installed';
						$status_label = esc_html__( 'Installed', 'accepta' );
						$item_state   = 'accepta-plugin-item--installed';
					} else {
						$status_class = 'accepta-plugin-state--missing';
						$status_label = esc_html__( 'Not installed', 'accepta' );
						$item_state   = 'accepta-plugin-item--missing';
					}
					?>
					<div class="accepta-plugin-item accepta-plugin-item-<?php echo esc_attr( $plugin_slug ); ?> <?php echo esc_attr( $item_state ); ?>">
						<?php if ( ! $is_active ) : ?>
							<label class="accepta-plugin-checkbox-label">
								<input type="checkbox" class="accepta-plugin-checkbox" name="<?php echo esc_attr( $plugin_slug ); ?>" value="1" data-slug="<?php echo esc_attr( $plugin_slug ); ?>" checked>
								<span class="accepta-plugin-checkbox-box" aria-hidden="true"></span>
								<span class="screen-reader-text"><?php echo esc_html( sprintf( /* translators: %s: plugin name */ __( 'Include %s', 'accepta' ), $plugin['name'] ) ); ?></span>
							</label>
						<?php else : ?>
							<span class="accepta-plugin-checkbox-label accepta-plugin-checkbox-label--disabled" aria-hidden="true">
								<span class="accepta-plugin-checkbox-box accepta-plugin-checkbox-box--checked"></span>
							</span>
							<span class="screen-reader-text"><?php echo esc_html( sprintf( /* translators: %s: plugin name */ __( '%s is already active', 'accepta' ), $plugin['name'] ) ); ?></span>
						<?php endif; ?>

						<div class="accepta-plugin-state <?php echo esc_attr( $status_class ); ?>">
							<span class="accepta-plugin-state-dot" aria-hidden="true"></span>
							<span class="accepta-plugin-state-label"><?php echo esc_html( $status_label ); ?></span>
						</div>

						<div class="accepta-plugin-body">
							<div class="accepta-plugin-icon">
								<?php if ( ! empty( $plugin['icon_url'] ) ) : ?>
									<img src="<?php echo esc_url( $plugin['icon_url'] ); ?>" alt="<?php echo esc_attr( $plugin['name'] ); ?>" width="64" height="64">
								<?php elseif ( ! empty( $plugin['icon'] ) && file_exists( get_template_directory() . '/inc/admin/assets/images/plugins/' . $plugin['icon'] ) ) : ?>
									<img src="<?php echo esc_url( get_template_directory_uri() . '/inc/admin/assets/images/plugins/' . $plugin['icon'] ); ?>" alt="<?php echo esc_attr( $plugin['name'] ); ?>">
								<?php else : ?>
									<span class="dashicons dashicons-admin-plugins"></span>
								<?php endif; ?>
							</div>

							<div class="accepta-plugin-info">
								<h3><?php echo esc_html( $plugin['name'] ); ?></h3>
								<p><?php echo wp_kses_post( $plugin['description'] ); ?></p>
								<div class="accepta-plugin-item-error" role="alert" aria-live="polite"></div>
							</div>
						</div>

						<?php if ( ! $is_active || ! empty( $plugin['required'] ) ) : ?>
							<div class="accepta-plugin-actions">
								<?php if ( ! $is_active ) : ?>
									<div class="accepta-plugin-loading-state" aria-hidden="true">
										<span class="accepta-plugin-spinner"></span>
										<span class="accepta-plugin-loading-text"></span>
									</div>
									<button type="button"
											class="accepta-plugin-action-link js-accepta-plugin-btn"
											data-slug="<?php echo esc_attr( $plugin_slug ); ?>"
											data-initial-text="<?php echo esc_attr( $button_text ); ?>">
										<?php echo esc_html( $button_text ); ?>
									</button>
								<?php endif; ?>
								<?php if ( ! empty( $plugin['required'] ) ) : ?>
									<span class="accepta-plugin-status required"><?php esc_html_e( 'Required', 'accepta' ); ?></span>
								<?php endif; ?>
							</div>
						<?php endif; ?>
					</div>
				<?php endforeach; ?>
			</div>
		</div>
	</div>

	<?php include_once get_template_directory() . '/inc/admin/templates/admin-footer.php'; ?>
</div>
