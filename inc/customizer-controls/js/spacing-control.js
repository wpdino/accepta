/**
 * Accepta Spacing Control JavaScript
 * Handles the interactive functionality for Elementor-style spacing controls
 */

(function($) {
    'use strict';

    // Initialize spacing controls when customizer is ready
    wp.customize.bind('ready', function() {
        initializeSpacingControls();
    });

    function initializeSpacingControls() {
        $('.accepta-spacing-control').each(function() {
            var $control = $(this);
            var controlId = $control.data('control-id');
            
            initializeControl($control, controlId);
        });
    }

    function initializeControl($control, controlId) {
        var $hiddenInput = $control.find('input[type="hidden"]');
        var currentValue = {};
        
        // Parse initial value
        try {
            currentValue = JSON.parse($hiddenInput.val() || '{}');
        } catch (e) {
            currentValue = {};
        }

        // Initialize responsive tabs
        $control.find('.accepta-responsive-tab').on('click', function() {
            var device = $(this).data('device');
            switchDevice($control, device);
        });
        
        // Listen for customizer viewport changes to sync back
        setupViewportSync($control);

        // Initialize reset button
        $control.find('.accepta-spacing-reset-btn').on('click', function() {
            resetToDefaults($control, controlId);
        });

        // Initialize spacing inputs
        $control.find('.accepta-spacing-input').on('input change', function() {
            updateSpacingValue($control, controlId);
        });

        // Initialize unit selectors
        $control.find('.accepta-spacing-unit').on('change', function() {
            updateSpacingValue($control, controlId);
        });

        // Set initial states
        setInitialStates($control, currentValue);
    }

    function switchDevice($control, device) {
        // Update tab states
        $control.find('.accepta-responsive-tab').removeClass('active');
        $control.find('.accepta-responsive-tab[data-device="' + device + '"]').addClass('active');
        
        // Show/hide device panels
        $control.find('.accepta-spacing-device').hide();
        $control.find('.accepta-spacing-' + device).show();
        
        // Sync with customizer viewport preview
        syncCustomizerViewport(device);
    }

    function syncCustomizerViewport(device) {
        // Check if wp.customize.previewedDevice exists (WordPress 4.5+)
        if (wp.customize && wp.customize.previewedDevice) {
            var deviceMap = {
                'desktop': 'desktop',
                'tablet': 'tablet', 
                'mobile': 'mobile'
            };
            
            var wpDevice = deviceMap[device];
            if (wpDevice && wp.customize.previewedDevice.get() !== wpDevice) {
                wp.customize.previewedDevice.set(wpDevice);
                
                // Add visual feedback for sync
                $('.accepta-responsive-tab.active').addClass('synced');
                setTimeout(function() {
                    $('.accepta-responsive-tab.active').removeClass('synced');
                }, 1000);
                
            }
        } else {
            // Fallback: Try to trigger the responsive buttons directly
            var buttonMap = {
                'desktop': '.wp-full-overlay-footer .devices button[data-device="desktop"]',
                'tablet': '.wp-full-overlay-footer .devices button[data-device="tablet"]', 
                'mobile': '.wp-full-overlay-footer .devices button[data-device="mobile"]'
            };
            
            var buttonSelector = buttonMap[device];
            if (buttonSelector) {
                var $button = $(buttonSelector);
                if ($button.length && !$button.hasClass('active')) {
                    $button.trigger('click');
                    
                    // Add visual feedback for sync
                    $('.accepta-responsive-tab.active').addClass('synced');
                    setTimeout(function() {
                        $('.accepta-responsive-tab.active').removeClass('synced');
                    }, 1000);
                    
                }
            }
        }
    }

    function setupViewportSync($control) {
        // Listen for customizer viewport changes to sync spacing control tabs
        if (wp.customize && wp.customize.previewedDevice) {
            wp.customize.previewedDevice.bind(function(device) {
                // Update spacing control tab to match customizer viewport
                var currentActiveTab = $control.find('.accepta-responsive-tab.active').data('device');
                if (currentActiveTab !== device) {
                    $control.find('.accepta-responsive-tab').removeClass('active');
                    $control.find('.accepta-responsive-tab[data-device="' + device + '"]').addClass('active');
                    
                    // Show/hide device panels without triggering viewport sync again
                    $control.find('.accepta-spacing-device').hide();
                    $control.find('.accepta-spacing-' + device).show();
                    
                }
            });
        }
    }

    function updateSpacingValue($control, controlId) {
        var value = {};
        var devices = ['desktop', 'tablet', 'mobile'];
        
        devices.forEach(function(device) {
            var $deviceContainer = $control.find('.accepta-spacing-' + device);
            if ($deviceContainer.length) {
                value[device] = {};
                
                // Get spacing values
                $deviceContainer.find('.accepta-spacing-input').each(function() {
                    var side = $(this).data('side');
                    var inputValue = $(this).val();
                    if (inputValue !== '') {
                        value[device][side] = inputValue;
                    }
                });
                
                // Get unit
                var unit = $deviceContainer.find('.accepta-spacing-unit').val();
                if (unit) {
                    value[device].unit = unit;
                }
            }
        });

        // Update hidden input
        var $hiddenInput = $control.find('input[type="hidden"]');
        $hiddenInput.val(JSON.stringify(value)).trigger('change');
        
        // Trigger customizer change
        if (wp.customize && wp.customize.control(controlId)) {
            wp.customize.control(controlId).setting.set(JSON.stringify(value));
        }
    }

    function resetToDefaults($control, controlId) {
        // Confirm reset action
        var confirmMessage = (typeof acceptaSpacingL10n !== 'undefined' && acceptaSpacingL10n.confirmReset) 
            ? acceptaSpacingL10n.confirmReset 
            : 'Are you sure you want to reset all spacing values to defaults?';
            
        if (!confirm(confirmMessage)) {
            return;
        }

        // Clear all input values
        $control.find('.accepta-spacing-input').val('');
        
        // Reset units to default (px)
        $control.find('.accepta-spacing-unit').val('px');
        
        // Clear the hidden input value (this will set it to empty object)
        var $hiddenInput = $control.find('input[type="hidden"]');
        $hiddenInput.val('{}').trigger('change');
        
        // Trigger customizer change to clear the setting
        if (wp.customize && wp.customize.control(controlId)) {
            wp.customize.control(controlId).setting.set('{}');
        }
        
    }

    function setInitialStates($control, currentValue) {
        var devices = ['desktop', 'tablet', 'mobile'];
        
        devices.forEach(function(device) {
            var $deviceContainer = $control.find('.accepta-spacing-' + device);
            if ($deviceContainer.length && currentValue[device]) {
                var deviceValue = currentValue[device];
                
                // Set input values
                ['top', 'right', 'bottom', 'left'].forEach(function(side) {
                    if (deviceValue[side]) {
                        $deviceContainer.find('.accepta-spacing-input[data-side="' + side + '"]').val(deviceValue[side]);
                    }
                });
                
                // Set unit
                if (deviceValue.unit) {
                    $deviceContainer.find('.accepta-spacing-unit').val(deviceValue.unit);
                }
            }
        });
    }

    // Helper function to get default values
    function getDefaultValue() {
        return {
            desktop: { top: '', right: '', bottom: '', left: '', unit: 'px' },
            tablet: { top: '', right: '', bottom: '', left: '', unit: 'px' },
            mobile: { top: '', right: '', bottom: '', left: '', unit: 'px' }
        };
    }

    function debugSpacingState($control, action) {
        // Debug function removed
    }

    // Add control to customizer control types
    wp.customize.controlConstructor['accepta-spacing'] = wp.customize.Control.extend({
        ready: function() {
            var control = this;
            var $control = this.container.find('.accepta-spacing-control');
            
            initializeControl($control, control.id);
            debugSpacingState($control, 'Control initialized');
        }
    });

})(jQuery);
