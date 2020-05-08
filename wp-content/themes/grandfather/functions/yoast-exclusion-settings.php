<?php

function fth_sitemap_exclude_post_types($value, $post_type)
{
    $excluded_posts = \Fatherly\Config\FatherlyConfig::config()->get('post_types_excluded_in_sitemap');
    if (in_array($post_type, $excluded_posts)) {
        return true;
    }
}

add_filter('wpseo_sitemap_exclude_post_type', 'fth_sitemap_exclude_post_types', 10, 2);

function fth_add_noindex_tag_to_excluded_post_types($robotStr)
{
    global $post;
    $post_type = get_post_type();
    $excluded_posts = \Fatherly\Config\FatherlyConfig::config()->get('post_types_excluded_in_sitemap');
    if (in_array($post_type, $excluded_posts)) {
        $robotStr = "noindex,follow";
    }
    return $robotStr;
}

add_filter('wpseo_robots', 'fth_add_noindex_tag_to_excluded_post_types', 10, 1);
