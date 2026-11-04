<?php

namespace Boilerplate\Services;

class Menus
{
    public static function register() 
    {
        register_nav_menus([
            'main'   => 'Main Menu',
            'legal'  => 'Legal Menu',
            'footer_discover' => 'Footer Discover Menu',
            'footer_about' => 'Footer About Menu',
            'footer_useful_links' => 'Footer Useful Links Menu',
            'footer_support' => 'Footer Support Menu',
            'footer_learn' => 'Footer Learn Menu',
            'footer_connect' => 'Footer Connect Menu',
            'footer_legal' => 'Footer Legal Menu',
            'footer_haus' => 'Footer Haus Menu',
        ]);
    }

    public static function displayMainMenu()
    {
        wp_nav_menu([
            'theme_location' => 'main',
            'menu_id' => 'main-menu',
            'menu_class' => 'main-menu flex items-center gap-4',
            'walker' => new MainMenuWalker(),
        ]);
    }

    public static function displayFooterMenu($location)
    {
        wp_nav_menu([
            'theme_location' => $location,
            'menu_id' => $location . '-menu',
            'menu_class' => $location . '-menu flex flex-col body-2 text-white footer-menu gap-2',
            'walker' => new MainMenuWalker(),
        ]);
    }

    public static function displayMobileMenu()
    {
        wp_nav_menu([
            'theme_location' => 'main',
            'menu_id' => 'mobile-menu',
            'menu_class' => 'mobile-menu-list',
            'walker' => new MobileMenuWalker(),
        ]);
    }
}