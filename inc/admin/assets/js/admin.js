/**
 * Accepta Admin JavaScript
 */
(function($) {
    'use strict';

    // Initialize when the DOM is ready
    $(document).ready(function() {
        // Install/Activate plugin via AJAX (plugins page)
        $(document).on('click', '.js-accepta-plugin-btn', function(e) {
            var $button = $(this);
            if ($button.prop('disabled') || $button.hasClass('button-disabled')) {
                e.preventDefault();
                return false;
            }
            e.preventDefault();
            if (typeof accepta_plugins_vars === 'undefined') {
                return;
            }
            var slug = $button.data('slug');
            var $item = $button.closest('.accepta-plugin-item');
            var $error = $item.find('.accepta-plugin-item-error');
            var initialText = $button.data('initial-text') || $button.text();
            var isInstalled = $button.text().indexOf('Activate') !== -1;
            $error.empty();
            $button.prop('disabled', true);
            $item.addClass('accepta-plugin-item--loading');
            var $loadingState = $item.find('.accepta-plugin-loading-state');
            var loadingText = isInstalled ? (accepta_plugins_vars.activating || 'Activating...') : (accepta_plugins_vars.installing || 'Installing...');
            $loadingState.find('.accepta-plugin-loading-text').text(loadingText);
            $loadingState.show().attr('aria-hidden', 'false');
            $button.hide();
            $.ajax({
                url: typeof ajaxurl !== 'undefined' ? ajaxurl : '',
                type: 'POST',
                data: {
                    action: 'accepta_install_activate_plugin',
                    security: accepta_plugins_vars.nonce,
                    slug: slug
                }
            }).done(function(response) {
                $item.removeClass('accepta-plugin-item--loading');
                $item.find('.accepta-plugin-loading-state').hide().attr('aria-hidden', 'true');
                $button.show().prop('disabled', false);
                if (response.success) {
                    $item.addClass('accepta-plugin-item--active');
                    $button.text(accepta_plugins_vars.active).addClass('button-disabled accepta-plugin-btn--active').prop('disabled', true);
                    $item.find('.accepta-plugin-checkbox-label').remove();
                    updateInstallSelectedButtonState();
                } else {
                    $button.text(initialText);
                    $error.html('<p class="accepta-plugin-error">' + (response.data || '') + '</p>');
                }
            }).fail(function(xhr, status, err) {
                $item.removeClass('accepta-plugin-item--loading');
                $item.find('.accepta-plugin-loading-state').hide().attr('aria-hidden', 'true');
                $button.show().prop('disabled', false);
                $button.text(initialText);
                var msg = (xhr.responseJSON && xhr.responseJSON.data) ? xhr.responseJSON.data : (err || status);
                $error.html('<p class="accepta-plugin-error">' + msg + '</p>');
            });
        });

        function updateInstallSelectedButtonState() {
            var $btn = $('#accepta-install-selected-btn');
            if (!$btn.length) return;
            var checkedCount = $('.accepta-plugin-checkbox:checked').length;
            var noneSelected = checkedCount === 0;
            $btn.prop('disabled', noneSelected);
            if (noneSelected) $btn.addClass('button-disabled');
            else $btn.removeClass('button-disabled');
        }

        $(document).on('change', '.accepta-plugin-checkbox', function() {
            updateInstallSelectedButtonState();
        });

        function acceptaInstallPluginsBulk(slugs, index, $allButton) {
            if (index >= slugs.length) {
                $allButton.prop('disabled', false).removeClass('button-disabled').text(accepta_plugins_vars.installSelected || 'Install & Activate Selected Plugins');
                updateInstallSelectedButtonState();
                return;
            }
            var slug = slugs[index];
            var $item = $('.accepta-plugin-item-' + slug);
            var $button = $item.find('.js-accepta-plugin-btn');
            var $error = $item.find('.accepta-plugin-item-error');
            var initialText = $button.data('initial-text') || $button.text();
            var isInstalled = $button.text().indexOf('Activate') !== -1;
            $error.empty();
            $button.prop('disabled', true);
            $item.addClass('accepta-plugin-item--loading');
            var $loadingState = $item.find('.accepta-plugin-loading-state');
            var loadingText = isInstalled ? (accepta_plugins_vars.activating || 'Activating...') : (accepta_plugins_vars.installing || 'Installing...');
            $loadingState.find('.accepta-plugin-loading-text').text(loadingText);
            $loadingState.show().attr('aria-hidden', 'false');
            $button.hide();
            $.ajax({
                url: typeof ajaxurl !== 'undefined' ? ajaxurl : '',
                type: 'POST',
                data: {
                    action: 'accepta_install_activate_plugin',
                    security: accepta_plugins_vars.nonce,
                    slug: slug
                }
            }).done(function(response) {
                $item.removeClass('accepta-plugin-item--loading');
                $item.find('.accepta-plugin-loading-state').hide().attr('aria-hidden', 'true');
                $button.show().prop('disabled', false);
                if (response.success) {
                    $item.addClass('accepta-plugin-item--active');
                    $button.text(accepta_plugins_vars.active).addClass('button-disabled accepta-plugin-btn--active').prop('disabled', true);
                    $item.find('.accepta-plugin-checkbox-label').remove();
                } else {
                    $button.text(initialText);
                    $error.html('<p class="accepta-plugin-error">' + (response.data || '') + '</p>');
                }
                acceptaInstallPluginsBulk(slugs, index + 1, $allButton);
            }).fail(function(xhr) {
                $item.removeClass('accepta-plugin-item--loading');
                $item.find('.accepta-plugin-loading-state').hide().attr('aria-hidden', 'true');
                $button.show().prop('disabled', false);
                $button.text(initialText);
                var msg = (xhr.responseJSON && xhr.responseJSON.data) ? xhr.responseJSON.data : (xhr.statusText || 'Error');
                $error.html('<p class="accepta-plugin-error">' + msg + '</p>');
                acceptaInstallPluginsBulk(slugs, index + 1, $allButton);
            });
        }

        $(document).on('click', '.js-accepta-install-selected', function(e) {
            e.preventDefault();
            if (typeof accepta_plugins_vars === 'undefined') return;
            var $btn = $(this);
            if ($btn.prop('disabled')) return;
            var slugs = [];
            $('.accepta-plugin-checkbox:checked').each(function() {
                var s = $(this).data('slug') || $(this).attr('name');
                if (s) slugs.push(s);
            });
            if (slugs.length === 0) return;
            $btn.prop('disabled', true).addClass('button-disabled').text(accepta_plugins_vars.installing || 'Installing...');
            acceptaInstallPluginsBulk(slugs, 0, $btn);
        });

        updateInstallSelectedButtonState();

        // Legacy: handle plugin activation links (non-AJAX fallback)
        $('.accepta-plugin-actions .button:not(.js-accepta-plugin-btn)').on('click', function(e) {
            var $button = $(this);
            if ($button.hasClass('button-disabled')) {
                e.preventDefault();
                return false;
            }
            if ($button.text().indexOf('Activate') !== -1) {
                e.preventDefault();
                var href = $button.attr('href');
                window.location.href = href ? (href.indexOf('?') !== -1 ? href + '&action=activate' : href + '?action=activate') : '#';
            }
        });
        
        // Add smooth scroll to internal links
        $('.accepta-admin-wrap a[href^="#"]').on('click', function(e) {
            e.preventDefault();
            
            $('html, body').animate({
                scrollTop: $($(this).attr('href')).offset().top - 50
            }, 500);
        });
        
        // Initialize tooltips if any
        if (typeof $.fn.tipTip !== 'undefined') {
            $('.accepta-tooltip').tipTip({
                attribute: 'data-tip',
                fadeIn: 50,
                fadeOut: 50,
                delay: 200
            });
        }
        
        // Fix tab navigation highlighting
        var currentUrl = window.location.href;
        $('.accepta-admin-tabs a').each(function() {
            var tabUrl = $(this).attr('href');
            if (currentUrl.indexOf(tabUrl) !== -1) {
                $(this).addClass('nav-tab-active');
            }
        });
        
        // Enhance welcome page actions
        $('.accepta-welcome-actions .button').hover(function() {
            $(this).addClass('animated-button');
        }, function() {
            $(this).removeClass('animated-button');
        });
    });
    
})(jQuery); 