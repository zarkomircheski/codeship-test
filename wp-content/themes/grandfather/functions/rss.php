<?php

add_action('init', 'yesterdayRSS');
function yesterdayRSS()
{
    add_feed('yesterday', 'yesterdayRSSFunc');
}

function yesterdayRSSFunc()
{
    get_template_part('feeds/yesterday');
}

add_action('init', 'fth_newsletter_feed');
function fth_newsletter_feed()
{
    add_feed('newsletter-feed', 'fth_newsletter_feed_func');
}

function fth_newsletter_feed_func()
{
    get_template_part('feeds/newsletter');
}

add_action('init', 'fth_yahoo_finance_feed');
function fth_yahoo_finance_feed()
{
    add_feed('yahoo-finance-feed', 'fth_yahoo_finance_feed_func');
}

function fth_yahoo_finance_feed_func()
{
    get_template_part('feeds/yahoo-finance');
}

add_action('init', 'fth_customize_default_rss');
add_filter('the_content_feed', 'fth_related_articles_rss');
function fth_related_articles_rss($output)
{
    if (is_feed()) {
        $top_level_category = null;
        $categories = get_the_category();
        foreach ($categories as $category) {
            if ($category->category_parent === 0) {
                $top_level_category = $category;
                break;
            }
        }

        if ($top_level_category) {
            $top_level_category = (!$top_level_category) ? $categories[0] : $top_level_category;
            $related_articles = new WP_Query([
                'no_found_rows' => true,
                'posts_per_page' => 4,
                'category' => $top_level_category->term_id,
                'exclude' => [get_the_ID()]
            ]);
            if ($related_articles->have_posts()) {
                $output .= "<h4>Related Articles:</h4><ul>";

                foreach ($related_articles->posts as $related) {
                    $output .= '<li><a href="' . get_permalink($related->ID) . '">' . $related->post_title . '</a></li>';
                }
                $output .= '</ul>';
            }
        }
    }
    return $output;
}

/**
 * This sets up a custom template file for our default RSS 2.0 feeds.
 */
function fth_custom_rss($for_comments)
{
    get_template_part('feeds/rss2');
}

function fth_customize_default_rss()
{
    remove_action('do_feed_rss2', 'do_feed_rss2');
    add_action('do_feed_rss2', 'fth_custom_rss');
}

/**
 * fth_return_404_on_comment_and_search_feeds
 * This function will check if the current page being loaded is a comment feed or a search feed and if it is it will
 * set the status to 404, return our 404 template and then stop execution.
 */
function fth_return_404_on_comment_and_search_feeds()
{
    global $wp_query;
    if (is_feed() && ($wp_query->is_comment_feed || is_search())) {
        $wp_query->set_404();
        status_header(404);
        include(get_query_template('404'));
        die();
    }
}

/**
 * fth_modify_content_type_header_on_feed_404
 *
 * WP sets up the headers for the page request before it fires any hooks to setup the main query. This means that by
 * the time we check for the feed type and set the 404 the headers have already been set which for a feed means the
 * content type is set to rss/xml meaning the html for our 404 is output in plaintext to the page. This function checks
 * the query_vars that WP extracts from the URL to determine if we're loading a feed on a single article or on a search
 * page and if so it will modify the content type header and set it to html.
 *
 * @param $headers
 * @return mixed
 */
function fth_modify_content_type_header_on_feed_404($headers)
{
    global $wp;
    if (array_key_exists('feed', $wp->query_vars) && (array_key_exists('name', $wp->query_vars) || array_key_exists('s', $wp->query_vars) || array_key_exists('pagename', $wp->query_vars))) {
        $headers['Content-Type'] = "text/html; charset=UTF-8";
    }
    return $headers;
}

add_filter('wp_headers', 'fth_modify_content_type_header_on_feed_404');
add_action('wp', 'fth_return_404_on_comment_and_search_feeds');

/**
 * fth_get_rss_excerpt_for_post
 * This will check to see if an Opengraph description for a post exists and return it if so. If no OG description is
 * set then it return the output of `the_excerpt_rss()`
 * @param $postId
 * @return string
 */
function fth_get_rss_excerpt_for_post($postId)
{
    $postOpenGraphDescription = get_post_meta($postId, '_yoast_wpseo_opengraph-description', true);
    return (!empty($postOpenGraphDescription)) ? $postOpenGraphDescription : the_excerpt_rss();
}

/**
 * fth_get_newsletter_rss_title_for_post
 * This will check to see if an Opengraph title for a post exists and return it if so. If no OG title is
 * set then it return the output of `the_title_rss()`
 * @param $postId
 * @return string
 */
function fth_get_newsletter_rss_title_for_post($postId)
{
    $postOpenGraphTitle = get_post_meta($postId, '_yoast_wpseo_opengraph-title', true);
    return (!empty($postOpenGraphTitle)) ? $postOpenGraphTitle : the_title_rss();
}

/**
 * fth_rss_categories
 * This function will return in RSS format the parent category for a post.
 * @param $postId
 * @return string
 */
function fth_rss_categories($postId)
{
    //Snippets of code taken from `get_the_category_rss()` inside of `wp-includes\feed.php`
    $categories = get_the_category($postId);
    $the_list = '';
    $cat_names = array();
    if (!empty($categories)) {
        foreach ((array)$categories as $category) {
            if ($category->parent === 0) {
                $cat_names[] = sanitize_term_field('name', $category->name, $category->term_id, 'category', 'rss');
            }
        }
    }
    foreach ($cat_names as $cat_name) {
        $the_list .= "\t\t<category><![CDATA[" . @html_entity_decode(
            $cat_name,
            ENT_COMPAT,
            get_option('blog_charset')
        ) . "]]></category>\n";
    }
    return $the_list;
}


add_action('init', 'fth_setup_stages_feeds');
function fth_setup_stages_feeds()
{
    $stages = get_terms(array('taxonomy' => 'stages', 'hide_empty' => false));
    foreach ($stages as $stage) {
        $feed_name = $stage->slug . '/feed';
        add_feed($feed_name, 'fth_process_stages_feed');
    }
}

function fth_process_stages_feed()
{
    $stage = str_replace(array('/', 'feed'), '', $_SERVER['REQUEST_URI']);
    set_query_var('stage', $stage);
    get_template_part('feeds/stages-rss2');
}
