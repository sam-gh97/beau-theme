<?php 
    get_header(); 
    
    use Boilerplate\Models\Spotlight;
    
    $spotlight = new Spotlight(get_post());
    $related_spotlights = $spotlight->getRelatedSpotlights();
    $products = $spotlight->getProducts();
?>

<header class="py-9 background-cream">
    <div class="container">
        <div class="flex gap-7 items-center">
            <div class="flex-1 text-center lg:text-left">
                <p class="heading-4 uppercase mb-1">Spotlight</p>

                <h1 class="heading-1 mb-3"><?= $spotlight->getTitle(); ?></h1>

                <?php if(get_field('excerpt')): ?>
                    <p><?= get_field('excerpt'); ?></p>
                <?php endif; ?>
            </div>
        
            <div class="w-[440px] hidden lg:block">
                <div class="aspect-square overflow-hidden">
                    <img src="<?= get_the_post_thumbnail_url(); ?>" alt="<?php the_title(); ?>" class="w-full h-full object-cover object-center">
                </div>
            </div>

        </div>
    </div>
</header>

<main class="py-8">
    <?php if (!empty($products)): ?>
        <div class="container">
            <div class="grid grid-cols-1 xs:grid-cols-2 md:grid-cols-3 gap-5 lg:hidden">
                <?php foreach ($products as $product): ?>
                    <?php 
                    get_template_part(
                        'partials/loop/product-card',
                        null,
                        ['product' => $product]
                    );
                    ?>
                <?php endforeach; ?>
            </div>

            <div class="hidden lg:flex flex-col gap-5">
                <?php 
                $total = count($products);
                
                if ($total >= 5):
                ?>
                    <div class="grid grid-cols-2 gap-5">
                        <div>
                            <?php 
                            get_template_part(
                                'partials/loop/product-card',
                                null,
                                ['product' => $products[0]]
                            );
                            ?>
                        </div>
                        <div class="grid grid-cols-2 gap-5">
                            <?php for ($i = 1; $i <= 4; $i++): ?>
                                <?php 
                                get_template_part(
                                    'partials/loop/product-card',
                                    null,
                                    ['product' => $products[$i]]
                                );
                                ?>
                            <?php endfor; ?>
                        </div>
                    </div>
                <?php endif; ?>
                
                <?php 
                $start_index = ($total >= 5) ? 5 : 0;
                if ($start_index < $total):
                ?>
                    <div class="grid grid-cols-4 gap-5">
                        <?php for ($i = $start_index; $i < $total; $i++): ?>
                            <?php 
                            get_template_part(
                                'partials/loop/product-card',
                                null,
                                ['product' => $products[$i]]
                            );
                            ?>
                        <?php endfor; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    <?php endif; ?>
</main>

<?php if (!empty($related_spotlights)): ?>
    <section class="py-8">
        <div class="container">
            <h2 class="heading-2 text-center mb-6">Related edits</h2>

            <div class="grid grid-cols-3 gap-5">
                <?php foreach ($related_spotlights as $spotlight_post): ?>
                    <?php 
                        get_template_part(
                            'partials/loop/spotlight-card',
                            null,
                            ['spotlight' => $spotlight_post]
                        );
                    ?>
                <?php endforeach; ?>
            </div>
        </div>
    </section>
<?php endif; ?>

<?php get_footer(); ?>