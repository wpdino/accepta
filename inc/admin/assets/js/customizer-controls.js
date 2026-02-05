/**
 * Accepta Theme Customizer Controls JS
 *
 * Enhances the customizer controls
 */
( function( $ ) {
    'use strict';

    wp.customize.bind( 'ready', function() {
        // Toggle visibility of dependent controls
        function toggle_dependency( control_id, value, dependent_controls ) {
            wp.customize( control_id, function( setting ) {
                $.each( dependent_controls, function( index, dependent_control ) {
                    wp.customize.control( dependent_control, function( control ) {
                        var visibility = function() {
                            if ( value === setting.get() ) {
                                control.container.slideDown( 180 );
                            } else {
                                control.container.slideUp( 180 );
                            }
                        };
                        
                        // Set initial visibility
                        visibility();
                        
                        // Update visibility on setting change
                        setting.bind( visibility );
                    });
                });
            });
        }
        
        // Overlay header control visibility (only overlay text color)
        wp.customize( 'accepta_transparent_header', function( setting ) {
            wp.customize.control( 'accepta_transparent_header_text_color', function( control ) {
                var updateVisibility = function() {
                    if ( setting.get() ) {
                        control.container.slideDown( 200 );
                    } else {
                        control.container.slideUp( 200 );
                    }
                };
                
                // Set initial visibility
                updateVisibility();
                
                // Update visibility on setting change
                setting.bind( updateVisibility );
            });
        });
        
        // Add opacity control nested inside scrolled header background color control
        wp.customize.control( 'accepta_scrolled_header_bg', function( bgControl ) {
            if ( ! bgControl ) {
                return;
            }
            
            var $bgContainer = bgControl.container;
            
            // Get the current opacity value from the setting
            var getOpacityValue = function() {
                var value = wp.customize( 'accepta_scrolled_header_bg_opacity' ).get();
                // Ensure value is a number between 0 and 1
                value = parseFloat( value );
                if ( isNaN( value ) || value < 0 ) {
                    value = 1;
                } else if ( value > 1 ) {
                    value = 1;
                }
                return value;
            };
            
            var opacityValue = getOpacityValue();
            
            // Check if opacity control HTML already exists
            if ( $bgContainer.find( '.accepta-scrolled-header-opacity-control' ).length === 0 ) {
                // Create opacity control HTML (similar to overlay opacity in background control)
                var opacityHtml = '<div class="accepta-scrolled-header-opacity-control" style="margin-top: 15px; padding-top: 15px; border-top: 1px solid #ddd;">' +
                    '<label>' +
                    '<span class="customize-control-title">' + 'Background Opacity' + '</span>' +
                    '<div class="accepta-opacity-control" style="display: flex; align-items: center; gap: 10px; margin-top: 8px;">' +
                    '<input type="range" class="accepta-scrolled-header-opacity-slider" value="' + opacityValue + '" min="0" max="1" step="0.1" style="flex: 1; height: 6px; margin: 0; -webkit-appearance: none; appearance: none; background: #ddd; border-radius: 3px; outline: none;" />' +
                    '<span class="accepta-opacity-value" style="min-width: 40px; text-align: right;">' + opacityValue + '</span>' +
                    '</div>' +
                    '</label>' +
                    '</div>';
                
                // Append to color control container
                $bgContainer.find( '.wp-picker-container' ).after( opacityHtml );
            }
            
            // Initialize opacity slider
            var $opacitySlider = $bgContainer.find( '.accepta-scrolled-header-opacity-slider' );
            var $opacityValue = $bgContainer.find( '.accepta-opacity-value' );
            
            // Set initial value from setting
            var initialValue = getOpacityValue();
            $opacitySlider.val( initialValue );
            $opacityValue.text( initialValue );
            
            $opacitySlider.on( 'input change', function() {
                var opacity = parseFloat( $( this ).val() );
                if ( isNaN( opacity ) ) {
                    opacity = 1;
                }
                $opacityValue.text( opacity );
                // Save the value as a string to match WordPress customizer format
                wp.customize( 'accepta_scrolled_header_bg_opacity' ).set( String( opacity ) );
            } );
            
            // Update opacity value when setting changes (from external sources)
            wp.customize( 'accepta_scrolled_header_bg_opacity', function( opacitySetting ) {
                opacitySetting.bind( function( newValue ) {
                    var opacity = parseFloat( newValue );
                    if ( isNaN( opacity ) ) {
                        opacity = 1;
                    }
                    $opacitySlider.val( opacity );
                    $opacityValue.text( opacity );
                } );
            } );
        } );
        
        // Sticky header control visibility (scrolled header options)
        wp.customize( 'accepta_sticky_header', function( setting ) {
            var scrolledControls = [
                'accepta_scrolled_header_bg',
                'accepta_scrolled_header_text_color'
            ];
            
            $.each( scrolledControls, function( index, controlId ) {
                wp.customize.control( controlId, function( control ) {
                    if ( ! control ) {
                        console.warn( '[Customizer] Control not found: ' + controlId );
                        return;
                    }
                    
                    var updateVisibility = function() {
                        if ( setting.get() ) {
                            control.container.slideDown( 200 );
                            control.container.css( 'display', '' ); // Ensure it's visible
                        } else {
                            control.container.slideUp( 200 );
                        }
                    };
                    
                    // Set initial visibility immediately
                    if ( setting.get() ) {
                        control.container.show();
                    } else {
                        control.container.hide();
                    }
                    
                    // Update visibility on setting change
                    setting.bind( updateVisibility );
                });
            });
        });
        
        // Hero height control visibility
        wp.customize( 'accepta_hero_height', function( setting ) {
            wp.customize.control( 'accepta_hero_min_height', function( control ) {
                var visibility = function() {
                    var height = setting.get();
                    if ( height === 'min-height' || height === 'custom' ) {
                        control.container.slideDown( 180 );
                    } else {
                        control.container.slideUp( 180 );
                    }
                };
                
                // Set initial visibility
                visibility();
                
                // Update visibility on setting change
                setting.bind( visibility );
            });
        });
        
        // Example toggle: if accepta_header_style is 'style-2', show accepta_header_style_2_options
        toggle_dependency( 'accepta_header_style', 'style-2', ['accepta_header_style_2_options'] );
        
        // Header Social Icons Visibility Toggle
        wp.customize( 'accepta_display_header_social_icons', function( setting ) {
            wp.customize.control( 'accepta_header_social_media', function( control ) {
                var updateVisibility = function() {
                    if ( setting.get() ) {
                        control.container.slideDown( 200 );
                    } else {
                        control.container.slideUp( 200 );
                    }
                };
                
                // Set initial visibility
                updateVisibility();
                
                // Update visibility on setting change
                setting.bind( updateVisibility );
            });
        });
    });

} )( jQuery ); 