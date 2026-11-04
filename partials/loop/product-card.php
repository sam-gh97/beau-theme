<?php
    $product = $args['product'] ?? null;
    if (! $product) {
        return;
    }
?>

<article class="product-card relative flex flex-col h-full p-4">
    <?php if ($product->isFeatured()): ?>
        <div class="absolute -top-[14px] left-[50%] -translate-x-1/2 border-1 border-black px-2 py-[4px] text-sm font-futura-pt font-medium uppercase z-10 whitespace-nowrap bg-white">Beau Co Approved</div>
    <?php endif; ?>
    
    <a href="<?= $product->getPermalink(); ?>" class="block mb-4">
        <div class="aspect-square overflow-hidden bg-neutral-100 flex items-center justify-center">
            <?php if ($product->getImageUrl('large')) : ?>
                <img src="<?= $product->getImageUrl('large'); ?>" alt="<?= $product->getTitle(); ?>" class="w-full h-full object-cover object-center">
            <?php else : ?>
                <span class="text-neutral-400 body-2">No image</span>
            <?php endif; ?>
        </div>
    </a>

    <?php if ($product->getBrand()): ?>
        <div class="body-2 text-neutral-500">
            <span><?= esc_html($product->getBrand()); ?></span>
        </div>
    <?php endif; ?>

    <h3 class="display-3 !mb-4">
        <a href="<?= $product->getPermalink(); ?>" class="hover:underline"><?= $product->getTitle(); ?></a>
    </h3>

    <?php if (is_user_logged_in() && $product->getPrice()): ?>
        <div class="body-2 text-black font-medium mb-4">
            <?= wc_price($product->getPrice()); ?>
        </div>
    <?php endif; ?>

    <a href="<?= $product->getPermalink(); ?>" class="ui-btn text-center mt-auto mb-4">Find out more</a>

    <div class="flex justify-end items-center">
        <?php if (is_user_logged_in()) : ?>
            <button 
                class="wishlist-btn flex items-center gap-2 body-2 cursor-pointer hover:opacity-70 transition-opacity"
                data-product-id="<?= $product->getId(); ?>"
                data-product-title="<?= esc_attr($product->getTitle()); ?>"
                data-product-url="<?= esc_url($product->getPermalink()); ?>"
                data-product-image="<?= esc_url($product->getImageUrl('large')); ?>"
                aria-label="Add to wishlist"
            >
                <svg width="22" height="19" viewBox="0 0 22 19" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M18.9209 2.09907C16.7888 -0.0330236 13.3314 -0.0330236 11.1993 2.09907C10.9364 2.362 10.7082 2.64561 10.51 2.94323C10.3118 2.64561 10.0836 2.362 9.82066 2.09974C7.68856 -0.0323563 4.23117 -0.0323563 2.09907 2.09974C-0.0330236 4.23183 -0.0330236 7.68923 2.09907 9.82132L10.51 18.2316L18.9209 9.82066C21.053 7.68856 21.053 4.23183 18.9209 2.09907Z" stroke="black" stroke-miterlimit="10" stroke-linecap="square"/>
                </svg>
            </button>
        <?php else : ?>
            <button 
                class="wishlist-login-trigger flex items-center gap-2 body-2 cursor-pointer hover:opacity-70 transition-opacity"
                aria-label="Add to wishlist"
            >
                <svg width="22" height="19" viewBox="0 0 22 19" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M18.9209 2.09907C16.7888 -0.0330236 13.3314 -0.0330236 11.1993 2.09907C10.9364 2.362 10.7082 2.64561 10.51 2.94323C10.3118 2.64561 10.0836 2.362 9.82066 2.09974C7.68856 -0.0323563 4.23117 -0.0323563 2.09907 2.09974C-0.0330236 4.23183 -0.0330236 7.68923 2.09907 9.82132L10.51 18.2316L18.9209 9.82066C21.053 7.68856 21.053 4.23183 18.9209 2.09907Z" stroke="black" stroke-miterlimit="10" stroke-linecap="square"/>
                </svg>
            </button>
        <?php endif; ?>
    </div>

    <span class="border-left"></span>
    <span class="border-right"></span>
    <span class="border-bottom-left"></span>
    <span class="border-bottom-right"></span>
</article>