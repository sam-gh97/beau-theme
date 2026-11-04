<?php

$autoload = get_template_directory() . '/vendor/autoload.php';
if (file_exists($autoload)) {
    require_once $autoload;
} else {
    wp_die('Missing Composer autoload. Run <code>composer install</code> in your theme folder.');
}

// Load Action Scheduler
$actionScheduler = get_template_directory() . '/vendor/woocommerce/action-scheduler/action-scheduler.php';
if (file_exists($actionScheduler)) {
    require_once $actionScheduler;
} else {
    error_log('AWIN: ERROR - Action Scheduler file not found at: ' . $actionScheduler);
}

use Boilerplate\Theme;
use Boilerplate\Integrations\HubSpot\Bootstrap;

Theme::get_instance();

add_action('init', function () {
    Bootstrap::init();
}, 20);

add_filter('wpcf7_autop_or_not', function() {
    return false;
});



include get_template_directory() . '/woocommerce-functions.php';

add_filter('allowed_block_types_all', function ($allowed_blocks, $context) {

    if (!isset($context->post) || $context->post->post_type !== 'page') {
        return $allowed_blocks;
    }

    $blocks = \Boilerplate\Services\BlockRegistrar::getBlocks();

    $acf_blocks = array_map(function ($name) {
        return 'acf/' . $name;
    }, array_keys($blocks));

    $acf_blocks[] = 'core/paragraph';

    return $acf_blocks;

}, 10, 2);

add_filter('manage_users_columns', function ($columns) {
    unset($columns['user_jetpack']);
    unset($columns['wordpressdotcom']); 
    return $columns;
});


add_action('wp_ajax_save_wishlist', function () {
    if (!is_user_logged_in()) {
        wp_send_json_error('not_logged_in');
    }

    $wishlist = isset($_POST['wishlist']) ? wp_unslash($_POST['wishlist']) : '[]';
    update_user_meta(get_current_user_id(), 'beau_co_wishlist', $wishlist);

    wp_send_json_success();
});


add_action('wp_ajax_get_wishlist', function () {
    if (!is_user_logged_in()) {
        wp_send_json_success([]);
    }

    $wishlist = json_decode(get_user_meta(get_current_user_id(), 'beau_co_wishlist', true), true);
    wp_send_json_success($wishlist ?: []);
});

add_filter('wpseo_sitemap_exclude_post_type', function ($exclude, $post_type) {
    $excluded_post_types = [
        'event',
        'business',
        'author',
    ];

    if (in_array($post_type, $excluded_post_types, true)) {
        return true;
    }

    return $exclude;
}, 10, 2);



add_filter('acf/load_field/key=field_696108a533a5b', function (array $field): array {
    $redirect = admin_url('admin.php?page=awin-settings');

    $urlNow = wp_nonce_url(
        add_query_arg(
            [
                'action'   => 'beauco_awin_run_sync_categories_now',
                'redirect' => rawurlencode($redirect),
            ],
            admin_url('admin-post.php')
        ),
        'beauco_awin_sync_categories_now'
    );

    $field['message'] =
        '<div class="beauco-awin-job" style="padding:15px; background:#f9f9f9; border-radius:4px;">' .
            '<h3 style="margin-top:0;">Sync Product Categories</h3>' .
            '<p style="color:#666; margin:10px 0;">Updates product categories based on AWIN category phrase mappings.</p>' .
            '<a href="' . esc_url($urlNow) . '" class="button button-primary" onclick="return confirm(\'Sync categories now? This will process all products and may take several minutes.\')">Sync Now</a>' .
        '</div>';

    return $field;
});

add_filter('acf/load_field/key=field_696116096babf', function (array $field): array {
    $redirect = admin_url('admin.php?page=awin-settings');

    $urlClear = wp_nonce_url(
        add_query_arg(
            [
                'action'   => 'beauco_awin_clear_inactive_feeds',
                'redirect' => rawurlencode($redirect),
            ],
            admin_url('admin-post.php')
        ),
        'beauco_awin_clear_inactive_feeds'
    );

    $countNonce = wp_create_nonce('beauco_awin_count_inactive');

    $field['message'] =
        '<div class="beauco-awin-job" style="padding:15px; background:#f9f9f9; border-radius:4px;">' .
            '<h3 style="margin-top:0;">Clear Inactive Feeds</h3>' .
            '<p style="color:#666; margin:10px 0;">Permanently deletes products from feeds that are no longer active and removes their associated images.</p>' .
            '<p style="color:#d63638; margin:10px 0; font-size:13px;"><strong>Warning:</strong> This action cannot be undone. Products from inactive feeds will be permanently deleted.</p>' .
            '<button type="button" class="button button-secondary beauco-clear-inactive-feeds" data-url="' . esc_url($urlClear) . '" data-nonce="' . esc_attr($countNonce) . '">Clear Inactive Feeds</button>' .
        '</div>';

    return $field;
});

// Enqueue the clear inactive feeds script on the AWIN settings page
add_action('admin_enqueue_scripts', function($hook) {
    $screen = get_current_screen();
    if (!$screen || strpos($screen->id, 'awin-settings') === false) {
        return;
    }

    wp_add_inline_script('jquery', "
        jQuery(document).ready(function($) {
            // Auto-open specific tab if requested via URL parameter
            const urlParams = new URLSearchParams(window.location.search);
            if (urlParams.get('open_tab') === 'category_mapping') {
                setTimeout(function() {
                    const tab = $('.acf-tab-button[data-key=\"field_69604c52bad36\"]');
                    if (tab.length) {
                        tab.trigger('click');
                    }
                }, 300);
            }
            
            $('.beauco-clear-inactive-feeds').on('click', function(e) {
                e.preventDefault();
                const \$btn = $(this);
                const url = \$btn.data('url');
                const nonce = \$btn.data('nonce');
                
                // Disable button and show loading state
                \$btn.prop('disabled', true).text('Checking...');
                
                // Fetch count via AJAX
                $.ajax({
                    url: ajaxurl,
                    type: 'POST',
                    data: {
                        action: 'beauco_awin_count_inactive_products',
                        nonce: nonce
                    },
                    success: function(response) {
                        \$btn.prop('disabled', false).text('Clear Inactive Feeds');
                        
                        if (response.success) {
                            const products = response.data.products;
                            const images = response.data.images;
                            
                            if (products === 0) {
                                alert('No inactive products found. All products belong to active feeds.');
                                return;
                            }
                            
                            const message = 
                                'Are you sure you want to delete products from inactive feeds?\\n\\n' +
                                'This will permanently remove:\\n' +
                                '• ' + products + ' product' + (products === 1 ? '' : 's') + '\\n' +
                                '• ' + images + ' image' + (images === 1 ? '' : 's') + '\\n\\n' +
                                'This action cannot be undone!';
                            
                            if (confirm(message)) {
                                window.location.href = url;
                            }
                        } else {
                            alert('Error checking inactive products: ' + (response.data.message || 'Unknown error'));
                        }
                    },
                    error: function() {
                        \$btn.prop('disabled', false).text('Clear Inactive Feeds');
                        alert('Error checking inactive products. Please try again.');
                    }
                });
            });
        });
    ");
});

