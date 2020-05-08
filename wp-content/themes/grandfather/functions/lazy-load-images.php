<?php
add_action('init', 'my_replace_image_urls');

function my_replace_image_urls()
{
    if (defined('Fatherly_ENV')) {
        if (Fatherly_ENV == 'local') {
            add_filter('wp_get_attachment_url', 'local_attachment_url', 10, 2);
        }
    } elseif (function_exists('is_wpe')) {
        if (is_wpe_snapshot()) {
            add_filter('wp_get_attachment_url', 'staging_attachment_url', 10, 2);
        }
    }
}

function local_attachment_url($url, $post_id)
{
    return str_replace('php.hgv.test', 'www.fatherly.com', $url);
}

function staging_attachment_url($url, $post_id)
{
    return str_replace('fatherly2016.staging.wpengine.com', 'www.fatherly.com', $url);
}

function reverse_local_attachment_url($url, $post_id)
{
    return str_replace('www.fatherly.com', 'php.hgv.test', $url);
}

function reverse_staging_attachment_url($url, $post_id)
{
    return str_replace('www.fatherly.com', 'fatherly2016.staging.wpengine.com', $url);
}

function reverse_images()
{
    if (defined('Fatherly_ENV')) {
        if (Fatherly_ENV == 'local') {
            add_filter('wp_get_attachment_url', 'reverse_local_attachment_url', 10, 2);
        }
    } elseif (function_exists('is_wpe')) {
        if (is_wpe_snapshot()) {
            add_filter('wp_get_attachment_url', 'reverse_staging_attachment_url', 10, 2);
        }
    }
}

// add_filter( 'max_srcset_image_width', 'fth_remove_scrset' );
function fth_remove_scrset()
{
    return 1;
}

// add_filter('wp_get_attachment_image_attributes', 'lazy_load_srcset', 10, 2 );
function lazy_load_srcset($attr)
{
    if (!is_admin()) {
        if (function_exists('is_amp_endpoint')) {
            if (!is_amp_endpoint()) {
                $attr['data-src'] = $attr['src'];
                $attr['src'] = '/wp-content/themes/grandfather/images/fatherly-transparent-pixel.png';
                $attr['class'] = $attr['class'] . ' lazyload';
            }
        }
    }
    return $attr;
}
function bjll_compat_amp()
{
    if (function_exists('is_amp_endpoint') && is_amp_endpoint()) {
        add_filter('bjll/enabled', '__return_false');
    }
}

add_action('bjll/compat', 'bjll_compat_amp');

// function add_image_class($class){
//     $class .= ' lazyload';
//     return $class;
// }
// add_filter('get_image_tag_class','add_image_class');

function add_image_responsive_class($content)
{
    global $post;
    $pattern ="/<img(.*?)class=\"(.*?)\"(.*?)>/i";
    $replacement = '<img$1class="$2 lazyload"$3>';
    $content = preg_replace($pattern, $replacement, $content);
    return $content;
}

function change_img_attribute_src($content)
{
    global $post;
    if (preg_match("/<img.*?class=/i", $content)) {
        $pattern = "/(<img.*?)src/";
        $replacement = '$1data-src';
        $content = preg_replace($pattern, $replacement, $content);
    }
    return $content;
}

// add_filter('the_content', 'add_image_responsive_class');
// add_filter('the_content', 'change_img_attribute_src');
