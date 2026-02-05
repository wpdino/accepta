<?php
/**
 * Hero Section Template Part
 *
 * @package Accepta
 */

// Get hero settings
$hero_enabled = get_theme_mod( 'accepta_hero_enabled', false );
if ( ! $hero_enabled ) {
    return;
}

// Display hero section on front page (static page set as homepage) or blog homepage
// This matches Inspiro theme behavior - works with both frontpage template and blog homepage
if ( ! is_front_page() && ! is_home() ) {
    return;
}

$hero_height = get_theme_mod( 'accepta_hero_height', 'min-height' );
$hero_min_height = get_theme_mod( 'accepta_hero_min_height', 500 );

// Get background data from unified control (JSON format)
$hero_bg_json = get_theme_mod( 'accepta_hero_background', '' );
$hero_bg = json_decode( $hero_bg_json, true );

// Default values
if ( ! is_array( $hero_bg ) ) {
	$hero_bg = array(
		'type' => 'solid',
		'color' => '#6F9C50',
		'gradient_type' => 'linear',
		'gradient_angle' => '90',
		'gradient_start' => '#6F9C50',
		'gradient_end' => '#568F0C',
		'image' => '',
		'size' => 'cover',
		'repeat' => 'no-repeat',
		'position' => 'center',
		'attachment' => 'scroll',
		'overlay_enabled' => false,
		'overlay_color' => '#6F9C50',
		'overlay_opacity' => '0.5',
		'video_type' => 'youtube',
		'video_url' => '',
		'video_mp4' => '',
		'video_autoplay' => true,
		'video_loop' => true,
		'video_muted' => true,
		'video_controls' => false,
	);
}

// Extract background values
$bg_type = isset( $hero_bg['type'] ) ? $hero_bg['type'] : 'solid';
$bg_color = isset( $hero_bg['color'] ) ? $hero_bg['color'] : '#6F9C50';
$bg_gradient = isset( $hero_bg['gradient_type'] ) ? $hero_bg : null;
$bg_image = isset( $hero_bg['image'] ) ? $hero_bg['image'] : '';
$bg_video_type = isset( $hero_bg['video_type'] ) ? $hero_bg['video_type'] : 'youtube';
$bg_video_url = isset( $hero_bg['video_url'] ) ? $hero_bg['video_url'] : '';
$bg_video_mp4 = isset( $hero_bg['video_mp4'] ) ? $hero_bg['video_mp4'] : '';
$bg_video_autoplay = isset( $hero_bg['video_autoplay'] ) ? (bool) $hero_bg['video_autoplay'] : true;
$bg_video_loop = isset( $hero_bg['video_loop'] ) ? (bool) $hero_bg['video_loop'] : true;
$bg_video_muted = isset( $hero_bg['video_muted'] ) ? (bool) $hero_bg['video_muted'] : true;
$bg_video_controls = isset( $hero_bg['video_controls'] ) ? (bool) $hero_bg['video_controls'] : false;
$bg_overlay_enabled = isset( $hero_bg['overlay_enabled'] ) ? (bool) $hero_bg['overlay_enabled'] : false;
$bg_overlay_color = isset( $hero_bg['overlay_color'] ) ? $hero_bg['overlay_color'] : '#000000';
$bg_overlay_opacity = isset( $hero_bg['overlay_opacity'] ) ? floatval( $hero_bg['overlay_opacity'] ) : 0.5;

$hero_heading = get_theme_mod( 'accepta_hero_heading', 'Build Bold! Build Beautiful!' );
$hero_text = get_theme_mod( 'accepta_hero_text', 'Accepta is a flexible, modern WordPress theme engineered for Elementor.' );
$hero_button_text = get_theme_mod( 'accepta_hero_button_text', 'Check Now' );
$hero_button_url = get_theme_mod( 'accepta_hero_button_url', 'https://wpdino.com' );
$hero_button_style = get_theme_mod( 'accepta_hero_button_style', 'primary' );

$hero_heading_color = get_theme_mod( 'accepta_hero_heading_color', '#ffffff' );
$hero_heading_size = get_theme_mod( 'accepta_hero_heading_size', 48 );
$hero_text_color = get_theme_mod( 'accepta_hero_text_color', '#ffffff' );
$hero_text_size = get_theme_mod( 'accepta_hero_text_size', 18 );

// Get width setting
$hero_width = get_theme_mod( 'accepta_hero_width', 'boxed' );

// Calculate width class
$width_class = '';
if ( $hero_width === 'fullwidth' ) {
    $width_class = 'accepta-hero-fullwidth';
} else {
    $width_class = 'accepta-hero-boxed';
}

// Calculate height class
$height_class = '';
if ( $hero_height === 'fullscreen' ) {
    $height_class = 'accepta-hero-fullscreen';
} elseif ( $hero_height === 'min-height' ) {
    $height_class = 'accepta-hero-min-height';
} else {
    $height_class = 'accepta-hero-custom-height';
}

// Build overlay style (overlay opacity is stored as 0-1, not 0-100)
$overlay_style = '';
if ( $bg_overlay_enabled ) {
    $overlay_rgba = accepta_hex_to_rgba( $bg_overlay_color, $bg_overlay_opacity );
    $overlay_style = 'background-color: ' . esc_attr( $overlay_rgba ) . ';';
}
?>

<section class="accepta-hero-section <?php echo esc_attr( $width_class ); ?> <?php echo esc_attr( $height_class ); ?>" 
         data-hero-width="<?php echo esc_attr( $hero_width ); ?>"
         data-hero-height="<?php echo esc_attr( $hero_height ); ?>"
         data-hero-min-height="<?php echo esc_attr( $hero_min_height ); ?>"
         data-hero-fullscreen="<?php echo ( $hero_height === 'fullscreen' ) ? 'true' : 'false'; ?>">
    
    <?php if ( $bg_type === 'video' && ( ! empty( $bg_video_url ) || ! empty( $bg_video_mp4 ) ) ) : ?>
        <div class="accepta-hero-video-background">
            <?php if ( $bg_video_type === 'youtube' || $bg_video_type === 'vimeo' ) : ?>
                <div class="accepta-hero-video-embed" 
                     data-video-type="<?php echo esc_attr( $bg_video_type ); ?>"
                     data-video-url="<?php echo esc_url( $bg_video_url ); ?>"
                     data-autoplay="<?php echo $bg_video_autoplay ? '1' : '0'; ?>"
                     data-loop="<?php echo $bg_video_loop ? '1' : '0'; ?>"
                     data-muted="<?php echo $bg_video_muted ? '1' : '0'; ?>"
                     data-controls="<?php echo $bg_video_controls ? '1' : '0'; ?>"></div>
            <?php elseif ( $bg_video_type === 'mp4' && ! empty( $bg_video_mp4 ) ) : ?>
                <video class="accepta-hero-video-element" 
                       <?php echo $bg_video_autoplay ? 'autoplay' : ''; ?>
                       <?php echo $bg_video_loop ? 'loop' : ''; ?>
                       <?php echo $bg_video_muted ? 'muted' : ''; ?>
                       data-muted="<?php echo $bg_video_muted ? '1' : '0'; ?>">
                    <source src="<?php echo esc_url( $bg_video_mp4 ); ?>" type="video/mp4">
                </video>
            <?php endif; ?>
        </div>
        
        <?php if ( $bg_video_controls ) : ?>
            <?php if ( $bg_video_type === 'youtube' || $bg_video_type === 'vimeo' ) : ?>
                <div class="accepta-hero-video-controls accepta-hero-video-embed-controls">
                    <button class="accepta-hero-video-play-pause" aria-label="<?php esc_attr_e( 'Play/Pause', 'accepta' ); ?>">
                        <span class="accepta-hero-play-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/></svg>
                        </span>
                        <span class="accepta-hero-pause-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M6 4h4v16H6V4zm8 0h4v16h-4V4z"/></svg>
                        </span>
                    </button>
                    <button class="accepta-hero-video-mute-unmute" aria-label="<?php esc_attr_e( 'Mute/Unmute', 'accepta' ); ?>">
                        <span class="accepta-hero-mute-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M16.5 12c0-1.77-1.02-3.29-2.5-4.03v2.21l2.45 2.45c.03-.2.05-.41.05-.63zm2.5 0c0 .94-.2 1.82-.54 2.64l1.51 1.51C20.63 14.91 21 13.5 21 12c0-4.28-2.99-7.86-7-8.77v2.06c2.89.86 5 3.54 5 6.71zM4.27 3L3 4.27 7.73 9H3v6h4l5 5v-6.73l4.25 4.25c-.67.52-1.42.93-2.25 1.18v2.06c1.38-.31 2.63-.95 3.69-1.81L19.73 21 21 19.73l-9-9L4.27 3zM12 4L9.91 6.09 12 8.18V4z"/></svg>
                        </span>
                        <span class="accepta-hero-unmute-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M3 9v6h4l5 5V4L7 9H3zm13.5 3c0-1.77-1.02-3.29-2.5-4.03v8.05c1.48-.73 2.5-2.25 2.5-4.02zM14 3.23v2.06c2.89.86 5 3.54 5 6.71s-2.11 5.85-5 6.71v2.06c4.01-.91 7-4.49 7-8.77s-2.99-7.86-7-8.77z"/></svg>
                        </span>
                    </button>
                </div>
            <?php else : ?>
                <div class="accepta-hero-video-controls">
                    <button class="accepta-hero-video-play-pause" aria-label="<?php esc_attr_e( 'Play/Pause', 'accepta' ); ?>">
                        <span class="accepta-hero-play-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/></svg>
                        </span>
                        <span class="accepta-hero-pause-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M6 4h4v16H6V4zm8 0h4v16h-4V4z"/></svg>
                        </span>
                    </button>
                    <button class="accepta-hero-video-mute-unmute" aria-label="<?php esc_attr_e( 'Mute/Unmute', 'accepta' ); ?>">
                        <span class="accepta-hero-mute-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M16.5 12c0-1.77-1.02-3.29-2.5-4.03v2.21l2.45 2.45c.03-.2.05-.41.05-.63zm2.5 0c0 .94-.2 1.82-.54 2.64l1.51 1.51C20.63 14.91 21 13.5 21 12c0-4.28-2.99-7.86-7-8.77v2.06c2.89.86 5 3.54 5 6.71zM4.27 3L3 4.27 7.73 9H3v6h4l5 5v-6.73l4.25 4.25c-.67.52-1.42.93-2.25 1.18v2.06c1.38-.31 2.63-.95 3.69-1.81L19.73 21 21 19.73l-9-9L4.27 3zM12 4L9.91 6.09 12 8.18V4z"/></svg>
                        </span>
                        <span class="accepta-hero-unmute-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M3 9v6h4l5 5V4L7 9H3zm13.5 3c0-1.77-1.02-3.29-2.5-4.03v8.05c1.48-.73 2.5-2.25 2.5-4.02zM14 3.23v2.06c2.89.86 5 3.54 5 6.71s-2.11 5.85-5 6.71v2.06c4.01-.91 7-4.49 7-8.77s-2.99-7.86-7-8.77z"/></svg>
                        </span>
                    </button>
                </div>
            <?php endif; ?>
        <?php endif; ?>
    <?php endif; ?>
    
    <?php if ( $bg_overlay_enabled ) : ?>
        <div class="accepta-hero-overlay" style="<?php echo esc_attr( $overlay_style ); ?>"></div>
    <?php endif; ?>
    
    <div class="accepta-hero-content-wrapper">
        <div class="container">
            <div class="accepta-hero-content">
                <?php if ( ! empty( $hero_heading ) ) : ?>
                    <h1 class="accepta-hero-heading" 
                        style="color: <?php echo esc_attr( $hero_heading_color ); ?>; font-size: <?php echo esc_attr( $hero_heading_size ); ?>px;">
                        <?php echo wp_kses_post( $hero_heading ); ?>
                    </h1>
                <?php endif; ?>
                
                <?php if ( ! empty( $hero_text ) ) : ?>
                    <div class="accepta-hero-text" 
                         style="color: <?php echo esc_attr( $hero_text_color ); ?>; font-size: <?php echo esc_attr( $hero_text_size ); ?>px;">
                        <?php echo wp_kses_post( wpautop( $hero_text ) ); ?>
                    </div>
                <?php endif; ?>
                
                <?php if ( ! empty( $hero_button_text ) && ! empty( $hero_button_url ) ) : ?>
                    <div class="accepta-hero-button-wrapper">
                        <a href="<?php echo esc_url( $hero_button_url ); ?>" 
                           class="accepta-hero-button accepta-hero-button-<?php echo esc_attr( $hero_button_style ); ?>">
                            <?php echo esc_html( $hero_button_text ); ?>
                        </a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>

