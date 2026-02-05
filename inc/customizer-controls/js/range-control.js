/**
 * Accepta Range Control JavaScript
 * Handles the interactive functionality for range controls with number inputs
 */

(function($) {
    'use strict';

    // Initialize range controls when customizer is ready
    wp.customize.bind('ready', function() {
        initializeRangeControls();
    });

    function initializeRangeControls() {
        $('.accepta-range-control-wrapper').each(function() {
            var $wrapper = $(this);
            var controlId = $wrapper.data('control-id');
            
            initializeRangeControl($wrapper, controlId);
        });
    }

    function initializeRangeControl($wrapper, controlId) {
        var $slider = $wrapper.find('.accepta-range-slider');
        var $number = $wrapper.find('.accepta-range-number');
        var $hidden = $wrapper.find('.accepta-range-hidden');

        // Sync slider to number input
        $slider.on('input change', function() {
            var value = $(this).val();
            $number.val(value);
            updateHiddenInput($wrapper, value, controlId);
        });

        // Sync number input to slider
        $number.on('input change', function() {
            var value = $(this).val();
            var min = parseInt($slider.attr('min'));
            var max = parseInt($slider.attr('max'));
            
            // Validate range
            if (value < min) {
                value = min;
                $(this).val(value);
            } else if (value > max) {
                value = max;
                $(this).val(value);
            }
            
            $slider.val(value);
            updateHiddenInput($wrapper, value, controlId);
        });

        // Handle keyboard navigation for slider
        $slider.on('keydown', function(e) {
            var currentValue = parseInt($(this).val());
            var step = parseInt($(this).attr('step')) || 1;
            var min = parseInt($(this).attr('min'));
            var max = parseInt($(this).attr('max'));
            var newValue = currentValue;

            switch(e.which) {
                case 37: // Left arrow
                case 40: // Down arrow
                    newValue = Math.max(min, currentValue - step);
                    break;
                case 38: // Up arrow
                case 39: // Right arrow
                    newValue = Math.min(max, currentValue + step);
                    break;
                case 36: // Home
                    newValue = min;
                    break;
                case 35: // End
                    newValue = max;
                    break;
                default:
                    return; // Exit if not a handled key
            }

            if (newValue !== currentValue) {
                e.preventDefault();
                $(this).val(newValue);
                $number.val(newValue);
                updateHiddenInput($wrapper, newValue, controlId);
            }
        });

        // Handle number input validation on blur
        $number.on('blur', function() {
            var value = parseInt($(this).val());
            var min = parseInt($slider.attr('min'));
            var max = parseInt($slider.attr('max'));
            
            if (isNaN(value)) {
                value = parseInt($slider.val()) || min;
            }
            
            // Ensure value is within range
            value = Math.max(min, Math.min(max, value));
            
            $(this).val(value);
            $slider.val(value);
            updateHiddenInput($wrapper, value, controlId);
        });
    }

    function updateHiddenInput($wrapper, value, controlId) {
        var $hidden = $wrapper.find('.accepta-range-hidden');
        $hidden.val(value).trigger('change');
        
        // Trigger customizer setting change
        if (wp.customize && wp.customize.control(controlId)) {
            wp.customize.control(controlId).setting.set(value);
        }
        
    }

    // Add control to customizer control types
    wp.customize.controlConstructor['accepta-range'] = wp.customize.Control.extend({
        ready: function() {
            var control = this;
            var $wrapper = this.container.find('.accepta-range-control-wrapper');
            
            initializeRangeControl($wrapper, control.id);
        }
    });

})(jQuery);
