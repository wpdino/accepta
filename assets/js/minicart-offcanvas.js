/**
 * Minicart offcanvas panel – open/close from right.
 * Uses event delegation so the cart icon still works after WooCommerce cart fragments replace it.
 *
 * @package Accepta
 */
(function () {
	'use strict';

	function acceptaMinicartOffcanvasInit() {
		var offcanvas = document.getElementById('accepta-minicart-offcanvas');
		if (!offcanvas) return;

		function acceptaMinicartOffcanvasOpen() {
			offcanvas.classList.add('active');
			offcanvas.setAttribute('aria-hidden', 'false');
			document.body.style.overflow = 'hidden';
		}

		function acceptaMinicartOffcanvasClose() {
			offcanvas.classList.remove('active');
			offcanvas.setAttribute('aria-hidden', 'true');
			document.body.style.overflow = '';
		}

		// Delegation: cart icon may be replaced by WooCommerce cart fragments
		document.addEventListener('click', function (e) {
			if (e.target.closest('[data-accepta-minicart-toggle]')) {
				e.preventDefault();
				acceptaMinicartOffcanvasOpen();
			}
		});

		offcanvas.querySelectorAll('[data-accepta-minicart-close]').forEach(function (btn) {
			btn.addEventListener('click', acceptaMinicartOffcanvasClose);
		});

		document.addEventListener('keydown', function (e) {
			if (e.key === 'Escape' && offcanvas.classList.contains('active')) {
				acceptaMinicartOffcanvasClose();
			}
		});
	}

	if (document.readyState === 'loading') {
		document.addEventListener('DOMContentLoaded', acceptaMinicartOffcanvasInit);
	} else {
		acceptaMinicartOffcanvasInit();
	}
})();
