<?php

namespace Boilerplate\PostTypes;

class CustomPostType
{
    protected static string $slug = '';
    protected static array $labels = [];
    protected static array $options = [];

    public static function register()
    {
        if (!static::$slug) {
            return;
        }

        $plural_name = static::$labels['name'] ?? 'Custom Posts';
        $singular_name = static::$labels['singular_name'] ?? 'Custom Post';

        $default_labels = [
            'name' => $plural_name,
            'singular_name' => $singular_name,
            'menu_name' => $plural_name,
            'name_admin_bar' => $singular_name,
            'add_new' => 'Add New',
            'add_new_item' => 'Add New ' . $singular_name,
            'edit_item' => 'Edit ' . $singular_name,
            'new_item' => 'New ' . $singular_name,
            'view_item' => 'View ' . $singular_name,
            'all_items' => 'All ' . $plural_name,
            'search_items' => 'Search ' . $plural_name,
        ];

        $default_args = [
            'label' => $singular_name,
            'supports' => ['title', 'editor', 'thumbnail'],
            'taxonomies' => [],
            'public' => true,
            'has_archive' => true,
            'show_in_rest' => true,
        ];

        $labels = array_merge($default_labels, static::$labels);
        $args = array_merge($default_args, static::$options, ['labels' => $labels]);

        register_post_type(static::$slug, $args);
    }
}
