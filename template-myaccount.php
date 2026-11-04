<?php
/**
 * Template Name: WooCommerce MyAccount
    * Description: Page template for displaying WooCommerce account page.
 */

 defined('ABSPATH') || exit;
 get_header();
 ?>
 
<div class="py-8 background-cream">
   <div class="container account-page">
       <?= do_shortcode('[woocommerce_my_account]'); ?>
   </div>
</div>

<?php get_footer(); ?>