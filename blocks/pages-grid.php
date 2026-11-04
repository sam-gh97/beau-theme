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
                <h2 class="heading-2 text-center"><?= get_field('title'); ?></h2>
            <?php endif; ?>
            
            <?php if(get_field('content')): ?>
                <div class="text-center mt-3"><?= get_field('content'); ?></div>
            <?php endif; ?>
        </div>

        <div class="container">
            <?php 
                $pages = get_field('pages');
                $page_count = $pages ? count($pages) : 0;
                $grid_cols = $page_count === 2 ? 'grid-cols-1 md:grid-cols-2' : 'grid-cols-1 md:grid-cols-3';
            ?>

            <div class="grid <?= $grid_cols; ?> gap-4 mt-7">
                <?php if(have_rows('pages')): ?>
                    <?php while(have_rows('pages')): the_row(); 
                        $link = get_sub_field('link');
                    ?>
                        <div class="page-card">
                            <?php if($link && !empty($link['url'])): ?>
                                <a href="<?= $link['url']; ?>" target="<?= $link['target'] ?? '_self'; ?>" class="block">
                                    <div class="relative aspect-[4/5] overflow-hidden">
                                        <img src="<?= get_sub_field('image')['url']; ?>" alt="<?= get_sub_field('image')['alt']; ?>" class="w-full h-full object-cover object-center transition-transform duration-300 hover:scale-105">
                                    </div>
                                    <h3 class="heading-3 mt-2"><?= !empty($link['title']) ? $link['title'] : $link['url']; ?></h3>
                                </a>
                            <?php else: ?>
                                <div class="relative aspect-[4/5] overflow-hidden">
                                    <img src="<?= get_sub_field('image')['url']; ?>" alt="<?= get_sub_field('image')['alt']; ?>" class="w-full h-full object-cover object-center transition-transform duration-300 hover:scale-105">
                                </div>
                            <?php endif; ?>
                        </div>
                    <?php endwhile; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

