<?php
    $padding_top = get_field('padding_top');
    $padding_bottom = get_field('padding_bottom');
    $background_color = get_field('background_colour');
    $column_count = get_field('column_count') ?: 3;
    $grid_cols = $column_count == 4 ? 'grid-cols-2 md:grid-cols-4' : 'grid-cols-1 md:grid-cols-3';
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
                <h2 class="heading-2 mb-3 text-center"><?= get_field('title'); ?></h2>
            <?php endif; ?>
            
            <?php if(get_field('content')): ?>
                <p class="text-center"><?= get_field('content'); ?></p>
            <?php endif; ?>
        </div>

        <div class="container mt-7">
            <div class="grid <?= $grid_cols; ?> gap-5">
                <?php if(have_rows('icons')): ?>
                    <?php while(have_rows('icons')): the_row(); ?>
                        <div class="col-span-1 text-center">
                            <?php if(get_sub_field('icon')): ?>
                                <div class="w-[50px] h-[50px] mx-auto flex items-center justify-center">
                                    <img src="<?= get_sub_field('icon'); ?>" alt="<?= get_sub_field('title'); ?>" class="mx-auto object-contain">
                                </div>
                            <?php endif; ?>

                            <?php if(get_sub_field('title')): ?>
                                <h2 class="heading-3 mb-1 "><?= get_sub_field('title'); ?></h2>
                            <?php endif; ?>

                            <?php if(get_sub_field('content')): ?>
                                <p><?= get_sub_field('content'); ?></p>
                            <?php endif; ?>
                        </div>
                    <?php endwhile; ?>
                <?php endif; ?>
            </div>
        </div>

    </div>
</div>

