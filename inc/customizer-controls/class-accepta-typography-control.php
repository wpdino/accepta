<?php
/**
 * Accepta Typography Control Class
 * 
 * Custom control for typography settings with Google Fonts integration
 * Includes font family, size, weight, line height, letter spacing
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
 * Accepta Typography Control Class
 */
class Accepta_Typography_Control extends WP_Customize_Control {

	/**
	 * Control type
	 *
	 * @var string
	 */
	public $type = 'accepta-typography';

	/**
	 * Show font family selector
	 *
	 * @var bool
	 */
	public $show_font_family = true;

	/**
	 * Show font size control
	 *
	 * @var bool
	 */
	public $show_font_size = true;

	/**
	 * Show font weight control
	 *
	 * @var bool
	 */
	public $show_font_weight = true;

	/**
	 * Show line height control
	 *
	 * @var bool
	 */
	public $show_line_height = true;

	/**
	 * Show letter spacing control
	 *
	 * @var bool
	 */
	public $show_letter_spacing = true;

	/**
	 * Show text transform control
	 *
	 * @var bool
	 */
	public $show_text_transform = true;

	/**
	 * Constructor
	 */
	public function __construct( $manager, $id, $args = array() ) {
		parent::__construct( $manager, $id, $args );

		// Set properties from args
		if ( isset( $args['show_font_family'] ) ) {
			$this->show_font_family = $args['show_font_family'];
		}
		if ( isset( $args['show_font_size'] ) ) {
			$this->show_font_size = $args['show_font_size'];
		}
		if ( isset( $args['show_font_weight'] ) ) {
			$this->show_font_weight = $args['show_font_weight'];
		}
		if ( isset( $args['show_line_height'] ) ) {
			$this->show_line_height = $args['show_line_height'];
		}
		if ( isset( $args['show_letter_spacing'] ) ) {
			$this->show_letter_spacing = $args['show_letter_spacing'];
		}
		if ( isset( $args['show_text_transform'] ) ) {
			$this->show_text_transform = $args['show_text_transform'];
		}
	}

	/**
	 * Enqueue control related scripts/styles
	 */
	public function enqueue() {
		wp_enqueue_script(
			'accepta-typography-control',
			get_template_directory_uri() . '/inc/customizer-controls/js/typography-control.js',
			array( 'jquery', 'customize-base' ),
			wp_get_theme()->get( 'Version' ),
			true
		);

		wp_enqueue_style(
			'accepta-typography-control',
			get_template_directory_uri() . '/inc/customizer-controls/css/typography-control.css',
			array(),
			wp_get_theme()->get( 'Version' )
		);

		// Load Google Fonts data from JSON file
		$fonts_file = get_template_directory() . '/inc/customizer-controls/google-fonts.json';
		$google_fonts_data = array();
		
		if ( file_exists( $fonts_file ) ) {
			$fonts_json = file_get_contents( $fonts_file );
			$fonts_data = json_decode( $fonts_json, true );
			
			if ( isset( $fonts_data['items'] ) && is_array( $fonts_data['items'] ) ) {
				$google_fonts_data = $fonts_data['items'];
			}
		}

		// Localize script with minimal data for font styling
		wp_localize_script(
			'accepta-typography-control',
			'acceptaTypographyL10n',
			array(
				'systemFonts' => $this->get_system_fonts(),
				'googleFonts' => $this->get_google_fonts(),
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

		$defaults = array(
			'font_family' => '',
			'font_size' => '',
			'font_size_desktop' => '',
			'font_size_tablet' => '',
			'font_size_mobile' => '',
			'font_weight' => '',
			'line_height' => '',
			'letter_spacing' => '',
			'text_transform' => '',
		);

		$value = wp_parse_args( $value, $defaults );
		?>
		<div class="accepta-typography-control-wrapper" data-control-id="<?php echo esc_attr( $this->id ); ?>">
			<?php if ( ! empty( $this->label ) ) : ?>
				<span class="customize-control-title"><?php echo esc_html( $this->label ); ?></span>
			<?php endif; ?>
			
			<?php if ( ! empty( $this->description ) ) : ?>
				<span id="<?php echo esc_attr( $description_id ); ?>" class="description customize-control-description"><?php echo $this->description; ?></span>
			<?php endif; ?>

			<div class="accepta-typography-fields">
				
				<?php if ( $this->show_font_family ) : ?>
				<!-- Font Family spans full width -->
				<div class="accepta-typography-field">
					<label class="accepta-typography-label"><?php esc_html_e( 'Font Family', 'accepta' ); ?></label>
					<select class="accepta-typography-select accepta-font-family-select" data-field="font_family">
						<option value=""><?php esc_html_e( 'Inherit', 'accepta' ); ?></option>
						<optgroup label="<?php esc_attr_e( 'System Fonts', 'accepta' ); ?>">
							<?php foreach ( $this->get_system_fonts() as $font_key => $font_name ) : ?>
								<option value="<?php echo esc_attr( $font_key ); ?>" <?php selected( $value['font_family'], $font_key ); ?> data-font-family="<?php echo esc_attr( $font_key ); ?>">
									<?php echo esc_html( $font_name ); ?>
								</option>
							<?php endforeach; ?>
						</optgroup>
						<optgroup label="<?php esc_attr_e( 'Google Fonts', 'accepta' ); ?>">
							<?php foreach ( $this->get_google_fonts() as $font_key => $font_name ) : ?>
								<option value="<?php echo esc_attr( $font_key ); ?>" <?php selected( $value['font_family'], $font_key ); ?> data-font-family="<?php echo esc_attr( $font_name ); ?>">
									<?php echo esc_html( $font_name ); ?>
								</option>
							<?php endforeach; ?>
						</optgroup>
					</select>
				</div>
				<?php endif; ?>

				<!-- Two-column layout for other fields -->
				<div class="accepta-typography-field-group">
					<?php if ( $this->show_font_size ) : ?>
					<div class="accepta-typography-field accepta-responsive-field">
						<label class="accepta-typography-label"><?php esc_html_e( 'Font Size', 'accepta' ); ?></label>
						
						<!-- Responsive tabs -->
						<div class="accepta-responsive-tabs">
							<button type="button" class="accepta-responsive-tab active" data-device="desktop">
								<span class="dashicons dashicons-desktop"></span>
								<span class="screen-reader-text"><?php esc_html_e( 'Desktop', 'accepta' ); ?></span>
							</button>
							<button type="button" class="accepta-responsive-tab" data-device="tablet">
								<span class="dashicons dashicons-tablet"></span>
								<span class="screen-reader-text"><?php esc_html_e( 'Tablet', 'accepta' ); ?></span>
							</button>
							<button type="button" class="accepta-responsive-tab" data-device="mobile">
								<span class="dashicons dashicons-smartphone"></span>
								<span class="screen-reader-text"><?php esc_html_e( 'Mobile', 'accepta' ); ?></span>
							</button>
						</div>
						
						<!-- Responsive inputs -->
						<div class="accepta-responsive-inputs">
							<div class="accepta-input-with-unit accepta-responsive-input active" data-device="desktop">
								<input type="number" class="accepta-typography-input" data-field="font_size_desktop" value="<?php echo esc_attr( isset( $value['font_size_desktop'] ) ? $value['font_size_desktop'] : $value['font_size'] ); ?>" min="8" max="100" step="1" placeholder="<?php esc_attr_e( 'Desktop', 'accepta' ); ?>" />
								<span class="accepta-unit-label">px</span>
							</div>
							<div class="accepta-input-with-unit accepta-responsive-input" data-device="tablet">
								<input type="number" class="accepta-typography-input" data-field="font_size_tablet" value="<?php echo esc_attr( isset( $value['font_size_tablet'] ) ? $value['font_size_tablet'] : '' ); ?>" min="8" max="100" step="1" placeholder="<?php esc_attr_e( 'Tablet', 'accepta' ); ?>" />
								<span class="accepta-unit-label">px</span>
							</div>
							<div class="accepta-input-with-unit accepta-responsive-input" data-device="mobile">
								<input type="number" class="accepta-typography-input" data-field="font_size_mobile" value="<?php echo esc_attr( isset( $value['font_size_mobile'] ) ? $value['font_size_mobile'] : '' ); ?>" min="8" max="100" step="1" placeholder="<?php esc_attr_e( 'Mobile', 'accepta' ); ?>" />
								<span class="accepta-unit-label">px</span>
							</div>
						</div>
					</div>
					<?php endif; ?>

					<?php if ( $this->show_font_weight ) : ?>
					<div class="accepta-typography-field">
						<label class="accepta-typography-label"><?php esc_html_e( 'Font Weight', 'accepta' ); ?></label>
						<select class="accepta-typography-select" data-field="font_weight">
							<option value=""><?php esc_html_e( 'Inherit', 'accepta' ); ?></option>
							<option value="100" <?php selected( $value['font_weight'], '100' ); ?>><?php esc_html_e( '100 - Thin', 'accepta' ); ?></option>
							<option value="200" <?php selected( $value['font_weight'], '200' ); ?>><?php esc_html_e( '200 - Extra Light', 'accepta' ); ?></option>
							<option value="300" <?php selected( $value['font_weight'], '300' ); ?>><?php esc_html_e( '300 - Light', 'accepta' ); ?></option>
							<option value="400" <?php selected( $value['font_weight'], '400' ); ?>><?php esc_html_e( '400 - Normal', 'accepta' ); ?></option>
							<option value="500" <?php selected( $value['font_weight'], '500' ); ?>><?php esc_html_e( '500 - Medium', 'accepta' ); ?></option>
							<option value="600" <?php selected( $value['font_weight'], '600' ); ?>><?php esc_html_e( '600 - Semi Bold', 'accepta' ); ?></option>
							<option value="700" <?php selected( $value['font_weight'], '700' ); ?>><?php esc_html_e( '700 - Bold', 'accepta' ); ?></option>
							<option value="800" <?php selected( $value['font_weight'], '800' ); ?>><?php esc_html_e( '800 - Extra Bold', 'accepta' ); ?></option>
							<option value="900" <?php selected( $value['font_weight'], '900' ); ?>><?php esc_html_e( '900 - Black', 'accepta' ); ?></option>
						</select>
					</div>
					<?php endif; ?>

					<?php if ( $this->show_line_height ) : ?>
					<div class="accepta-typography-field">
						<label class="accepta-typography-label"><?php esc_html_e( 'Line Height', 'accepta' ); ?></label>
						<div class="accepta-input-with-unit">
							<input type="number" class="accepta-typography-input" data-field="line_height" value="<?php echo esc_attr( $value['line_height'] ); ?>" min="0.5" max="5" step="0.1" />
							<span class="accepta-unit-label">em</span>
						</div>
					</div>
					<?php endif; ?>

					<?php if ( $this->show_letter_spacing ) : ?>
					<div class="accepta-typography-field">
						<label class="accepta-typography-label"><?php esc_html_e( 'Letter Spacing', 'accepta' ); ?></label>
						<div class="accepta-input-with-unit">
							<input type="number" class="accepta-typography-input" data-field="letter_spacing" value="<?php echo esc_attr( $value['letter_spacing'] ); ?>" min="-5" max="10" step="0.1" />
							<span class="accepta-unit-label">px</span>
						</div>
					</div>
					<?php endif; ?>

					<?php if ( $this->show_text_transform ) : ?>
					<div class="accepta-typography-field">
						<label class="accepta-typography-label"><?php esc_html_e( 'Text Transform', 'accepta' ); ?></label>
						<select class="accepta-typography-select" data-field="text_transform">
							<option value=""><?php esc_html_e( 'Inherit', 'accepta' ); ?></option>
							<option value="none" <?php selected( $value['text_transform'], 'none' ); ?>><?php esc_html_e( 'None', 'accepta' ); ?></option>
							<option value="uppercase" <?php selected( $value['text_transform'], 'uppercase' ); ?>><?php esc_html_e( 'Uppercase', 'accepta' ); ?></option>
							<option value="lowercase" <?php selected( $value['text_transform'], 'lowercase' ); ?>><?php esc_html_e( 'Lowercase', 'accepta' ); ?></option>
							<option value="capitalize" <?php selected( $value['text_transform'], 'capitalize' ); ?>><?php esc_html_e( 'Capitalize', 'accepta' ); ?></option>
						</select>
					</div>
					<?php endif; ?>
				</div>

			</div>

			<input type="hidden" <?php $this->link(); ?> value="<?php echo esc_attr( json_encode( $value ) ); ?>" class="accepta-typography-hidden" />
		</div>
		<?php
	}

	/**
	 * Get system fonts
	 *
	 * @return array
	 */
	private function get_system_fonts() {
		return array(
			'Arial, sans-serif' => 'Arial',
			'Helvetica, Arial, sans-serif' => 'Helvetica',
			'"Times New Roman", Times, serif' => 'Times New Roman',
			'Georgia, serif' => 'Georgia',
			'"Courier New", Courier, monospace' => 'Courier New',
			'Verdana, Geneva, sans-serif' => 'Verdana',
			'Tahoma, Geneva, sans-serif' => 'Tahoma',
			'"Trebuchet MS", Helvetica, sans-serif' => 'Trebuchet MS',
			'"Arial Black", Gadget, sans-serif' => 'Arial Black',
			'"Palatino Linotype", "Book Antiqua", Palatino, serif' => 'Palatino',
			'"Lucida Sans Unicode", "Lucida Grande", sans-serif' => 'Lucida Sans',
			'"MS Serif", "New York", serif' => 'MS Serif',
			'"Comic Sans MS", cursive' => 'Comic Sans MS',
			'Impact, Charcoal, sans-serif' => 'Impact',
		);
	}

	/**
	 * Get Google Fonts from JSON file
	 *
	 * @return array
	 */
	private function get_google_fonts() {
		$fonts_file = get_template_directory() . '/inc/customizer-controls/google-fonts.json';
		
		if ( ! file_exists( $fonts_file ) ) {
			return array();
		}
		
		$fonts_json = file_get_contents( $fonts_file );
		$fonts_data = json_decode( $fonts_json, true );
		
		if ( ! isset( $fonts_data['items'] ) || ! is_array( $fonts_data['items'] ) ) {
			return array();
		}
		
		$fonts = array();
		foreach ( $fonts_data['items'] as $font ) {
			if ( isset( $font['family'] ) ) {
				$fonts[ $font['family'] ] = $font['family'];
			}
		}
		
		return $fonts;
	}

}
