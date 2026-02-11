<?php
/**
 * Accepta Background Control Class
 * 
 * Custom control for advanced background options (solid, gradient, image)
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
 * Accepta Background Control Class
 */
class Accepta_Background_Control extends WP_Customize_Control {

	/**
	 * Control type
	 *
	 * @var string
	 */
	public $type = 'accepta-background';

	/**
	 * Enqueue scripts and styles
	 */
	public function enqueue() {
		// Scripts and styles are now enqueued globally in Accepta_Admin_Customizer
		// This method is kept for compatibility but scripts are loaded via customize_controls_enqueue_scripts hook
	}

	/**
	 * Render the control's content
	 */
	public function render_content() {
		$value = $this->value();
		$data = json_decode( $value, true );
		
		// Set defaults based on control ID (hero uses green, footer uses dark)
		$is_hero = ( $this->id === 'accepta_hero_background' );
		$default_color = $is_hero ? '#6F9C50' : '#2c3e50';
		$default_gradient_start = $is_hero ? '#6F9C50' : '#2c3e50';
		$default_gradient_end = $is_hero ? '#568F0C' : '#34495e';
		$default_overlay_color = $is_hero ? '#6F9C50' : '#000000';
		
		if ( ! is_array( $data ) ) {
			$data = array(
				'type' => 'solid',
				'color' => $default_color,
				'gradient_type' => 'linear',
				'gradient_angle' => '90',
				'gradient_start' => $default_gradient_start,
				'gradient_end' => $default_gradient_end,
				'image' => '',
				'size' => 'cover',
				'repeat' => 'no-repeat',
				'position' => 'center',
				'attachment' => 'scroll',
				'overlay_enabled' => false,
				'overlay_color' => $default_overlay_color,
				'overlay_opacity' => '0.5',
			);
		}
		
		$type = isset( $data['type'] ) ? $data['type'] : 'solid';
		$color = isset( $data['color'] ) ? $data['color'] : $default_color;
		$gradient_type = isset( $data['gradient_type'] ) ? $data['gradient_type'] : 'linear';
		$gradient_angle = isset( $data['gradient_angle'] ) ? $data['gradient_angle'] : '90';
		$gradient_start = isset( $data['gradient_start'] ) ? $data['gradient_start'] : $default_gradient_start;
		$gradient_end = isset( $data['gradient_end'] ) ? $data['gradient_end'] : $default_gradient_end;
		$image = isset( $data['image'] ) ? $data['image'] : '';
		$size = isset( $data['size'] ) ? $data['size'] : 'cover';
		$repeat = isset( $data['repeat'] ) ? $data['repeat'] : 'no-repeat';
		$position = isset( $data['position'] ) ? $data['position'] : 'center';
		$attachment = isset( $data['attachment'] ) ? $data['attachment'] : 'scroll';
		$overlay_enabled = isset( $data['overlay_enabled'] ) ? (bool) $data['overlay_enabled'] : false;
		$overlay_color = isset( $data['overlay_color'] ) ? $data['overlay_color'] : $default_overlay_color;
		$overlay_opacity = isset( $data['overlay_opacity'] ) ? $data['overlay_opacity'] : '0.5';
		
		// Default values for reset (use hero defaults if this is hero control)
		$default_values = array(
			'type' => 'solid',
			'color' => $default_color,
			'gradient_type' => 'linear',
			'gradient_angle' => '90',
			'gradient_start' => $default_gradient_start,
			'gradient_end' => $default_gradient_end,
			'image' => '',
			'size' => 'cover',
			'repeat' => 'no-repeat',
			'position' => 'center',
			'attachment' => 'scroll',
			'overlay_enabled' => false,
			'overlay_color' => $default_overlay_color,
			'overlay_opacity' => '0.5',
		);
		$default_values_json = wp_json_encode( $default_values );
		// Use single quotes for attribute to avoid conflicts with JSON double quotes
		$default_values_encoded = esc_js( $default_values_json );
		?>
		<div class="accepta-background-control-wrapper" data-control-id="<?php echo esc_attr( $this->id ); ?>" data-default-values='<?php echo $default_values_encoded; ?>'>
			<div class="accepta-background-control-header">
				<?php if ( ! empty( $this->label ) ) : ?>
					<span class="customize-control-title"><?php echo esc_html( $this->label ); ?></span>
				<?php endif; ?>
				<button type="button" class="button-link accepta-background-reset" title="<?php esc_attr_e( 'Reset to defaults', 'accepta' ); ?>">
					<?php esc_html_e( 'Reset', 'accepta' ); ?>
				</button>
			</div>
			
			<?php if ( ! empty( $this->description ) ) : ?>
				<span class="description customize-control-description"><?php echo $this->description; ?></span>
			<?php endif; ?>

			<input type="hidden" <?php $this->link(); ?> value="<?php echo esc_attr( $value ); ?>" class="accepta-background-hidden" />

			<div class="accepta-background-options">
				<!-- Background Type Selector -->
				<div class="accepta-background-type-selector">
					<label>
						<input type="radio" name="<?php echo esc_attr( $this->id ); ?>_type" value="solid" <?php checked( $type, 'solid' ); ?> />
						<span><?php esc_html_e( 'Solid', 'accepta' ); ?></span>
					</label>
					<label>
						<input type="radio" name="<?php echo esc_attr( $this->id ); ?>_type" value="gradient" <?php checked( $type, 'gradient' ); ?> />
						<span><?php esc_html_e( 'Gradient', 'accepta' ); ?></span>
					</label>
				<label>
					<input type="radio" name="<?php echo esc_attr( $this->id ); ?>_type" value="image" <?php checked( $type, 'image' ); ?> />
					<span><?php esc_html_e( 'Image', 'accepta' ); ?></span>
				</label>
				<?php if ( $this->id === 'accepta_hero_background' ) : ?>
				<label>
					<input type="radio" name="<?php echo esc_attr( $this->id ); ?>_type" value="video" <?php checked( $type, 'video' ); ?> />
					<span><?php esc_html_e( 'Video', 'accepta' ); ?></span>
				</label>
				<?php endif; ?>
			</div>

				<!-- Solid Color Option -->
				<div class="accepta-background-option accepta-background-solid" style="display: <?php echo ( $type === 'solid' ) ? 'block' : 'none'; ?>;">
					<label>
						<span class="customize-control-title"><?php esc_html_e( 'Color', 'accepta' ); ?></span>
						<input type="text" class="accepta-background-color" value="<?php echo esc_attr( $color ); ?>" data-default-color="<?php echo esc_attr( $default_color ); ?>" />
					</label>
				</div>

				<!-- Gradient Option -->
				<div class="accepta-background-option accepta-background-gradient" style="display: <?php echo ( $type === 'gradient' ) ? 'block' : 'none'; ?>;">
					<label>
						<span class="customize-control-title"><?php esc_html_e( 'Gradient Type', 'accepta' ); ?></span>
						<select class="accepta-background-gradient-type">
							<option value="linear" <?php selected( $gradient_type, 'linear' ); ?>><?php esc_html_e( 'Linear', 'accepta' ); ?></option>
							<option value="radial" <?php selected( $gradient_type, 'radial' ); ?>><?php esc_html_e( 'Radial', 'accepta' ); ?></option>
						</select>
					</label>
					
					<label class="accepta-gradient-angle" style="display: <?php echo ( $gradient_type === 'linear' ) ? 'block' : 'none'; ?>;">
						<span class="customize-control-title"><?php esc_html_e( 'Angle', 'accepta' ); ?></span>
						<input type="number" class="accepta-background-gradient-angle" value="<?php echo esc_attr( $gradient_angle ); ?>" min="0" max="360" step="1" />
						<span class="description"><?php esc_html_e( 'degrees', 'accepta' ); ?></span>
					</label>
					
					<label>
						<span class="customize-control-title"><?php esc_html_e( 'Start Color', 'accepta' ); ?></span>
						<input type="text" class="accepta-background-gradient-start" value="<?php echo esc_attr( $gradient_start ); ?>" data-default-color="<?php echo esc_attr( $default_gradient_start ); ?>" />
					</label>
					
					<label>
						<span class="customize-control-title"><?php esc_html_e( 'End Color', 'accepta' ); ?></span>
						<input type="text" class="accepta-background-gradient-end" value="<?php echo esc_attr( $gradient_end ); ?>" data-default-color="<?php echo esc_attr( $default_gradient_end ); ?>" />
					</label>
				</div>

				<!-- Image Option -->
				<div class="accepta-background-option accepta-background-image" style="display: <?php echo ( $type === 'image' ) ? 'block' : 'none'; ?>;">
					<div class="accepta-background-image-upload">
						<button type="button" class="button accepta-background-image-button">
							<?php esc_html_e( 'Select Image', 'accepta' ); ?>
						</button>
						<button type="button" class="button accepta-background-image-remove" style="display: <?php echo ( ! empty( $image ) ) ? 'inline-block' : 'none'; ?>;">
							<?php esc_html_e( 'Remove', 'accepta' ); ?>
						</button>
					</div>
					
					<div class="accepta-background-image-preview" style="display: <?php echo ( ! empty( $image ) ) ? 'block' : 'none'; ?>;">
						<img src="<?php echo esc_url( $image ); ?>" alt="" />
					</div>
					
					<input type="hidden" class="accepta-background-image-url" value="<?php echo esc_url( $image ); ?>" />
					
					<label>
						<span class="customize-control-title"><?php esc_html_e( 'Size', 'accepta' ); ?></span>
						<select class="accepta-background-image-size">
							<option value="auto" <?php selected( $size, 'auto' ); ?>><?php esc_html_e( 'Auto', 'accepta' ); ?></option>
							<option value="cover" <?php selected( $size, 'cover' ); ?>><?php esc_html_e( 'Cover', 'accepta' ); ?></option>
							<option value="contain" <?php selected( $size, 'contain' ); ?>><?php esc_html_e( 'Contain', 'accepta' ); ?></option>
							<option value="100% 100%" <?php selected( $size, '100% 100%' ); ?>><?php esc_html_e( '100%', 'accepta' ); ?></option>
						</select>
					</label>
					
					<label>
						<span class="customize-control-title"><?php esc_html_e( 'Repeat', 'accepta' ); ?></span>
						<select class="accepta-background-image-repeat">
							<option value="no-repeat" <?php selected( $repeat, 'no-repeat' ); ?>><?php esc_html_e( 'No Repeat', 'accepta' ); ?></option>
							<option value="repeat" <?php selected( $repeat, 'repeat' ); ?>><?php esc_html_e( 'Repeat', 'accepta' ); ?></option>
							<option value="repeat-x" <?php selected( $repeat, 'repeat-x' ); ?>><?php esc_html_e( 'Repeat X', 'accepta' ); ?></option>
							<option value="repeat-y" <?php selected( $repeat, 'repeat-y' ); ?>><?php esc_html_e( 'Repeat Y', 'accepta' ); ?></option>
						</select>
					</label>
					
					<label>
						<span class="customize-control-title"><?php esc_html_e( 'Position', 'accepta' ); ?></span>
						<select class="accepta-background-image-position">
							<option value="left top" <?php selected( $position, 'left top' ); ?>><?php esc_html_e( 'Left Top', 'accepta' ); ?></option>
							<option value="left center" <?php selected( $position, 'left center' ); ?>><?php esc_html_e( 'Left Center', 'accepta' ); ?></option>
							<option value="left bottom" <?php selected( $position, 'left bottom' ); ?>><?php esc_html_e( 'Left Bottom', 'accepta' ); ?></option>
							<option value="center top" <?php selected( $position, 'center top' ); ?>><?php esc_html_e( 'Center Top', 'accepta' ); ?></option>
							<option value="center center" <?php selected( $position, 'center center' ); ?>><?php esc_html_e( 'Center Center', 'accepta' ); ?></option>
							<option value="center bottom" <?php selected( $position, 'center bottom' ); ?>><?php esc_html_e( 'Center Bottom', 'accepta' ); ?></option>
							<option value="right top" <?php selected( $position, 'right top' ); ?>><?php esc_html_e( 'Right Top', 'accepta' ); ?></option>
							<option value="right center" <?php selected( $position, 'right center' ); ?>><?php esc_html_e( 'Right Center', 'accepta' ); ?></option>
							<option value="right bottom" <?php selected( $position, 'right bottom' ); ?>><?php esc_html_e( 'Right Bottom', 'accepta' ); ?></option>
							<option value="center" <?php selected( $position, 'center' ); ?>><?php esc_html_e( 'Center', 'accepta' ); ?></option>
						</select>
					</label>
					
					<label>
						<span class="customize-control-title"><?php esc_html_e( 'Background Effect', 'accepta' ); ?></span>
						<select class="accepta-background-image-attachment">
							<option value="scroll" <?php selected( $attachment, 'scroll' ); ?>><?php esc_html_e( 'Scroll', 'accepta' ); ?></option>
							<option value="fixed" <?php selected( $attachment, 'fixed' ); ?>><?php esc_html_e( 'Fixed', 'accepta' ); ?></option>
							<?php if ( $this->id === 'accepta_hero_background' ) : ?>
							<option value="parallax" <?php selected( $attachment, 'parallax' ); ?>><?php esc_html_e( 'Parallax', 'accepta' ); ?></option>
							<?php endif; ?>
						</select>
					</label>
				</div>
				</div>

				<?php if ( $this->id === 'accepta_hero_background' ) : ?>
				<!-- Video Option (only for hero section) -->
				<?php
				$video_type = isset( $data['video_type'] ) ? $data['video_type'] : 'youtube';
				$video_url = isset( $data['video_url'] ) ? $data['video_url'] : '';
				$video_mp4 = isset( $data['video_mp4'] ) ? $data['video_mp4'] : '';
				$video_autoplay = isset( $data['video_autoplay'] ) ? (bool) $data['video_autoplay'] : true;
				$video_loop = isset( $data['video_loop'] ) ? (bool) $data['video_loop'] : true;
				$video_muted = isset( $data['video_muted'] ) ? (bool) $data['video_muted'] : true;
				$video_controls = isset( $data['video_controls'] ) ? (bool) $data['video_controls'] : false;
				?>
				<div class="accepta-background-option accepta-background-video" style="display: <?php echo ( $type === 'video' ) ? 'block' : 'none'; ?>;">
					<label>
						<span class="customize-control-title"><?php esc_html_e( 'Video Source', 'accepta' ); ?></span>
						<select class="accepta-background-video-type">
							<option value="youtube" <?php selected( $video_type, 'youtube' ); ?>><?php esc_html_e( 'YouTube', 'accepta' ); ?></option>
							<option value="vimeo" <?php selected( $video_type, 'vimeo' ); ?>><?php esc_html_e( 'Vimeo', 'accepta' ); ?></option>
							<option value="mp4" <?php selected( $video_type, 'mp4' ); ?>><?php esc_html_e( 'Local MP4', 'accepta' ); ?></option>
						</select>
					</label>
					
					<div class="accepta-video-url-option" style="display: <?php echo ( in_array( $video_type, array( 'youtube', 'vimeo' ) ) ) ? 'block' : 'none'; ?>;">
						<label>
							<span class="customize-control-title"><?php esc_html_e( 'Video URL', 'accepta' ); ?></span>
							<input type="url" class="accepta-background-video-url" value="<?php echo esc_url( $video_url ); ?>" placeholder="<?php esc_attr_e( 'Enter YouTube or Vimeo URL', 'accepta' ); ?>" />
						</label>
					</div>
					
					<div class="accepta-video-mp4-option" style="display: <?php echo ( $video_type === 'mp4' ) ? 'block' : 'none'; ?>;">
						<div class="accepta-background-video-upload">
							<button type="button" class="button accepta-background-video-button">
								<?php esc_html_e( 'Select MP4 Video', 'accepta' ); ?>
							</button>
							<button type="button" class="button accepta-background-video-remove" style="display: <?php echo ( ! empty( $video_mp4 ) ) ? 'inline-block' : 'none'; ?>;">
								<?php esc_html_e( 'Remove', 'accepta' ); ?>
							</button>
						</div>
						<input type="hidden" class="accepta-background-video-mp4" value="<?php echo esc_url( $video_mp4 ); ?>" />
					</div>
					
					<label class="accepta-video-checkbox">
						<input type="checkbox" class="accepta-background-video-autoplay" <?php checked( $video_autoplay, true ); ?> />
						<span class="customize-control-title"><?php esc_html_e( 'Autoplay Video', 'accepta' ); ?></span>
					</label>
					
					<label class="accepta-video-checkbox">
						<input type="checkbox" class="accepta-background-video-loop" <?php checked( $video_loop, true ); ?> />
						<span class="customize-control-title"><?php esc_html_e( 'Loop Video', 'accepta' ); ?></span>
					</label>
					
					<label class="accepta-video-checkbox">
						<input type="checkbox" class="accepta-background-video-muted" <?php checked( $video_muted, true ); ?> />
						<span class="customize-control-title"><?php esc_html_e( 'Mute Video', 'accepta' ); ?></span>
					</label>
					
					<label class="accepta-video-checkbox">
						<input type="checkbox" class="accepta-background-video-controls" <?php checked( $video_controls, true ); ?> />
						<span class="customize-control-title"><?php esc_html_e( 'Show Video Controls', 'accepta' ); ?></span>
					</label>
				</div>
				<?php endif; ?>
				
				<!-- Shared Overlay Options (show for image and video types) -->
				<div class="accepta-background-overlay" style="display: <?php echo ( in_array( $type, array( 'image', 'video' ) ) ) ? 'block' : 'none'; ?>;">
					<label class="accepta-overlay-toggle">
						<input type="checkbox" class="accepta-background-overlay-enabled" <?php checked( $overlay_enabled, true ); ?> />
						<span class="customize-control-title"><?php esc_html_e( 'Enable Overlay', 'accepta' ); ?></span>
					</label>
					
					<div class="accepta-overlay-options" style="display: <?php echo $overlay_enabled ? 'block' : 'none'; ?>;">
						<label>
							<span class="customize-control-title"><?php esc_html_e( 'Overlay Color', 'accepta' ); ?></span>
							<input type="text" class="accepta-background-overlay-color" value="<?php echo esc_attr( $overlay_color ); ?>" data-default-color="<?php echo esc_attr( $default_overlay_color ); ?>" />
						</label>
						
						<label>
							<span class="customize-control-title"><?php esc_html_e( 'Overlay Opacity', 'accepta' ); ?></span>
							<div class="accepta-opacity-control">
								<input type="range" class="accepta-background-overlay-opacity" value="<?php echo esc_attr( $overlay_opacity ); ?>" min="0" max="1" step="0.1" />
								<span class="accepta-opacity-value"><?php echo esc_html( $overlay_opacity ); ?></span>
							</div>
						</label>
					</div>
				</div>
			</div>
		</div>
		<?php
	}

}

