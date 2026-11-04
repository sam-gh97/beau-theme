<?php
    if (isset($args['spotlight'])) {
        $spotlight = $args['spotlight'];
    }
    
    if (! isset($spotlight) || ! $spotlight) {
        return;
    }
?>

<div class="spotlight-card flex flex-col h-full">
    <a href="<?= $spotlight->getPermalink(); ?>" class="relative aspect-[4/5] overflow-hidden mb-4 block">
        <?php if ($spotlight->getImageUrl('large')): ?>
            <img src="<?= $spotlight->getImageUrl('large'); ?>" alt="<?= $spotlight->getTitle(); ?>" class="w-full h-full object-cover object-center">
        <?php else: ?>
            <div class="w-full h-full bg-black"></div>
        <?php endif; ?>
    </a>
    <div class="flex flex-col flex-grow">
        <h3 class="display-2 !font-normal mb-3">
            <a href="<?= $spotlight->getPermalink(); ?>" class="hover:underline"><?= $spotlight->getTitle(); ?></a>
        </h3>

        <?php if (get_field('excerpt', $spotlight->getId())): ?>
            <p class="body-2 mb-4 line-clamp-3"><?= get_field('excerpt', $spotlight->getId()); ?></p>
        <?php endif; ?>

        <a href="<?= $spotlight->getPermalink(); ?>" class="ui-link mt-auto self-start">Read more</a>
    </div>
</div>

