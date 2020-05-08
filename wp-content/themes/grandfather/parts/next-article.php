<?php

// If no pinned post exits try load the next post using parsely recommendations
if ($_GET['tax'] == 'selected' && url_to_postid($_GET['url']) > 0) {
    $post = get_post(url_to_postid($_GET['url']));
    $parsely_html = fth_get_next_article_html($post);
    $excludes = $_GET['excludes'];
    array_push($excludes, $post->ID);
    $return_array = [
        'html' => $parsely_html,
        'url' => get_the_permalink($post->ID),
        'id' => $post->ID,
        'title' => get_the_title($post->ID),
        'categories' => $data_categories,
        'excludes'=> $excludes
    ];

    wp_reset_postdata($post);
    wp_send_json_success($return_array);
    exit();
}


// Find newest post in current category
$next_posts = new WP_Query([
    'post_type'      => 'post',
    'post_status'    => 'publish',
    'posts_per_page' => 1,
    'category_name'  => $_GET['term'],
    'offset'         => $_GET['page'] - count($pinned_posts),
    'post__not_in'   => $_GET['excludes']
]);

if (!$pinned_html && $next_posts->have_posts()) :
    $post =  get_post($next_posts->posts[0]);
    $excludes = $_GET['excludes'];
    array_push($excludes, $post->ID);
    $data = fth_get_next_article_html($post);

    $return_array = [
        'html'       => $data,
        'url'        => get_the_permalink($post->ID),
        'id'         => $post->ID,
        'title'      => get_the_title($post->ID),
        'categories' => $data_categories,
        'pinned' => get_field('field_infinite_pinned', 'options'),
        'excludes' => $excludes
    ];


    wp_reset_postdata($post);
    wp_send_json_success($return_array);
else :
    wp_send_json_success(array('html' => '<!-- No more content to load-->', 'end' => true));
endif;
