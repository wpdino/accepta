/**
 * Sticky Header functionality
 *
 * Handles the sticky header behavior when scrolling
 */

(function() {
    'use strict';

    // Check if sticky header is enabled
    if (typeof acceptaStickyHeader === 'undefined' || !acceptaStickyHeader.enabled) {
        // Clean up any sticky header classes if they exist
        const header = document.querySelector('.site-header');
        const body = document.body;
        if (header) {
            header.classList.remove('scrolled');
            header.style.top = '';
        }
        if (body) {
            body.classList.remove('has-sticky-header');
            body.style.paddingTop = '';
        }
        // Remove CSS variable
        document.documentElement.style.removeProperty('--header-height');
        return; // Exit if sticky header is disabled
    }

    // Variables
    const header = document.querySelector('.site-header');
    let headerHeight = header ? header.offsetHeight : 0;
    let scrollPosition = 0;
    let ticking = false;
    const isAdminBar = document.body.classList.contains('admin-bar');
    
    // Get admin bar height from CSS variable or calculate it
    function getAdminBarHeight() {
        if (!isAdminBar) return 0;
        // Use WordPress CSS variable if available (WordPress 5.7+)
        const cssVar = getComputedStyle(document.documentElement).getPropertyValue('--wp-admin--admin-bar--height');
        if (cssVar && cssVar.trim() !== '') {
            // Remove 'px' if present and parse
            const value = parseInt(cssVar.trim().replace('px', ''), 10);
            if (!isNaN(value) && value > 0) {
                return value;
            }
        }
        // Fallback to calculated height based on viewport width
        return window.innerWidth > 782 ? 32 : 46;
    }
    
    let adminBarHeight = getAdminBarHeight();
    
    // Set the header height as a CSS variable
    document.documentElement.style.setProperty('--header-height', `${headerHeight}px`);

    /**
     * Initialize sticky header
     */
    function initStickyHeader() {
        if (!header) return;
        
        // Get initial header height
        updateHeaderHeight();
        
        // Update header height on resize
        window.addEventListener('resize', function() {
            updateHeaderHeight();
            // On mobile: remove sticky state so header scrolls with page
            if (window.innerWidth <= 767) {
                header.classList.remove('scrolled');
                header.style.removeProperty('position');
                header.style.removeProperty('top');
                header.style.left = '';
                header.style.right = '';
                header.style.removeProperty('background-color');
                header.style.removeProperty('box-shadow');
                header.style.removeProperty('padding');
                document.body.classList.remove('has-sticky-header');
                return;
            }
            // Recalculate admin bar height on resize
            adminBarHeight = getAdminBarHeight();
            if (header.classList.contains('scrolled') && isAdminBar) {
                header.style.top = `${adminBarHeight}px`;
            }
        });

        // Listen for scroll events
        window.addEventListener('scroll', function() {
            scrollPosition = window.scrollY;
            
            if (!ticking) {
                window.requestAnimationFrame(function() {
                    handleScroll();
                    ticking = false;
                });
                
                ticking = true;
            }
        });
    }
    
    /**
     * Update header height
     */
    function updateHeaderHeight() {
        headerHeight = header.offsetHeight;
        document.documentElement.style.setProperty('--header-height', `${headerHeight}px`);
    }

    /**
     * Handle scroll behavior
     */
    /**
     * Convert hex color to rgba
     */
    function hexToRgba(hex, opacity) {
        hex = hex.replace('#', '');
        if (hex.length === 3) {
            hex = hex[0] + hex[0] + hex[1] + hex[1] + hex[2] + hex[2];
        }
        const r = parseInt(hex.substring(0, 2), 16);
        const g = parseInt(hex.substring(2, 4), 16);
        const b = parseInt(hex.substring(4, 6), 16);
        return 'rgba(' + r + ', ' + g + ', ' + b + ', ' + opacity + ')';
    }

    function handleScroll() {
        // On mobile: header scrolls with page (no sticky)
        if (window.innerWidth <= 767) {
            header.classList.remove('scrolled');
            header.style.removeProperty('position');
            header.style.removeProperty('top');
            header.style.left = '';
            header.style.right = '';
            header.style.removeProperty('background-color');
            header.style.removeProperty('box-shadow');
            header.style.removeProperty('padding');
            document.body.classList.remove('has-sticky-header');
            return;
        }

        // Threshold to activate sticky header
        const threshold = 50; // Lower threshold for quicker activation
        const isTransparent = typeof acceptaStickyHeader !== 'undefined' && acceptaStickyHeader.transparent;
        const scrolledBg = typeof acceptaStickyHeader !== 'undefined' ? acceptaStickyHeader.scrolledBg : '#ffffff';
        const scrolledBgOpacity = typeof acceptaStickyHeader !== 'undefined' ? (acceptaStickyHeader.scrolledBgOpacity || 1) : 1;
        
        if (scrollPosition > threshold) {
            if (!header.classList.contains('scrolled')) {
                header.classList.add('scrolled');
                // Update header height after it becomes sticky (after transition)
                setTimeout(updateHeaderHeight, 300);
            }
            
            // Always apply scrolled background color with opacity (whether just scrolled or already scrolled)
            // If transparent header (overlay), change position to fixed
            if (isTransparent) {
                header.style.position = 'fixed';
                header.style.top = isAdminBar ? `${adminBarHeight}px` : '0';
                // Apply scrolled background color with opacity
                if (scrolledBg) {
                    const rgba = hexToRgba(scrolledBg, scrolledBgOpacity);
                    header.style.backgroundColor = rgba;
                    header.style.boxShadow = '0 4px 15px rgba(0, 0, 0, 0.1)';
                    header.style.padding = '0.5rem 0';
                }
            } else {
                // For regular sticky header, change to fixed position when scrolled
                header.style.position = 'fixed';
                header.style.top = isAdminBar ? `${adminBarHeight}px` : '0';
                header.style.left = '0';
                header.style.right = '0';
                
                // Apply scrolled background color with opacity
                if (scrolledBg) {
                    const rgba = hexToRgba(scrolledBg, scrolledBgOpacity);
                    header.style.backgroundColor = rgba;
                    header.style.boxShadow = '0 4px 15px rgba(0, 0, 0, 0.1)';
                    header.style.padding = '0.5rem 0';
                }
                document.body.classList.add('has-sticky-header');
            }
        } else {
            // Remove sticky class when near the top
            if (header.classList.contains('scrolled')) {
                header.classList.remove('scrolled');
                
                // If transparent header (overlay), remove inline styles to let CSS handle it
                if (isTransparent) {
                    header.style.removeProperty('position');
                    header.style.removeProperty('top');
                    header.style.removeProperty('background-color');
                    header.style.removeProperty('box-shadow');
                    header.style.removeProperty('padding');
                } else {
                    // For regular sticky header, remove inline styles to let CSS handle it
                    header.style.removeProperty('position');
                    header.style.removeProperty('top');
                    header.style.left = '';
                    header.style.right = '';
                    header.style.removeProperty('background-color');
                    header.style.removeProperty('box-shadow');
                    header.style.removeProperty('padding');
                    document.body.classList.remove('has-sticky-header');
                }
                
                // Update header height after it becomes non-sticky (after transition)
                setTimeout(updateHeaderHeight, 300);
            }
        }
    }

    // Initialize when DOM is fully loaded
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initStickyHeader);
    } else {
        initStickyHeader();
    }
})(); 