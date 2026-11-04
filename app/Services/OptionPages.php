<?php

namespace Boilerplate\Services;

class OptionPages
{
    public static function register() 
    {
        if (!function_exists('acf_add_options_page')) {
            return;
        }

        acf_add_options_page([
            'page_title' => 'Global Settings',
            'menu_title' => 'Global Settings',
            'menu_slug'  => 'global-settings',
            'capability' => 'edit_posts',
            'icon'   => 'dashicons-admin-generic',
            'redirect'   => false,
        ]);

    }
}