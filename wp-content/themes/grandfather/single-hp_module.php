<?php

    $collection = new \Fatherly\Page\Collection();
    $modulePreview = get_fields();
    set_query_var('collection', $collection);
    get_header();
    $modules = array_merge(
        array_slice($collection->modules, 0, 3, true),
        array_slice($collection->modules, -3, 3, true)
    );
    foreach ($modules as $key => $module) {
        if ($key === 3) {
            $module = \Fatherly\Page\Module::init()->setupModuleStandalone($modulePreview);
            set_query_var('module', $module);
            if (is_preview()) {
                $module->renderPreview();
            } else {
                $module->render();
            }
        } else {
            set_query_var('module', $module);
            $module->render();
        }
    }

    get_footer();
