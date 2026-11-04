<?php
defined('ABSPATH') || exit;

$user_id = get_current_user_id();
$wishlist = json_decode(get_user_meta($user_id, 'beau_co_wishlist', true), true) ?: [];
?>


<div class="wishlist-wrapper">
    <h2 class="display-2 mb-5">Your Wishlist</h2>

    <?php if (empty($wishlist)) : ?>

    <p>Your wishlist is empty.</p>

    <?php else : ?>

    <div class="grid grid-cols-1 xs:grid-cols-2 lg:grid-cols-3 gap-5">
        <?php foreach ($wishlist as $item) : ?>
            <article class="product-card relative flex flex-col h-full p-4">
                <a href="<?php echo esc_url($item['url']); ?>" class="block mb-4">
                    <div class="aspect-square overflow-hidden">
                        <img src="<?php echo esc_url($item['image']); ?>" alt="<?php echo esc_attr($item['title']); ?>" class="w-full h-full object-cover object-center">
                    </div>
                </a>

                <h3 class="display-3 !mb-4">
                    <a href="<?php echo esc_url($item['url']); ?>" class="hover:underline">
                        <?php echo esc_html($item['title']); ?>
                    </a>
                </h3>
                <a href="<?php echo esc_url($item['url']); ?>" class="ui-btn text-center mt-auto mb-4">Find out more</a>

                <div class="flex justify-between items-center">
                    <label class="flex items-center gap-2 body-2 cursor-pointer">
                        <input type="checkbox" name="compare[]" value="<?php echo esc_attr($item['id']); ?>" class="compare-checkbox w-[20px] h-[20px] rounded-none cursor-pointer accent-black">
                        <span>Compare</span>
                    </label>
                    <button 
                        class="wishlist-btn flex items-center gap-2 body-2 cursor-pointer hover:opacity-70 transition-opacity in-wishlist"
                        data-product-id="<?php echo esc_attr($item['id']); ?>"
                        data-product-title="<?php echo esc_attr($item['title']); ?>"
                        data-product-url="<?php echo esc_url($item['url']); ?>"
                        data-product-image="<?php echo esc_url($item['image']); ?>"
                        aria-label="Add to wishlist"
                    >
                        <svg width="22" height="19" viewBox="0 0 22 19" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M18.9209 2.09907C16.7888 -0.0330236 13.3314 -0.0330236 11.1993 2.09907C10.9364 2.362 10.7082 2.64561 10.51 2.94323C10.3118 2.64561 10.0836 2.362 9.82066 2.09974C7.68856 -0.0323563 4.23117 -0.0323563 2.09907 2.09974C-0.0330236 4.23183 -0.0330236 7.68923 2.09907 9.82132L10.51 18.2316L18.9209 9.82066C21.053 7.68856 21.053 4.23183 18.9209 2.09907Z" stroke="black" fill="black" stroke-miterlimit="10" stroke-linecap="square"/>
                        </svg>
                    </button>
                </div>

                <span class="border-left"></span>
                <span class="border-right"></span>
                <span class="border-bottom-left"></span>
                <span class="border-bottom-right"></span>
            </article>
        <?php endforeach; ?>
    </div>

    <?php endif; ?>
</div>