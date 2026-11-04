<?php
    $padding_top = get_field('padding_top');
    $padding_bottom = get_field('padding_bottom');
    $background_color = get_field('background_colour');
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

        <?php 
        $images = get_field('images');
        
        if ($images && is_array($images) && count($images) > 0): 
            $original_count = count($images);
            if ($original_count < 6) {
                $duplicated_images = $images;
                while (count($duplicated_images) < 6) {
                    $duplicated_images = array_merge($duplicated_images, $images);
                }
                $images = $duplicated_images;
            }
        ?>
            <div class="images-slider swiper overflow-hidden" data-swiper="images" style="height: 560px;">
                <div class="swiper-wrapper">
                    <?php foreach ($images as $image): 
                        if ($image && isset($image['url'])):
                    ?>
                        <div class="swiper-slide" style="width: auto; height: 560px;">
                            <div class="block h-full">
                                <img src="<?= esc_url($image['url']); ?>" 
                                     alt="<?= esc_attr($image['alt'] ?? ''); ?>" 
                                     class="h-full w-auto object-contain"
                                     style="height: 560px;">
                            </div>
                        </div>
                    <?php 
                        endif;
                    endforeach; ?>
                </div>
            </div>
        <?php endif; ?>

    </div>
</div>

