<?php

namespace Boilerplate\PostTypes;

class Event extends CustomPostType
{
    protected static string $slug = 'event';

    protected static array $labels = [
        'name' => 'Events',
        'singular_name' => 'Event',
        'menu_name' => 'Events',
    ];

    protected static array $options = [
        'menu_icon' => 'dashicons-calendar-alt',
        'hierarchical' => true,
        'supports' => ['title', 'thumbnail'],
    ];
}