<?php
defined('ABSPATH') || exit;

use Boilerplate\Models\Product;

get_header('shop');

do_action('woocommerce_before_main_content');

$product_id = (int) get_queried_object_id();
$wc_product = $product_id > 0 ? wc_get_product($product_id) : null;

if ($wc_product) {
    $my_product = new Product($wc_product->get_id());
    $breadcrumbs = $my_product->getBreadcrumbs();

    if (!empty($breadcrumbs) && is_array($breadcrumbs)) {
        ?>
        <nav
            aria-label="Breadcrumb"
            class="body-3 w-full flex justify-center items-center flex-wrap text-neutral-400 text-center"
            style="column-gap: var(--spacing-2); row-gap: var(--spacing-2); padding: 10px 10px; border-bottom: 1px solid var(--color-neutral-200);"
        >
            <?php
            $total = count($breadcrumbs);
            foreach ($breadcrumbs as $i => $crumb) :
                $fullLabel = (string) ($crumb['label'] ?? '');
                if ($fullLabel === '') {
                    continue;
                }
                $url = $crumb['url'] ?? null;
                $isLast = $i === ($total - 1);
                $isPenultimate = $i === ($total - 2);
                $itemClass = $isLast ? 'text-black' : 'text-neutral-400';
                $sepClass  = $isPenultimate ? 'text-black' : 'text-neutral-400';

                $displayLabel = $fullLabel;
                if ($isLast) {
                    $plain = wp_strip_all_tags($fullLabel);
                    $displayLabel = function_exists('wp_html_excerpt')
                        ? wp_html_excerpt($plain, 48, '…')
                        : $plain;
                }
                ?>
                <?php if (!$isLast) : ?>
                    <?php if (is_string($url) && $url !== '') : ?>
                        <a class="<?= esc_attr($itemClass); ?> hover:text-neutral-700 transition-colors" href="<?= esc_url($url); ?>">
                            <?= esc_html($displayLabel); ?>
                        </a>
                    <?php else : ?>
                        <span class="<?= esc_attr($itemClass); ?>"><?= esc_html($displayLabel); ?></span>
                    <?php endif; ?>

                    <span class="<?= esc_attr($sepClass); ?> flex items-center" aria-hidden="true">
                        <svg width="12" height="12" viewBox="0 0 12 12" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M4 10L8 6L4 2" stroke="currentColor" stroke-linecap="square"/>
                        </svg>
                    </span>
                <?php else : ?>
                    <span
                        class="<?= esc_attr($itemClass); ?>"
                        style="max-width: 60vw; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;"
                        <?php if ($displayLabel !== $fullLabel) : ?>
                            title="<?= esc_attr(wp_strip_all_tags($fullLabel)); ?>"
                        <?php endif; ?>
                    ><?= esc_html($displayLabel); ?></span>
                <?php endif; ?>
            <?php endforeach; ?>
        </nav>
        <?php
    }
}
?>

<div class="container">

    <?php
    while (have_posts()) :
        the_post();
        wc_get_template_part('content', 'single-product');
    endwhile;
    ?>

</div>

<?php
do_action('woocommerce_after_main_content');
get_footer('shop');
