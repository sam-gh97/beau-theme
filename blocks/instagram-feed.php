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
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-7">
                <?php if(get_field('title')): ?>
                    <h2 class="heading-2"><?= get_field('title'); ?></h2>
                <?php endif; ?>
                <?php if(get_field('handle')): 
                    $instagram_link = get_field('instagram_link');
                ?>
                    <p class="body-1 underline">
                        <?php if($instagram_link && !empty($instagram_link)): ?>
                            <a href="<?= $instagram_link; ?>" target="_blank"><?= get_field('handle'); ?></a>
                        <?php else: ?>
                            <?= get_field('handle'); ?>
                        <?php endif; ?>
                    </p>
                <?php endif; ?>
            </div>

            <?php if(get_field('shortcode')): ?>
                <div>
                    <?= do_shortcode(get_field('shortcode')); ?>
                </div>
            <?php endif; ?>
        </div>

    </div>
</div>

