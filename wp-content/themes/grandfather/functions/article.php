<?php
/**
 * Retrieves the article html that appears for an article when on infiinite scroll
 *
 * @param $post A post object
 *
 * @return string Returns html
 */
function fth_get_next_article_html($post)
{
    ob_start(); ?>

    <div class="latest-posts-infinite clear clearfix">
        <div id="main" class="main latest-posts-infinite__main">
            <?php

            // setting this so ads can see this as an article, not misc
            global $wp_query;
            $wp_query->is_single = true;

            setup_postdata($post);

            $share_url = (get_field('full_url', $post->ID) ? get_field('full_url', $post->ID) : get_permalink($post->ID));
            $data_categories = array_map(function ($catId) {
                $cat = get_category($catId);
                return ($cat) ? $cat->name : $catId;
            }, wp_get_post_categories($post->ID));

            set_query_var('data_categories', $data_categories);
            set_query_var('readmore', $readmore);
            set_query_var('add_classes', 'next-article');
            set_query_var('share_url', $share_url);
            set_query_var('rail_template', 'rail');
            set_query_var('first_article', false);

            get_template_part('parts/article', 'body'); ?>

            <img class="loading-gif" src="<?php echo fth_get_protocol_relative_template_directory_uri() ?>/images/preloader.gif">
        </div>
    </div>

    <?php
    $data = ob_get_clean();

    return $data;
}
