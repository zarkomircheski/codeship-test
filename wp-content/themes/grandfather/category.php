<?php get_header();
$term = get_queried_object();
?>
<?php if ($collection = get_field('content_collection', $term)) : ?>
    <?php
    $collection = new \Fatherly\Page\Collection($collection->ID);
    set_query_var('collection', $collection);
    foreach ($collection->modules as $module) {
        set_query_var('module', $module);
        $module->render();
    }
else :
    if ($term->parent !== 0) :
        $context_info = array(
        'sub_category' => $term
        );
        $parentTerm = get_term($term->parent, 'category');
        $collection = get_field('content_collection', $parentTerm);
        $collection = new \Fatherly\Page\Collection($collection->ID, $context_info);
        set_query_var('collection', $collection);
        foreach ($collection->modules as $module) {
            set_query_var('module', $module);
            $module->render();
        }
    else :
        ?>

        <div class="page-content container-sm clearfix">

            <main role="main">

                <section class="category-latest">
                    <?php get_template_part('parts/category', 'posts'); ?>
                    <?php get_template_part('pagination'); ?>
                </section>

            </main>

        </div>
    <?php endif;
endif; ?>
<?php get_footer(); ?>
