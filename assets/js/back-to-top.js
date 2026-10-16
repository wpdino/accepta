/**
 * Back to top button – show after scroll, smooth scroll to top.
 *
 * @package Accepta
 */
(function () {
	'use strict';

	document.addEventListener('DOMContentLoaded', function () {
		var button = document.querySelector('[data-accepta-back-to-top]');
		if (!button) {
			return;
		}

		var threshold = 400;
		var preferReducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

		function acceptaBackToTopToggle() {
			var shouldShow = window.scrollY > threshold;
			button.hidden = !shouldShow;
			button.classList.toggle('is-visible', shouldShow);
		}

		button.addEventListener('click', function () {
			window.scrollTo({
				top: 0,
				behavior: preferReducedMotion ? 'auto' : 'smooth',
			});
		});

		window.addEventListener('scroll', acceptaBackToTopToggle, { passive: true });
		acceptaBackToTopToggle();
	});
})();
