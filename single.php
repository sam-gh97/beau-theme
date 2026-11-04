<?php 
    get_header(); 
    $article = new Boilerplate\Models\Post(get_post());
    $related_posts = $article->getRelatedPosts();

    use Boilerplate\Models\Product;
    $product_ids = get_field('featured_products');
    $products = $product_ids ? Product::getByIds($product_ids) : [];
?>

<header class="pt-9">
    <div class="container">
        <div class="mb-8 text-center">
            <h1 class="heading-1 mb-5"><?php the_title(); ?></h1>

            <div class="flex flex-wrap justify-center gap-7 s-3">
                <div class="flex flex-col items-center">
                    <p class="uppercase">Written by:</p>
                    <p><?= $article->getWrittenBy(); ?></p>
                </div>
                <div class="flex flex-col items-center">
                    <p class="uppercase">Date:</p>
                    <p><?= $article->getWrittenOn(); ?></p>
                </div>

                <?php if (get_post_type() === 'post'): 
                    $categories = get_the_category($article->getId());
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
                            <div class="flex flex-col items-center">
                                <p class="uppercase mb-1">Category:</p>
                                <div class="post-categories flex flex-wrap justify-center gap-2">
                                    <?php foreach ($filtered_cats as $category) : ?>
                                        <a href="<?= get_category_link($category->term_id); ?>" class="border text-sm px-2 py-[4px] font-futura-pt font-medium uppercase category-pill hover:bg-black hover:text-white transition-all duration-200">
                                            <?= esc_html($category->name); ?>
                                        </a>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        <?php endif;
                    }
                endif; ?>
            </div>
        </div>

        <div class="aspect-video overflow-hidden">
            <img src="<?= get_the_post_thumbnail_url(); ?>" alt="<?php the_title(); ?>" class="w-full h-full object-cover object-center">
        </div>
    </div>
</header>

<main class="py-8">
    <div class="container-sm">
        <div class="body-content">
            <?php the_content(); ?>
        </div>
    </div>

    <?php if (!empty($products)): ?>
        <div class="container mt-8">
            <div class="flex flex-wrap gap-5 justify-center">
                <?php foreach ($products as $product): ?>
                    <div class="w-full sm:w-1/2 lg:w-1/4 max-w-sm">
                        <?php 
                            get_template_part(
                                'partials/loop/product-card',
                                null,
                                ['product' => $product]
                            );
                        ?>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    <?php endif; ?>
</main>

<section class="py-8">
    <div class="container">
        <h2 class="heading-2 text-center mb-6">Related posts</h2>

        <div class="grid grid-cols-3 gap-5">
            <?php foreach ($related_posts as $post): ?>
                <?php include partial('loop/post-card.php'); ?>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<?php get_footer(); ?>