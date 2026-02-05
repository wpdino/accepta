<?php
/**
 * Accepta Spacing Control Class
 * 
 * Custom control for advanced spacing with responsive and unit options
 * Similar to Elementor's spacing controls
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
 * Accepta Spacing Control Class
 */
class Accepta_Spacing_Control extends WP_Customize_Control {

	/**
	 * Control type
	 *
	 * @var string
	 */
	public $type = 'accepta-spacing';

	/**
	 * Control settings
	 *
	 * @var array
	 */
	public $settings_args = array();

	/**
	 * Units available
	 *
	 * @var array
	 */
	public $units = array( 'px', 'em', 'rem', '%' );

	/**
	 * Default unit
	 *
	 * @var string
	 */
	public $default_unit = 'px';

	/**
	 * Responsive breakpoints
	 *
	 * @var array
	 */
	public $responsive = true;

	/**
	 * Spacing sides
	 *
	 * @var array
	 */
	public $sides = array( 'top', 'right', 'bottom', 'left' );

	/**
	 * Constructor
	 */
	public function __construct( $manager, $id, $args = array() ) {
		parent::__construct( $manager, $id, $args );

		// Set default values
		if ( isset( $args['units'] ) ) {
			$this->units = $args['units'];
		}

		if ( isset( $args['default_unit'] ) ) {
			$this->default_unit = $args['default_unit'];
		}

		if ( isset( $args['responsive'] ) ) {
			$this->responsive = $args['responsive'];
		}

		if ( isset( $args['sides'] ) ) {
			$this->sides = $args['sides'];
		}
	}

	/**
	 * Enqueue control related scripts/styles
	 */
	public function enqueue() {
		wp_enqueue_script(
			'accepta-spacing-control',
			get_template_directory_uri() . '/inc/customizer-controls/js/spacing-control.js',
			array( 'jquery', 'customize-base' ),
			'1.0.0',
			true
		);

		wp_enqueue_style(
			'accepta-spacing-control',
			get_template_directory_uri() . '/inc/customizer-controls/css/spacing-control.css',
			array(),
			'1.0.0'
		);

		// Localize script for translations
		wp_localize_script(
			'accepta-spacing-control',
			'acceptaSpacingL10n',
			array(
				'confirmReset' => __( 'Are you sure you want to reset all spacing values to defaults?', 'accepta' ),
			)
		);
	}

	/**
	 * Render the control's content
	 */
	public function render_content() {
		$input_id = '_customize-input-' . $this->id;
		$description_id = '_customize-description-' . $this->id;
		$describedby_attr = ( ! empty( $this->description ) ) ? ' aria-describedby="' . esc_attr( $description_id ) . '"' : '';
		$value = $this->value();
		
		// Parse value if it's JSON
		if ( is_string( $value ) ) {
			$value = json_decode( $value, true );
		}
		
		// Set defaults
		if ( ! is_array( $value ) ) {
			$value = array();
		}

		$breakpoints = $this->responsive ? array( 'desktop', 'tablet', 'mobile' ) : array( 'desktop' );
		?>
		<div class="accepta-spacing-control-wrapper">
			<?php if ( ! empty( $this->label ) ) : ?>
				<span class="customize-control-title"><?php echo esc_html( $this->label ); ?></span>
			<?php endif; ?>
			
			<?php if ( ! empty( $this->description ) ) : ?>
				<span id="<?php echo esc_attr( $description_id ); ?>" class="description customize-control-description"><?php echo $this->description; ?></span>
			<?php endif; ?>

			<div class="accepta-spacing-control" data-control-id="<?php echo esc_attr( $this->id ); ?>">
				
				<div class="accepta-spacing-header">
					<?php if ( $this->responsive ) : ?>
						<div class="accepta-responsive-tabs">
							<button type="button" class="accepta-responsive-tab active" data-device="desktop">
								<i class="dashicons dashicons-desktop"></i>
							</button>
							<button type="button" class="accepta-responsive-tab" data-device="tablet">
								<i class="dashicons dashicons-tablet"></i>
							</button>
							<button type="button" class="accepta-responsive-tab" data-device="mobile">
								<i class="dashicons dashicons-smartphone"></i>
							</button>
						</div>
					<?php endif; ?>
					
					<div class="accepta-spacing-actions">
						<button type="button" class="accepta-spacing-reset-btn" title="<?php esc_attr_e( 'Reset to Default', 'accepta' ); ?>">
							<i class="dashicons dashicons-image-rotate"></i>
							<span class="screen-reader-text"><?php esc_html_e( 'Reset to Default', 'accepta' ); ?></span>
						</button>
					</div>
				</div>

				<?php foreach ( $breakpoints as $breakpoint ) : ?>
					<div class="accepta-spacing-device accepta-spacing-<?php echo esc_attr( $breakpoint ); ?>" <?php echo $breakpoint !== 'desktop' ? 'style="display:none;"' : ''; ?>>
						
						<div class="accepta-spacing-inputs">
							<div class="accepta-spacing-visual">
								<?php foreach ( $this->sides as $side ) : 
									$field_value = isset( $value[ $breakpoint ][ $side ] ) ? $value[ $breakpoint ][ $side ] : '';
									$side_labels = array(
										'top' => __( 'Top', 'accepta' ),
										'right' => __( 'Right', 'accepta' ),
										'bottom' => __( 'Bottom', 'accepta' ),
										'left' => __( 'Left', 'accepta' )
									);
								?>
									<div class="accepta-spacing-input-wrapper accepta-spacing-<?php echo esc_attr( $side ); ?>">
										<input 
											type="number" 
											class="accepta-spacing-input" 
											data-side="<?php echo esc_attr( $side ); ?>" 
											data-device="<?php echo esc_attr( $breakpoint ); ?>"
											value="<?php echo esc_attr( $field_value ); ?>"
											placeholder="0"
											min="0"
											step="1"
										/>
										<div class="accepta-spacing-input-label"><?php echo esc_html( $side_labels[ $side ] ); ?></div>
									</div>
								<?php endforeach; ?>
							</div>

							<div class="accepta-spacing-units">
								<select class="accepta-spacing-unit" data-device="<?php echo esc_attr( $breakpoint ); ?>">
									<?php 
									$current_unit = isset( $value[ $breakpoint ]['unit'] ) ? $value[ $breakpoint ]['unit'] : $this->default_unit;
									foreach ( $this->units as $unit ) : 
									?>
										<option value="<?php echo esc_attr( $unit ); ?>" <?php selected( $current_unit, $unit ); ?>>
											<?php echo esc_html( $unit ); ?>
										</option>
									<?php endforeach; ?>
								</select>
							</div>
						</div>

					</div>
				<?php endforeach; ?>

				<input 
					type="hidden" 
					id="<?php echo esc_attr( $input_id ); ?>"
					value="<?php echo esc_attr( json_encode( $value ) ); ?>"
					<?php echo $describedby_attr; ?>
					<?php $this->link(); ?>
				/>

			</div>
		</div>
		<?php
	}
}
