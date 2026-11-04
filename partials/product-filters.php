<?php
$all_brands = get_terms([
    'taxonomy' => 'product_brand',
    'hide_empty' => true,
    'orderby' => 'name',
    'order' => 'ASC',
]);

// Filter brands to only show featured ones
$brands = [];
if (!is_wp_error($all_brands) && !empty($all_brands)) {
    foreach ($all_brands as $brand) {
        $is_featured = get_field('featured', $brand);
        if ($is_featured) {
            $brands[] = $brand;
        }
    }
    
    // Sort brands alphabetically by name after filtering
    usort($brands, function($a, $b) {
        return strcasecmp($a->name, $b->name);
    });
}

// Get only parent categories
$parent_categories = get_terms([
    'taxonomy' => 'product_cat',
    'hide_empty' => true,
    'parent' => 0,
    'orderby' => 'name',
    'order' => 'ASC',
]);

$queried_object = get_queried_object();
$current_term_id = 0;
$current_taxonomy = '';

if ($queried_object && isset($queried_object->term_id)) {
    $current_term_id = $queried_object->term_id;
    $current_taxonomy = $queried_object->taxonomy;
}

global $wp_query;
$product_ids = [];
if ($wp_query->have_posts()) {
    foreach ($wp_query->posts as $product) {
        $product_ids[] = $product->ID;
    }
}

$open_brand = true; // Always open by default
?>

<div class="product-filters">
    <?php if (!is_wp_error($brands) && !empty($brands)): ?>
        <div class="filter-section border-b border-black py-4">
            <button class="filter-toggle cursor-pointer flex items-center justify-between w-full text-left" data-filter="brand">
                <span class="body-2">Brand</span>
                <img src="<?= get_image('icons/menu-chevron.svg'); ?>" alt="" class="filter-chevron transition-transform duration-200 w-[12px] h-[12px]" style="<?= $open_brand ? 'transform: rotate(180deg);' : ''; ?>">
            </button>
            <div class="filter-content <?= $open_brand ? '' : 'hidden'; ?> pt-4 max-h-[300px] overflow-y-auto">
                <div class="flex flex-col items-start space-y-2">
                    <?php 
                    $initial_limit = 4;
                    $total_brands = count($brands);
                    $show_view_all = $total_brands > 0;
                    
                    $counter = 0;
                    foreach ($brands as $brand):
                        $is_current = ($current_taxonomy === 'product_brand' && $current_term_id === $brand->term_id);
                        $link_class = $is_current ? 'inline-block body-3 !underline' : 'inline-block body-3 hover:!underline';
                        $is_hidden = $counter >= $initial_limit;
                        $counter++;
                    ?>
                        <a href="<?= get_term_link($brand); ?>" 
                           class="<?= $link_class; ?> <?= $is_hidden ? 'brand-extra-item' : ''; ?>"
                           style="<?= $is_hidden ? 'display: none;' : ''; ?>">
                            <?= esc_html($brand->name); ?> (<?= $brand->count; ?>)
                        </a>
                    <?php endforeach; ?>
                    
                    <?php if ($show_view_all): ?>
                        <a href="/brands/" class="inline-block body-3 hover:!underline mt-1">
                            View all
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <?php if (!is_wp_error($parent_categories) && !empty($parent_categories)): ?>
        <?php foreach ($parent_categories as $parent): 
            if (strtolower($parent->slug) === 'uncategorized' || strtolower($parent->slug) === 'uncategorised') {
                continue;
            }
            
            $children = get_terms([
                'taxonomy' => 'product_cat',
                'hide_empty' => true,
                'parent' => $parent->term_id,
                'orderby' => 'name',
                'order' => 'ASC',
            ]);
            
            $is_parent_current = ($current_taxonomy === 'product_cat' && $current_term_id === $parent->term_id);
            $has_current_child = false;
            if (!empty($children)) {
                foreach ($children as $child) {
                    if ($current_taxonomy === 'product_cat' && $current_term_id === $child->term_id) {
                        $has_current_child = true;
                        break;
                    }
                }
            }
            
            $open_this_category = $is_parent_current || $has_current_child;
        ?>
        <div class="filter-section border-b border-black py-4">
            <button class="filter-toggle cursor-pointer flex items-center justify-between w-full text-left" data-filter="category-<?= $parent->term_id; ?>">
                <span class="body-2"><?= esc_html($parent->name); ?></span>
                <img src="<?= get_image('icons/menu-chevron.svg'); ?>" alt="" class="filter-chevron transition-transform duration-200 w-[12px] h-[12px]" style="<?= $open_this_category ? 'transform: rotate(180deg);' : ''; ?>">
            </button>
            <div class="filter-content <?= $open_this_category ? '' : 'hidden'; ?> pt-3 max-h-[300px] overflow-y-auto">
                <?php if (!empty($children)): ?>
                    <div class="flex flex-col items-start space-y-2">
                        <?php 
                        foreach ($children as $child):
                            $is_current = ($current_taxonomy === 'product_cat' && $current_term_id === $child->term_id);
                            $link_class = $is_current ? 'inline-block body-3 !underline' : 'inline-block body-3 hover:!underline';
                        ?>
                            <a href="<?= get_term_link($child); ?>" class="<?= $link_class; ?>">
                                <?= esc_html($child->name); ?> (<?= $child->count; ?>)
                            </a>
                        <?php endforeach; ?>
                        
                        <a href="<?= get_term_link($parent); ?>" class="inline-block body-3 hover:!underline mt-1">
                            View all
                        </a>
                    </div>
                <?php else: ?>
                    <a href="<?= get_term_link($parent); ?>" class="<?= $is_parent_current ? 'inline-block body-3 !underline' : 'inline-block body-3 hover:!underline'; ?>">
                        View all <?= esc_html($parent->name); ?> (<?= $parent->count; ?>)
                    </a>
                <?php endif; ?>
            </div>
        </div>
        <?php endforeach; ?>
    <?php endif; ?>

</div>

<script>
(function() {
    // Handle accordion toggles
    const filterToggles = document.querySelectorAll('.filter-toggle');
    
    filterToggles.forEach(toggle => {
        toggle.addEventListener('click', function() {
            const filterSection = this.closest('.filter-section');
            const filterContent = filterSection.querySelector('.filter-content');
            const chevron = this.querySelector('.filter-chevron');
            const isOpen = !filterContent.classList.contains('hidden');
            
            if (isOpen) {
                filterContent.classList.add('hidden');
                chevron.style.transform = 'rotate(0deg)';
            } else {
                filterContent.classList.remove('hidden');
                chevron.style.transform = 'rotate(180deg)';
            }
        });
    });
})();
</script>
