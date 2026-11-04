<?php
    $padding_top = get_field('padding_top');
    $padding_bottom = get_field('padding_bottom');
    $background_color = get_field('background_colour');
    $image_side = get_field('image_side');
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

        <div class="container">
            <div class="grid grid-cols-1 md:grid-cols-2 items-center gap-8">
                <?php 
                    $image = get_field('image');
                    $is_image_left = ($image_side === 'left');
                    $text_order = $is_image_left ? 'order-1 md:order-2' : 'order-1 md:order-1';
                    $image_order = $is_image_left ? 'order-2 md:order-1' : 'order-2 md:order-2';
                ?>
                
                <div class="<?= $text_order; ?>">
                    <?php if(get_field('title')): ?>
                        <h2 class="heading-2 mb-4"><?= get_field('title'); ?></h2>
                    <?php endif; ?>

                    <?php if(get_field('content')): ?>
                        <div class="body-content"><?= get_field('content'); ?></div>
                    <?php endif; ?>
                </div>

                <?php if($image):
                    $image_url = is_array($image) ? $image['url'] : $image;
                    $image_alt = is_array($image) ? ($image['alt'] ?? '') : '';
                ?>
                    <div class="aspect-[2/3] overflow-hidden <?= $image_order; ?>">
                        <img src="<?= esc_url($image_url); ?>" class="w-full h-full object-cover object-center" alt="<?= esc_attr($image_alt); ?>">
                    </div>
                <?php endif; ?>
            </div>


        </div>

    </div>
</div>

