<?php
    $anim = get_field('animation') ?: 'none';
    $anim_class = $anim !== 'none' ? 'anim-' . $anim : '';
?>

<?php if( !empty( $block['data']['_is_preview'] ) ) : ?>
    <div class="aspect-video flex items-center justify-center">
        <img src="<?= get_image('previews/main-hero.png'); ?>" class="w-full object-contain" alt="">
    </div>

    <?php return; ?>
<?php endif; ?>

<div <?= get_block_wrapper_attributes(['class' => $anim_class]); ?>>
    <div class="custom-acf-block bg-primary-500">

            <div class="py-8">
                <div class="container">
                    <?php if(get_field('title')): ?>
                        <h1 class="heading-1-xl text-center"><?= get_field('title'); ?></h1>
                    <?php endif; ?>
                </div>

                <div class="container-sm">
                    <?php if(get_field('content')): ?>
                        <p class="body-1 text-center mt-3"><?= get_field('content'); ?></p>
                    <?php endif; ?>
                </div>
            </div>

        <?php if(get_field('image')): ?>
            <div class="relative h-auto md:h-[440px] overflow-hidden">
                <img src="<?= get_field('image'); ?>" class="w-full h-full object-contain md:object-cover object-center">
            </div>
        <?php endif; ?>

        <div class="container pt-8 mb-5">
            <?php if(get_field('title')): ?>
                <h1 class="heading-2 text-center"><?= get_field('footer_title'); ?></h1>
            <?php endif; ?>
        </div>

        <?php if ( have_rows('footer_carousel') ) : ?>
            <div class="carousel-container pb-8">
                <div class="carousel-track">
                    <?php 
                    $keywords = [];
                    while( have_rows('footer_carousel') ) : the_row();
                        $keywords[] = get_sub_field('keyword');
                    endwhile;
                    
                    for ($i = 0; $i < 2; $i++) :
                        foreach ($keywords as $keyword) :
                    ?>
                        <span class="carousel-keyword uppercase"><?= esc_html($keyword); ?></span>
                        <span class="carousel-dot">•</span>
                    <?php 
                        endforeach;
                    endfor;
                    ?>
                </div>
            </div>
        <?php endif; ?>

    </div>
</div>

