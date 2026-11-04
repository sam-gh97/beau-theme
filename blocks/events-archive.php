<?php
    use Boilerplate\Models\Event;

    $paged = max(1, get_query_var('paged') ?: get_query_var('page') ?: (isset($_GET['paged']) ? (int) $_GET['paged'] : 1));
    $perPage = get_option('posts_per_page');
    $prev_icon = '<img src="' . get_image('icons/chevron-right.svg') . '" class="pagination-arrow pagination-prev" alt="Previous">';
    $next_icon = '<img src="' . get_image('icons/chevron-right.svg') . '" class="pagination-arrow pagination-next" alt="Next">';
    $result = Event::getPaginated($paged, $perPage);
    $anim = get_field('animation') ?: 'none';
    $anim_class = $anim !== 'none' ? 'anim-' . $anim : '';
?>


<?php if( !empty( $block['data']['_is_preview'] ) ) : ?>
    <div class="aspect-video flex items-center justify-center">
        <img src="<?= get_image('previews/intro-content.png'); ?>" class="w-full object-contain" alt="">
    </div>

    <?php return; ?>
<?php endif; ?>

<div <?= get_block_wrapper_attributes(['class' => $anim_class]); ?>>
    <div class="custom-acf-block py-8">

    <div>
        <div class="container">

                <?php if (!empty($result['posts'])): ?>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                        <?php foreach ($result['posts'] as $post): ?>
                            <?php include partial('loop/event-card.php'); ?>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <div class="text-center">
                        <p class="heading-3 mb-3">No events found</p>
                    </div>
                <?php endif; ?>

                <?php if ($result['max_pages'] > 1): ?>
                    <nav class="pagination mt-16" aria-label="Events pagination">
                        <?php
                            if (is_post_type_archive('event')) {
                                $pagination_links = paginate_links([
                                    'total'     => $result['max_pages'],
                                    'current'   => $result['current'],
                                    'prev_text' => $prev_icon,
                                    'next_text' => $next_icon,
                                    'type'      => 'array',
                                ]);
                            } else {
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
                            }
                            
                            if ($pagination_links) {
                                foreach ($pagination_links as $link) {
                                    echo $link;
                                }
                            }
                        ?>
                    </nav>
                <?php endif; ?>
            </div>
        </div>

    </div>
</div>

