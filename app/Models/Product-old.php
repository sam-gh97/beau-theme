<?php

namespace Boilerplate\Models;

class Product_old extends PostModel
{
    protected $post;

    public function __construct($post)
    {
        $this->post = is_object($post) ? $post : get_post($post);
    }

    public static function getLatestProducts(int $count = 6): array
    {
        $query = new \WP_Query([
            'post_type'      => 'product',
            'posts_per_page' => $count,
            'post_status'    => 'publish',
            'orderby'        => 'date',
            'order'          => 'DESC',
            'no_found_rows'  => true,
        ]);

        if (!$query->have_posts()) {
            return [];
        }

        return array_map(
            fn($post) => new self($post),
            $query->posts
        );
    }

    public static function getByIds(array $ids): array
    {
        if (empty($ids)) {
            return [];
        }

        // Filter out invalid IDs and ensure they're integers
        $ids = array_filter(array_map('intval', $ids), fn($id) => $id > 0);

        if (empty($ids)) {
            return [];
        }

        $query = new \WP_Query([
            'post_type'      => 'product',
            'post__in'       => $ids,
            'post_status'    => 'publish',
            'orderby'        => 'post__in',
            'posts_per_page' => count($ids),
            'no_found_rows'  => true,
        ]);

        if (!$query->have_posts()) {
            return [];
        }

        return array_map(
            fn($post) => new self($post),
            $query->posts
        );
    }

    public function isFeatured(): bool
    {
        // Check if WooCommerce is active and use its method
        if (function_exists('wc_get_product')) {
            $wc_product = wc_get_product($this->post->ID);
            if ($wc_product) {
                return $wc_product->is_featured();
            }
        }

        // Fallback: Check product_visibility taxonomy for 'featured' term
        if (taxonomy_exists('product_visibility')) {
            $terms = wp_get_post_terms($this->post->ID, 'product_visibility', ['fields' => 'slugs']);
            return in_array('featured', $terms, true);
        }

        // Fallback: Check meta field (older WooCommerce versions)
        $featured = get_post_meta($this->post->ID, '_featured', true);
        return $featured === 'yes';
    }
}
