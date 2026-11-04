<?php

namespace Boilerplate\Integrations\HubSpot;

class Token
{
    public static function get(): ?string
    {
        if (!function_exists('get_field')) {
            error_log('[HubSpot] ACF get_field() function not found - is ACF Pro installed?');
            return null;
        }

        $token = get_field('hubspot_token', 'options');
        
        if (!is_string($token) || $token === '') {
            error_log('[HubSpot] Token is empty or not set in ACF options');
            return null;
        }

        return trim($token);
    }
}
