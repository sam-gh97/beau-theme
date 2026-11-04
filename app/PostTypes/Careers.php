<?php

namespace Boilerplate\PostTypes;

class Careers extends CustomPostType
{
    protected static string $slug = 'careers';

    protected static array $labels = [
        'name' => 'Inspiration',
        'singular_name' => 'Inspiration',
        'menu_name' => 'Inspirations',
    ];

    protected static array $options = [
        'menu_icon' => 'dashicons-businessman',
        'hierarchical' => true,
        'has_archive' => false,
        'rewrite' => [
            'slug' => 'inspiration',
            'with_front' => false,
        ],
        'supports' => ['title', 'thumbnail', 'editor', 'excerpt'],
    ];
}