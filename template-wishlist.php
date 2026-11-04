<?php
/**
 * Template Name: Wishlist
 * Description: Page template for displaying user's wishlist. Use only on the wishlist page.
 */

get_header();
?>

<section class="py-16">
    <div class="container">
    <div class="text-center mb-7">
        <h1 class="heading-1">Your Wishlist</h1>
    </div>

    <?php if (is_user_logged_in()) : ?>
        <div id="wishlist-empty" class="text-center py-16" style="display: none;">
            <h2 class="display-2 mb-4">Your wishlist is empty</h2>
            <p class="mb-6">Start adding products to your wishlist by clicking the heart icon</p>
            <a href="<?= esc_url(get_permalink(wc_get_page_id('shop'))); ?>" class="ui-btn inline-block">
                Browse Products
            </a>
        </div>

        <div id="wishlist-products" class="grid grid-cols-1 xs:grid-cols-2 lg:grid-cols-3 gap-5">
            <!-- Products -->
        </div>
    <?php else : ?>
        <div class="text-center mt-5">
            <p class="mb-6">You need to be logged in to view your wishlist</p>
            <a href="<?= home_url('/log-in/'); ?>" class="ui-btn inline-block">
                Sign in or register
            </a>
        </div>
    <?php endif; ?>
    </div>
</section>

<?php if (is_user_logged_in()) : ?>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        if (window.wishlistManager) {
            window.wishlistManager.renderWishlistPage();
        }
    });
</script>
<?php endif; ?>

<?php
get_footer();

