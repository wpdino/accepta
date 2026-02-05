/**
 * Accepta Typography Control JavaScript
 * Handles typography controls with font family styling
 */

(function($) {
    'use strict';

    // Initialize typography controls when customizer is ready
    wp.customize.bind('ready', function() {
        initializeTypographyControls();
    });

    function initializeTypographyControls() {
        $('.accepta-typography-control-wrapper').each(function() {
            var $wrapper = $(this);
            var controlId = $wrapper.data('control-id');
            
            initializeTypographyControl($wrapper, controlId);
        });
    }

    function initializeTypographyControl($wrapper, controlId) {
        var $hiddenInput = $wrapper.find('.accepta-typography-hidden');
        
        // Function to load and populate values
        function loadSavedValues() {
            var savedValue = '';
            var hasSavedData = false;
            
            // Try to get value from customizer setting first (most reliable)
            if (wp.customize && wp.customize.control(controlId)) {
                try {
                    savedValue = wp.customize.control(controlId).setting.get();
                } catch (e) {
                    // Fallback to hidden input
                    savedValue = $hiddenInput.val();
                }
            } else {
                // Fallback to hidden input
                savedValue = $hiddenInput.val();
            }
            
            if (savedValue) {
                try {
                    var savedData = JSON.parse(savedValue);
                    hasSavedData = true;
                    
                    // Populate all inputs with saved values
                    $wrapper.find('.accepta-typography-select, .accepta-typography-input').each(function() {
                        var $field = $(this);
                        var fieldName = $field.data('field');
                        if (fieldName && savedData.hasOwnProperty(fieldName)) {
                            var fieldValue = savedData[fieldName];
                            // Set the value if it exists (including empty strings for responsive font sizes)
                            if (fieldValue !== null && fieldValue !== undefined) {
                                $field.val(fieldValue);
                            }
                        }
                    });
                } catch (e) {
                    // Silent error handling
                }
            }
            
            return hasSavedData;
        }
        
        // Try to load immediately
        var hasSavedData = loadSavedValues();
        
        // Also try after a delay in case WordPress hasn't populated the setting yet
        if (!hasSavedData) {
            setTimeout(function() {
                loadSavedValues();
            }, 300);
        }
        
        // If no saved data exists, capture current input values (which have PHP defaults) and save them
        // Use a delay to ensure all inputs are rendered with their PHP default values
        setTimeout(function() {
            // Check again if we have saved data now
            var currentSavedValue = '';
            if (wp.customize && wp.customize.control(controlId)) {
                try {
                    currentSavedValue = wp.customize.control(controlId).setting.get();
                } catch (e) {
                    currentSavedValue = $hiddenInput.val();
                }
            } else {
                currentSavedValue = $hiddenInput.val();
            }
            
            // Only save defaults if we still don't have saved data
            if (!currentSavedValue || currentSavedValue === '{}' || currentSavedValue === '') {
                // Capture all current input values (which include PHP defaults)
                var defaultValues = {};
                $wrapper.find('.accepta-typography-select, .accepta-typography-input').each(function() {
                    var $field = $(this);
                    var fieldName = $field.data('field');
                    var fieldValue = $field.val();
                    // Capture all non-empty values, including responsive font sizes
                    if (fieldName && fieldValue !== '' && fieldValue !== null && fieldValue !== undefined) {
                        defaultValues[fieldName] = fieldValue;
                    }
                });
                
                // If we found default values, save them
                if (Object.keys(defaultValues).length > 0) {
                    $hiddenInput.val(JSON.stringify(defaultValues));
                    if (wp.customize && wp.customize.control(controlId)) {
                        wp.customize.control(controlId).setting.set(JSON.stringify(defaultValues));
                    }
                }
            }
        }, 500);

        // Handle field changes
        $wrapper.find('.accepta-typography-select, .accepta-typography-input').on('change input', function() {
            updateTypographyValue($wrapper, controlId);
        });

        // Handle responsive tabs
        $wrapper.find('.accepta-responsive-tab').on('click', function(e) {
            e.preventDefault();
            var $tab = $(this);
            var device = $tab.data('device');
            var $field = $tab.closest('.accepta-responsive-field');
            
            // Update tab states
            $field.find('.accepta-responsive-tab').removeClass('active');
            $tab.addClass('active');
            
            // Update input visibility
            $field.find('.accepta-responsive-input').removeClass('active');
            $field.find('.accepta-responsive-input[data-device="' + device + '"]').addClass('active');
            
            // Trigger customizer viewport change if available
            if (typeof wp !== 'undefined' && wp.customize && wp.customize.previewedDevice) {
                wp.customize.previewedDevice.set(device);
            }
        });

        // Style font family select options
        styleFontFamilySelect($wrapper);
        
        // Listen for customizer setting changes (when value is updated from outside)
        if (wp.customize && wp.customize.control(controlId)) {
            var setting = wp.customize.control(controlId).setting;
            
            // Bind to setting changes
            setting.bind(function(newValue) {
                if (newValue) {
                    try {
                        var newData = JSON.parse(newValue);
                        
                        // Update all inputs with new values
                        $wrapper.find('.accepta-typography-select, .accepta-typography-input').each(function() {
                            var $field = $(this);
                            var fieldName = $field.data('field');
                            if (fieldName && newData[fieldName] !== undefined && newData[fieldName] !== null) {
                                $field.val(newData[fieldName]);
                            }
                        });
                    } catch (e) {
                        // Silent error handling
                    }
                }
            });
            
            // Also trigger immediately to load initial value
            setTimeout(function() {
                try {
                    var initialValue = setting.get();
                    if (initialValue) {
                        var initialData = JSON.parse(initialValue);
                        
                        // Update all inputs with initial values
                        $wrapper.find('.accepta-typography-select, .accepta-typography-input').each(function() {
                            var $field = $(this);
                            var fieldName = $field.data('field');
                            if (fieldName && initialData[fieldName] !== undefined && initialData[fieldName] !== null) {
                                $field.val(initialData[fieldName]);
                            }
                        });
                    }
                } catch (e) {
                    // Silent error handling
                }
            }, 100);
        }
    }

    function updateTypographyValue($wrapper, controlId) {
        var $hiddenInput = $wrapper.find('.accepta-typography-hidden');
        
        // Start with existing saved values to preserve data
        var existingValue = {};
        try {
            var existingData = $hiddenInput.val();
            if (existingData) {
                existingValue = JSON.parse(existingData);
            }
        } catch (e) {
            // If parsing fails, start fresh
            existingValue = {};
        }
        
        // Merge with current input values
        var value = $.extend({}, existingValue);
        
        // Get desktop font size for fallback
        var desktopFontSize = '';
        $wrapper.find('.accepta-typography-input[data-field="font_size_desktop"]').each(function() {
            desktopFontSize = $(this).val();
        });
        
        // Collect all typography field values (including hidden responsive inputs)
        // jQuery finds all elements in DOM regardless of visibility
        $wrapper.find('.accepta-typography-select, .accepta-typography-input').each(function() {
            var $field = $(this);
            var fieldName = $field.data('field');
            var fieldValue = $field.val();
            
            if (fieldName) {
                if (fieldName.indexOf('font_size_') === 0) {
                    // For responsive font size fields
                    if (fieldValue !== '' && fieldValue !== null && fieldValue !== undefined) {
                        // Input has a value (including defaults from PHP), always save it
                        value[fieldName] = fieldValue;
                    } else if (existingValue[fieldName] !== undefined && existingValue[fieldName] !== '') {
                        // Input is empty but we have a previously saved value, preserve it
                        value[fieldName] = existingValue[fieldName];
                    }
                    // If input is empty and no saved value exists, don't include it
                    // This allows the field to use PHP defaults on next load
                } else if (fieldValue !== '') {
                    // For other fields, only include if not empty
                    value[fieldName] = fieldValue;
                } else if (existingValue[fieldName] !== undefined) {
                    // Preserve existing value if current is empty
                    value[fieldName] = existingValue[fieldName];
                }
            }
        });
        
        $hiddenInput.val(JSON.stringify(value)).trigger('change');
        
        // Trigger customizer setting change
        if (wp.customize && wp.customize.control(controlId)) {
            wp.customize.control(controlId).setting.set(JSON.stringify(value));
        }
    }

    function styleFontFamilySelect($wrapper) {
        var $select = $wrapper.find('.accepta-font-family-select');
        
        if ($select.length === 0) {
            return;
        }

        // Load Google Fonts for preview
        loadGoogleFontsForSelect();
        
        // Apply font styles to options after fonts are loaded
        setTimeout(function() {
            applyFontStylesToOptions($select);
        }, 1000);

        // Update font style when selection changes
        $select.on('change', function() {
            var selectedFont = $(this).val();
            if (selectedFont && selectedFont !== '') {
                loadGoogleFont(selectedFont);
            }
        });
    }

    function loadGoogleFontsForSelect() {
        // Load common Google Fonts for the select dropdown
        var commonFonts = [
            'Open Sans', 'Roboto', 'Lato', 'Montserrat', 'Poppins', 
            'Source Sans Pro', 'Raleway', 'Ubuntu', 'Nunito', 'Inter',
            'Work Sans', 'Rubik', 'DM Sans', 'Manrope', 'Fira Sans',
            'Playfair Display', 'Merriweather', 'PT Serif', 'Roboto Slab',
            'EB Garamond', 'Source Code Pro', 'Roboto Mono'
        ];
        
        // Load all common fonts at once
        var fontUrl = 'https://fonts.googleapis.com/css2?family=' + 
                     commonFonts.map(function(font) {
                         return encodeURIComponent(font) + ':wght@300;400;500;600;700';
                     }).join('&family=') + '&display=swap';
        
        if ($('#accepta-select-fonts').length === 0) {
            $('<link>')
                .attr('id', 'accepta-select-fonts')
                .attr('rel', 'stylesheet')
                .attr('href', fontUrl)
                .appendTo('head');
        }
    }

    function applyFontStylesToOptions($select) {
        $select.find('option').each(function() {
            var $option = $(this);
            var fontFamily = $option.data('font-family');
            
            if (fontFamily && fontFamily !== '') {
                // Apply font family to option if available
                $option.css('font-family', '"' + fontFamily + '", sans-serif');
            }
        });
    }

    function loadGoogleFont(fontName) {
        if (!fontName) return;
        
        // Extract only the first font name (before any comma) and clean it
        var cleanFontName = fontName.split(',')[0].trim().replace(/['"]/g, '');
        
        // Check if it's a system font - if so, don't try to load from Google
        var systemFonts = [
            'Arial', 'Helvetica', 'Times New Roman', 'Times', 'Courier New', 'Courier',
            'Verdana', 'Georgia', 'Palatino', 'Garamond', 'Bookman', 'Comic Sans MS',
            'Trebuchet MS', 'Arial Black', 'Impact', 'Lucida Sans Unicode', 'Tahoma',
            'Lucida Console', 'Monaco', 'Brush Script MT', 'Copperplate', 'Papyrus'
        ];
        
        if (systemFonts.indexOf(cleanFontName) !== -1) {
            return;
        }
        
        // Check if font is already loaded by backend (accepta-google-fonts)
        var backendFonts = $('#accepta-google-fonts-css');
        if (backendFonts.length > 0) {
            var backendUrl = backendFonts.attr('href');
            if (backendUrl && backendUrl.indexOf(encodeURIComponent(cleanFontName)) !== -1) {
                return;
            }
        }
        
        // Create a safe ID by removing all non-alphanumeric characters except hyphens
        var fontId = 'accepta-google-font-' + cleanFontName.replace(/[^a-zA-Z0-9\s]/g, '').replace(/\s+/g, '-').toLowerCase();
        
        if ($('#' + fontId).length === 0) {
            var fontUrl = 'https://fonts.googleapis.com/css2?family=' + 
                         encodeURIComponent(cleanFontName) + ':wght@300;400;500;600;700&display=swap';
            
            $('<link>')
                .attr('id', fontId)
                .attr('rel', 'stylesheet')
                .attr('href', fontUrl)
                .appendTo('head');
        }
    }

    // Add control to customizer control types
    wp.customize.controlConstructor['accepta-typography'] = wp.customize.Control.extend({
        ready: function() {
            var control = this;
            var $wrapper = this.container.find('.accepta-typography-control-wrapper');
            
            initializeTypographyControl($wrapper, control.id);
        }
    });

})(jQuery);