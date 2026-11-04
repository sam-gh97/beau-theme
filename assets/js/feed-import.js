jQuery(document).ready(function($) {
    // Handle Import Now button click
    $(document).on('click', '.feed-import-btn', function() {
        const $button = $(this);
        
        // Don't trigger if already importing
        if ($button.prop('disabled') || $button.hasClass('importing')) {
            return false;
        }
        
        const feedId = $button.data('feed-id');
        const feedName = $button.data('feed-name');
        const feedUrl = $button.data('feed-url');
        const productCount = $button.data('product-count');
        
        // Calculate estimated time (10 products per minute)
        const estimatedMinutes = Math.ceil(productCount / 10);
        const timeEstimate = estimatedMinutes === 1 
            ? '1 minute' 
            : `${estimatedMinutes} minutes`;
        
        // Show confirmation dialog
        const confirmMessage = `Import products from ${feedName}?\n\n` +
            `• Products: ${productCount}\n` +
            `• Estimated time: ${timeEstimate}\n` +
            `• Import rate: ~10 products per minute`;
        
        if (!confirm(confirmMessage)) {
            return false;
        }
        
        // Start the import
        startImport($button, feedId, feedName, feedUrl, productCount);
    });
    
    function startImport($button, feedId, feedName, feedUrl, productCount) {
        const originalText = $button.text();
        
        // Update button state
        $button.prop('disabled', true)
               .addClass('importing')
               .text('Importing...');
        
        $.ajax({
            url: ajaxurl,
            type: 'POST',
            data: {
                action: 'beauco_awin_import_feed',
                nonce: beaucoAwinImport.nonce,
                feed_id: feedId,
                feed_name: feedName
            },
            success: function(response) {
                if (response.success && response.data.redirect_url) {
                    // Redirect to status page
                    window.location.href = response.data.redirect_url;
                } else {
                    showNotification(
                        `Import failed: ${response.data.message || 'Unknown error'}`,
                        'error',
                        5000
                    );
                    
                    // Re-enable button on error
                    $button.prop('disabled', false)
                           .removeClass('importing')
                           .text(originalText);
                }
            },
            error: function(xhr, status, error) {
                showNotification(
                    `Import failed: Unable to communicate with server`,
                    'error',
                    5000
                );
                
                // Re-enable button on error
                $button.prop('disabled', false)
                       .removeClass('importing')
                       .text(originalText);
            }
        });
    }
    
    function showNotification(message, type, duration) {
        const noticeClass = type === 'success' ? 'notice-success' : 'notice-error';
        const notice = $('<div>')
            .addClass('notice ' + noticeClass + ' is-dismissible')
            .css({
                position: 'fixed',
                top: '32px',
                right: '20px',
                zIndex: 100000,
                minWidth: '350px',
                maxWidth: '500px'
            })
            .html('<p><strong>' + message + '</strong></p>');
        
        $('body').append(notice);
        
        setTimeout(function() {
            notice.fadeOut(function() {
                $(this).remove();
            });
        }, duration || 3000);
    }
});
