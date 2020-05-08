<?php

function set_ga_tags_on_article($post)
{
    $fields = get_fields();
    $ga_data = new stdClass();
    $terms = wp_get_post_terms($post->ID, 'fields');
    $cats = get_the_category($post->ID);
    $stages = wp_get_post_terms($post->ID, 'stages');
    $this_term = ($terms && is_array($terms) && isset($terms[0])) ? $terms[0] : '';
    $term_slug = (isset($this_term->slug)) ? $this_term->slug : 'none';
    $ga_data->dimension6 = get_the_date('m-d-Y', $post->ID);
    $ga_data->dimension7 = $term_slug;
    $ga_data->dimension9 = str_replace(" ", ",", trim(get_post_cat_by_id($post->ID)));
    $ga_data->dimension10 = normalize_cat_name(get_the_author_meta(
        'display_name',
        $post->post_author
    ));
    $ga_data->dimension11 = get_the_time('m-d-Y', $post->ID);
    $ga_data->dimension12 = get_the_modified_date('m-d-Y', $post->ID);
    $ga_data->dimension13 = str_replace(" ", ",", trim(get_post_tags_by_id($post->ID)));
    ($fields['sponsored'] == true ? $ga_data->dimension14 = 'sponsored' : $ga_data->dimension14 = null);
    foreach ($cats as $cat) {
        if ($cat->parent === 0) {
            $ga_data->dimension15 = $cat->slug;
        } else {
            $ga_data->dimension16 .= $cat->slug . ', ';
        }
    }
    if (count($stages) > 0) {
        foreach ($stages as $stage) {
            $ga_data->dimension17 .= $stage->slug . ', ';
        }
    } else {
        $ga_data->dimension17 = '';
    }
    if ($fields['ga_tags']) {
        $ga_data->dimension18 = $fields['ga_tags'];
    }

    echo wp_json_encode($ga_data);
}

/*
 * Sets up dimension data that is global and not tied to a specific article.
 */
function set_ga_tags_on_page($post)
{
    $analytics_data = new stdClass();
    $analytics_data->dimension8 = get_page_type();
    $analytics_data->page_type = get_page_type();
    echo wp_json_encode($analytics_data);
}
