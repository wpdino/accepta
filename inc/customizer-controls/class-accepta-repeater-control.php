<?php
/**
 * Accepta Repeater Control Class
 * 
 * Custom control for repeatable fields like social media links
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
 * Accepta Repeater Control Class
 */
class Accepta_Repeater_Control extends WP_Customize_Control {

	/**
	 * Control type
	 *
	 * @var string
	 */
	public $type = 'accepta-repeater';

	/**
	 * Fields configuration
	 *
	 * @var array
	 */
	public $fields = array();

	/**
	 * Maximum number of items
	 *
	 * @var int
	 */
	public $max_items = 10;

	/**
	 * Minimum number of items
	 *
	 * @var int
	 */
	public $min_items = 0;

	/**
	 * Default item structure
	 *
	 * @var array
	 */
	public $default_item = array();

	/**
	 * Constructor
	 */
	public function __construct( $manager, $id, $args = array() ) {
		parent::__construct( $manager, $id, $args );
		
		// Set default fields if not provided
		if ( empty( $this->fields ) ) {
			$this->fields = array(
				'label' => array(
					'type' => 'text',
					'label' => __( 'Label', 'accepta' ),
					'placeholder' => __( 'Enter label', 'accepta' ),
				),
				'url' => array(
					'type' => 'url',
					'label' => __( 'URL', 'accepta' ),
					'placeholder' => __( 'https://example.com', 'accepta' ),
				),
			);
		}
	}

	/**
	 * Enqueue control related scripts/styles
	 */
	public function enqueue() {
		// Enqueue Font Awesome for icon previews (use theme's local version)
		wp_enqueue_style(
			'accepta-font-awesome',
			get_template_directory_uri() . '/assets/fonts/fontawesome/all.min.css',
			array(),
			'6.4.0'
		);

		wp_enqueue_script(
			'accepta-repeater-control',
			get_template_directory_uri() . '/inc/customizer-controls/js/repeater-control.js',
			array( 'jquery', 'customize-base', 'jquery-ui-sortable' ),
			wp_get_theme()->get( 'Version' ),
			true
		);

		wp_enqueue_style(
			'accepta-repeater-control',
			get_template_directory_uri() . '/inc/customizer-controls/css/repeater-control.css',
			array( 'accepta-font-awesome' ),
			wp_get_theme()->get( 'Version' )
		);

		// Localize script for translations and theme data
		wp_localize_script(
			'accepta-repeater-control',
			'acceptaRepeaterL10n',
			array(
				'confirmDelete' => __( 'Are you sure you want to delete this item?', 'accepta' ),
				'addNew' => __( 'Add New Item', 'accepta' ),
				'maxItems' => sprintf( __( 'Maximum %d items allowed', 'accepta' ), $this->max_items ),
				'templateUrl' => get_template_directory_uri(),
			)
		);
	}

	/**
	 * Render the control's content
	 */
	public function render_content() {
		$input_id = '_customize-input-' . $this->id;
		$describedby_attr = '';
		
		if ( ! empty( $this->description ) ) {
			$describedby_attr = 'aria-describedby="' . esc_attr( $input_id ) . '-description"';
		}
		?>
		<div class="accepta-repeater-control" data-control-id="<?php echo esc_attr( $this->id ); ?>" data-max-items="<?php echo esc_attr( $this->max_items ); ?>">
			<?php if ( ! empty( $this->label ) ) : ?>
				<span class="customize-control-title"><?php echo esc_html( $this->label ); ?></span>
			<?php endif; ?>
			
			<?php if ( ! empty( $this->description ) ) : ?>
				<span class="description customize-control-description" id="<?php echo esc_attr( $input_id ); ?>-description">
					<?php echo $this->description; ?>
				</span>
			<?php endif; ?>

			<div class="accepta-repeater-items">
				<?php $this->render_items(); ?>
			</div>

			<div class="accepta-repeater-actions">
				<button type="button" class="button accepta-repeater-add-item">
					<span class="dashicons dashicons-plus-alt"></span>
					<?php esc_html_e( 'Add Item', 'accepta' ); ?>
				</button>
			</div>

			<input type="hidden" <?php $this->link(); ?> value="<?php echo esc_attr( $this->value() ); ?>" class="accepta-repeater-input" />
		</div>

		<!-- Item template for JavaScript -->
		<script type="text/html" id="tmpl-accepta-repeater-item-<?php echo esc_attr( $this->id ); ?>">
			<?php $this->render_item_template(); ?>
		</script>
		<?php
	}

	/**
	 * Render existing items
	 */
	private function render_items() {
		$value = $this->value();
		$items = array();
		
		if ( ! empty( $value ) ) {
			$items = json_decode( $value, true );
			if ( ! is_array( $items ) ) {
				$items = array();
			}
		}

		if ( empty( $items ) && ! empty( $this->default_item ) ) {
			$items = array( $this->default_item );
		}

		foreach ( $items as $index => $item ) {
			$this->render_item( $item, $index );
		}
	}

	/**
	 * Render a single item
	 */
	private function render_item( $item = array(), $index = 0 ) {
		?>
		<div class="accepta-repeater-item" data-index="<?php echo esc_attr( $index ); ?>">
			<div class="accepta-repeater-item-header">
				<span class="accepta-repeater-item-title">
					<?php 
					$title = ! empty( $item['label'] ) ? $item['label'] : sprintf( __( 'Item %d', 'accepta' ), $index + 1 );
					echo esc_html( $title );
					?>
				</span>
				<div class="accepta-repeater-item-actions">
					<button type="button" class="accepta-repeater-item-toggle" title="<?php esc_attr_e( 'Toggle', 'accepta' ); ?>">
						<span class="dashicons dashicons-arrow-down-alt2"></span>
						<span class="dashicons dashicons-arrow-up-alt2"></span>
					</button>
					<button type="button" class="accepta-repeater-item-delete" title="<?php esc_attr_e( 'Delete', 'accepta' ); ?>">
						<span class="dashicons dashicons-trash"></span>
					</button>
				</div>
			</div>
			<div class="accepta-repeater-item-content">
				<?php $this->render_item_fields( $item, $index ); ?>
			</div>
		</div>
		<?php
	}

	/**
	 * Render item template for JavaScript
	 */
	private function render_item_template() {
		?>
		<div class="accepta-repeater-item" data-index="{{data.index}}">
			<div class="accepta-repeater-item-header">
				<span class="accepta-repeater-item-title">
					<?php printf( __( 'Item %s', 'accepta' ), '{{data.index + 1}}' ); ?>
				</span>
				<div class="accepta-repeater-item-actions">
					<button type="button" class="accepta-repeater-item-toggle" title="<?php esc_attr_e( 'Toggle', 'accepta' ); ?>">
						<span class="dashicons dashicons-arrow-down-alt2"></span>
						<span class="dashicons dashicons-arrow-up-alt2"></span>
					</button>
					<button type="button" class="accepta-repeater-item-delete" title="<?php esc_attr_e( 'Delete', 'accepta' ); ?>">
						<span class="dashicons dashicons-trash"></span>
					</button>
				</div>
			</div>
			<div class="accepta-repeater-item-content">
				<?php $this->render_item_fields_template(); ?>
			</div>
		</div>
		<?php
	}

	/**
	 * Render fields for an item
	 */
	private function render_item_fields( $item = array(), $index = 0 ) {
		foreach ( $this->fields as $field_key => $field_config ) {
			$field_value = isset( $item[ $field_key ] ) ? $item[ $field_key ] : '';
			$this->render_field( $field_key, $field_config, $field_value, $index );
		}
	}

	/**
	 * Render fields template for JavaScript
	 */
	private function render_item_fields_template() {
		foreach ( $this->fields as $field_key => $field_config ) {
			$this->render_field_template( $field_key, $field_config );
		}
	}

	/**
	 * Render a single field
	 */
	private function render_field( $field_key, $field_config, $value = '', $index = 0 ) {
		$field_id = $this->id . '_' . $index . '_' . $field_key;
		$field_name = $this->id . '[' . $index . '][' . $field_key . ']';
		?>
		<div class="accepta-repeater-field accepta-repeater-field-<?php echo esc_attr( $field_config['type'] ); ?>">
			<label for="<?php echo esc_attr( $field_id ); ?>" class="accepta-repeater-field-label">
				<?php echo esc_html( $field_config['label'] ); ?>
			</label>
			<?php $this->render_field_input( $field_key, $field_config, $value, $field_id, $field_name ); ?>
		</div>
		<?php
	}

	/**
	 * Render field template for JavaScript
	 */
	private function render_field_template( $field_key, $field_config ) {
		?>
		<div class="accepta-repeater-field accepta-repeater-field-<?php echo esc_attr( $field_config['type'] ); ?>">
			<label class="accepta-repeater-field-label">
				<?php echo esc_html( $field_config['label'] ); ?>
			</label>
			<?php $this->render_field_input_template( $field_key, $field_config ); ?>
		</div>
		<?php
	}

	/**
	 * Render field input
	 */
	private function render_field_input( $field_key, $field_config, $value, $field_id, $field_name ) {
		$placeholder = isset( $field_config['placeholder'] ) ? $field_config['placeholder'] : '';
		
		switch ( $field_config['type'] ) {
			case 'textarea':
				?>
				<textarea 
					id="<?php echo esc_attr( $field_id ); ?>"
					class="accepta-repeater-field-input"
					data-field="<?php echo esc_attr( $field_key ); ?>"
					placeholder="<?php echo esc_attr( $placeholder ); ?>"
					rows="3"
				><?php echo esc_textarea( $value ); ?></textarea>
				<?php
				break;

			case 'select':
				?>
				<select 
					id="<?php echo esc_attr( $field_id ); ?>"
					class="accepta-repeater-field-input"
					data-field="<?php echo esc_attr( $field_key ); ?>"
				>
					<?php if ( isset( $field_config['options'] ) && is_array( $field_config['options'] ) ) : ?>
						<?php foreach ( $field_config['options'] as $option_value => $option_label ) : ?>
							<option value="<?php echo esc_attr( $option_value ); ?>" <?php selected( $value, $option_value ); ?>>
								<?php echo esc_html( $option_label ); ?>
							</option>
						<?php endforeach; ?>
					<?php endif; ?>
				</select>
				<?php
				break;

			case 'media':
				?>
				<div class="accepta-repeater-media-field">
					<input 
						type="hidden"
						id="<?php echo esc_attr( $field_id ); ?>"
						class="accepta-repeater-field-input accepta-repeater-media-input"
						data-field="<?php echo esc_attr( $field_key ); ?>"
						value="<?php echo esc_attr( $value ); ?>"
					/>
					<div class="accepta-repeater-media-preview">
						<?php if ( $value ) : ?>
							<img src="<?php echo esc_url( $value ); ?>" alt="" />
						<?php endif; ?>
					</div>
					<div class="accepta-repeater-media-actions">
						<button type="button" class="button accepta-repeater-media-select">
							<?php esc_html_e( 'Select Image', 'accepta' ); ?>
						</button>
						<button type="button" class="button accepta-repeater-media-remove" <?php echo $value ? '' : 'style="display:none;"'; ?>>
							<?php esc_html_e( 'Remove', 'accepta' ); ?>
						</button>
					</div>
				</div>
				<?php
				break;

			case 'fontawesome':
				?>
				<div class="accepta-repeater-fontawesome-field">
					<input 
						type="text"
						id="<?php echo esc_attr( $field_id ); ?>"
						class="accepta-repeater-field-input accepta-repeater-fontawesome-input"
						data-field="<?php echo esc_attr( $field_key ); ?>"
						value="<?php echo esc_attr( $value ); ?>"
						placeholder="<?php echo esc_attr( $placeholder ); ?>"
						readonly
					/>
					<div class="accepta-repeater-fontawesome-preview">
						<?php if ( $value ) : ?>
							<i class="<?php echo esc_attr( $value ); ?>"></i>
						<?php endif; ?>
					</div>
					<div class="accepta-repeater-fontawesome-actions">
						<button type="button" class="button accepta-repeater-fontawesome-select">
							<?php esc_html_e( 'Select Icon', 'accepta' ); ?>
						</button>
						<button type="button" class="button accepta-repeater-fontawesome-remove" <?php echo $value ? '' : 'style="display:none;"'; ?>>
							<?php esc_html_e( 'Remove', 'accepta' ); ?>
						</button>
					</div>
				</div>
				<?php
				break;

			default: // text, url, email, etc.
				?>
				<input 
					type="<?php echo esc_attr( $field_config['type'] ); ?>"
					id="<?php echo esc_attr( $field_id ); ?>"
					class="accepta-repeater-field-input"
					data-field="<?php echo esc_attr( $field_key ); ?>"
					value="<?php echo esc_attr( $value ); ?>"
					placeholder="<?php echo esc_attr( $placeholder ); ?>"
				/>
				<?php
				break;
		}
	}

	/**
	 * Render field input template for JavaScript
	 */
	private function render_field_input_template( $field_key, $field_config ) {
		$placeholder = isset( $field_config['placeholder'] ) ? $field_config['placeholder'] : '';
		
		switch ( $field_config['type'] ) {
			case 'textarea':
				?>
				<textarea 
					class="accepta-repeater-field-input"
					data-field="<?php echo esc_attr( $field_key ); ?>"
					placeholder="<?php echo esc_attr( $placeholder ); ?>"
					rows="3"
				></textarea>
				<?php
				break;

			case 'select':
				?>
				<select 
					class="accepta-repeater-field-input"
					data-field="<?php echo esc_attr( $field_key ); ?>"
				>
					<?php if ( isset( $field_config['options'] ) && is_array( $field_config['options'] ) ) : ?>
						<?php foreach ( $field_config['options'] as $option_value => $option_label ) : ?>
							<?php 
							// Set fontawesome as default for icon_type field
							$is_selected = ( $field_key === 'icon_type' && $option_value === 'fontawesome' );
							?>
							<option value="<?php echo esc_attr( $option_value ); ?>" <?php echo $is_selected ? 'selected' : ''; ?>>
								<?php echo esc_html( $option_label ); ?>
							</option>
						<?php endforeach; ?>
					<?php endif; ?>
				</select>
				<?php
				break;

			case 'media':
				?>
				<div class="accepta-repeater-media-field">
					<input 
						type="hidden"
						class="accepta-repeater-field-input accepta-repeater-media-input"
						data-field="<?php echo esc_attr( $field_key ); ?>"
					/>
					<div class="accepta-repeater-media-preview"></div>
					<div class="accepta-repeater-media-actions">
						<button type="button" class="button accepta-repeater-media-select">
							<?php esc_html_e( 'Select Image', 'accepta' ); ?>
						</button>
						<button type="button" class="button accepta-repeater-media-remove" style="display:none;">
							<?php esc_html_e( 'Remove', 'accepta' ); ?>
						</button>
					</div>
				</div>
				<?php
				break;

			case 'fontawesome':
				?>
				<div class="accepta-repeater-fontawesome-field">
					<input 
						type="text"
						class="accepta-repeater-field-input accepta-repeater-fontawesome-input"
						data-field="<?php echo esc_attr( $field_key ); ?>"
						placeholder="<?php echo esc_attr( $placeholder ); ?>"
						readonly
					/>
					<div class="accepta-repeater-fontawesome-preview"></div>
					<div class="accepta-repeater-fontawesome-actions">
						<button type="button" class="button accepta-repeater-fontawesome-select">
							<?php esc_html_e( 'Select Icon', 'accepta' ); ?>
						</button>
						<button type="button" class="button accepta-repeater-fontawesome-remove" style="display:none;">
							<?php esc_html_e( 'Remove', 'accepta' ); ?>
						</button>
					</div>
				</div>
				<?php
				break;

			default: // text, url, email, etc.
				?>
				<input 
					type="<?php echo esc_attr( $field_config['type'] ); ?>"
					class="accepta-repeater-field-input"
					data-field="<?php echo esc_attr( $field_key ); ?>"
					placeholder="<?php echo esc_attr( $placeholder ); ?>"
				/>
				<?php
				break;
		}
	}
}
