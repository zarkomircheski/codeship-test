<?php
// take post categories and return a sequentially nested array from parent to child
function fth_get_nested_categories($categories, $return_array = [], $parent_id = 0)
{
    foreach ($categories as $category) {
        if ($category->category_parent === $parent_id) {
            array_push($return_array, $category);
            $new_parent_id = $return_array[count($return_array) - 1]->term_id;
            if (count($return_array) == 2) {
                break;
            } else {
                $return_array = fth_get_nested_categories($categories, $return_array, $new_parent_id);
            }
        }
    }

    return $return_array;
}

// take post categories and return nested category links
function fth_category_breadcrumbs($categories)
{
    if (count($categories) > 1) {
        $category_array = fth_get_nested_categories($categories);
    } else {
        $category_array = $categories;
    }

    ob_start();
    echo '<div class="category-breadcrumbs">';
    $category_count = 1;
    foreach ($category_array as $category) {
        echo '<a class="category-breadcrumbs__link" href="' . get_category_link($category->term_id) . '" 
        data-ev-loc="Body" data-ev-name="Category Link" data-ev-val="' . $category->name . '">' . $category->name . '</a>';
        if ($category_count != count($category_array)) {
            echo ' / ';
        }
        $category_count++;
    }
    echo '</div>';
    return ob_get_clean();
}

// wrap iframes with divs instead of p tags so we can target more easily in CSS
function fth_wrap_embed_with_div($html, $url, $attr)
{
    if (strpos($url, 'vimeo.com') !== false || strpos($url, 'youtube.com') !== false) {
        return '<div class="video-container fitvidsignore">' . $html . '</div>';
    } else {
        return $html;
    }
}

add_filter('embed_oembed_html', 'fth_wrap_embed_with_div', 10, 3);

function fth_dfp_prefix()
{
    if (constant('ENV') === 'prod') {
        return "/72233705/";
    } else {
        return "/72233705/z_";
    }
}


function fth_set_environment()
{
    if (!defined('ENV')) {
        if ($_SERVER['HTTP_HOST'] !== 'www.fatherly.com') {
            define('ENV', 'dev');
        } else {
            define('ENV', 'prod');
        }
    }
}

add_action('init', 'fth_set_environment');
function fth_remove_yoast_json_ld_markup($data)
{
    return array();
}

add_filter('wpseo_json_ld_output', 'fth_remove_yoast_json_ld_markup', 10, 1);

function fth_get_article_updated_date($post, $format = null)
{
    $updated = get_post_meta($post->ID, 'content_updated_date', true);
    /*check if the current updated datetime is the same as the published datetime or if the updated datetime
     * precedes the publish datetime plus 1 minute.
     */
    if (isset($updated) && strtotime($updated) <= (strtotime($post->post_date) + 60)) {
        //If this is the case then the updated date needs to be deleted.
        delete_post_meta($post->ID, 'content_updated_date');
        $updated = null;
    } else {
        if ($format) {
            $updated = date($format, strtotime($updated));
        } else {
            $updated = date('M d Y, g:i A', strtotime($updated));
        }
    }
    return $updated;
}


function fth_modify_post_count_on_sitemap($query)
{
    if ($query->query_vars['year'] > 2008 && is_archive() && is_date()) {
        $query->query_vars['posts_per_page'] = -1;
    }
    return $query;
}

add_action('pre_get_posts', 'fth_modify_post_count_on_sitemap');


function fth_remove_srcset_on_feed_articles()
{
    if (is_feed()) {
        remove_filter('the_content', 'wp_make_content_images_responsive');
    }
}

add_action('pre_get_posts', 'fth_remove_srcset_on_feed_articles', 1);

function fth_outbrain($postID, $isFirstPost)
{
    $fields = get_fields($postID);
    if (!$fields['sponsored']) {
        if ($isFirstPost) {
            return printf('<div class="OUTBRAIN" data-src="' . get_permalink($postID) . '" data-widget-id="GS_2"></div>');
        } else {
            return printf('<div class="OUTBRAIN" data-src="' . get_permalink($postID) . '" data-widget-id="AR_2"></div>');
        }
    }
}

add_action('pre_get_posts', 'fth_remove_srcset_on_feed_articles', 1);

function fth_add_featured_image_post_in_feeds($content)
{
    if (is_feed()) {
        $img = fth_img(array('width' => 600));
        $content = sprintf("<img src='%s'/>", $img) . $content;
    }
    return $content;
}

add_filter('the_content', 'fth_add_featured_image_post_in_feeds');

function fth_register_admin_menu_item_homepage()
{
    add_menu_page(
        __('Fatherly Homepage', 'fth'),
        'Layouts',
        'edit_posts',
        'fthhome',
        'fth_homepage_admin_page',
        'data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMzZweCIgaGVpZ2h0PSI0NnB4IiB2aWV3Qm94PSIwIDAgMzYgNzUiDQogICAgICAgIHZlcnNpb249IjEuMSIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIg0KICAgICAgICB4bWxuczp4bGluaz0iaHR0cDovL3d3dy53My5vcmcvMTk5OS94bGluayI+DQogICAgPCEtLSBHZW5lcmF0b3I6IFNrZXRjaCA0Ni4xICg0NDQ2MykgLSBodHRwOi8vd3d3LmJvaGVtaWFuY29kaW5nLmNvbS9za2V0Y2ggLS0+DQogICAgPHRpdGxlPkZhdGhlcmx5IExvZ28gTG9ja3VwPC90aXRsZT4NCiAgICA8ZGVzYz5DcmVhdGVkIHdpdGggU2tldGNoLjwvZGVzYz4NCiAgICA8ZGVmcz4NCiAgICAgICAgPHBvbHlnb24gaWQ9InBhdGgtMSINCiAgICAgICAgICAgICAgICAgcG9pbnRzPSIwLjAwMDIzNDk2NTAzNSA3NC42MDkzNzUgMzUuNzY5MzUzOCA3NC42MDkzNzUgMzUuNzY5MzUzOCAwLjEwNjkxNDA2MiAwLjAwMDIzNDk2NTAzNSAwLjEwNjkxNDA2MiI+PC9wb2x5Z29uPg0KICAgIDwvZGVmcz4NCiAgICA8ZyBpZD0iUGFnZS0xIiBzdHJva2U9Im5vbmUiIHN0cm9rZS13aWR0aD0iMSINCiAgICAgICBmaWxsPSJub25lIiBmaWxsLXJ1bGU9ImV2ZW5vZGQiPg0KICAgICAgICA8ZyBpZD0iTW9iaWxlLU5hdiINCiAgICAgICAgICAgdHJhbnNmb3JtPSJ0cmFuc2xhdGUoLTI5LjAwMDAwMCwgLTM3LjAwMDAwMCkiPg0KICAgICAgICAgICAgPGcgaWQ9Ik5BViI+DQogICAgICAgICAgICAgICAgPGcgaWQ9IkZhdGhlcmx5LUxvZ28tTG9ja3VwIg0KICAgICAgICAgICAgICAgICAgIHRyYW5zZm9ybT0idHJhbnNsYXRlKDI5LjAwMDAwMCwgMzcuMDAwMDAwKSI+DQogICAgICAgICAgICAgICAgICAgIDxwb2x5Z29uIGlkPSJGaWxsLTEiIGZpbGw9IiNEQzQyMjgiDQogICAgICAgICAgICAgICAgICAgICAgICAgICAgIHBvaW50cz0iNS4wNTA1NzM0MyA0My40MTEzMjgxIDEyLjUwMDUzMTUgNDMuNDExMzI4MSAxMi41MDA1MzE1IDQwLjMzNjcxODcgNS4wNTA1NzM0MyA0MC4zMzY3MTg3Ij48L3BvbHlnb24+DQogICAgICAgICAgICAgICAgICAgIDxwb2x5Z29uIGlkPSJGaWxsLTIiIGZpbGw9IiNEQzQwMjgiDQogICAgICAgICAgICAgICAgICAgICAgICAgICAgIHBvaW50cz0iNS4wNTA1NzM0MyAyOS4wNzQyMTg3IDEyLjUwMDUzMTUgMjkuMDc0MjE4NyAxMi41MDA1MzE1IDI1Ljk5OTYwOTQgNS4wNTA1NzM0MyAyNS45OTk2MDk0Ij48L3BvbHlnb24+DQoNCiAgICAgICAgICAgICAgICAgICAgPGcgaWQ9Ikdyb3VwLTIxIj4NCiAgICAgICAgICAgICAgICAgICAgICAgIDxtYXNrIGlkPSJtYXNrLTIiIGZpbGw9IndoaXRlIj4NCiAgICAgICAgICAgICAgICAgICAgICAgICAgICA8dXNlIHhsaW5rOmhyZWY9IiNwYXRoLTEiPjwvdXNlPg0KICAgICAgICAgICAgICAgICAgICAgICAgPC9tYXNrPg0KICAgICAgICAgICAgICAgICAgICAgICAgPGcgaWQ9IkNsaXAtMjAiPjwvZz4NCiAgICAgICAgICAgICAgICAgICAgICAgIDxwYXRoIGQ9Ik0zNS43NjkzNTM4LDEzLjgyOTE3OTcgTDM0LjYwNzg0MzQsMTMuNTMyMzA0NyBDMjIuNzMxOTI3MywxMC41MjgwMDc4IDIwLjM4OTcxNzUsMS4zNzk5NjA5NCAyMC4zNjczOTU4LDEuMjg4NTU0NjkgTDIwLjA4MzQ3OTcsMC4xMDY5MTQwNjIgTDE4Ljg2OTQ5MzcsMC4xMDY5MTQwNjIgQzguNDY0ODUwMzUsMC4xMDY5MTQwNjIgMC4wMDAyMzQ5NjUwMzUsOC41NDk0OTIxOSAwLjAwMDIzNDk2NTAzNSwxOC45MjgwMDc4IEwwLjAwMDIzNDk2NTAzNSwyMC4wMDQ1NzAzIEwzLjA4MzM2NzgzLDIwLjAwNDU3MDMgTDMuMDgzMzY3ODMsMTguOTI4MDA3OCBDMy4wODMzNjc4MywxMC42MjkxNzk3IDkuNDk0NzgwNDIsMy43OTk4ODI4MSAxNy43NDYzNjA4LDMuMjIxMzY3MTkgQzE4LjcxNzU0OTcsNS44OTQ4MDQ2OSAyMi4yMzc3MTc1LDEzLjA3MzMyMDMgMzIuNjg3MDA0MiwxNi4xOTI4NTE2IEwzMi42ODcwMDQyLDE3LjA2ODI0MjIgTDEyLjE1NTc1OTQsMTcuMDQ3NTM5MSBMMTAuNjI3NzAzNSwxNy4xNTE4MzU5IEwxMC42NTkwMzIyLDE5LjMzNzM4MjggTDExLjE0Mzg0MzQsMTkuNzgzODY3MiBDMTUuNjk3ODU3MywyMy45ODgxNjQxIDE3LjkzMzk0MTMsMjguNzQyODUxNiAxOC45OTUyLDMyLjg4MDc0MjIgTDEuNDk1MDA0MiwzMi44ODA3NDIyIEwxLjQ5NTAwNDIsMzUuOTU2NTIzNCBMMTkuNjA3MjgzOSwzNS45NTY1MjM0IEMyMC4xODk5OTcyLDQwLjA5NDQxNDEgMTkuNjg3NTYzNiw0My4xMDE4MzU5IDE5LjYzNzgyOTQsNDMuMzg2NjAxNiBDMTkuNjM0MzA0OSw0My40MjkxNzk3IDE5LjI2ODkzNDMsNDcuMzI4MDA3OCAxNy41ODAzMTg5LDUwLjYzNDI1NzggQzE2LjQ0MzQ3OTcsNDkuODc1MjczNCAxNS4wNDE5MTMzLDQ5LjEzMDc0MjIgMTMuNzgxMzI1OSw0OC4zODg5NDUzIEMxMy4xMjkyOTc5LDQ4LjAwNDk2MDkgMTIuNTg2OTIwMyw0Ny42MjIxNDg0IDEyLjA2Njg2NDMsNDcuMjM4MTY0MSBMNy44ODU2NjE1NCw0Ny4yMzgxNjQxIEM5LjQzOTU2MzY0LDQ5Ljc0MjQ2MDkgMTMuNTAyODkyMyw1MS42MTgyNDIyIDE2LjQ1MjA5NTEsNTMuNTc5MTc5NyBDMjAuNTYwNDU4Nyw1Ni4zMTIzODI4IDI0LjgwOTAxODIsNTkuMTM4OTQ1MyAyNC44MDkwMTgyLDYzLjY5Mjg1MTYgQzI0LjgwOTAxODIsNjkuNDc1NjY0MSAyMC43NDc2NDc2LDcxLjUzNTgyMDMgMTYuOTQ2Njk2NSw3MS41MzU4MjAzIEMxMy42NDY2MTI2LDcxLjUzNTgyMDMgMTAuOTYyMTM3MSw2OC44NTg4NjcyIDEwLjk2MjEzNzEsNjUuNTY5NDE0MSBDMTAuOTYyMTM3MSw2My4xMDczMDQ3IDEyLjk3MjI2MjksNjEuMTA0NTcwMyAxNS40NDIxMzcxLDYxLjEwNDU3MDMgQzE3LjI0NTEwMjEsNjEuMTA0NTcwMyAxOC43MTI0NTg3LDYyLjU2NzA3MDMgMTguNzEyNDU4Nyw2NC4zNjQzMzU5IEwxOC43MTI0NTg3LDY0Ljg2MjM4MjggTDIxLjc5NTk4MzIsNjQuODYyMzgyOCBMMjEuNzk1OTgzMiw2NC4zNjQzMzU5IEMyMS43OTU5ODMyLDYwLjg3MTM2NzIgMTguOTQ1ODU3Myw1OC4wMjkxNzk3IDE1LjQ0MjEzNzEsNTguMDI5MTc5NyBDMTEuMjcyMjkwOSw1OC4wMjkxNzk3IDcuODc5Nzg3NDEsNjEuNDEyMzgyOCA3Ljg3OTc4NzQxLDY1LjU2OTQxNDEgQzcuODc5Nzg3NDEsNzAuNTU0NTcwMyAxMS45NDcwMzIyLDc0LjYwOTY0ODQgMTYuOTQ2Njk2NSw3NC42MDk2NDg0IEMyMy41OTUwMzIyLDc0LjYwOTY0ODQgMjcuODkxMzY3OCw3MC4zMjQ4ODI4IDI3Ljg5MTM2NzgsNjMuNjkyODUxNiBDMjcuODkxMzY3OCw1OC4zODU0Mjk3IDI0LjEwMzMzOTksNTUuMTA0NTcwMyAyMC4xNTA0NDQ4LDUyLjM2NDcyNjYgQzIyLjI4MTU3NzYsNDguNDA4MDg1OSAyMi42ODk2MzM2LDQzLjgxMzk0NTMgMjIuNjg0MTUxLDQzLjgxMzk0NTMgQzIyLjczNzQwOTgsNDMuNTUwNjY0MSAyMy4zNDEyNjk5LDQwLjM5Mjg1MTYgMjIuNzk2NTQyNywzNS45NTY1MjM0IEwzMC4zNDg3MTA1LDM1Ljk1NjUyMzQgTDMwLjM0ODcxMDUsMzIuODgwNzQyMiBMMjIuMjU0MTY1LDMyLjg4MDc0MjIgQzIxLjM1MTExNjEsMjguOTQ2NzU3OCAxOS40NzgwNTMxLDI0LjQyMDk3NjYgMTUuNzU2MjA3LDIwLjEyMzMyMDMgTDM1Ljc2OTM1MzgsMjAuMTQ1NTg1OSBMMzUuNzY5MzUzOCwxMy44MjkxNzk3IFoiDQogICAgICAgICAgICAgICAgICAgICAgICAgICAgICBpZD0iRmlsbC0xOSINCiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIGZpbGw9IiNEQzQwMjgiDQogICAgICAgICAgICAgICAgICAgICAgICAgICAgICBtYXNrPSJ1cmwoI21hc2stMikiPjwvcGF0aD4NCiAgICAgICAgICAgICAgICAgICAgPC9nPg0KICAgICAgICAgICAgICAgIDwvZz4NCiAgICAgICAgICAgIDwvZz4NCiAgICAgICAgPC9nPg0KICAgIDwvZz4NCjwvc3ZnPg==',
        9999
    );
}

add_action('admin_menu', 'fth_register_admin_menu_item_homepage');
function fth_homepage_admin_page()
{
    esc_html_e('Fatherly Homepage', 'fth');
}

function fth_remove_yoast_meta_box_on_custom_post_types()
{
    remove_meta_box('wpseo_meta', 'hp_module', 'normal');
    remove_meta_box('wpseo_meta', 'hp_collection', 'normal');
    remove_meta_box('tagsdiv-stages', 'post', 'side');
    remove_meta_box('wpseo_meta', 'rule_groups', 'normal');
}

add_action('add_meta_boxes', 'fth_remove_yoast_meta_box_on_custom_post_types', 100);

// puts the template part in an output buffer to control php output.

function fth_get_template_part($template)
{
    ob_start();
    get_template_part($template);

    return ob_get_clean();
}

add_filter('better_internal_link_search_results', 'fth_remove_prominent_words_on_search', 99, 1);
function fth_remove_prominent_words_on_search($results)
{
    foreach ($results as $i => $result) {
        if ($result['info'] === 'Prominent word') {
            unset($results[$i]);
        }
    }
    return $results;
}

add_action('wp_ajax_fth_migrate_posts', 'fth_migrate_tag_to_cat');

function fth_migrate_tag_to_cat()
{
    $return = Fatherly\Migration\Stages::init()->migrateStagesTerm($_POST['term']);
    echo json_encode($return);
    wp_die();
}

add_action('wp_ajax_fth_migrate_legacy_stages_relationships', 'fth_migrate_legacy_stages_relationships');
function fth_migrate_legacy_stages_relationships()
{
    $return = \Fatherly\Migration\Stages::init()->migrateLegacyStagesRelationships();
    echo wp_json_encode($return);
    wp_die();
}


add_filter('body_class', function ($classes) {
    if (\Fatherly\Page\Helper::isHomepageTemplate()) {
        return array_merge($classes, array('has-collection'));
    } else {
        return $classes;
    }
});
function categoryWrap($category)
{
    $categories = explode(' ', $category, 2);
    if (strlen($categories[0]) > 14) :
        $length = strlen($categories[0]) > 17 ? 14 : strlen($categories[0]) - 3;
        echo substr($categories[0], 0, $length) . '<br> -' . substr($categories[0], $length);
        echo ' ' . $categories[1];
    else :
        echo $category;
    endif;
}

/**
 * Implementation of `strpos()` with the added ability to pass an array of needles to check against.
 * @param $haystack
 * @param $needle
 * @param int $offset
 * @return bool
 */
function strposa($haystack, $needle, $offset = 0)
{
    if (!is_array($needle)) {
        $needle = array($needle);
    }
    foreach ($needle as $query) {
        if (strpos($haystack, $query, $offset) !== false) {
            return true;
        } // stop on first true result
    }
    return false;
}

/**
 * @param $tag
 * @return \Fatherly\Page\Collection|null
 */
function fth_get_collection_for_tag_page($tag)
{
    $context = array(
        'tag' => $tag
    );
    $tag_fields = get_fields($tag);
    if ($tag_collection = get_field('content_collection', $tag)) {
        //This means that this tag has a specific collection overriding the default.
        return new \Fatherly\Page\Collection($tag_collection->ID, $context);
    } elseif (!$tag_fields['is_franchise'] && $tag_collection = get_field('default_tag_collection', 'option')) {
        // This means we need to use the default tag collection
        return new \Fatherly\Page\Collection($tag_collection->ID, $context);
    } elseif ($tag_fields['is_franchise'] && $tag_collection = get_field(
        'default_franchise_tag_collection',
        'option'
    )) {
        // This means that this tag is a franchise and we have a default franchise tag collection.
        $context['franchise'] = true;
        $context['franchise_fields'] = $tag_fields;
        return new \Fatherly\Page\Collection($tag_collection->ID, $context);
    } else {
        //This means this tag has no collection set and also that there's no default tag collection.
        return null;
    }
}

add_action('do_meta_boxes', 'move_post_options');

function move_post_options()
{
    // Remove post meta boxes and add them back in the order we would like them to appear
    remove_meta_box('submitdiv', 'post', 'side');
    remove_meta_box('categorydiv', 'post', 'side');
    remove_meta_box('postimagediv', 'post', 'side');
    remove_meta_box('tagsdiv-post_tag', 'post', 'side');

    add_meta_box('submitdiv', __('Publish'), 'post_submit_meta_box', 'post', 'side', 'high');
    add_meta_box('categorydiv', __('Categories'), 'post_categories_meta_box', 'post', 'side', 'high');
    add_meta_box('postimagediv', __('Featured Image'), 'post_thumbnail_meta_box', 'post', 'side', 'high');
    add_meta_box('tagsdiv-post_tag', __('Tags'), 'post_tags_meta_box', 'post', 'side', 'default');
}


function fth_set_fastly_service_ids()
{
    if (defined('PURGELY_FASTLY_SERVICE_ID')) {
        if (($fastly_general_settings = get_option('fastly-settings-general'))) {
            if ($fastly_general_settings['fastly_service_id'] !== constant('PURGELY_FASTLY_SERVICE_ID')) {
                $fastly_general_settings['fastly_service_id'] = constant('PURGELY_FASTLY_SERVICE_ID');
                update_option('fastly-settings-general', $fastly_general_settings);
            }
        }
    }
}

add_action('admin_init', 'fth_set_fastly_service_ids');

function fth_move_yoast_box_to_bottom()
{
    return 'low';
}

add_filter('wpseo_metabox_prio', 'fth_move_yoast_box_to_bottom');

/**
 * fth_save_listice
 *
 * This function is fired on post save/update and if the post is a listicle then we need to perform a few actions.
 * Documentation for each of these actions can be found on the methods themselves.
 *
 * @param $post_id
 */
function fth_save_listice($post_id)
{

    $fields = get_fields($post_id);
    $listicle_post_ids = get_option('listicle_posts', array());
    if ($fields && $fields['is_listicle'] === true && $_GET['action'] !== 'trash') {
        \Fatherly\Listicle\Helper::handleListiclePostCRUD(get_post($post_id), $fields['list_items']);
    } else {
        if (in_array($post_id, $listicle_post_ids)) {
            \Fatherly\Listicle\Helper::removePostIDFromListiclePosts($post_id);
        }
    }
}

add_action('save_post', 'fth_save_listice', 100);

function remove_cookie()
{
    if (!is_user_logged_in() && $GLOBALS['pagenow'] !== 'wp-login.php' && isset($_COOKIE['wordpress_google_apps_login'])) {
        unset($_COOKIE['wordpress_google_apps_login']);
        setcookie('wordpress_google_apps_login', null, -1, '/');
    }
}

add_action('init', 'remove_cookie');

add_filter('acf/update_value/name=enable_weekly_update', 'fire_hook_for_amazon_cron_job_manager', 10, 4);
add_filter('acf/update_value/name=enable_daily_update', 'fire_hook_for_amazon_cron_job_manager', 10, 4);
function fire_hook_for_amazon_cron_job_manager($value, $post_id, $field, $old_value)
{
    $old_value = get_field($field['name'], 'option');
    if ($value !== $old_value) {
        $hook = sprintf('fatherly_apu_%s_setting_update', $field['name']);
        do_action($hook, $value);
    }
    return $value;
}
