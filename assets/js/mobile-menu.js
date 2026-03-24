/**
 * Mobile menu toggle functionality
 */
(function() {
    'use strict';

document.addEventListener('DOMContentLoaded', function() {
    const menuToggle = document.querySelector('.menu-toggle');
    const mainNavigation = document.querySelector('.main-navigation');
    const primaryMenu = document.querySelector('#primary-menu');
    const dropdownItems = document.querySelectorAll('.menu-item-has-children, .page_item_has_children');
    const body = document.body;
    const menuPanelGap = 12;

    function acceptaMobileIsOpen() {
        return !!(primaryMenu && primaryMenu.classList.contains('toggled'));
    }

    function acceptaMobileGetFocusableElements() {
        if (!primaryMenu) {
            return [];
        }

        const focusables = primaryMenu.querySelectorAll('a[href], button:not([disabled]), [tabindex]:not([tabindex="-1"])');
        return Array.prototype.filter.call(focusables, function(el) {
            return el.offsetParent !== null;
        });
    }

    function acceptaMobileOpenMenu() {
        if (!menuToggle || !primaryMenu) {
            return;
        }

        primaryMenu.classList.add('toggled');
        menuToggle.classList.add('toggled');
        menuToggle.setAttribute('aria-expanded', 'true');
        body.classList.add('accepta-mobile-menu-open');

        if (mainNavigation) {
            mainNavigation.classList.add('toggled');
        }

        // Position fixed menu below the toggle/header row.
        const toggleRect = menuToggle.getBoundingClientRect();
        document.documentElement.style.setProperty('--accepta-mobile-menu-top', Math.max(0, Math.round(toggleRect.bottom + menuPanelGap)) + 'px');

        const focusables = acceptaMobileGetFocusableElements();
        if (focusables.length > 0) {
            focusables[0].focus();
        } else {
            menuToggle.focus();
        }
    }

    function acceptaMobileCloseMenu(restoreFocus) {
        if (!menuToggle || !primaryMenu) {
            return;
        }

        primaryMenu.classList.remove('toggled');
        menuToggle.classList.remove('toggled');
        menuToggle.setAttribute('aria-expanded', 'false');
        body.classList.remove('accepta-mobile-menu-open');
        document.documentElement.style.removeProperty('--accepta-mobile-menu-top');

        if (mainNavigation) {
            mainNavigation.classList.remove('toggled');
        }

        // Close all open dropdown menus when closing main menu.
        const allOpenDropdowns = primaryMenu.querySelectorAll('.menu-item-has-children.toggled, .page_item_has_children.toggled');
        allOpenDropdowns.forEach(function(item) {
            item.classList.remove('toggled');
        });

        if (restoreFocus) {
            menuToggle.focus();
        }
    }

    // Toggle mobile menu
    if (menuToggle && primaryMenu) {
        menuToggle.addEventListener('click', function() {
            if (acceptaMobileIsOpen()) {
                acceptaMobileCloseMenu(false);
            } else {
                acceptaMobileOpenMenu();
            }
        });
    }
    
    // Function to setup mobile dropdown behaviors
    function acceptaMobileSetupDropdowns() {
        if (window.innerWidth <= 768) {
            // First remove existing click events to prevent duplicates
            dropdownItems.forEach(function(item) {
                const link = item.querySelector('a');
                if (link) {
                    link.removeEventListener('click', acceptaMobileHandleDropdownClick);
                    link.addEventListener('click', acceptaMobileHandleDropdownClick);
                }
            });
        } else {
            // Remove mobile-specific behaviors when on desktop
            dropdownItems.forEach(function(item) {
                const link = item.querySelector('a');
                if (link) {
                    link.removeEventListener('click', acceptaMobileHandleDropdownClick);
                }
                // Reset any toggled state when switching to desktop
                item.classList.remove('toggled');
            });

            // Ensure mobile modal state is cleaned up on desktop.
            acceptaMobileCloseMenu(false);
        }
    }
    
    // Handler for dropdown clicks
    function acceptaMobileHandleDropdownClick(e) {
        // Only prevent default if we're on mobile
        if (window.innerWidth <= 768) {
            e.preventDefault();
            e.stopPropagation();
            
            const parentItem = this.parentNode;
            parentItem.classList.toggle('toggled');
            
            // Find the submenu - could be either .sub-menu or .children
            const submenu = parentItem.querySelector('.sub-menu, .children');
            
            // If nested dropdown was toggled but parent item is being closed, close all nested dropdowns
            if (!parentItem.classList.contains('toggled')) {
                // Close all child dropdown items when parent is closed
                const nestedDropdowns = parentItem.querySelectorAll('.menu-item-has-children.toggled, .page_item_has_children.toggled');
                nestedDropdowns.forEach(function(nestedItem) {
                    nestedItem.classList.remove('toggled');
                });
            }
            
            // Close other open dropdowns at the same level
            const siblings = parentItem.parentNode.querySelectorAll(':scope > .menu-item-has-children, :scope > .page_item_has_children');
            siblings.forEach(function(sibling) {
                if (sibling !== parentItem) {
                    sibling.classList.remove('toggled');
                    
                    // Also close any nested dropdowns in siblings
                    const nestedDropdowns = sibling.querySelectorAll('.menu-item-has-children.toggled, .page_item_has_children.toggled');
                    nestedDropdowns.forEach(function(nestedItem) {
                        nestedItem.classList.remove('toggled');
                    });
                }
            });
        }
    }
    
    // Setup on page load
    acceptaMobileSetupDropdowns();
    
    // Close mobile menu when clicking outside
    document.addEventListener('click', function(e) {
        if (window.innerWidth <= 768 && primaryMenu && primaryMenu.classList.contains('toggled')) {
            // Check if the click is outside the navigation area
            if (!e.target.closest('.main-navigation')) {
                acceptaMobileCloseMenu(false);
            }
        }
    });

    // Trap focus inside mobile menu when open.
    document.addEventListener('keydown', function(e) {
        if (window.innerWidth > 768 || !acceptaMobileIsOpen() || !menuToggle) {
            return;
        }

        if (e.key === 'Escape') {
            e.preventDefault();
            acceptaMobileCloseMenu(true);
            return;
        }

        if (e.key !== 'Tab') {
            return;
        }

        const focusables = acceptaMobileGetFocusableElements();
        const closeButton = menuToggle;
        const active = document.activeElement;

        if (focusables.length === 0) {
            e.preventDefault();
            closeButton.focus();
            return;
        }

        const first = focusables[0];
        const last = focusables[focusables.length - 1];

        if (!e.shiftKey) {
            // Forward cycle: close button -> first item, last item -> close button.
            if (active === closeButton) {
                e.preventDefault();
                first.focus();
            } else if (active === last) {
                e.preventDefault();
                closeButton.focus();
            }
        } else {
            // Backward cycle requested:
            // first item -> close button, close button -> last item.
            if (active === first) {
                e.preventDefault();
                closeButton.focus();
            } else if (active === closeButton) {
                e.preventDefault();
                last.focus();
            }
        }
    });
    
    // Update on window resize
    let resizeTimer;
    window.addEventListener('resize', function() {
        // Debounce the resize event
        clearTimeout(resizeTimer);
        resizeTimer = setTimeout(function() {
            acceptaMobileSetupDropdowns();
        }, 250);
    });
}); 

})(); 