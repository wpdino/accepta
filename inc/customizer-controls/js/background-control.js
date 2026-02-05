/**
 * Accepta Background Control JavaScript
 */


(function($) {
	'use strict';


	wp.customize.bind('ready', function() {
		initializeBackgroundControls();
	});
	
	// Also initialize when controls are added dynamically
	wp.customize.control.bind('add', function(control) {
		if (control.params && (control.params.type === 'accepta-background' || control.params.type === 'accepta-hero-background')) {
			setTimeout(function() {
				initializeBackgroundControls();
			}, 100);
		}
	});

	function initializeBackgroundControls() {
		var $wrappers = $('.accepta-background-control-wrapper');
		
		// Initialize all existing controls
		$wrappers.each(function(index) {
			var $wrapper = $(this);
			
			if (!$wrapper.data('initialized')) {
				var controlId = $wrapper.data('control-id');
				
				if (!controlId) {
					var $hiddenInput = $wrapper.find('.accepta-background-hidden');
					
					var inputId = $hiddenInput.attr('id');
					if (inputId) {
						controlId = inputId.replace('_customize-input-', '').replace(/-/g, '_');
					} else {
						var settingLink = $hiddenInput.attr('data-customize-setting-link');
						if (settingLink) {
							controlId = settingLink;
						}
					}
				}
				
				if (controlId) {
					initializeSingleBackgroundControl($wrapper, controlId);
				} else {
					// Try to get control ID from wrapper data attribute
					var wrapperControlId = $wrapper.data('control-id');
					if (wrapperControlId) {
						initializeSingleBackgroundControl($wrapper, wrapperControlId);
					}
				}
			} else {
			}
		});
	}

	function initializeSingleBackgroundControl($wrapper, controlId) {
		
		if (!$wrapper.length) {
			return;
		}
		
		if ($wrapper.data('initialized')) {
			return; // Already initialized
		}
		
		$wrapper.data('initialized', true);
		
		// Get control ID if not provided
		if (!controlId) {
			var $hiddenInput = $wrapper.find('.accepta-background-hidden');
			var inputId = $hiddenInput.attr('id');
			if (inputId) {
				controlId = inputId.replace('_customize-input-', '').replace(/-/g, '_');
			} else {
				var settingLink = $hiddenInput.attr('data-customize-setting-link');
				if (settingLink) {
					controlId = settingLink;
				}
			}
		}
		
		
		// Initialize color pickers
		// Regular HEX color pickers (solid color, gradient colors)
		var $hexColorInputs = $wrapper.find('.accepta-background-color');
		var $gradientColorInputs = $wrapper.find('.accepta-background-gradient-start, .accepta-background-gradient-end');
		
		if ($hexColorInputs.length > 0) {
			$hexColorInputs.each(function(index) {
				var $input = $(this);
				
				if (!$input.data('wpColorPicker')) {
					$input.wpColorPicker({
						change: function(event, ui) {
							updateBackgroundValue($wrapper, controlId);
						}
					});
				} else {
				}
			});
		}
		
		// Initialize gradient color pickers
		if ($gradientColorInputs.length > 0) {
			$gradientColorInputs.each(function(index) {
				var $input = $(this);
				
				if (!$input.data('wpColorPicker')) {
					$input.wpColorPicker({
						change: function(event, ui) {
							updateBackgroundValue($wrapper, controlId);
						},
						clear: function() {
							$input.val('#000000');
							updateBackgroundValue($wrapper, controlId);
						}
					});
				} else {
				}
			});
		}
		
		// Initialize color picker for overlay color
		var $overlayColorInput = $wrapper.find('.accepta-background-overlay-color');
		if ($overlayColorInput.length > 0) {
			
			if (!$overlayColorInput.data('wpColorPicker')) {
				$overlayColorInput.wpColorPicker({
					change: function(event, ui) {
						updateBackgroundValue($wrapper, controlId);
					}
				});
			} else {
			}
		}
		
		// Opacity slider
		$wrapper.on('input change', '.accepta-background-overlay-opacity', function() {
			var opacity = $(this).val();
			$wrapper.find('.accepta-opacity-value').text(opacity);
			updateBackgroundValue($wrapper, controlId);
		});

		// Background type selector - make labels clickable and handle changes
		var $typeLabels = $wrapper.find('.accepta-background-type-selector label');
		
		$wrapper.on('click', '.accepta-background-type-selector label', function(e) {
			e.preventDefault();
			e.stopPropagation();
			var $label = $(this);
			var $radio = $label.find('input[type="radio"]');
			
			if ($radio.length && !$radio.prop('checked')) {
				var type = $radio.val();
				// Update radio button
				$radio.prop('checked', true).trigger('change');
			} else if ($radio.length) {
			}
		});
		
		$wrapper.on('change', 'input[name$="_type"]', function() {
			var type = $(this).val();
			
			$wrapper.find('.accepta-background-option').hide();
			var $targetOption = $wrapper.find('.accepta-background-' + type);
			$targetOption.show();
			
			// Show/hide overlay options - show for both image and video types
			// Note: overlay state is preserved, but only applies when image or video tab is active
			if (type === 'image' || type === 'video') {
				$wrapper.find('.accepta-background-overlay').show();
			} else {
				$wrapper.find('.accepta-background-overlay').hide();
			}
			
			// Update label styling
			$wrapper.find('.accepta-background-type-selector label').removeClass('active');
			$(this).closest('label').addClass('active');
			
			// Update the background value when type changes (important for video tab)
			updateBackgroundValue($wrapper, controlId);
			
			// Show/hide gradient angle for linear gradients and load gradient values
			if (type === 'gradient') {
				var gradientType = $wrapper.find('.accepta-background-gradient-type').val();
				if (gradientType === 'linear') {
					$wrapper.find('.accepta-gradient-angle').show();
				} else {
					$wrapper.find('.accepta-gradient-angle').hide();
				}
				
				// Ensure gradient values are loaded from saved data if form fields are empty
				setTimeout(function() {
					if (wp.customize && wp.customize.control(controlId)) {
						var savedValue = wp.customize.control(controlId).setting.get();
						if (savedValue) {
							try {
								var data = JSON.parse(savedValue);
								if (data.type === 'gradient') {
									// Load gradient values if form fields are empty
									if (!$wrapper.find('.accepta-background-gradient-start').val() && data.gradient_start) {
										$wrapper.find('.accepta-background-gradient-start').val(data.gradient_start);
										if ($wrapper.find('.accepta-background-gradient-start').data('wpColorPicker')) {
											$wrapper.find('.accepta-background-gradient-start').wpColorPicker('color', data.gradient_start);
										}
									}
									if (!$wrapper.find('.accepta-background-gradient-end').val() && data.gradient_end) {
										$wrapper.find('.accepta-background-gradient-end').val(data.gradient_end);
										if ($wrapper.find('.accepta-background-gradient-end').data('wpColorPicker')) {
											$wrapper.find('.accepta-background-gradient-end').wpColorPicker('color', data.gradient_end);
										}
									}
									if (!$wrapper.find('.accepta-background-gradient-angle').val() && data.gradient_angle) {
										$wrapper.find('.accepta-background-gradient-angle').val(data.gradient_angle);
									}
									if (!$wrapper.find('.accepta-background-gradient-type').val() && data.gradient_type) {
										$wrapper.find('.accepta-background-gradient-type').val(data.gradient_type).trigger('change');
									}
								}
							} catch (e) {
								// Silent error
							}
						}
					}
				}, 100);
			}
			
			updateBackgroundValue($wrapper, controlId);
		});
		
		// Set initial active state
		var $checkedRadio = $wrapper.find('input[name$="_type"]:checked');
		
		if ($checkedRadio.length) {
			var initialType = $checkedRadio.val();
			$checkedRadio.closest('label').addClass('active');
			// Trigger initial show/hide
			$wrapper.find('.accepta-background-option').hide();
			var $initialOption = $wrapper.find('.accepta-background-' + initialType);
			$initialOption.show();
			
			// Show/hide overlay based on initial type
			if (initialType === 'image') {
				$wrapper.find('.accepta-background-overlay').show();
			} else {
				$wrapper.find('.accepta-background-overlay').hide();
			}
		} else {
			// If none checked, check the first one
			var $firstRadio = $wrapper.find('input[name$="_type"]').first();
			if ($firstRadio.length) {
				var firstType = $firstRadio.val();
				$firstRadio.prop('checked', true).closest('label').addClass('active');
				$wrapper.find('.accepta-background-option').hide();
				$wrapper.find('.accepta-background-' + firstType).show();
				
				// Show/hide overlay based on first type
				if (firstType === 'image') {
					$wrapper.find('.accepta-background-overlay').show();
				} else {
					$wrapper.find('.accepta-background-overlay').hide();
				}
				
				updateBackgroundValue($wrapper, controlId);
			} else {
			}
		}
		
		// Initialize opacity value display
		var initialOpacity = $wrapper.find('.accepta-background-overlay-opacity').val();
		$wrapper.find('.accepta-opacity-value').text(initialOpacity || '0.5');

		// Gradient type change
		$wrapper.on('change', '.accepta-background-gradient-type', function() {
			var gradientType = $(this).val();
			if (gradientType === 'linear') {
				$wrapper.find('.accepta-gradient-angle').show();
			} else {
				$wrapper.find('.accepta-gradient-angle').hide();
			}
			updateBackgroundValue($wrapper, controlId);
		});
		
		// Gradient color pickers change (already handled by wpColorPicker change event, but adding as backup)
		$wrapper.on('change', '.accepta-background-gradient-start, .accepta-background-gradient-end', function() {
			updateBackgroundValue($wrapper, controlId);
		});

		// All other field changes
		$wrapper.on('change input', '.accepta-background-gradient-angle, .accepta-background-image-size, .accepta-background-image-repeat, .accepta-background-image-position, .accepta-background-image-attachment', function() {
			updateBackgroundValue($wrapper, controlId);
		});

		// Video field changes
		$wrapper.on('change', '.accepta-background-video-type', function() {
			var videoType = $(this).val();
			if (videoType === 'mp4') {
				$wrapper.find('.accepta-video-url-option').hide();
				$wrapper.find('.accepta-video-mp4-option').show();
			} else {
				$wrapper.find('.accepta-video-url-option').show();
				$wrapper.find('.accepta-video-mp4-option').hide();
			}
			updateBackgroundValue($wrapper, controlId);
		});

		$wrapper.on('change input', '.accepta-background-video-url, .accepta-background-video-mp4, .accepta-background-video-autoplay, .accepta-background-video-loop, .accepta-background-video-muted, .accepta-background-video-controls', function() {
			// Ensure video tab is selected when video fields are changed
			var $videoTypeRadio = $wrapper.find('input[name$="_type"][value="video"]');
			if ($videoTypeRadio.length && !$videoTypeRadio.is(':checked')) {
				$videoTypeRadio.prop('checked', true).trigger('change');
			}
			updateBackgroundValue($wrapper, controlId);
		});

		// Video upload
		var videoFrame;
		$wrapper.on('click', '.accepta-background-video-button', function(e) {
			e.preventDefault();
			
			if (videoFrame) {
				videoFrame.open();
				return;
			}

			videoFrame = wp.media({
				title: 'Select MP4 Video',
				button: {
					text: 'Use this video'
				},
				library: {
					type: 'video'
				},
				multiple: false
			});

			videoFrame.on('select', function() {
				var attachment = videoFrame.state().get('selection').first().toJSON();
				$wrapper.find('.accepta-background-video-mp4').val(attachment.url);
				$wrapper.find('.accepta-background-video-remove').show();
				updateBackgroundValue($wrapper, controlId);
			});

			videoFrame.open();
		});

		// Video remove
		$wrapper.on('click', '.accepta-background-video-remove', function(e) {
			e.preventDefault();
			$wrapper.find('.accepta-background-video-mp4').val('');
			$(this).hide();
			updateBackgroundValue($wrapper, controlId);
		});

		// Image upload
		var frame;
		$wrapper.on('click', '.accepta-background-image-button', function(e) {
			e.preventDefault();
			
			if (frame) {
				frame.open();
				return;
			}

			frame = wp.media({
				title: 'Select Background Image',
				button: {
					text: 'Use this image'
				},
				multiple: false
			});

			frame.on('select', function() {
				var attachment = frame.state().get('selection').first().toJSON();
				$wrapper.find('.accepta-background-image-url').val(attachment.url);
				$wrapper.find('.accepta-background-image-preview').html('<img src="' + attachment.url + '" alt="" />').show();
				$wrapper.find('.accepta-background-image-remove').show();
				updateBackgroundValue($wrapper, controlId);
			});

			frame.open();
		});

		// Image remove
		$wrapper.on('click', '.accepta-background-image-remove', function(e) {
			e.preventDefault();
			$wrapper.find('.accepta-background-image-url').val('');
			$wrapper.find('.accepta-background-image-preview').hide();
			$(this).hide();
			updateBackgroundValue($wrapper, controlId);
		});

		// Overlay toggle - make label clickable and handle changes
		var $overlayToggle = $wrapper.find('.accepta-overlay-toggle');
		
		$wrapper.on('click', '.accepta-overlay-toggle', function(e) {
			e.preventDefault();
			e.stopPropagation();
			var $checkbox = $(this).find('.accepta-background-overlay-enabled');
			if ($checkbox.length) {
				var newState = !$checkbox.prop('checked');
				$checkbox.prop('checked', newState).trigger('change');
			}
		});
		
		$wrapper.on('change', '.accepta-background-overlay-enabled', function() {
			var enabled = $(this).is(':checked');
			var $overlayOptions = $wrapper.find('.accepta-overlay-options');
			
			if (enabled) {
				$overlayOptions.show();
			} else {
				$overlayOptions.hide();
			}
			updateBackgroundValue($wrapper, controlId);
		});
		
		// Set initial overlay state
		var $overlayCheckbox = $wrapper.find('.accepta-background-overlay-enabled');
		if ($overlayCheckbox.length) {
			var isChecked = $overlayCheckbox.is(':checked');
			if (isChecked) {
				$wrapper.find('.accepta-overlay-options').show();
			} else {
				$wrapper.find('.accepta-overlay-options').hide();
			}
		}

		// Overlay opacity slider
		$wrapper.on('input change', '.accepta-background-overlay-opacity', function() {
			var opacity = $(this).val();
			$wrapper.find('.accepta-opacity-value').text(opacity);
			updateBackgroundValue($wrapper, controlId);
		});

		// Reset button
		$wrapper.on('click', '.accepta-background-reset', function(e) {
			e.preventDefault();
			e.stopPropagation();
			
			if (!confirm('Are you sure you want to reset all background settings to defaults?')) {
				return;
			}
			
			// Get default values from data attribute (jQuery .data() auto-parses JSON)
			var defaultValuesAttr = $wrapper.attr('data-default-values');
			
			var defaultValues = $wrapper.data('default-values');
			
			// If .data() didn't parse it (returns string), parse it manually
			if (typeof defaultValues === 'string') {
				try {
					// Decode HTML entities first if needed
					var decoded = $('<div>').html(defaultValues).text();
					defaultValues = JSON.parse(decoded);
				} catch (e) {
					// Try parsing the raw attribute
					try {
						defaultValues = JSON.parse(defaultValuesAttr);
					} catch (e2) {
						return;
					}
				}
			}
			
			if (defaultValues && typeof defaultValues === 'object') {
				resetToDefaults($wrapper, defaultValues, controlId);
			} else {
			}
		});

		// Load saved values
		loadSavedValues($wrapper, controlId);
	}

	function resetToDefaults($wrapper, defaults, controlId) {
		
		// First, clear the image completely before switching types
		$wrapper.find('.accepta-background-image-url').val('');
		$wrapper.find('.accepta-background-image-preview').hide().html('');
		$wrapper.find('.accepta-background-image-remove').hide();
		
		// Reset background type first
		$wrapper.find('input[name$="_type"][value="' + defaults.type + '"]').prop('checked', true).trigger('change');
		
		// Wait a bit for the change event to process
		setTimeout(function() {
			// Reset solid color
			if (defaults.color) {
				$wrapper.find('.accepta-background-color').val(defaults.color);
				if ($wrapper.find('.accepta-background-color').data('wpColorPicker')) {
					$wrapper.find('.accepta-background-color').wpColorPicker('color', defaults.color);
				}
			}
			
			// Reset gradient
			if (defaults.gradient_type) {
				$wrapper.find('.accepta-background-gradient-type').val(defaults.gradient_type).trigger('change');
			}
			if (defaults.gradient_angle) {
				$wrapper.find('.accepta-background-gradient-angle').val(defaults.gradient_angle);
			}
			if (defaults.gradient_start) {
				$wrapper.find('.accepta-background-gradient-start').val(defaults.gradient_start);
				if ($wrapper.find('.accepta-background-gradient-start').data('wpColorPicker')) {
					$wrapper.find('.accepta-background-gradient-start').wpColorPicker('color', defaults.gradient_start);
				}
			}
			if (defaults.gradient_end) {
				$wrapper.find('.accepta-background-gradient-end').val(defaults.gradient_end);
				if ($wrapper.find('.accepta-background-gradient-end').data('wpColorPicker')) {
					$wrapper.find('.accepta-background-gradient-end').wpColorPicker('color', defaults.gradient_end);
				}
			}
			
			// Reset image - always clear it if default is empty
			if (defaults.image && defaults.image !== '') {
				$wrapper.find('.accepta-background-image-url').val(defaults.image);
				$wrapper.find('.accepta-background-image-preview').html('<img src="' + defaults.image + '" alt="" />').show();
				$wrapper.find('.accepta-background-image-remove').show();
			} else {
				// Clear image completely
				$wrapper.find('.accepta-background-image-url').val('');
				$wrapper.find('.accepta-background-image-preview').hide().html('');
				$wrapper.find('.accepta-background-image-remove').hide();
			}
			if (defaults.size) {
				$wrapper.find('.accepta-background-image-size').val(defaults.size);
			}
			if (defaults.repeat) {
				$wrapper.find('.accepta-background-image-repeat').val(defaults.repeat);
			}
			if (defaults.position) {
				$wrapper.find('.accepta-background-image-position').val(defaults.position);
			}
			if (defaults.attachment) {
				$wrapper.find('.accepta-background-image-attachment').val(defaults.attachment);
			}
			
			// Reset video options (if control supports video)
			if (defaults.video_type !== undefined) {
				$wrapper.find('.accepta-background-video-type').val(defaults.video_type || 'youtube').trigger('change');
				$wrapper.find('.accepta-background-video-url').val(defaults.video_url || '');
				$wrapper.find('.accepta-background-video-mp4').val(defaults.video_mp4 || '');
				$wrapper.find('.accepta-background-video-autoplay').prop('checked', defaults.video_autoplay !== false);
				$wrapper.find('.accepta-background-video-loop').prop('checked', defaults.video_loop !== false);
				$wrapper.find('.accepta-background-video-muted').prop('checked', defaults.video_muted !== false);
				$wrapper.find('.accepta-background-video-controls').prop('checked', defaults.video_controls === true);
				if (!defaults.video_mp4) {
					$wrapper.find('.accepta-background-video-remove').hide();
				}
			}
			
			// Reset overlay - always reset checkbox state to false (unchecked)
			var overlayEnabled = defaults.overlay_enabled === true || defaults.overlay_enabled === 'true' || defaults.overlay_enabled === 1;
			var $overlayCheckbox = $wrapper.find('.accepta-background-overlay-enabled');
			
			// Always uncheck the overlay checkbox on reset
			$overlayCheckbox.prop('checked', false);
			
			// Hide overlay options
			$wrapper.find('.accepta-overlay-options').hide();
			
			// Trigger change event to ensure UI updates
			$overlayCheckbox.trigger('change');
			
			// Reset overlay color
			if (defaults.overlay_color) {
				$wrapper.find('.accepta-background-overlay-color').val(defaults.overlay_color);
				if ($wrapper.find('.accepta-background-overlay-color').data('wpColorPicker')) {
					$wrapper.find('.accepta-background-overlay-color').wpColorPicker('color', defaults.overlay_color);
				}
			}
			if (defaults.overlay_opacity !== undefined) {
				$wrapper.find('.accepta-background-overlay-opacity').val(defaults.overlay_opacity);
				$wrapper.find('.accepta-opacity-value').text(defaults.overlay_opacity);
			}
			
			// Update the value - this will trigger the live preview
			updateBackgroundValue($wrapper, controlId);
			
			// Ensure the preview updates by explicitly triggering the setting change
			setTimeout(function() {
				if (wp.customize && wp.customize.control(controlId)) {
					var setting = wp.customize.control(controlId).setting;
					if (setting) {
						// Get the current value from the hidden input
						var currentValue = $wrapper.find('.accepta-background-hidden').val();
						// Force update by setting the value again
						setting.set(currentValue);
					}
				}
			}, 150);
			
		}, 100);
	}

	function loadSavedValues($wrapper, controlId) {
		if (wp.customize && wp.customize.control(controlId)) {
			var savedValue = wp.customize.control(controlId).setting.get();
			if (savedValue) {
				try {
					var data = JSON.parse(savedValue);
					
					// Update background type
					if (data.type) {
						var $typeRadio = $wrapper.find('input[name$="_type"][value="' + data.type + '"]');
						if ($typeRadio.length) {
							$typeRadio.prop('checked', true).trigger('change');
							// Also update the visual state
							$wrapper.find('.accepta-background-type-selector label').removeClass('active');
							$typeRadio.closest('label').addClass('active');
						}
					}
					
					// Update solid color
					if (data.color) {
						$wrapper.find('.accepta-background-color').val(data.color);
						if ($wrapper.find('.accepta-background-color').data('wpColorPicker')) {
							$wrapper.find('.accepta-background-color').wpColorPicker('color', data.color);
						}
					}
					
					// Update gradient options
					if (data.gradient_type !== undefined) {
						$wrapper.find('.accepta-background-gradient-type').val(data.gradient_type || 'linear').trigger('change');
					}
					if (data.gradient_angle !== undefined) {
						$wrapper.find('.accepta-background-gradient-angle').val(data.gradient_angle || '90');
					}
					if (data.gradient_start !== undefined) {
						// Check if this is hero background control
						var isHero = controlId === 'accepta_hero_background';
						var defaultGradientStart = isHero ? '#6F9C50' : '#2c3e50';
						var gradientStart = data.gradient_start || defaultGradientStart;
						$wrapper.find('.accepta-background-gradient-start').val(gradientStart);
						setTimeout(function() {
							if ($wrapper.find('.accepta-background-gradient-start').data('wpColorPicker')) {
								$wrapper.find('.accepta-background-gradient-start').wpColorPicker('color', gradientStart);
							}
						}, 100);
					}
					if (data.gradient_end !== undefined) {
						// Check if this is hero background control
						var isHero = controlId === 'accepta_hero_background';
						var defaultGradientEnd = isHero ? '#568F0C' : '#34495e';
						var gradientEnd = data.gradient_end || defaultGradientEnd;
						$wrapper.find('.accepta-background-gradient-end').val(gradientEnd);
						setTimeout(function() {
							if ($wrapper.find('.accepta-background-gradient-end').data('wpColorPicker')) {
								$wrapper.find('.accepta-background-gradient-end').wpColorPicker('color', gradientEnd);
							}
						}, 100);
					}
					
					// Update video options (if control supports video)
					if (data.video_type !== undefined) {
						$wrapper.find('.accepta-background-video-type').val(data.video_type || 'youtube').trigger('change');
						$wrapper.find('.accepta-background-video-url').val(data.video_url || '');
						$wrapper.find('.accepta-background-video-mp4').val(data.video_mp4 || '');
						$wrapper.find('.accepta-background-video-autoplay').prop('checked', data.video_autoplay !== false);
						$wrapper.find('.accepta-background-video-loop').prop('checked', data.video_loop !== false);
						$wrapper.find('.accepta-background-video-muted').prop('checked', data.video_muted !== false);
						$wrapper.find('.accepta-background-video-controls').prop('checked', data.video_controls === true);
						if (data.video_mp4) {
							$wrapper.find('.accepta-background-video-remove').show();
						}
					}
					
					// Update overlay checkbox
					if (data.overlay_enabled !== undefined) {
						$wrapper.find('.accepta-background-overlay-enabled').prop('checked', data.overlay_enabled);
						$wrapper.find('.accepta-overlay-options').toggle(data.overlay_enabled);
					}
					
					// Update overlay color
					if (data.overlay_color) {
						$wrapper.find('.accepta-background-overlay-color').val(data.overlay_color);
						setTimeout(function() {
							if ($wrapper.find('.accepta-background-overlay-color').data('wpColorPicker')) {
								$wrapper.find('.accepta-background-overlay-color').wpColorPicker('color', data.overlay_color);
							}
						}, 100);
					}
					if (data.overlay_opacity !== undefined) {
						$wrapper.find('.accepta-background-overlay-opacity').val(data.overlay_opacity);
						$wrapper.find('.accepta-opacity-value').text(data.overlay_opacity);
					}
					
				} catch (e) {
				}
			}
		}
	}

	function updateBackgroundValue($wrapper, controlId) {
		var type = $wrapper.find('input[name$="_type"]:checked').val();
		
		// If no type is selected, default to solid
		if (!type) {
			type = 'solid';
			$wrapper.find('input[name$="_type"][value="solid"]').prop('checked', true);
		}
		
		
		// Get existing data first to preserve values (especially image)
		var existingData = {};
		if (wp.customize && wp.customize.control(controlId)) {
			var savedValue = wp.customize.control(controlId).setting.get();
			if (savedValue) {
				try {
					existingData = JSON.parse(savedValue);
				} catch (e) {
					// Try from hidden input as fallback
					var currentValue = $wrapper.find('.accepta-background-hidden').val();
					if (currentValue) {
						try {
							existingData = JSON.parse(currentValue);
						} catch (e2) {
							existingData = {};
						}
					}
				}
			}
		}
		
		var data = {
			type: type
		};

		if (type === 'solid') {
			// Check if this is hero background control
			var controlId = $wrapper.data('control-id');
			var isHero = controlId === 'accepta_hero_background';
			var defaultColor = isHero ? '#6F9C50' : '#2c3e50';
			data.color = $wrapper.find('.accepta-background-color').val() || defaultColor;
			data.image = ''; // Clear image when switching to solid
			// Preserve overlay state, but it won't apply (only applies to image)
			if (existingData.overlay_enabled !== undefined) {
				data.overlay_enabled = existingData.overlay_enabled;
			}
			// Preserve video data even when not on video tab
			if (existingData.video_type !== undefined) {
				data.video_type = existingData.video_type;
				data.video_url = existingData.video_url || '';
				data.video_mp4 = existingData.video_mp4 || '';
				data.video_autoplay = existingData.video_autoplay !== undefined ? existingData.video_autoplay : true;
				data.video_loop = existingData.video_loop !== undefined ? existingData.video_loop : true;
				data.video_muted = existingData.video_muted !== undefined ? existingData.video_muted : true;
				data.video_controls = existingData.video_controls !== undefined ? existingData.video_controls : false;
			}
		} else if (type === 'gradient') {
			// Get gradient values from form fields, use existing data as fallback, then defaults
			var gradientTypeValue = $wrapper.find('.accepta-background-gradient-type').val();
			data.gradient_type = gradientTypeValue || (existingData.gradient_type !== undefined ? existingData.gradient_type : 'linear');
			
			var gradientAngleValue = $wrapper.find('.accepta-background-gradient-angle').val();
			data.gradient_angle = gradientAngleValue || (existingData.gradient_angle !== undefined ? existingData.gradient_angle : '90');
			
			// Check if this is hero background control
			var isHero = controlId === 'accepta_hero_background';
			var defaultGradientStart = isHero ? '#6F9C50' : '#2c3e50';
			var gradientStartValue = $wrapper.find('.accepta-background-gradient-start').val();
			data.gradient_start = gradientStartValue || (existingData.gradient_start !== undefined ? existingData.gradient_start : defaultGradientStart);
			
			var defaultGradientEnd = isHero ? '#568F0C' : '#34495e';
			var gradientEndValue = $wrapper.find('.accepta-background-gradient-end').val();
			data.gradient_end = gradientEndValue || (existingData.gradient_end !== undefined ? existingData.gradient_end : defaultGradientEnd);
			
			data.image = ''; // Clear image when switching to gradient
			// Preserve overlay state, but it won't apply (only applies to image)
			if (existingData.overlay_enabled !== undefined) {
				data.overlay_enabled = existingData.overlay_enabled;
			}
			// Preserve video data even when not on video tab
			if (existingData.video_type !== undefined) {
				data.video_type = existingData.video_type;
				data.video_url = existingData.video_url || '';
				data.video_mp4 = existingData.video_mp4 || '';
				data.video_autoplay = existingData.video_autoplay !== undefined ? existingData.video_autoplay : true;
				data.video_loop = existingData.video_loop !== undefined ? existingData.video_loop : true;
				data.video_muted = existingData.video_muted !== undefined ? existingData.video_muted : true;
				data.video_controls = existingData.video_controls !== undefined ? existingData.video_controls : false;
			}
		} else if (type === 'image') {
			// Always preserve existing image data - only update if form field has a value
			var imageUrl = $wrapper.find('.accepta-background-image-url').val() || '';
			// If form field is empty, use existing saved image
			if (!imageUrl && existingData.image) {
				imageUrl = existingData.image;
			}
			data.image = imageUrl;
			
			// Preserve all image settings, only update if form has values
			var sizeValue = $wrapper.find('.accepta-background-image-size').val();
			data.size = sizeValue || existingData.size || 'cover';
			
			var repeatValue = $wrapper.find('.accepta-background-image-repeat').val();
			data.repeat = repeatValue || existingData.repeat || 'no-repeat';
			
			var positionValue = $wrapper.find('.accepta-background-image-position').val();
			data.position = positionValue || existingData.position || 'center';
			
			var attachmentValue = $wrapper.find('.accepta-background-image-attachment').val();
			data.attachment = attachmentValue || existingData.attachment || 'scroll';
			// Preserve video data even when not on video tab
			if (existingData.video_type !== undefined) {
				data.video_type = existingData.video_type;
				data.video_url = existingData.video_url || '';
				data.video_mp4 = existingData.video_mp4 || '';
				data.video_autoplay = existingData.video_autoplay !== undefined ? existingData.video_autoplay : true;
				data.video_loop = existingData.video_loop !== undefined ? existingData.video_loop : true;
				data.video_muted = existingData.video_muted !== undefined ? existingData.video_muted : true;
				data.video_controls = existingData.video_controls !== undefined ? existingData.video_controls : false;
			}
		} else if (type === 'video') {
			// Video options - get from form fields, preserve existing if empty
			var videoTypeValue = $wrapper.find('.accepta-background-video-type').val();
			data.video_type = videoTypeValue || (existingData.video_type !== undefined ? existingData.video_type : 'youtube');
			
			var videoUrlValue = $wrapper.find('.accepta-background-video-url').val() || '';
			// If form field is empty, use existing saved video URL
			if (!videoUrlValue && existingData.video_url) {
				videoUrlValue = existingData.video_url;
			}
			data.video_url = videoUrlValue;
			
			var videoMp4Value = $wrapper.find('.accepta-background-video-mp4').val() || '';
			// If form field is empty, use existing saved video MP4
			if (!videoMp4Value && existingData.video_mp4) {
				videoMp4Value = existingData.video_mp4;
			}
			data.video_mp4 = videoMp4Value;
			
			// Get checkbox states, use existing if checkboxes don't exist (for hero control)
			var $autoplayCheckbox = $wrapper.find('.accepta-background-video-autoplay');
			data.video_autoplay = $autoplayCheckbox.length ? $autoplayCheckbox.is(':checked') : (existingData.video_autoplay !== undefined ? existingData.video_autoplay : true);
			
			var $loopCheckbox = $wrapper.find('.accepta-background-video-loop');
			data.video_loop = $loopCheckbox.length ? $loopCheckbox.is(':checked') : (existingData.video_loop !== undefined ? existingData.video_loop : true);
			
			var $mutedCheckbox = $wrapper.find('.accepta-background-video-muted');
			data.video_muted = $mutedCheckbox.length ? $mutedCheckbox.is(':checked') : (existingData.video_muted !== undefined ? existingData.video_muted : true);
			
			var $controlsCheckbox = $wrapper.find('.accepta-background-video-controls');
			data.video_controls = $controlsCheckbox.length ? $controlsCheckbox.is(':checked') : (existingData.video_controls !== undefined ? existingData.video_controls : false);
			
			// Preserve other background data
			if (existingData.color !== undefined) {
				data.color = existingData.color;
			}
			if (existingData.gradient_type !== undefined) {
				data.gradient_type = existingData.gradient_type;
				data.gradient_angle = existingData.gradient_angle;
				data.gradient_start = existingData.gradient_start;
				data.gradient_end = existingData.gradient_end;
			}
			if (existingData.image !== undefined) {
				data.image = existingData.image;
				data.size = existingData.size;
				data.repeat = existingData.repeat;
				data.position = existingData.position;
				data.attachment = existingData.attachment;
			}
		}

		// Overlay options
		data.overlay_enabled = $wrapper.find('.accepta-background-overlay-enabled').is(':checked');
		data.overlay_color = $wrapper.find('.accepta-background-overlay-color').val() || '#000000';
		data.overlay_opacity = $wrapper.find('.accepta-background-overlay-opacity').val() || '0.5';

		var jsonValue = JSON.stringify(data);
		$wrapper.find('.accepta-background-hidden').val(jsonValue);


		if (wp.customize && wp.customize.control(controlId)) {
			var setting = wp.customize.control(controlId).setting;
			if (setting) {
				// Set the value - this will automatically trigger the live preview if transport is postMessage
				setting.set(jsonValue);
			} else {
			}
		} else {
		}
	}

	// Register control types
	wp.customize.controlConstructor['accepta-background'] = wp.customize.Control.extend({
		ready: function() {
			var control = this;
			var $wrapper = control.container.find('.accepta-background-control-wrapper');
			
			if ($wrapper.length) {
				var isInitialized = $wrapper.data('initialized');
				
				if (!isInitialized) {
					// Initialize this specific control
					var controlId = $wrapper.data('control-id') || control.id;
					
					// Use multiple timeouts to ensure DOM is ready
					setTimeout(function() {
						initializeSingleBackgroundControl($wrapper, controlId);
					}, 50);
					
					setTimeout(function() {
						if (!$wrapper.data('initialized')) {
							initializeSingleBackgroundControl($wrapper, controlId);
						} else {
						}
					}, 200);
				}
			} else {
				// Try to find wrapper in the entire document as fallback
				var $globalWrapper = $('.accepta-background-control-wrapper[data-control-id="' + control.id + '"]');
				if ($globalWrapper.length) {
					var controlId = $globalWrapper.data('control-id') || control.id;
					initializeSingleBackgroundControl($globalWrapper, controlId);
				}
			}
		}
	});

	// Register hero background control type (uses same JS as base background control)
	wp.customize.controlConstructor['accepta-hero-background'] = wp.customize.controlConstructor['accepta-background'];

})(jQuery);
