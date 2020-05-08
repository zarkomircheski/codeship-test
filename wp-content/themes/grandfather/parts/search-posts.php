<div class="search-section">
    <?php parse_str(parse_url($_SERVER['REQUEST_URI'], PHP_URL_QUERY), $queryarg);  ?>
    <?php global $post;
    $count = 0;
    $search_query = new WP_Query(array( 's' => $queryarg['s'], 'posts_per_page' => '9', 'post_type' => 'post', 'post_status' => 'publish' ));
    if ($search_query->have_posts()) :
        while ($search_query->have_posts()) :
            $search_query->the_post(); ?>
            <?php get_template_part('parts/loop', 'search'); ?>

            <?php if ($count == 10 || $count == $wp_query->found_posts) : ?>
            <?php endif; ?>
        <?php endwhile; ?>
    <div class="more-from-load-more">
        Show More
    </div>
</div>
    <?php else : ?>
    <div class="has-collection">
        <?php
        $collection_404 = get_field('404_collection', 'option');
        $collection = new \Fatherly\Page\Collection($collection_404);
        set_query_var('collection', $collection);
        foreach ($collection->modules as $module) {
            set_query_var('module', $module);
            $module->render();
        }
        ?>
    </div>


    <?php endif; ?> 