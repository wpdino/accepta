/**
 * Accepta Repeater Control JavaScript
 * Handles the interactive functionality for the repeater control
 */

(function($) {
    'use strict';

    function initializeRepeaterControl($control, controlId) {
        // Prevent double initialization
        if ($control.data('initialized')) {
            return;
        }
        $control.data('initialized', true);

        var $input = $control.find('.accepta-repeater-input');
        var $itemsContainer = $control.find('.accepta-repeater-items');
        var $addButton = $control.find('.accepta-repeater-add-item');
        var maxItems = parseInt($control.data('max-items')) || 10;

        // Remove any existing event handlers to prevent duplicates
        $addButton.off('click.accepta-repeater');
        $itemsContainer.off('click.accepta-repeater');

        // Add new item
        $addButton.on('click.accepta-repeater', function(e) {
            e.preventDefault();
            e.stopPropagation();
            
            var currentItems = $itemsContainer.find('.accepta-repeater-item').length;
            if (currentItems >= maxItems) {
                alert(acceptaRepeaterL10n.maxItems);
                return;
            }

            addNewItem($control, controlId);
        });

        // Handle item actions (delete, toggle) with namespaced events
        $itemsContainer.on('click.accepta-repeater', '.accepta-repeater-item-delete', function(e) {
            e.preventDefault();
            e.stopPropagation();
            
            if (confirm('Are you sure you want to delete this item?')) {
                $(this).closest('.accepta-repeater-item').remove();
                updateControlValue($control);
                updateItemIndices($itemsContainer);
            }
        });

        $itemsContainer.on('click.accepta-repeater', '.accepta-repeater-item-toggle', function(e) {
            e.preventDefault();
            e.stopPropagation();
            
            var $item = $(this).closest('.accepta-repeater-item');
            toggleRepeaterItem($item);
        });

        // Make the entire header clickable (except for action buttons)
        $itemsContainer.on('click.accepta-repeater', '.accepta-repeater-item-header', function(e) {
            // Don't toggle if clicking on action buttons
            if ($(e.target).closest('.accepta-repeater-item-actions').length) {
                return;
            }
            
            e.preventDefault();
            e.stopPropagation();
            
            var $item = $(this).closest('.accepta-repeater-item');
            toggleRepeaterItem($item);
        });

        // Handle field changes with namespaced events
        $itemsContainer.off('input.accepta-repeater change.accepta-repeater');
        $itemsContainer.on('input.accepta-repeater change.accepta-repeater', '.accepta-repeater-field-input', function() {
            updateControlValue($control);
            updateItemTitle($(this));
        });

        // Handle media selection
        $itemsContainer.on('click.accepta-repeater', '.accepta-repeater-media-select', function(e) {
            e.preventDefault();
            e.stopPropagation();
            
            var $button = $(this);
            var $field = $button.closest('.accepta-repeater-media-field');
            var $input = $field.find('.accepta-repeater-media-input');
            var $preview = $field.find('.accepta-repeater-media-preview');
            var $removeButton = $field.find('.accepta-repeater-media-remove');

            // Open WordPress media library
            var mediaUploader = wp.media({
                title: 'Select Image',
                button: {
                    text: 'Use This Image'
                },
                multiple: false,
                library: {
                    type: 'image'
                }
            });

            mediaUploader.on('select', function() {
                var attachment = mediaUploader.state().get('selection').first().toJSON();
                var imageUrl = attachment.sizes && attachment.sizes.thumbnail ? 
                              attachment.sizes.thumbnail.url : attachment.url;
                
                $input.val(attachment.url).trigger('change');
                $preview.html('<img src="' + imageUrl + '" alt="" />');
                $removeButton.show();
            });

            mediaUploader.open();
        });

        // Handle media removal
        $itemsContainer.on('click.accepta-repeater', '.accepta-repeater-media-remove', function(e) {
            e.preventDefault();
            e.stopPropagation();
            
            var $button = $(this);
            var $field = $button.closest('.accepta-repeater-media-field');
            var $input = $field.find('.accepta-repeater-media-input');
            var $preview = $field.find('.accepta-repeater-media-preview');
            
            $input.val('').trigger('change');
            $preview.empty();
            $button.hide();
        });

        // Handle Font Awesome icon selection
        $itemsContainer.on('click.accepta-repeater', '.accepta-repeater-fontawesome-select', function(e) {
            e.preventDefault();
            e.stopPropagation();
            
            var $button = $(this);
            var $field = $button.closest('.accepta-repeater-fontawesome-field');
            var $input = $field.find('.accepta-repeater-fontawesome-input');
            var $preview = $field.find('.accepta-repeater-fontawesome-preview');
            var $removeButton = $field.find('.accepta-repeater-fontawesome-remove');
            
            openFontAwesomeModal(function(selectedIcon) {
                $input.val(selectedIcon).trigger('change');
                $preview.html('<i class="' + selectedIcon + '"></i>');
                $removeButton.show();
                updateControlValue($control);
            });
        });

        // Handle Font Awesome icon removal
        $itemsContainer.on('click.accepta-repeater', '.accepta-repeater-fontawesome-remove', function(e) {
            e.preventDefault();
            e.stopPropagation();
            
            var $button = $(this);
            var $field = $button.closest('.accepta-repeater-fontawesome-field');
            var $input = $field.find('.accepta-repeater-fontawesome-input');
            var $preview = $field.find('.accepta-repeater-fontawesome-preview');
            
            $input.val('').trigger('change');
            $preview.empty();
            $button.hide();
            updateControlValue($control);
        });

        // Handle icon type change for conditional field display
        $itemsContainer.on('change.accepta-repeater', '[data-field="icon_type"]', function() {
            var $select = $(this);
            var $item = $select.closest('.accepta-repeater-item');
            var iconType = $select.val();
            
            // Show/hide fields based on icon type
            var $iconField = $item.find('[data-field="icon"]').closest('.accepta-repeater-field');
            var $customIconField = $item.find('[data-field="custom_icon"]').closest('.accepta-repeater-field');
            
            if (iconType === 'fontawesome') {
                $iconField.show().addClass('show');
                $customIconField.hide().removeClass('show');
            } else if (iconType === 'custom') {
                $iconField.hide().removeClass('show');
                $customIconField.show().addClass('show');
            }
            
            updateControlValue($control);
        });

        // Initialize existing items
        $itemsContainer.find('.accepta-repeater-item').each(function(index) {
            $(this).attr('data-index', index);
        });

        // Make items sortable
        $itemsContainer.sortable({
            handle: '.accepta-repeater-item-header',
            axis: 'y',
            placeholder: 'accepta-repeater-item-placeholder',
            helper: 'clone',
            opacity: 0.8,
            cursor: 'move',
            tolerance: 'pointer',
            start: function(event, ui) {
                ui.placeholder.height(ui.item.height());
                ui.helper.addClass('accepta-repeater-item-dragging');
            },
            stop: function(event, ui) {
                ui.item.removeClass('accepta-repeater-item-dragging');
                updateControlValue($control);
                updateItemIndices($itemsContainer);
            }
        });
    }

    function addNewItem($control, controlId) {
        var $itemsContainer = $control.find('.accepta-repeater-items');
        var newIndex = $itemsContainer.find('.accepta-repeater-item').length;
        var template = $('#tmpl-accepta-repeater-item-' + controlId).html();
        
        if (!template) {
            console.error('Template not found for control: ' + controlId);
            return;
        }
        
        // Replace template variables
        var itemHtml = template.replace(/\{\{data\.index\}\}/g, newIndex)
                              .replace(/\{\{data\.index \+ 1\}\}/g, newIndex + 1);
        
        var $newItem = $(itemHtml);
        $itemsContainer.append($newItem);
        
        // Set up the new item state
        var $content = $newItem.find('.accepta-repeater-item-content');
        
        // Expand the new item (only new items should be expanded)
        $content.show();
        $newItem.addClass('expanded');
        
        // Initialize conditional field display for new item
        var $iconTypeSelect = $newItem.find('[data-field="icon_type"]');
        if ($iconTypeSelect.length) {
            // Set default value to fontawesome
            $iconTypeSelect.val('fontawesome');
            // Trigger change event to show/hide appropriate fields
            $iconTypeSelect.trigger('change');
        }
        
        // Focus on first field
        setTimeout(function() {
            $newItem.find('.accepta-repeater-field-input').first().focus();
        }, 100);
        
        updateControlValue($control);
    }

    function toggleRepeaterItem($item) {
        var $content = $item.find('.accepta-repeater-item-content');
        
        // Check current state and toggle accordingly
        if ($content.is(':visible')) {
            $content.slideUp();
            $item.removeClass('expanded');
        } else {
            $content.slideDown();
            $item.addClass('expanded');
        }
    }

    function updateControlValue($control) {
        var $input = $control.find('.accepta-repeater-input');
        var $items = $control.find('.accepta-repeater-item');
        var data = [];

        $items.each(function() {
            var $item = $(this);
            var itemData = {};
            
            $item.find('.accepta-repeater-field-input').each(function() {
                var $field = $(this);
                var fieldName = $field.data('field');
                var fieldValue = $field.val();
                
                if (fieldName && fieldValue !== undefined) {
                    itemData[fieldName] = fieldValue;
                }
            });
            
            // Only add item if it has some data
            if (Object.keys(itemData).length > 0) {
                data.push(itemData);
            }
        });

        $input.val(JSON.stringify(data)).trigger('change');
    }

    function updateItemTitle($field) {
        var $item = $field.closest('.accepta-repeater-item');
        var $title = $item.find('.accepta-repeater-item-title');
        var fieldName = $field.data('field');
        var fieldValue = $field.val();
        
        // Update title if this is a label field
        if (fieldName === 'label' && fieldValue) {
            $title.text(fieldValue);
        } else if (fieldName === 'label' && !fieldValue) {
            var index = $item.data('index');
            $title.text('Item ' + (index + 1));
        }
    }

    function updateItemIndices($container) {
        $container.find('.accepta-repeater-item').each(function(index) {
            var $item = $(this);
            $item.attr('data-index', index);
            
            // Update title if it's still default
            var $title = $item.find('.accepta-repeater-item-title');
            var titleText = $title.text();
            if (titleText.match(/^Item \d+$/)) {
                $title.text('Item ' + (index + 1));
            }
        });
    }

    // Add control to customizer control types
    wp.customize.controlConstructor['accepta-repeater'] = wp.customize.Control.extend({
        ready: function() {
            var control = this;
            var $control = this.container.find('.accepta-repeater-control');
            
            // Initialize the control
            initializeRepeaterControl($control, control.id);
            
            // Ensure all existing items are closed by default
            $control.find('.accepta-repeater-item').each(function(index) {
                var $item = $(this);
                var $content = $item.find('.accepta-repeater-item-content');
                
                // Close all items by default
                $content.hide();
                $item.removeClass('expanded');
                
                // Initialize conditional field display
                var $iconTypeSelect = $item.find('[data-field="icon_type"]');
                if ($iconTypeSelect.length) {
                    $iconTypeSelect.trigger('change');
                }
            });
        }
    });

    // Font Awesome Modal Functions
    var fontAwesomeIcons = null;
    var currentFontAwesomeCallback = null;

    function openFontAwesomeModal(callback) {
        currentFontAwesomeCallback = callback;
        
        if (!fontAwesomeIcons) {
            loadFontAwesomeIcons();
        }
        
        createFontAwesomeModal();
        $('.accepta-fontawesome-modal').show();
    }

    function loadFontAwesomeIcons() {
        // For now, just render the icons directly without AJAX
        // This prevents the wp.customize.settings.nonce error
        renderFontAwesomeIcons();
    }

    function createFontAwesomeModal() {
        if ($('.accepta-fontawesome-modal').length) {
            return;
        }

        // Ensure Font Awesome is loaded
        if (!$('link[href*="fontawesome"]').length && !$('link[href*="font-awesome"]').length) {
            var templateUrl = (typeof acceptaRepeaterL10n !== 'undefined' && acceptaRepeaterL10n.templateUrl) 
                ? acceptaRepeaterL10n.templateUrl 
                : '';
            if (templateUrl) {
                $('<link rel="stylesheet" href="' + templateUrl + '/assets/fonts/fontawesome/all.min.css">').appendTo('head');
            }
        }

        var modalHtml = `
            <div class="accepta-fontawesome-modal">
                <div class="accepta-fontawesome-modal-content">
                    <div class="accepta-fontawesome-modal-header">
                        <h3 class="accepta-fontawesome-modal-title">Select Font Awesome Icon</h3>
                        <button type="button" class="accepta-fontawesome-modal-close">&times;</button>
                    </div>
                    <div class="accepta-fontawesome-modal-search">
                        <input type="text" class="accepta-fontawesome-search-input" placeholder="Search icons..." />
                    </div>
                    <div class="accepta-fontawesome-modal-body">
                        <div class="accepta-fontawesome-loading">Loading icons...</div>
                    </div>
                    <div class="accepta-fontawesome-modal-footer">
                        <button type="button" class="button button-secondary accepta-fontawesome-cancel">Cancel</button>
                        <button type="button" class="button button-primary accepta-fontawesome-select-btn" disabled>Select Icon</button>
                    </div>
                </div>
            </div>
        `;

        $('body').append(modalHtml);

        // Modal event handlers
        $('.accepta-fontawesome-modal-close, .accepta-fontawesome-cancel').on('click', function() {
            $('.accepta-fontawesome-modal').hide();
        });

        $('.accepta-fontawesome-modal').on('click', function(e) {
            if (e.target === this) {
                $(this).hide();
            }
        });

        $('.accepta-fontawesome-select-btn').on('click', function() {
            var selectedIcon = $('.accepta-fontawesome-icon-item.selected').data('icon');
            if (selectedIcon && currentFontAwesomeCallback) {
                currentFontAwesomeCallback(selectedIcon);
                $('.accepta-fontawesome-modal').hide();
            }
        });

        $('.accepta-fontawesome-search-input').on('input', function() {
            var searchTerm = $(this).val().toLowerCase();
            filterFontAwesomeIcons(searchTerm);
        });

        // Show loading state
        $('.accepta-fontawesome-modal-body').html('<div class="accepta-fontawesome-loading" style="text-align: center; padding: 40px; color: #666;">Loading icons...</div>');
        
        // Load icons with delay to ensure Font Awesome loads
        setTimeout(function() {
            if (!fontAwesomeIcons) {
                loadFontAwesomeIcons();
            } else {
                renderFontAwesomeIcons();
            }
        }, 300);
    }

    function renderFontAwesomeIcons() {
        var html = '';
        
        // Force load Font Awesome if not detected
        if (!$('link[href*="fontawesome"], link[href*="font-awesome"]').length) {
            var templateUrl = (typeof acceptaRepeaterL10n !== 'undefined' && acceptaRepeaterL10n.templateUrl) 
                ? acceptaRepeaterL10n.templateUrl 
                : '';
            if (templateUrl) {
                $('<link rel="stylesheet" href="' + templateUrl + '/assets/fonts/fontawesome/all.min.css">').appendTo('head');
            }
        }
        
        // Use a simplified icon set for now
        var simpleIcons = {
            'Social Media': {
                'fab fa-facebook-f': 'Facebook',
                'fab fa-twitter': 'Twitter', 
                'fab fa-instagram': 'Instagram',
                'fab fa-linkedin-in': 'LinkedIn',
                'fab fa-youtube': 'YouTube',
                'fab fa-pinterest-p': 'Pinterest',
                'fab fa-tiktok': 'TikTok',
                'fab fa-snapchat-ghost': 'Snapchat',
                'fab fa-whatsapp': 'WhatsApp',
                'fab fa-telegram-plane': 'Telegram',
                'fab fa-discord': 'Discord',
                'fab fa-reddit-alien': 'Reddit',
                'fab fa-github': 'GitHub',
                'fab fa-dribbble': 'Dribbble',
                'fab fa-behance': 'Behance'
            },
            'Communication': {
                'fas fa-envelope': 'Email',
                'fas fa-phone': 'Phone',
                'fas fa-mobile-alt': 'Mobile',
                'fas fa-comments': 'Comments',
                'fas fa-comment': 'Comment',
                'fas fa-paper-plane': 'Send'
            },
            'Business': {
                'fas fa-briefcase': 'Briefcase',
                'fas fa-building': 'Building',
                'fas fa-chart-bar': 'Chart',
                'fas fa-calendar': 'Calendar',
                'fas fa-clock': 'Clock',
                'fas fa-handshake': 'Handshake',
                'fas fa-trophy': 'Trophy'
            },
            'Navigation': {
                'fas fa-home': 'Home',
                'fas fa-user': 'User',
                'fas fa-cog': 'Settings',
                'fas fa-search': 'Search',
                'fas fa-heart': 'Heart',
                'fas fa-star': 'Star',
                'fas fa-share': 'Share'
            }
        };

        for (var category in simpleIcons) {
            html += '<div class="accepta-fontawesome-category" data-category="' + category.toLowerCase().replace(/\s+/g, '-') + '">';
            html += '<h4 class="accepta-fontawesome-category-title">' + category + '</h4>';
            html += '<div class="accepta-fontawesome-icons-grid">';
            
            for (var iconClass in simpleIcons[category]) {
                var iconName = simpleIcons[category][iconClass];
                html += '<div class="accepta-fontawesome-icon-item" data-icon="' + iconClass + '" data-name="' + iconName.toLowerCase() + '" data-class="' + iconClass.toLowerCase() + '">';
                html += '<i class="' + iconClass + '" aria-hidden="true"></i>';
                html += '<span class="icon-name">' + iconName + '</span>';
                html += '</div>';
            }
            
            html += '</div></div>';
        }

        $('.accepta-fontawesome-modal-body').html(html);
        
        // Store original icons for search reset
        window.acceptaOriginalIcons = $('.accepta-fontawesome-icon-item');

        // Icon selection handler
        $('.accepta-fontawesome-icon-item').on('click', function() {
            $('.accepta-fontawesome-icon-item').removeClass('selected');
            $(this).addClass('selected');
            $('.accepta-fontawesome-select-btn').prop('disabled', false);
        });
        
        
        // Test if Font Awesome is working
        setTimeout(function() {
            var testIcon = $('.accepta-fontawesome-icon-item i').first();
            if (testIcon.length) {
                var computedStyle = window.getComputedStyle(testIcon[0], ':before');
                var content = computedStyle.getPropertyValue('content');
                if (content && content !== 'none' && content !== '""') {
                } else {
                }
            }
        }, 500);
    }

    function filterFontAwesomeIcons(searchTerm) {
        searchTerm = searchTerm.trim().toLowerCase();
        
        // If search term is empty, show all icons
        if (searchTerm === '') {
            $('.accepta-fontawesome-icon-item').show();
            $('.accepta-fontawesome-category').show();
            return;
        }
        
        
        $('.accepta-fontawesome-icon-item').each(function() {
            var $item = $(this);
            var iconClass = $item.data('class') || $item.data('icon') || '';
            var iconName = $item.data('name') || $item.find('.icon-name').text().toLowerCase();
            
            // Check if search term matches icon class or name
            if (iconClass.includes(searchTerm) || iconName.includes(searchTerm)) {
                $item.show();
            } else {
                $item.hide();
            }
        });

        // Hide empty categories
        $('.accepta-fontawesome-category').each(function() {
            var $category = $(this);
            var visibleIcons = $category.find('.accepta-fontawesome-icon-item:visible').length;
            
            if (visibleIcons === 0) {
                $category.hide();
            } else {
                $category.show();
            }
        });
        
        var totalVisible = $('.accepta-fontawesome-icon-item:visible').length;
    }

})(jQuery);
