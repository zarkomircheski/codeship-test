<?php get_header();
    $tag = get_queried_object();
    $collection = fth_get_collection_for_tag_page($tag);
if ($collection) :
    set_query_var('collection', $collection);
    foreach ($collection->modules as $module) {
        set_query_var('module', $module);
        $module->render();
    }
else :
    ?>

        <div class="page-content container-sm clearfix">

            <main role="main">

                <section class="tag-latest">
                    <?php get_template_part('parts/category', 'posts'); ?>
                    <?php get_template_part('pagination'); ?>
                </section>

            </main>

        </div>
<?php endif; ?>
<?php get_footer(); ?>
