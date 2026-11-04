<?php

namespace Boilerplate\Models;

class Business extends PostModel
{
    protected $post;

    public function __construct($post)
    {
        $this->post = is_object($post) ? $post : get_post($post);
    }

    public static function getAllBusinesses(): array
    {
        $query = new \WP_Query([
            'post_type'      => 'business',
            'posts_per_page' => -1,
            'post_status'    => 'publish',
            'orderby'        => 'title',
            'order'          => 'ASC',
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
            'post_type'      => 'business',
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

    public function getBusinessTypes(): array
    {
        $terms = wp_get_post_terms($this->post->ID, 'business_type');
        
        if (is_wp_error($terms) || empty($terms)) {
            return [];
        }

        return $terms;
    }

    public function getWebsite(): ?string
    {
        $website = get_field('business_link', $this->post->ID);
        return $website ?: null;
    }

    public function getPhone(): ?string
    {
        $phone = get_field('phone', $this->post->ID);
        return $phone ?: null;
    }

    public function getEmail(): ?string
    {
        $email = get_field('email', $this->post->ID);
        return $email ?: null;
    }

    public function getAddress(): ?string
    {
        $address = get_field('address', $this->post->ID);
        return $address ?: null;
    }
}

