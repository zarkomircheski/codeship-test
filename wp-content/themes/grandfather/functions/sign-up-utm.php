<?php
function fth_get_utm_info()
{
    $output = array();
    if (is_page('sign-up-custom')) {
        parse_str(parse_url($_SERVER['REQUEST_URI'], PHP_URL_QUERY), $queryarg);
        if (isset($queryarg['utm_source'])) {
            $output['name'] = $queryarg['utm_source'];
            if ($output['name'] == 'skillshare' || $output['name'] == 'primary') {
                $output['cta'] = "Sign up for parenting advice and ideas froxyzm ridiculously overqualified experts";
            } elseif ($output['name'] == 'cricket') {
                $output['cta'] = "Sign up for parenting advice and ideas froabcm ridiculously overqualified experts";
            } elseif ($output['name'] == 'energystar') {
                $output['cta'] = "Enter To <span class=\"drop-title\">Win The Wash</span>";
            } else {
                $output['cta'] = '';
            }
        }
    }
    return $output;
}

function fth_get_utm_class()
{
    $output = '';
    if (is_page('sign-up-custom')) {
        parse_str(parse_url($_SERVER['REQUEST_URI'], PHP_URL_QUERY), $queryarg);
        if (isset($queryarg['utm_source'])) {
            $output = ' ' . $queryarg['utm_source'];
        }
    }
    return $output;
}

function fth_tag_kv($post_id)
{
    $tag_string = '';
    if (is_single()) {
        // $post_tags = wp_get_post_terms( $post_id, 'fields' );
        $post_tags = wp_get_post_tags($post_id);
        $tag_string = '';
        $tag_count = 0;
        if (isset($post_tags) && count($post_tags) > 0) {
            foreach ($post_tags as $post_tag) {
                $tag_count++;
                if (count($post_tags) > 1 && $tag_count < count($post_tags)) {
                    $separator = ',';
                } else {
                    $separator = '';
                }
                $tag_string .= "'" . str_replace('\'', '\\\'', $post_tag->name)  . "'" . $separator;
            }
        }
    }
    return $tag_string;
}
