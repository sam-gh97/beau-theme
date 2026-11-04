<?php

namespace Boilerplate;

use Boilerplate\Services\Menus;
use Boilerplate\Services\BlockRegistrar;
use Boilerplate\Services\OptionPages;
use Boilerplate\PostTypes\PostTypeManager;
use Boilerplate\Taxonomies\TaxonomyManager;

class Theme
{
    private static $instance = null;

    private function __construct()
    {
        $this->load_dependencies();
        $this->setup_theme();
    }

    public static function get_instance(): self
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    private function load_dependencies()
    {
        require_once get_template_directory() . '/includes/enqueue.php';
        require_once get_template_directory() . '/includes/helpers.php';
    }

    private function setup_theme()
    {
        PostTypeManager::register();
        TaxonomyManager::register();

        add_theme_support('post-thumbnails');
        add_theme_support('title-tag');
        add_theme_support('align-wide');
        add_theme_support('editor-styles');

        add_action('after_setup_theme', [Menus::class, 'register']);

        add_action('acf/init', [BlockRegistrar::class, 'register']);
        add_action('acf/init', [OptionPages::class, 'register']);

        add_filter('show_admin_bar', '__return_false');
    }

}
