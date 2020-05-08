<?php
if (!function_exists('homepage_collections')) {
// Register Custom Post Type
    function homepage_collections()
    {
        $labels = array(
            'name' => _x('Collections', 'Post Type General Name', 'fth'),
            'singular_name' => _x('Collection', 'Post Type Singular Name', 'fth'),
            'menu_name' => __('Collections', 'fth'),
            'name_admin_bar' => __('Collection', 'fth'),
            'archives' => __('Collection Archives', 'fth'),
            'attributes' => __('Collection Attributes', 'fth'),
            'parent_item_colon' => __('Parent Collection:', 'fth'),
            'all_items' => __('Collections', 'fth'),
            'add_new_item' => __('Add New Collection', 'fth'),
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
            'label' => __('Collection', 'fth'),
            'description' => __('Contains collections of modules for homepage', 'fth'),
            'labels' => $labels,
            'supports' => array('title'),
            'hierarchical' => false,
            'public' => true,
            'show_ui' => true,
            'show_in_menu' => 'fthhome',
            'menu_position' => 100,
            'menu_icon' => 'dashicons-list-view',
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
        register_post_type('hp_collection', $args);
    }

    add_action('init', 'homepage_collections', 0);
}
