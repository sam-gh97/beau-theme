<?php
    $padding_top = get_field('padding_top');
    $padding_bottom = get_field('padding_bottom');
    $background_color = get_field('background_colour');

    $brands = get_terms([
        'taxonomy' => 'product_brand',
        'hide_empty' => true,
        'orderby' => 'name',
        'order' => 'ASC',
    ]);

    $grouped_brands = [];
    if (!is_wp_error($brands) && !empty($brands)) {
        foreach ($brands as $brand) {
            $first_char = strtoupper(substr($brand->name, 0, 1));
            
            if (is_numeric($first_char)) {
                $first_char = '#';
            }
            
            if (!isset($grouped_brands[$first_char])) {
                $grouped_brands[$first_char] = [];
            }
            
            $grouped_brands[$first_char][] = $brand;
        }
        
        ksort($grouped_brands);
        
        if (isset($grouped_brands['#'])) {
            $hash = $grouped_brands['#'];
            unset($grouped_brands['#']);
            $grouped_brands = ['#' => $hash] + $grouped_brands;
        }
    }

    $all_letters = array_merge(['#'], range('A', 'Z'));
    $available_letters = array_keys($grouped_brands);
?>

<?php if( !empty( $block['data']['_is_preview'] ) ) : ?>
    <div class="aspect-video flex items-center justify-center">
        <img src="<?= get_image('previews/intro-content.png'); ?>" class="w-full object-contain" alt="">
    </div>

    <?php return; ?>
<?php endif; ?>

<div <?= get_block_wrapper_attributes(); ?>>
    <div class="custom-acf-block padding-top-<?= $padding_top ?> padding-bottom-<?= $padding_bottom ?> background-<?= $background_color; ?>">

        <div class="container">

            <?php if(get_field('title')): ?>
                <h2 class="heading-2 text-center mb-7"><?= get_field('title'); ?></h2>
            <?php endif; ?>

            <?php if (!empty($grouped_brands)): ?>
                <div class="brand-directory-filters mb-8 flex flex-wrap gap-2 justify-center">
                    <?php foreach ($available_letters as $letter): ?>
                        <button 
                            type="button" 
                            class="brand-filter-btn px-1 py-1 w-[28px] h-[28px] text-center body-2 border border-black uppercase transition-colors hover:text-white hover:bg-black cursor-pointer"
                            data-letter="<?= $letter; ?>"
                        >
                            <?= $letter; ?>
                        </button>
                    <?php endforeach; ?>
                </div>

                <div class="brand-directory-content">
                    <?php 
                    $group_count = count($grouped_brands);
                    $current_index = 0;
                    foreach ($grouped_brands as $letter => $brands_in_group): 
                        $current_index++;
                    ?>
                        <div class="brand-group mb-5 pb-5 <?= $current_index < $group_count ? 'border-b border-black' : ''; ?>" data-letter="<?= $letter; ?>">
                            <h3 class="display-2 !font-normal mb-4"><?= $letter; ?></h3>
                            <div class="flex flex-wrap gap-x-6 gap-y-2">
                                <?php foreach ($brands_in_group as $brand): ?>
                                    <a href="<?= get_term_link($brand); ?>" class="brand-item hover:!underline">
                                        <span class="body-2"><?= esc_html($brand->name); ?></span>
                                    </a>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <p class="text-center">No brands found.</p>
            <?php endif; ?>
        </div>

    </div>
</div>

<?php if (!empty($grouped_brands)): ?>
<style>
.brand-filter-btn.active {
    background-color: black;
    color: white;
}
.brand-group {
    display: none;
}
.brand-group.visible {
    display: block;
}
</style>
<script>
(function() {
    const filterBtns = document.querySelectorAll('.brand-filter-btn');
    const brandGroups = document.querySelectorAll('.brand-group');
    const activeFilters = new Set();

    function updateDisplay() {
        if (activeFilters.size === 0) {
            brandGroups.forEach(group => group.classList.add('visible'));
        } else {
            brandGroups.forEach(group => {
                const letter = group.getAttribute('data-letter');
                if (activeFilters.has(letter)) {
                    group.classList.add('visible');
                } else {
                    group.classList.remove('visible');
                }
            });
        }
    }

    filterBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            const letter = this.getAttribute('data-letter');
            
            if (activeFilters.has(letter)) {
                activeFilters.delete(letter);
                this.classList.remove('active');
            } else {
                activeFilters.add(letter);
                this.classList.add('active');
            }
            
            updateDisplay();
        });
    });

    updateDisplay();
})();
</script>
<?php endif; ?>
