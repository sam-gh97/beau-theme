<?php

function partial($source) {
    return get_template_directory() . '/partials/' . $source;
}

function get_image($source) {
    return get_template_directory_uri() . '/assets/images/' . $source;
}