<?php
    if (isset($args['career'])) {
        $career = $args['career'];
    }
    
    if (! isset($career) || ! $career) {
        return;
    }
?>

<div class="event-card flex flex-col h-full">
    <a href="<?= $career->getPermalink(); ?>" class="relative aspect-square overflow-hidden mb-4 block">
        <?php if ($career->getImageUrl('large')): ?>
            <img src="<?= $career->getImageUrl('large'); ?>" alt="<?= $career->getTitle(); ?>" class="w-full h-full object-cover object-center">
        <?php else: ?>
            <div class="w-full h-full bg-black"></div>
        <?php endif; ?>
    </a>
    <div class="flex flex-col flex-grow">
        <h3 class="display-2 !font-normal mb-2">
            <a href="<?= $career->getPermalink(); ?>" class="hover:underline"><?= $career->getTitle(); ?></a>
        </h3>

        <?php if ($career->getExcerpt()): ?>
            <p class="body-2 mb-5 line-clamp-3"><?= $career->getExcerpt(); ?></p>
        <?php endif; ?>

        <a href="<?= $career->getPermalink(); ?>" class="ui-link mt-auto self-start">Read more</a>
    </div>
</div>

