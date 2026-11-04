<?php
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
    <div class="custom-acf-block py-9 background-cream">

        <div class="container">
            <?php if(get_field('sub_title')): ?>
                <p class="s-1 uppercase text-center mb-2"><?= get_field('sub_title'); ?></p>
            <?php endif; ?>

            <?php if(get_field('title')): ?>
                <h1 class="heading-1 text-center mb-3"><?= get_field('title'); ?></h1>
            <?php endif; ?>
        </div>

        <div class="container-sm">
            <?php if(get_field('content')): ?>
                <p class="text-center"><?= get_field('content'); ?></p>
            <?php endif; ?>
        </div>

    </div>
</div>

