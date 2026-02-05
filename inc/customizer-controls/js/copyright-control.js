/**
 * Accepta Copyright Control JavaScript
 * Handles the interactive functionality for the copyright control
 */

(function($) {
    'use strict';

    // Initialize copyright controls when customizer is ready
    wp.customize.bind('ready', function() {
        initializeCopyrightControls();
    });

    function initializeCopyrightControls() {
        $('.accepta-copyright-control').each(function() {
            var $control = $(this);
            var controlId = $control.data('control-id');
            
            initializeCopyrightControl($control, controlId);
        });
    }

    function initializeCopyrightControl($control, controlId) {
        var $textarea = $control.find('.accepta-copyright-textarea');
        var $preview = $control.find('.accepta-copyright-preview-content');
        var $tagButtons = $control.find('.accepta-copyright-tag-btn');

        // Remove any existing event handlers to prevent duplicates
        $tagButtons.off('click.accepta-copyright');
        $textarea.off('input.accepta-copyright change.accepta-copyright');

        // Handle tag button clicks with namespaced events
        $tagButtons.on('click.accepta-copyright', function(e) {
            e.preventDefault();
            e.stopPropagation();
            var tag = $(this).data('tag');
            insertTag($textarea, tag);
            updatePreview($control);
        });

        // Handle textarea input with namespaced events
        $textarea.on('input.accepta-copyright change.accepta-copyright', function() {
            updatePreview($control);
        });

        // Initialize preview
        updatePreview($control);
    }

    function insertTag($textarea, tag) {
        var textarea = $textarea[0];
        var startPos = textarea.selectionStart;
        var endPos = textarea.selectionEnd;
        var textBefore = textarea.value.substring(0, startPos);
        var textAfter = textarea.value.substring(endPos);
        
        // Insert the tag at cursor position
        var newValue = textBefore + tag + textAfter;
        textarea.value = newValue;
        
        // Set cursor position after the inserted tag
        var newCursorPos = startPos + tag.length;
        textarea.setSelectionRange(newCursorPos, newCursorPos);
        
        // Focus back to textarea
        textarea.focus();
        
        // Add visual feedback
        $textarea.addClass('tag-inserted');
        setTimeout(function() {
            $textarea.removeClass('tag-inserted');
        }, 500);
        
        // Trigger change event for customizer
        $textarea.trigger('input').trigger('change');
    }

    function updatePreview($control) {
        var $textarea = $control.find('.accepta-copyright-textarea');
        var $preview = $control.find('.accepta-copyright-preview-content');
        var text = $textarea.val();
        
        if (!text) {
            $preview.html('<em style="color: #999;">Enter copyright text to see preview...</em>');
            return;
        }

        // Process tags for preview
        var processedText = processTagsForPreview(text);
        $preview.html(processedText);
    }

    function processTagsForPreview(text) {
        // Get current site info for preview
        var siteTitle = wp.customize('blogname')() || 'Your Site Title';
        var currentYear = new Date().getFullYear();
        
        var replacements = {
            '{copyright}': '©',
            '{current-year}': currentYear,
            '{site-title}': siteTitle,
            '{site-url}': '<a href="#" onclick="return false;">' + siteTitle + '</a>',
            '{theme-name}': 'Accepta',
            '{theme-author}': '<a href="#" onclick="return false;">WPDINO</a>',
            '{wordpress}': '<a href="#" onclick="return false;">WordPress</a>'
        };

        var processedText = text;
        
        // Replace each tag
        Object.keys(replacements).forEach(function(tag) {
            var regex = new RegExp(escapeRegExp(tag), 'g');
            processedText = processedText.replace(regex, replacements[tag]);
        });

        return processedText;
    }

    function escapeRegExp(string) {
        return string.replace(/[.*+?^${}()|[\]\\]/g, '\\$&');
    }

    // Add control to customizer control types
    wp.customize.controlConstructor['accepta-copyright'] = wp.customize.Control.extend({
        ready: function() {
            var control = this;
            var $control = this.container.find('.accepta-copyright-control');
            
            initializeCopyrightControl($control, control.id);
            
            // Update preview when site title changes
            wp.customize('blogname', function(value) {
                value.bind(function() {
                    updatePreview($control);
                });
            });
        }
    });

})(jQuery);
