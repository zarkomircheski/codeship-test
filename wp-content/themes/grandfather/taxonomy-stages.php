<?php get_header();
    $term = get_queried_object();
?>
<?php if ($collection = get_field('content_collection', $term)) : ?>
    <?php
    $context_info = array(
        'stage' => $term
    );
    $collection = new \Fatherly\Page\Collection($collection->ID, $context_info);
    set_query_var('collection', $collection);
    foreach ($collection->modules as $module) {
        set_query_var('module', $module);
        $module->render();
    }
endif ?>


<?php get_footer();
