<?php 

function boilerplate_asset(string $entry): ?array {
    static $manifest;

    if (!isset($manifest)) {
        $manifest_path = get_template_directory() . '/dist/.vite/manifest.json';

        if (!file_exists($manifest_path)) {
            return null;
        }

        $manifest = json_decode(file_get_contents($manifest_path), true);
    }

    return $manifest[$entry] ?? null;
}


function boilerplate_enqueue_assets() {
    $is_dev = in_array(wp_get_environment_type(), ['development', 'local'], true);
    $vite_running = false;

    if ($is_dev) {
        $curl = curl_init('http://localhost:5173/@vite/client');
        curl_setopt_array($curl, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CONNECTTIMEOUT_MS => 100,
            CURLOPT_TIMEOUT_MS => 300,
        ]);
        curl_exec($curl);
        $http_code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        curl_close($curl);

        $vite_running = $http_code >= 200 && $http_code < 300;
    }

    if ($is_dev && $vite_running) {
        add_action('wp_footer', function () {
            echo '<script type="module" src="http://localhost:5173/@vite/client"></script>';
            echo '<script type="module" src="http://localhost:5173/assets/js/app.js"></script>';
        }, 100);
    } else {
        $entry = boilerplate_asset('assets/js/app.js');

        if (!$entry) {
            return;
        }

        if (!empty($entry['css'])) {
            foreach ($entry['css'] as $css_file) {
                wp_enqueue_style('theme-style', get_template_directory_uri() . '/dist/' . $css_file, [], null);
            }
        }

        wp_enqueue_script('theme-script', get_template_directory_uri() . '/dist/' . $entry['file'], [], null, true);
    }
}
add_action('wp_enqueue_scripts', 'boilerplate_enqueue_assets');

function block_editor_enqueue_assets() {
    $entry = boilerplate_asset('assets/js/app.js');

    if (!empty($entry['css'])) {
        foreach ($entry['css'] as $css_file) {
            wp_enqueue_style('theme-editor-style', get_template_directory_uri() . '/dist/' . $css_file, [], null);
        }
    }
}
add_action('enqueue_block_editor_assets', 'block_editor_enqueue_assets');
