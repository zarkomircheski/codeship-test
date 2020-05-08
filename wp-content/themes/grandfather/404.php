<?php get_header();
    $collection_404 = get_field('404_collection', 'option');
    $collection = new \Fatherly\Page\Collection($collection_404);
    set_query_var('collection', $collection);
?>

<div class="page-content container-sm clearfix has-collection">
    <main role="main">
        <!-- section -->
        <section>

            <!-- article -->
            <article id="post-404" class="page-404">

                <h1 class="page-404__title"><?php _e('Oh No!', 'html5blank'); ?></h1>
                <h2 class="page-404__subtitle">Sorry, We cant find the page you're looking for.</h2>
                <p>Instead you can check out some of our trending articles or head back to the homepage by clicking the
                    button below.</p>
                <a class="page-404__button" href="<?php bloginfo('url'); ?>">Go To Homepage</a>
            </article>
            <!-- /article -->

        </section>
        <!-- /section -->
    </main>
    <?php
    foreach ($collection->modules as $module) {
        set_query_var('module', $module);
        $module->render();
    }
    ?>
</div>

<?php // get_sidebar();?>

<?php get_footer(); ?>
