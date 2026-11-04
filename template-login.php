<?php
/**
 * Template Name: WooCommerce Login
 * Description: Page template for displaying WooCommerce login page. Use only on the login page.
 */

get_header();
?>


<section class="py-8 background-cream">
    <div class="container">
        <div class="text-center">
            <?php 
            $current_url = $_SERVER['REQUEST_URI'];
            $is_lost_password = strpos($current_url, '/lost-password') !== false || 
                               isset($_GET['show-reset-form']) || 
                               isset($_GET['reset-link-sent']);
            $heading = $is_lost_password ? 'Reset Password' : 'Login';
            ?>
            <h1 class="heading-1 mb-7"><?= esc_html($heading); ?></h1>
        </div>
    </div>

    <div class="container-sm">
        <?= do_shortcode('[woocommerce_my_account]'); ?>
    </div>
</section>

<?php get_footer(); ?>