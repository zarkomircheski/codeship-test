<?php
// Register Custom Post Type
function fth_rule_groups_post_type()
{
    $labels = array(
        'name'                  => _x('Rule Groups', 'Post Type General Name', 'fth'),
        'singular_name'         => _x('Rule Group', 'Post Type Singular Name', 'fth'),
        'menu_name'             => __('Rule Groups', 'fth'),
        'name_admin_bar'        => __('Rule Groups', 'fth'),
        'archives'              => __('Rule Group Archives', 'fth'),
        'attributes'            => __('Rule Group Attributes', 'fth'),
        'parent_item_colon'     => __('Parent Item:', 'fth'),
        'all_items'             => __('Rule Groups', 'fth'),
        'add_new_item'          => __('Add New Rule Group', 'fth'),
        'add_new'               => __('Add New', 'fth'),
        'new_item'              => __('New Rule Group', 'fth'),
        'edit_item'             => __('Edit Rule Group', 'fth'),
        'update_item'           => __('Update Rule Group', 'fth'),
        'view_item'             => __('View Rule Group', 'fth'),
        'view_items'            => __('View Rule Groups', 'fth'),
        'search_items'          => __('Search Item', 'fth'),
        'not_found'             => __('Not found', 'fth'),
        'not_found_in_trash'    => __('Not found in Trash', 'fth'),
        'featured_image'        => __('Featured Image', 'fth'),
        'set_featured_image'    => __('Set featured image', 'fth'),
        'remove_featured_image' => __('Remove featured image', 'fth'),
        'use_featured_image'    => __('Use as featured image', 'fth'),
        'insert_into_item'      => __('Insert into item', 'fth'),
        'uploaded_to_this_item' => __('Uploaded to this item', 'fth'),
        'items_list'            => __('Items list', 'fth'),
        'items_list_navigation' => __('Items list navigation', 'fth'),
        'filter_items_list'     => __('Filter items list', 'fth'),
    );
    $args = array(
        'label'                 => __('Rule Group', 'fth'),
        'labels'                => $labels,
        'supports'              => array( 'title' ),
        'hierarchical'          => false,
        'public'                => true,
        'show_ui'               => true,
        'show_in_menu'          => 'fthhome',
        'menu_position'         => 100,
        'show_in_admin_bar'     => false,
        'show_in_nav_menus'     => false,
        'can_export'            => true,
        'has_archive'           => false,
        'exclude_from_search'   => true,
        'publicly_queryable'    => true,
        'rewrite'               => false,
        'capability_type'       => 'page',
    );
    register_post_type('rule_groups', $args);
}
add_action('init', 'fth_rule_groups_post_type', 0);
