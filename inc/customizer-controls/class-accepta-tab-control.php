<?php
/**
 * Accepta Tab Control Class
 * 
 * Custom control for tab-based selection (used for background type)
 *
 * @package Accepta
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Exit if WP_Customize_Control doesn't exist (not in customizer context)
if ( ! class_exists( 'WP_Customize_Control' ) ) {
	return;
}

/**
 * Accepta Tab Control Class
 */
class Accepta_Tab_Control extends WP_Customize_Control {

	/**
	 * Control type
	 *
	 * @var string
	 */
	public $type = 'accepta-tab';

	/**
	 * Tab choices
	 *
	 * @var array
	 */
	public $choices = array();

	/**
	 * Enqueue scripts and styles
	 */
	public function enqueue() {
		wp_enqueue_style(
			'accepta-tab-control',
			get_template_directory_uri() . '/inc/customizer-controls/css/tab-control.css',
			array(),
			_ACCEPTA_VERSION
		);
		wp_enqueue_script(
			'accepta-tab-control',
			get_template_directory_uri() . '/inc/customizer-controls/js/tab-control.js',
			array( 'jquery', 'customize-controls' ),
			_ACCEPTA_VERSION,
			true
		);
	}

	/**
	 * Render the control's content
	 */
	public function render_content() {
		if ( empty( $this->choices ) ) {
			return;
		}

		$value = $this->value();
		?>
		<label>
			<?php if ( ! empty( $this->label ) ) : ?>
				<span class="customize-control-title"><?php echo esc_html( $this->label ); ?></span>
			<?php endif; ?>
			<?php if ( ! empty( $this->description ) ) : ?>
				<span class="description customize-control-description"><?php echo esc_html( $this->description ); ?></span>
			<?php endif; ?>
		</label>
		<div class="accepta-tab-control customize-control-accepta-tab" data-control-id="<?php echo esc_attr( $this->id ); ?>">
			<div class="accepta-tab-buttons">
				<?php foreach ( $this->choices as $key => $label ) : ?>
					<button type="button" 
							class="accepta-tab-button <?php echo ( $value === $key ) ? 'active' : ''; ?>" 
							data-tab="<?php echo esc_attr( $key ); ?>"
							aria-label="<?php echo esc_attr( $label ); ?>">
						<?php echo esc_html( $label ); ?>
					</button>
				<?php endforeach; ?>
			</div>
			<input type="hidden" <?php $this->link(); ?> value="<?php echo esc_attr( $value ); ?>" />
		</div>
		<?php
	}
}

