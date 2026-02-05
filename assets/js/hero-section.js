/**
 * Hero Section Functionality
 * Handles video backgrounds (YouTube, Vimeo, MP4) and video controls
 */

(function() {
    'use strict';

    /**
     * Initialize hero section
     */
    function initHeroSection() {
        // Handle YouTube/Vimeo video embeds
        const videoEmbeds = document.querySelectorAll('.accepta-hero-video-embed');
        videoEmbeds.forEach(function(embed) {
            const videoType = embed.getAttribute('data-video-type');
            const videoUrl = embed.getAttribute('data-video-url');
            const autoplay = embed.getAttribute('data-autoplay') === '1';
            const loop = embed.getAttribute('data-loop') === '1';
            const muted = embed.getAttribute('data-muted') === '1';
            const controls = embed.getAttribute('data-controls') === '1';

            if (!videoUrl) return;

            let embedUrl = '';
            let videoId = '';

            if (videoType === 'youtube') {
                // Extract YouTube video ID
                const youtubeRegex = /(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/\s]{11})/;
                const match = videoUrl.match(youtubeRegex);
                if (match && match[1]) {
                    videoId = match[1];
                    embedUrl = 'https://www.youtube.com/embed/' + videoId + '?';
                    embedUrl += 'autoplay=' + (autoplay ? '1' : '0');
                    embedUrl += '&loop=' + (loop ? '1' : '0');
                    embedUrl += '&mute=' + (muted ? '1' : '0');
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
                    if (loop) {
                        embedUrl += '&playlist=' + videoId;
                    }
                }
            } else if (videoType === 'vimeo') {
                // Extract Vimeo video ID
                const vimeoRegex = /(?:vimeo\.com\/)(\d+)/;
                const match = videoUrl.match(vimeoRegex);
                if (match && match[1]) {
                    videoId = match[1];
                    embedUrl = 'https://player.vimeo.com/video/' + videoId + '?';
                    embedUrl += 'autoplay=' + (autoplay ? '1' : '0');
                    embedUrl += '&loop=' + (loop ? '1' : '0');
                    embedUrl += '&muted=' + (muted ? '1' : '0');
                    embedUrl += '&background=1';
                    embedUrl += '&controls=0'; // Always hide Vimeo controls, we use custom ones
                    embedUrl += '&api=1'; // Enable Vimeo API for postMessage
                }
            }

            if (embedUrl) {
                const iframe = document.createElement('iframe');
                iframe.src = embedUrl;
                iframe.frameBorder = '0';
                iframe.allow = 'autoplay; encrypted-media';
                iframe.allowFullscreen = true;
                iframe.style.width = '100%';
                iframe.style.height = '100%';
                embed.appendChild(iframe);
            }
        });

        // Handle YouTube/Vimeo embed controls
        const embedControls = document.querySelectorAll('.accepta-hero-video-embed-controls');
        embedControls.forEach(function(controlsContainer) {
            const heroSection = controlsContainer.closest('.accepta-hero-section');
            const embedContainer = heroSection?.querySelector('.accepta-hero-video-background');
            const embed = embedContainer?.querySelector('.accepta-hero-video-embed');
            const iframe = embed?.querySelector('iframe');
            
            if (!iframe) return;
            
            const playPauseBtn = controlsContainer.querySelector('.accepta-hero-video-play-pause');
            const muteUnmuteBtn = controlsContainer.querySelector('.accepta-hero-video-mute-unmute');
            
            // Play/pause for YouTube/Vimeo (using postMessage API)
            if (playPauseBtn) {
                let isPlaying = true; // Assume playing if autoplay is enabled
                playPauseBtn.addEventListener('click', function() {
                    if (iframe.src.includes('youtube.com')) {
                        // YouTube API
                        if (isPlaying) {
                            iframe.contentWindow.postMessage('{"event":"command","func":"pauseVideo","args":""}', '*');
                            isPlaying = false;
                            playPauseBtn.classList.remove('paused');
                        } else {
                            iframe.contentWindow.postMessage('{"event":"command","func":"playVideo","args":""}', '*');
                            isPlaying = true;
                            playPauseBtn.classList.add('paused');
                        }
                    } else if (iframe.src.includes('vimeo.com')) {
                        // Vimeo API
                        if (isPlaying) {
                            iframe.contentWindow.postMessage('{"method":"pause"}', '*');
                            isPlaying = false;
                            playPauseBtn.classList.remove('paused');
                        } else {
                            iframe.contentWindow.postMessage('{"method":"play"}', '*');
                            isPlaying = true;
                            playPauseBtn.classList.add('paused');
                        }
                    }
                });
            }
            
            // Mute/unmute for YouTube/Vimeo
            if (muteUnmuteBtn) {
                let isMuted = embed?.getAttribute('data-muted') === '1';
                muteUnmuteBtn.addEventListener('click', function() {
                    if (iframe.src.includes('youtube.com')) {
                        // YouTube API
                        if (isMuted) {
                            iframe.contentWindow.postMessage('{"event":"command","func":"unMute","args":""}', '*');
                            isMuted = false;
                            muteUnmuteBtn.classList.add('unmuted');
                        } else {
                            iframe.contentWindow.postMessage('{"event":"command","func":"mute","args":""}', '*');
                            isMuted = true;
                            muteUnmuteBtn.classList.remove('unmuted');
                        }
                    } else if (iframe.src.includes('vimeo.com')) {
                        // Vimeo API
                        if (isMuted) {
                            iframe.contentWindow.postMessage('{"method":"setVolume","value":1}', '*');
                            isMuted = false;
                            muteUnmuteBtn.classList.add('unmuted');
                        } else {
                            iframe.contentWindow.postMessage('{"method":"setVolume","value":0}', '*');
                            isMuted = true;
                            muteUnmuteBtn.classList.remove('unmuted');
                        }
                    }
                });
                
                // Set initial state
                if (isMuted) {
                    muteUnmuteBtn.classList.remove('unmuted');
                } else {
                    muteUnmuteBtn.classList.add('unmuted');
                }
            }
        });

        // Handle MP4 video controls
        const videoElements = document.querySelectorAll('.accepta-hero-video-element');
        videoElements.forEach(function(video) {
            // Set muted state from data attribute or ensure it's set correctly
            const mutedAttr = video.getAttribute('data-muted');
            if (mutedAttr === '1') {
                video.muted = true;
            } else if (mutedAttr === '0') {
                video.muted = false;
            }
            
            const heroSection = video.closest('.accepta-hero-section');
            const controlsContainer = heroSection?.querySelector('.accepta-hero-video-controls');
            
            if (!controlsContainer) return;
            
            // Play/pause button functionality
            const playPauseBtn = controlsContainer.querySelector('.accepta-hero-video-play-pause');
            if (playPauseBtn) {
                playPauseBtn.addEventListener('click', function() {
                    if (video.paused) {
                        video.play();
                        playPauseBtn.classList.add('paused');
                    } else {
                        video.pause();
                        playPauseBtn.classList.remove('paused');
                    }
                });

                // Update button state based on video state
                video.addEventListener('play', function() {
                    playPauseBtn.classList.add('paused');
                });
                video.addEventListener('pause', function() {
                    playPauseBtn.classList.remove('paused');
                });
                
                // Set initial state
                if (!video.paused) {
                    playPauseBtn.classList.add('paused');
                }
            }

            // Mute/unmute button functionality
            const muteUnmuteBtn = controlsContainer.querySelector('.accepta-hero-video-mute-unmute');
            if (muteUnmuteBtn) {
                muteUnmuteBtn.addEventListener('click', function() {
                    if (video.muted) {
                        video.muted = false;
                        muteUnmuteBtn.classList.add('unmuted');
                    } else {
                        video.muted = true;
                        muteUnmuteBtn.classList.remove('unmuted');
                    }
                });

                // Update button state based on video muted state
                video.addEventListener('volumechange', function() {
                    if (video.muted) {
                        muteUnmuteBtn.classList.remove('unmuted');
                    } else {
                        muteUnmuteBtn.classList.add('unmuted');
                    }
                });
                
                // Set initial state
                if (!video.muted) {
                    muteUnmuteBtn.classList.add('unmuted');
                }
            }
        });
    }

    // Initialize when DOM is ready
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initHeroSection);
    } else {
        initHeroSection();
    }
})();

