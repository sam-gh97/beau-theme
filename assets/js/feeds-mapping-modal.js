jQuery(document).ready(function($) {
    const modal = $('#feed-mapping-modal');
    const modalOverlay = $('.feed-mapping-modal-overlay');
    const modalContent = $('.feed-mapping-modal-content');
    let currentFeedId = null;
    let currentFeedUrl = null;

    // Open modal on button click
    $(document).on('click', '.feed-map-btn', function() {
        // Don't open modal if button is disabled
        if ($(this).prop('disabled')) {
            return false;
        }
        
        const feedId = $(this).data('feed-id');
        const feedName = $(this).data('feed-name');
        const feedUrl = $(this).data('feed-url');

        currentFeedId = feedId;
        currentFeedUrl = feedUrl;

        // Update modal title
        $('.feed-name-display').text(feedName + ' (' + feedId + ')');

        // Clear previous values
        clearModalInputs();

        // Load existing mapping
        loadMapping(feedId);

        // Show modal
        modal.fadeIn(200);
        $('body').css('overflow', 'hidden');
    });

    // Close modal
    function closeModal() {
        modal.fadeOut(200);
        $('body').css('overflow', '');
        currentFeedId = null;
        currentFeedUrl = null;
    }

    $('.feed-mapping-modal-close, .feed-mapping-cancel').on('click', closeModal);

    modalOverlay.on('click', closeModal);

    // Prevent closing when clicking inside modal content
    modalContent.on('click', function(e) {
        e.stopPropagation();
    });

    // ESC key to close
    $(document).on('keydown', function(e) {
        if (e.key === 'Escape' && modal.is(':visible')) {
            closeModal();
        }
    });

    // Load existing mapping
    function loadMapping(feedId) {
        $.ajax({
            url: ajaxurl,
            type: 'POST',
            data: {
                action: 'beauco_awin_get_feed_mapping',
                nonce: beaucoAwinMapping.nonce,
                feed_id: feedId
            },
            beforeSend: function() {
                disableModalInputs(true);
                $('.feed-mapping-delete').hide();
            },
            success: function(response) {
                if (response.success && response.data.mapping) {
                    const mapping = response.data.mapping;
                    const hasMapping = mapping.title_column || mapping.price_column || 
                                     mapping.category_column || mapping.description_column;
                    
                    $('#mapping-title').val(mapping.title_column || '');
                    $('#mapping-price').val(mapping.price_column || '');
                    $('#mapping-category').val(mapping.category_column || '');
                    $('#mapping-description').val(mapping.description_column || '');
                    $('#mapping-brand').val(mapping.brand_column || '');
                    $('#mapping-enable-stock').prop('checked', mapping.enable_stock_filter || false);
                    $('#mapping-stock').val(mapping.stock_column || '');
                    
                    // Toggle stock column field visibility
                    toggleStockColumn();
                    
                    // Show delete button if mapping exists
                    if (hasMapping) {
                        $('.feed-mapping-delete').show();
                    }
                }
            },
            error: function() {
                console.error('Failed to load mapping');
            },
            complete: function() {
                disableModalInputs(false);
            }
        });
    }

    // Save mapping
    $('.feed-mapping-save').on('click', function() {
        if (!currentFeedId) {
            return;
        }

        const mapping = {
            title_column: $('#mapping-title').val().trim(),
            price_column: $('#mapping-price').val().trim(),
            category_column: $('#mapping-category').val().trim(),
            description_column: $('#mapping-description').val().trim(),
            brand_column: $('#mapping-brand').val().trim(),
            enable_stock_filter: $('#mapping-enable-stock').is(':checked') ? 1 : 0,
            stock_column: $('#mapping-stock').val().trim()
        };

        // Validate required fields
        if (!mapping.title_column || !mapping.price_column || 
            !mapping.category_column || !mapping.description_column) {
            alert('Please fill in all required fields (marked with *)');
            return;
        }

        $.ajax({
            url: ajaxurl,
            type: 'POST',
            data: {
                action: 'beauco_awin_save_feed_mapping',
                nonce: beaucoAwinMapping.nonce,
                feed_id: currentFeedId,
                ...mapping
            },
            beforeSend: function() {
                disableModalInputs(true);
                $('.feed-mapping-save').text('Saving...');
            },
            success: function(response) {
                if (response.success) {
                    showNotification('Mapping saved successfully!', 'success');
                    
                    // Add mapping indicator if not already present
                    const $btn = $('.feed-map-btn[data-feed-id="' + currentFeedId + '"]');
                    const $container = $btn.closest('.feed-actions');
                    const $row = $btn.closest('tr');
                    
                    if ($container.find('.feed-mapping-indicator').length === 0) {
                        $container.append('<span class="feed-mapping-indicator" title="Column mapping configured">✓</span>');
                    }
                    
                    // Show Import Now button if feed is active and doesn't already have one
                    const isActive = $row.hasClass('active-feed');
                    if (isActive && $container.find('.feed-import-btn').length === 0) {
                        const feedName = $btn.data('feed-name');
                        const feedUrl = $btn.data('feed-url');
                        const productCount = $row.find('td').eq(7).text().trim(); // Products column
                        
                        const $importBtn = $('<button type="button" class="button button-primary button-small feed-import-btn">' +
                            'Import Now</button>')
                            .attr('data-feed-id', currentFeedId)
                            .attr('data-feed-name', feedName)
                            .attr('data-feed-url', feedUrl)
                            .attr('data-product-count', productCount);
                        
                        $container.append($importBtn);
                    }
                    
                    closeModal();
                } else {
                    alert('Error: ' + (response.data.message || 'Failed to save mapping'));
                }
            },
            error: function() {
                alert('Error: Failed to communicate with server');
            },
            complete: function() {
                disableModalInputs(false);
                $('.feed-mapping-save').text('Save Mapping');
            }
        });
    });

    // Delete mapping
    $('.feed-mapping-delete').on('click', function() {
        if (!currentFeedId) {
            return;
        }

        if (!confirm('Are you sure you want to delete this column mapping? The feed will revert to using default column names.')) {
            return;
        }

        $.ajax({
            url: ajaxurl,
            type: 'POST',
            data: {
                action: 'beauco_awin_delete_feed_mapping',
                nonce: beaucoAwinMapping.nonce,
                feed_id: currentFeedId
            },
            beforeSend: function() {
                disableModalInputs(true);
                $('.feed-mapping-delete').text('Deleting...');
            },
            success: function(response) {
                if (response.success) {
                    showNotification('Mapping deleted successfully!', 'success');
                    
                    // Remove mapping indicator and import button
                    const $btn = $('.feed-map-btn[data-feed-id="' + currentFeedId + '"]');
                    const $container = $btn.closest('.feed-actions');
                    $container.find('.feed-mapping-indicator').remove();
                    $container.find('.feed-import-btn').remove();
                    
                    closeModal();
                } else {
                    alert('Error: ' + (response.data.message || 'Failed to delete mapping'));
                }
            },
            error: function() {
                alert('Error: Failed to communicate with server');
            },
            complete: function() {
                disableModalInputs(false);
                $('.feed-mapping-delete').text('Delete Mapping');
            }
        });
    });

    // Toggle stock column visibility based on checkbox
    function toggleStockColumn() {
        if ($('#mapping-enable-stock').is(':checked')) {
            $('#stock-column-field').slideDown(200);
        } else {
            $('#stock-column-field').slideUp(200);
        }
    }

    // Handle stock filter toggle
    $('#mapping-enable-stock').on('change', toggleStockColumn);

    // Helper functions
    function clearModalInputs() {
        $('#mapping-title, #mapping-price, #mapping-category, #mapping-description, #mapping-brand, #mapping-stock').val('');
        $('#mapping-enable-stock').prop('checked', false);
        $('#stock-column-field').hide();
        $('.feed-mapping-delete').hide();
    }

    function disableModalInputs(disable) {
        $('.feed-mapping-modal-body input').prop('disabled', disable);
        $('.feed-mapping-save, .feed-mapping-cancel, .feed-mapping-delete').prop('disabled', disable);
    }

    function showNotification(message, type) {
        const noticeClass = type === 'success' ? 'notice-success' : 'notice-error';
        const notice = $('<div>')
            .addClass('notice ' + noticeClass + ' is-dismissible')
            .css({
                position: 'fixed',
                top: '32px',
                right: '20px',
                zIndex: 100000,
                minWidth: '300px'
            })
            .html('<p><strong>' + message + '</strong></p>');
        
        $('body').append(notice);
        
        setTimeout(function() {
            notice.fadeOut(function() {
                $(this).remove();
            });
        }, 3000);
    }
});
