<?php
    $padding_top = get_field('padding_top');
    $padding_bottom = get_field('padding_bottom');
    $background_color = get_field('background_colour');

    use Boilerplate\Models\Product;
    $product_ids = get_field('products');
    $products = $product_ids ? Product::getByIds($product_ids) : [];
    $anim = get_field('animation') ?: 'none';
    $anim_class = $anim !== 'none' ? 'anim-' . $anim : '';
?>

<?php if( !empty( $block['data']['_is_preview'] ) ) : ?>
    <div class="aspect-video flex items-center justify-center">
        <img src="<?= get_image('previews/intro-content.png'); ?>" class="w-full object-contain" alt="">
    </div>

    <?php return; ?>
<?php endif; ?>

<div <?= get_block_wrapper_attributes(['class' => $anim_class]); ?>>
    <div class="custom-acf-block padding-top-<?= $padding_top ?> padding-bottom-<?= $padding_bottom ?> background-<?= $background_color; ?>">

        <div class="container-sm">
            <?php if(get_field('title')): ?>
                <h2 class="heading-2 text-center"><?= get_field('title'); ?></h2>
            <?php endif; ?>
            
            <?php if(get_field('content')): ?>
                <div class="text-center mt-3"><?= get_field('content'); ?></div>
            <?php endif; ?>
        </div>

        <?php if (!empty($products)): ?>
            <div class="container mt-7 relative px-0 xl:px-16">
                <div class="swiper-button-prev product-nav-prev !hidden xl:!flex">
                    <img src="<?= get_image('arrow.svg'); ?>" alt="Previous" class="product-arrow-icon product-arrow-prev">
                </div>
                <div class="swiper-button-next product-nav-next !hidden xl:!flex">
                    <img src="<?= get_image('arrow.svg'); ?>" alt="Next" class="product-arrow-icon product-arrow-next">
                </div>
                
                <div class="products-slider-wrapper relative">
                    <div class="products-slider swiper overflow-hidden !pt-[16px]" data-swiper="products">
                        <div class="swiper-wrapper">
                            <?php foreach ($products as $product): ?>
                                <div class="swiper-slide relative">
                                    <?php 
                                        get_template_part(
                                            'partials/loop/product-card',
                                            null,
                                            ['product' => $product]
                                        );
                                    ?>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>

    </div>
</div>

