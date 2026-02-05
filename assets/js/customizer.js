/**
 * Accepta Customizer Live Preview
 * Handles live preview functionality in the WordPress Customizer
 */

(function( $ ) {
	'use strict';

	function escapeHtml( unsafe ) {
		return String( unsafe )
			.replace( /&/g, '&amp;' )
			.replace( /</g, '&lt;' )
			.replace( />/g, '&gt;' )
			.replace( /"/g, '&quot;' )
			.replace( /'/g, '&#039;' );
	}

	function getCustomizerValue( settingId, fallback ) {
		try {
			if ( wp.customize && wp.customize( settingId ) ) {
				var setting = wp.customize( settingId );
				return typeof setting.get === 'function' ? setting.get() : setting();
			}
		} catch ( e ) {}
		return fallback;
	}

	/**
	 * Mimic PHP `accepta_process_copyright_tags()` in the preview.
	 * This ensures `{site-title}`, `{site-url}`, etc. render immediately without refresh.
	 */
	function processCopyrightTagsForPreview( text ) {
		if ( ! text ) {
			return '';
		}

		var siteTitle = getCustomizerValue( 'blogname', document.title || '' );
		var currentYear = String( new Date().getFullYear() );
		var homeUrl = window.location.origin ? window.location.origin + '/' : '/';

		var replacements = {
			'{copyright}': '©',
			'{current-year}': currentYear,
			'{site-title}': escapeHtml( siteTitle ),
			'{site-url}': '<a href="' + homeUrl + '">' + escapeHtml( siteTitle ) + '</a>',
			'{theme-name}': 'Accepta',
			'{theme-author}': '<a href="https://wpdino.com/" target="_blank" rel="noopener noreferrer">WPDINO</a>',
			'{wordpress}': '<a href="https://wordpress.org/" target="_blank" rel="noopener noreferrer">WordPress</a>'
		};

		var processed = String( text );
		Object.keys( replacements ).forEach( function( tag ) {
			processed = processed.split( tag ).join( replacements[ tag ] );
		} );

		return processed;
	}

	function renderFooterCopyright( rawText ) {
		var $copyright = $( '.footer-copyright' );
		if ( ! $copyright.length ) {
			return;
		}

		var processed = processCopyrightTagsForPreview( rawText || '' );

		// Match backend behavior: keep the container visible, use a non-breaking space when empty.
		if ( processed && processed.trim() !== '' ) {
			$copyright.html( processed ).show();
		} else {
			$copyright.html( '&nbsp;' ).show();
		}
	}

	// Wait for customizer to be ready
	wp.customize.bind( 'ready', function() {
		// Initialize customizer enhancements
		initializeCustomizerEnhancements();
	});

	function initializeCustomizerEnhancements() {
		// Add any customizer initialization here
	}

	// Site Title and Description Live Preview
	wp.customize( 'blogname', function( value ) {
		value.bind( function( newval ) {
			$( '.site-title a' ).text( newval );

			// If copyright uses `{site-title}` or `{site-url}`, keep it in sync.
			renderFooterCopyright( getCustomizerValue( 'accepta_footer_copyright', '' ) );
		} );
	} );

	wp.customize( 'blogdescription', function( value ) {
		value.bind( function( newval ) {
			$( '.site-description' ).text( newval );
		} );
	} );

	// Header Text Color
	wp.customize( 'header_textcolor', function( value ) {
		value.bind( function( newval ) {
			if ( 'blank' === newval ) {
				$( '.site-title, .site-description' ).css( {
					'clip': 'rect(1px, 1px, 1px, 1px)',
					'position': 'absolute'
				} );
			} else {
				$( '.site-title, .site-description' ).css( {
					'clip': 'auto',
					'position': 'relative'
				} );
				$( '.site-title a, .site-description' ).css( {
					'color': newval
				} );
			}
		} );
	} );

	// Footer Copyright Live Preview
	wp.customize( 'accepta_footer_copyright', function( value ) {
		value.bind( function( newval ) {
			renderFooterCopyright( newval );
		} );
	} );

	// Social Media Icons Live Preview
	wp.customize( 'accepta_social_media', function( value ) {
		value.bind( function( newval ) {
			var $socialContainer = $( '.footer-social-icons .social-icons' );
			var socialData = [];

			// Parse the social media data
			try {
				socialData = JSON.parse( newval );
			} catch (e) {
				socialData = [];
			}

			// Clear existing icons
			$socialContainer.empty();

			// Add new icons
			if ( socialData && socialData.length > 0 ) {
				socialData.forEach( function( item ) {
					if ( item.label && item.url ) {
						var iconHtml = '';
						var iconType = item.icon_type || 'fontawesome';
						var cssClass = item.label.toLowerCase().replace(/[^a-z0-9]/g, '');

						// Build icon HTML based on type
						if ( iconType === 'custom' && item.custom_icon ) {
							iconHtml = '<img src="' + item.custom_icon + '" alt="' + item.label + '" class="social-icon-svg" />';
						} else if ( iconType === 'fontawesome' && item.icon ) {
							iconHtml = '<i class="' + item.icon + '" aria-hidden="true"></i>';
						} else {
							iconHtml = '<span class="social-icon-text">' + item.label.charAt(0) + '</span>';
						}

						var linkHtml = '<a href="' + item.url + '" class="social-icon ' + cssClass + '" target="_blank" rel="noopener noreferrer" title="' + item.label + '">' +
							iconHtml +
							'<span class="screen-reader-text">' + item.label + '</span>' +
							'</a>';

						$socialContainer.append( linkHtml );
					}
				} );

				// Show the social icons section
				$( '.footer-social-icons' ).show();
			} else {
				// Hide the social icons section if no items
				$( '.footer-social-icons' ).hide();
			}
		} );
	} );

	// Container Width Live Preview
	wp.customize( 'accepta_container_width', function( value ) {
		value.bind( function( newval ) {
			var css = '.container { max-width: ' + parseInt( newval ) + 'px; }';
			updateDynamicCSS( 'container-width', css );
		} );
	} );

	// Sidebar Layout Live Preview
	wp.customize( 'accepta_sidebar_layout', function( value ) {
		value.bind( function( newval ) {
			var css = '';
			
			switch( newval ) {
				case 'left':
					css = '.content-sidebar-wrap { display: grid; grid-template-columns: 300px 1fr; gap: 30px; flex-wrap: nowrap; }';
					css += '.content-sidebar-wrap .site-main { order: 2; grid-column: 2; }';
					css += '.content-sidebar-wrap .widget-area { order: 1; grid-column: 1; width: 300px; }';
					break;
				case 'right':
					css = '.content-sidebar-wrap { display: grid; grid-template-columns: 1fr 300px; gap: 30px; flex-wrap: nowrap; }';
					css += '.content-sidebar-wrap .site-main { order: 1; grid-column: 1; }';
					css += '.content-sidebar-wrap .widget-area { order: 2; grid-column: 2; width: 300px; }';
					break;
				case 'none':
					css = '.content-sidebar-wrap { display: block; }';
					css += '.content-sidebar-wrap .widget-area { display: none; }';
					css += '.content-sidebar-wrap .site-main { width: 100%; }';
					break;
			}
			
			updateDynamicCSS( 'sidebar-layout', css );
		} );
	} );

	// Content Padding Live Preview
	wp.customize( 'accepta_content_padding', function( value ) {
		value.bind( function( newval ) {
			var spacing = {};
			try {
				spacing = JSON.parse( newval );
			} catch (e) {
				spacing = {};
			}
			
			var css = generateSpacingCSS( spacing, '.site-main', 'padding' );
			updateDynamicCSS( 'content-padding', css );
		} );
	} );

	// Content Margin Live Preview
	wp.customize( 'accepta_content_margin', function( value ) {
		value.bind( function( newval ) {
			var spacing = {};
			try {
				spacing = JSON.parse( newval );
			} catch (e) {
				spacing = {};
			}
			
			var css = generateSpacingCSS( spacing, '.site-main', 'margin' );
			updateDynamicCSS( 'content-margin', css );
		} );
	} );

	// Footer Padding Live Preview
	wp.customize( 'accepta_footer_padding', function( value ) {
		value.bind( function( newval ) {
			var spacing = {};
			try {
				spacing = JSON.parse( newval );
			} catch (e) {
				spacing = {};
			}
			
			var css = generateSpacingCSS( spacing, '.site-footer', 'padding' );
			updateDynamicCSS( 'footer-padding', css );
		} );
	} );

	// Footer Margin Live Preview
	wp.customize( 'accepta_footer_margin', function( value ) {
		value.bind( function( newval ) {
			var spacing = {};
			try {
				spacing = JSON.parse( newval );
			} catch (e) {
				spacing = {};
			}
			
			var css = generateSpacingCSS( spacing, '.site-footer', 'margin' );
			updateDynamicCSS( 'footer-margin', css );
		} );
	} );

	// Global Colors Live Preview
	wp.customize( 'accepta_primary_color', function( value ) {
		value.bind( function( newval ) {
			var css = ':root { --accepta-primary-color: ' + newval + '; }';
			css += '.button, input[type="submit"], .wp-block-button__link { background-color: ' + newval + '; }';
			css += 'a { color: ' + newval + '; }';
			updateDynamicCSS( 'primary-color', css );
		} );
	} );

	wp.customize( 'accepta_background_color', function( value ) {
		value.bind( function( newval ) {
			var css = 'body { background-color: ' + newval + '; }';
			updateDynamicCSS( 'background-color', css );
		} );
	} );

	wp.customize( 'accepta_text_color', function( value ) {
		value.bind( function( newval ) {
			var css = 'body, p, .entry-content { color: ' + newval + '; }';
			updateDynamicCSS( 'text-color', css );
		} );
	} );

	wp.customize( 'accepta_link_color', function( value ) {
		value.bind( function( newval ) {
			var css = 'a { color: ' + newval + '; }';
			updateDynamicCSS( 'link-color', css );
		} );
	} );

	wp.customize( 'accepta_link_hover_color', function( value ) {
		value.bind( function( newval ) {
			var css = 'a:hover { color: ' + newval + '; }';
			updateDynamicCSS( 'link-hover-color', css );
		} );
	} );

	// Footer Styling Live Preview
	
	// Footer Background Live Preview
	wp.customize( 'accepta_footer_background', function( value ) {
		value.bind( function( newval ) {
			var bgData = {};
			try {
				bgData = JSON.parse( newval );
			} catch (e) {
				bgData = { type: 'solid', color: '#2c3e50' };
			}
			
			var css = '';
			var bgCss = [];
			
			if ( bgData.type === 'solid' && bgData.color ) {
				bgCss.push('background-color: ' + bgData.color + '');
				bgCss.push('background-image: none');
			} else if ( bgData.type === 'gradient' ) {
				var gradientType = bgData.gradient_type || 'linear';
				var gradientAngle = bgData.gradient_angle || '90';
				var gradientStart = bgData.gradient_start || '#2c3e50';
				var gradientEnd = bgData.gradient_end || '#34495e';
				
				if ( gradientType === 'linear' ) {
					bgCss.push('background-image: linear-gradient(' + parseInt( gradientAngle ) + 'deg, ' + gradientStart + ', ' + gradientEnd + ')');
				} else {
					bgCss.push('background-image: radial-gradient(circle, ' + gradientStart + ', ' + gradientEnd + ')');
				}
				bgCss.push('background-color: transparent');
			} else if ( bgData.type === 'image' && bgData.image ) {
				bgCss.push('background-image: url(' + bgData.image + ')');
				bgCss.push('background-size: ' + ( bgData.size || 'cover' ) + '');
				bgCss.push('background-repeat: ' + ( bgData.repeat || 'no-repeat' ) + '');
				bgCss.push('background-position: ' + ( bgData.position || 'center' ) + '');
				bgCss.push('background-attachment: ' + ( bgData.attachment || 'scroll' ) + '');
			} else {
				// Default: clear everything
				bgCss.push('background-image: none');
			}
			
			if ( bgCss.length > 0 ) {
				css = '.site-footer { ' + bgCss.join('; ') + '; }';
			}
			
			// Overlay - only show if image is selected and overlay is enabled
			if ( bgData.type === 'image' && bgData.overlay_enabled && bgData.overlay_color && bgData.overlay_opacity !== undefined ) {
				var overlayRgba = hexToRgba( bgData.overlay_color, parseFloat( bgData.overlay_opacity ) );
				css += '.site-footer::before { content: ""; position: absolute; top: 0; left: 0; right: 0; bottom: 0; background-color: ' + overlayRgba + '; z-index: 0; display: block; }';
				css += '.site-footer { position: relative; }';
				css += '.site-footer > * { position: relative; z-index: 1; }';
			} else if ( bgData.type === 'image' ) {
				// On image tab but overlay is disabled - explicitly hide it
				css += '.site-footer::before { display: none; }';
			} else {
				// Not on image tab - hide overlay
				css += '.site-footer::before { display: none; }';
				css += '.site-footer { position: static; }';
				css += '.site-footer > * { position: static; z-index: auto; }';
			}
			
			updateDynamicCSS( 'footer-background', css );
		} );
	} );
	
	// Helper function to convert hex to rgba
	function hexToRgba( hex, opacity ) {
		var r = parseInt( hex.slice( 1, 3 ), 16 );
		var g = parseInt( hex.slice( 3, 5 ), 16 );
		var b = parseInt( hex.slice( 5, 7 ), 16 );
		return 'rgba(' + r + ', ' + g + ', ' + b + ', ' + opacity + ')';
	}
	
	/**
	 * Update hero video preview when video options change
	 */
	function updateHeroVideoPreview( bgData ) {
		var $hero = $( '.accepta-hero-section' );
		var $videoContainer = $hero.find( '.accepta-hero-video-background' );
		
		// If video type is not selected or no video URL/MP4, remove video
		if ( bgData.type !== 'video' || ( !bgData.video_url && !bgData.video_mp4 ) ) {
			$videoContainer.remove();
			return;
		}
		
		// Create video container if it doesn't exist
		if ( $videoContainer.length === 0 ) {
			$hero.prepend( '<div class="accepta-hero-video-background"></div>' );
			$videoContainer = $hero.find( '.accepta-hero-video-background' );
		}
		
		var videoType = bgData.video_type || 'youtube';
		var videoUrl = bgData.video_url || '';
		var videoMp4 = bgData.video_mp4 || '';
		var autoplay = bgData.video_autoplay !== false;
		var loop = bgData.video_loop !== false;
		var muted = bgData.video_muted !== false;
		var controls = bgData.video_controls === true;
		
		$videoContainer.empty();
		
		if ( ( videoType === 'youtube' || videoType === 'vimeo' ) && videoUrl ) {
			// Create embed container
			var $embed = $( '<div class="accepta-hero-video-embed"></div>' );
			$embed.attr( 'data-video-type', videoType );
			$embed.attr( 'data-video-url', videoUrl );
			$embed.attr( 'data-autoplay', autoplay ? '1' : '0' );
			$embed.attr( 'data-loop', loop ? '1' : '0' );
			$embed.attr( 'data-muted', muted ? '1' : '0' );
			$embed.attr( 'data-controls', controls ? '1' : '0' );
			$videoContainer.append( $embed );
			
			// Initialize video embed
			initializeVideoEmbed( $embed[0] );
			
			// Add controls if enabled
			if ( controls ) {
				var $controlsDiv = $( '<div class="accepta-hero-video-controls accepta-hero-video-embed-controls"></div>' );
				var $playPauseBtn = $( '<button class="accepta-hero-video-play-pause" aria-label="Play/Pause"></button>' );
				$playPauseBtn.append( '<span class="accepta-hero-play-icon"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/></svg></span>' );
				$playPauseBtn.append( '<span class="accepta-hero-pause-icon"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M6 4h4v16H6V4zm8 0h4v16h-4V4z"/></svg></span>' );
				var $muteUnmuteBtn = $( '<button class="accepta-hero-video-mute-unmute" aria-label="Mute/Unmute"></button>' );
				$muteUnmuteBtn.append( '<span class="accepta-hero-mute-icon"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M16.5 12c0-1.77-1.02-3.29-2.5-4.03v2.21l2.45 2.45c.03-.2.05-.41.05-.63zm2.5 0c0 .94-.2 1.82-.54 2.64l1.51 1.51C20.63 14.91 21 13.5 21 12c0-4.28-2.99-7.86-7-8.77v2.06c2.89.86 5 3.54 5 6.71zM4.27 3L3 4.27 7.73 9H3v6h4l5 5v-6.73l4.25 4.25c-.67.52-1.42.93-2.25 1.18v2.06c1.38-.31 2.63-.95 3.69-1.81L19.73 21 21 19.73l-9-9L4.27 3zM12 4L9.91 6.09 12 8.18V4z"/></svg></span>' );
				$muteUnmuteBtn.append( '<span class="accepta-hero-unmute-icon"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M3 9v6h4l5 5V4L7 9H3zm13.5 3c0-1.77-1.02-3.29-2.5-4.03v8.05c1.48-.73 2.5-2.25 2.5-4.02zM14 3.23v2.06c2.89.86 5 3.54 5 6.71s-2.11 5.85-5 6.71v2.06c4.01-.91 7-4.49 7-8.77s-2.99-7.86-7-8.77z"/></svg></span>' );
				$controlsDiv.append( $playPauseBtn );
				$controlsDiv.append( $muteUnmuteBtn );
				$videoContainer.append( $controlsDiv );
				
				// Initialize controls after a short delay to ensure iframe is loaded
				setTimeout( function() {
					if ( typeof initHeroSection === 'function' ) {
						initHeroSection();
					}
				}, 500 );
			}
		} else if ( videoType === 'mp4' && videoMp4 ) {
			// Create MP4 video element
			var $video = $( '<video class="accepta-hero-video-element"></video>' );
			if ( autoplay ) $video.attr( 'autoplay', 'autoplay' );
			if ( loop ) $video.attr( 'loop', 'loop' );
			if ( muted ) {
				$video.attr( 'muted', 'muted' );
				$video.prop( 'muted', true ); // Also set as property to ensure it works
			}
			$video.attr( 'data-muted', muted ? '1' : '0' );
			if ( controls ) $video.attr( 'controls', 'controls' );
			
			var $source = $( '<source></source>' );
			$source.attr( 'src', videoMp4 );
			$source.attr( 'type', 'video/mp4' );
			$video.append( $source );
			
			if ( controls ) {
				var $controlsDiv = $( '<div class="accepta-hero-video-controls"></div>' );
				$controlsDiv.append( '<button class="accepta-hero-video-play-pause" aria-label="Play/Pause"><span class="accepta-hero-play-icon">▶</span><span class="accepta-hero-pause-icon">⏸</span></button>' );
				$controlsDiv.append( '<button class="accepta-hero-video-mute-unmute" aria-label="Mute/Unmute"><span class="accepta-hero-mute-icon">🔇</span><span class="accepta-hero-unmute-icon">🔊</span></button>' );
				$videoContainer.append( $controlsDiv );
			}
			
			$videoContainer.append( $video );
			
			// Set muted property AFTER appending to DOM but BEFORE loading
			if ( muted ) {
				$video[0].muted = true;
			} else {
				$video[0].muted = false;
			}
			
			// Load and play video
			$video[0].load();
			if ( autoplay ) {
				$video[0].play().catch( function( e ) {
					// Autoplay prevented
				} );
			}
			
			// Initialize video controls
			initMP4VideoControls( $video[0] );
		}
	}
	
	/**
	 * Initialize video embed (YouTube/Vimeo)
	 */
	function initializeVideoEmbed( embedElement ) {
		var videoType = embedElement.getAttribute( 'data-video-type' );
		var videoUrl = embedElement.getAttribute( 'data-video-url' );
		var autoplay = embedElement.getAttribute( 'data-autoplay' ) === '1';
		var loop = embedElement.getAttribute( 'data-loop' ) === '1';
		var muted = embedElement.getAttribute( 'data-muted' ) === '1';
		var controls = embedElement.getAttribute( 'data-controls' ) === '1';
		
		if ( !videoUrl ) return;
		
		var embedUrl = '';
		var videoId = '';
		
		if ( videoType === 'youtube' ) {
			var youtubeRegex = /(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/\s]{11})/;
			var match = videoUrl.match( youtubeRegex );
			if ( match && match[1] ) {
				videoId = match[1];
				embedUrl = 'https://www.youtube.com/embed/' + videoId + '?';
				embedUrl += 'autoplay=' + ( autoplay ? '1' : '0' );
				embedUrl += '&loop=' + ( loop ? '1' : '0' );
				embedUrl += '&mute=' + ( muted ? '1' : '0' );
				embedUrl += '&controls=0'; // Always hide YouTube controls, we use custom ones
				embedUrl += '&playsinline=1';
				embedUrl += '&rel=0'; // Don't show related videos
				embedUrl += '&showinfo=0'; // Don't show video info
				embedUrl += '&modestbranding=1'; // Hide YouTube logo
				embedUrl += '&enablejsapi=1'; // Enable JavaScript API for postMessage
				embedUrl += '&iv_load_policy=3'; // Hide annotations
				embedUrl += '&disablekb=1'; // Disable keyboard controls
				embedUrl += '&fs=0'; // Disable fullscreen button
				embedUrl += '&cc_load_policy=0'; // Don't show captions by default
				if ( loop ) {
					embedUrl += '&playlist=' + videoId;
				}
			}
		} else if ( videoType === 'vimeo' ) {
			var vimeoRegex = /(?:vimeo\.com\/)(\d+)/;
			var match = videoUrl.match( vimeoRegex );
			if ( match && match[1] ) {
				videoId = match[1];
				embedUrl = 'https://player.vimeo.com/video/' + videoId + '?';
				embedUrl += 'autoplay=' + ( autoplay ? '1' : '0' );
				embedUrl += '&loop=' + ( loop ? '1' : '0' );
				embedUrl += '&muted=' + ( muted ? '1' : '0' );
				embedUrl += '&controls=0'; // Always hide Vimeo controls, we use custom ones
				embedUrl += '&background=1';
				embedUrl += '&api=1'; // Enable Vimeo API for postMessage
			}
		}
		
		if ( embedUrl ) {
			var $iframe = $( '<iframe></iframe>' );
			$iframe.attr( 'src', embedUrl );
			$iframe.attr( 'frameborder', '0' );
			$iframe.attr( 'allow', 'autoplay; encrypted-media' );
			$iframe.attr( 'allowfullscreen', 'allowfullscreen' );
			$iframe.css( {
				'position': 'absolute',
				'top': '50%',
				'left': '50%',
				'width': '100%',
				'height': '100%',
				'transform': 'translate(-50%, -50%)',
				'object-fit': 'cover'
			} );
			$( embedElement ).append( $iframe );
		}
	}
	
	/**
	 * Initialize MP4 video controls
	 */
	function initMP4VideoControls( videoElement ) {
		var $video = $( videoElement );
		var $container = $video.closest( '.accepta-hero-video-background' );
		var $playPauseBtn = $container.find( '.accepta-hero-video-play-pause' );
		var $muteUnmuteBtn = $container.find( '.accepta-hero-video-mute-unmute' );
		
		// Play/Pause button
		if ( $playPauseBtn.length ) {
			$playPauseBtn.on( 'click', function() {
				if ( $video[0].paused ) {
					$video[0].play();
					$playPauseBtn.find( '.accepta-hero-play-icon' ).hide();
					$playPauseBtn.find( '.accepta-hero-pause-icon' ).show();
				} else {
					$video[0].pause();
					$playPauseBtn.find( '.accepta-hero-play-icon' ).show();
					$playPauseBtn.find( '.accepta-hero-pause-icon' ).hide();
				}
			} );
			
			// Update button state based on video state
			$video.on( 'play', function() {
				$playPauseBtn.find( '.accepta-hero-play-icon' ).hide();
				$playPauseBtn.find( '.accepta-hero-pause-icon' ).show();
			} );
			$video.on( 'pause', function() {
				$playPauseBtn.find( '.accepta-hero-play-icon' ).show();
				$playPauseBtn.find( '.accepta-hero-pause-icon' ).hide();
			} );
		}
		
		// Mute/Unmute button
		if ( $muteUnmuteBtn.length ) {
			$muteUnmuteBtn.on( 'click', function() {
				$video[0].muted = !$video[0].muted;
				if ( $video[0].muted ) {
					$muteUnmuteBtn.find( '.accepta-hero-mute-icon' ).show();
					$muteUnmuteBtn.find( '.accepta-hero-unmute-icon' ).hide();
				} else {
					$muteUnmuteBtn.find( '.accepta-hero-mute-icon' ).hide();
					$muteUnmuteBtn.find( '.accepta-hero-unmute-icon' ).show();
				}
			} );
			
			// Update button state based on video state
			$video.on( 'volumechange', function() {
				if ( $video[0].muted ) {
					$muteUnmuteBtn.find( '.accepta-hero-mute-icon' ).show();
					$muteUnmuteBtn.find( '.accepta-hero-unmute-icon' ).hide();
				} else {
					$muteUnmuteBtn.find( '.accepta-hero-mute-icon' ).hide();
					$muteUnmuteBtn.find( '.accepta-hero-unmute-icon' ).show();
				}
			} );
		}
	}
	
	// Hero Background Live Preview
	wp.customize( 'accepta_hero_background', function( value ) {
		value.bind( function( newval ) {
			var bgData = {};
			try {
				bgData = JSON.parse( newval );
			} catch (e) {
				bgData = { type: 'solid', color: '#ffffff' };
			}
			
			var css = '';
			var bgCss = [];
			
			// Always check and remove video if type is not 'video'
			if ( bgData.type !== 'video' ) {
				updateHeroVideoPreview( bgData );
			}
			
			if ( bgData.type === 'solid' && bgData.color ) {
				bgCss.push('background-color: ' + bgData.color + '');
				bgCss.push('background-image: none');
			} else if ( bgData.type === 'gradient' ) {
				var gradientType = bgData.gradient_type || 'linear';
				var gradientAngle = bgData.gradient_angle || '90';
				var gradientStart = bgData.gradient_start || '#6F9C50';
				var gradientEnd = bgData.gradient_end || '#568F0C';
				
				if ( gradientType === 'linear' ) {
					bgCss.push('background-image: linear-gradient(' + parseInt( gradientAngle ) + 'deg, ' + gradientStart + ', ' + gradientEnd + ')');
				} else {
					bgCss.push('background-image: radial-gradient(circle, ' + gradientStart + ', ' + gradientEnd + ')');
				}
				bgCss.push('background-color: transparent');
			} else if ( bgData.type === 'image' && bgData.image ) {
				bgCss.push('background-image: url(' + bgData.image + ')');
				bgCss.push('background-size: ' + ( bgData.size || 'cover' ) + '');
				bgCss.push('background-repeat: ' + ( bgData.repeat || 'no-repeat' ) + '');
				bgCss.push('background-position: ' + ( bgData.position || 'center' ) + '');
				bgCss.push('background-attachment: ' + ( bgData.attachment || 'scroll' ) + '');
			} else if ( bgData.type === 'video' ) {
				// Video backgrounds are handled via inline styles in the template
				// Just clear other background styles
				bgCss.push('background-image: none');
				bgCss.push('background-color: transparent');
				
				// Update video element in preview
				updateHeroVideoPreview( bgData );
			} else {
				// Default: clear everything
				bgCss.push('background-image: none');
			}
			
			if ( bgCss.length > 0 ) {
				css = '.accepta-hero-section { ' + bgCss.join('; ') + '; }';
			}
			
			// Overlay - show for both image and video backgrounds if enabled
			var $hero = $( '.accepta-hero-section' );
			var $overlay = $hero.find( '.accepta-hero-overlay' );
			
			// Ensure overlay element exists
			if ( $overlay.length === 0 ) {
				$overlay = $( '<div class="accepta-hero-overlay"></div>' );
				$hero.append( $overlay );
			}
			
			if ( ( bgData.type === 'image' || bgData.type === 'video' ) && bgData.overlay_enabled && bgData.overlay_color ) {
				// Parse opacity - ensure it's a valid number between 0 and 1
				var opacity = parseFloat( bgData.overlay_opacity );
				if ( isNaN( opacity ) || opacity < 0 ) {
					opacity = 0;
				} else if ( opacity > 1 ) {
					opacity = 1;
				}
				
				var overlayRgba = hexToRgba( bgData.overlay_color, opacity );
				// Update both via CSS and inline style for immediate feedback
				$overlay.css( {
					'background-color': overlayRgba,
					'display': 'block'
				} );
				css += '.accepta-hero-section .accepta-hero-overlay { background-color: ' + overlayRgba + '; display: block; }';
			} else {
				// Overlay disabled or not applicable - hide it
				$overlay.css( 'display', 'none' );
				css += '.accepta-hero-section .accepta-hero-overlay { display: none; }';
			}
			
			updateDynamicCSS( 'hero-background', css );
		} );
	} );
	
	// Hero Width Live Preview
	wp.customize( 'accepta_hero_width', function( value ) {
		value.bind( function( newval ) {
			var css = '';
			var $hero = $( '.accepta-hero-section' );
			
			// Remove all width classes
			$hero.removeClass( 'accepta-hero-boxed accepta-hero-fullwidth' );
			
			if ( newval === 'fullwidth' ) {
				$hero.addClass( 'accepta-hero-fullwidth' );
				css = '.accepta-hero-section.accepta-hero-fullwidth { width: 100vw; max-width: 100vw; margin-left: calc(-50vw + 50%); position: relative; left: 0; }';
				css += '.accepta-hero-section.accepta-hero-fullwidth .accepta-hero-content-wrapper { width: 100%; }';
			} else {
				$hero.addClass( 'accepta-hero-boxed' );
				css = '.accepta-hero-section.accepta-hero-boxed { width: 100%; }';
			}
			
			updateDynamicCSS( 'hero-width', css );
		} );
	} );
	
	// Hero Height Live Preview
	wp.customize( 'accepta_hero_height', function( value ) {
		value.bind( function( newval ) {
			var $hero = $( '.accepta-hero-section' );
			$hero.removeClass( 'accepta-hero-fullscreen accepta-hero-min-height accepta-hero-custom-height' );
			
			if ( newval === 'fullscreen' ) {
				$hero.addClass( 'accepta-hero-fullscreen' );
			} else if ( newval === 'min-height' ) {
				$hero.addClass( 'accepta-hero-min-height' );
			} else {
				$hero.addClass( 'accepta-hero-custom-height' );
			}
		} );
	} );
	
	// Hero Minimum Height Live Preview
	wp.customize( 'accepta_hero_min_height', function( value ) {
		value.bind( function( newval ) {
			var height = parseInt( newval ) || 500;
			var css = '.accepta-hero-section.accepta-hero-min-height { min-height: ' + height + 'px; }';
			css += '.accepta-hero-section.accepta-hero-custom-height { min-height: ' + height + 'px; }';
			updateDynamicCSS( 'hero-min-height', css );
		} );
	} );
	
	// Hero Content Live Preview
	wp.customize( 'accepta_hero_heading', function( value ) {
		value.bind( function( newval ) {
			var $content = $( '.accepta-hero-content' );
			var $heading = $content.find( '.accepta-hero-heading' );
			
			if ( newval && newval.trim() !== '' ) {
				if ( $heading.length === 0 ) {
					// Create heading element if it doesn't exist
					// Get current heading color and size from other settings
					var headingColor = wp.customize( 'accepta_hero_heading_color' ).get() || '#ffffff';
					var headingSize = wp.customize( 'accepta_hero_heading_size' ).get() || 48;
					$heading = $( '<h1 class="accepta-hero-heading"></h1>' );
					$heading.css( {
						'color': headingColor,
						'font-size': headingSize + 'px'
					} );
					var $text = $content.find( '.accepta-hero-text' );
					if ( $text.length > 0 ) {
						$text.before( $heading );
					} else {
						$content.prepend( $heading );
					}
				}
				$heading.html( newval ).show();
			} else {
				$heading.hide();
			}
		} );
	} );
	
	wp.customize( 'accepta_hero_text', function( value ) {
		value.bind( function( newval ) {
			var $content = $( '.accepta-hero-content' );
			var $text = $content.find( '.accepta-hero-text' );
			
			// Convert line breaks to paragraphs (similar to wpautop)
			function wpautop( text ) {
				if ( !text || text.trim() === '' ) {
					return '';
				}
				// Preserve existing HTML
				if ( text.indexOf( '<' ) !== -1 ) {
					return text;
				}
				// Convert double line breaks to paragraph breaks
				text = text.replace( /\r\n|\r/g, '\n' );
				text = text.replace( /\n\n+/g, '</p><p>' );
				// Convert single line breaks to <br />
				text = text.replace( /\n/g, '<br />' );
				return '<p>' + text + '</p>';
			}
			
			if ( newval && newval.trim() !== '' ) {
				if ( $text.length === 0 ) {
					// Create text element if it doesn't exist
					// Get current text color and size from other settings
					var textColor = wp.customize( 'accepta_hero_text_color' ).get() || '#ffffff';
					var textSize = wp.customize( 'accepta_hero_text_size' ).get() || 18;
					$text = $( '<div class="accepta-hero-text"></div>' );
					$text.css( {
						'color': textColor,
						'font-size': textSize + 'px'
					} );
					var $buttonWrapper = $content.find( '.accepta-hero-button-wrapper' );
					if ( $buttonWrapper.length > 0 ) {
						$buttonWrapper.before( $text );
					} else {
						var $heading = $content.find( '.accepta-hero-heading' );
						if ( $heading.length > 0 ) {
							$heading.after( $text );
						} else {
							$content.prepend( $text );
						}
					}
				}
				$text.html( wpautop( newval ) ).show();
			} else {
				$text.hide();
			}
		} );
	} );
	
	wp.customize( 'accepta_hero_button_text', function( value ) {
		value.bind( function( newval ) {
			var $content = $( '.accepta-hero-content' );
			var $buttonWrapper = $content.find( '.accepta-hero-button-wrapper' );
			var $button = $buttonWrapper.length > 0 ? $buttonWrapper.find( '.accepta-hero-button' ) : null;
			
			// Get current button URL to preserve it
			var currentUrl = $button && $button.length > 0 ? $button.attr( 'href' ) : '#';
			var currentStyle = $button && $button.length > 0 ? $button.attr( 'class' ).match( /accepta-hero-button-(primary|secondary|outline)/ ) : null;
			var buttonStyle = currentStyle ? currentStyle[1] : 'primary';
			
			if ( newval && newval.trim() !== '' ) {
				if ( $buttonWrapper.length === 0 ) {
					// Create button wrapper and button if they don't exist
					$buttonWrapper = $( '<div class="accepta-hero-button-wrapper"></div>' );
					$button = $( '<a href="' + ( currentUrl || '#' ) + '" class="accepta-hero-button accepta-hero-button-' + buttonStyle + '"></a>' );
					$buttonWrapper.append( $button );
					$content.append( $buttonWrapper );
				}
				if ( !$button || $button.length === 0 ) {
					$button = $( '<a href="' + ( currentUrl || '#' ) + '" class="accepta-hero-button accepta-hero-button-' + buttonStyle + '"></a>' );
					$buttonWrapper.append( $button );
				}
				$button.text( newval );
				$buttonWrapper.show();
			} else {
				if ( $buttonWrapper.length > 0 ) {
					$buttonWrapper.hide();
				}
			}
		} );
	} );
	
	wp.customize( 'accepta_hero_button_url', function( value ) {
		value.bind( function( newval ) {
			var $button = $( '.accepta-hero-button' );
			if ( $button.length > 0 ) {
				$button.attr( 'href', newval && newval.trim() !== '' ? newval : '#' );
			}
		} );
	} );
	
	wp.customize( 'accepta_hero_button_style', function( value ) {
		value.bind( function( newval ) {
			var $button = $( '.accepta-hero-button' );
			if ( $button.length > 0 ) {
				$button.removeClass( 'accepta-hero-button-primary accepta-hero-button-secondary accepta-hero-button-outline' );
				$button.addClass( 'accepta-hero-button-' + newval );
			}
		} );
	} );
	
	// Hero Alignment Live Preview (Responsive)
	// Horizontal Alignment
	wp.customize( 'accepta_hero_align_horizontal', function( value ) {
		value.bind( function( newval ) {
			try {
				var alignData = JSON.parse( newval );
				if ( typeof alignData === 'object' && alignData !== null ) {
					var css = '';
					
					// Desktop
					if ( alignData.desktop ) {
						css += '.accepta-hero-content { align-items: ' + alignData.desktop + '; }';
					}
					
					// Tablet
					if ( alignData.tablet ) {
						css += '@media (min-width: 600px) and (max-width: 782px) { .accepta-hero-content { align-items: ' + alignData.tablet + '; } }';
					}
					
					// Mobile
					if ( alignData.mobile ) {
						css += '@media (max-width: 599px) { .accepta-hero-content { align-items: ' + alignData.mobile + '; } }';
					}
					
					updateDynamicCSS( 'hero-align-horizontal', css );
				}
			} catch ( e ) {
				// Fallback if JSON parse fails
				var css = '.accepta-hero-content { align-items: center; }';
				updateDynamicCSS( 'hero-align-horizontal', css );
			}
		} );
	} );
	
	// Vertical Alignment
	wp.customize( 'accepta_hero_align_vertical', function( value ) {
		value.bind( function( newval ) {
			try {
				var alignData = JSON.parse( newval );
				if ( typeof alignData === 'object' && alignData !== null ) {
					var css = '';
					
					// Ensure flex structure for vertical alignment
					css += '.accepta-hero-section { display: flex; flex-direction: column; }';
					css += '.accepta-hero-content-wrapper { display: flex; flex-direction: column; flex: 1; min-height: 100%; }';
					css += '.accepta-hero-content-wrapper .container { display: flex; flex-direction: column; flex: 1; }';
					
					// Desktop
					if ( alignData.desktop ) {
						css += '.accepta-hero-content-wrapper .container { justify-content: ' + alignData.desktop + '; }';
					}
					
					// Tablet
					if ( alignData.tablet ) {
						css += '@media (min-width: 600px) and (max-width: 782px) { .accepta-hero-content-wrapper .container { justify-content: ' + alignData.tablet + '; } }';
					}
					
					// Mobile
					if ( alignData.mobile ) {
						css += '@media (max-width: 599px) { .accepta-hero-content-wrapper .container { justify-content: ' + alignData.mobile + '; } }';
					}
					
					updateDynamicCSS( 'hero-align-vertical', css );
				}
			} catch ( e ) {
				// Fallback if JSON parse fails
				var css = '.accepta-hero-section { display: flex; flex-direction: column; }';
				css += '.accepta-hero-content-wrapper { display: flex; flex-direction: column; flex: 1; min-height: 100%; }';
				css += '.accepta-hero-content-wrapper .container { display: flex; flex-direction: column; flex: 1; justify-content: center; }';
				updateDynamicCSS( 'hero-align-vertical', css );
			}
		} );
	} );
	
	// Hero Typography Live Preview
	wp.customize( 'accepta_hero_heading_color', function( value ) {
		value.bind( function( newval ) {
			$( '.accepta-hero-heading' ).css( 'color', newval );
		} );
	} );
	
	wp.customize( 'accepta_hero_heading_size', function( value ) {
		value.bind( function( newval ) {
			$( '.accepta-hero-heading' ).css( 'font-size', newval + 'px' );
		} );
	} );
	
	wp.customize( 'accepta_hero_text_color', function( value ) {
		value.bind( function( newval ) {
			$( '.accepta-hero-text' ).css( 'color', newval );
		} );
	} );
	
	wp.customize( 'accepta_hero_text_size', function( value ) {
		value.bind( function( newval ) {
			$( '.accepta-hero-text' ).css( 'font-size', newval + 'px' );
		} );
	} );

	// Footer Text Color Live Preview
	wp.customize( 'accepta_footer_text_color', function( value ) {
		value.bind( function( newval ) {
			var css = '.site-footer { color: ' + newval + '; }';
			css += '.site-footer a { color: ' + newval + '; }';
			updateDynamicCSS( 'footer-text-color', css );
		} );
	} );

	// Typography Live Preview
	
	// Body Typography Live Preview
	wp.customize( 'accepta_body_typography', function( value ) {
		value.bind( function( newval ) {
			var typography = {};
			try {
				typography = JSON.parse( newval );
			} catch (e) {
				typography = {};
			}
			
			var css = generateTypographyCSS( typography, 'body, p, .entry-content, .entry-summary, .widget' );
			updateDynamicCSS( 'body-typography', css );
			
			// Handle font loading
			handleFontLoading( typography, 'BODY TYPOGRAPHY' );
		} );
	} );

	// All Headings Typography Live Preview (Default)
	wp.customize( 'accepta_all_headings_typography', function( value ) {
		value.bind( function( newval ) {
			var typography = {};
			try {
				typography = JSON.parse( newval );
			} catch (e) {
				typography = {};
			}
			
			var css = generateTypographyCSS( typography, 'h1, h2, h3, h4, h5, h6, .entry-title' );
			updateDynamicCSS( 'all-headings-typography', css );
			
			// Handle font loading
			handleFontLoading( typography, 'ALL HEADINGS' );
		} );
	} );

	// Post/Page Title Typography Live Preview
	wp.customize( 'accepta_post_title_typography', function( value ) {
		value.bind( function( newval ) {
			var typography = {};
			try {
				typography = JSON.parse( newval );
			} catch (e) {
				typography = {};
			}
			
			var css = generateTypographyCSS( typography, '.entry-title' );
			updateDynamicCSS( 'post-title-typography', css );
			
			// Handle font loading
			handleFontLoading( typography, 'POST TITLE' );
		} );
	} );

	// Individual Heading Typography Live Preview (Overrides)
	wp.customize( 'accepta_h1_typography', function( value ) {
		value.bind( function( newval ) {
			var typography = {};
			try {
				typography = JSON.parse( newval );
			} catch (e) {
				typography = {};
			}
			
			var css = generateTypographyCSS( typography, 'h1, .entry-title' );
			updateDynamicCSS( 'h1-typography', css );
			
			// Load Google Font if needed (only for non-system fonts)
			if ( typography.font_family && typography.font_family !== '' && ! isSystemFont( typography.font_family ) ) {
				loadGoogleFontInPreview( typography.font_family );
			}
		} );
	} );

	wp.customize( 'accepta_h2_typography', function( value ) {
		value.bind( function( newval ) {
			var typography = {};
			try {
				typography = JSON.parse( newval );
			} catch (e) {
				typography = {};
			}
			
			var css = generateTypographyCSS( typography, 'h2' );
			updateDynamicCSS( 'h2-typography', css );
			
			// Handle font loading
			handleFontLoading( typography, 'H2 TYPOGRAPHY' );
		} );
	} );

	wp.customize( 'accepta_h3_typography', function( value ) {
		value.bind( function( newval ) {
			var typography = {};
			try {
				typography = JSON.parse( newval );
			} catch (e) {
				typography = {};
			}
			
			var css = generateTypographyCSS( typography, 'h3' );
			updateDynamicCSS( 'h3-typography', css );
			
			// Load Google Font if needed (only for non-system fonts)
			if ( typography.font_family && typography.font_family !== '' && ! isSystemFont( typography.font_family ) ) {
				loadGoogleFontInPreview( typography.font_family );
			}
		} );
	} );

	wp.customize( 'accepta_h4_typography', function( value ) {
		value.bind( function( newval ) {
			var typography = {};
			try {
				typography = JSON.parse( newval );
			} catch (e) {
				typography = {};
			}
			
			var css = generateTypographyCSS( typography, 'h4' );
			updateDynamicCSS( 'h4-typography', css );
			
			// Load Google Font if needed (only for non-system fonts)
			if ( typography.font_family && typography.font_family !== '' && ! isSystemFont( typography.font_family ) ) {
				loadGoogleFontInPreview( typography.font_family );
			}
		} );
	} );

	wp.customize( 'accepta_h5_typography', function( value ) {
		value.bind( function( newval ) {
			var typography = {};
			try {
				typography = JSON.parse( newval );
			} catch (e) {
				typography = {};
			}
			
			var css = generateTypographyCSS( typography, 'h5' );
			updateDynamicCSS( 'h5-typography', css );
			
			// Load Google Font if needed (only for non-system fonts)
			if ( typography.font_family && typography.font_family !== '' && ! isSystemFont( typography.font_family ) ) {
				loadGoogleFontInPreview( typography.font_family );
			}
		} );
	} );

	wp.customize( 'accepta_h6_typography', function( value ) {
		value.bind( function( newval ) {
			var typography = {};
			try {
				typography = JSON.parse( newval );
			} catch (e) {
				typography = {};
			}
			
			var css = generateTypographyCSS( typography, 'h6' );
			updateDynamicCSS( 'h6-typography', css );
			
			// Load Google Font if needed (only for non-system fonts)
			if ( typography.font_family && typography.font_family !== '' && ! isSystemFont( typography.font_family ) ) {
				loadGoogleFontInPreview( typography.font_family );
			}
		} );
	} );

	// Button Typography Live Preview
	wp.customize( 'accepta_button_typography', function( value ) {
		value.bind( function( newval ) {
			var typography = {};
			try {
				typography = JSON.parse( newval );
			} catch (e) {
				typography = {};
			}
			
			var css = generateTypographyCSS( typography, 'button, .button, input[type="button"], input[type="reset"], input[type="submit"]' );
			updateDynamicCSS( 'button-typography', css );
			
			// Load Google Font if needed (only for non-system fonts)
			if ( typography.font_family && typography.font_family !== '' && ! isSystemFont( typography.font_family ) ) {
				loadGoogleFontInPreview( typography.font_family );
			}
		} );
	} );

	// Responsive Device Preview Sync
	function syncResponsivePreview( device ) {
		// Get the customizer preview device buttons
		var $desktopBtn = $( '.wp-full-overlay-footer .devices button[data-device="desktop"]' );
		var $tabletBtn = $( '.wp-full-overlay-footer .devices button[data-device="tablet"]' );
		var $mobileBtn = $( '.wp-full-overlay-footer .devices button[data-device="mobile"]' );
		
		// Remove active class from all buttons
		$desktopBtn.removeClass( 'active' );
		$tabletBtn.removeClass( 'active' );
		$mobileBtn.removeClass( 'active' );
		
		// Add active class and trigger click on the appropriate button
		switch( device ) {
			case 'tablet':
				$tabletBtn.addClass( 'active' ).trigger( 'click' );
				break;
			case 'mobile':
				$mobileBtn.addClass( 'active' ).trigger( 'click' );
				break;
			default:
				$desktopBtn.addClass( 'active' ).trigger( 'click' );
				break;
		}
	}

	// Helper function to update dynamic CSS
	function updateDynamicCSS( styleId, css ) {
		var fullStyleId = 'accepta-customizer-' + styleId;
		
		// Remove existing style
		var existingStyle = $( '#' + fullStyleId );
		if ( existingStyle.length > 0 ) {
			existingStyle.remove();
		}
		
		// Add new style at the end of head to ensure it overrides backend CSS
		if ( css ) {
			// Try to insert after the backend dynamic CSS if it exists
			var $backendCSS = $( '#accepta-dynamic-css' );
			if ( $backendCSS.length > 0 ) {
				$backendCSS.after( '<style type="text/css" id="' + fullStyleId + '">' + css + '</style>' );
			} else {
				$( 'head' ).append( '<style type="text/css" id="' + fullStyleId + '">' + css + '</style>' );
			}
		}
	}

	// Helper function to check if font is a system font
	function isSystemFont( fontFamily ) {
		var systemFonts = [
			'Arial', 'Helvetica', 'Times New Roman', 'Times', 'Courier New', 'Courier',
			'Verdana', 'Georgia', 'Palatino', 'Garamond', 'Bookman', 'Comic Sans MS',
			'Trebuchet MS', 'Arial Black', 'Impact', 'Lucida Sans Unicode', 'Tahoma',
			'Lucida Console', 'Monaco', 'Brush Script MT', 'Copperplate', 'Papyrus'
		];
		return systemFonts.indexOf( fontFamily ) !== -1;
	}

	// Helper function to handle font loading logic
	function handleFontLoading( typography, controlName ) {
		if ( typography.font_family && typography.font_family !== '' ) {
			var cleanFontName = typography.font_family.split(',')[0].trim().replace(/['"]/g, '');
			
			if ( ! isSystemFont( cleanFontName ) ) {
				loadGoogleFontInPreview( typography.font_family );
			}
		}
	}

	// Helper function to generate typography CSS
	function generateTypographyCSS( typography, selector ) {
		var css = '';
		var properties = [];
		
		if ( typography.font_family && typography.font_family !== '' ) {
			var fontFamily = typography.font_family;
			var cleanFontName = fontFamily.split(',')[0].trim().replace(/['"]/g, '');
			
			if ( isSystemFont( cleanFontName ) ) {
				// For system fonts, use the original font stack as-is
				properties.push( 'font-family: ' + fontFamily + '' );
			} else {
				// For Google fonts, use sans-serif fallback
				properties.push( 'font-family: "' + cleanFontName + '", sans-serif' );
			}
		}
		// Handle responsive font sizes
		if ( typography.font_size_desktop && typography.font_size_desktop !== '' ) {
			properties.push( 'font-size: ' + parseInt( typography.font_size_desktop ) + 'px' );
		} else if ( typography.font_size && typography.font_size !== '' ) {
			// Fallback to legacy font_size
			properties.push( 'font-size: ' + parseInt( typography.font_size ) + 'px' );
		}
		if ( typography.font_weight && typography.font_weight !== '' ) {
			properties.push( 'font-weight: ' + typography.font_weight + '' );
		}
		if ( typography.line_height && typography.line_height !== '' ) {
			properties.push( 'line-height: ' + typography.line_height + '' );
		}
		if ( typography.letter_spacing && typography.letter_spacing !== '' ) {
			properties.push( 'letter-spacing: ' + typography.letter_spacing + 'px' );
		}
		if ( typography.text_transform && typography.text_transform !== '' ) {
			properties.push( 'text-transform: ' + typography.text_transform + '' );
		}
		
		if ( properties.length > 0 ) {
			css = selector + ' { ' + properties.join( '; ' ) + '; }';
		}
		
		// Add responsive font size media queries
		if ( typography.font_size_tablet && typography.font_size_tablet !== '' ) {
			css += ' @media (max-width: 782px) { ' + selector + ' { font-size: ' + parseInt( typography.font_size_tablet ) + 'px; } }';
		}
		if ( typography.font_size_mobile && typography.font_size_mobile !== '' ) {
			css += ' @media (max-width: 600px) { ' + selector + ' { font-size: ' + parseInt( typography.font_size_mobile ) + 'px; } }';
		}
		
		return css;
	}

	// Helper function to load Google Fonts in preview
	function loadGoogleFontInPreview( fontFamily ) {
		if ( ! fontFamily || fontFamily === '' ) {
			return;
		}
		
		// Extract only the first font name (before any comma) and clean it
		var cleanFontName = fontFamily.split(',')[0].trim().replace(/['"]/g, '');
		
		// Skip if it's a system font
		if ( isSystemFont( cleanFontName ) ) {
			return;
		}
		
		// Check if font is already loaded by backend (accepta-google-fonts)
		var backendFonts = $( '#accepta-google-fonts-css' );
		if ( backendFonts.length > 0 ) {
			var backendUrl = backendFonts.attr( 'href' );
			if ( backendUrl && backendUrl.indexOf( encodeURIComponent( cleanFontName ) ) !== -1 ) {
				return;
			}
		}
		
		// Create a safe ID by removing all non-alphanumeric characters except hyphens
		var fontId = 'accepta-google-font-preview-' + cleanFontName.replace(/[^a-zA-Z0-9\s]/g, '').replace(/\s+/g, '-').toLowerCase();
		
		// Check if font is already loaded by preview
		if ( $( '#' + fontId ).length > 0 ) {
			return;
		}
		
		// Load font with common weights for better preview
		var fontUrl = 'https://fonts.googleapis.com/css2?family=' + 
		             encodeURIComponent( cleanFontName ) + ':ital,wght@0,300;0,400;0,500;0,600;0,700;1,400;1,700&display=swap';
		
		$( '<link>' )
			.attr( 'id', fontId )
			.attr( 'rel', 'stylesheet' )
			.attr( 'href', fontUrl )
			.appendTo( 'head' );
	}

	// Helper function to generate spacing CSS
	function generateSpacingCSS( spacing, selector, type ) {
		type = type || 'padding'; // Default to padding if not specified
		var css = '';
		var desktopCSS = [];
		var tabletCSS = [];
		var mobileCSS = [];
		
		// Helper function to process a device's spacing data
		function processDevice( deviceData, cssArray ) {
			if ( ! deviceData ) return;
			
			var unit = deviceData.unit || 'px';
			var sides = [ 'top', 'right', 'bottom', 'left' ];
			
			sides.forEach( function( side ) {
				// Allow 0 values and any non-empty string (including '0')
				if ( deviceData[ side ] !== undefined && deviceData[ side ] !== '' && deviceData[ side ] !== null ) {
					cssArray.push( type + '-' + side + ': ' + deviceData[ side ] + unit + '' );
				}
			} );
		}
		
		// Process each device
		processDevice( spacing.desktop, desktopCSS );
		processDevice( spacing.tablet, tabletCSS );
		processDevice( spacing.mobile, mobileCSS );
		
		// Build CSS with media queries
		if ( desktopCSS.length > 0 ) {
			css += selector + ' { ' + desktopCSS.join( '; ' ) + '; }';
		}
		
		if ( tabletCSS.length > 0 ) {
			css += '@media (min-width: 600px) and (max-width: 782px) { ' + selector + ' { ' + tabletCSS.join( '; ' ) + '; } }';
		}
		
		if ( mobileCSS.length > 0 ) {
			css += '@media (max-width: 599px) { ' + selector + ' { ' + mobileCSS.join( '; ' ) + '; } }';
		}
		
		return css;
	}

	// Overlay Header Live Preview
	wp.customize( 'accepta_transparent_header', function( value ) {
		value.bind( function( newval ) {
			var $header = $( '.site-header' );
			var isAdminBar = $( 'body' ).hasClass( 'admin-bar' );
			// Use WordPress CSS variable for admin bar height
			var adminBarTop = isAdminBar ? 'var(--wp-admin--admin-bar--height, ' + ( window.innerWidth > 782 ? '32px' : '46px' ) + ')' : '0';
			var stickyEnabled = wp.customize( 'accepta_sticky_header' ).get();
			var transparentColor = wp.customize( 'accepta_transparent_header_text_color' ).get();
			var transparentBorderColor = hexToRgba( transparentColor, 0.3 );
			var css = '';
			
			if ( newval ) {
				// Make header absolute to overlay hero using CSS
				css += '.site-header { position: absolute; top: 0; left: 0; right: 0; background-color: transparent; box-shadow: none; z-index: 1001; }';
				if ( isAdminBar ) {
					css += '.admin-bar .site-header { top: var(--wp-admin--admin-bar--height, 32px); }';
					css += '@media screen and (max-width: 782px) { .admin-bar .site-header { top: var(--wp-admin--admin-bar--height, 46px); } }';
				}
				
				// Apply transparent text color (only when not scrolled)
				css += '.site-header:not(.scrolled) .site-title a { color: ' + transparentColor + '; }';
				css += '.site-header:not(.scrolled) .site-description { color: ' + transparentColor + '; opacity: 0.8; }';
				css += '.site-header:not(.scrolled) .main-navigation a { color: ' + transparentColor + '; }';
				css += '.site-header:not(.scrolled) .menu-toggle { color: ' + transparentColor + '; }';
				css += '.site-header:not(.scrolled) .menu-toggle .icon-bar { background-color: ' + transparentColor + '; }';
				css += '.site-header:not(.scrolled) .header-social-icons .social-icon { color: ' + transparentColor + '; }';
				css += '.site-header:not(.scrolled) .header-search-toggle { color: ' + transparentColor + '; }';
				css += '.site-header:not(.scrolled) .header-search-toggle svg { color: ' + transparentColor + '; stroke: ' + transparentColor + '; }';
				css += '.site-header:not(.scrolled) .header-search-close { color: ' + transparentColor + '; }';
				
				// Apply logo filter
				css += '.site-header:not(.scrolled) .custom-logo-link img { filter: brightness(0) invert(1); }';
				
				// Overlay header social icons and search button border colors (when not scrolled) - use text color with 0.3 opacity
				css += '.site-header:not(.scrolled) .header-social-icons .social-icon { border-color: ' + transparentBorderColor + '; }';
				css += '.site-header:not(.scrolled) .header-social-icons .social-icon .social-icon-svg { filter: brightness(0) invert(1); }';
				css += '.site-header:not(.scrolled) .header-search-toggle { border-color: ' + transparentBorderColor + '; }';
				
				// Remove body padding when overlay is enabled
				css += 'body:not(.has-sticky-header) { padding-top: 0; }';
				css += '.accepta-hero-section { margin-top: 0; padding-top: 0; }';
				css += '.site-content { margin-top: 0; }';
			} else {
				// Reset overlay styles
				if ( stickyEnabled ) {
					// If sticky is enabled, restore sticky positioning
					css += '.site-header { position: sticky; top: 0; z-index: 1000; }';
					if ( isAdminBar ) {
						css += '.admin-bar .site-header { top: var(--wp-admin--admin-bar--height, 32px); }';
						css += '@media screen and (max-width: 782px) { .admin-bar .site-header { top: var(--wp-admin--admin-bar--height, 46px); } }';
					}
				} else {
					// If sticky is disabled, use relative
					css += '.site-header { position: relative; top: auto; z-index: 1000; }';
				}
				
				// Reset text colors only if not scrolled
				css += '.site-header:not(.scrolled) .site-title a { color: inherit; }';
				css += '.site-header:not(.scrolled) .site-description { color: inherit; opacity: 1; }';
				css += '.site-header:not(.scrolled) .main-navigation a { color: inherit; }';
				css += '.site-header:not(.scrolled) .menu-toggle { color: inherit; }';
				css += '.site-header:not(.scrolled) .menu-toggle .icon-bar { background-color: inherit; }';
				
				// Reset logo filter
				css += '.site-header:not(.scrolled) .custom-logo-link img { filter: none; }';
				
				// Reset social icons and search button to dark colors
				css += '.site-header:not(.scrolled) .header-social-icons .social-icon { color: #2c3e50; border-color: rgba(44, 62, 80, 0.2); }';
				css += '.site-header:not(.scrolled) .header-social-icons .social-icon .social-icon-svg { filter: none; }';
				css += '.site-header:not(.scrolled) .header-search-toggle { color: #2c3e50; border-color: rgba(44, 62, 80, 0.2); }';
				css += '.site-header:not(.scrolled) .header-search-toggle svg { color: #2c3e50; stroke: #2c3e50; }';
			}
			
			// Apply CSS via updateDynamicCSS for better preview compatibility
			updateDynamicCSS( 'overlay-header', css );
		} );
	} );

	// Overlay Header Text Color Live Preview
	wp.customize( 'accepta_transparent_header_text_color', function( value ) {
		value.bind( function( newval ) {
			if ( wp.customize( 'accepta_transparent_header' ).get() ) {
				var $header = $( '.site-header:not(.scrolled)' );
				var borderColor = hexToRgba( newval, 0.3 );
				
				$header.find( '.site-title a' ).css( 'color', newval );
				$header.find( '.site-description' ).css( 'color', newval );
				$header.find( '.main-navigation a' ).css( 'color', newval );
				$header.find( '.menu-toggle' ).css( 'color', newval );
				$header.find( '.menu-toggle .icon-bar' ).css( 'background-color', newval );
				$header.find( '.header-social-icons .social-icon' ).css( 'color', newval );
				$header.find( '.header-search-toggle' ).css( 'color', newval );
				$header.find( '.header-search-toggle svg' ).css( { 'color': newval, 'stroke': newval } );
				$header.find( '.header-search-close' ).css( 'color', newval );
				
				// Update border colors using text color with 0.3 opacity
				$header.find( '.header-social-icons .social-icon' ).css( 'border-color', borderColor );
				$header.find( '.header-search-toggle' ).css( 'border-color', borderColor );
				
				// Update CSS via updateDynamicCSS for better preview
				var css = '.site-header:not(.scrolled) .site-title a { color: ' + newval + '; }';
				css += '.site-header:not(.scrolled) .site-description { color: ' + newval + '; opacity: 0.8; }';
				css += '.site-header:not(.scrolled) .main-navigation a { color: ' + newval + '; }';
				css += '.site-header:not(.scrolled) .menu-toggle { color: ' + newval + '; }';
				css += '.site-header:not(.scrolled) .menu-toggle .icon-bar { background-color: ' + newval + '; }';
				css += '.site-header:not(.scrolled) .header-social-icons .social-icon { color: ' + newval + '; border-color: ' + borderColor + '; }';
				css += '.site-header:not(.scrolled) .header-search-toggle { color: ' + newval + '; border-color: ' + borderColor + '; }';
				css += '.site-header:not(.scrolled) .header-search-toggle svg { color: ' + newval + '; stroke: ' + newval + '; }';
				css += '.site-header:not(.scrolled) .header-search-close { color: ' + newval + '; }';
				updateDynamicCSS( 'overlay-header-text-color', css );
			}
		} );
	} );

	// Scrolled Header Background Color Live Preview
	wp.customize( 'accepta_scrolled_header_bg', function( value ) {
		value.bind( function( newval ) {
			// Get opacity value
			var opacity = wp.customize( 'accepta_scrolled_header_bg_opacity' ).get();
			opacity = parseFloat( opacity ) || 1;
			opacity = Math.min( Math.max( 0, opacity ), 1 ); // Clamp between 0 and 1
			
			// Convert to rgba
			var rgba = hexToRgba( newval, opacity );
			
			// Update the scrolled header background color (works for both overlay and non-overlay)
			var css = '.site-header.scrolled { background-color: ' + rgba + '; }';
			updateDynamicCSS( 'scrolled-header-bg', css );
			
			// Also update inline style if header is already scrolled
			var $header = $( '.site-header.scrolled' );
			if ( $header.length ) {
				$header.css( 'background-color', rgba );
			}
		} );
	} );

	// Scrolled Header Background Opacity Live Preview
	wp.customize( 'accepta_scrolled_header_bg_opacity', function( value ) {
		value.bind( function( newval ) {
			// Get background color
			var bgColor = wp.customize( 'accepta_scrolled_header_bg' ).get();
			var opacity = parseFloat( newval ) || 1;
			opacity = Math.min( Math.max( 0, opacity ), 1 ); // Clamp between 0 and 1
			
			// Convert to rgba
			var rgba = hexToRgba( bgColor, opacity );
			
			// Update the scrolled header background color
			var css = '.site-header.scrolled { background-color: ' + rgba + '; }';
			updateDynamicCSS( 'scrolled-header-bg', css );
			
			// Also update inline style if header is already scrolled
			var $header = $( '.site-header.scrolled' );
			if ( $header.length ) {
				$header.css( 'background-color', rgba );
			}
		} );
	} );

	// Scrolled Header Text Color Live Preview
	wp.customize( 'accepta_scrolled_header_text_color', function( value ) {
		value.bind( function( newval ) {
			// Apply to scrolled header regardless of overlay state (as long as sticky is enabled)
			var css = '.site-header.scrolled .site-title a { color: ' + newval + '; }';
			css += '.site-header.scrolled .site-description { color: ' + newval + '; opacity: 0.7; }';
			css += '.site-header.scrolled .main-navigation a { color: ' + newval + '; }';
			css += '.site-header.scrolled .menu-toggle { color: ' + newval + '; }';
			css += '.site-header.scrolled .menu-toggle .icon-bar { background-color: ' + newval + '; }';
			updateDynamicCSS( 'scrolled-header-text', css );
		} );
	} );

	// Header Width Live Preview
	wp.customize( 'accepta_header_width', function( value ) {
		value.bind( function( newval ) {
			var containerWidth = wp.customize( 'accepta_container_width' ).get();
			var css = '';
			
			if ( newval === 'fullwidth' ) {
				css = '.site-header .container { max-width: 100%; padding-left: 20px; padding-right: 20px; }';
				css += '.site-header { width: 100%; }';
			} else {
				// Boxed - use container width setting, keep padding to align with footer
				css = '.site-header .container { max-width: ' + containerWidth + 'px; padding-left: 20px; padding-right: 20px; }';
				css += '.site-header { width: auto; }';
			}
			
			updateDynamicCSS( 'header-width', css );
		} );
		
		// Also update when container width changes (for boxed header)
		wp.customize( 'accepta_container_width', function( containerValue ) {
			containerValue.bind( function( containerWidth ) {
				var currentWidth = value.get();
				var css = '';
				
				if ( currentWidth === 'fullwidth' ) {
					css = '.site-header .container { max-width: 100%; padding-left: 20px; padding-right: 20px; }';
					css += '.site-header { width: 100%; }';
				} else {
					// Boxed - keep padding to align with footer
					css = '.site-header .container { max-width: ' + containerWidth + 'px; padding-left: 20px; padding-right: 20px; }';
					css += '.site-header { width: auto; }';
				}
				
				updateDynamicCSS( 'header-width', css );
			} );
		} );
	} );

	// Header Layout Live Preview
	wp.customize( 'accepta_header_layout', function( value ) {
		value.bind( function( newval ) {
			var $headerContent = $( '.header-content' );
			// Remove all layout classes (including any with double 'layout-' prefix)
			$headerContent.removeClass( function( index, className ) {
				// Remove all classes that start with 'header-layout-'
				return ( className.match( /(^|\s)header-layout-\S+/g ) || [] ).join( ' ' );
			} );
			// Remove 'layout-' prefix if present to avoid double prefix
			var layoutSuffix = newval.replace( 'layout-', '' );
			// Add new layout class
			$headerContent.addClass( 'header-layout-' + layoutSuffix );
			
			// Apply layout-specific styles via CSS (use layoutSuffix for class names)
			var css = '';
			if ( newval === 'layout-1' || layoutSuffix === '1' ) {
				css = '.header-content.header-layout-1 { justify-content: space-between; align-items: center; width: 100%; min-width: 0; }';
				css += '.header-content.header-layout-1 .site-branding { order: 1; flex: 0 0 auto; min-width: 0; }';
				css += '.header-content.header-layout-1 .main-navigation { order: 2; margin-left: 0; margin-right: 20px; justify-content: flex-end; flex: 1 1 auto; min-width: 0; max-width: 100%; }';
				css += '.header-content.header-layout-1 .main-navigation ul { justify-content: flex-end; margin-left: 0; flex-wrap: wrap; min-width: 0; }';
				css += '.header-content.header-layout-1 .header-social-icons { order: 3; flex: 0 0 auto; min-width: 0; flex-shrink: 0; }';
				css += '.header-content.header-layout-1 .header-search-toggle { order: 4; margin-left: 10px; flex: 0 0 auto; flex-shrink: 0; }';
			} else if ( newval === 'layout-2' || layoutSuffix === '2' ) {
				css = '.header-content.header-layout-2 { justify-content: space-between; align-items: center; width: 100%; min-width: 0; box-sizing: border-box; }';
				css += '.header-content.header-layout-2 .site-branding { order: 1; margin-right: 0; flex: 0 0 auto; min-width: 0; flex-shrink: 0; }';
				css += '.header-content.header-layout-2 .main-navigation { order: 1; margin-left: 20px; margin-right: 0; justify-content: flex-start; flex: 1 1 auto; min-width: 0; max-width: none; position: relative; z-index: 1; }';
				css += '.header-content.header-layout-2 .main-navigation ul { justify-content: flex-start; margin-left: 0; flex-wrap: wrap; min-width: 0; }';
				css += '.header-content.header-layout-2 .header-social-icons { order: 2; margin-left: auto; flex: 0 0 auto; min-width: 0; flex-shrink: 0; position: relative; z-index: 2; max-width: 100%; }';
				css += '.header-content.header-layout-2 .header-search-toggle { order: 3; margin-left: 10px; flex: 0 0 auto; flex-shrink: 0; position: relative; z-index: 3; max-width: 100%; }';
			} else if ( newval === 'layout-3' || layoutSuffix === '3' ) {
				css = '.header-content.header-layout-3 { justify-content: space-between; position: relative; align-items: center; min-height: 60px; }';
				css += '.header-content.header-layout-3 .site-branding { order: 1; }';
				css += '.header-content.header-layout-3 .main-navigation { order: 2; position: absolute; left: 50%; top: 50%; transform: translate(-50%, -50%); margin-left: 0; margin-right: 0; justify-content: center; flex: 0 0 auto; width: auto; }';
				css += '@media screen and (min-width: 768px) { .header-content.header-layout-3 .main-navigation { display: flex; align-items: center; justify-content: center; margin-left: 0; flex: 0 0 auto; } }';
				css += '.header-content.header-layout-3 .main-navigation ul { justify-content: center; margin-left: 0; }';
				css += '@media screen and (min-width: 768px) { .header-content.header-layout-3 .main-navigation ul { display: flex; justify-content: center; margin-left: 0; } }';
				css += '.header-content.header-layout-3 .header-social-icons { order: 3; margin-left: auto; }';
				css += '.header-content.header-layout-3 .header-search-toggle { order: 4; margin-left: 10px; }';
			}
			
			updateDynamicCSS( 'header-layout', css );
			
			// Always show navigation for layouts 1-3
			var $nav = $headerContent.find( '.main-navigation' );
			$nav.show();
		} );
	} );

	// Header Social Icons Live Preview
	wp.customize( 'accepta_display_header_social_icons', function( value ) {
		value.bind( function( newval ) {
			var $headerSocial = $( '.header-social-icons' );
			if ( newval ) {
				// Check if social icons exist, if not, create them
				if ( $headerSocial.length === 0 ) {
					var $headerContent = $( '.header-content' );
					var socialHtml = '<div class="header-social-icons"><div class="social-icons"></div></div>';
					$headerContent.append( socialHtml );
					$headerSocial = $( '.header-social-icons' );
				}
				// Update social icons from header social media setting
				var socialData = wp.customize( 'accepta_header_social_media' ).get();
				updateHeaderSocialIcons( socialData );
			} else {
				$headerSocial.hide();
			}
		} );
	} );

	// Header Social Media Links Live Preview
	wp.customize( 'accepta_header_social_media', function( value ) {
		value.bind( function( newval ) {
			var $headerSocial = $( '.header-social-icons' );
			var displayEnabled = wp.customize( 'accepta_display_header_social_icons' ).get();
			
			if ( displayEnabled ) {
				// Create container if it doesn't exist
				if ( $headerSocial.length === 0 ) {
					var $headerContent = $( '.header-content' );
					var socialHtml = '<div class="header-social-icons"><div class="social-icons"></div></div>';
					$headerContent.append( socialHtml );
					$headerSocial = $( '.header-social-icons' );
				}
				updateHeaderSocialIcons( newval );
			}
		} );
	} );

	// Helper function to update header social icons
	function updateHeaderSocialIcons( socialData ) {
		var $socialContainer = $( '.header-social-icons .social-icons' );
		var socialItems = [];
		
		// Parse the social media data
		try {
			if ( typeof socialData === 'string' ) {
				socialItems = JSON.parse( socialData );
			} else {
				socialItems = socialData;
			}
		} catch ( e ) {
			socialItems = [];
		}

		// Clear existing icons
		$socialContainer.empty();

		// Add new icons
		if ( socialItems && socialItems.length > 0 ) {
			socialItems.forEach( function( item ) {
				if ( item.label && item.url ) {
					var iconHtml = '';
					var iconType = item.icon_type || 'fontawesome';
					var cssClass = item.label.toLowerCase().replace(/[^a-z0-9]/g, '');

					// Build icon HTML based on type
					if ( iconType === 'custom' && item.custom_icon ) {
						iconHtml = '<img src="' + item.custom_icon + '" alt="' + item.label + '" class="social-icon-svg" />';
					} else if ( iconType === 'fontawesome' && item.icon ) {
						iconHtml = '<i class="' + item.icon + '" aria-hidden="true"></i>';
					} else {
						iconHtml = '<span class="social-icon-text">' + item.label.charAt(0) + '</span>';
					}

					var linkHtml = '<a href="' + item.url + '" class="social-icon ' + cssClass + '" target="_blank" rel="noopener noreferrer" title="' + item.label + '">' +
						iconHtml +
						'<span class="screen-reader-text">' + item.label + '</span>' +
						'</a>';

					$socialContainer.append( linkHtml );
				}
			} );

			// Show the social icons section
			$( '.header-social-icons' ).show();
		} else {
			// Hide the social icons section if no items
			$( '.header-social-icons' ).hide();
		}
	}

})( jQuery );