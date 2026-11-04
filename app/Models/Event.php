<?php

namespace Boilerplate\Models;

class Event extends PostModel
{
    protected $post;

    public function __construct($post)
    {
        $this->post = is_object($post) ? $post : get_post($post);
    }

    public function getEventDate(): string
    {
        $start_date = get_field('event_date', $this->post->ID);
        $end_date = get_field('end_date', $this->post->ID);
        
        if (empty($start_date)) {
            return '';
        }

        $start_obj = $this->parseDate($start_date);
        
        if ($start_obj === false) {
            return $start_date;
        }

        // If no end date, return just the start date
        if (empty($end_date)) {
            return $start_obj->format('F j, Y');
        }

        $end_obj = $this->parseDate($end_date);
        
        if ($end_obj === false) {
            return $start_obj->format('F j, Y');
        }

        // Format date range
        return $this->formatDateRange($start_obj, $end_obj);
    }

    private function parseDate(string $date)
    {
        // Try multiple date formats that ACF might return
        $formats = ['Ymd', 'Y-m-d', 'd/m/Y', 'm/d/Y', 'd-m-Y'];
        $date_obj = false;
        
        foreach ($formats as $format) {
            $date_obj = \DateTime::createFromFormat($format, $date);
            if ($date_obj !== false) {
                break;
            }
        }
        
        // If all formats fail, try strtotime as fallback
        if ($date_obj === false) {
            $timestamp = strtotime($date);
            if ($timestamp !== false) {
                $date_obj = new \DateTime();
                $date_obj->setTimestamp($timestamp);
            }
        }
        
        return $date_obj;
    }

    private function formatDateRange(\DateTime $start, \DateTime $end): string
    {
        $start_year = $start->format('Y');
        $end_year = $end->format('Y');
        $start_month = $start->format('F');
        $end_month = $end->format('F');
        $start_day = $start->format('j');
        $end_day = $end->format('j');

        // Same year and month: "March 1–2, 2006"
        if ($start_year === $end_year && $start_month === $end_month) {
            return $start_month . ' ' . $start_day . '–' . $end_day . ', ' . $start_year;
        }

        // Same year, different months: "March 1–April 2, 2006"
        if ($start_year === $end_year) {
            return $start_month . ' ' . $start_day . '–' . $end_month . ' ' . $end_day . ', ' . $start_year;
        }

        // Different years: "December 31, 2005–January 1, 2006"
        return $start_month . ' ' . $start_day . ', ' . $start_year . '–' . $end_month . ' ' . $end_day . ', ' . $end_year;
    }

    public function getEventTime(): string
    {
        $start_time = get_field('start_time', $this->post->ID);
        $end_time = get_field('end_time', $this->post->ID);
        
        if (!empty($start_time) && !empty($end_time)) {
            // Format times to HH:mm format, removing am/pm
            $formatted_start = $this->formatTime($start_time);
            $formatted_end = $this->formatTime($end_time);
            
            return $formatted_start . ' - ' . $formatted_end;
        }
        
        $event_time = get_field('event_time', $this->post->ID);
        
        if (!empty($event_time)) {
            return $this->formatTime($event_time);
        }
        
        return '';
    }

    private function formatTime(string $time): string
    {
        // Remove any whitespace
        $time = trim($time);
        
        // Try to parse 12-hour format (e.g., "12:00 am", "5:00 pm")
        $time_obj = \DateTime::createFromFormat('g:i a', $time);
        
        if ($time_obj === false) {
            // Try 24-hour format (e.g., "12:00", "17:00")
            $time_obj = \DateTime::createFromFormat('H:i', $time);
        }
        
        if ($time_obj === false) {
            // Try format with seconds
            $time_obj = \DateTime::createFromFormat('H:i:s', $time);
        }
        
        if ($time_obj === false) {
            // Try alternative 12-hour formats
            $time_obj = \DateTime::createFromFormat('g:i A', $time);
        }
        
        if ($time_obj === false) {
            // Last resort: try strtotime
            $timestamp = strtotime($time);
            if ($timestamp !== false) {
                $time_obj = new \DateTime();
                $time_obj->setTimestamp($timestamp);
            }
        }
        
        if ($time_obj === false) {
            return $time; // Return original if parsing fails
        }
        
        // Return in HH:mm format (24-hour)
        return $time_obj->format('H:i');
    }

    public function getEventLocation(): string
    {
        $location = get_field('location', $this->post->ID);
        
        return $location ?: '';
    }

    public static function getPaginated(int $page, int $perPage, array $args = []): array
    {
        if ($perPage === null) {
            $perPage = get_option('posts_per_page');
        }

        $today = date('Ymd');

        $filter_callback = function($orderby, $query) use ($today) {
            if ($query->get('post_type') === 'event' && $query->get('event_custom_order')) {
                global $wpdb;
                // Upcoming events (ASC), then past events (DESC - most recent first)
                $orderby = "CASE WHEN CAST({$wpdb->postmeta}.meta_value AS UNSIGNED) >= {$today} THEN 0 ELSE 1 END ASC, 
                            CASE WHEN CAST({$wpdb->postmeta}.meta_value AS UNSIGNED) >= {$today} 
                            THEN CAST({$wpdb->postmeta}.meta_value AS UNSIGNED) END ASC,
                            CASE WHEN CAST({$wpdb->postmeta}.meta_value AS UNSIGNED) < {$today} 
                            THEN CAST({$wpdb->postmeta}.meta_value AS UNSIGNED) END DESC";
            }
            return $orderby;
        };

        add_filter('posts_orderby', $filter_callback, 10, 2);

        $defaults = [
            'post_type'      => 'event',
            'post_status'    => 'publish',
            'meta_key'       => 'event_date',
            'orderby'        => 'meta_value_num',
            'order'          => 'ASC',
            'paged'          => $page,
            'posts_per_page' => $perPage,
            'no_found_rows'  => false,
            'event_custom_order' => true,
        ];
    
        $query = new \WP_Query(array_merge($defaults, $args));
    
        // Remove filter after query to prevent conflicts
        remove_filter('posts_orderby', $filter_callback, 10);
    
        $posts = $query->have_posts()
            ? array_map(fn($p) => new self($p), $query->posts)
            : [];
    
        $total_posts = (int) $query->found_posts;
        $max_pages   = (int) $query->max_num_pages;
        $has_more    = $page < $max_pages;
    
        wp_reset_postdata();
    
        return [
            'posts'      => $posts,
            'total'      => $total_posts,
            'max_pages'  => $max_pages,
            'has_more'   => $has_more,
            'current'    => $page,
        ];
    }

    public function getEventRegisterLink(): string
    {
        $link = get_field('event_link', $this->post->ID);
        return $link ?: '';
    }
}
