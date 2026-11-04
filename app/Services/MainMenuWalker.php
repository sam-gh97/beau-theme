<?php

namespace Boilerplate\Services;

use Walker_Nav_Menu;

// Custom menu walker - only renders 2 levels (top-level + sub-menu)
class MainMenuWalker extends Walker_Nav_Menu
{
    protected function getTopLevelItemClasses($item, $classes)
    {
        $custom_classes = [];
        
        if (in_array('menu-item-has-children', $classes)) {
            $custom_classes[] = 'has-dropdown';
        }
        
        return implode(' ', $custom_classes);
    }

    protected function getTopLevelLinkClasses($item, $classes)
    {
        return 'body-2';
    }

    protected function getSubMenuItemClasses($item, $classes)
    {
        return '';
    }

    protected function getSubMenuLinkClasses($item, $classes)
    {
        return 'body-2';
    }

    protected function getSubMenuClasses()
    {
        return 'sub-menu';
    }

    public function start_lvl(&$output, $depth = 0, $args = null)
    {
        if ($depth > 0) {
            return;
        }
        
        $indent = str_repeat("\t", $depth);
        $sub_menu_classes = $this->getSubMenuClasses();
        $output .= "\n$indent<ul class=\"" . esc_attr($sub_menu_classes) . "\">\n";
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

        if ($depth === 0) {
            $custom_item_classes = $this->getTopLevelItemClasses($item, $classes);
        } else {
            $custom_item_classes = $this->getSubMenuItemClasses($item, $classes);
        }

        $all_classes = array_merge($classes, explode(' ', $custom_item_classes));
        $class_names = join(' ', apply_filters('nav_menu_css_class', array_filter($all_classes), $item, $args));
        $class_names = $class_names ? ' class="' . esc_attr($class_names) . '"' : '';

        $id = apply_filters('nav_menu_item_id', 'menu-item-' . $item->ID, $item, $args);
        $id = $id ? ' id="' . esc_attr($id) . '"' : '';

        $output .= $indent . '<li' . $id . $class_names . '>';

        $attributes = !empty($item->attr_title) ? ' title="' . esc_attr($item->attr_title) . '"' : '';
        $attributes .= !empty($item->target) ? ' target="' . esc_attr($item->target) . '"' : '';
        $attributes .= !empty($item->xfn) ? ' rel="' . esc_attr($item->xfn) . '"' : '';
        $attributes .= !empty($item->url) ? ' href="' . esc_attr($item->url) . '"' : '';

        if ($depth === 0) {
            $link_classes = $this->getTopLevelLinkClasses($item, $classes);
        } else {
            $link_classes = $this->getSubMenuLinkClasses($item, $classes);
        }

        $item_output = isset($args->before) ? $args->before : '';
        $item_output .= '<a' . $attributes . ' class="' . esc_attr($link_classes) . '">';
        $item_output .= (isset($args->link_before) ? $args->link_before : '') . apply_filters('the_title', $item->title, $item->ID) . (isset($args->link_after) ? $args->link_after : '');
        
        // Add chevron for items with children (only at top level)
        if ($depth === 0 && in_array('menu-item-has-children', $classes)) {
            $chevron_url = get_template_directory_uri() . '/assets/images/icons/chevron-down.svg';
            $item_output .= '<img src="' . esc_url($chevron_url) . '" alt="" class="menu-chevron inline-block w-3 h-3 transition-transform duration-200" style="margin-left: 6px;" />';
        }
        
        $item_output .= '</a>';
        $item_output .= isset($args->after) ? $args->after : '';

        $output .= apply_filters('walker_nav_menu_start_el', $item_output, $item, $depth, $args);
    }

    public function end_el(&$output, $item, $depth = 0, $args = null)
    {
        if ($depth > 1) {
            return;
        }
        
        $output .= "</li>\n";
    }
}

