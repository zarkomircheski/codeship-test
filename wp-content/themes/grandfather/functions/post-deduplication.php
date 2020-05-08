<?php
/**
 * remove the posts from query_posts
 */
function bm_postStrip($where)
{
    global  $wpdb;
    $excludedPosts = \Fatherly\Page\Collection::$postIdsToExclude;
    if (count($excludedPosts) > 0) {
        $where .= ' AND ' . $wpdb->posts . '.ID NOT IN(' . implode(',', $excludedPosts) . ') ';
    }

    return $where;
}

add_filter('posts_where', 'bm_postStrip');
