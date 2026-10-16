/**
 * Sticky Header functionality
 *
 * Handles the sticky header behavior when scrolling.
 * Keeps --header-height locked to the unscrolled header size so overlay
 * hero padding does not jump when the header shrinks on scroll.
 */

(function() {
	'use strict';

	const header = document.querySelector('.site-header');
	const body = document.body;

	/**
	 * Always expose measured header height for overlay-hero spacing CSS.
	 */
	function acceptaSetHeaderHeightVar() {
		if (!header) {
			return;
		}
		document.documentElement.style.setProperty('--header-height', header.offsetHeight + 'px');
	}

	// Check if sticky header is enabled
	if (typeof acceptaStickyHeader === 'undefined' || !acceptaStickyHeader.enabled) {
		// Clean up any sticky header classes if they exist
		if (header) {
			header.classList.remove('scrolled');
			header.style.top = '';
		}
		if (body) {
			body.classList.remove('has-sticky-header');
			body.style.paddingTop = '';
		}
		// Still set height var for transparent/overlay hero clearance.
		acceptaSetHeaderHeightVar();
		window.addEventListener('resize', acceptaSetHeaderHeightVar);
		return; // Exit sticky behavior if sticky header is disabled
	}

	// Variables
	let headerHeight = header ? header.offsetHeight : 0;
	let scrollPosition = 0;
	let ticking = false;
	let isCompact = false;
	const isAdminBar = document.body.classList.contains('admin-bar');

	/**
	 * Get admin bar height from CSS variable or calculate it.
	 *
	 * @return {number}
	 */
	function acceptaStickyGetAdminBarHeight() {
		if (!isAdminBar) {
			return 0;
		}
		// Use WordPress CSS variable if available (WordPress 5.7+)
		const cssVar = getComputedStyle(document.documentElement).getPropertyValue('--wp-admin--admin-bar--height');
		if (cssVar && cssVar.trim() !== '') {
			const value = parseInt(cssVar.trim().replace('px', ''), 10);
			if (!isNaN(value) && value > 0) {
				return value;
			}
		}
		return window.innerWidth > 782 ? 32 : 46;
	}

	let adminBarHeight = acceptaStickyGetAdminBarHeight();

	document.documentElement.style.setProperty('--header-height', headerHeight + 'px');

	/**
	 * Measure the natural (unscrolled) header height and publish CSS var.
	 * Avoids hero/content jumps when the compact scrolled styles shrink the bar.
	 */
	function acceptaStickyUpdateHeaderHeight() {
		if (!header) {
			return;
		}

		const wasScrolled = header.classList.contains('scrolled');
		const saved = {
			padding: header.style.padding,
			backgroundColor: header.style.backgroundColor,
			boxShadow: header.style.boxShadow,
			position: header.style.position,
			top: header.style.top,
		};

		if (wasScrolled) {
			header.classList.remove('scrolled');
			header.style.removeProperty('padding');
			header.style.removeProperty('background-color');
			header.style.removeProperty('box-shadow');
		}

		headerHeight = header.offsetHeight;
		document.documentElement.style.setProperty('--header-height', headerHeight + 'px');

		if (wasScrolled) {
			header.classList.add('scrolled');
			if (saved.padding) {
				header.style.padding = saved.padding;
			}
			if (saved.backgroundColor) {
				header.style.backgroundColor = saved.backgroundColor;
			}
			if (saved.boxShadow) {
				header.style.boxShadow = saved.boxShadow;
			}
			if (saved.position) {
				header.style.position = saved.position;
			}
			if (saved.top) {
				header.style.top = saved.top;
			}
		}
	}

	/**
	 * Convert hex color to rgba.
	 *
	 * @param {string} hex Hex color.
	 * @param {number} opacity Opacity 0–1.
	 * @return {string}
	 */
	function acceptaStickyHexToRgba(hex, opacity) {
		hex = hex.replace('#', '');
		if (hex.length === 3) {
			hex = hex[0] + hex[0] + hex[1] + hex[1] + hex[2] + hex[2];
		}
		const r = parseInt(hex.substring(0, 2), 16);
		const g = parseInt(hex.substring(2, 4), 16);
		const b = parseInt(hex.substring(4, 6), 16);
		return 'rgba(' + r + ', ' + g + ', ' + b + ', ' + opacity + ')';
	}

	/**
	 * Apply compact scrolled styles once when crossing the threshold.
	 */
	function acceptaStickyEnterCompact() {
		if (!header || isCompact) {
			return;
		}

		isCompact = true;
		header.classList.add('scrolled');

		const isTransparent = typeof acceptaStickyHeader !== 'undefined' && acceptaStickyHeader.transparent;
		const scrolledBg = typeof acceptaStickyHeader !== 'undefined' ? acceptaStickyHeader.scrolledBg : '#ffffff';
		const scrolledBgOpacity = typeof acceptaStickyHeader !== 'undefined' ? (acceptaStickyHeader.scrolledBgOpacity || 1) : 1;

		header.style.position = 'fixed';
		header.style.top = isAdminBar ? adminBarHeight + 'px' : '0';

		if (!isTransparent) {
			header.style.left = '0';
			header.style.right = '0';
			document.body.classList.add('has-sticky-header');
		}

		if (scrolledBg) {
			header.style.backgroundColor = acceptaStickyHexToRgba(scrolledBg, scrolledBgOpacity);
			header.style.boxShadow = '0 4px 15px rgba(0, 0, 0, 0.1)';
			header.style.padding = '0.5rem 0';
		}

		// Do not update --header-height here — hero padding must stay stable.
	}

	/**
	 * Restore the header when near the top of the page.
	 */
	function acceptaStickyLeaveCompact() {
		if (!header || !isCompact) {
			return;
		}

		isCompact = false;
		header.classList.remove('scrolled');

		const isTransparent = typeof acceptaStickyHeader !== 'undefined' && acceptaStickyHeader.transparent;

		header.style.removeProperty('position');
		header.style.removeProperty('top');
		header.style.removeProperty('background-color');
		header.style.removeProperty('box-shadow');
		header.style.removeProperty('padding');

		if (!isTransparent) {
			header.style.left = '';
			header.style.right = '';
			document.body.classList.remove('has-sticky-header');
		}

		// Refresh base height after leaving compact mode (logo/padding restored).
		acceptaStickyUpdateHeaderHeight();
	}

	/**
	 * Handle scroll behavior.
	 */
	function acceptaStickyHandleScroll() {
		const threshold = 50;

		if (scrollPosition > threshold) {
			acceptaStickyEnterCompact();
		} else {
			acceptaStickyLeaveCompact();
		}
	}

	/**
	 * Initialize sticky header.
	 */
	function acceptaStickyInitHeader() {
		if (!header) {
			return;
		}

		acceptaStickyUpdateHeaderHeight();

		window.addEventListener('resize', function() {
			adminBarHeight = acceptaStickyGetAdminBarHeight();
			acceptaStickyUpdateHeaderHeight();

			if (isCompact && isAdminBar) {
				header.style.top = adminBarHeight + 'px';
			}
		});

		window.addEventListener('scroll', function() {
			scrollPosition = window.scrollY;

			if (!ticking) {
				window.requestAnimationFrame(function() {
					acceptaStickyHandleScroll();
					ticking = false;
				});
				ticking = true;
			}
		});

		// Apply correct state if the page loads mid-scroll (e.g. refresh).
		scrollPosition = window.scrollY;
		acceptaStickyHandleScroll();
	}

	if (document.readyState === 'loading') {
		document.addEventListener('DOMContentLoaded', acceptaStickyInitHeader);
	} else {
		acceptaStickyInitHeader();
	}
})();
