<?php

namespace Boilerplate\Taxonomies;

use Boilerplate\Taxonomies\BusinessType;
use Boilerplate\Taxonomies\Advertiser;

class TaxonomyManager
{
    /** @var array<class-string<Taxonomy>> */
    private static array $taxonomies = [
        // MediaTag::class,
        // VideoTag::class,
        BusinessType::class,
        Advertiser::class,
    ];

    public static function register(): void
    {
        foreach (self::$taxonomies as $taxonomyClass) {
            if (class_exists($taxonomyClass) && is_subclass_of($taxonomyClass, Taxonomy::class)) {
                $taxonomyClass::register();
            }
        }
    }
}


