<?php

namespace Boilerplate\PostTypes;

class Spotlight extends CustomPostType
{
    protected static string $slug = 'spotlight';

    protected static array $labels = [
        'name' => 'Spotlights',
        'singular_name' => 'Spotlight',
        'menu_name' => 'Spotlights',
    ];

    protected static array $options = [
        'menu_icon' => 'dashicons-megaphone',
        'hierarchical' => true,
        'supports' => ['title', 'thumbnail'],
        'has_archive' => false,
        'rewrite' => [
            'slug' => 'spotlight',
            'with_front' => false,
        ],
    ];
}