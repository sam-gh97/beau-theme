<?php

namespace Boilerplate\Models;

class Spotlight extends PostModel
{
    protected $post;

    public function __construct($post)
    {
        $this->post = is_object($post) ? $post : get_post($post);
    }

    public static function getLatestSpotlights(int $count = 4): array
    {
        $query = new \WP_Query([
            'post_type'      => 'spotlight',
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

    public static function getAllSpotlights(): array
    {
        $query = new \WP_Query([
            'post_type'      => 'spotlight',
            'posts_per_page' => -1,
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
        $ids = array_filter(array_map('intval', $ids), fn($id) => $id > 0);

        if (empty($ids)) {
            return [];
        }

        $query = new \WP_Query([
            'post_type'      => 'spotlight',
            'post__in'       => $ids,
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

    public function getRelatedSpotlights(): array
    {
        $query = new \WP_Query([
            'post_type'      => 'spotlight',
            'posts_per_page' => 3,
            'post_status'    => 'publish',
            'post__not_in'   => [$this->post->ID],
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

    public function getProducts(): array
    {
        $product_ids = get_field('products', $this->post->ID);
        
        if (empty($product_ids)) {
            return [];
        }

        return Product::getByIds($product_ids);
    }

    public static function getPaginated(int $page, int $perPage, array $args = []): array
    {
        $defaults = [
            'post_type'      => 'spotlight',
            'post_status'    => 'publish',
            'orderby'        => 'date',
            'order'          => 'DESC',
            'paged'          => $page,
            'posts_per_page' => $perPage,
            'no_found_rows'  => false,
        ];

        $query = new \WP_Query(array_merge($defaults, $args));

        $spotlights = $query->have_posts()
            ? array_map(fn($p) => new self($p), $query->posts)
            : [];

        $total   = (int) $query->found_posts;
        $hasMore = ($perPage * max(1, $page)) < $total;

        wp_reset_postdata();

        return [
            'posts'    => $spotlights,
            'total'    => $total,
            'has_more' => $hasMore,
        ];
    }
}

