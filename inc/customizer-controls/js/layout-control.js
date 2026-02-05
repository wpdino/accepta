/**
 * Accepta Layout Control JavaScript
 * Handles the interactive functionality for layout selection controls
 */

(function($) {
    'use strict';

    // Initialize layout controls when customizer is ready
    wp.customize.bind('ready', function() {
        initializeLayoutControls();
    });

    function initializeLayoutControls() {
        $('.accepta-layout-options').each(function() {
            var $container = $(this);
            var controlId = $container.data('control-id');
            
            initializeLayoutControl($container, controlId);
        });
    }

    function initializeLayoutControl($container, controlId) {
        // Initialize selected state on load
        function setSelectedState() {
            if (wp.customize && wp.customize.control(controlId)) {
                var currentValue = wp.customize.control(controlId).setting.get();
                if (currentValue) {
                    $container.find('.accepta-layout-option').removeClass('selected');
                    $container.find('.accepta-layout-option[data-layout="' + currentValue + '"]').addClass('selected');
                    $container.find('.accepta-layout-option[data-layout="' + currentValue + '"] input[type="radio"]').prop('checked', true);
                }
            }
        }
        
        // Set selected state immediately
        setSelectedState();
        
        // Also set it after a short delay to ensure customizer is fully ready
        setTimeout(setSelectedState, 100);
        
        // Listen for setting changes to update selected state
        if (wp.customize && wp.customize.control(controlId)) {
            wp.customize.control(controlId).setting.bind(function(newValue) {
                $container.find('.accepta-layout-option').removeClass('selected');
                $container.find('.accepta-layout-option[data-layout="' + newValue + '"]').addClass('selected');
                $container.find('.accepta-layout-option[data-layout="' + newValue + '"] input[type="radio"]').prop('checked', true);
            });
        }
        
        // Handle layout option clicks
        $container.find('.accepta-layout-option').on('click', function() {
            var $option = $(this);
            var layout = $option.data('layout');
            
            // Update visual selection
            $container.find('.accepta-layout-option').removeClass('selected');
            $option.addClass('selected');
            
            // Update radio button
            $option.find('input[type="radio"]').prop('checked', true);
            
            // Update hidden input and trigger customizer change
            var $hiddenInput = $container.closest('.accepta-layout-control-wrapper').find('input[type="hidden"]');
            $hiddenInput.val(layout).trigger('change');
            
            // Trigger customizer setting change
            if (wp.customize && wp.customize.control(controlId)) {
                wp.customize.control(controlId).setting.set(layout);
            }
        });

        // Handle keyboard navigation
        $container.find('.accepta-layout-option input[type="radio"]').on('keydown', function(e) {
            var $current = $(this).closest('.accepta-layout-option');
            var $options = $container.find('.accepta-layout-option');
            var currentIndex = $options.index($current);
            var $target = null;

            switch(e.which) {
                case 37: // Left arrow
                case 38: // Up arrow
                    e.preventDefault();
                    $target = $options.eq(currentIndex - 1);
                    if ($target.length === 0) {
                        $target = $options.last();
                    }
                    break;
                case 39: // Right arrow
                case 40: // Down arrow
                    e.preventDefault();
                    $target = $options.eq(currentIndex + 1);
                    if ($target.length === 0) {
                        $target = $options.first();
                    }
                    break;
                case 13: // Enter
                case 32: // Space
                    e.preventDefault();
                    $current.trigger('click');
                    return;
            }

            if ($target && $target.length) {
                $target.find('input[type="radio"]').focus();
            }
        });
    }

    // Add control to customizer control types
    wp.customize.controlConstructor['accepta-layout'] = wp.customize.Control.extend({
        ready: function() {
            var control = this;
            var $container = this.container.find('.accepta-layout-options');
            
            initializeLayoutControl($container, control.id);
        }
    });

})(jQuery);
