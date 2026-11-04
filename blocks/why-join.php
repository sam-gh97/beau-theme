<?php
    $padding_top = get_field('padding_top');
    $padding_bottom = get_field('padding_bottom');
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
    <div class="custom-acf-block padding-top-<?= $padding_top ?> padding-bottom-<?= $padding_bottom ?> ">

        <div class="container">
            <div class="grid grid-cols-1 md:grid-cols-2 items-center gap-8">
                <div class="col-span-1">
                    <h2 class="heading-2"><?= get_field('title'); ?></h2>

                    <?php if ( have_rows('accordion') ) : 
                        $index = 0;
                        $first_image = null;
                    ?>
                        <div class="mt-6 space-y-4 why-join-accordion">
                            <?php while( have_rows('accordion') ) : the_row(); 
                                $index++;
                                $is_first = $index === 1;
                                $title = get_sub_field('title');
                                $content = get_sub_field('content');
                                $image = get_sub_field('image');
                                
                                if ($is_first && $image && isset($image['url'])) {
                                    $first_image = $image;
                                }
                            ?>
                                <div class="why-join-accordion-item border-b border-neutral-200 pb-4 <?= $is_first ? 'is-open' : ''; ?>"
                                    <?php if ($image && isset($image['url'])): ?>
                                        data-image-url="<?= esc_url($image['url']); ?>"
                                        data-image-alt="<?= esc_attr($image['alt'] ?? ''); ?>"
                                    <?php endif; ?>
                                >
                                    <button type="button" class="accordion-trigger cursor-pointer heading-3 py-2 w-full text-left">
                                        <div class="flex items-center justify-between">
                                            <span><?= esc_html($title); ?></span>
                                            <img src="<?= get_image('icons/chevron-down.svg'); ?>" alt="" class="w-5 h-5 transition-transform duration-200 flex-shrink-0 ml-4 accordion-chevron">
                                        </div>
                                    </button>
                                    <div class="accordion-content">
                                        <div class="accordion-content-inner pt-3 pb-2">
                                            <?php if ($content): ?>
                                                <div class="body-content"><?= wp_kses_post($content); ?></div>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            <?php endwhile; ?>
                        </div>
                    <?php endif; ?>
                </div>
                
                <div class="col-span-1 overflow-visible">
                    <div class="bg-primary-800 w-full aspect-[2/3] relative overflow-visible why-join-image-container">
                        <?php if (isset($first_image) && $first_image && isset($first_image['url'])): ?>
                            <img src="<?= esc_url($first_image['url']); ?>" alt="<?= esc_attr($first_image['alt'] ?? ''); ?>" class="why-join-image-bottom" style="position: absolute; inset: 0; width: 100%; height: 100%; object-fit: cover; object-position: center;">
                        <?php else: ?>
                            <img src="" alt="" class="why-join-image-bottom" style="display: none; position: absolute; inset: 0; width: 100%; height: 100%; object-fit: cover; object-position: center;">
                        <?php endif; ?>
                        <img src="" alt="" class="why-join-image-top" style="position: absolute; inset: 0; width: 100%; height: 100%; object-fit: cover; object-position: center; opacity: 0; transition: opacity 0.4s ease-in-out; z-index: 1;">
                        <img src="<?= get_image('stamp-new.svg'); ?>" class="absolute -top-5 -right-5 object-cover xl:top-25 xl:-right-10 w-[100px] h-[100px] md:w-[180px] md:h-[180px] lg:w-[220px] lg:h-[220px] xl:translate-x-1/4 xl:-translate-y-1/4" alt="" style="z-index: 2;">
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php if ( have_rows('accordion') ) : ?>
<style>
.why-join-accordion-item.is-open .accordion-chevron {
    transform: rotate(180deg);
}
.why-join-accordion-item.is-open .accordion-content {
    height: auto !important;
}
.why-join-accordion-item:not(.is-open) .accordion-content {
    height: 0 !important;
    overflow: hidden;
}
</style>
<script>
(function() {
    const accordionContainer = document.querySelector('.why-join-accordion');
    const imageBottom = document.querySelector('.why-join-image-bottom');
    const imageTop = document.querySelector('.why-join-image-top');
    
    if (!accordionContainer || !imageBottom || !imageTop) return;
    
    const accordionItems = Array.from(accordionContainer.querySelectorAll('.why-join-accordion-item'));
    
    function openAccordion(item) {
        const content = item.querySelector('.accordion-content');
        const inner = item.querySelector('.accordion-content-inner');
        
        if (!content || !inner) return;
        
        item.classList.add('is-open');
        content.style.height = inner.offsetHeight + 'px';
        
        const imageUrl = item.getAttribute('data-image-url');
        const imageAlt = item.getAttribute('data-image-alt');
        
        if (imageUrl) {
            imageBottom.style.display = 'block';
            
            const newImage = new Image();
            newImage.onload = function() {
                imageTop.src = imageUrl;
                imageTop.alt = imageAlt || '';
                imageTop.style.opacity = '0';
                
                requestAnimationFrame(() => {
                    imageTop.style.opacity = '1';
                    
                    setTimeout(() => {
                        imageBottom.src = imageUrl;
                        imageBottom.alt = imageAlt || '';
                        imageTop.style.opacity = '0';
                    }, 400);
                });
            };
            newImage.src = imageUrl;
        }
    }
    
    function closeAccordion(item) {
        const content = item.querySelector('.accordion-content');
        if (!content) return;
        
        item.classList.remove('is-open');
        content.style.height = '0px';
    }
    
    accordionItems.forEach((item) => {
        const content = item.querySelector('.accordion-content');
        const inner = item.querySelector('.accordion-content-inner');
        const isOpen = item.classList.contains('is-open');
        
        content.style.overflow = 'hidden';
        content.style.transition = 'height 0.3s ease-in-out';
        content.style.height = (isOpen ? inner.offsetHeight : 0) + 'px';
    });
    
    accordionItems.forEach((item) => {
        const trigger = item.querySelector('.accordion-trigger');
        
        if (trigger) {
            trigger.addEventListener('click', function(e) {
                e.preventDefault();
                
                if (item.classList.contains('is-open')) return;
                
                accordionItems.forEach(otherItem => {
                    if (otherItem !== item && otherItem.classList.contains('is-open')) {
                        closeAccordion(otherItem);
                    }
                });
                
                openAccordion(item);
            });
        }
    });
})();
</script>
<?php endif; ?>
