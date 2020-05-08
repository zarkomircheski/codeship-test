<?php

function fth_add_admin_scripts($hook)
{
    global $post;

    if ($hook == 'post-new.php' || $hook == 'post.php') {
        wp_enqueue_script('fth_admin_yoast_additions', get_stylesheet_directory_uri().'/js/admin/yoast.js');
    }

    if ($hook == 'toplevel_page_pull-fatherly-iq') {
        wp_enqueue_script('fth_fatherly-iq-admin', get_stylesheet_directory_uri().'/js/admin/fatherly-iq-admin.js');
    }
}
add_action('admin_enqueue_scripts', 'fth_add_admin_scripts', 10, 1);
