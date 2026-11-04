<?php
defined('ABSPATH') || exit;

get_header('shop');

do_action('woocommerce_before_main_content');
?>

<?php
if (is_shop()) {
    $shop_page_id = wc_get_page_id('shop');
    if ($shop_page_id > 0) {
        echo '<div class="shop-intro-content">';
        echo apply_filters('the_content', get_post_field('post_content', $shop_page_id));
        echo '</div>';
    }
}
?>


<?php
if (is_product_category()) :
    $term = get_queried_object();
    $term_id = $term->term_id;

    $hero_title = get_field('hero_title', 'product_cat_' . $term_id);
    $hero_content = get_field('hero_content', 'product_cat_' . $term_id);

    $fallback_title = $term->name;
    $fallback_content = term_description($term_id, 'product_cat');

    $title = $hero_title ?: $fallback_title;
    $content = $hero_content ?: $fallback_content;
?>
    <div class="py-9 background-cream">
        <div class="container">
            <p class="heading-4 uppercase text-center mb-1">DISCOVER</p>

            <h1 class="heading-1 text-center mb-3">
                <?= esc_html($title); ?>
            </h1>
        </div>

        <?php if ($content): ?>
            <div class="container-sm">
                <div class="text-center">
                    <?= wp_kses_post($content); ?>
                </div>
            </div>
        <?php endif; ?>
    </div>
<?php endif; ?>

<?php
if (is_product_taxonomy() && is_tax('product_brand')) :
    $term = get_queried_object();
    $term_id = $term->term_id;

    $title = $term->name;
    $content = term_description($term_id, 'product_brand');
?>
    <div class="py-9 background-cream">
        <div class="container">
            <p class="heading-4 uppercase text-center mb-1">DISCOVER</p>

            <h1 class="heading-1 text-center mb-3">
                <?= esc_html($title); ?>
            </h1>
        </div>

        <?php if ($content): ?>
            <div class="container-sm">
                <div class="text-center">
                    <?= wp_kses_post($content); ?>
                </div>
            </div>
        <?php endif; ?>
    </div>
<?php endif; ?>

<?php do_action('woocommerce_shop_loop_header'); ?>

<section class="py-8">
	<div class="container">

		<?php if (woocommerce_product_loop()) : ?>

			<?php do_action('woocommerce_before_shop_loop'); ?>

			<div class="grid md:grid-cols-4 grid-cols-1 w-full gap-8">
                <div class="col-span-1 md:mb-0 mb-6">
                        <?php include get_template_directory() . '/partials/product-filters.php'; ?>
                </div>

				<div class="md:col-span-3 col-span-1">
					<?php woocommerce_product_loop_start(); ?>

					<?php if (wc_get_loop_prop('total')) : ?>
						<?php while (have_posts()) : the_post(); ?>
							<?php do_action('woocommerce_shop_loop'); ?>
							<?php wc_get_template_part('content','product'); ?>
						<?php endwhile; ?>
					<?php endif; ?>

					<?php woocommerce_product_loop_end(); ?>
				</div>
			</div>

            <div class="mt-8">
                <?php do_action('woocommerce_after_shop_loop'); ?>
            </div>

		<?php else : ?>

			<?php do_action('woocommerce_no_products_found'); ?>

		<?php endif; ?>

	</div>
</section>

<?php
do_action('woocommerce_after_main_content');
get_footer('shop');