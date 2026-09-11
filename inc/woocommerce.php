<?php
/**
 * WooCommerce Compatibility File
 *
 * @link https://woocommerce.com/
 *
 * @package Accepta
 */

/**
 * WooCommerce setup function.
 *
 * @link https://docs.woocommerce.com/document/third-party-custom-theme-compatibility/
 * @link https://github.com/woocommerce/woocommerce/wiki/Enabling-product-gallery-features-(zoom,-swipe,-lightbox)
 * @link https://github.com/woocommerce/woocommerce/wiki/Declaring-WooCommerce-support-in-themes
 *
 * @return void
 */
function accepta_woocommerce_setup() {
	add_theme_support( 'woocommerce' );
	add_theme_support( 'wc-product-gallery-zoom' );
	add_theme_support( 'wc-product-gallery-lightbox' );
	add_theme_support( 'wc-product-gallery-slider' );
}
add_action( 'after_setup_theme', 'accepta_woocommerce_setup' );

/**
 * Available catalog / single product style variants.
 *
 * 'default' is the theme's original look and stays the shipped default so
 * existing sites are untouched when they update.
 *
 * @return array Style slug => translated label.
 */
function accepta_get_woocommerce_style_choices() {
	return array(
		'default'  => __( 'Default', 'accepta' ),
		'shopline' => __( 'Shopline', 'accepta' ),
	);
}

/**
 * Sanitize a catalog / product style slug against the known variants.
 *
 * Maps the retired "editorial" slug to "shopline" so existing theme mods keep working.
 *
 * @param string $style Raw style slug.
 * @return string Valid style slug, falling back to 'default'.
 */
function accepta_sanitize_woocommerce_style( $style ) {
	if ( 'editorial' === $style ) {
		$style = 'shopline';
	}

	return array_key_exists( $style, accepta_get_woocommerce_style_choices() ) ? $style : 'default';
}

/**
 * Selected WooCommerce style for both catalog and single product pages.
 *
 * Falls back to the older per-page theme mods if the shared setting is unset,
 * so existing Customizer choices are preserved after the controls were merged.
 *
 * @return string
 */
function accepta_get_woocommerce_style() {
	$style = get_theme_mod( 'accepta_woo_style', null );

	if ( null === $style || '' === $style ) {
		$style = get_theme_mod( 'accepta_woo_shop_style', null );
	}

	if ( null === $style || '' === $style ) {
		$style = get_theme_mod( 'accepta_woo_single_style', 'default' );
	}

	return accepta_sanitize_woocommerce_style( $style );
}

/**
 * Selected shop / product archive catalog style.
 *
 * @return string
 */
function accepta_get_shop_style() {
	return accepta_get_woocommerce_style();
}

/**
 * Selected single product page style.
 *
 * @return string
 */
function accepta_get_single_product_style() {
	return accepta_get_woocommerce_style();
}

/**
 * Available header cart icon presets.
 *
 * @return array Icon slug => translated label.
 */
function accepta_get_cart_icon_choices() {
	return array(
		'cart'   => __( 'Cart', 'accepta' ),
		'bag'    => __( 'Bag', 'accepta' ),
		'basket' => __( 'Basket', 'accepta' ),
		'tote'   => __( 'Tote', 'accepta' ),
	);
}

/**
 * Sanitize a cart icon slug against the known presets.
 *
 * @param string $icon Raw icon slug.
 * @return string Valid icon slug, falling back to 'cart'.
 */
function accepta_sanitize_cart_icon( $icon ) {
	return array_key_exists( $icon, accepta_get_cart_icon_choices() ) ? $icon : 'cart';
}

/**
 * Selected header cart icon preset.
 *
 * @return string
 */
function accepta_get_cart_icon() {
	return accepta_sanitize_cart_icon( get_theme_mod( 'accepta_woo_cart_icon', 'cart' ) );
}

/**
 * Return the SVG markup for a header cart icon preset.
 *
 * Stroke-based icons sized to match the header search/social controls.
 *
 * @param string $icon Optional icon slug. Defaults to the selected theme mod.
 * @return string Inline SVG markup.
 */
function accepta_get_cart_icon_svg( $icon = '' ) {
	$icon = accepta_sanitize_cart_icon( $icon ? $icon : accepta_get_cart_icon() );

	$shapes = array(
		'cart'   => '<circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/>',
		'bag'    => '<path d="M6 2 3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4Z"/><path d="M3 6h18"/><path d="M16 10a4 4 0 0 1-8 0"/>',
		'basket' => '<path d="m5 8 1.5 11.5a2 2 0 0 0 2 1.5h7a2 2 0 0 0 2-1.5L19 8"/><path d="M3 8h18"/><path d="M8 8V5a4 4 0 0 1 8 0v3"/>',
		'tote'   => '<path d="M5 7h14l-1.2 13.2A2 2 0 0 1 15.81 22H8.19a2 2 0 0 1-1.99-1.8L5 7Z"/><path d="M9 7V5a3 3 0 0 1 6 0v2"/>',
	);

	return '<svg class="header-cart-icon" width="18" height="18" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false">' . $shapes[ $icon ] . '</svg>';
}

/**
 * Return the SVG markup for the header account / My Account icon.
 *
 * @return string Inline SVG markup.
 */
function accepta_get_account_icon_svg() {
	return '<svg class="header-account-icon" width="18" height="18" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>';
}

/**
 * Header My Account link (user icon).
 *
 * Links to the WooCommerce My Account page. Shows a login-oriented label when
 * the visitor is logged out.
 *
 * @return void
 */
function accepta_woocommerce_account_link() {
	if ( ! function_exists( 'wc_get_page_permalink' ) ) {
		return;
	}

	$url   = wc_get_page_permalink( 'myaccount' );
	$title = is_user_logged_in() ? __( 'My account', 'accepta' ) : __( 'Log in', 'accepta' );

	if ( ! $url ) {
		return;
	}
	?>
	<a class="header-account-link" href="<?php echo esc_url( $url ); ?>" aria-label="<?php echo esc_attr( $title ); ?>" title="<?php echo esc_attr( $title ); ?>">
		<?php echo accepta_get_account_icon_svg(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- trusted SVG from theme helper. ?>
		<span class="screen-reader-text"><?php echo esc_html( $title ); ?></span>
	</a>
	<?php
}

/**
 * Add WooCommerce body classes, including the selected style variants.
 *
 * The catalog class is added site-wide rather than only on the shop, because
 * product loops also appear in related products, upsells, cart cross-sells,
 * widgets and shortcodes.
 *
 * @param  array $classes CSS classes applied to the body tag.
 * @return array $classes modified to include WooCommerce classes.
 */
function accepta_woocommerce_active_body_class( $classes ) {
	$classes[] = 'woocommerce-active';
	$classes[] = 'accepta-shop-style-' . accepta_get_shop_style();

	if ( function_exists( 'is_product' ) && is_product() ) {
		$classes[] = 'accepta-product-style-' . accepta_get_single_product_style();
	}

	return $classes;
}
add_filter( 'body_class', 'accepta_woocommerce_active_body_class' );

/**
 * Related Products Args.
 *
 * @param array $args related products args.
 * @return array $args related products args.
 */
function accepta_woocommerce_related_products_args( $args ) {
	$defaults = array(
		'posts_per_page' => 3,
		'columns'        => 3,
	);

	$args = wp_parse_args( $defaults, $args );

	return $args;
}
add_filter( 'woocommerce_output_related_products_args', 'accepta_woocommerce_related_products_args' );

/**
 * Rewire the product loop hooks for image wrap + catalog Add to cart.
 *
 * Default puts Add to cart inside the image wrap as a hover overlay.
 * Shopline keeps WooCommerce's own hook so the button sits below the price
 * and stays visible without a hover reveal.
 *
 * Runs on wp_loaded so it also applies to AJAX-rendered loops.
 */
function accepta_woocommerce_setup_loop_hooks() {
	remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_product_link_close', 5 );

	add_action( 'woocommerce_before_shop_loop_item', 'accepta_woocommerce_loop_image_wrap_open', 5 );
	add_action( 'woocommerce_before_shop_loop_item_title', 'accepta_woocommerce_loop_image_wrap_close_and_add_to_cart', 15 );

	// The product link is closed early above, so the title needs its own link.
	remove_action( 'woocommerce_shop_loop_item_title', 'woocommerce_template_loop_product_title', 10 );
	add_action( 'woocommerce_shop_loop_item_title', 'accepta_woocommerce_template_loop_product_title', 10 );

	if ( 'default' === accepta_get_shop_style() ) {
		remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart', 10 );
		add_filter( 'woocommerce_loop_add_to_cart_args', 'accepta_woocommerce_loop_add_to_cart_args_overlay', 10, 2 );
	}
}
add_action( 'wp_loaded', 'accepta_woocommerce_setup_loop_hooks' );

if ( ! function_exists( 'accepta_woocommerce_loop_add_to_cart_args_overlay' ) ) {
	/**
	 * Add class for overlay Add to cart (green background styling).
	 *
	 * @param array      $args    Button args.
	 * @param WC_Product $product Product object.
	 * @return array
	 */
	function accepta_woocommerce_loop_add_to_cart_args_overlay( $args, $product ) {
		$args['class'] = ( isset( $args['class'] ) ? $args['class'] . ' ' : '' ) . 'add-to-cart-overlay';

		return $args;
	}
}

if ( ! function_exists( 'accepta_woocommerce_loop_image_wrap_open' ) ) {
	/**
	 * Open wrapper around product image for Add to cart overlay.
	 */
	function accepta_woocommerce_loop_image_wrap_open() {
		echo '<div class="woocommerce-loop-product__image-wrap">';
	}
}

if ( ! function_exists( 'accepta_woocommerce_loop_image_wrap_close_and_add_to_cart' ) ) {
	/**
	 * Close product link, optionally output overlay Add to cart, close wrapper.
	 *
	 * Shopline skips the overlay button and keeps WooCommerce's own Add to cart
	 * hook, which renders below the product meta instead.
	 */
	function accepta_woocommerce_loop_image_wrap_close_and_add_to_cart() {
		woocommerce_template_loop_product_link_close();

		if ( 'default' === accepta_get_shop_style() ) {
			woocommerce_template_loop_add_to_cart();
		}

		echo '</div><!-- .woocommerce-loop-product__image-wrap -->';
	}
}

if ( ! function_exists( 'accepta_woocommerce_template_loop_product_title' ) ) {
	/**
	 * Product title with link (needed because we close the product link early).
	 */
	function accepta_woocommerce_template_loop_product_title() {
		$classes = apply_filters( 'woocommerce_product_loop_title_classes', 'woocommerce-loop-product__title' );
		echo '<h2 class="' . esc_attr( $classes ) . '">';
		echo '<a href="' . esc_url( get_the_permalink() ) . '">' . get_the_title() . '</a>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		echo '</h2>';
	}
}

/**
 * Remove default WooCommerce wrapper.
 */
remove_action( 'woocommerce_before_main_content', 'woocommerce_output_content_wrapper', 10 );
remove_action( 'woocommerce_after_main_content', 'woocommerce_output_content_wrapper_end', 10 );

if ( ! function_exists( 'accepta_woocommerce_wrapper_before' ) ) {
	/**
	 * Before Content.
	 *
	 * Wraps all WooCommerce content in wrappers which match the theme markup.
	 *
	 * @return void
	 */
	function accepta_woocommerce_wrapper_before() {
		?>
			<main id="primary" class="site-main">
		<?php
	}
}
add_action( 'woocommerce_before_main_content', 'accepta_woocommerce_wrapper_before' );

if ( ! function_exists( 'accepta_woocommerce_wrapper_after' ) ) {
	/**
	 * After Content.
	 *
	 * Closes the wrapping divs.
	 *
	 * @return void
	 */
	function accepta_woocommerce_wrapper_after() {
		?>
			</main><!-- #main -->
		<?php
	}
}
add_action( 'woocommerce_after_main_content', 'accepta_woocommerce_wrapper_after' );

/**
 * Sample implementation of the WooCommerce Mini Cart.
 *
 * You can add the WooCommerce Mini Cart to header.php like so ...
 *
	<?php
		if ( function_exists( 'accepta_woocommerce_header_cart' ) ) {
			accepta_woocommerce_header_cart();
		}
	?>
 */

if ( ! function_exists( 'accepta_woocommerce_cart_link_fragment' ) ) {
	/**
	 * Cart Fragments.
	 *
	 * Ensure cart contents update when products are added to the cart via AJAX.
	 * Use a unique selector (a.header-cart-link) so only our header icon is updated;
	 * otherwise another callback can overwrite 'a.cart-contents' with text-style HTML
	 * and our icon is replaced and loses order/position (appears before logo then hidden).
	 *
	 * @param array $fragments Fragments to refresh via AJAX.
	 * @return array Fragments to refresh via AJAX.
	 */
	function accepta_woocommerce_cart_link_fragment( $fragments ) {
		ob_start();
		accepta_woocommerce_cart_link( true );
		$html = ob_get_clean();
		$fragments['a.header-cart-link.cart-contents'] = $html;

		return $fragments;
	}
}
add_filter( 'woocommerce_add_to_cart_fragments', 'accepta_woocommerce_cart_link_fragment', 20 );

/**
 * Keep the Customizer cart-icon preview from being overwritten.
 *
 * WooCommerce cart fragments fetch the live (saved) cart markup via AJAX and
 * replace the header icon. That request is outside the Customizer changeset,
 * so the preview briefly shows the selected icon then snaps back to the saved
 * one. Skip fragments entirely while customizing.
 */
function accepta_woocommerce_disable_cart_fragments_in_customizer() {
	if ( ! is_customize_preview() ) {
		return;
	}

	wp_dequeue_script( 'wc-cart-fragments' );
	wp_dequeue_script( 'accepta-woocommerce-cart-refresh' );
}
add_action( 'wp_enqueue_scripts', 'accepta_woocommerce_disable_cart_fragments_in_customizer', 100 );

if ( ! function_exists( 'accepta_woocommerce_cart_link' ) ) {
	/**
	 * Cart Link.
	 *
	 * Display a link to the cart. When $icon_style is true, outputs icon + count to match header search/social style.
	 *
	 * @param bool $icon_style Whether to output the header icon style (circle, SVG, count).
	 * @return void
	 */
	function accepta_woocommerce_cart_link( $icon_style = false ) {
		$cart_available = ( function_exists( 'WC' ) && WC() ) ? WC()->cart : null;
		if ( ! $cart_available ) {
			if ( $icon_style ) {
				/* Always show icon in header (e.g. customizer or when cart not initialized). Opens offcanvas minicart. */
				$title = __( 'View your shopping cart', 'accepta' );
				?>
				<a class="header-cart-link cart-contents" href="#" role="button" data-accepta-minicart-toggle aria-label="<?php echo esc_attr( $title ); ?>" title="<?php echo esc_attr( $title ); ?>">
					<?php echo accepta_get_cart_icon_svg(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- trusted SVG from theme helper. ?>
				</a>
				<?php
			}
			return;
		}
		$cart_url = wc_get_cart_url();
		$count    = WC()->cart->get_cart_contents_count();
		$title    = __( 'View your shopping cart', 'accepta' );

		if ( $icon_style ) {
			/* Always show icon and count (0 when empty) so the cart is visible even with no products. */
			?>
			<a class="header-cart-link cart-contents" href="#" role="button" data-accepta-minicart-toggle aria-label="<?php echo esc_attr( $title ); ?>" title="<?php echo esc_attr( $title ); ?>">
				<?php echo accepta_get_cart_icon_svg(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- trusted SVG from theme helper. ?>
				<span class="header-cart-count"><?php echo esc_html( $count ); ?></span>
			</a>
			<?php
			return;
		}
		?>
		<a class="cart-contents" href="<?php echo esc_url( $cart_url ); ?>" title="<?php echo esc_attr( $title ); ?>">
			<?php
			$item_count_text = sprintf(
				/* translators: number of items in the mini cart. */
				_n( '%d item', '%d items', $count, 'accepta' ),
				$count
			);
			?>
			<span class="amount"><?php echo wp_kses_data( WC()->cart->get_cart_subtotal() ); ?></span> <span class="count"><?php echo esc_html( $item_count_text ); ?></span>
		</a>
		<?php
	}
}

if ( ! function_exists( 'accepta_woocommerce_header_cart' ) ) {
	/**
	 * Display Header Cart.
	 *
	 * @return void
	 */
	function accepta_woocommerce_header_cart() {
		if ( is_cart() ) {
			$class = 'current-menu-item';
		} else {
			$class = '';
		}
		?>
		<ul id="site-header-cart" class="site-header-cart">
			<li class="<?php echo esc_attr( $class ); ?>">
				<?php accepta_woocommerce_cart_link(); ?>
			</li>
			<li>
				<?php
				$instance = array(
					'title' => '',
				);

				the_widget( 'WC_Widget_Cart', $instance );
				?>
			</li>
		</ul>
		<?php
	}
}

/**
 * Whether the sticky single-product Add to cart bar should render.
 *
 * @param WC_Product|null $product Product object.
 * @return bool
 */
function accepta_woocommerce_should_show_sticky_atc( $product = null ) {
	if ( ! get_theme_mod( 'accepta_woo_sticky_atc', true ) ) {
		return false;
	}

	if ( ! $product instanceof WC_Product ) {
		$product = function_exists( 'wc_get_product' ) ? wc_get_product( get_the_ID() ) : null;
	}

	if ( ! $product ) {
		return false;
	}

	// Only products that can currently be bought (excludes OOS, non-purchasable, etc.).
	if ( ! $product->is_purchasable() || ! $product->is_in_stock() ) {
		return false;
	}

	// Grouped products need the form on-page; skip the sticky shortcut.
	if ( $product->is_type( 'grouped' ) ) {
		return false;
	}

	return true;
}

/**
 * Whether the minicart offcanvas markup/scripts are needed.
 *
 * @return bool
 */
function accepta_woocommerce_needs_minicart() {
	if ( ! class_exists( 'WooCommerce' ) ) {
		return false;
	}

	if ( get_theme_mod( 'accepta_woo_display_header_cart', true ) ) {
		return true;
	}

	return function_exists( 'is_product' ) && is_product() && get_theme_mod( 'accepta_woo_sticky_atc', true );
}

/**
 * Output the sticky Add to cart bar on single product pages.
 *
 * Visible only after the main Add to cart control leaves the viewport (JS).
 *
 * @return void
 */
function accepta_woocommerce_sticky_add_to_cart_bar() {
	if ( ! function_exists( 'is_product' ) || ! is_product() ) {
		return;
	}

	$product = wc_get_product( get_the_ID() );
	if ( ! accepta_woocommerce_should_show_sticky_atc( $product ) ) {
		return;
	}

	$product_id = $product->get_id();
	$title      = $product->get_name();
	$permalink  = get_permalink( $product_id );
	$thumb_html = $product->get_image(
		'woocommerce_gallery_thumbnail',
		array(
			'class'   => 'accepta-sticky-atc__image',
			'alt'     => $title,
			'loading' => 'lazy',
		)
	);

	$is_variable = $product->is_type( 'variable' );
	$is_external = $product->is_type( 'external' );

	if ( $is_external ) {
		$button_label = $product->single_add_to_cart_text();
		$button_url   = $product->add_to_cart_url();
	} elseif ( $is_variable ) {
		$button_label = __( 'Select options', 'accepta' );
		$button_url   = '';
	} else {
		$button_label = $product->single_add_to_cart_text();
		$button_url   = '';
	}
	?>
	<div
		class="accepta-sticky-atc"
		hidden
		aria-hidden="true"
		data-accepta-sticky-atc
		data-product-type="<?php echo esc_attr( $product->get_type() ); ?>"
		data-product-id="<?php echo esc_attr( (string) $product_id ); ?>"
	>
		<div class="accepta-sticky-atc__inner">
			<a class="accepta-sticky-atc__product" href="<?php echo esc_url( $permalink ); ?>">
				<span class="accepta-sticky-atc__thumb"><?php echo $thumb_html; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- WC product image HTML. ?></span>
				<span class="accepta-sticky-atc__meta">
					<span class="accepta-sticky-atc__title"><?php echo esc_html( $title ); ?></span>
					<span class="accepta-sticky-atc__price"><?php echo wp_kses_post( $product->get_price_html() ); ?></span>
				</span>
			</a>

			<?php if ( $is_external && $button_url ) : ?>
				<a class="accepta-sticky-atc__button" href="<?php echo esc_url( $button_url ); ?>" rel="nofollow">
					<?php echo esc_html( $button_label ); ?>
				</a>
			<?php else : ?>
				<button
					type="button"
					class="accepta-sticky-atc__button"
					data-accepta-sticky-atc-trigger
					data-label-add="<?php echo esc_attr( $product->single_add_to_cart_text() ); ?>"
					data-label-select="<?php echo esc_attr__( 'Select options', 'accepta' ); ?>"
				>
					<?php echo esc_html( $button_label ); ?>
				</button>
			<?php endif; ?>
		</div>
	</div>
	<?php
}
add_action( 'wp_footer', 'accepta_woocommerce_sticky_add_to_cart_bar', 20 );

/**
 * Enqueue sticky Add to cart assets on purchasable single product pages.
 *
 * @return void
 */
function accepta_woocommerce_sticky_atc_scripts() {
	if ( ! function_exists( 'is_product' ) || ! is_product() ) {
		return;
	}

	$product = wc_get_product( get_the_ID() );
	if ( ! accepta_woocommerce_should_show_sticky_atc( $product ) ) {
		return;
	}

	wp_enqueue_script( 'wc-cart-fragments' );
	wp_enqueue_script(
		'accepta-minicart-offcanvas',
		accepta_get_asset_uri( 'assets/js/minicart-offcanvas.js' ),
		array(),
		_ACCEPTA_VERSION,
		true
	);

	wp_enqueue_script(
		'accepta-sticky-add-to-cart',
		accepta_get_asset_uri( 'assets/js/sticky-add-to-cart.js' ),
		array( 'jquery', 'wc-cart-fragments', 'accepta-minicart-offcanvas' ),
		_ACCEPTA_VERSION,
		true
	);

	wp_localize_script(
		'accepta-sticky-add-to-cart',
		'acceptaStickyAtc',
		array(
			'ajaxUrl' => class_exists( 'WC_AJAX' ) ? WC_AJAX::get_endpoint( 'add_to_cart' ) : '',
			'i18n'    => array(
				'adding' => __( 'Adding…', 'accepta' ),
				'error'  => __( 'Could not add to cart. Please try again.', 'accepta' ),
			),
		)
	);
}
add_action( 'wp_enqueue_scripts', 'accepta_woocommerce_sticky_atc_scripts', 30 );
