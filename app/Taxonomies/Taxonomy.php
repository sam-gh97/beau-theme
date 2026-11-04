<?php

namespace Boilerplate\Taxonomies;

abstract class Taxonomy
{
    protected static string $slug = '';
    protected static string|array $objectType = 'post';
    protected static array $labels = [];
    protected static array $args = [];

    public static function register(): void
    {
        if (!static::$slug) {
            return;
        }

        $label = static::$labels;
        $args  = static::$args;

        $defaultLabels = [
            'name' => $label['plural'] ?? 'Taxonomies',
            'singular_name' => $label['singular'] ?? 'Taxonomy',
            'search_items' => 'Search ' . ($label['plural'] ?? 'items'),
            'all_items' => 'All ' . ($label['plural'] ?? 'items'),
            'parent_item' => 'Parent ' . ($label['singular'] ?? 'item'),
            'parent_item_colon' => 'Parent ' . ($label['singular'] ?? 'item') . ':',
            'edit_item' => 'Edit ' . ($label['singular'] ?? 'item'),
            'update_item' => 'Update ' . ($label['singular'] ?? 'item'),
            'add_new_item' => 'Add New ' . ($label['singular'] ?? 'item'),
            'new_item_name' => 'New ' . ($label['singular'] ?? 'item'),
            'menu_name' => $label['plural'] ?? 'Taxonomies',
        ];

        $defaultArgs = [
            'hierarchical' => $args['hierarchical'] ?? true,
            'show_ui' => $args['show_ui'] ?? true,
            'show_in_rest' => $args['show_in_rest'] ?? true,
            'show_admin_column' => $args['show_admin_column'] ?? true,
            'query_var' => $args['query_var'] ?? true,
            'rest_base' => $args['rest_base'] ?? static::$slug,
            'rewrite' => $args['rewrite'] ?? ['slug' => static::$slug],
            'labels' => $defaultLabels,
        ];

        register_taxonomy(static::$slug, static::$objectType, $defaultArgs);
    }
}


