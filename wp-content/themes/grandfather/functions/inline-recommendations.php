<?php

add_shortcode('sponsored_content', 'fatherly_sponsor');

function fatherly_sponsor($atts)
{
    // no longer used. Kept here to make sure references don't break.
    return $atts;
}


add_shortcode('related_content', 'fatherly_related');

function fatherly_related($atts, $content)
{
    global $post;
    $categories = get_the_category($post->ID);
    if ($categories) {
        $category_ids = array();
        foreach ($categories as $individual_category) {
            $category_ids[] = $individual_category->term_id;
        }

        $args = array(
        'category__in' => $category_ids,
        'post__not_in' => array($post->ID),
        'posts_per_page' => 15, // Number of related posts that will be shown.
        );
        $i = 0;
        $related = get_posts($args);
        if ($related) {
            shuffle($related);
        }

        foreach ($related as $post) {
            $i++;
            setup_postdata($post);
            $related_content = '<div class="recommended-post">
		<a href="' . get_the_permalink() . '" class="recommended-post__thumbnail thumbnail">' . get_the_post_thumbnail(
                $post->ID,
                "large-thumb"
            ) . '</a>
		<div class="recommended-post__post-block post-block">';
            $related_content .= '<div class="recommended-post__descriptor descriptor">RELATED</div><a href="' . get_the_permalink($post->ID) . '"><h4 class="recommended-post__post-title">' . get_the_title($post->ID) . '</h4></a></div></div>';
            if ($i == 1) {
                break;
            }
        }
        wp_reset_postdata();
    }
    return $related_content;
}
