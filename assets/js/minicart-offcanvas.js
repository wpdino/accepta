/**
 * Minicart offcanvas panel – open/close from right.
 * Uses event delegation so the cart icon still works after WooCommerce cart fragments replace it.
 *
 * @package Accepta
 */
(function () {
	'use strict';

	function acceptaMinicartGetFocusable(container) {
		if (!container) {
			return [];
		}
		return Array.prototype.slice.call(
			container.querySelectorAll(
				'a[href], button:not([disabled]), input:not([disabled]), select:not([disabled]), textarea:not([disabled]), [tabindex]:not([tabindex="-1"])'
			)
		).filter(function (el) {
			return el.offsetParent !== null || el === document.activeElement;
		});
	}

	function acceptaMinicartOffcanvasInit() {
		var offcanvas = document.getElementById('accepta-minicart-offcanvas');
		if (!offcanvas) {
			return;
		}

		var dialog = document.getElementById('accepta-minicart-dialog');
		var lastFocused = null;

		function acceptaMinicartOffcanvasOpen(trigger) {
			lastFocused = trigger && trigger.focus ? trigger : document.activeElement;
			offcanvas.classList.add('active');
			offcanvas.setAttribute('aria-hidden', 'false');
			document.body.style.overflow = 'hidden';

			var focusTarget = dialog
				? dialog.querySelector('[data-accepta-minicart-close]')
				: null;
			if (focusTarget) {
				focusTarget.focus();
			} else if (dialog) {
				dialog.focus();
			}
		}

		function acceptaMinicartOffcanvasClose() {
			offcanvas.classList.remove('active');
			offcanvas.setAttribute('aria-hidden', 'true');
			document.body.style.overflow = '';

			if (lastFocused && typeof lastFocused.focus === 'function') {
				lastFocused.focus();
			}
			lastFocused = null;
		}

		window.acceptaOpenMinicart = acceptaMinicartOffcanvasOpen;
		window.acceptaCloseMinicart = acceptaMinicartOffcanvasClose;

		document.addEventListener('click', function (e) {
			var toggle = e.target.closest('[data-accepta-minicart-toggle]');
			if (toggle) {
				e.preventDefault();
				acceptaMinicartOffcanvasOpen(toggle);
			}
		});

		offcanvas.querySelectorAll('[data-accepta-minicart-close]').forEach(function (btn) {
			btn.addEventListener('click', acceptaMinicartOffcanvasClose);
		});

		document.addEventListener('keydown', function (e) {
			if (!offcanvas.classList.contains('active')) {
				return;
			}

			if (e.key === 'Escape') {
				e.preventDefault();
				acceptaMinicartOffcanvasClose();
				return;
			}

			if (e.key !== 'Tab' || !dialog) {
				return;
			}

			var focusable = acceptaMinicartGetFocusable(dialog);
			if (!focusable.length) {
				e.preventDefault();
				return;
			}

			var first = focusable[0];
			var last = focusable[focusable.length - 1];

			if (e.shiftKey && document.activeElement === first) {
				e.preventDefault();
				last.focus();
			} else if (!e.shiftKey && document.activeElement === last) {
				e.preventDefault();
				first.focus();
			}
		});
	}

	if (document.readyState === 'loading') {
		document.addEventListener('DOMContentLoaded', acceptaMinicartOffcanvasInit);
	} else {
		acceptaMinicartOffcanvasInit();
	}
})();