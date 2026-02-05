<?php
/**
 * Accepta Range Control Class
 * 
 * Custom control for range slider with linked number input
 * Provides both slider and text input for precise control
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
 * Accepta Range Control Class
 */
class Accepta_Range_Control extends WP_Customize_Control {

	/**
	 * Control type
	 *
	 * @var string
	 */
	public $type = 'accepta-range';

	/**
	 * Minimum value
	 *
	 * @var int
	 */
	public $min = 0;

	/**
	 * Maximum value
	 *
	 * @var int
	 */
	public $max = 100;

	/**
	 * Step value
	 *
	 * @var float|int
	 */
	public $step = 1;

	/**
	 * Unit label
	 *
	 * @var string
	 */
	public $unit = '';

	/**
	 * Constructor
	 */
	public function __construct( $manager, $id, $args = array() ) {
		parent::__construct( $manager, $id, $args );

		// Set properties from args
		if ( isset( $args['min'] ) ) {
			$this->min = is_numeric( $args['min'] ) ? floatval( $args['min'] ) : 0;
		}
		if ( isset( $args['max'] ) ) {
			$this->max = is_numeric( $args['max'] ) ? floatval( $args['max'] ) : 100;
		}
		if ( isset( $args['step'] ) ) {
			$this->step = is_numeric( $args['step'] ) ? floatval( $args['step'] ) : 1;
		}
		if ( isset( $args['unit'] ) ) {
			$this->unit = sanitize_text_field( $args['unit'] );
		}
	}

	/**
	 * Enqueue control related scripts/styles
	 */
	public function enqueue() {
		wp_enqueue_script(
			'accepta-range-control',
			get_template_directory_uri() . '/inc/customizer-controls/js/range-control.js',
			array( 'jquery', 'customize-base' ),
			wp_get_theme()->get( 'Version' ),
			true
		);

		wp_enqueue_style(
			'accepta-range-control',
			get_template_directory_uri() . '/inc/customizer-controls/css/range-control.css',
			array(),
			wp_get_theme()->get( 'Version' )
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
		?>
		<div class="accepta-range-control-wrapper" data-control-id="<?php echo esc_attr( $this->id ); ?>">
			<?php if ( ! empty( $this->label ) ) : ?>
				<span class="customize-control-title"><?php echo esc_html( $this->label ); ?></span>
			<?php endif; ?>
			
			<?php if ( ! empty( $this->description ) ) : ?>
				<span id="<?php echo esc_attr( $description_id ); ?>" class="description customize-control-description"><?php echo $this->description; ?></span>
			<?php endif; ?>

			<div class="accepta-range-input-wrapper">
				<div class="accepta-range-slider-wrapper">
					<input 
						type="range" 
						class="accepta-range-slider"
						id="<?php echo esc_attr( $input_id ); ?>-slider"
						min="<?php echo esc_attr( $this->min ); ?>"
						max="<?php echo esc_attr( $this->max ); ?>"
						step="<?php echo esc_attr( $this->step ); ?>"
						value="<?php echo esc_attr( $value ); ?>"
						<?php echo $describedby_attr; ?>
					/>
				</div>
				
				<div class="accepta-range-number-wrapper">
					<input 
						type="number" 
						class="accepta-range-number"
						id="<?php echo esc_attr( $input_id ); ?>-number"
						min="<?php echo esc_attr( $this->min ); ?>"
						max="<?php echo esc_attr( $this->max ); ?>"
						step="<?php echo esc_attr( $this->step ); ?>"
						value="<?php echo esc_attr( $value ); ?>"
						<?php echo $describedby_attr; ?>
					/>
					<?php if ( ! empty( $this->unit ) ) : ?>
						<span class="accepta-range-unit"><?php echo esc_html( $this->unit ); ?></span>
					<?php endif; ?>
				</div>
			</div>

			<input type="hidden" <?php $this->link(); ?> value="<?php echo esc_attr( $value ); ?>" class="accepta-range-hidden" />
		</div>
		<?php
	}
}
