jQuery(document).ready(function($) {
    $(".feed-toggle-checkbox").on("change", function() {
        const checkbox = $(this);
        const feedId = checkbox.data("feed-id");
        const feedName = checkbox.data("feed-name");
        const enable = checkbox.is(":checked");
        const toggleSwitch = checkbox.closest(".feed-toggle-switch");
        const row = checkbox.closest("tr");

        // Disable the checkbox during the request
        checkbox.prop("disabled", true);
        toggleSwitch.addClass("loading");

        $.ajax({
            url: ajaxurl,
            type: "POST",
            data: {
                action: "beauco_awin_toggle_feed",
                nonce: beaucoAwinFeeds.nonce,
                feed_id: feedId,
                feed_name: feedName,
                enable: enable
            },
            success: function(response) {
                if (response.success) {
                    // Update row styling
                    if (enable) {
                        row.addClass("active-feed");
                    } else {
                        row.removeClass("active-feed");
                    }
                    
                    // Enable/disable the "Map Columns" button
                    const mapButton = row.find(".feed-map-btn");
                    if (enable) {
                        mapButton.prop("disabled", false);
                    } else {
                        mapButton.prop("disabled", true);
                    }
                    
                    // Show/hide the "Import Now" button based on active state and mapping
                    const importButton = row.find(".feed-import-btn");
                    const hasMappingIndicator = row.find(".feed-mapping-indicator").length > 0;
                    
                    if (enable && hasMappingIndicator) {
                        // Feed is active and has mapping, show import button if it doesn't exist
                        if (importButton.length === 0) {
                            const feedId = mapButton.data("feed-id");
                            const feedName = mapButton.data("feed-name");
                            const feedUrl = mapButton.data("feed-url");
                            const productCount = row.find("td").eq(7).text().trim(); // Products column
                            
                            const $newImportBtn = $('<button type="button" class="button button-primary button-small feed-import-btn">' +
                                'Import Now</button>')
                                .attr("data-feed-id", feedId)
                                .attr("data-feed-name", feedName)
                                .attr("data-feed-url", feedUrl)
                                .attr("data-product-count", productCount);
                            
                            row.find(".feed-actions").append($newImportBtn);
                        }
                    } else {
                        // Feed is not active or doesn't have mapping, remove import button
                        importButton.remove();
                    }
                    
                    // Show brief success message
                    const message = $("<div>")
                        .addClass("notice notice-success is-dismissible feed-refresh-notice")
                        .css({
                            position: "fixed",
                            top: "32px",
                            right: "20px",
                            zIndex: 10000,
                            minWidth: "300px"
                        })
                        .html("<p><strong>" + response.data.message + "</strong></p>");
                    
                    $("body").append(message);
                    
                    setTimeout(function() {
                        message.fadeOut(function() {
                            $(this).remove();
                        });
                    }, 2000);
                } else {
                    // Revert checkbox on error
                    checkbox.prop("checked", !enable);
                    alert("Error: " + (response.data.message || "Failed to update feed"));
                }
            },
            error: function() {
                // Revert checkbox on error
                checkbox.prop("checked", !enable);
                alert("Error: Failed to communicate with server");
            },
            complete: function() {
                checkbox.prop("disabled", false);
                toggleSwitch.removeClass("loading");
            }
        });
    });
});
