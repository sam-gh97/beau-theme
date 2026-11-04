<?php

namespace Boilerplate\PostTypes;

class Business extends CustomPostType
{
    protected static string $slug = 'business';

    protected static array $labels = [
        'name' => 'Businesses',
        'singular_name' => 'Business',
        'menu_name' => 'Businesses',
    ];

    protected static array $options = [
        'menu_icon' => 'dashicons-building',
        'hierarchical' => true,
        'supports' => ['title'],
        'has_archive' => false,
    ];
}