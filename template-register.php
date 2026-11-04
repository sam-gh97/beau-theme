<?php
/**
 * Template Name: WooCommerce Register
    * Description: Page template for displaying WooCommerce login page.
 */

 defined('ABSPATH') || exit;

 if (is_user_logged_in()) {
     wp_safe_redirect(get_permalink(get_option('woocommerce_myaccount_page_id')));
     exit;
 }
 
 get_header();
 ?>
 
 <div class="py-8 background-cream">
    <div class="container">
        <div class="text-center">
            <h1 class="heading-1 mb-7">Register</h1>
        </div>
    </div>

    <div class="container-sm">
        <?php wc_get_template('myaccount/form-login.php'); ?>
    </div>
 </div>

<?php get_footer(); ?>