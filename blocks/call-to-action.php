<?php
    $padding_top = get_field('padding_top');
    $padding_bottom = get_field('padding_bottom');
    $full_width = get_field('full_width');
    $link = get_field('link');
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
    <div class="custom-acf-block padding-top-<?= $padding_top ?> padding-bottom-<?= $padding_bottom ?>">

        <?php if (!$full_width): ?>
            <div class="container">
        <?php endif; ?>

        <div class="relative py-9 px-8 text-center bg-black text-white">
            <?php if(get_field('background_image')): ?>
                <img src="<?= get_field('background_image')['url']; ?>" class="absolute top-0 left-0 w-full h-full object-cover z-0">
                <div class="absolute top-0 left-0 w-full h-full bg-black opacity-60 z-[1]"></div>
            <?php endif; ?>
            <div class="relative z-10">
                <div class="container-sm">

                    <h2 class="heading-2 mb-4"><?= get_field('title'); ?></h2>
                    <p class="mb-6"><?= get_field('content'); ?></p>
                    <a href="<?= $link['url']; ?>" class="ui-btn ui-btn--white"><?= $link['title']; ?></a>

                </div>
            </div>
        </div>

        <?php if (!$full_width): ?>
            </div>
        <?php endif; ?>

    </div>
</div>

