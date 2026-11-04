<?php
$parent_slugs = [
    'beau-co-stories',
    'beauty-brands',
    'business-growth',
    'directory-voices',
    'trends-insights'
];

$queried_object = get_queried_object();
$current_term_id = 0;
if ($queried_object && isset($queried_object->term_id)) {
    $current_term_id = $queried_object->term_id;
}
?>

<div class="blog-filters mb-6">
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-5">
        <?php foreach ($parent_slugs as $slug) :
            $parent = get_term_by('slug', $slug, 'category');
            
            if (!$parent || is_wp_error($parent)) {
                continue;
            }
            
            // Get child categories
            $children = get_terms([
                'taxonomy' => 'category',
                'parent' => $parent->term_id,
                'hide_empty' => true,
                'orderby' => 'name',
                'order' => 'ASC',
            ]);
            
            $is_current_parent = ($current_term_id === $parent->term_id);
            $current_child_id = 0;
            if (!$is_current_parent && $current_term_id > 0) {
                $current_term = get_term($current_term_id, 'category');
                if ($current_term && !is_wp_error($current_term) && $current_term->parent === $parent->term_id) {
                    $current_child_id = $current_term_id;
                }
            }
        ?>
            <div class="filter-dropdown">
                <select 
                    class="blog-filter-select w-full py-3 border-b border-neutral-300 bg-white cursor-pointer body-2 appearance-none bg-no-repeat bg-right"
                    style="background-image: url('data:image/svg+xml;charset=UTF-8,%3csvg width=\'12\' height=\'12\' viewBox=\'0 0 12 12\' fill=\'none\' xmlns=\'http://www.w3.org/2000/svg\'%3e%3cpath d=\'M2 4L6 8L10 4\' stroke=\'black\' stroke-width=\'1.5\' stroke-linecap=\'square\'/%3e%3c/svg%3e'); background-position: right center; padding-right: 20px; padding-left: 0;"
                    data-parent="<?= esc_attr($parent->name); ?>"
                >
                    <?php 
                    $has_selection = ($is_current_parent || $current_child_id > 0);
                    ?>
                    
                    <option value="" disabled <?= !$has_selection ? 'selected' : ''; ?> hidden style="display: none;">
                        <?= esc_html($parent->name); ?>
                    </option>
                    
                    <option value="<?= esc_url(get_term_link($parent)); ?>" <?= $is_current_parent ? 'selected' : ''; ?>>
                        All <?= esc_html($parent->name); ?>
                    </option>
                    
                    <?php if (!is_wp_error($children) && !empty($children)) : ?>
                        <?php foreach ($children as $child) : 
                            $is_selected = ($current_child_id === $child->term_id);
                        ?>
                            <option value="<?= esc_url(get_term_link($child)); ?>" <?= $is_selected ? 'selected' : ''; ?>>
                                <?= esc_html($child->name); ?>
                            </option>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </select>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<script>
(function() {
    const blogFilters = document.querySelectorAll('.blog-filter-select');
    
    blogFilters.forEach(select => {
        select.addEventListener('change', function() {
            const url = this.value;
            if (url) {
                window.location.href = url;
            }
        });
    });
})();
</script>

