<?php
/**
 * Very lightweight DataLayer to enable interaction between
 * WordPress and JavaScript.
 */
function generateDataLayer()
{
    global $post;
    $ga_id = \Fatherly\Config\FatherlyConfig::config()->get('ga_id');

    // Initialize the datalayer object
    $dl = array();
    $dl['ga_tracking_id'] = $ga_id;
    $dl['nielsen'] = array();
    if (isset($post)) {
        // Set data we need

        $dl['title'] = $post->post_title;
        $dl['categories'] = array_map(function ($catId) {
            $cat = get_category($catId);
            return ($cat) ? $cat->name : $catId;
        }, wp_get_post_categories($post->ID));
        $category_slugs = array_map(function ($catId) {
            $cat = get_category($catId);
            return ($cat) ? $cat->slug : $catId;
        }, wp_get_post_categories($post->ID));
        if (is_category() || is_tag() || is_tax('stages')) {
            $dl['nielsen']['segA'] = (is_category() ? 'category' : (is_tax('stages') ? 'stages_tax' : 'tag'));
            $dl['nielsen']['asn'] = (get_queried_object())->slug;
        } elseif (is_front_page()) {
            $dl['nielsen']['asn'] = "homepage";
            $dl['nielsen']['segA'] = "NA";
        } else {
            $post_type = get_post_type($post);
            if ($post_type === 'post') {
                $dl['nielsen']['asn'] = implode(',', $category_slugs);
                $dl['nielsen']['segA'] = "article";
            } else {
                $dl['nielsen']['asn'] = $post_type;
                $dl['nielsen']['segA'] = "NA";
            }
        }

        // Cleanup
        foreach ($dl as $key => $item) {
            if (empty($item)) {
                unset($dl[$key]);
            }
        }
    } else {
        global $wp_query;
        global $wp;
        if (is_search()) {
            $dl['title'] = sprintf("Search for \"%s\" yielded 0 results", $wp->query_vars['s']);
        }
        if (is_404()) {
            $dl['title'] = sprintf("404 on \"%s\"", $_SERVER['REQUEST_URI']);
        }
        /* TODO: Once we establish a method of logging there will need to be an item added here to log when this else
        condition is triggered. */
    }
    echo sprintf('<script type="text/javascript">window.fatherlyDataLayer = %s</script>', wp_json_encode($dl));
}

add_action('wp_head', 'generateDataLayer');
