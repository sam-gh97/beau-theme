<?php

namespace Boilerplate\Services;

use Walker_Nav_Menu;

class MobileMenuWalker extends Walker_Nav_Menu
{
    public function start_lvl(&$output, $depth = 0, $args = null)
    {
        if ($depth > 0) {
            return;
        }
        
        $indent = str_repeat("\t", $depth);
        $output .= "\n$indent<ul class=\"mobile-submenu hidden pt-2\">\n";
    }

    public function end_lvl(&$output, $depth = 0, $args = null)
    {
        if ($depth > 0) {
            return;
        }
        
        $indent = str_repeat("\t", $depth);
        $output .= "$indent</ul>\n";
    }

    public function start_el(&$output, $item, $depth = 0, $args = null, $id = 0)
    {
        if ($depth > 1) {
            return;
        }

        $indent = ($depth) ? str_repeat("\t", $depth) : '';
        $classes = empty($item->classes) ? [] : (array) $item->classes;
        $classes[] = 'menu-item-' . $item->ID;
        
        $has_children = in_array('menu-item-has-children', $classes);
        
        if ($depth === 0) {
            $item_classes = 'mobile-menu-item border-b border-neutral-200 py-4';
            $link_classes = 'mobile-menu-link';
        } else {
            $item_classes = 'mobile-submenu-item py-2';
            $link_classes = 'mobile-submenu-link body-2';
        }

        $all_classes = array_merge($classes, explode(' ', $item_classes));
        $class_names = join(' ', apply_filters('nav_menu_css_class', array_filter($all_classes), $item, $args));
        $class_names = $class_names ? ' class="' . esc_attr($class_names) . '"' : '';

        $id = apply_filters('nav_menu_item_id', 'menu-item-' . $item->ID, $item, $args);
        $id = $id ? ' id="' . esc_attr($id) . '"' : '';

        $output .= $indent . '<li' . $id . $class_names . '>';

        if ($depth === 0 && $has_children) {
            $chevron_icon = get_template_directory_uri() . '/assets/images/icons/menu-chevron.svg';
            $output .= '<button class="mobile-submenu-toggle flex items-center justify-between w-full text-left">';
            $output .= '<span class="' . esc_attr($link_classes) . ' flex-1">';
            $output .= apply_filters('the_title', $item->title, $item->ID);
            $output .= '</span>';
            $output .= '<img src="' . esc_url($chevron_icon) . '" alt="" class="transition-transform duration-200 flex-shrink-0 w-[12px] h-[12px]" />';
            $output .= '</button>';
        } else {
            $attributes = !empty($item->attr_title) ? ' title="' . esc_attr($item->attr_title) . '"' : '';
            $attributes .= !empty($item->target) ? ' target="' . esc_attr($item->target) . '"' : '';
            $attributes .= !empty($item->xfn) ? ' rel="' . esc_attr($item->xfn) . '"' : '';
            $attributes .= !empty($item->url) ? ' href="' . esc_attr($item->url) . '"' : '';

            $output .= '<a' . $attributes . ' class="' . esc_attr($link_classes) . '">';
            $output .= apply_filters('the_title', $item->title, $item->ID);
            $output .= '</a>';
        }
    }

    public function end_el(&$output, $item, $depth = 0, $args = null)
    {
        if ($depth > 1) {
            return;
        }
        
        $output .= "</li>\n";
    }
}

