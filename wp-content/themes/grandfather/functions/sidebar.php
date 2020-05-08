<?php
// Register sidebars
function custom_register_sidebar()
{
    if (function_exists('register_sidebar')) {
        register_sidebar(array(
            'name' => 'Default Sidebar',
            'id' => 'default-post-sidebar',
            'before_widget' => '<div id="%1$s" class="widget %2$s">',
            'after_widget' => '</div>',
            'before_title' => '<h3>',
            'after_title' => '</h3>'
        ));
    }
}

add_action('widgets_init', 'custom_register_sidebar');
