<?php

namespace Boilerplate\Taxonomies;

class BusinessType extends Taxonomy
{
    protected static string $slug = 'business_type';
    protected static string|array $objectType = 'business';

    protected static array $labels = [
        'singular' => 'Business Type',
        'plural'   => 'Business Types',
    ];

    protected static array $args = [
        'hierarchical' => false,
        'show_ui' => true,
        'show_admin_column' => true,
    ];
}
