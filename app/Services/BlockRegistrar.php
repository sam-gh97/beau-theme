<?php

namespace Boilerplate\Services;

class BlockRegistrar
{
    public static function getBlocks()
    {
        return [
            'content-block' => [
                'title' => 'Content Block',
                'icon'  => 'editor-paragraph',
            ],
            'main-hero' => [
                'title' => 'Main Hero Block',
                'icon'  => 'cover-image',
            ],
            'hero' => [
                'title' => 'Hero Block',
                'icon'  => 'cover-image',
            ],
            'products-slider' => [
                'title' => 'Products Slider Block',
                'icon'  => 'products',
            ],
            'text-image' => [
                'title' => 'Text Image Block',
                'icon'  => 'align-pull-left',
            ],
            'icons-grid' => [
                'title' => 'Icons Grid Block',
                'icon'  => 'grid-view',
            ],
            'testimonials-slider' => [
                'title' => 'Testimonials Slider',
                'icon'  => 'format-quote',
            ],
            'testimonials-grid' => [
                'title' => 'Testimonials Grid',
                'icon'  => 'format-quote',
            ],
            'latest-news' => [
                'title' => 'Latest News',
                'icon'  => 'admin-post',
            ],
            'brand-directory' => [
                'title' => 'Brand Directory',
                'icon'  => 'book-alt',
            ],
            'business-directory' => [
                'title' => 'Business Directory',
                'icon'  => 'building',
            ],
            'call-to-action' => [
                'title' => 'Call to Action',
                'icon'  => 'megaphone',
            ],
            'team-members' => [
                'title' => 'Team Members',
                'icon'  => 'groups',
            ],
            'feature-post' => [
                'title' => 'Feature Post',
                'icon'  => 'star-filled',
            ],
            'advert' => [
                'title' => 'Advert',
                'icon'  => 'megaphone',
            ],
            'faqs' => [
                'title' => 'FAQs',
                'icon'  => 'editor-help',
            ],
            'instagram-feed' => [
                'title' => 'Instagram Feed',
                'icon'  => 'instagram',
            ],
            'newsletter-cta' => [
                'title' => 'Newsletter CTA',
                'icon'  => 'email',
            ],
            'pages-grid' => [
                'title' => 'Pages Grid',
                'icon'  => 'admin-page',
            ],
            'images-slider' => [
                'title' => 'Images Slider',
                'icon'  => 'images-alt2',
            ],
            'why-join' => [
                'title' => 'Why Join Block',
                'icon'  => 'yes-alt',
            ],
            'video-section' => [
                'title' => 'Video Section',
                'icon'  => 'video-alt3',
            ],
            'form-block' => [
                'title' => 'Form Block',
                'icon'  => 'feedback',
            ],
            'logos-carousel' => [
                'title' => 'Logos Carousel',
                'icon'  => 'images-alt',
            ],
            'events-archive' => [
                'title'    => 'Events Archive',
                'icon'     => 'calendar-alt',
                'category' => 'archive',
            ],
            'posts-archive' => [
                'title'    => 'Posts Archive',
                'icon'     => 'admin-post',
                'category' => 'archive',
            ],
            'spotlights-archive' => [
                'title'    => 'Spotlights Archive',
                'icon'     => 'megaphone',
                'category' => 'archive',
            ],
            'careers-archive' => [
                'title'    => 'Careers Archive',
                'icon'     => 'businessman',
                'category' => 'archive',
            ],
        ];
    }

    public static function register()
    {
        if (!function_exists('acf_register_block_type')) {
            return;
        }

        add_filter('block_categories_all', [self::class, 'addCustomBlockCategory'], 10, 2);

        $blocks = self::getBlocks();

        foreach ($blocks as $name => $args) {
            acf_register_block_type(array_merge([
                'name'            => $name,
                'render_template' => get_template_directory() . "/blocks/{$name}.php",
                'category'        => $args['category'] ?? 'voodoochilli-blocks',
                'mode'            => 'edit',
                'supports'        => [
                    'align' => true,
                    'mode'  => true,
                ],
                'align' => 'full',
                'example' => [
                    'attributes' => [
                        'mode' => 'preview',
                        'data' => [
                            '_is_preview' => true,
                        ],
                    ],
                ],
            ], $args));
        }
    }

    public static function addCustomBlockCategory($categories, $post)
    {
        array_unshift($categories, [
            'slug'  => 'archive',
            'title' => 'Archive',
            'icon'  => 'archive',
        ]);

        array_unshift($categories, [
            'slug'  => 'voodoochilli-blocks',
            'title' => 'VoodooChilli Blocks',
            'icon'  => 'admin-customizer',
        ]);

        return $categories;
    }
}
