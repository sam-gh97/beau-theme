<?php 
    get_header(); 
    $career = new Boilerplate\Models\Career(get_post());
    $related_careers = $career->getRelatedCareers();
?>

<header class="pt-9">
    <div class="container">
        <div class="mb-8 text-center">
            <h1 class="heading-1 mb-5"><?php the_title(); ?></h1>
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
</main>

<?php if (!empty($related_careers)): ?>
    <section class="py-8">
        <div class="container">
            <h2 class="heading-2 text-center mb-6">Related Stories</h2>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
                <?php foreach ($related_careers as $career): ?>
                    <?php 
                        get_template_part(
                            'partials/loop/careers-card',
                            null,
                            ['career' => $career]
                        );
                    ?>
                <?php endforeach; ?>
            </div>
        </div>
    </section>
<?php endif; ?>

<?php get_footer(); ?>