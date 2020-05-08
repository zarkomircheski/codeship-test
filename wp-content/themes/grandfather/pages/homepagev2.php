<?php
/*
Template Name: Homepage V2
*/

// Collection information is needed in the header. All other code should be placed below get_header()
$collection = new \Fatherly\Page\Collection();
set_query_var('collection', $collection);
get_header();

foreach ($collection->modules as $module) {
    set_query_var('module', $module);
    $module->render();
}

 get_footer();
