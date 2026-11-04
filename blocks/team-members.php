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

        <div class="container-sm mb-8">
            <?php if(get_field('title')): ?>
                <h2 class="heading-2 mb-3 text-center"><?= get_field('title'); ?></h2>
            <?php endif; ?>
            
            <?php if(get_field('content')): ?>
                <p class="text-center"><?= get_field('content'); ?></p>
            <?php endif; ?>
        </div>

        <?php if ( have_rows('team_members') ) : ?>
            <div class="container">
                <div class="flex flex-col gap-8">
                    <?php 
                    $index = 0;
                    while( have_rows('team_members') ) : the_row(); 
                        $is_even = $index % 2 === 1;
                        $image_order = $is_even ? 'order-2 md:order-2' : 'order-2 md:order-1';
                        $text_order = $is_even ? 'order-1 md:order-1' : 'order-1 md:order-2';
                    ?>
                
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-7 items-center">

                            <div class="aspect-square overflow-hidden <?= $image_order; ?>">
                                <img src="<?= get_sub_field('image')['url']; ?>" alt="<?= get_sub_field('image')['alt']; ?>" class="w-full h-full object-cover object-center">
                            </div>

                            <div class="<?= $text_order; ?>">
                                <h3 class="heading-3"><?= get_sub_field('name'); ?></h3>
                                <p class="body-1 mb-3"><?= get_sub_field('position'); ?></p>
                                <div class="body-content"><?= get_sub_field('bio'); ?></div>
                            </div>

                        </div>
                
                    <?php 
                        $index++;
                    endwhile; 
                    ?>
                </div>
            </div>
        <?php endif; ?>
    
    </div>
</div>