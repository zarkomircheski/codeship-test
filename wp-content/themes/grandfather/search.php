<?php
    $post_type = (isset($_GET['post_type']) ? $_GET['post_type'] : '');

if (isset($post_type) && locate_template('search-' . $post_type . '.php')) {
    // if so, load that template
    get_template_part('search', $post_type);

    // and then exit out
    exit;
} else {
    ?>
    <?php get_header(); ?>

        <div class="page-content search-page clearfix">
        <?php get_search_form(); ?>
            <main role="main">
                <h3 class="latest-posts__title category-posts__title--archive">
                <?php echo sprintf(__(
                    '<span class="latest-posts__search-result-count">%s Search Results for </span>',
                    'html5blank'
                ), $wp_query->found_posts);
                    echo get_search_query(); ?>
                </h3>
                <?php get_template_part('parts/search', 'posts'); ?>

            </main>

        </div>
        <?php get_footer(); ?>

        <?php
}
?>


