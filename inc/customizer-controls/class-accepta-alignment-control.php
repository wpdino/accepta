<?php
/**
 * Accepta Alignment Control Class
 * 
 * Custom control for content alignment selection with visual SVG icons
 * Shows alignment options for horizontal and vertical alignment
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
 * Accepta Alignment Control Class
 */
class Accepta_Alignment_Control extends WP_Customize_Control {

	/**
	 * Control type
	 *
	 * @var string
	 */
	public $type = 'accepta-alignment';

	/**
	 * Alignment options
	 *
	 * @var array
	 */
	public $alignments = array();

	/**
	 * Whether to show responsive tabs
	 *
	 * @var bool
	 */
	public $responsive = true;

	/**
	 * Constructor
	 */
	public function __construct( $manager, $id, $args = array() ) {
		// Store alignments from args before parent call
		$alignments = isset( $args['alignments'] ) ? $args['alignments'] : array();
		$responsive = isset( $args['responsive'] ) ? $args['responsive'] : true;
		
		parent::__construct( $manager, $id, $args );

		// Set responsive property
		$this->responsive = $responsive;

		// WordPress should automatically set public properties from $args, but ensure it's set
		// Check if WordPress set it, otherwise use our stored value or defaults
		if ( ! empty( $this->alignments ) ) {
			// WordPress set it, use that
		} elseif ( ! empty( $alignments ) ) {
			// Use the value from args
			$this->alignments = $alignments;
		} else {
			// Use defaults - only 3 options: start, center, end
			$this->alignments = array(
				'flex-start' => array(
					'label' => __( 'Start', 'accepta' ),
				),
				'center' => array(
					'label' => __( 'Center', 'accepta' ),
				),
				'flex-end' => array(
					'label' => __( 'End', 'accepta' ),
				),
			);
		}
	}

	/**
	 * Export alignments to JSON for JavaScript
	 */
	public function to_json() {
		parent::to_json();
		$this->json['alignments'] = $this->alignments;
	}

	/**
	 * Enqueue control related scripts/styles
	 */
	public function enqueue() {
		wp_enqueue_script(
			'accepta-alignment-control',
			get_template_directory_uri() . '/inc/customizer-controls/js/alignment-control.js',
			array( 'jquery', 'customize-base' ),
			wp_get_theme()->get( 'Version' ),
			true
		);

		wp_enqueue_style(
			'accepta-alignment-control',
			get_template_directory_uri() . '/inc/customizer-controls/css/alignment-control.css',
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
		
		// Parse value - if responsive, expect JSON with desktop/tablet/mobile keys
		$raw_value = $this->value();
		$value = array();
		
		if ( $this->responsive ) {
			// Try to parse as JSON
			if ( ! empty( $raw_value ) ) {
				$decoded = json_decode( $raw_value, true );
				if ( is_array( $decoded ) ) {
					$value = $decoded;
				}
			}
			
			// Set defaults if empty
			if ( empty( $value ) ) {
				$value = array(
					'desktop' => 'center',
					'tablet' => 'center',
					'mobile' => 'center',
				);
			}
		} else {
			// Non-responsive: single value
			$value = ! empty( $raw_value ) ? $raw_value : 'center';
		}
		
		// Ensure alignments are set (fallback to defaults if empty)
		if ( empty( $this->alignments ) && ! empty( $this->json['alignments'] ) ) {
			$this->alignments = $this->json['alignments'];
		}
		if ( empty( $this->alignments ) ) {
			// Default: only 3 options
			$this->alignments = array(
				'flex-start' => array( 'label' => __( 'Start', 'accepta' ) ),
				'center' => array( 'label' => __( 'Center', 'accepta' ) ),
				'flex-end' => array( 'label' => __( 'End', 'accepta' ) ),
			);
		}
		
		$breakpoints = $this->responsive ? array( 'desktop', 'tablet', 'mobile' ) : array( 'desktop' );
		?>
		<div class="accepta-alignment-control-wrapper">
			<?php if ( ! empty( $this->label ) ) : ?>
				<span class="customize-control-title"><?php echo esc_html( $this->label ); ?></span>
			<?php endif; ?>
			
			<?php if ( ! empty( $this->description ) ) : ?>
				<span id="<?php echo esc_attr( $description_id ); ?>" class="description customize-control-description"><?php echo $this->description; ?></span>
			<?php endif; ?>

			<div class="accepta-alignment-control" data-control-id="<?php echo esc_attr( $this->id ); ?>">
				
				<?php if ( $this->responsive ) : ?>
					<div class="accepta-alignment-header">
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
					</div>
				<?php endif; ?>

				<?php foreach ( $breakpoints as $breakpoint ) : 
					$device_value = $this->responsive ? ( isset( $value[ $breakpoint ] ) ? $value[ $breakpoint ] : 'center' ) : $value;
				?>
					<div class="accepta-alignment-device accepta-alignment-<?php echo esc_attr( $breakpoint ); ?>" <?php echo $breakpoint !== 'desktop' ? 'style="display:none;"' : ''; ?>>
						<div class="accepta-alignment-options" data-device="<?php echo esc_attr( $breakpoint ); ?>">
							<?php foreach ( $this->alignments as $alignment_key => $alignment_data ) : ?>
								<label class="accepta-alignment-option <?php echo $device_value === $alignment_key ? 'selected' : ''; ?>" data-alignment="<?php echo esc_attr( $alignment_key ); ?>">
									<input 
										type="radio" 
										name="<?php echo esc_attr( $input_id ); ?>-<?php echo esc_attr( $breakpoint ); ?>" 
										value="<?php echo esc_attr( $alignment_key ); ?>" 
										data-device="<?php echo esc_attr( $breakpoint ); ?>"
										<?php checked( $device_value, $alignment_key ); ?>
										<?php echo $describedby_attr; ?>
									/>
									<div class="accepta-alignment-icon">
										<?php echo $this->get_alignment_svg( $alignment_key, $this->id ); ?>
									</div>
									<span class="accepta-alignment-label"><?php echo esc_html( isset( $alignment_data['label'] ) ? $alignment_data['label'] : $alignment_key ); ?></span>
								</label>
							<?php endforeach; ?>
						</div>
					</div>
				<?php endforeach; ?>

				<input 
					type="hidden" 
					id="<?php echo esc_attr( $input_id ); ?>"
					value="<?php echo esc_attr( $this->responsive ? json_encode( $value ) : $value ); ?>"
					<?php echo $describedby_attr; ?>
					<?php $this->link(); ?>
				/>

			</div>
		</div>
		<?php
	}

	/**
	 * Get SVG for alignment option
	 *
	 * @param string $alignment Alignment key
	 * @param string $control_id Control ID to determine if horizontal or vertical
	 * @return string SVG markup
	 */
	private function get_alignment_svg( $alignment, $control_id ) {
		$svg_content = '';
		$is_vertical = strpos( $control_id, 'vertical' ) !== false || strpos( $control_id, 'align_items' ) !== false;
		
		if ( $is_vertical ) {
			// Vertical alignment SVGs (top to bottom)
			switch ( $alignment ) {
				case 'flex-start':
					$svg_content = '<svg width="60" height="60" viewBox="0 0 60 60" xmlns="http://www.w3.org/2000/svg">
						<rect x="5" y="5" width="50" height="45" fill="#f0f0f1" stroke="#c3c4c7" stroke-width="1" rx="2"/>
						<rect x="15" y="8" width="30" height="8" fill="#0073aa" rx="1"/>
						<rect x="20" y="20" width="20" height="6" fill="#c3c4c7" rx="1"/>
						<rect x="20" y="30" width="20" height="6" fill="#c3c4c7" rx="1"/>
						<rect x="20" y="40" width="20" height="6" fill="#c3c4c7" rx="1"/>
					</svg>';
					break;
				case 'center':
					$svg_content = '<svg width="60" height="60" viewBox="0 0 60 60" xmlns="http://www.w3.org/2000/svg">
						<rect x="5" y="5" width="50" height="45" fill="#f0f0f1" stroke="#c3c4c7" stroke-width="1" rx="2"/>
						<rect x="20" y="12" width="20" height="6" fill="#c3c4c7" rx="1"/>
						<rect x="15" y="22" width="30" height="8" fill="#0073aa" rx="1"/>
						<rect x="20" y="34" width="20" height="6" fill="#c3c4c7" rx="1"/>
					</svg>';
					break;
				case 'flex-end':
					$svg_content = '<svg width="60" height="60" viewBox="0 0 60 60" xmlns="http://www.w3.org/2000/svg">
						<rect x="5" y="5" width="50" height="45" fill="#f0f0f1" stroke="#c3c4c7" stroke-width="1" rx="2"/>
						<rect x="20" y="8" width="20" height="6" fill="#c3c4c7" rx="1"/>
						<rect x="20" y="18" width="20" height="6" fill="#c3c4c7" rx="1"/>
						<rect x="20" y="28" width="20" height="6" fill="#c3c4c7" rx="1"/>
						<rect x="15" y="37" width="30" height="8" fill="#0073aa" rx="1"/>
					</svg>';
					break;
				case 'stretch':
					$svg_content = '<svg width="60" height="60" viewBox="0 0 60 60" xmlns="http://www.w3.org/2000/svg">
						<rect x="5" y="5" width="50" height="45" fill="#f0f0f1" stroke="#c3c4c7" stroke-width="1" rx="2"/>
						<rect x="15" y="8" width="30" height="10" fill="#0073aa" rx="1"/>
						<rect x="15" y="22" width="30" height="10" fill="#0073aa" rx="1"/>
						<rect x="15" y="36" width="30" height="10" fill="#0073aa" rx="1"/>
					</svg>';
					break;
			}
		} else {
			// Horizontal alignment SVGs (left to right)
			switch ( $alignment ) {
				case 'flex-start':
					$svg_content = '<svg width="60" height="60" viewBox="0 0 60 60" xmlns="http://www.w3.org/2000/svg">
						<rect x="5" y="5" width="50" height="45" fill="#f0f0f1" stroke="#c3c4c7" stroke-width="1" rx="2"/>
						<rect x="8" y="20" width="30" height="8" fill="#0073aa" rx="1"/>
						<rect x="42" y="22" width="8" height="4" fill="#c3c4c7" rx="1"/>
						<rect x="42" y="28" width="8" height="4" fill="#c3c4c7" rx="1"/>
						<rect x="42" y="34" width="8" height="4" fill="#c3c4c7" rx="1"/>
					</svg>';
					break;
				case 'center':
					$svg_content = '<svg width="60" height="60" viewBox="0 0 60 60" xmlns="http://www.w3.org/2000/svg">
						<rect x="5" y="5" width="50" height="45" fill="#f0f0f1" stroke="#c3c4c7" stroke-width="1" rx="2"/>
						<rect x="12" y="22" width="6" height="4" fill="#c3c4c7" rx="1"/>
						<rect x="22" y="20" width="16" height="8" fill="#0073aa" rx="1"/>
						<rect x="42" y="22" width="6" height="4" fill="#c3c4c7" rx="1"/>
					</svg>';
					break;
				case 'flex-end':
					$svg_content = '<svg width="60" height="60" viewBox="0 0 60 60" xmlns="http://www.w3.org/2000/svg">
						<rect x="5" y="5" width="50" height="45" fill="#f0f0f1" stroke="#c3c4c7" stroke-width="1" rx="2"/>
						<rect x="8" y="22" width="6" height="4" fill="#c3c4c7" rx="1"/>
						<rect x="8" y="28" width="6" height="4" fill="#c3c4c7" rx="1"/>
						<rect x="8" y="34" width="6" height="4" fill="#c3c4c7" rx="1"/>
						<rect x="20" y="20" width="30" height="8" fill="#0073aa" rx="1"/>
					</svg>';
					break;
				case 'space-between':
					$svg_content = '<svg width="60" height="60" viewBox="0 0 60 60" xmlns="http://www.w3.org/2000/svg">
						<rect x="5" y="5" width="50" height="45" fill="#f0f0f1" stroke="#c3c4c7" stroke-width="1" rx="2"/>
						<rect x="8" y="22" width="12" height="6" fill="#0073aa" rx="1"/>
						<rect x="24" y="22" width="12" height="6" fill="#0073aa" rx="1"/>
						<rect x="40" y="22" width="12" height="6" fill="#0073aa" rx="1"/>
					</svg>';
					break;
				case 'space-around':
					$svg_content = '<svg width="60" height="60" viewBox="0 0 60 60" xmlns="http://www.w3.org/2000/svg">
						<rect x="5" y="5" width="50" height="45" fill="#f0f0f1" stroke="#c3c4c7" stroke-width="1" rx="2"/>
						<rect x="10" y="22" width="10" height="6" fill="#0073aa" rx="1"/>
						<rect x="25" y="22" width="10" height="6" fill="#0073aa" rx="1"/>
						<rect x="40" y="22" width="10" height="6" fill="#0073aa" rx="1"/>
					</svg>';
					break;
				case 'space-evenly':
					$svg_content = '<svg width="60" height="60" viewBox="0 0 60 60" xmlns="http://www.w3.org/2000/svg">
						<rect x="5" y="5" width="50" height="45" fill="#f0f0f1" stroke="#c3c4c7" stroke-width="1" rx="2"/>
						<rect x="8" y="22" width="8" height="6" fill="#0073aa" rx="1"/>
						<rect x="22" y="22" width="8" height="6" fill="#0073aa" rx="1"/>
						<rect x="36" y="22" width="8" height="6" fill="#0073aa" rx="1"/>
						<rect x="50" y="22" width="8" height="6" fill="#0073aa" rx="1"/>
					</svg>';
					break;
			}
		}

		return $svg_content;
	}
}

