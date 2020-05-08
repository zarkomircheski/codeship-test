<?php
    /*
     * Template Name: Landing Page
     */
    get_header();
if ($collection = get_field('content_collection')) : ?>
        <?php
        $collection = new \Fatherly\Page\Collection($collection->ID);
        set_query_var('collection', $collection);
        foreach ($collection->modules as $module) {
            set_query_var('module', $module);
            $module->render();
        }

        ?>

<?php else :
        $fields = get_fields();
        $fields['paged'] = '1';
        $posts = Fatherly\Page\Helper::fetchPostsForLandingPage($fields);
        $page_title = get_the_title();
        $GLOBALS['wp_query'] = $posts;
        set_query_var('title', $page_title);
    ?>

        <div class="page-content container-sm clearfix">

            <main role="main">

                <section class="category-latest">
                    <?php get_template_part('parts/landing', 'posts'); ?>
                    <?php get_template_part('pagination'); ?>
                </section>

            </main>

        </div>
<?php endif; ?>
<?php get_footer(); ?>
