<?php
/**
 * Accepta Layout Control Class
 * 
 * Custom control for layout selection with visual images
 * Shows layout options with sidebar positions
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
 * Accepta Layout Control Class
 */
class Accepta_Layout_Control extends WP_Customize_Control {

	/**
	 * Control type
	 *
	 * @var string
	 */
	public $type = 'accepta-layout';

	/**
	 * Layout options
	 *
	 * @var array
	 */
	public $layouts = array();

	/**
	 * Constructor
	 */
	public function __construct( $manager, $id, $args = array() ) {
		parent::__construct( $manager, $id, $args );

		// Set default layouts if not provided
		if ( empty( $this->layouts ) ) {
			$this->layouts = array(
				'none' => array(
					'label' => __( 'No Sidebar', 'accepta' ),
					'image' => 'no-sidebar.svg',
				),
				'left' => array(
					'label' => __( 'Left Sidebar', 'accepta' ),
					'image' => 'left-sidebar.svg',
				),
				'right' => array(
					'label' => __( 'Right Sidebar', 'accepta' ),
					'image' => 'right-sidebar.svg',
				),
			);
		}
	}

	/**
	 * Enqueue control related scripts/styles
	 */
	public function enqueue() {
		wp_enqueue_script(
			'accepta-layout-control',
			get_template_directory_uri() . '/inc/customizer-controls/js/layout-control.js',
			array( 'jquery', 'customize-base' ),
			wp_get_theme()->get( 'Version' ),
			true
		);

		wp_enqueue_style(
			'accepta-layout-control',
			get_template_directory_uri() . '/inc/customizer-controls/css/layout-control.css',
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
		<div class="accepta-layout-control-wrapper">
			<?php if ( ! empty( $this->label ) ) : ?>
				<span class="customize-control-title"><?php echo esc_html( $this->label ); ?></span>
			<?php endif; ?>
			
			<?php if ( ! empty( $this->description ) ) : ?>
				<span id="<?php echo esc_attr( $description_id ); ?>" class="description customize-control-description"><?php echo $this->description; ?></span>
			<?php endif; ?>

			<div class="accepta-layout-options" data-control-id="<?php echo esc_attr( $this->id ); ?>">
				<?php foreach ( $this->layouts as $layout_key => $layout_data ) : ?>
					<label class="accepta-layout-option <?php echo $value === $layout_key ? 'selected' : ''; ?>" data-layout="<?php echo esc_attr( $layout_key ); ?>">
						<input 
							type="radio" 
							name="<?php echo esc_attr( $input_id ); ?>" 
							value="<?php echo esc_attr( $layout_key ); ?>" 
							<?php checked( $value, $layout_key ); ?>
							<?php echo $describedby_attr; ?>
						/>
						<div class="accepta-layout-image">
							<?php echo $this->get_layout_svg( $layout_key ); ?>
						</div>
						<span class="accepta-layout-label"><?php echo esc_html( $layout_data['label'] ); ?></span>
					</label>
				<?php endforeach; ?>
			</div>

			<input type="hidden" <?php $this->link(); ?> value="<?php echo esc_attr( $value ); ?>" />
		</div>
		<?php
	}

	/**
	 * Get SVG for layout option
	 *
	 * @param string $layout Layout key
	 * @return string SVG markup
	 */
	private function get_layout_svg( $layout ) {
		$svg_content = '';
		
		// Check if this is a hero width control
		if ( isset( $this->id ) && $this->id === 'accepta_hero_width' ) {
			$hero_width_svg = $this->get_hero_width_svg( $layout );
			return $hero_width_svg;
		}
		
		// Check if this is a header width control
		if ( isset( $this->id ) && $this->id === 'accepta_header_width' ) {
			$header_width_svg = $this->get_header_width_svg( $layout );
			return $header_width_svg;
		}
		
		// Check if this is a header layout control
		if ( isset( $this->id ) && $this->id === 'accepta_header_layout' ) {
			$header_svg = $this->get_header_layout_svg( $layout );
			return $header_svg;
		}
		
		// Check if this is a footer columns control
		// Footer columns use numeric keys (0-4), sidebar layouts use text keys (none, left, right)
		$is_footer_columns = false;
		
		// Check control ID first (most reliable)
		if ( isset( $this->id ) && $this->id === 'accepta_footer_columns' ) {
			$is_footer_columns = true;
		}
		// Fallback: check if layout key is numeric and in footer columns range
		elseif ( is_numeric( $layout ) && in_array( (string) $layout, array( '0', '1', '2', '3', '4' ), true ) ) {
			$is_footer_columns = true;
		}
		
		if ( $is_footer_columns ) {
			$footer_svg = $this->get_footer_column_svg( (string) $layout );
			return $footer_svg;
		}
		
		// Default to sidebar layout SVGs
		switch ( $layout ) {
			case 'none':
				$svg_content = '<svg width="80" height="60" viewBox="0 0 80 60" xmlns="http://www.w3.org/2000/svg">
					<rect x="5" y="5" width="70" height="8" fill="#0073aa" rx="2"/>
					<rect x="5" y="18" width="70" height="37" fill="#f0f0f1" stroke="#c3c4c7" stroke-width="1" rx="2"/>
					<rect x="10" y="23" width="60" height="4" fill="#c3c4c7" rx="1"/>
					<rect x="10" y="30" width="45" height="4" fill="#c3c4c7" rx="1"/>
					<rect x="10" y="37" width="55" height="4" fill="#c3c4c7" rx="1"/>
					<rect x="10" y="44" width="40" height="4" fill="#c3c4c7" rx="1"/>
				</svg>';
				break;
			case 'left':
				$svg_content = '<svg width="80" height="60" viewBox="0 0 80 60" xmlns="http://www.w3.org/2000/svg">
					<rect x="5" y="5" width="70" height="8" fill="#0073aa" rx="2"/>
					<rect x="5" y="18" width="20" height="37" fill="#e0e0e0" stroke="#c3c4c7" stroke-width="1" rx="2"/>
					<rect x="30" y="18" width="45" height="37" fill="#f0f0f1" stroke="#c3c4c7" stroke-width="1" rx="2"/>
					<rect x="8" y="23" width="14" height="3" fill="#c3c4c7" rx="1"/>
					<rect x="8" y="29" width="10" height="3" fill="#c3c4c7" rx="1"/>
					<rect x="8" y="35" width="12" height="3" fill="#c3c4c7" rx="1"/>
					<rect x="35" y="23" width="35" height="4" fill="#c3c4c7" rx="1"/>
					<rect x="35" y="30" width="25" height="4" fill="#c3c4c7" rx="1"/>
					<rect x="35" y="37" width="30" height="4" fill="#c3c4c7" rx="1"/>
					<rect x="35" y="44" width="20" height="4" fill="#c3c4c7" rx="1"/>
				</svg>';
				break;
			case 'right':
				$svg_content = '<svg width="80" height="60" viewBox="0 0 80 60" xmlns="http://www.w3.org/2000/svg">
					<rect x="5" y="5" width="70" height="8" fill="#0073aa" rx="2"/>
					<rect x="5" y="18" width="45" height="37" fill="#f0f0f1" stroke="#c3c4c7" stroke-width="1" rx="2"/>
					<rect x="55" y="18" width="20" height="37" fill="#e0e0e0" stroke="#c3c4c7" stroke-width="1" rx="2"/>
					<rect x="10" y="23" width="35" height="4" fill="#c3c4c7" rx="1"/>
					<rect x="10" y="30" width="25" height="4" fill="#c3c4c7" rx="1"/>
					<rect x="10" y="37" width="30" height="4" fill="#c3c4c7" rx="1"/>
					<rect x="10" y="44" width="20" height="4" fill="#c3c4c7" rx="1"/>
					<rect x="58" y="23" width="14" height="3" fill="#c3c4c7" rx="1"/>
					<rect x="58" y="29" width="10" height="3" fill="#c3c4c7" rx="1"/>
					<rect x="58" y="35" width="12" height="3" fill="#c3c4c7" rx="1"/>
				</svg>';
				break;
		}

		return $svg_content;
	}

	/**
	 * Get SVG for footer column layout
	 *
	 * @param string $columns Number of columns (0, 1, 2, 3, 4)
	 * @return string SVG markup
	 */
	private function get_footer_column_svg( $columns ) {
		$svg_content = '';
		
		switch ( $columns ) {
			case '0':
				$svg_content = '<svg width="80" height="60" viewBox="0 0 80 60" xmlns="http://www.w3.org/2000/svg">
					<rect x="5" y="5" width="70" height="37" fill="#f0f0f1" stroke="#c3c4c7" stroke-width="1" rx="2"/>
					<text x="40" y="25" text-anchor="middle" fill="#c3c4c7" font-size="10" font-family="Arial, sans-serif">No Columns</text>
					<rect x="5" y="47" width="70" height="8" fill="#0073aa" rx="2"/>
				</svg>';
				break;
			case '1':
				$svg_content = '<svg width="80" height="60" viewBox="0 0 80 60" xmlns="http://www.w3.org/2000/svg">
					<rect x="5" y="5" width="70" height="37" fill="#f0f0f1" stroke="#c3c4c7" stroke-width="1" rx="2"/>
					<rect x="10" y="10" width="60" height="4" fill="#c3c4c7" rx="1"/>
					<rect x="10" y="17" width="45" height="4" fill="#c3c4c7" rx="1"/>
					<rect x="10" y="24" width="55" height="4" fill="#c3c4c7" rx="1"/>
					<rect x="10" y="31" width="40" height="4" fill="#c3c4c7" rx="1"/>
					<rect x="5" y="47" width="70" height="8" fill="#0073aa" rx="2"/>
				</svg>';
				break;
			case '2':
				$svg_content = '<svg width="80" height="60" viewBox="0 0 80 60" xmlns="http://www.w3.org/2000/svg">
					<rect x="5" y="5" width="33" height="37" fill="#f0f0f1" stroke="#c3c4c7" stroke-width="1" rx="2"/>
					<rect x="42" y="5" width="33" height="37" fill="#f0f0f1" stroke="#c3c4c7" stroke-width="1" rx="2"/>
					<rect x="10" y="10" width="23" height="4" fill="#c3c4c7" rx="1"/>
					<rect x="10" y="17" width="18" height="4" fill="#c3c4c7" rx="1"/>
					<rect x="10" y="24" width="20" height="4" fill="#c3c4c7" rx="1"/>
					<rect x="10" y="31" width="15" height="4" fill="#c3c4c7" rx="1"/>
					<rect x="47" y="10" width="23" height="4" fill="#c3c4c7" rx="1"/>
					<rect x="47" y="17" width="18" height="4" fill="#c3c4c7" rx="1"/>
					<rect x="47" y="24" width="20" height="4" fill="#c3c4c7" rx="1"/>
					<rect x="47" y="31" width="15" height="4" fill="#c3c4c7" rx="1"/>
					<rect x="5" y="47" width="70" height="8" fill="#0073aa" rx="2"/>
				</svg>';
				break;
			case '3':
				$svg_content = '<svg width="80" height="60" viewBox="0 0 80 60" xmlns="http://www.w3.org/2000/svg">
					<rect x="5" y="5" width="21" height="37" fill="#f0f0f1" stroke="#c3c4c7" stroke-width="1" rx="2"/>
					<rect x="29" y="5" width="21" height="37" fill="#f0f0f1" stroke="#c3c4c7" stroke-width="1" rx="2"/>
					<rect x="53" y="5" width="22" height="37" fill="#f0f0f1" stroke="#c3c4c7" stroke-width="1" rx="2"/>
					<rect x="8" y="10" width="15" height="4" fill="#c3c4c7" rx="1"/>
					<rect x="8" y="17" width="12" height="4" fill="#c3c4c7" rx="1"/>
					<rect x="8" y="24" width="13" height="4" fill="#c3c4c7" rx="1"/>
					<rect x="8" y="31" width="10" height="4" fill="#c3c4c7" rx="1"/>
					<rect x="32" y="10" width="15" height="4" fill="#c3c4c7" rx="1"/>
					<rect x="32" y="17" width="12" height="4" fill="#c3c4c7" rx="1"/>
					<rect x="32" y="24" width="13" height="4" fill="#c3c4c7" rx="1"/>
					<rect x="32" y="31" width="10" height="4" fill="#c3c4c7" rx="1"/>
					<rect x="56" y="10" width="15" height="4" fill="#c3c4c7" rx="1"/>
					<rect x="56" y="17" width="12" height="4" fill="#c3c4c7" rx="1"/>
					<rect x="56" y="24" width="13" height="4" fill="#c3c4c7" rx="1"/>
					<rect x="56" y="31" width="10" height="4" fill="#c3c4c7" rx="1"/>
					<rect x="5" y="47" width="70" height="8" fill="#0073aa" rx="2"/>
				</svg>';
				break;
			case '4':
				$svg_content = '<svg width="80" height="60" viewBox="0 0 80 60" xmlns="http://www.w3.org/2000/svg">
					<rect x="5" y="5" width="15" height="37" fill="#f0f0f1" stroke="#c3c4c7" stroke-width="1" rx="2"/>
					<rect x="23" y="5" width="15" height="37" fill="#f0f0f1" stroke="#c3c4c7" stroke-width="1" rx="2"/>
					<rect x="41" y="5" width="15" height="37" fill="#f0f0f1" stroke="#c3c4c7" stroke-width="1" rx="2"/>
					<rect x="59" y="5" width="16" height="37" fill="#f0f0f1" stroke="#c3c4c7" stroke-width="1" rx="2"/>
					<rect x="7" y="10" width="11" height="4" fill="#c3c4c7" rx="1"/>
					<rect x="7" y="17" width="9" height="4" fill="#c3c4c7" rx="1"/>
					<rect x="7" y="24" width="10" height="4" fill="#c3c4c7" rx="1"/>
					<rect x="7" y="31" width="8" height="4" fill="#c3c4c7" rx="1"/>
					<rect x="25" y="10" width="11" height="4" fill="#c3c4c7" rx="1"/>
					<rect x="25" y="17" width="9" height="4" fill="#c3c4c7" rx="1"/>
					<rect x="25" y="24" width="10" height="4" fill="#c3c4c7" rx="1"/>
					<rect x="25" y="31" width="8" height="4" fill="#c3c4c7" rx="1"/>
					<rect x="43" y="10" width="11" height="4" fill="#c3c4c7" rx="1"/>
					<rect x="43" y="17" width="9" height="4" fill="#c3c4c7" rx="1"/>
					<rect x="43" y="24" width="10" height="4" fill="#c3c4c7" rx="1"/>
					<rect x="43" y="31" width="8" height="4" fill="#c3c4c7" rx="1"/>
					<rect x="61" y="10" width="11" height="4" fill="#c3c4c7" rx="1"/>
					<rect x="61" y="17" width="9" height="4" fill="#c3c4c7" rx="1"/>
					<rect x="61" y="24" width="10" height="4" fill="#c3c4c7" rx="1"/>
					<rect x="61" y="31" width="8" height="4" fill="#c3c4c7" rx="1"/>
					<rect x="5" y="47" width="70" height="8" fill="#0073aa" rx="2"/>
				</svg>';
				break;
			default:
				// Fallback: return empty SVG if layout doesn't match
				$svg_content = '';
				break;
		}
		
		return $svg_content;
	}

	/**
	 * Get SVG for header layout
	 *
	 * @param string $layout Layout key (layout-1, layout-2, layout-3, layout-4)
	 * @return string SVG markup
	 */
	private function get_header_layout_svg( $layout ) {
		$svg_content = '';
		
		switch ( $layout ) {
			case 'layout-1':
				// Layout 1: Default - LOGO | Menu | Social | Search (space-between)
				$svg_content = '<svg width="80" height="60" viewBox="0 0 80 60" xmlns="http://www.w3.org/2000/svg">
					<rect x="5" y="5" width="70" height="12" fill="#f0f0f1" stroke="#c3c4c7" stroke-width="1" rx="2"/>
					<text x="12" y="13" font-size="7" font-weight="bold" fill="#0073aa">LOGO</text>
					<line x1="25" y1="8" x2="25" y2="14" stroke="#c3c4c7" stroke-width="1"/>
					<line x1="28" y1="8" x2="28" y2="14" stroke="#c3c4c7" stroke-width="1"/>
					<line x1="31" y1="8" x2="31" y2="14" stroke="#c3c4c7" stroke-width="1"/>
					<circle cx="55" cy="10" r="2" fill="#c3c4c7"/>
					<circle cx="59" cy="10" r="2" fill="#c3c4c7"/>
					<circle cx="65" cy="10" r="3" fill="#c3c4c7"/>
					<rect x="68" y="8" width="4" height="4" fill="#c3c4c7" rx="0.5"/>
					<text x="40" y="25" text-anchor="middle" fill="#0073aa" font-size="8" font-family="Arial, sans-serif" font-weight="bold">Default</text>
				</svg>';
				break;
			case 'layout-2':
				// Layout 2: Menu near logo - LOGO Menu | Social | Search
				$svg_content = '<svg width="80" height="60" viewBox="0 0 80 60" xmlns="http://www.w3.org/2000/svg">
					<rect x="5" y="5" width="70" height="12" fill="#f0f0f1" stroke="#c3c4c7" stroke-width="1" rx="2"/>
					<text x="12" y="13" font-size="7" font-weight="bold" fill="#0073aa">LOGO</text>
					<line x1="22" y1="8" x2="22" y2="14" stroke="#c3c4c7" stroke-width="1"/>
					<line x1="25" y1="8" x2="25" y2="14" stroke="#c3c4c7" stroke-width="1"/>
					<line x1="28" y1="8" x2="28" y2="14" stroke="#c3c4c7" stroke-width="1"/>
					<circle cx="55" cy="10" r="2" fill="#c3c4c7"/>
					<circle cx="59" cy="10" r="2" fill="#c3c4c7"/>
					<circle cx="65" cy="10" r="3" fill="#c3c4c7"/>
					<rect x="68" y="8" width="4" height="4" fill="#c3c4c7" rx="0.5"/>
					<text x="40" y="25" text-anchor="middle" fill="#0073aa" font-size="8" font-family="Arial, sans-serif" font-weight="bold">Menu Left</text>
				</svg>';
				break;
			case 'layout-3':
				// Layout 3: Menu centered - LOGO | Menu (center) | Social | Search
				$svg_content = '<svg width="80" height="60" viewBox="0 0 80 60" xmlns="http://www.w3.org/2000/svg">
					<rect x="5" y="5" width="70" height="12" fill="#f0f0f1" stroke="#c3c4c7" stroke-width="1" rx="2"/>
					<text x="12" y="13" font-size="7" font-weight="bold" fill="#0073aa">LOGO</text>
					<line x1="35" y1="8" x2="35" y2="14" stroke="#c3c4c7" stroke-width="1"/>
					<line x1="38" y1="8" x2="38" y2="14" stroke="#c3c4c7" stroke-width="1"/>
					<line x1="41" y1="8" x2="41" y2="14" stroke="#c3c4c7" stroke-width="1"/>
					<circle cx="55" cy="10" r="2" fill="#c3c4c7"/>
					<circle cx="59" cy="10" r="2" fill="#c3c4c7"/>
					<circle cx="65" cy="10" r="3" fill="#c3c4c7"/>
					<rect x="68" y="8" width="4" height="4" fill="#c3c4c7" rx="0.5"/>
					<text x="40" y="25" text-anchor="middle" fill="#0073aa" font-size="8" font-family="Arial, sans-serif" font-weight="bold">Menu Center</text>
				</svg>';
				break;
			default:
				$svg_content = '';
				break;
		}
		
		return $svg_content;
	}

	/**
	 * Get SVG for hero width layout
	 *
	 * @param string $width Width key (boxed, fullwidth)
	 * @return string SVG markup
	 */
	private function get_hero_width_svg( $width ) {
		$svg_content = '';
		
		switch ( $width ) {
			case 'boxed':
				$svg_content = '<svg width="80" height="60" viewBox="0 0 80 60" xmlns="http://www.w3.org/2000/svg">
					<rect x="5" y="5" width="70" height="50" fill="#f0f0f1" stroke="#c3c4c7" stroke-width="1" rx="2"/>
					<rect x="10" y="10" width="60" height="40" fill="#ffffff" stroke="#0073aa" stroke-width="2" rx="2"/>
					<rect x="15" y="15" width="50" height="5" fill="#c3c4c7" rx="1"/>
					<rect x="15" y="23" width="40" height="4" fill="#c3c4c7" rx="1"/>
					<rect x="15" y="30" width="45" height="4" fill="#c3c4c7" rx="1"/>
					<rect x="15" y="37" width="35" height="4" fill="#c3c4c7" rx="1"/>
					<text x="40" y="52" text-anchor="middle" fill="#0073aa" font-size="8" font-family="Arial, sans-serif" font-weight="bold">Boxed</text>
				</svg>';
				break;
			case 'fullwidth':
				$svg_content = '<svg width="80" height="60" viewBox="0 0 80 60" xmlns="http://www.w3.org/2000/svg">
					<rect x="0" y="5" width="80" height="50" fill="#f0f0f1" stroke="#c3c4c7" stroke-width="1" rx="2"/>
					<rect x="5" y="10" width="70" height="40" fill="#ffffff" stroke="#0073aa" stroke-width="2" rx="2"/>
					<rect x="10" y="15" width="60" height="5" fill="#c3c4c7" rx="1"/>
					<rect x="10" y="23" width="50" height="4" fill="#c3c4c7" rx="1"/>
					<rect x="10" y="30" width="55" height="4" fill="#c3c4c7" rx="1"/>
					<rect x="10" y="37" width="45" height="4" fill="#c3c4c7" rx="1"/>
					<text x="40" y="52" text-anchor="middle" fill="#0073aa" font-size="8" font-family="Arial, sans-serif" font-weight="bold">Full Width</text>
				</svg>';
				break;
		}
		
		return $svg_content;
	}

	/**
	 * Get SVG for header width layout
	 *
	 * @param string $width Width key (boxed, fullwidth)
	 * @return string SVG markup
	 */
	private function get_header_width_svg( $width ) {
		$svg_content = '';
		
		switch ( $width ) {
			case 'boxed':
				$svg_content = '<svg width="80" height="60" viewBox="0 0 80 60" xmlns="http://www.w3.org/2000/svg">
					<rect x="5" y="5" width="70" height="12" fill="#f0f0f1" stroke="#c3c4c7" stroke-width="1" rx="2"/>
					<rect x="10" y="8" width="60" height="6" fill="#ffffff" stroke="#0073aa" stroke-width="2" rx="1"/>
					<text x="12" y="13" font-size="6" font-weight="bold" fill="#0073aa">LOGO</text>
					<line x1="25" y1="9" x2="25" y2="13" stroke="#c3c4c7" stroke-width="1"/>
					<line x1="28" y1="9" x2="28" y2="13" stroke="#c3c4c7" stroke-width="1"/>
					<line x1="31" y1="9" x2="31" y2="13" stroke="#c3c4c7" stroke-width="1"/>
					<circle cx="55" cy="11" r="1.5" fill="#c3c4c7"/>
					<circle cx="59" cy="11" r="1.5" fill="#c3c4c7"/>
					<circle cx="65" cy="11" r="2" fill="#c3c4c7"/>
					<rect x="68" y="9" width="3" height="4" fill="#c3c4c7" rx="0.5"/>
					<text x="40" y="25" text-anchor="middle" fill="#0073aa" font-size="8" font-family="Arial, sans-serif" font-weight="bold">Boxed</text>
				</svg>';
				break;
			case 'fullwidth':
				$svg_content = '<svg width="80" height="60" viewBox="0 0 80 60" xmlns="http://www.w3.org/2000/svg">
					<rect x="0" y="5" width="80" height="12" fill="#f0f0f1" stroke="#c3c4c7" stroke-width="1" rx="2"/>
					<rect x="5" y="8" width="70" height="6" fill="#ffffff" stroke="#0073aa" stroke-width="2" rx="1"/>
					<text x="7" y="13" font-size="6" font-weight="bold" fill="#0073aa">LOGO</text>
					<line x1="20" y1="9" x2="20" y2="13" stroke="#c3c4c7" stroke-width="1"/>
					<line x1="23" y1="9" x2="23" y2="13" stroke="#c3c4c7" stroke-width="1"/>
					<line x1="26" y1="9" x2="26" y2="13" stroke="#c3c4c7" stroke-width="1"/>
					<circle cx="50" cy="11" r="1.5" fill="#c3c4c7"/>
					<circle cx="54" cy="11" r="1.5" fill="#c3c4c7"/>
					<circle cx="60" cy="11" r="2" fill="#c3c4c7"/>
					<rect x="63" y="9" width="3" height="4" fill="#c3c4c7" rx="0.5"/>
					<text x="40" y="25" text-anchor="middle" fill="#0073aa" font-size="8" font-family="Arial, sans-serif" font-weight="bold">Full Width</text>
				</svg>';
				break;
		}
		
		return $svg_content;
	}
}
