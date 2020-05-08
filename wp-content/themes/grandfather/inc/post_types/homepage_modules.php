<?php
if (!function_exists('homepage_modules')) {
// Register Custom Post Type
    function homepage_modules()
    {
        $labels = array(
            'name' => _x('Modules', 'Post Type General Name', 'fth'),
            'singular_name' => _x('Module', 'Post Type Singular Name', 'fth'),
            'menu_name' => __('Modules', 'fth'),
            'name_admin_bar' => __('Module', 'fth'),
            'archives' => __('Module Archives', 'fth'),
            'attributes' => __('Module Attributes', 'fth'),
            'parent_item_colon' => __('Parent Module:', 'fth'),
            'all_items' => __('Modules', 'fth'),
            'add_new_item' => __('Add New Module', 'fth'),
            'add_new' => __('Add New', 'fth'),
            'new_item' => __('New Item', 'fth'),
            'edit_item' => __('Edit Item', 'fth'),
            'update_item' => __('Update Item', 'fth'),
            'view_item' => __('View Item', 'fth'),
            'view_items' => __('View Items', 'fth'),
            'search_items' => __('Search Item', 'fth'),
            'not_found' => __('Not found', 'fth'),
            'not_found_in_trash' => __('Not found in Trash', 'fth'),
            'featured_image' => __('Featured Image', 'fth'),
            'set_featured_image' => __('Set featured image', 'fth'),
            'remove_featured_image' => __('Remove featured image', 'fth'),
            'use_featured_image' => __('Use as featured image', 'fth'),
            'insert_into_item' => __('Insert into item', 'fth'),
            'uploaded_to_this_item' => __('Uploaded to this item', 'fth'),
            'items_list' => __('Items list', 'fth'),
            'items_list_navigation' => __('Items list navigation', 'fth'),
            'filter_items_list' => __('Filter items list', 'fth'),
        );
        $args = array(
            'label' => __('Module', 'fth'),
            'description' => __('Modules for display on homepage', 'fth'),
            'labels' => $labels,
            'supports' => array('title'),
            'hierarchical' => false,
            'public' => true,
            'show_ui' => true,
            'show_in_menu' => 'fthhome',
            'menu_position' => 100,
            'menu_icon' => 'dashicons-tagcloud',
            'show_in_admin_bar' => true,
            'show_in_nav_menus' => false,
            'can_export' => true,
            'has_archive' => false,
            'exclude_from_search' => true,
            'publicly_queryable' => true,
            'rewrite' => false,
            'capability_type' => 'page',
            'show_in_rest' => true,
        );
        register_post_type('hp_module', $args);
    }

    add_action('init', 'homepage_modules', 0);
}
