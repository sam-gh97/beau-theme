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
        <div class="container">
            <?php if(get_field('title')): ?>
                <h2 class="heading-2 text-center mb-7"><?= get_field('title'); ?></h2>
            <?php endif; ?>

            <?php if ( have_rows('logos') ) : 
                $logos = [];
                while( have_rows('logos') ) : the_row();
                    $logos[] = get_sub_field('logo');
                endwhile;
                
                if (!empty($logos)):
            ?>
                <div class="logo-carousel swiper" data-swiper="logos">
                    <div class="swiper-wrapper">
                        <?php 
                        foreach ($logos as $logo): 
                        ?>
                            <div class="swiper-slide">
                                <div>
                                    <img src="<?= esc_url($logo); ?>" alt="">
                                </div>
                            </div>
                        <?php endforeach; ?>
                        
                        <?php 
                        if (count($logos) < 12):
                            foreach ($logos as $logo): 
                        ?>
                            <div class="swiper-slide">
                                <div>
                                    <img src="<?= esc_url($logo); ?>" alt="">
                                </div>
                            </div>
                        <?php 
                            endforeach;
                        endif;
                        ?>
                    </div>
                </div>
            <?php 
                endif;
            endif; ?>
        </div>
    </div>
</div>

