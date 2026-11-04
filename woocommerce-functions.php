<?php

//disable acf field for featured posts (bidirectional)
add_filter('acf/prepare_field/key=field_6928a9617692a', function($field) {
    return false;
});

add_action('after_setup_theme', function() {
    add_theme_support('woocommerce');
});

remove_action('woocommerce_shop_loop_header', 'woocommerce_archive_description', 10);
remove_action('woocommerce_shop_loop_header', 'woocommerce_product_taxonomy_archive_header', 10);

add_filter('loop_shop_columns', function () {
    return 3;
});

remove_action('woocommerce_shop_loop_header', 'woocommerce_product_taxonomy_archive_header', 10);

add_filter('loop_shop_per_page', function () {
    return 9;
});

// Get featured product IDs and store them for sorting
add_action('pre_get_posts', function($query) {
    // Only for main product queries on frontend
    if (is_admin() || !$query->is_main_query()) {
        return;
    }
    
    // Check if this is a product query (shop page or product taxonomy)
    $post_type = $query->get('post_type');
    $is_product_query = $post_type === 'product' || 
                        (is_array($post_type) && in_array('product', $post_type));
    
    // Also check if we're querying product taxonomies
    $product_cat = $query->get('product_cat');
    $product_brand = $query->get('product_brand');
    $is_product_tax = !empty($product_cat) || !empty($product_brand);
    
    if (!$is_product_query && !$is_product_tax) {
        return;
    }
    
    // Get the featured term
    $featured_term = get_term_by('name', 'featured', 'product_visibility');
    
    if (!$featured_term) {
        return;
    }
    
    global $wpdb;
    
    // Get all featured product IDs
    $featured_ids = $wpdb->get_col($wpdb->prepare("
        SELECT object_id 
        FROM {$wpdb->term_relationships}
        WHERE term_taxonomy_id = %d
    ", $featured_term->term_taxonomy_id));
    
    if (!empty($featured_ids)) {
        // Store featured IDs for use in posts_orderby
        $GLOBALS['wc_featured_ids'] = $featured_ids;
    }
}, 10);

// Modify SQL ORDER BY to prioritize featured products
add_filter('posts_orderby', function($orderby, $query) {
    global $wpdb;
    
    // Only for product queries on shop/archive pages (not admin)
    if (is_admin() || !$query->is_main_query()) {
        return $orderby;
    }
    
    // Check if we're on shop or product taxonomy pages
    if (!is_shop() && !is_product_taxonomy()) {
        return $orderby;
    }
    
    // Check if we have featured product IDs
    if (empty($GLOBALS['wc_featured_ids'])) {
        return $orderby;
    }
    
    $featured_ids = $GLOBALS['wc_featured_ids'];
    $featured_ids_str = implode(',', array_map('intval', $featured_ids));
    
    // Create CASE statement: featured products get 0, others get 1, then sort by original order
    $new_orderby = "CASE WHEN {$wpdb->posts}.ID IN ({$featured_ids_str}) THEN 0 ELSE 1 END ASC";
    
    if (!empty($orderby)) {
        $new_orderby .= ", " . $orderby;
    } else {
        $new_orderby .= ", {$wpdb->posts}.post_date DESC";
    }
    
    return $new_orderby;
}, 20, 2);

remove_action('woocommerce_before_main_content', 'woocommerce_breadcrumb', 20);
remove_action('woocommerce_after_shop_loop', 'woocommerce_pagination', 10);

remove_action('woocommerce_after_single_product_summary', 'woocommerce_upsell_display', 15);

// Disable cart/checkout
remove_action('woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart', 10);
remove_action('woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_add_to_cart', 10);
remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_add_to_cart', 30);
remove_action('woocommerce_cart_actions', 'woocommerce_cart_action_buttons');
remove_action('woocommerce_proceed_to_checkout', 'woocommerce_button_proceed_to_checkout', 20);
add_filter('woocommerce_cart_fragments', '__return_empty_array');

add_action('template_redirect', function () {
    if (is_cart() || is_checkout()) {
        wp_redirect(home_url());
        exit;
    }
});

add_filter('woocommerce_product_supports', function ($supports, $feature, $product) {
    if ($feature === 'ajax_add_to_cart') {
        return false;
    }
    return $supports;
}, 10, 3);


add_action('woocommerce_after_shop_loop', function () {

    global $wp_query;

    if ($wp_query->max_num_pages <= 1) {
        return;
    }

    $current = max(1, get_query_var('paged'));
    $total   = $wp_query->max_num_pages;

    $prev_icon = '<img src="' . get_image('icons/chevron-left.svg') . '" class="pagination-arrow pagination-prev" alt="Previous">';
    $next_icon = '<img src="' . get_image('icons/chevron-right.svg') . '" class="pagination-arrow pagination-next" alt="Next">';

    echo '<nav class="pagination">';
    echo '<div class="pagination-container">';

    // Previous button
    if ($current > 1) {
        echo '<a href="' . esc_url(get_pagenum_link($current - 1)) . '" class="pagination-link">' . $prev_icon . '</a>';
    }

    // Smart pagination with ellipsis
    $pages_to_show = 2; // Number of pages to show at start/end
    $pages_around_current = 1; // Number of pages to show around current page

    $dots_shown = [false, false]; // Track if we've shown dots [before current, after current]

    for ($i = 1; $i <= $total; $i++) {
        $show_page = false;

        // Always show first few pages
        if ($i <= $pages_to_show) {
            $show_page = true;
        }
        // Always show last few pages
        elseif ($i > $total - $pages_to_show) {
            $show_page = true;
        }
        // Show pages around current page
        elseif ($i >= $current - $pages_around_current && $i <= $current + $pages_around_current) {
            $show_page = true;
        }

        if ($show_page) {
            if ($i == $current) {
                echo '<span class="pagination-link pagination-link--current">' . $i . '</span>';
            } else {
                echo '<a href="' . esc_url(get_pagenum_link($i)) . '" class="pagination-link">' . $i . '</a>';
            }
        } else {
            // Show ellipsis only once per gap
            if ($i < $current && !$dots_shown[0]) {
                echo '<span class="pagination-link pagination-ellipsis">...</span>';
                $dots_shown[0] = true;
            } elseif ($i > $current && !$dots_shown[1]) {
                echo '<span class="pagination-link pagination-ellipsis">...</span>';
                $dots_shown[1] = true;
            }
        }
    }

    // Next button
    if ($current < $total) {
        echo '<a href="' . esc_url(get_pagenum_link($current + 1)) . '" class="pagination-link">' . $next_icon . '</a>';
    }

    echo '</div>';
    echo '</nav>';
});

function get_wishlist_page_url() {
    $pages = get_pages([
        'meta_key' => '_wp_page_template',
        'meta_value' => 'template-wishlist.php'
    ]);
    
    if (!empty($pages)) {
        return get_permalink($pages[0]->ID);
    }
    
    return home_url('/wishlist/');
}

//disable cart
add_filter('woocommerce_load_cart_from_session', '__return_false');
remove_action('init', 'wc_load_cart', 0);


//remove cart fragments and scripts
add_action('wp_enqueue_scripts', function () {
    wp_dequeue_script('wc-cart-fragments');
    // wp_dequeue_script('woocommerce');
    wp_dequeue_script('wc-add-to-cart');
}, 20);

// remove shipping, tax and coupons
add_filter('woocommerce_shipping_enabled', '__return_false');
add_filter('woocommerce_tax_enabled', '__return_false');
add_filter('woocommerce_coupons_enabled', '__return_false');

//remove order related stuff from account
add_filter('woocommerce_account_menu_items', function ($items) {
    unset($items['orders']);
    unset($items['downloads']);
    unset($items['payment-methods']);
    unset($items['subscriptions']); // if present
    return $items;
});

//disable admin post types
add_action('admin_menu', function () {
    remove_menu_page('edit.php?post_type=shop_order');
    remove_menu_page('edit.php?post_type=shop_coupon');
});

// remove checkout fields
add_filter('woocommerce_checkout_fields', '__return_empty_array');

// woocommerce account approval stuff
add_filter('woocommerce_registration_auth_new_customer', '__return_false');

// disable new account email
add_filter('woocommerce_email_enabled_customer_new_account', '__return_false');
add_filter('woocommerce_email_enabled_customer_new_account_generated_password', '__return_false');

add_action('woocommerce_created_customer', function ($user_id) {

    // Save name fields
    if (isset($_POST['first_name'])) {
        update_user_meta($user_id, 'first_name', sanitize_text_field($_POST['first_name']));
    }
    if (isset($_POST['last_name'])) {
        update_user_meta($user_id, 'last_name', sanitize_text_field($_POST['last_name']));
    }

    update_user_meta($user_id, 'account_status', 'pending');

    // Fetch updated user data
    $first = get_user_meta($user_id, 'first_name', true);
    $last  = get_user_meta($user_id, 'last_name', true);
    $user  = get_userdata($user_id);

    $approval_email = '';
    if (function_exists('get_field')) {
        $approval_email = (string) get_field('account_approval_email', 'option');
    }
    if (!$approval_email) {
        $approval_email = get_option('admin_email');
    }

    $approve_url = wp_nonce_url(
        add_query_arg(
            [
                'approve_user' => $user_id,
                'from_email'   => 1,
            ],
            admin_url('users.php')
        ),
        'approve_user_' . $user_id
    );

    $subject = 'New account awaiting approval';
    $message  = "A new user has registered and is awaiting approval.\n\n";
    $message .= "Name: {$first} {$last}\n";
    $message .= "Email: {$user->user_email}\n\n";
    $message .= "Approve this account:\n{$approve_url}\n";

    wp_mail($approval_email, $subject, $message);
});


//block login if account is pending
add_filter('wp_authenticate_user', function ($user) {
    if (is_wp_error($user)) {
        return $user;
    }

    $status = get_user_meta($user->ID, 'account_status', true);

    if ($status === 'pending') {
        return new WP_Error(
            'account_pending',
            __('Your account is awaiting approval by an administrator.', 'your-textdomain')
        );
    }

    return $user;
});

add_filter('user_row_actions', function ($actions, $user) {
    $status = get_user_meta($user->ID, 'account_status', true);

    if ($status === 'pending' && current_user_can('manage_options')) {
        $approve_url = wp_nonce_url(
            add_query_arg(
                [
                    'approve_user' => $user->ID,
                    'from_list'    => 1,
                ],
                admin_url('users.php')
            ),
            'approve_user_' . $user->ID
        );

        $actions['approve_user'] = '<a href="' . esc_url($approve_url) . '">Approve account</a>';
    }

    return $actions;
}, 10, 2);

add_action('admin_init', function () {
    if (!isset($_GET['approve_user'])) {
        return;
    }

    if (!current_user_can('manage_options')) {
        return;
    }

    $user_id = absint($_GET['approve_user']);
    if (!$user_id) {
        return;
    }

    if (!isset($_GET['_wpnonce']) || !wp_verify_nonce($_GET['_wpnonce'], 'approve_user_' . $user_id)) {
        return;
    }

    update_user_meta($user_id, 'account_status', 'approved');

    $redirect = remove_query_arg(['approve_user', '_wpnonce', 'from_email', 'from_list']);
    $redirect = add_query_arg(['user_approved' => $user_id], $redirect);

    wp_safe_redirect($redirect);
    exit;
});


add_action('updated_user_meta', function ($meta_id, $user_id, $meta_key, $meta_value) {
    if ($meta_key !== 'account_status' || $meta_value !== 'approved') {
        return;
    }

    $user = get_userdata($user_id);
    if (!$user) {
        return;
    }

    // Generate reset key
    $reset_key = get_password_reset_key($user);

    if (is_wp_error($reset_key)) {
        return;
    }

    // Build reset URL
    $reset_url = add_query_arg(
        [
            'key'   => $reset_key,
            'login' => rawurlencode($user->user_login),
        ],
        wp_lostpassword_url()
    );

    $subject = 'Your account has been approved';
    $message  = "Hi {$user->first_name},\n\n";
    $message .= "Your account has now been approved. To set your password and access your account, click the link below:\n\n";
    $message .= "{$reset_url}\n\n";
    $message .= "If you did not request this, you can ignore this email.";

    wp_mail($user->user_email, $subject, $message);

}, 10, 4);



add_action('admin_notices', function () {
    if (!isset($_GET['user_approved'])) {
        return;
    }

    $user_id = absint($_GET['user_approved']);
    if (!$user_id) {
        return;
    }

    $user = get_userdata($user_id);
    if (!$user) {
        return;
    }

    echo '<div class="notice notice-success is-dismissible"><p>';
    echo esc_html(sprintf('User %s has been approved.', $user->user_email));
    echo '</p></div>';
});

add_filter('manage_users_columns', function ($columns) {
    $columns['account_status'] = 'Status';
    return $columns;
});

add_filter('manage_users_custom_column', function ($value, $column_name, $user_id) {
    if ($column_name === 'account_status') {
        $status = get_user_meta($user_id, 'account_status', true);

        if ($status === 'pending') {
            return '<span style="color:#d63638;font-weight:600;">Pending</span>';
        }

        if ($status === 'approved') {
            return '<span style="color:#008a20;font-weight:600;">Approved</span>';
        }

        return '<span style="color:#646970;">—</span>';
    }

    return $value;
}, 10, 3);

add_action('admin_head-users.php', function () {
    ?>
    <style>
        tr.pending-user td {
            background: #fff4f4 !important;
        }
    </style>
    <?php
});

add_filter('user_row_actions', function ($actions, $user) {
    if (get_user_meta($user->ID, 'account_status', true) === 'pending') {
        echo '<script>
            document.addEventListener("DOMContentLoaded", function() {
                var row = document.getElementById("user-' . $user->ID . '");
                if (row) row.classList.add("pending-user");
            });
        </script>';
    }
    return $actions;
}, 9, 2);


add_filter('allow_password_reset', function ($allow, $user_id) {
    $status = get_user_meta($user_id, 'account_status', true);

    if ($status === 'pending') {
        return new WP_Error('account_pending', 'Your account is awaiting approval by an administrator.');
    }

    return $allow;
}, 10, 2);

add_filter('woocommerce_add_success', function ($message) {
    if (strpos($message, 'Your account was created successfully') !== false) {
        return 'Your account has been created and is now pending approval. You will receive an email once an administrator approves your account.';
    }

    return $message;
});

add_filter('woocommerce_account_menu_items', function($items) {
    unset($items['edit-address']);
    unset($items['edit-addresses']);
    return $items;
});

add_action('init', function () {
    add_rewrite_endpoint('wishlist', EP_ROOT | EP_PAGES);
});

add_filter('woocommerce_account_menu_items', function ($items) {
    $new = [];
    foreach ($items as $key => $label) {
        if ($key === 'edit-account') {
            $new['wishlist'] = 'Wishlist';
        }
        $new[$key] = $label;
    }
    return $new;
});

add_action('woocommerce_account_wishlist_endpoint', function () {
    wc_get_template('myaccount/wishlist.php');
});

// Remove Tags, Stock, and SKU columns from products admin list
add_filter('manage_edit-product_columns', function ($columns) {
    unset($columns['product_tag']); // Tags
    unset($columns['is_in_stock']);  // Stock
    unset($columns['sku']);          // SKU
    return $columns;
}, 20);
