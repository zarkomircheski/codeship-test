<?php

// redirect rules for 404 pages
function check_404()
{
    if (is_404()) {
        // use tags as search query
        $tag = get_query_var('tag');
        if (!empty($tag)) {
            $tag = urldecode($tag);
            $clean_string = trim(str_replace('  ', ' ', preg_replace("/[^A-Za-z0-9'\s]/", " ", $tag)));
            $encoded_string = urlencode($clean_string);
            
            $redirect = '/?s='.$encoded_string;

            wp_redirect(home_url($redirect), 301);
            exit;
        }

        // remove page number and use page-less path
        $page = get_query_var('page');
        if (!empty($page)) {
            global $wp;

            $without_page = substr($wp->request, 0, strpos($wp->request, '/page/'));
            wp_redirect(home_url($without_page), 301);
            exit;
        }
    }
}
add_action('template_redirect', 'check_404');


function redirect_bare_subcat_urls_to_url_with_parent()
{
    // We only want to trigger this on category archive pages
    if (is_category() && !is_feed()) {
        global $wp;
        $category = get_queried_object(); //The current category.
        $category_url = get_category_link($category); //The correct canonical url for the current category
        $request_url = home_url($wp->request . '/'); // The full url for the current request with a trailing slash

        /**
         * We check to see if the request url matches the canonical url for the category if it doesn't then we 301
         * redirect the user to the canonical url and then `exit;`
         **/
        if ($request_url !== $category_url) {
            wp_safe_redirect($category_url, 301);
            exit;
        }
    }
}

add_action('template_redirect', 'redirect_bare_subcat_urls_to_url_with_parent');
