<?php
function set_fth_page_data($data)
{
    global $wp_query;
    $data['extra'] = array();
    $data['page_number'] = get_query_var('paged', 1);
    if (is_category() || is_tag() || is_tax('stages')) {
        $data['page_type'] = (is_category() ? 'category' : (is_tax('stages') ? 'stages_tax' : 'tag'));
        $data['term'] = get_queried_object();
    } elseif (is_author() && get_field('default_author_collection', 'option')) {
        $author = get_queried_object();
        $data['page_type'] = 'author';
        $data['extra']['author_id'] = $author->ID;
    } elseif (is_search() && $wp_query->have_posts()) {
        $data['page_type'] = 'search';
        $data['s'] = $_GET['s'];
    } else {
        $data['page_type'] = 'homepage';
    }
    return $data;
}

add_filter('fth_page_data', 'set_fth_page_data', 10, 1);
