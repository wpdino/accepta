/**
 * Header Search Functionality
 * Handles the search button click and overlay display
 */
(function() {
    'use strict';

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
            body.style.overflow = 'hidden'; // Prevent body scroll
            
            // Focus on search field after animation
            setTimeout(function() {
                if (searchField) {
                    searchField.focus();
                }
            }, 300);
        }

        // Close search overlay
        function closeSearch() {
            searchOverlay.classList.remove('active');
            searchOverlay.setAttribute('aria-hidden', 'true');
            searchToggle.setAttribute('aria-expanded', 'false');
            body.style.overflow = ''; // Restore body scroll
        }

        // Toggle search overlay
        searchToggle.addEventListener('click', function(e) {
            e.preventDefault();
            if (searchOverlay.classList.contains('active')) {
                closeSearch();
            } else {
                openSearch();
            }
        });

        // Close button click
        if (searchClose) {
            searchClose.addEventListener('click', function(e) {
                e.preventDefault();
                closeSearch();
            });
        }

        // Close on Escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape' && searchOverlay.classList.contains('active')) {
                closeSearch();
            }
        });

        // Close on overlay background click (but not on content click)
        searchOverlay.addEventListener('click', function(e) {
            if (e.target === searchOverlay) {
                closeSearch();
            }
        });
    });
})();

