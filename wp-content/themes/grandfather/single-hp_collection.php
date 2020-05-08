<?php get_header();
    $collection = new \Fatherly\Page\Collection(get_the_ID());
    set_query_var('collection', $collection);
foreach ($collection->modules as $module) {
    set_query_var('module', $module);
    $module->render();
}
    get_footer();
