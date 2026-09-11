/**
 * Sticky Add to cart bar on single product pages.
 *
 * Shows when the main Add to cart control scrolls out of the viewport above,
 * AJAX-adds the product, then opens the minicart panel.
 */
(function () {
	'use strict';

	document.addEventListener('DOMContentLoaded', function () {
		var bar = document.querySelector('[data-accepta-sticky-atc]');
		if (!bar) {
			return;
		}

		var trigger = document.querySelector('.summary form.cart .single_add_to_cart_button');
		var form = document.querySelector('.summary form.cart');
		var stickyButton = bar.querySelector('[data-accepta-sticky-atc-trigger]');
		var productType = bar.getAttribute('data-product-type') || '';
		var productId = bar.getAttribute('data-product-id') || '';
		var settings = window.acceptaStickyAtc || {};

		if (!trigger) {
			return;
		}

		function acceptaStickyAtcSetVisible(isVisible) {
			if (isVisible) {
				bar.hidden = false;
				bar.setAttribute('aria-hidden', 'false');
				bar.classList.add('is-visible');
				document.body.classList.add('accepta-sticky-atc-visible');
			} else {
				bar.classList.remove('is-visible');
				bar.setAttribute('aria-hidden', 'true');
				document.body.classList.remove('accepta-sticky-atc-visible');
				window.setTimeout(function () {
					if (!bar.classList.contains('is-visible')) {
						bar.hidden = true;
					}
				}, 280);
			}
		}

		function acceptaStickyAtcVariationReady() {
			if (productType !== 'variable' || !form) {
				return true;
			}

			var variationId = form.querySelector('input[name="variation_id"]');
			return !!(variationId && variationId.value && variationId.value !== '0');
		}

		function acceptaStickyAtcUpdateLabel() {
			if (!stickyButton || productType !== 'variable') {
				return;
			}

			var addLabel = stickyButton.getAttribute('data-label-add') || 'Add to cart';
			var selectLabel = stickyButton.getAttribute('data-label-select') || 'Select options';
			stickyButton.textContent = acceptaStickyAtcVariationReady() ? addLabel : selectLabel;
		}

		function acceptaStickyAtcRestoreLabel() {
			if (!stickyButton) {
				return;
			}

			if (productType === 'variable') {
				acceptaStickyAtcUpdateLabel();
				return;
			}

			stickyButton.textContent =
				stickyButton.getAttribute('data-label-add') || stickyButton.textContent;
		}

		function acceptaStickyAtcCollectData() {
			var data = {};

			if (form) {
				var formData = new FormData(form);
				formData.forEach(function (value, key) {
					data[key] = value;
				});
			}

			if (!data.product_id) {
				data.product_id = data['add-to-cart'] || productId;
			}

			if (!data.quantity) {
				data.quantity = 1;
			}

			return data;
		}

		function acceptaStickyAtcOpenMinicart() {
			if (typeof window.acceptaOpenMinicart === 'function') {
				window.acceptaOpenMinicart();
			}
		}

		function acceptaStickyAtcAddToCart() {
			var ajaxUrl = settings.ajaxUrl || '';

			if (!ajaxUrl || typeof window.jQuery === 'undefined') {
				if (trigger && !trigger.disabled) {
					trigger.click();
				}
				return;
			}

			if (stickyButton.disabled || stickyButton.classList.contains('is-loading')) {
				return;
			}

			var data = acceptaStickyAtcCollectData();
			var addingLabel =
				(settings.i18n && settings.i18n.adding) || 'Adding…';
			var errorLabel =
				(settings.i18n && settings.i18n.error) ||
				'Could not add to cart. Please try again.';

			stickyButton.classList.add('is-loading');
			stickyButton.disabled = true;
			stickyButton.textContent = addingLabel;

			window.jQuery
				.post(ajaxUrl, data)
				.done(function (response) {
					if (!response) {
						window.alert(errorLabel);
						return;
					}

					if (response.error && response.product_url) {
						window.location = response.product_url;
						return;
					}

					window.jQuery(document.body).trigger('added_to_cart', [
						response.fragments,
						response.cart_hash,
						window.jQuery(stickyButton),
					]);

					acceptaStickyAtcOpenMinicart();
				})
				.fail(function () {
					window.alert(errorLabel);
				})
				.always(function () {
					stickyButton.classList.remove('is-loading');
					stickyButton.disabled = false;
					acceptaStickyAtcRestoreLabel();
				});
		}

		var observer = new IntersectionObserver(
			function (entries) {
				entries.forEach(function (entry) {
					// Show only after the main button has scrolled above the viewport.
					var scrolledPast =
						!entry.isIntersecting && entry.boundingClientRect.top < 0;
					acceptaStickyAtcSetVisible(scrolledPast);
				});
			},
			{
				root: null,
				threshold: 0,
			}
		);

		observer.observe(trigger);

		if (form && productType === 'variable') {
			form.addEventListener('change', acceptaStickyAtcUpdateLabel);
			form.addEventListener('woocommerce_variation_has_changed', acceptaStickyAtcUpdateLabel);
			form.addEventListener('found_variation', acceptaStickyAtcUpdateLabel);
			form.addEventListener('reset_data', acceptaStickyAtcUpdateLabel);

			if (typeof window.jQuery !== 'undefined') {
				window.jQuery(form).on(
					'found_variation reset_data hide_variation show_variation check_variations',
					acceptaStickyAtcUpdateLabel
				);
			}

			acceptaStickyAtcUpdateLabel();
		}

		if (stickyButton) {
			stickyButton.addEventListener('click', function () {
				if (productType === 'variable' && !acceptaStickyAtcVariationReady()) {
					if (form) {
						form.scrollIntoView({ behavior: 'smooth', block: 'center' });
					}
					return;
				}

				acceptaStickyAtcAddToCart();
			});
		}
	});
})();
