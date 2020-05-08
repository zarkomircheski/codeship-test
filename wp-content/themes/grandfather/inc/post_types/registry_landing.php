<?php
// Registry Custom Post Type
function registry_landing()
{
    $labels = array(
        'name'                  => _x('Registry Landing', 'Post Type General Name', 'text_domain'),
        'singular_name'         => _x('Registry Landing', 'Post Type Singular Name', 'text_domain'),
        'menu_name'             => __('Registry Landing', 'text_domain'),
        'name_admin_bar'        => __('Registry Landing', 'text_domain'),
        'archives'              => __('Registry Landing Archives', 'text_domain'),
        'attributes'            => __('Registry Landing Attributes', 'text_domain'),
        'parent_item_colon'     => __('Parent Item:', 'text_domain'),
        'all_items'             => __('All Registry Landing', 'text_domain'),
        'add_new_item'          => __('Add New Registry Landing', 'text_domain'),
        'add_new'               => __('Add New', 'text_domain'),
        'new_item'              => __('New Item', 'text_domain'),
        'edit_item'             => __('Edit Item', 'text_domain'),
        'update_item'           => __('Update Item', 'text_domain'),
        'view_item'             => __('View Item', 'text_domain'),
        'view_items'            => __('View Items', 'text_domain'),
        'search_items'          => __('Search Item', 'text_domain'),
        'not_found'             => __('Not found', 'text_domain'),
        'not_found_in_trash'    => __('Not found in Trash', 'text_domain'),
        'featured_image'        => __('Featured Image', 'text_domain'),
        'set_featured_image'    => __('Set featured image', 'text_domain'),
        'remove_featured_image' => __('Remove featured image', 'text_domain'),
        'use_featured_image'    => __('Use as featured image', 'text_domain'),
        'insert_into_item'      => __('Insert into item', 'text_domain'),
        'uploaded_to_this_item' => __('Uploaded to this item', 'text_domain'),
        'items_list'            => __('Items list', 'text_domain'),
        'items_list_navigation' => __('Items list navigation', 'text_domain'),
        'filter_items_list'     => __('Filter items list', 'text_domain'),
    );
    $rewrite = array(
        'slug'=>'registry-landing'
    );
    $args = array(
        'label'                 => __('Registry Landing', 'text_domain'),
        'description'           => __('Post type for creating new Registry Landing pages', 'text_domain'),
        'labels'                => $labels,
        'supports'              => array( 'title', 'editor', 'thumbnail' ),
        'taxonomies'            => array( 'category', 'post_tag' ),
        'hierarchical'          => false,
        'public'                => true,
        'show_ui'               => true,
        'show_in_menu'          => true,
        'menu_position'         => 1000,
        'menu_icon'             => 'dashicons-format-aside',
        'show_in_admin_bar'     => true,
        'show_in_nav_menus'     => false,
        'can_export'            => true,
        'has_archive'           => false,
        'exclude_from_search'   => true,
        'publicly_queryable'    => true,
        'rewrite'               => $rewrite,
        'capability_type'       => 'page',
    );
    register_post_type('registry_landing', $args);
}
add_action('init', 'registry_landing', 0);
