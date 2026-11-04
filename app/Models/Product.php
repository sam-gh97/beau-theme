<?php

namespace Boilerplate\Models;

class Product extends PostModel
{
    protected $post;
    protected $wc;

    public function __construct($item)
    {
        if ($item instanceof \WC_Product) {
            $this->wc = $item;
            $this->post = get_post($item->get_id());
            return;
        }

        if ($item instanceof \WP_Post) {
            $this->post = $item;
            return;
        }

        $this->post = get_post((int) $item);
    }

    protected function wc()
    {
        if (! $this->wc) {
            $this->wc = wc_get_product($this->post->ID);
        }
        return $this->wc;
    }

    public function getId(): int
    {
        return $this->post->ID;
    }

    public function getTitle(): string
    {
        return get_the_title($this->post);
    }

    public function getPermalink(): string
    {
        return get_permalink($this->post);
    }

    public function getImageUrl($size = 'large'): string
    {
        $url = get_the_post_thumbnail_url($this->post, $size);
        return $url ?: '';
    }

    public function isFeatured(): bool
    {
        $wc = $this->wc();
        if ($wc) {
            return $wc->is_featured();
        }

        $terms = wp_get_post_terms($this->post->ID, 'product_visibility', ['fields' => 'slugs']);
        if (in_array('featured', $terms, true)) {
            return true;
        }

        $meta = get_post_meta($this->post->ID, '_featured', true);
        return $meta === 'yes';
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

        if (! $query->have_posts()) {
            return [];
        }

        return array_map(
            fn($post) => new self($post),
            $query->posts
        );
    }

    public static function getByIds(array $ids): array
    {
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

        if (! $query->have_posts()) {
            return [];
        }

        return array_map(
            fn($post) => new self($post),
            $query->posts
        );
    }

    public function getFeaturedInPostIds(string $acfField = 'featured_products'): array
    {
        $ids = get_posts([
            'post_type'      => 'post',
            'posts_per_page' => -1,
            'fields'         => 'ids',
            'meta_query'     => [
                [
                    'key'     => $acfField,
                    'value'   => '"' . $this->post->ID . '"',
                    'compare' => 'LIKE',
                ]
            ]
        ]);
    
        if (!is_array($ids) || empty($ids)) {
            return [];
        }
    
        return $ids;
    }

    public function getBrand(): ?string
    {
        if (!taxonomy_exists('product_brand')) {
            return null;
        }

        $terms = wp_get_post_terms($this->post->ID, 'product_brand', ['fields' => 'names']);
        
        if (is_wp_error($terms) || empty($terms)) {
            return null;
        }

        return $terms[0];
    }

    public function getPrice(): ?string
    {
        $wc = $this->wc();
        if (!$wc) {
            return null;
        }

        $price = $wc->get_regular_price();
        return $price !== '' ? $price : null;
    }

    public function getBreadcrumbs(): array
    {
        $items = [];

        $items[] = [
            'label'   => 'Home',
            'url'     => home_url('/'),
            'current' => false,
        ];

        $shopId = function_exists('wc_get_page_id') ? (int) wc_get_page_id('shop') : 0;
        $shopUrl = $shopId > 0 ? get_permalink($shopId) : home_url('/shop/');
        $items[] = [
            'label'   => 'Shop',
            'url'     => $shopUrl ?: home_url('/shop/'),
            'current' => false,
        ];

        if (taxonomy_exists('product_cat')) {
            $terms = wp_get_post_terms($this->post->ID, 'product_cat', [
                'orderby' => 'term_order',
                'order'   => 'ASC',
            ]);

            if (!is_wp_error($terms) && is_array($terms) && !empty($terms) && $terms[0] instanceof \WP_Term) {
                $term = $terms[0];
                $termLink = get_term_link($term, 'product_cat');

                $items[] = [
                    'label'   => (string) $term->name,
                    'url'     => !is_wp_error($termLink) ? (string) $termLink : null,
                    'current' => false,
                ];
            }
        }

        $items[] = [
            'label'   => $this->getTitle(),
            'url'     => null,
            'current' => true,
        ];

        return $items;
    }
}
