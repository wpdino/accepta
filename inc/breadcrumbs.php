<?php
/**
 * Site-wide breadcrumbs.
 *
 * Disabled by default so existing installs keep their current markup.
 *
 * @package Accepta
 */

/**
 * Whether theme breadcrumbs should render.
 *
 * @return bool
 */
function accepta_breadcrumbs_enabled() {
	return (bool) get_theme_mod( 'accepta_breadcrumbs_enable', false );
}

/**
 * Body class when theme breadcrumbs are enabled.
 *
 * @param array $classes Body classes.
 * @return array
 */
function accepta_breadcrumbs_body_class( $classes ) {
	if ( accepta_breadcrumbs_enabled() ) {
		$classes[] = 'accepta-has-theme-breadcrumbs';
	}
	return $classes;
}
add_filter( 'body_class', 'accepta_breadcrumbs_body_class' );

/**
 * Whether breadcrumbs should show on the current view.
 *
 * @return bool
 */
function accepta_should_show_breadcrumbs() {
	if ( ! accepta_breadcrumbs_enabled() ) {
		return false;
	}

	if ( is_front_page() ) {
		return false;
	}

	/**
	 * Filter whether breadcrumbs render on the current request.
	 *
	 * @param bool $show Whether to show breadcrumbs.
	 */
	return (bool) apply_filters( 'accepta_should_show_breadcrumbs', true );
}

/**
 * Build breadcrumb items for the current request.
 *
 * @return array<int, array{label:string,url?:string}>
 */
function accepta_get_breadcrumb_items() {
	$items = array();
	$home  = get_theme_mod( 'accepta_breadcrumbs_home_label', __( 'Home', 'accepta' ) );

	$items[] = array(
		'label' => $home,
		'url'   => home_url( '/' ),
	);

	if ( function_exists( 'is_shop' ) && is_shop() ) {
		$shop_id = wc_get_page_id( 'shop' );
		$items[] = array(
			'label' => $shop_id > 0 ? get_the_title( $shop_id ) : __( 'Shop', 'accepta' ),
		);
		return $items;
	}

	if ( function_exists( 'is_product' ) && is_product() ) {
		$shop_id = function_exists( 'wc_get_page_id' ) ? wc_get_page_id( 'shop' ) : 0;
		if ( $shop_id > 0 ) {
			$items[] = array(
				'label' => get_the_title( $shop_id ),
				'url'   => get_permalink( $shop_id ),
			);
		}

		$terms = get_the_terms( get_the_ID(), 'product_cat' );
		if ( $terms && ! is_wp_error( $terms ) ) {
			$primary = $terms[0];
			$items[] = array(
				'label' => $primary->name,
				'url'   => get_term_link( $primary ),
			);
		}

		$items[] = array(
			'label' => get_the_title(),
		);
		return $items;
	}

	if ( function_exists( 'is_product_category' ) && ( is_product_category() || is_product_tag() ) ) {
		$shop_id = wc_get_page_id( 'shop' );
		if ( $shop_id > 0 ) {
			$items[] = array(
				'label' => get_the_title( $shop_id ),
				'url'   => get_permalink( $shop_id ),
			);
		}
		$items[] = array(
			'label' => single_term_title( '', false ),
		);
		return $items;
	}

	if ( is_home() && ! is_front_page() ) {
		$posts_page = (int) get_option( 'page_for_posts' );
		$items[]    = array(
			'label' => $posts_page ? get_the_title( $posts_page ) : __( 'Blog', 'accepta' ),
		);
		return $items;
	}

	if ( is_category() || is_tag() || is_tax() ) {
		$term = get_queried_object();
		if ( $term instanceof WP_Term ) {
			if ( is_category() && $term->parent ) {
				$ancestors = array_reverse( get_ancestors( $term->term_id, 'category' ) );
				foreach ( $ancestors as $ancestor_id ) {
					$ancestor = get_term( $ancestor_id, 'category' );
					if ( $ancestor && ! is_wp_error( $ancestor ) ) {
						$items[] = array(
							'label' => $ancestor->name,
							'url'   => get_term_link( $ancestor ),
						);
					}
				}
			}
			$items[] = array(
				'label' => $term->name,
			);
		}
		return $items;
	}

	if ( is_search() ) {
		$items[] = array(
			/* translators: %s: search query */
			'label' => sprintf( __( 'Search results for “%s”', 'accepta' ), get_search_query() ),
		);
		return $items;
	}

	if ( is_author() ) {
		$items[] = array(
			/* translators: %s: author display name */
			'label' => sprintf( __( 'Author: %s', 'accepta' ), get_the_author() ),
		);
		return $items;
	}

	if ( is_day() ) {
		$items[] = array(
			'label' => get_the_date(),
		);
		return $items;
	}

	if ( is_month() ) {
		$items[] = array(
			'label' => get_the_date( 'F Y' ),
		);
		return $items;
	}

	if ( is_year() ) {
		$items[] = array(
			'label' => get_the_date( 'Y' ),
		);
		return $items;
	}

	if ( is_singular() ) {
		if ( is_singular( 'post' ) ) {
			$categories = get_the_category();
			if ( ! empty( $categories ) ) {
				$cat = $categories[0];
				$items[] = array(
					'label' => $cat->name,
					'url'   => get_category_link( $cat->term_id ),
				);
			}
		}

		if ( is_page() && ! is_front_page() ) {
			$ancestors = array_reverse( get_post_ancestors( get_the_ID() ) );
			foreach ( $ancestors as $ancestor_id ) {
				$items[] = array(
					'label' => get_the_title( $ancestor_id ),
					'url'   => get_permalink( $ancestor_id ),
				);
			}
		}

		$items[] = array(
			'label' => get_the_title(),
		);
		return $items;
	}

	if ( is_404() ) {
		$items[] = array(
			'label' => __( 'Page not found', 'accepta' ),
		);
	}

	return $items;
}

/**
 * Output breadcrumb markup.
 *
 * @return void
 */
function accepta_breadcrumbs() {
	if ( ! accepta_should_show_breadcrumbs() ) {
		return;
	}

	$items = accepta_get_breadcrumb_items();
	if ( count( $items ) < 2 ) {
		return;
	}

	$separator = apply_filters( 'accepta_breadcrumbs_separator', '/' );
	?>
	<nav class="accepta-breadcrumbs" aria-label="<?php esc_attr_e( 'Breadcrumbs', 'accepta' ); ?>">
		<div class="container">
			<ol class="accepta-breadcrumbs__list">
				<?php foreach ( $items as $index => $item ) : ?>
					<li class="accepta-breadcrumbs__item">
						<?php if ( ! empty( $item['url'] ) && $index < count( $items ) - 1 ) : ?>
							<a href="<?php echo esc_url( $item['url'] ); ?>"><?php echo esc_html( $item['label'] ); ?></a>
						<?php else : ?>
							<span class="accepta-breadcrumbs__current" aria-current="page"><?php echo esc_html( $item['label'] ); ?></span>
						<?php endif; ?>
					</li>
					<?php if ( $index < count( $items ) - 1 ) : ?>
						<li class="accepta-breadcrumbs__sep" aria-hidden="true"><?php echo esc_html( $separator ); ?></li>
					<?php endif; ?>
				<?php endforeach; ?>
			</ol>
		</div>
	</nav>
	<?php
}

/**
 * When theme breadcrumbs are on, hide WooCommerce default breadcrumbs to avoid duplicates.
 *
 * @return void
 */
function accepta_breadcrumbs_remove_woocommerce() {
	if ( ! accepta_breadcrumbs_enabled() || ! class_exists( 'WooCommerce' ) ) {
		return;
	}

	remove_action( 'woocommerce_before_main_content', 'woocommerce_breadcrumb', 20 );
}
add_action( 'wp', 'accepta_breadcrumbs_remove_woocommerce' );
