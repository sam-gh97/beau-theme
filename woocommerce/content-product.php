<?php
defined('ABSPATH') || exit;

global $product;

if (empty($product) || ! $product->is_visible()) {
    return;
}

$my_product = new Boilerplate\Models\Product($product);

get_template_part('partials/loop/product-card', null, [
	'product' => $my_product,
]);
