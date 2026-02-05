/**
 * Accepta Tab Control JavaScript
 * Handles tab switching and control visibility
 */

(function($) {
	'use strict';

	// Initialize tab controls when customizer is ready
	wp.customize.bind('ready', function() {
		initializeTabControls();
		addControlClasses();
	});

	function addControlClasses() {
		// Add classes to controls based on their IDs
		var controlClassMap = {
			'accepta_hero_bg_color': 'accepta-bg-color-control',
			'accepta_hero_bg_gradient': 'accepta-bg-gradient-control',
			'accepta_hero_bg_image': 'accepta-bg-image-control',
			'accepta_hero_bg_video_type': 'accepta-bg-video-control',
			'accepta_hero_bg_video_url': 'accepta-bg-video-control',
			'accepta_hero_bg_video_mp4': 'accepta-bg-video-control',
			'accepta_hero_bg_video_autoplay': 'accepta-bg-video-control',
			'accepta_hero_bg_video_loop': 'accepta-bg-video-control',
			'accepta_hero_bg_video_muted': 'accepta-bg-video-control',
			'accepta_hero_bg_video_controls': 'accepta-bg-video-control'
		};

		$.each(controlClassMap, function(controlId, className) {
			wp.customize.control(controlId, function(control) {
				if (control && control.container) {
					control.container.addClass(className);
				}
			});
		});
	}

	function initializeTabControls() {
		$('.accepta-tab-control').each(function() {
			var $control = $(this);
			var controlId = $control.data('control-id');
			var $hiddenInput = $control.find('input[type="hidden"]');
			var $buttons = $control.find('.accepta-tab-button');

			// Handle tab button clicks
			$buttons.on('click', function() {
				var $button = $(this);
				var tabValue = $button.data('tab');

				// Update button states
				$buttons.removeClass('active');
				$button.addClass('active');

				// Update hidden input value
				$hiddenInput.val(tabValue).trigger('change');

				// Trigger customizer setting change
				if (controlId && wp.customize.control(controlId)) {
					wp.customize.control(controlId).setting.set(tabValue);
				}

				// Show/hide related controls based on tab
				updateControlVisibility(controlId, tabValue);
			});

			// Listen for setting changes from outside
			if (controlId && wp.customize.control(controlId)) {
				wp.customize.control(controlId).setting.bind(function(value) {
					// Update active button
					$buttons.removeClass('active');
					$buttons.filter('[data-tab="' + value + '"]').addClass('active');
					$hiddenInput.val(value);

					// Update control visibility
					updateControlVisibility(controlId, value);
				});
			}

			// Initial visibility update
			var currentValue = $hiddenInput.val();
			if (currentValue) {
				updateControlVisibility(controlId, currentValue);
			}
		});
	}

	function updateControlVisibility(controlId, tabValue) {
		// Find the section containing this control
		var $section = $('.customize-control[data-control-id="' + controlId + '"]').closest('.accordion-section');
		
		if ($section.length === 0) {
			$section = $('.customize-control-accepta-tab[data-control-id="' + controlId + '"]').closest('.accordion-section');
		}

		if ($section.length === 0) {
			return;
		}

		// Show/hide controls based on tab value
		$section.find('.customize-control').each(function() {
			var $control = $(this);

			// Check if this control should be visible for the current tab
			if ($control.hasClass('accepta-bg-color-control')) {
				if (tabValue === 'color') {
					$control.slideDown(200);
				} else {
					$control.slideUp(200);
				}
			} else if ($control.hasClass('accepta-bg-gradient-control')) {
				if (tabValue === 'gradient') {
					$control.slideDown(200);
				} else {
					$control.slideUp(200);
				}
			} else if ($control.hasClass('accepta-bg-image-control')) {
				if (tabValue === 'image') {
					$control.slideDown(200);
				} else {
					$control.slideUp(200);
				}
			} else if ($control.hasClass('accepta-bg-video-control')) {
				if (tabValue === 'video') {
					$control.slideDown(200);
				} else {
					$control.slideUp(200);
				}
			}
		});
	}

	// Also handle active_callback-based visibility
	wp.customize.bind('ready', function() {
		// Listen for background type changes and update visibility
		wp.customize('accepta_hero_bg_type', function(setting) {
			setting.bind(function(value) {
				// Small delay to ensure controls are rendered
				setTimeout(function() {
					// Trigger active_callback evaluation for all related controls
					wp.customize.control.each(function(control) {
						if (control.activeCallback && typeof control.activeCallback === 'function') {
							var isActive = control.activeCallback.call(control);
							if (isActive) {
								control.container.slideDown(200);
							} else {
								control.container.slideUp(200);
							}
						}
					});
				}, 100);
			});
		});
	});

})(jQuery);

