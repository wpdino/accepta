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
    const socialToggle = document.querySelector('.header-social-toggle');
    const headerContent = document.querySelector('.header-content');
    const socialIconsPanel = document.querySelector('.header-social-icons');
    const searchToggle = document.querySelector('.header-search-toggle');
    const body = document.body;
    const menuPanelGap = 12;

    function acceptaMobileSocialIsOpen() {
        return !!(headerContent && headerContent.classList.contains('social-icons-open'));
    }

    function acceptaMobileOpenSocialIcons() {
        if (!socialToggle || !headerContent || !socialIconsPanel) {
            return;
        }

        headerContent.classList.add('social-icons-open');
        socialToggle.setAttribute('aria-expanded', 'true');
        socialIconsPanel.setAttribute('aria-hidden', 'false');
    }

    function acceptaMobileCloseSocialIcons() {
        if (!socialToggle || !headerContent || !socialIconsPanel) {
            return;
        }

        headerContent.classList.remove('social-icons-open');
        socialToggle.setAttribute('aria-expanded', 'false');
        socialIconsPanel.setAttribute('aria-hidden', 'true');
    }

    if (socialToggle && socialIconsPanel) {
        socialToggle.setAttribute('aria-expanded', 'false');
        socialIconsPanel.setAttribute('aria-hidden', window.innerWidth <= 768 ? 'true' : 'false');
    }

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
        acceptaMobileCloseSocialIcons();

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
            const itemToggle = item.querySelector(':scope > .accepta-submenu-toggle');
            if (itemToggle) {
                itemToggle.setAttribute('aria-expanded', 'false');
            }
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

    if (socialToggle && headerContent && socialIconsPanel) {
        socialToggle.addEventListener('click', function(e) {
            if (window.innerWidth > 768) {
                return;
            }

            e.preventDefault();
            e.stopPropagation();

            if (acceptaMobileSocialIsOpen()) {
                acceptaMobileCloseSocialIcons();
            } else {
                acceptaMobileCloseMenu(false);
                acceptaMobileOpenSocialIcons();
            }
        });
    }

    if (searchToggle) {
        searchToggle.addEventListener('click', function() {
            acceptaMobileCloseSocialIcons();
        });
    }
    
    // Function to setup mobile dropdown behaviors
    function acceptaMobileGetSubmenuToggleButton(item) {
        return item.querySelector(':scope > .accepta-submenu-toggle');
    }

    function acceptaMobileEnsureSubmenuToggleButton(item) {
        const submenu = item.querySelector(':scope > .sub-menu, :scope > .children');
        if (!submenu) {
            return null;
        }

        let toggleButton = acceptaMobileGetSubmenuToggleButton(item);
        if (toggleButton) {
            return toggleButton;
        }

        const parentLink = item.querySelector(':scope > a');
        const buttonText = parentLink ? parentLink.textContent.trim() : 'Submenu';

        toggleButton = document.createElement('button');
        toggleButton.type = 'button';
        toggleButton.className = 'accepta-submenu-toggle';
        toggleButton.setAttribute('aria-label', 'Toggle submenu for ' + buttonText);
        toggleButton.setAttribute('aria-expanded', 'false');
        toggleButton.setAttribute('aria-controls', submenu.id || '');

        if (!submenu.id) {
            submenu.id = 'accepta-submenu-' + Math.random().toString(36).slice(2, 10);
            toggleButton.setAttribute('aria-controls', submenu.id);
        }

        item.insertBefore(toggleButton, submenu);
        return toggleButton;
    }

    function acceptaMobileSetupDropdowns() {
        if (window.innerWidth <= 768) {
            dropdownItems.forEach(function(item) {
                const toggleButton = acceptaMobileEnsureSubmenuToggleButton(item);
                if (toggleButton) {
                    toggleButton.setAttribute('aria-expanded', item.classList.contains('toggled') ? 'true' : 'false');
                    toggleButton.removeEventListener('click', acceptaMobileHandleDropdownClick);
                    toggleButton.addEventListener('click', acceptaMobileHandleDropdownClick);
                }
            });
        } else {
            dropdownItems.forEach(function(item) {
                const toggleButton = acceptaMobileGetSubmenuToggleButton(item);
                if (toggleButton) {
                    toggleButton.removeEventListener('click', acceptaMobileHandleDropdownClick);
                    toggleButton.remove();
                }

                item.classList.remove('toggled');
            });

            // Ensure mobile modal state is cleaned up on desktop.
            acceptaMobileCloseMenu(false);
            acceptaMobileCloseSocialIcons();
        }
    }
    
    // Handler for dropdown clicks
    function acceptaMobileCollapseNested(parentItem) {
        const nestedDropdowns = parentItem.querySelectorAll('.menu-item-has-children.toggled, .page_item_has_children.toggled');
        nestedDropdowns.forEach(function(nestedItem) {
            nestedItem.classList.remove('toggled');
            const nestedToggle = nestedItem.querySelector(':scope > .accepta-submenu-toggle');
            if (nestedToggle) {
                nestedToggle.setAttribute('aria-expanded', 'false');
            }
        });
    }

    function acceptaMobileToggleParentItem(parentItem, shouldOpen) {
        if (!parentItem) {
            return;
        }

        const parentToggle = parentItem.querySelector(':scope > .accepta-submenu-toggle');
        const willOpen = typeof shouldOpen === 'boolean' ? shouldOpen : !parentItem.classList.contains('toggled');

        if (willOpen) {
            parentItem.classList.add('toggled');
            if (parentToggle) {
                parentToggle.setAttribute('aria-expanded', 'true');
            }
        } else {
            parentItem.classList.remove('toggled');
            if (parentToggle) {
                parentToggle.setAttribute('aria-expanded', 'false');
            }
            acceptaMobileCollapseNested(parentItem);
        }
    }

    function acceptaMobileHandleDropdownClick(e) {
        // Only prevent default if we're on mobile
        if (window.innerWidth <= 768) {
            e.preventDefault();
            e.stopPropagation();
            
            const parentItem = this.parentNode;
            const isOpening = !parentItem.classList.contains('toggled');
            acceptaMobileToggleParentItem(parentItem, isOpening);
            
            // Close other open dropdowns at the same level
            const siblings = parentItem.parentNode.querySelectorAll(':scope > .menu-item-has-children, :scope > .page_item_has_children');
            siblings.forEach(function(sibling) {
                if (sibling !== parentItem) {
                    acceptaMobileToggleParentItem(sibling, false);
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

        if (window.innerWidth <= 768 && acceptaMobileSocialIsOpen()) {
            const clickedInsideSocial = e.target.closest('.header-social-icons');
            const clickedSocialToggle = e.target.closest('.header-social-toggle');
            if (!clickedInsideSocial && !clickedSocialToggle) {
                acceptaMobileCloseSocialIcons();
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
            if (acceptaMobileSocialIsOpen()) {
                acceptaMobileCloseSocialIcons();
                if (socialToggle) {
                    socialToggle.focus();
                }
            } else {
                acceptaMobileCloseMenu(true);
            }
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