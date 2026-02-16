/**
 * Refresh header cart count when products are removed from the cart page.
 * Listens for WooCommerce cart events and triggers fragment refresh.
 *
 * @package Accepta
 */
(function () {
	'use strict';

	function init() {
		if ( typeof jQuery === 'undefined' ) return;

		var $ = jQuery;
		$( document.body ).on( 'item_removed_from_classic_cart wc_cart_emptied', function () {
			$( document.body ).trigger( 'wc_fragment_refresh' );
		} );
	}

	if ( document.readyState === 'loading' ) {
		document.addEventListener( 'DOMContentLoaded', init );
	} else {
		init();
	}
})();
