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
            <div class="bg-black text-white p-7 grid grid-cols-1 md:grid-cols-2 items-center gap-8">
                <div>
                    <h2 class="heading-2-sm mb-3"><?= get_field('cta_title', 'options'); ?></h2>
                    <p><?= get_field('cta_content', 'options'); ?></p>
                </div>

                <div>
                    <?= do_shortcode('[contact-form-7 id="' . get_field('newsletter_cta_form', 'options') . '" title="Newsletter Inline"]'); ?>
                </div>
            </div>
        </div>
    </div>
</div>

