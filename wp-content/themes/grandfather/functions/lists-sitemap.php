<?php
/**
 * fth_add_list_items_sitemap
 *
 * This function adds a new sitemap to our sitemap index for list items. This does not include the list post itself
 * since that is already located in the post sitemap. This is only for the individual list items.
 *
 * @return string
 */
function fth_add_list_items_sitemap()
{
    $appended_text = '';
    $last_modified = str_replace(' ', 'T', get_option('listicle_last_modified')) . '+00:00';
    $sitemap_url = get_site_url() . '/lists-sitemap.xml';


    $appended_text .= '<sitemap>' .
        '<loc>' . $sitemap_url . '</loc>' .
        '<lastmod>' . htmlspecialchars($last_modified) . '</lastmod>' .
        '</sitemap>';


    return $appended_text;
}

/**
 * fth_init_wpseo_do_sitemap_actions
 *
 * This function sets up the action necessary for the generation of our markup for the lists sitemap. This tell yoast
 * what function to call in order to generate that markup.
 */
function fth_init_wpseo_do_sitemap_actions()
{
    add_action('wpseo_do_sitemap_lists', 'fth_generate_lists_sitemap_markup');
}

/**
 * fth_generate_lists_sitemap_markup
 *
 * This is the method called by yoast to generate the markup for the lists sitemap. We load the option containing all
 * the post ids of listicle posts and then we loop through them and grab all of their published list items and then we
 * add these urls to the sitemap. The code below was written based off of the code here:
 * https://gist.github.com/mohandere/4286103ce313d0cd6549 It's not a straight copy paste since our use cases differ but
 * it gave me all the info i needed to be able to do what we need.
 */
function fth_generate_lists_sitemap_markup()
{
    global $wpdb;
    global $wp_query;
    global $wpseo_sitemaps;
    $listicle_posts = get_option('listicle_posts');
    $output = '';
    if ($listicle_posts) {
        $change_frequency = 'weekly';
        $priority = 1.0;
        foreach ($listicle_posts as $listicle_post) {
            $listicle_post = get_post($listicle_post);
            if ($listicle_post->post_status !== 'publish') {
                continue;
            }
            $listicle_post_published_list_items = \Fatherly\Listicle\Helper::getPublishedListItems($listicle_post);
            if (count($listicle_post_published_list_items) > 0) {
                foreach ($listicle_post_published_list_items as $list_item) {
                    $url = array();
                    $url['mod'] = $listicle_post->post_modified_gmt;
                    $url['loc'] = $list_item['item_url'];
                    $url['chf'] = $change_frequency;
                    $url['pri'] = $priority;
                    $output .= $wpseo_sitemaps->renderer->sitemap_url($url);
                }
            }
        }
    }

    if (empty($output)) {
        $wpseo_sitemaps->bad_sitemap = true;
        return;
    }
    //Build the full sitemap
    $sitemap = '<urlset xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" ';
    $sitemap .= 'xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9 http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd" ';
    $sitemap .= 'xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";
    $sitemap .= $output . '</urlset>';
    $wpseo_sitemaps->set_sitemap($sitemap);
}

add_filter('wpseo_sitemap_index', 'fth_add_list_items_sitemap', 10);
add_action('init', 'fth_init_wpseo_do_sitemap_actions');
