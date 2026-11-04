<?php

namespace Boilerplate\PostTypes;

use Boilerplate\PostTypes\Event;
use Boilerplate\PostTypes\Spotlight;
use Boilerplate\PostTypes\Business;
use Boilerplate\PostTypes\Careers;

class PostTypeManager
{
    public static function register()
    {
        add_action('init', [Event::class, 'register']);
        add_action('init', [Business::class, 'register']);
        add_action('init', [Spotlight::class, 'register']);
        add_action('init', [Careers::class, 'register']);
    }
}