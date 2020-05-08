<?php
/**
 * Filter for page title to override the Yoast plugin
 */
add_filter('pre_get_document_title', function ($title) {
    if (is_single()) {
        return $title . ' | Fatherly';
    } else {
        return $title;
    }
}, 999, 1);

add_action('wp_head', function () {
    if (is_archive() || is_search()) {
        $lead_image = get_the_post_thumbnail_url(get_option('dfi_image_id'));
        echo '<meta property="og:image" content="' . $lead_image . '" />';
    }
});

/**
 * Add Twitter Site to Yoast SEO Twitter card
 */
function fth_yoast_twitter_site($this_options_twitter_site)
{
    return '@fatherlyhq';
};
add_filter('wpseo_twitter_site', 'fth_yoast_twitter_site', 10, 1);


function fth_change_title($title)
{
    $newTitle = getListData('title');
    $title = $newTitle ? $newTitle : $title;
    return $title;
}
add_filter('wpseo_opengraph_title', 'fth_change_title');

function fth_change_twitter_title($title)
{
    $newTitle = getListData('title');
    $title = $newTitle ? $newTitle : $title;
    return $title;
}
add_filter('wpseo_twitter_title', 'fth_change_twitter_title');

function fth_change_image($image)
{
    $newImage = getListData('image');
    if ($newImage) {
        $image = $newImage;
    } else if (is_single()) {
        $yoast_module_image = WPSEO_Meta::get_value('opengraph-image-id');
        $featured_image = get_post_thumbnail_id();
        if (!empty($yoast_module_image)) {
            return fth_img(array('attachment_id' => $yoast_module_image, 'width' => 600));
        } else if (!empty($featured_image)) {
            return fth_img(array('attachment_id' => $featured_image, 'width' => 600));
        }
    }

    return $image;
}
add_filter('wpseo_opengraph_image', 'fth_change_image');

function fth_add_image_if_empty($ogImageArray)
{
    // check if any og images have been set, if not force it to add one
    if (!$ogImageArray->has_images()) {
        $image_to_add = fth_change_image(null);
        $ogImageArray->add_image($image_to_add);
    }
    return $ogImageArray;
}
add_filter('wpseo_add_opengraph_additional_images', 'fth_add_image_if_empty');

function fth_twitter_change_image($image)
{
    $newImage = getListData('image');
    $image = $newImage ? $newImage : $image;
    return $image;
}
add_filter('wpseo_twitter_image', 'fth_twitter_change_image');

function fth_change_url($url)
{
    $newUrl = getListData('item_slug');
    $url = $newUrl ? $newUrl : $url;
    return $url;
}
add_filter('wpseo_opengraph_url', 'fth_change_url');

function fth_change_type($type)
{
    global $wp_query;
    if (array_key_exists('list_item', $wp_query->query_vars) && $listItemSlug = $wp_query->query_vars['list_item']) {
        $type = 'list';
    }
    return $type;
}

add_filter('wpseo_opengraph_type', 'fth_change_type');

function fth_set_page_title_on_list_items($title)
{
    $newTitle = getListData('title');
    $title = $newTitle ? $newTitle : $title;
    return $title;
}

add_filter('wpseo_title', 'fth_set_page_title_on_list_items');

function getListData($item)
{
    $return = false;
    global $wp_query;
    if (array_key_exists('list_item', $wp_query->query_vars) && $listItemSlug = $wp_query->query_vars['list_item']) {
        global $post;
        $newListItem = \Fatherly\Listicle\Helper::getPublishedListItem($post, substr($listItemSlug, -3));
        if ($item === 'image') {
            // If none of the if conditions below are met setting this to null will have fth_img return the lead image of the post
            $image = null;
            if ($newListItem[0]['is_social'] && $newListItem[0]['social_image']['ID']) {
                $image = $newListItem[0]['social_image']['ID'];
            } else if ($newListItem[0]['image']['ID']) {
                $image = $newListItem[0]['image']['ID'];
            } else if (get_post_meta($post->ID)['_yoast_wpseo_opengraph-image'][0]) {
                return get_post_meta($post->ID)['_yoast_wpseo_opengraph-image'][0].'?q=65&enable=upscale&w=1200';
            }
            $return =  fth_img(array('width' => 1200, 'retina' => false,'attachment_id' => $image));
        } else if ($item === 'title') {
            $return = get_the_title($post).' | ';
            $return .= $newListItem[0]['item_number'] ? '#'.$newListItem[0]['item_number'].' '.$newListItem[0]['headline'] : $newListItem[0]['headline'];
        } else {
            $return = $newListItem[0][$item];
        }
    }
    return $return;
}
