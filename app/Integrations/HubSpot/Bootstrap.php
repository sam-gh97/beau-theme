<?php

namespace Boilerplate\Integrations\HubSpot;

class Bootstrap
{
    public static function init(): void
    {
        // error_log('[HubSpot] Bootstrap::init() called');
        
        if (!class_exists('WPCF7')) {
            error_log('[HubSpot] WPCF7 class not found - Contact Form 7 may not be installed or activated');
            return;
        }
        
        // error_log('[HubSpot] WPCF7 found, initializing Cf7Listener');
        new Cf7Listener();
    }
}