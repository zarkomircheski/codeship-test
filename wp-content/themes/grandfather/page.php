<?php get_header(); ?>
<?php
// Check to see if this is a fatherly at work page
$tags = get_the_tags();
$isFaw = false;
if (is_array($tags)) {
    foreach ($tags as $tag) {
        if ($tag->name === 'Fatherly At Work') {
            $isFaw = true;
            break;
        }
    }
}
?>
<?php if ($collection = get_field('content_collection')) : ?>
    <?php
    $collection = new \Fatherly\Page\Collection($collection->ID);
    set_query_var('collection', $collection);
    foreach ($collection->modules as $module) {
        set_query_var('module', $module);
        $module->render();
    }

    ?>

<?php else : ?>
    <div class="page-template-default page-content container-xsm clearfix">
        <main role="main" class="default-page">
            <!-- section -->
            <section class="page-template">

                <h1 class="page-template__title"><?php the_title(); ?></h1>

                <?php if (have_posts()) :
                    while (have_posts()) :
                        the_post(); ?>

                    <!-- article -->
                    <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

                        <?php the_content(); ?>


                    </article>
                    <!-- /article -->

                    <?php endwhile; ?>

                <?php else : ?>
                    <!-- article -->
                    <article>

                        <h2><?php _e('Sorry, nothing to display.', 'html5blank'); ?></h2>

                    </article>
                    <!-- /article -->

                <?php endif; ?>

            </section>
            <!-- /section -->
        </main>
    </div>

<?php endif; ?>

<?php get_footer(); ?>
