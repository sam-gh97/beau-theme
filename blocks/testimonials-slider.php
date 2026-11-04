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

            <div class="flex flex-col md:flex-row justify-between gap-7">
                <div class="w-full md:w-[450px] flex flex-col justify-between">
                    <h2 class="heading-2 mb-5 text-center md:text-left"><?= get_field('title'); ?></h2>
                    
                    <div class="hidden md:flex items-center">
                        <div class="swiper-button-prev testimonial-nav-prev">
                            <img src="<?= get_image('arrow.svg'); ?>" alt="Previous" class="testimonial-arrow-icon testimonial-arrow-prev">
                        </div>
                        <div class="testimonial-nav-separator"></div>
                        <div class="swiper-button-next testimonial-nav-next">
                            <img src="<?= get_image('arrow.svg'); ?>" alt="Next" class="testimonial-arrow-icon testimonial-arrow-next">
                        </div>
                    </div>
                </div>

                <div class="flex-1 min-w-0">
                    <img src="<?= get_image('quote.svg'); ?>" alt="Quote" class="w-6 mb-4">
                    <?php if ( have_rows('testimonials') ) : ?>
                        <div class="testimonial-slider swiper overflow-hidden" data-swiper="testimonials">
                            <div class="swiper-wrapper">
                            
                                <?php while( have_rows('testimonials') ) : the_row(); 
                                    $quote = get_sub_field('quote');
                                    $author = get_sub_field('author');
                                    $position = get_sub_field('job_title');
                                    $profile_picture = get_sub_field('profile_picture');
                                ?>
                            
                                    <div class="swiper-slide w-full">
                                        <div class="min-h-[unset] md:min-h-[320px] flex flex-col justify-between">
                                            <?php if ($quote): ?>
                                                <div class="mb-6 md:mb-2">
                                                    <p class="body-1"><?= esc_html($quote); ?></p>
                                                </div>
                                            <?php endif; ?>
                                            
                                            <div class="flex items-center gap-4 flex-wrap">
                                                <?php if ($profile_picture): ?>
                                                    <div class="flex-shrink-0">
                                                        <?php if (is_array($profile_picture)): ?>
                                                            <img src="<?= esc_url($profile_picture['url']); ?>" alt="<?= esc_attr($profile_picture['alt'] ?? $author); ?>" class="w-[52px] h-[52px] rounded-full object-cover">
                                                        <?php else: ?>
                                                            <img src="<?= esc_url($profile_picture); ?>" alt="<?= esc_attr($author); ?>" class="w-16 h-16 rounded-full object-cover">
                                                        <?php endif; ?>
                                                    </div>
                                                <?php endif; ?>
                                                
                                                <div>
                                                    <?php if ($author): ?>
                                                        <p><?= esc_html($author); ?></p>
                                                    <?php endif; ?>
                                                    <?php if ($position): ?>
                                                        <p class="body-2"><?= esc_html($position); ?></p>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                            
                                <?php endwhile; ?>
                            
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

        </div>

    </div>
</div>

