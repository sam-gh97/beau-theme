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

        <?php if(get_field('title') || get_field('content')): ?>
            <div class="container-sm mb-7 ">
                <?php if(get_field('title')): ?>
                    <h2 class="heading-2 text-center"><?= get_field('title'); ?></h2>
                <?php endif; ?>

                <?php if(get_field('content')): ?>
                    <div class="text-center mt-3"><?= get_field('content'); ?></div>
                <?php endif; ?>
            </div>
        <?php endif; ?>

        <?php
        $thumbnail = get_field('thumbnail');
        $video_url = get_field('video_url');
        ?>

        <div class="container-xl">
            <div class="aspect-video w-full h-full relative overflow-hidden">
                <?php if ($thumbnail): ?>
                    <?php if ($video_url): ?>
                        <a href="<?= esc_url($video_url); ?>" data-fancybox="video" class="block w-full h-full relative group">
                            <img src="<?= esc_url($thumbnail['url']); ?>" 
                                alt="<?= esc_attr($thumbnail['alt'] ?? ''); ?>" 
                                class="w-full h-full object-cover">
                            <div class="absolute inset-0 flex items-center justify-center bg-black/30 group-hover:bg-black/40 transition-colors">
                                <svg width="100" height="100" viewBox="0 0 100 100" fill="none" xmlns="http://www.w3.org/2000/svg" class="group-hover:scale-110 transition-transform">
                                    <path d="M66.0883 49.9978L38.9969 34.3574V65.6381L66.0883 49.9978Z" fill="white"/>
                                    <path d="M49.9978 98.7603C76.9291 98.7603 98.7613 76.9282 98.7613 49.9969C98.7613 23.0655 76.9291 1.2334 49.9978 1.2334C23.0665 1.2334 1.23438 23.0655 1.23438 49.9969C1.23438 76.9282 23.0665 98.7603 49.9978 98.7603Z" stroke="white" stroke-width="3" stroke-miterlimit="10"/>
                                </svg>
                            </div>
                        </a>
                    <?php else: ?>
                        <img src="<?= esc_url($thumbnail['url']); ?>" 
                            alt="<?= esc_attr($thumbnail['alt'] ?? ''); ?>" 
                            class="w-full h-full object-cover">
                    <?php endif; ?>
                <?php else: ?>
                    <div class="w-full h-full flex items-center justify-center">
                        <svg width="100" height="100" viewBox="0 0 100 100" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M66.0883 49.9978L38.9969 34.3574V65.6381L66.0883 49.9978Z" fill="white"/>
                            <path d="M49.9978 98.7603C76.9291 98.7603 98.7613 76.9282 98.7613 49.9969C98.7613 23.0655 76.9291 1.2334 49.9978 1.2334C23.0665 1.2334 1.23438 23.0655 1.23438 49.9969C1.23438 76.9282 23.0665 98.7603 49.9978 98.7603Z" stroke="white" stroke-width="3" stroke-miterlimit="10"/>
                        </svg>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

