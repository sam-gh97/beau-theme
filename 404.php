<?php get_header(); ?>

<main class="min-h-[calc(100vh-300px)] flex items-center justify-center py-9">
    <div class="container">
        <div class="max-w-2xl mx-auto text-center">
            <h1 class="display-1 mb-5">404</h1>
            <h2 class="heading-1 mb-4">Page Not Found</h2>
            <p class="body-1 mb-7">
                Sorry, the page you're looking for doesn't exist or has been moved.
            </p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="<?= home_url(); ?>" class="ui-link">
                    Go to Homepage
                </a>
                <a href="<?= home_url('/shop'); ?>" class="ui-link">
                    Visit Shop
                </a>
            </div>
        </div>
    </div>
</main>

<?php get_footer(); ?>