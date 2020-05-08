<?php

function get_page_type()
{
    if (is_home() || \Fatherly\Page\Helper::isHomepageTemplate()) {
        return 'home';
    } elseif (is_single()) {
        return 'article';
    } elseif (is_category()) {
        return 'category';
    } elseif (is_tag()) {
        return 'tag';
    } elseif (is_page('your-profile')) {
        return 'profile';
    } elseif (is_page('register')) {
        return 'register';
    }
}

function get_post_cat()
{
    if (is_single()) {
        $cats        = get_the_category(); // category object
        $top_cat_obj = [];
        $bot_cat_obj = [];

        foreach ($cats as $cat) {
            if ($cat->parent == 0) {
                $top_cat_obj[] = $cat;
            } else {
                $bot_cat_obj[] = $cat;
            }
        }
        $top_cat_obj = $top_cat_obj[0]->name;
        $top_cat_obj = normalize_cat_name($top_cat_obj);

        $bot_cat_obj       = $bot_cat_obj[0]->name;
        $bot_cat_obj       = normalize_cat_name($bot_cat_obj);
        $concat_cat_string = ($bot_cat_obj != '') ? $top_cat_obj . '--' . $bot_cat_obj . ' ' : '';

        return $top_cat_obj . ' ' . $concat_cat_string;
    } else {
        return '';
    }
}

function get_post_cat_by_id($postId)
{
    $cats        = get_the_category($postId); // category object
    $top_cat_obj = [];
    $bot_cat_obj = [];

    foreach ($cats as $cat) {
        if ($cat->parent == 0) {
            $top_cat_obj[] = $cat;
        } else {
            $bot_cat_obj[] = $cat;
        }
    }
    $top_cat_obj = $top_cat_obj[0]->name;
    $top_cat_obj = normalize_cat_name($top_cat_obj);

    $bot_cat_obj       = $bot_cat_obj[0]->name;
    $bot_cat_obj       = normalize_cat_name($bot_cat_obj);
    $concat_cat_string = ($bot_cat_obj != '') ? $top_cat_obj . '--' . $bot_cat_obj . ' ' : '';

    return $top_cat_obj . ' ' . $concat_cat_string;
}

function get_post_tags()
{
    if (is_single()) {
        $post_tags = get_the_tags();
        $tag_names = '';
        if (! empty($post_tags)) {
            foreach ($post_tags as $tag) {
                $tag_names .= normalize_cat_name($tag->name) . ' ';
            }

            return $tag_names;
        } else {
            return '';
        }
    } else {
        return '';
    }
}

function get_post_tags_by_id($postID)
{
    $post_tags = get_the_tags($postID);
    $tag_names = '';
    if (! empty($post_tags)) {
        foreach ($post_tags as $tag) {
            $tag_names .= normalize_cat_name($tag->name) . ' ';
        }

        return $tag_names;
    } else {
        return '';
    }
}

function get_post_author()
{
    if (is_single()) {
        global $post;
        $post_author = get_the_author_meta(
            'display_name',
            $post->post_author
        );
        $post_author = normalize_cat_name($post_author);

        return $post_author;
    } else {
        return '';
    }
}

function get_post_pub_time()
{
    if (is_single()) {
        global $post;

        return get_the_time('m-d-Y', $post->ID);
    }
}

function get_post_mod_time()
{
    if (is_single()) {
        global $post;

        return the_modified_date('m-d-Y', '', '', false);
    }
}

function normalize_cat_name($obj_string)
{
    $cat_name = strtolower($obj_string);
    $cat_name = str_replace(' ', '-', $cat_name);
    $cat_name = str_replace('\'', '', $cat_name);
    $cat_name = str_replace('&amp;', 'and', $cat_name);

    return $cat_name;
}
