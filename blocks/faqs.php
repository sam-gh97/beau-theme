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
            <?php if(get_field('title')): ?>
                <h2 class="heading-2 mb-3 text-center"><?= get_field('title'); ?></h2>
            <?php endif; ?>
            
            <?php if(get_field('content')): ?>
                <p class="text-center"><?= get_field('content'); ?></p>
            <?php endif; ?>
            
            <div class="mt-7 space-y-4">
                <?php if ( have_rows('faqs') ) : 
                    $index = 0;
                ?>
                    <?php while( have_rows('faqs') ) : the_row(); 
                        $index++;
                        $is_first = $index === 1;
                        $title = get_sub_field('title');
                        $content = get_sub_field('content');
                        $link = get_sub_field('link');
                    ?>
                        <details <?= $is_first ? 'open' : ''; ?> class="border-b border-neutral-200 pb-4">
                            <summary class="cursor-pointer display-2 !font-normal py-2 list-none">
                                <div class="flex items-center justify-between">
                                    <span><?= esc_html($title); ?></span>
                                    <img src="<?= get_image('icons/chevron-down.svg'); ?>" alt="" class="w-5 h-5 transition-transform duration-200 flex-shrink-0 ml-4 details-chevron">
                                </div>
                            </summary>
                            <div class="pt-3 pb-2">
                                <?php if ($content): ?>
                                    <div class="mb-4"><?= wp_kses_post($content); ?></div>
                                <?php endif; ?>
                                <?php if ($link && !empty($link['url'])): ?>
                                    <a href="<?= esc_url($link['url']); ?>" target="<?= esc_attr($link['target'] ?? '_self'); ?>" class="ui-link">
                                        <?= esc_html(!empty($link['title']) ? $link['title'] : 'Learn more'); ?>
                                    </a>
                                <?php endif; ?>
                            </div>
                        </details>
                    <?php endwhile; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>