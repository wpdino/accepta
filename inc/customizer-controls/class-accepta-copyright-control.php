<?php
/**
 * Accepta Copyright Control Class
 * 
 * Custom control for copyright text with dynamic tags
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
 * Accepta Copyright Control Class
 */
class Accepta_Copyright_Control extends WP_Customize_Control {

	/**
	 * Control type
	 *
	 * @var string
	 */
	public $type = 'accepta-copyright';

	/**
	 * Available dynamic tags
	 *
	 * @var array
	 */
	public $tags = array();

	/**
	 * Constructor
	 */
	public function __construct( $manager, $id, $args = array() ) {
		parent::__construct( $manager, $id, $args );
		
		// Set default tags
		$this->tags = array(
			'{copyright}' => __( 'Copyright Symbol (©)', 'accepta' ),
			'{current-year}' => __( 'Current Year', 'accepta' ),
			'{site-title}' => __( 'Site Title', 'accepta' ),
			'{site-url}' => __( 'Site URL', 'accepta' ),
			'{theme-name}' => __( 'Theme Name', 'accepta' ),
			'{theme-author}' => __( 'Theme Author', 'accepta' ),
			'{wordpress}' => __( 'WordPress Link', 'accepta' ),
		);
	}

	/**
	 * Enqueue control related scripts/styles
	 */
	public function enqueue() {
		wp_enqueue_script(
			'accepta-copyright-control',
			get_template_directory_uri() . '/inc/customizer-controls/js/copyright-control.js',
			array( 'jquery', 'customize-base' ),
			wp_get_theme()->get( 'Version' ),
			true
		);

		wp_enqueue_style(
			'accepta-copyright-control',
			get_template_directory_uri() . '/inc/customizer-controls/css/copyright-control.css',
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
		?>
		<div class="accepta-copyright-control" data-control-id="<?php echo esc_attr( $this->id ); ?>">
			<?php if ( ! empty( $this->label ) ) : ?>
				<label for="<?php echo esc_attr( $input_id ); ?>" class="customize-control-title">
					<?php echo esc_html( $this->label ); ?>
				</label>
			<?php endif; ?>
			
			<?php if ( ! empty( $this->description ) ) : ?>
				<span id="<?php echo esc_attr( $description_id ); ?>" class="description customize-control-description">
					<?php echo $this->description; ?>
				</span>
			<?php endif; ?>

			<div class="accepta-copyright-editor">
				<div class="accepta-copyright-tags">
					<div class="accepta-copyright-tags-label">
						<?php esc_html_e( 'Available Tags:', 'accepta' ); ?>
					</div>
					<div class="accepta-copyright-tags-buttons">
						<?php foreach ( $this->tags as $tag => $description ) : ?>
							<button type="button" class="accepta-copyright-tag-btn" data-tag="<?php echo esc_attr( $tag ); ?>" title="<?php echo esc_attr( $description ); ?>">
								<?php echo esc_html( $tag ); ?>
							</button>
						<?php endforeach; ?>
					</div>
				</div>

				<div class="accepta-copyright-input-wrapper">
					<textarea
						id="<?php echo esc_attr( $input_id ); ?>"
						class="accepta-copyright-textarea"
						rows="3"
						placeholder="<?php esc_attr_e( 'Enter your copyright text here. Use the tags above to insert dynamic content.', 'accepta' ); ?>"
						<?php echo $describedby_attr; ?>
						<?php $this->link(); ?>
					><?php echo esc_textarea( $this->value() ); ?></textarea>
				</div>

				<div class="accepta-copyright-preview">
					<div class="accepta-copyright-preview-label">
						<?php esc_html_e( 'Preview:', 'accepta' ); ?>
					</div>
					<div class="accepta-copyright-preview-content">
						<?php echo $this->process_tags( $this->value() ); ?>
					</div>
				</div>
			</div>
		</div>
		<?php
	}

	/**
	 * Process dynamic tags in the copyright text
	 *
	 * @param string $text The copyright text with tags
	 * @return string Processed text with tags replaced
	 */
	private function process_tags( $text ) {
		$replacements = array(
			'{copyright}' => '©',
			'{current-year}' => date( 'Y' ),
			'{site-title}' => get_bloginfo( 'name' ),
			'{site-url}' => '<a href="' . esc_url( home_url() ) . '">' . get_bloginfo( 'name' ) . '</a>',
			'{theme-name}' => wp_get_theme()->get( 'Name' ),
			'{theme-author}' => '<a href="' . esc_url( wp_get_theme()->get( 'AuthorURI' ) ) . '" target="_blank">' . wp_get_theme()->get( 'Author' ) . '</a>',
			'{wordpress}' => '<a href="https://wordpress.org/" target="_blank">WordPress</a>',
		);

		return str_replace( array_keys( $replacements ), array_values( $replacements ), $text );
	}
}
