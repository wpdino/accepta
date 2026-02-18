/**
 * Mobile menu toggle functionality
 */
document.addEventListener('DOMContentLoaded', function() {
    const menuToggle = document.querySelector('.menu-toggle');
    const primaryMenu = document.querySelector('#primary-menu');
    const dropdownItems = document.querySelectorAll('.menu-item-has-children, .page_item_has_children');
    
    // Toggle mobile menu
    if (menuToggle && primaryMenu) {
        menuToggle.addEventListener('click', function() {
            primaryMenu.classList.toggle('toggled');
            this.setAttribute('aria-expanded', this.getAttribute('aria-expanded') === 'true' ? 'false' : 'true');
        });
    }
    
    // Function to setup mobile dropdown behaviors
    function setupMobileDropdowns() {
        if (window.innerWidth <= 768) {
            // First remove existing click events to prevent duplicates
            dropdownItems.forEach(function(item) {
                const link = item.querySelector('a');
                if (link) {
                    link.removeEventListener('click', handleDropdownClick);
                    link.addEventListener('click', handleDropdownClick);
                }
            });
        } else {
            // Remove mobile-specific behaviors when on desktop
            dropdownItems.forEach(function(item) {
                const link = item.querySelector('a');
                if (link) {
                    link.removeEventListener('click', handleDropdownClick);
                }
                // Reset any toggled state when switching to desktop
                item.classList.remove('toggled');
            });
            
            // Make sure the menu is visible on desktop if it was hidden
            if (primaryMenu) {
                primaryMenu.classList.remove('toggled');
            }
            // Reset the menu toggle button state
            if (menuToggle) {
                menuToggle.setAttribute('aria-expanded', 'false');
            }
        }
    }
    
    // Handler for dropdown clicks
    function handleDropdownClick(e) {
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
    setupMobileDropdowns();
    
    // Close mobile menu when clicking outside
    document.addEventListener('click', function(e) {
        if (window.innerWidth <= 768 && primaryMenu && primaryMenu.classList.contains('toggled')) {
            // Check if the click is outside the navigation area
            if (!e.target.closest('.main-navigation')) {
                primaryMenu.classList.remove('toggled');
                // Close all dropdown menus
                const allOpenDropdowns = primaryMenu.querySelectorAll('.menu-item-has-children.toggled, .page_item_has_children.toggled');
                allOpenDropdowns.forEach(function(item) {
                    item.classList.remove('toggled');
                });
                
                if (menuToggle) {
                    menuToggle.setAttribute('aria-expanded', 'false');
                }
            }
        }
    });
    
    // Update on window resize
    let resizeTimer;
    window.addEventListener('resize', function() {
        // Debounce the resize event
        clearTimeout(resizeTimer);
        resizeTimer = setTimeout(function() {
            setupMobileDropdowns();
        }, 250);
    });
}); 