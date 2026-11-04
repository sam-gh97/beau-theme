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
        <div class="container-sm">
            <?php if(get_field('content')): ?>
                <div class="body-content"><?= get_field('content'); ?></div>
            <?php endif; ?>
        </div>
    </div>
</div>

