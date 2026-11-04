<?php

namespace Boilerplate\Models;

class Career extends PostModel
{
    protected $post;

    public function __construct($post)
    {
        $this->post = is_object($post) ? $post : get_post($post);
    }

    public static function getLatestCareers(int $count = 4): array
    {
        $query = new \WP_Query([
            'post_type'      => 'careers',
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

    public static function getAllCareers(): array
    {
        $query = new \WP_Query([
            'post_type'      => 'careers',
            'posts_per_page' => -1,
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
            'post_type'      => 'careers',
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

    public function getRelatedCareers(): array
    {
        $query = new \WP_Query([
            'post_type'      => 'careers',
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

    public function getWrittenOn(): string
    {
        $date = get_the_date('d/m/Y', $this->post);
        return $date ?: '';
    }

    public function getWrittenBy(int $maxLength = 30): string
    {
        $author_id = $this->post->post_author;
        $author_name = get_the_author_meta('display_name', $author_id);
        
        if (empty($author_name)) {
            return '';
        }
        
        if (strlen($author_name) > $maxLength) {
            return substr($author_name, 0, $maxLength) . '...';
        }
        
        return $author_name;
    }

    public static function getPaginated(int $page, int $perPage, array $args = []): array
    {
        $defaults = [
            'post_type'      => 'careers',
            'post_status'    => 'publish',
            'orderby'        => 'date',
            'order'          => 'DESC',
            'paged'          => $page,
            'posts_per_page' => $perPage,
            'no_found_rows'  => false,
        ];

        $query = new \WP_Query(array_merge($defaults, $args));

        $posts = $query->have_posts()
            ? array_map(fn($p) => new self($p), $query->posts)
            : [];

        $total   = (int) $query->found_posts;
        $hasMore = ($perPage * max(1, $page)) < $total;

        wp_reset_postdata();

        return [
            'careers'  => $posts,
            'total'    => $total,
            'has_more' => $hasMore,
        ];
    }
}



