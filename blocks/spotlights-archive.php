<?php
    $padding_top = get_field('padding_top');
    $padding_bottom = get_field('padding_bottom');
    $background_color = get_field('background_colour');

    use Boilerplate\Models\Spotlight;
    $paged = max(1, get_query_var('paged') ?: get_query_var('page') ?: (isset($_GET['paged']) ? (int) $_GET['paged'] : 1));
    $perPage = get_option('posts_per_page');
    $result = Spotlight::getPaginated($paged, $perPage);
    
    $result['max_pages'] = (int) ceil($result['total'] / $perPage);
    $result['current'] = $paged;
    $anim = get_field('animation') ?: 'none';
    $anim_class = $anim !== 'none' ? 'anim-' . $anim : '';
    
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
                <h2 class="heading-2 mb-3 text-center"><?= get_field('title'); ?></h2>
            <?php endif; ?>

            <?php if(get_field('content')): ?>
                <p class="text-center mb-7"><?= get_field('content'); ?></p>
            <?php endif; ?>
        </div>

        <div class="container">
            <?php if (!empty($result['posts'])): ?>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
                    <?php foreach ($result['posts'] as $spotlight): ?>
                        <?php 
                            get_template_part(
                                'partials/loop/spotlight-card',
                                null,
                                ['spotlight' => $spotlight]
                            );
                        ?>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <div class="text-center">
                    <p class="heading-3 mb-3">No spotlights found</p>
                </div>
            <?php endif; ?>
        </div>

        <?php if ($result['max_pages'] > 1): ?>
            <nav class="pagination mt-16" aria-label="Spotlights pagination">
                <div class="pagination-container">
                    <?php
                        $base = remove_query_arg('paged', get_permalink());

                        if (is_front_page()) {
                            $base = trailingslashit(home_url('/'));
                        }
                        
                        $pagination_links = paginate_links([
                            'total'     => $result['max_pages'],
                            'current'   => $result['current'],
                            'prev_text' => $prev_icon,
                            'next_text' => $next_icon,
                            'type'      => 'array',
                            'base'      => trailingslashit($base) . '%_%',
                            'format'    => '?paged=%#%',
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

    </div>
</div>