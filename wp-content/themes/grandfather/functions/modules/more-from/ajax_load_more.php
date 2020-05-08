<?php


function fth_module_more_from_load_more()
{
    header_remove('cache-control');
    header_remove('expires');
    $params = $_GET['data'];
    $offset = $params['page_number'] * 16;
    $excludes = $params['excludes'];
    $excluded_cats = array(get_cat_ID('News'));
    if ($params['page_type'] === 'homepage') {
        $args = array(
            'post_type' => 'post',
            'post_status' => 'publish',
            'posts_per_page' => 16,
            'meta_key' => 'visibility_on_home_page',
            'meta_value' => 'show',
            'category__not_in' => $excluded_cats,
            'offset' => $offset,
            'post__not_in' => $excludes
        );
    } elseif ($params['page_type'] === 'search') {
        $offset = $params['page_number'] * 9;
        $args = array(
            'post_type' => 'post',
            'post_status' => 'publish',
            'posts_per_page' => 9,
            'offset' => $offset,
            's' => $params['s']
        );
    } elseif ($params['page_type'] === 'author') {
        $args = array(
            'author' => $params['extra']['author_id'],
            'post_type' => 'post',
            'post_status' => 'publish',
            'posts_per_page' => 16,
            'offset' => $offset,
            'post__not_in' => $excludes
        );
    } elseif ($params['page_type'] === 'stages_tax') {
        $args = array(
            'post_type' => 'post',
            'post_status' => 'publish',
            'posts_per_page' => 16,
            'category__not_in' => $excluded_cats,
            'offset' => $offset,
            'post__not_in' => $excludes,
            'tax_query' => array (
                array (
                    'taxonomy' => 'stages',
                    'field' => 'term_id',
                    'terms' =>  $params['term']['term_id'],
                    'include_children' => 'false'
                )
            )
        );
    } else {
        $args = array(
            'post_type' => 'post',
            'post_status' => 'publish',
            'posts_per_page' => 16,
            'offset' => $offset,
            'post__not_in' => $excludes
        );

        if (!in_array($params['term']['term_id'], $excluded_cats)) {
            $args['category__not_in'] = $excluded_cats;
        }

        if ($params['page_type'] !== 'custom') {
            $args[($params['page_type'] === 'category') ? 'cat' : 'tag_id'] = $params['term']['term_id'];
        } else {
            if ($params['tag__in']) {
                $args['tag__in'] = $params['tag__in'];
            }
        }
    }
    set_query_var('args', $args);
    if ($params['page_type'] === 'search') {
        get_template_part('parts/modules/more-from-search');
    } else {
        get_template_part('parts/modules/more-from');
    }
    exit;
}

add_action('wp_ajax_fth_module_more_from_load_more', 'fth_module_more_from_load_more');
add_action('wp_ajax_nopriv_fth_module_more_from_load_more', 'fth_module_more_from_load_more');
