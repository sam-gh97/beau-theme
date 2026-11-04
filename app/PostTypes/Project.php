<?php

namespace Boilerplate\PostTypes;

class Project extends CustomPostType
{
    protected static string $slug = 'project';

    protected static array $labels = [
        'name' => 'Projects',
        'singular_name' => 'Project',
        'menu_name' => 'Projects',
    ];

    protected static array $options = [
        'menu_icon' => 'dashicons-hammer',
        'hierarchical' => true,
        'supports' => ['title', 'thumbnail', 'editor', 'page-attributes', 'excerpt'],
    ];
}