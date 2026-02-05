/**
 * Alignment Control JavaScript
 * Handles responsive tabs and alignment selection
 */
(function( $ ) {
	'use strict';

	wp.customize.bind( 'ready', function() {
		initializeAlignmentControls();
	});

	function initializeAlignmentControls() {
		$( '.accepta-alignment-control' ).each( function() {
			var $control = $( this );
			var controlId = $control.data( 'control-id' );
			
			initializeControl( $control, controlId );
		} );
	}

	function initializeControl( $control, controlId ) {
		var $hiddenInput = $control.find( 'input[type="hidden"]' );
		var currentValue = {};
		
		// Parse initial value
		try {
			var rawValue = $hiddenInput.val();
			if ( rawValue ) {
				currentValue = JSON.parse( rawValue );
			}
		} catch ( e ) {
			currentValue = { desktop: 'center', tablet: 'center', mobile: 'center' };
		}

		// Initialize responsive tabs
		$control.find( '.accepta-responsive-tab' ).on( 'click', function() {
			var device = $( this ).data( 'device' );
			switchDevice( $control, device );
		} );
		
		// Listen for customizer viewport changes to sync
		setupViewportSync( $control );

		// Initialize radio button changes
		$control.on( 'change', 'input[type="radio"]', function() {
			var $radio = $( this );
			var device = $radio.data( 'device' );
			var value = $radio.val();
			
			// Update selected state
			$control.find( '.accepta-alignment-device.accepta-alignment-' + device + ' .accepta-alignment-option' ).removeClass( 'selected' );
			$radio.closest( '.accepta-alignment-option' ).addClass( 'selected' );
			
			// Update value object
			currentValue[ device ] = value;
			
			// Update hidden input
			$hiddenInput.val( JSON.stringify( currentValue ) ).trigger( 'change' );
			
			// Update customizer setting
			if ( wp.customize && wp.customize.control( controlId ) ) {
				var setting = wp.customize.control( controlId ).setting;
				if ( setting ) {
					setting.set( JSON.stringify( currentValue ) );
				}
			}
		} );

		// Set initial device
		switchDevice( $control, 'desktop' );
	}

	function switchDevice( $control, device ) {
		// Update tab states
		$control.find( '.accepta-responsive-tab' ).removeClass( 'active' );
		$control.find( '.accepta-responsive-tab[data-device="' + device + '"]' ).addClass( 'active' );
		
		// Show/hide device panels
		$control.find( '.accepta-alignment-device' ).hide();
		$control.find( '.accepta-alignment-' + device ).show();
		
		// Sync with customizer viewport preview
		syncCustomizerViewport( device );
	}

	function syncCustomizerViewport( device ) {
		// Check if wp.customize.previewedDevice exists (WordPress 4.5+)
		if ( wp.customize && wp.customize.previewedDevice ) {
			var deviceMap = {
				'desktop': 'desktop',
				'tablet': 'tablet', 
				'mobile': 'mobile'
			};
			
			var wpDevice = deviceMap[ device ];
			if ( wpDevice && wp.customize.previewedDevice.get() !== wpDevice ) {
				wp.customize.previewedDevice.set( wpDevice );
				
				// Add visual feedback for sync
				$( '.accepta-responsive-tab.active' ).addClass( 'synced' );
				setTimeout( function() {
					$( '.accepta-responsive-tab.active' ).removeClass( 'synced' );
				}, 1000 );
			}
		}
	}

	function setupViewportSync( $control ) {
		// Listen for customizer viewport changes
		if ( wp.customize && wp.customize.previewedDevice ) {
			wp.customize.previewedDevice.bind( function( wpDevice ) {
				var deviceMap = {
					'desktop': 'desktop',
					'tablet': 'tablet',
					'mobile': 'mobile'
				};
				
				var device = deviceMap[ wpDevice ] || 'desktop';
				var $activeTab = $control.find( '.accepta-responsive-tab[data-device="' + device + '"]' );
				
				if ( $activeTab.length && ! $activeTab.hasClass( 'active' ) ) {
					switchDevice( $control, device );
				}
			} );
		}
	}

	// Register control constructor for alignment controls
	wp.customize.controlConstructor['accepta-alignment'] = wp.customize.Control.extend({
		ready: function() {
			var control = this;
			var $control = control.container.find( '.accepta-alignment-control' );
			
			if ( $control.length ) {
				var controlId = $control.data( 'control-id' ) || control.id;
				initializeControl( $control, controlId );
			}
		}
	});

})( jQuery );
