<?php
/**
 * Accepta functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package Accepta
 */

if ( ! defined( '_ACCEPTA_VERSION' ) ) {
	// Replace the version number of the theme on each release.
	$theme = wp_get_theme();
	define( '_ACCEPTA_VERSION', $theme->get( 'Version' ) );
}

/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which
 * runs before the init hook. The init hook is too late for some features, such
 * as indicating support for post thumbnails.
 */
function accepta_setup() {
	// Add default posts and comments RSS feed links to head.
	add_theme_support( 'automatic-feed-links' );

	/*
		* Let WordPress manage the document title.
		* By adding theme support, we declare that this theme does not use a
		* hard-coded <title> tag in the document head, and expect WordPress to
		* provide it for us.
		*/
	add_theme_support( 'title-tag' );

	/*
		* Enable support for Post Thumbnails on posts and pages.
		*
		* @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
		*/
	add_theme_support( 'post-thumbnails' );

	// This theme uses wp_nav_menu() in one location.
	register_nav_menus(
		array(
			'menu-1' => esc_html__( 'Primary', 'accepta' ),
		)
	);

	/*
		* Switch default core markup for search form, comment form, and comments
		* to output valid HTML5.
		*/
	add_theme_support(
		'html5',
		array(
			'search-form',
			'comment-form',
			'comment-list',
			'gallery',
			'caption',
			'style',
			'script',
			'navigation-widgets',
		)
	);

	// Set up the WordPress core custom background feature.
	add_theme_support(
		'custom-background',
		apply_filters(
			'accepta_custom_background_args',
			array(
				'default-color' => 'ffffff',
				'default-image' => '',
			)
		)
	);

	// Add theme support for selective refresh for widgets.
	add_theme_support( 'customize-selective-refresh-widgets' );

	/**
	 * Add support for core custom logo.
	 *
	 * @link https://codex.wordpress.org/Theme_Logo
	 */
	add_theme_support(
		'custom-logo',
		array(
			'height'      => 250,
			'width'       => 250,
			'flex-width'  => true,
			'flex-height' => true,
		)
	);

	/*
	 * Starter content: sample pages, menu, and widgets shown in the Customizer
	 * preview before the theme is activated. Only loaded in customize preview.
	 */
	if ( is_customize_preview() ) {
		require_once get_template_directory() . '/inc/starter-content.php';
		add_theme_support( 'starter-content', accepta_get_starter_content() );
	}
}
add_action( 'after_setup_theme', 'accepta_setup' );

/**
 * Load theme textdomain for translations.
 * 
 * Translations can be filed in the /languages/ directory.
 * If you're building a theme based on Accepta, use a find and replace
 * to change 'accepta' to the name of your theme in all the template files.
 */
function accepta_load_textdomain() {
	load_theme_textdomain( 'accepta', get_template_directory() . '/languages' );
}
add_action( 'init', 'accepta_load_textdomain' );

/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @global int $content_width
 */
function accepta_content_width() {
	$GLOBALS['content_width'] = apply_filters( 'accepta_content_width', 1160 ); // 1200px - 40px padding
}
add_action( 'after_setup_theme', 'accepta_content_width', 0 );

/**
 * Register widget area.
 *
 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
 */
function accepta_widgets_init() {
	register_sidebar(
		array(
			'name'          => esc_html__( 'Sidebar', 'accepta' ),
			'id'            => 'sidebar-1',
			'description'   => esc_html__( 'Add widgets here.', 'accepta' ),
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget'  => '</section>',
			'before_title'  => '<h2 class="widget-title">',
			'after_title'   => '</h2>',
		)
	);
	
	// Register Footer Widget Areas
	register_sidebar(
		array(
			'name'          => esc_html__( 'Footer 1', 'accepta' ),
			'id'            => 'footer-1',
			'description'   => esc_html__( 'Add widgets here to appear in the first footer column.', 'accepta' ),
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget'  => '</section>',
			'before_title'  => '<h2 class="widget-title">',
			'after_title'   => '</h2>',
		)
	);
	
	register_sidebar(
		array(
			'name'          => esc_html__( 'Footer 2', 'accepta' ),
			'id'            => 'footer-2',
			'description'   => esc_html__( 'Add widgets here to appear in the second footer column.', 'accepta' ),
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget'  => '</section>',
			'before_title'  => '<h2 class="widget-title">',
			'after_title'   => '</h2>',
		)
	);
	
	register_sidebar(
		array(
			'name'          => esc_html__( 'Footer 3', 'accepta' ),
			'id'            => 'footer-3',
			'description'   => esc_html__( 'Add widgets here to appear in the third footer column.', 'accepta' ),
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget'  => '</section>',
			'before_title'  => '<h2 class="widget-title">',
			'after_title'   => '</h2>',
		)
	);
	
	register_sidebar(
		array(
			'name'          => esc_html__( 'Footer 4', 'accepta' ),
			'id'            => 'footer-4',
			'description'   => esc_html__( 'Add widgets here to appear in the fourth footer column.', 'accepta' ),
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget'  => '</section>',
			'before_title'  => '<h2 class="widget-title">',
			'after_title'   => '</h2>',
		)
	);
}
add_action( 'widgets_init', 'accepta_widgets_init' );

/**
 * Enqueue scripts and styles.
 */
function accepta_scripts() {

	wp_enqueue_style(
		'accepta-style',
		get_template_directory_uri() . '/assets/css/accepta.css',
		array(),
		_ACCEPTA_VERSION
	);
	wp_style_add_data( 'accepta-style', 'rtl', 'replace' );

	wp_enqueue_style(
		'font-awesome',
		get_template_directory_uri() . '/assets/fonts/fontawesome/all.min.css',
		array(),
		'6.4.0'
	);

    if ( class_exists( 'WooCommerce' ) ) {
        wp_enqueue_style( 
			'accepta-woocommerce-style', 
			get_template_directory_uri() . '/assets/css/woocommerce.css', 
			array( 'accepta-style', 'woocommerce-layout' ), 
			_ACCEPTA_VERSION 
		);
        
		wp_style_add_data( 'accepta-woocommerce-style', 'rtl', 'replace' );
    }

	wp_enqueue_script( 
		'accepta-navigation', 
		get_template_directory_uri() . '/assets/js/navigation.js', 
		array(), 
		_ACCEPTA_VERSION, 
		true 
	);

	wp_enqueue_script( 
		'accepta-mobile-menu', 
		get_template_directory_uri() . '/assets/js/mobile-menu.js', 
		array(), 
		_ACCEPTA_VERSION,
		true 
	);

	$sticky_header_enabled = get_theme_mod( 'accepta_sticky_header', true );
    wp_enqueue_script( 
		'accepta-sticky-header', 
		get_template_directory_uri() . '/assets/js/sticky-header.js', 
		array(), 
		_ACCEPTA_VERSION, 
		true 
	);

	$transparent_header = get_theme_mod( 'accepta_transparent_header', true );
    $scrolled_bg = get_theme_mod( 'accepta_scrolled_header_bg', '#ffffff' );
    $scrolled_bg_opacity = get_theme_mod( 'accepta_scrolled_header_bg_opacity', '1' );
    $transparent_text_color = get_theme_mod( 'accepta_transparent_header_text_color', '#ffffff' );
    $scrolled_text_color = get_theme_mod( 'accepta_scrolled_header_text_color', '#2c3e50' );
    wp_localize_script(
		'accepta-sticky-header',
		'acceptaStickyHeader',
		array(
			'enabled' => $sticky_header_enabled,
			'transparent' => $transparent_header,
			'scrolledBg' => $scrolled_bg,
			'scrolledBgOpacity' => floatval( $scrolled_bg_opacity ),
			'transparentTextColor' => $transparent_text_color,
			'scrolledTextColor' => $scrolled_text_color,
		)
	);

	if ( is_front_page() || is_home() ) {
        wp_enqueue_script(
            'accepta-hero-section',
            get_template_directory_uri() . '/assets/js/hero-section.js',
            array(),
            _ACCEPTA_VERSION,
            true
        );
    }

	if ( get_theme_mod( 'accepta_display_header_search', true ) ) {
        wp_enqueue_script(
            'accepta-header-search',
            get_template_directory_uri() . '/assets/js/header-search.js',
            array(),
            _ACCEPTA_VERSION,
            true
        );
    }

	if ( class_exists( 'WooCommerce' ) && get_theme_mod( 'accepta_woo_display_header_cart', true ) ) {
        wp_enqueue_script( 'wc-cart-fragments' );
        wp_enqueue_script(
            'accepta-woocommerce-cart-refresh',
            get_template_directory_uri() . '/assets/js/woocommerce-cart-refresh.js',
            array( 'jquery', 'wc-cart-fragments' ),
            _ACCEPTA_VERSION,
            true
        );
        wp_enqueue_script(
            'accepta-minicart-offcanvas',
            get_template_directory_uri() . '/assets/js/minicart-offcanvas.js',
            array(),
            _ACCEPTA_VERSION,
            true
        );
    }

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'wp_enqueue_scripts', 'accepta_scripts' );


/**
 * Get all Google Fonts used across typography settings
 */
function accepta_get_all_google_fonts() {
	$google_fonts = array();

	$google_fonts['Outfit'] = array( 
		'weights' => array( '300', '400', '500', '600', '700' ),
		'used_in' => array( 'default' )
	);

	$typography_settings = array( 
		'body' => get_theme_mod( 'accepta_body_typography', '' ),
		'all_headings' => get_theme_mod( 'accepta_all_headings_typography', '' ),
		'post_title' => get_theme_mod( 'accepta_post_title_typography', '' ),
		'h1' => get_theme_mod( 'accepta_h1_typography', '' ),
		'h2' => get_theme_mod( 'accepta_h2_typography', '' ),
		'h3' => get_theme_mod( 'accepta_h3_typography', '' ),
		'h4' => get_theme_mod( 'accepta_h4_typography', '' ),
		'h5' => get_theme_mod( 'accepta_h5_typography', '' ),
		'h6' => get_theme_mod( 'accepta_h6_typography', '' ),
		'button' => get_theme_mod( 'accepta_button_typography', '' ),
	);

	foreach ( $typography_settings as $type => $typography ) {
		if ( ! empty( $typography ) ) {
			$typography_data = json_decode( $typography, true );
			if ( is_array( $typography_data ) && ! empty( $typography_data['font_family'] ) ) {
				$font_family = $typography_data['font_family'];
				$clean_font_name = explode( ',', $font_family )[0];
				$clean_font_name = trim( str_replace( array( '"', "'" ), '', $clean_font_name ) );
				if ( ! accepta_is_system_font( $clean_font_name ) ) {
					if ( ! isset( $google_fonts[ $clean_font_name ] ) ) {
						$google_fonts[ $clean_font_name ] = array( 
							'weights' => array( '300', '400', '500', '600', '700' ),
							'used_in' => array()
						);
					}
					$google_fonts[ $clean_font_name ]['used_in'][] = $type;
				}
			}
		}
	}
	
	return $google_fonts;
}

/**
 * Enqueue Google Fonts based on typography settings
 */
function accepta_enqueue_google_fonts() {
	$google_fonts_data = accepta_get_all_google_fonts();
	$google_fonts = array();
	foreach ( $google_fonts_data as $font_name => $font_data ) {
		$google_fonts[ $font_name ] = $font_data['weights'];
	}

	if ( ! empty( $google_fonts ) ) {
		$font_families = array();
		
		foreach ( $google_fonts as $font_family => $weights ) {
			// Encode font family name for URL
			$encoded_family = str_replace( ' ', '+', $font_family );
			
			// Build font family with weights
			$font_families[] = $encoded_family . ':wght@' . implode( ';', $weights );
		}
		
		if ( ! empty( $font_families ) ) {
			$fonts_url = 'https://fonts.googleapis.com/css2?family=' . implode( '&family=', $font_families ) . '&display=swap';
			
			// Enqueue the Google Fonts
			wp_enqueue_style( 'accepta-google-fonts', $fonts_url, array(), null );
			
			// Add debug comment to show combined fonts
			add_action( 'wp_head', function() use ( $google_fonts_data, $fonts_url ) {
				echo '<!-- Accepta Combined Google Fonts (' . count( $google_fonts_data ) . ' fonts): -->' . "\n";
				foreach ( $google_fonts_data as $font_name => $font_data ) {
					echo '<!--   ' . $font_name . ' (used in: ' . implode( ', ', $font_data['used_in'] ) . ') -->' . "\n";
				}
				echo '<!-- Single Google Fonts Request: ' . esc_url( $fonts_url ) . ' -->' . "\n";
			}, 1 );
		}
	}
}
add_action( 'wp_enqueue_scripts', 'accepta_enqueue_google_fonts' );


/**
 * Check if a font is a system font
 *
 * @param string $font_family Font family name
 * @return bool
 */
function accepta_is_system_font( $font_family ) {
	$system_fonts = array(
		'Arial, sans-serif',
		'Helvetica, Arial, sans-serif',
		'"Times New Roman", Times, serif',
		'Georgia, serif',
		'"Courier New", Courier, monospace',
		'Verdana, Geneva, sans-serif',
		'Tahoma, Geneva, sans-serif',
		'"Trebuchet MS", Helvetica, sans-serif',
		'"Arial Black", Gadget, sans-serif',
		'"Palatino Linotype", "Book Antiqua", Palatino, serif',
		'"Lucida Sans Unicode", "Lucida Grande", sans-serif',
		'"MS Serif", "New York", serif',
		'"Comic Sans MS", cursive',
		'Impact, Charcoal, sans-serif'
	);
	
	return in_array( $font_family, $system_fonts, true );
}

/**
 * Get font variants from Google Fonts JSON
 *
 * @param string $font_family Font family name
 * @return array
 */
function accepta_get_font_variants( $font_family ) {
	$fonts_file = get_template_directory() . '/inc/customizer-controls/google-fonts.json';
	
	if ( ! file_exists( $fonts_file ) ) {
		return array( '400' );
	}
	
	$fonts_json = file_get_contents( $fonts_file );
	$fonts_data = json_decode( $fonts_json, true );
	
	if ( ! isset( $fonts_data['items'] ) || ! is_array( $fonts_data['items'] ) ) {
		return array( '400' );
	}
	
	foreach ( $fonts_data['items'] as $font ) {
		if ( isset( $font['family'] ) && $font['family'] === $font_family ) {
			return isset( $font['variants'] ) ? $font['variants'] : array( '400' );
		}
	}
	
	return array( '400' );
}

/**
 * Filter for RTL replacements - point rtl styles to -rtl versions in assets/css
 */
add_filter( 'load_rtl_styles', function( $rtl_styles ) {
    foreach ( $rtl_styles as $key => $rtl_style ) {
        if ( strpos( $key, 'accepta-' ) === 0 ) {
            // Replace standard .css with -rtl.css for our theme styles
            $rtl_styles[$key] = str_replace( '.css', '-rtl.css', $rtl_style );
        }
    }
    return $rtl_styles;
});

/**
 * Load Accepta theme custom functions
 */
require get_template_directory() . '/inc/accepta-theme-functions.php';

/**
 * Implement the Custom Header feature.
 */
require get_template_directory() . '/inc/custom-header.php';

/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/template-tags.php';

/**
 * Functions which enhance the theme by hooking into WordPress.
 */
require get_template_directory() . '/inc/template-functions.php';

/**
 * Customizer additions.
 */
require get_template_directory() . '/inc/customizer.php';

/**
 * Elementor compatibility.
 */
require get_template_directory() . '/inc/elementor.php';

/**
 * Load Jetpack compatibility file.
 */
if ( defined( 'JETPACK__VERSION' ) ) {
	require get_template_directory() . '/inc/jetpack.php';
}

/**
 * Load WooCommerce compatibility file.
 */
if ( class_exists( 'WooCommerce' ) ) {
	require get_template_directory() . '/inc/woocommerce.php';
}

/**
 * Load Admin pages and functionality
 */
if ( is_admin() ) {
	require_once get_template_directory() . '/inc/admin/accepta-admin-init.php';
}

