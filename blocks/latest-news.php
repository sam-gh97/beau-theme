<?php
    $padding_top = get_field('padding_top');
    $padding_bottom = get_field('padding_bottom');
    $background_color = get_field('background_colour');
    $news_only = get_field('show_only_company_news');
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
    <div class="custom-acf-block padding-top-<?= $padding_top ?> padding-bottom-<?= $padding_bottom ?> background-<?= $background_color; ?>">

        <div class="container">
            <?php if(get_field('title')): ?>
                <h2 class="heading-2 mb-6 text-center"><?= get_field('title'); ?></h2>
            <?php endif; ?>

            <?php
                use Boilerplate\Models\Post;

                $query_args = [
                    'posts_per_page' => 3,
                    'post_status'    => 'publish',
                    'orderby'        => 'date',
                    'order'          => 'DESC',
                    'no_found_rows'  => true,
                ];

                if ($news_only) {
                    $query_args['category_name'] = 'company-news';
                }

                $query = new WP_Query($query_args);

                $posts = [];
                if ($query->have_posts()) {
                    $posts = array_map(
                        fn($post) => new Post($post),
                        $query->posts
                    );
                }
            ?>

            <?php if (!empty($posts)): ?>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                    <?php foreach ($posts as $post): ?>
                        <div>
                            <?php 
                            get_template_part(
                                'partials/loop/post-card',
                                null,
                                ['post' => $post]
                            );
                            ?>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <p class="text-center body-1">No posts found.</p>
            <?php endif; ?>
            
        </div>
    </div>
</div>

