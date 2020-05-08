<?php get_header(); ?>
<?php $author = get_queried_object();

if ($collection = get_field('default_author_collection', 'option')) :
    $context_info = array(
        'author' => $author->data,
        'contextual_more_from' => true
    );
    $collection = new  \Fatherly\Page\Collection($collection->ID, $context_info);
    set_query_var('collection', $collection);
    foreach ($collection->modules as $module) {
        set_query_var('module', $module);
        $module->render();
    }
else :
    ?>
    <div class="page-content container-sm clearfix">

        <main role="main">

            <section class="archive-latest">
                <?php get_template_part('parts/category', 'posts'); ?>
                <?php get_template_part('pagination'); ?>
            </section>

        </main>

    </div>
<?php endif; ?>
<?php get_footer(); ?>
