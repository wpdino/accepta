/**
 * Header Search Functionality
 * Handles the search button click, overlay display, and keyboard focus trap.
 */
(function() {
    'use strict';

    var FOCUSABLE_SELECTOR = 'button, [href], input, select, textarea, [tabindex]:not([tabindex="-1"])';

    function getFocusables(container) {
        if (!container) return [];
        var nodes = container.querySelectorAll(FOCUSABLE_SELECTOR);
        return Array.prototype.filter.call(nodes, function(el) {
            return el.offsetParent !== null && !el.hasAttribute('disabled');
        });
    }

    document.addEventListener('DOMContentLoaded', function() {
        var searchToggle = document.querySelector('.header-search-toggle');
        var searchOverlay = document.querySelector('.header-search-overlay');
        var searchClose = document.querySelector('.header-search-close');
        var searchField = document.querySelector('.header-search-overlay .search-field');
        var body = document.body;

        if (!searchToggle || !searchOverlay) {
            return;
        }

        // Open search overlay
        function openSearch() {
            searchOverlay.classList.add('active');
            searchOverlay.setAttribute('aria-hidden', 'false');
            searchToggle.setAttribute('aria-expanded', 'true');
            body.style.overflow = 'hidden';

            setTimeout(function() {
                var focusables = getFocusables(searchOverlay);
                if (searchField) {
                    searchField.focus();
                } else if (focusables.length > 0) {
                    focusables[0].focus();
                }
            }, 300);
        }

        // Close search overlay and return focus to toggle
        function closeSearch() {
            searchOverlay.classList.remove('active');
            searchOverlay.setAttribute('aria-hidden', 'true');
            searchToggle.setAttribute('aria-expanded', 'false');
            body.style.overflow = '';
            searchToggle.focus();
        }

        // Trap focus inside search overlay when open
        function handleOverlayKeydown(e) {
            if (!searchOverlay.classList.contains('active') || e.key !== 'Tab') {
                return;
            }
            var focusables = getFocusables(searchOverlay);
            if (focusables.length === 0) return;

            var first = focusables[0];
            var last = focusables[focusables.length - 1];
            var active = document.activeElement;

            if (e.shiftKey) {
                if (active === first) {
                    e.preventDefault();
                    last.focus();
                }
            } else {
                if (active === last) {
                    e.preventDefault();
                    first.focus();
                }
            }
        }

        searchOverlay.addEventListener('keydown', handleOverlayKeydown);

        searchToggle.addEventListener('click', function(e) {
            e.preventDefault();
            if (searchOverlay.classList.contains('active')) {
                closeSearch();
            } else {
                openSearch();
            }
        });

        if (searchClose) {
            searchClose.addEventListener('click', function(e) {
                e.preventDefault();
                closeSearch();
            });
        }

        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape' && searchOverlay.classList.contains('active')) {
                closeSearch();
            }
        });

        searchOverlay.addEventListener('click', function(e) {
            if (e.target === searchOverlay) {
                closeSearch();
            }
        });
    });
})();

