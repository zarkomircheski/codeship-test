<?php
/*
 * This function serves only to instantiate the imgix helper class which
 * is what contains all the functionality for the imgix integration
 */
function init_imgix_helper()
{
    if (get_field('imgix_enabled', 'option')) {
        if (function_exists('is_wpe')) {
            if (!is_wpe_snapshot()) {
                Fatherly\Imgix\Helper::init(get_field('image_cdn_domain', 'option'));
            }
        } else {
            Fatherly\Imgix\Helper::init(get_field('image_cdn_domain', 'option'));
        }
    }
}

add_action('init', 'init_imgix_helper', 10);
