<?php
    $quote = get_sub_field('quote');
    $author = get_sub_field('author');
    $position = get_sub_field('job_title');
    $profile_picture = get_sub_field('profile_picture');
?>

<article class="testimonial-card bg-primary-500 p-5 mb-5">
    <?php if ($quote): ?>
        <div class="mb-6">
            <img src="<?= get_image('quote.svg'); ?>" alt="Quote" class="w-6 mb-4">
            <p class="body-1"><?= esc_html($quote); ?></p>
        </div>
    <?php endif; ?>
    
    <div class="flex items-center gap-4">
        <?php if ($profile_picture): ?>
            <div class="flex-shrink-0">
                <?php if (is_array($profile_picture)): ?>
                    <img src="<?= esc_url($profile_picture['url']); ?>" alt="<?= esc_attr($profile_picture['alt'] ?? $author); ?>" class="w-[52px] h-[52px] rounded-full object-cover">
                <?php else: ?>
                    <img src="<?= esc_url($profile_picture); ?>" alt="<?= esc_attr($author); ?>" class="w-[52px] h-[52px] rounded-full object-cover">
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
</article>