<?php

namespace Boilerplate\Taxonomies;

class Advertiser extends Taxonomy
{
    protected static string $slug = 'advertiser';
    protected static string|array $objectType = 'product';

    protected static array $labels = [
        'singular' => 'Advertiser',
        'plural'   => 'Advertisers',
    ];

    protected static array $args = [
        'hierarchical'      => false,
        'show_ui'           => true,
        'show_admin_column' => true,
        'show_in_rest'      => false,
        'meta_box_cb'       => false,
    ];
}
