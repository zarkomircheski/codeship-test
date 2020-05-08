<div class="search-section">
    <?php
    if ($args = get_query_var('args')) {
        if (isset($args['s']) && $args['s']) {
            $args['s'] = str_replace("\\\'", '\'', $args['s']);
        }
    }

        $latest_query = new WP_Query($args);

    if ($latest_query->have_posts()) :
        while ($latest_query->have_posts()) :
            $latest_query->the_post();
            $categories = get_the_category();
            $top_category = get_top_category($categories);
            ?>

            <?php get_template_part('parts/loop', 'search'); ?>
            <?php
        endwhile;
    endif;
    ?>
</div>
