<?php
    if (isset($args['post'])) {
        $post = $args['post'];
    }
    
    if (! isset($post) || ! $post) {
        return;
    }
?>

<div class="event-card flex flex-col h-full">
    <a href="<?= $post->getPermalink(); ?>" class="relative aspect-video overflow-hidden mb-4 block">
        <?php if ($post->getImageUrl('large')): ?>
            <img src="<?= $post->getImageUrl('large'); ?>" alt="<?= $post->getTitle(); ?>" class="w-full h-full object-cover object-center">
        <?php else: ?>
            <div class="w-full h-full bg-black"></div>
        <?php endif; ?>
    </a>
    <div class="flex flex-col flex-grow">
        <?php
        $categories = get_the_category($post->getId());
        if (!empty($categories)) {
            $cats_to_display = [];
            
            foreach ($categories as $category) {
                if (strtolower($category->name) === 'uncategorised') {
                    continue;
                }
                
                if ($category->parent != 0) {
                    $parent = get_category($category->parent);
                    if ($parent && strtolower($parent->name) !== 'uncategorised') {
                        $cats_to_display[$parent->term_id] = $parent;
                    }
                }
                
                $cats_to_display[$category->term_id] = $category;
            }
            
            $filtered_cats = array_slice($cats_to_display, 0, 2);
            
            if (!empty($filtered_cats)) : ?>
                <div class="post-categories flex flex-wrap gap-2 mb-3">
                    <?php foreach ($filtered_cats as $category) : ?>
                        <a href="<?= get_category_link($category->term_id); ?>" class="border text-sm px-2 py-[4px] font-futura-pt font-medium uppercase category-pill hover:bg-black hover:text-white transition-all duration-200">
                            <?= esc_html($category->name); ?>
                        </a>
                    <?php endforeach; ?>
                </div>
            <?php endif;
        }
        ?>
        <h3 class="display-2 mb-3">
            <a href="<?= $post->getPermalink(); ?>" class="!font-normal hover:underline"><?= $post->getTitle(); ?></a>
        </h3>

        <div class="flex flex-wrap gap-5 s-3 mb-7">
            <div class="flex flex-col items-start">
                <p class="uppercase">Written by:</p>
                <p><?= $post->getWrittenBy(); ?></p>
            </div>
            <div class="flex flex-col items-start">
                <p class="uppercase">Written on:</p>
                <p><?= $post->getWrittenOn(); ?></p>
            </div>
        </div>

        <a href="<?= $post->getPermalink(); ?>" class="ui-link mt-auto self-start">Read more</a>
    </div>
</div>