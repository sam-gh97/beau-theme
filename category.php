<?php 
    get_header(); 

    use Boilerplate\Models\Post;
    use Boilerplate\Models\Product;

    $term = get_queried_object();
    $term_id = $term->term_id;
    $hero_title = get_field('hero_title', 'category_' . $term_id);
    $trending_products = get_field('trending_products', 'category_' . $term_id);
    
    $paged = max(1, get_query_var('paged'));
    $perPage = 8;
    
    $query = new WP_Query([
        'post_type'      => 'post',
        'posts_per_page' => $perPage,
        'paged'          => $paged,
        'cat'            => $term_id,
    ]);
    
    $posts = [];
    if ($query->have_posts()) {
        $posts = array_map(fn($post) => new Post($post), $query->posts);
    }
    
    $max_pages = $query->max_num_pages;
?>

<div class="py-9 background-cream">
    <div class="container">
        <p class="heading-4 uppercase text-center mb-1">Learn</p>

        <h1 class="heading-1 text-center mb-3">
            <?= esc_html($hero_title ?: $term->name); ?>
        </h1>
    </div>

    <?php if (term_description($term_id, 'category')): ?>
        <div class="container-sm">
            <div class="text-center">
                <?= term_description($term_id, 'category'); ?>
            </div>
        </div>
    <?php endif; ?>
</div>

<div class="py-8">
    <div class="container">
        <?php if (!empty($posts)): ?>
            <?php include partial('blog-filters.php'); ?>

            <div class="grid grid-cols-1 gap-5">
                <?php 
                $post_count = count($posts);
                $slider_position = min(4, $post_count);
                
                foreach ($posts as $index => $post): 
                    include partial('loop/post-card-alt.php');
                    
                    if (($index + 1) === $slider_position && !empty($trending_products)):
                        $products = Product::getByIds($trending_products);
                ?>
                        <div class="col-span-1">
                            <div class="py-8">
                                <div class="container-sm">
                                    <h2 class="heading-2 text-center mb-7">Trending Products</h2>
                                </div>

                                <?php if (!empty($products)): ?>
                                    <div class="container relative px-0 xl:px-16">
                                        <div class="swiper-button-prev product-nav-prev !hidden xl:!flex">
                                            <img src="<?= get_image('arrow.svg'); ?>" alt="Previous" class="product-arrow-icon product-arrow-prev">
                                        </div>
                                        <div class="swiper-button-next product-nav-next !hidden xl:!flex">
                                            <img src="<?= get_image('arrow.svg'); ?>" alt="Next" class="product-arrow-icon product-arrow-next">
                                        </div>
                                        
                                        <div class="products-slider-wrapper relative">
                                            <div class="products-slider swiper overflow-hidden" data-swiper="products">
                                                <div class="swiper-wrapper">
                                                    <?php foreach ($products as $product): ?>
                                                        <div class="swiper-slide relative">
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
                                        </div>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                <?php 
                    endif;
                endforeach; 
                ?>
            </div>

            <?php if ($max_pages > 1):
                    $prev_icon = '<span class="pagination-chevron pagination-chevron-left" aria-hidden="true">
                        <svg width="8" height="16" viewBox="0 0 8 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M6 2L2 8L6 14" stroke="currentColor" stroke-width="1.5" stroke-linecap="square"/>
                        </svg>
                    </span>';
                    $next_icon = '<span class="pagination-chevron pagination-chevron-right" aria-hidden="true">
                        <svg width="8" height="16" viewBox="0 0 8 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M2 2L6 8L2 14" stroke="currentColor" stroke-width="1.5" stroke-linecap="square"/>
                        </svg>
                    </span>';
            ?>
                <nav class="pagination mt-16" aria-label="Category pagination">
                    <div class="pagination-container">
                        <?php
                            $pagination_links = paginate_links([
                                'total'     => $max_pages,
                                'current'   => $paged,
                                'prev_text' => $prev_icon,
                                'next_text' => $next_icon,
                                'type'      => 'array',
                            ]);
                            
                            if ($pagination_links) {
                                foreach ($pagination_links as $link) {
                                    if (strpos($link, '<a ') !== false) {
                                        $link = str_replace('<a ', '<a class="pagination-link" ', $link);
                                    } elseif (strpos($link, '<span ') !== false) {
                                        if (strpos($link, 'aria-current') !== false) {
                                            $link = str_replace('<span ', '<span class="pagination-link pagination-link--current" ', $link);
                                        } else {
                                            $link = str_replace('<span ', '<span class="pagination-link pagination-link--disabled" ', $link);
                                        }
                                    }
                                    echo $link;
                                }
                            }
                        ?>
                    </div>
                </nav>
            <?php endif; ?>

        <?php else: ?>
            <div class="text-center">
                <p class="heading-3 mb-3">No posts found</p>
            </div>
        <?php endif; ?>
    </div>
</div>

<div class="padding-top-lg padding-bottom-lg">
    <div class="container">
        <div class="bg-black text-white p-7 grid grid-cols-1 md:grid-cols-2 items-center gap-8">
            <div>
                <h2 class="heading-2-sm mb-3"><?= get_field('cta_title', 'options'); ?></h2>
                <p><?= get_field('cta_content', 'options'); ?></p>
            </div>

            <div>
                <?= do_shortcode('[contact-form-7 id="' . get_field('newsletter_cta_form', 'options') . '" title="Newsletter Inline"]'); ?>
            </div>
        </div>
    </div>
</div>

<?php get_footer(); ?>