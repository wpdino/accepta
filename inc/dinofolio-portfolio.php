<?php
/**
 * DinoFolio portfolio single integration for Accepta.
 *
 * @package Accepta
 */

/**
 * Whether the current view uses the portfolio hero layout.
 *
 * @return bool
 */
function accepta_is_portfolio_single_hero() {
	return is_singular( 'wpdino_portfolio' );
}

/**
 * Whether the current view is a portfolio taxonomy archive.
 *
 * @return bool
 */
function accepta_is_portfolio_taxonomy_archive() {
	return is_tax( array( 'wpdino_portfolio_category', 'wpdino_portfolio_tag' ) );
}

/**
 * Whether the current view is the portfolio post type archive.
 *
 * @return bool
 */
function accepta_is_portfolio_post_type_archive() {
	return is_post_type_archive( 'wpdino_portfolio' );
}

/**
 * Whether the current view uses the shared portfolio archive layout styles.
 *
 * @return bool
 */
function accepta_is_portfolio_archive_layout() {
	return accepta_is_portfolio_taxonomy_archive() || accepta_is_portfolio_post_type_archive();
}

/**
 * Add body classes for portfolio singles and taxonomy archives.
 *
 * @param array $classes Body classes.
 * @return array
 */
function accepta_portfolio_body_classes( $classes ) {
	if ( accepta_is_portfolio_single_hero() ) {
		$classes[] = 'accepta-portfolio-single';
		$classes[] = 'accepta-has-hero';
	}

	if ( accepta_is_portfolio_archive_layout() ) {
		$classes[] = 'accepta-portfolio-taxonomy-archive';
	}

	if ( accepta_is_portfolio_post_type_archive() ) {
		$classes[] = 'accepta-portfolio-archive';
	}

	return $classes;
}
add_filter( 'body_class', 'accepta_portfolio_body_classes' );

/**
 * Portfolio singles are always full width (no sidebar).
 *
 * @param bool $has_sidebar Whether the theme should show a sidebar.
 * @return bool
 */
function accepta_portfolio_single_has_sidebar( $has_sidebar ) {
	if ( accepta_is_portfolio_single_hero() || accepta_is_portfolio_archive_layout() ) {
		return false;
	}

	return $has_sidebar;
}
add_filter( 'accepta_has_sidebar', 'accepta_portfolio_single_has_sidebar' );

/**
 * Get the attachment ID used for the portfolio single hero image.
 *
 * @param int $post_id Post ID.
 * @return int
 */
function accepta_get_portfolio_hero_image_id( $post_id = 0 ) {
	$post_id = $post_id ? absint( $post_id ) : get_the_ID();

	if ( $post_id < 1 ) {
		return 0;
	}

	if ( class_exists( 'DinoFolio\Util' ) && \DinoFolio\Util::is_portfolio_gallery_format( $post_id ) ) {
		$gallery_ids = \DinoFolio\Util::get_portfolio_gallery_image_ids( $post_id );

		if ( ! empty( $gallery_ids ) ) {
			return (int) $gallery_ids[0];
		}
	}

	$featured_display = get_post_meta( $post_id, '_wpdino_featured_image_display', true );

	if ( 'off' === $featured_display ) {
		return 0;
	}

	$thumbnail_id = get_post_thumbnail_id( $post_id );

	return $thumbnail_id ? (int) $thumbnail_id : 0;
}

/**
 * Read a portfolio post meta value with plugin settings fallback.
 *
 * @param int    $post_id          Post ID.
 * @param string $meta_key         Meta key without prefix.
 * @param string $settings_key     Plugin settings key for fallback.
 * @param mixed  $fallback         Fallback value.
 * @param array  $extra_fallbacks  Additional stored values that should fallback.
 * @return mixed
 */
function accepta_get_portfolio_effective_meta( $post_id, $meta_key, $settings_key, $fallback = '', $extra_fallbacks = array() ) {
	$stored = get_post_meta( absint( $post_id ), '_wpdino_' . $meta_key, true );
	$fallback_values = array_merge( array( '' ), $extra_fallbacks );

	if ( ! in_array( $stored, $fallback_values, true ) ) {
		return $stored;
	}

	if ( class_exists( '\DinoFolio\DinoFolio_Settings' ) ) {
		$settings = \DinoFolio\DinoFolio_Settings::instance();

		if ( $settings && method_exists( $settings, 'get_setting' ) ) {
			$setting_value = $settings->get_setting( $settings_key, null );

			if ( null !== $setting_value && '' !== $setting_value ) {
				return $setting_value;
			}
		}
	}

	return $fallback;
}

/**
 * Get release date and launch button data for the portfolio hero.
 *
 * @param int $post_id Post ID.
 * @return array{show_date:bool,date_label:string,date_value:string,external_url:string,button_label:string}
 */
function accepta_get_portfolio_hero_meta( $post_id = 0 ) {
	$post_id = $post_id ? absint( $post_id ) : get_the_ID();

	if ( $post_id < 1 ) {
		return array(
			'show_date'    => false,
			'date_label'   => '',
			'date_value'   => '',
			'external_url' => '',
			'button_label' => '',
		);
	}

	$date_display = accepta_get_portfolio_effective_meta(
		$post_id,
		'date_display',
		'portfolio_meta_default_date_display',
		'on',
		array( 'default' )
	);
	$date_label = accepta_get_portfolio_effective_meta(
		$post_id,
		'date_label',
		'portfolio_meta_default_date_label',
		esc_html__( 'Date', 'dinofolio' )
	);
	$date_of_work = accepta_get_portfolio_effective_meta(
		$post_id,
		'date_of_work',
		'portfolio_meta_default_date_of_work',
		''
	);
	$external_url = accepta_get_portfolio_effective_meta(
		$post_id,
		'external_url',
		'portfolio_meta_default_external_url',
		''
	);
	$button_label = accepta_get_portfolio_effective_meta(
		$post_id,
		'button_label',
		'portfolio_meta_default_button_label',
		esc_html__( 'Launch', 'dinofolio' )
	);

	return array(
		'show_date'    => ( 'off' !== $date_display ),
		'date_label'   => $date_label ? $date_label : esc_html__( 'Date', 'dinofolio' ),
		'date_value'   => $date_of_work ? $date_of_work : get_the_date( '', $post_id ),
		'external_url' => is_string( $external_url ) ? trim( $external_url ) : '',
		'button_label' => $button_label ? $button_label : esc_html__( 'Launch', 'dinofolio' ),
	);
}

/**
 * Enqueue portfolio single styles.
 *
 * @return void
 */
function accepta_enqueue_portfolio_assets() {
	if ( accepta_is_portfolio_single_hero() ) {
		wp_enqueue_style(
			'accepta-portfolio-single',
			accepta_get_asset_uri( 'assets/css/dinofolio-portfolio-single.css' ),
			array( 'accepta-style' ),
			_ACCEPTA_VERSION
		);
	}

	if ( accepta_is_portfolio_archive_layout() ) {
		wp_enqueue_style(
			'accepta-portfolio-taxonomy',
			accepta_get_asset_uri( 'assets/css/dinofolio-portfolio-taxonomy.css' ),
			array( 'accepta-style', 'dinofolio-portfolio-listing' ),
			_ACCEPTA_VERSION
		);
	}
}
add_action( 'wp_enqueue_scripts', 'accepta_enqueue_portfolio_assets', 20 );

/**
 * Omit the launch CTA from plugin meta output when the Accepta hero already shows it.
 *
 * @param array $template_data Template variables for single-portfolio-meta.php.
 * @param int   $post_id       Portfolio post ID.
 * @return array
 */
function accepta_filter_dinofolio_single_meta_template_data( $template_data, $post_id ) {
	if ( ! accepta_is_portfolio_single_hero() || empty( $template_data['external_url'] ) ) {
		return $template_data;
	}

	$template_data['external_url'] = '';

	return $template_data;
}
add_filter( 'dinofolio_single_meta_template_data', 'accepta_filter_dinofolio_single_meta_template_data', 10, 2 );
