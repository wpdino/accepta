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
 * Add 'woocommerce-active' class to the body tag.
 *
 * @param  array $classes CSS classes applied to the body tag.
 * @return array $classes modified to include 'woocommerce-active' class.
 */
function accepta_woocommerce_active_body_class( $classes ) {
	$classes[] = 'woocommerce-active';

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
					<svg width="18" height="18" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
						<circle cx="9" cy="21" r="1" stroke="currentColor" stroke-width="2"/>
						<circle cx="20" cy="21" r="1" stroke="currentColor" stroke-width="2"/>
						<path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
					</svg>
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
				<svg width="18" height="18" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
					<circle cx="9" cy="21" r="1" stroke="currentColor" stroke-width="2"/>
					<circle cx="20" cy="21" r="1" stroke="currentColor" stroke-width="2"/>
					<path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
				</svg>
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
