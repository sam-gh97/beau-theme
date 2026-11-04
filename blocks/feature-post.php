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
            <div class="grid grid-cols-1 md:grid-cols-2 items-start bg-black">

                <div class="col-span-1 text-white px-7 py-8 order-1 md:order-2">
                    <div class="w-full h-full flex flex-col justify-center">
                        <?php if(get_field('sub_title')): ?>
                            <h2 class="body-2 uppercase mb-2"><?= get_field('sub_title'); ?></h2>
                        <?php endif; ?>

                        <?php if(get_field('title')): ?>
                            <h2 class="display-1 mb-2 line-clamp-3"><?= get_field('title'); ?></h2>
                        <?php endif; ?>

                        <?php if(get_field('content')): ?>
                            <p class="body-2 line-clamp-3 mb-5"><?= get_field('content'); ?></p>
                        <?php endif; ?>

                        <?php if(get_field('link')): ?>
                            <a href="<?= get_field('link')['url']; ?>" class="ui-btn ui-btn--white self-start">
                                <?= get_field('link')['title']; ?>
                            </a>
                        <?php endif; ?>
                    </div>
                </div>

                <?php $img = get_field('image'); ?>
                <div
                    class="bg-cover bg-center h-[300px] md:h-full order-2 md:order-1"
                    style="background-image: url('<?= $img ? $img['url'] : ''; ?>');"
                ></div>

            </div>
        </div>


    </div>
</div>

