<?php
    use Boilerplate\Models\Business;

    $padding_top = get_field('padding_top');
    $padding_bottom = get_field('padding_bottom');
    $background_color = get_field('background_colour');
    $anim = get_field('animation') ?: 'none';
    $anim_class = $anim !== 'none' ? 'anim-' . $anim : '';

    $business_types = get_terms([
        'taxonomy' => 'business_type',
        'hide_empty' => true,
        'orderby' => 'name',
        'order' => 'ASC',
    ]);

    $businesses = Business::getAllBusinesses();
    
    $grouped_businesses = [];
    if (!is_wp_error($business_types) && !empty($business_types) && !empty($businesses)) {
        foreach ($businesses as $business) {
            $types = $business->getBusinessTypes();
            
            if (!empty($types)) {
                foreach ($types as $type) {
                    if (!isset($grouped_businesses[$type->term_id])) {
                        $grouped_businesses[$type->term_id] = [
                            'term' => $type,
                            'businesses' => []
                        ];
                    }
                    $grouped_businesses[$type->term_id]['businesses'][] = $business;
                }
            }
        }
        
        // Sort groups alphabetically by term name
        uasort($grouped_businesses, function($a, $b) {
            return strcmp($a['term']->name, $b['term']->name);
        });
    }
?>

<?php if( !empty( $block['data']['_is_preview'] ) ) : ?>
    <div class="aspect-video flex items-center justify-center">
        <img src="<?= get_image('previews/intro-content.png'); ?>" class="w-full object-contain" alt="">
    </div>

    <?php return; ?>
<?php endif; ?>

<div <?= get_block_wrapper_attributes(['class' => $anim_class]); ?>>
    <div class="custom-acf-block padding-top-<?= $padding_top ?> padding-bottom-<?= $padding_bottom ?> background-<?= $background_color; ?>">

        <div class="container-sm">
            <?php if(get_field('title')): ?>
                <h2 class="heading-2 text-center mb-3"><?= get_field('title'); ?></h2>
            <?php endif; ?>

            <?php if(get_field('content')): ?>
                <p class="text-center mb-7"><?= get_field('content'); ?></p>
            <?php endif; ?>
        </div>

        <div class="container">
            <?php if (!empty($grouped_businesses) && !empty($business_types)): ?>
                <div class="business-directory-filters mb-8 flex flex-wrap gap-2 justify-center">
                    <?php foreach ($business_types as $type): ?>
                        <button 
                            type="button" 
                            class="business-filter-btn px-2 py-1 text-sm leading-[22px] font-futura-pt font-medium border border-black uppercase transition-colors hover:text-white hover:bg-black cursor-pointer"
                            data-type="<?= $type->term_id; ?>"
                        >
                            <?= esc_html($type->name); ?>
                        </button>
                    <?php endforeach; ?>
                </div>

                <div class="business-directory-content">
                    <?php 
                    $group_count = count($grouped_businesses);
                    $current_index = 0;
                    foreach ($grouped_businesses as $type_id => $group): 
                        $current_index++;
                        $type = $group['term'];
                        $businesses_in_group = $group['businesses'];
                        
                        // Sort businesses alphabetically by title
                        usort($businesses_in_group, function($a, $b) {
                            return strcmp($a->getTitle(), $b->getTitle());
                        });
                    ?>
                        <div class="business-group mb-5 pb-5 <?= $current_index < $group_count ? 'border-b border-black' : ''; ?>" data-type="<?= $type_id; ?>">
                            <h3 class="display-2 !font-normal mb-4"><?= esc_html($type->name); ?></h3>
                            <div class="flex flex-wrap items-center gap-x-6 gap-y-2">
                                <?php foreach ($businesses_in_group as $business): ?>
                                    <?php 
                                        $website = $business->getWebsite();
                                        $has_link = !empty($website);
                                    ?>
                                    <?php if ($has_link): ?>
                                        <a href="<?= esc_url($website); ?>" target="_blank" rel="noopener noreferrer" class="business-item inline-block">
                                            <span class="body-2"><u><?= esc_html($business->getTitle()); ?></u></span>
                                        </a>
                                    <?php else: ?>
                                        <span class="business-item body-2 inline-block"><?= esc_html($business->getTitle()); ?></span>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <p class="text-center">No businesses found.</p>
            <?php endif; ?>
        </div>

    </div>
</div>

<?php if (!empty($grouped_businesses)): ?>
<style>
.business-filter-btn.active {
    background-color: black;
    color: white;
}
.business-group {
    display: none;
}
.business-group.visible {
    display: block;
}
</style>
<script>
(function() {
    const filterBtns = document.querySelectorAll('.business-filter-btn');
    const businessGroups = document.querySelectorAll('.business-group');
    const activeFilters = new Set();

    function updateDisplay() {
        if (activeFilters.size === 0) {
            businessGroups.forEach(group => group.classList.add('visible'));
        } else {
            businessGroups.forEach(group => {
                const type = group.getAttribute('data-type');
                if (activeFilters.has(type)) {
                    group.classList.add('visible');
                } else {
                    group.classList.remove('visible');
                }
            });
        }
    }

    filterBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            const type = this.getAttribute('data-type');
            
            if (activeFilters.has(type)) {
                activeFilters.delete(type);
                this.classList.remove('active');
            } else {
                activeFilters.add(type);
                this.classList.add('active');
            }
            
            updateDisplay();
        });
    });

    updateDisplay();
})();
</script>
<?php endif; ?>
