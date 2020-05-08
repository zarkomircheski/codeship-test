<?php
if (\Fatherly\Listicle\Helper::isListicle($post)) {
    global $wp_query;
    if ($list_item = $wp_query->query_vars['list_item']) {
        $list_item_uuid = intval(substr($list_item, -3));
        $list_item_slugs = get_post_meta($post->ID, 'list_item_slugs', true);

        if (!array_key_exists($list_item_uuid, $list_item_slugs)) {
            // This will trigger if the list item slug uuid isn't present or if it doesn't match a published item.
            wp_redirect(get_permalink($post->ID));
            exit;
        } else {
            $slugs_numerical = array_values($list_item_slugs);
            $current_item_key = array_search($list_item, $slugs_numerical);
            if (array_key_exists(($current_item_key - 1), $slugs_numerical)) {
                set_query_var('listicle_previous', $slugs_numerical[($current_item_key - 1)]);
            }
            if (array_key_exists(($current_item_key + 1), $slugs_numerical)) {
                set_query_var('listicle_next', $slugs_numerical[($current_item_key + 1)]);
            }
            if ($list_item !== $list_item_slugs[$list_item_uuid]) {
                //This will trigger in the event that the UUID is valid but the slug doesn't completely match
                wp_redirect(get_permalink($post->ID) . "/$list_item_slugs[$list_item_uuid]");
                exit;
            }
        }
    }
}
    get_header();

if (have_posts()) {
    while (have_posts()) :
        the_post();
        $share_url = (get_field('full_url') ? get_field('full_url') : get_permalink());

        $data_categories = array_map(function ($catId) {
            $cat = get_category($catId);
            return ($cat) ? $cat->name : $catId;
        }, wp_get_post_categories($post->ID));
        $readField = get_field('read_more_enabled');
        if (!isset($readField) || get_field('read_more_enabled') === true) {
            $readmore = 1;
        } else {
            $readmore = 0;
        }

        set_query_var('data_categories', $data_categories);
        set_query_var('readmore', $readmore);
        set_query_var('add_classes', '');
        set_query_var('share_url', $share_url);
        set_query_var('rail_template', 'video-rail');
        set_query_var('first_article', true);
        set_query_var('next_post', get_next_post(true));
        get_template_part('parts/article', 'body');
        ?>


        <img class="loading-gif"
             src="<?php echo fth_get_protocol_relative_template_directory_uri() ?>/images/preloader.gif">

        <section class="home-latest single-infinitescroll clearfix">
            <div class="container-sm"></div>
        </section>

        <?php
    endwhile;
} else {
    ?>
    <article>
        <h1><?php _e('Sorry, nothing to display.', 'html5blank'); ?></h1>
    </article>
    <?php
}
    get_footer();
?>
