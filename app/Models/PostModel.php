<?php

namespace Boilerplate\Models;

class PostModel
{
    protected $post;

    public function __construct($post)
    {
        $this->post = is_object($post) ? $post : get_post($post);
    }

    public function getTitle(): string
    {
        return get_the_title($this->post);
    }

    public function getContent(): string
    {
        $content = strtoupper(get_the_content($this->post));

        return $content;
    }

    public function getExcerpt(): string
    {
        $excerpt = get_the_excerpt($this->post);

        if(empty($excerpt)) {
            $excerpt = wp_trim_words(get_the_content($this->post), 40, '...');
        }

        return $excerpt;
    }

    public function getImage(string $size = 'medium', array $classes = []): string
    {
        $class_string = implode(' ', $classes);
        
        return get_the_post_thumbnail($this->post, $size, ['class' => $class_string]);
    }

    public function getImageUrl(string $size = 'medium'): string
    {
        return get_the_post_thumbnail_url($this->post, $size);
    }

    public function getPermalink(): string
    {
        return get_permalink($this->post);
    }

    public function getId(): int
    {
        return $this->post->ID;
    }
}
