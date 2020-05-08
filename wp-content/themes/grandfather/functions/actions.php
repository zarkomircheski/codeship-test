<?php
function cptui_register_my_cpts()
{
    $labels = array(
        "name" => __('Campaign', ''),
        "singular_name" => __('Campaign', ''),
        );

    $args = array(
        "label" => __('Campaigns', ''),
        "labels" => $labels,
        "description" => "",
        "public" => true,
        "show_ui" => true,
        "show_in_rest" => false,
        "rest_base" => "",
        "has_archive" => false,
        "show_in_menu" => true,
        "exclude_from_search" => false,
        "capability_type" => "post",
        "map_meta_cap" => true,
        "hierarchical" => false,
        "rewrite" => array( "slug" => "campaign", "with_front" => true ),
        'menu_icon' => 'dashicons-megaphone',
        "query_var" => true,

        "supports" => array( "title", "thumbnail" ),
    );
    register_post_type("campaign", $args);
}

function cptui_register_careers()
{
    $labels = array(
        "name" => __('Careers', ''),
        "singular_name" => __('Job', ''),
        );

    $args = array(
        "label" => __('Careers', ''),
        "labels" => $labels,
        "description" => "",
        "public" => true,
        "show_ui" => true,
        "show_in_rest" => false,
        "rest_base" => "",
        "has_archive" => false,
        "show_in_menu" => true,
        "exclude_from_search" => true,
        "capability_type" => "post",
        "map_meta_cap" => true,
        "hierarchical" => false,
        "rewrite" => array( "slug" => "careers", "with_front" => true ),
        'menu_icon' => 'dashicons-clipboard',
        "query_var" => true,

        "supports" => array( "title", "editor"),
    );
    register_post_type("job", $args);
}

// HTML5 Blank navigation
function html5blank_nav()
{
    wp_nav_menu(
        array(
        'theme_location'  => 'header-menu',
        'menu'            => '',
        'container'       => 'div',
        'container_class' => 'menu-{menu slug}-container',
        'container_id'    => '',
        'menu_class'      => 'menu',
        'menu_id'         => '',
        'echo'            => true,
        'fallback_cb'     => 'wp_page_menu',
        'before'          => '',
        'after'           => '',
        'link_before'     => '',
        'link_after'      => '',
        'items_wrap'      => '<ul>%3$s</ul>',
        'depth'           => 0,
        'walker'          => ''
        )
    );
}


function html5wp_pagination()
{
    global $wp_query;
    $big = 999999999;
    echo paginate_links(array(
        'base' => str_replace($big, '%#%', get_pagenum_link($big)),
        'format' => '?paged=%#%',
        'current' => max(1, get_query_var('paged')),
        'total' => $wp_query->max_num_pages
    ));
}

// Threaded Comments
function enable_threaded_comments()
{
    if (!is_admin()) {
        if (is_singular() and comments_open() and (get_option('thread_comments') == 1)) {
            wp_enqueue_script('comment-reply');
        }
    }
}

// Register HTML5 Blank Navigation
function register_html5_menu()
{
    register_nav_menus(array( // Using array to specify more menus if needed
        'header-menu' => __('Header Menu', 'html5blank'), // Main Navigation
        'sidebar-menu' => __('Sidebar Menu', 'html5blank'), // Sidebar Navigation
        'extra-menu' => __('Extra Menu', 'html5blank') // Extra Navigation if needed (duplicate as many as you need!)
    ));
}


// Create 1 Custom Post type for a Demo, called HTML5-Blank
function create_post_type_html5()
{
    register_taxonomy_for_object_type('category', 'html5-blank'); // Register Taxonomies for Category
    register_taxonomy_for_object_type('post_tag', 'html5-blank');
    register_post_type(
        'html5-blank', // Register Custom Post Type
        array(
        'labels' => array(
            'name' => __('HTML5 Blank Custom Post', 'html5blank'), // Rename these to suit
            'singular_name' => __('HTML5 Blank Custom Post', 'html5blank'),
            'add_new' => __('Add New', 'html5blank'),
            'add_new_item' => __('Add New HTML5 Blank Custom Post', 'html5blank'),
            'edit' => __('Edit', 'html5blank'),
            'edit_item' => __('Edit HTML5 Blank Custom Post', 'html5blank'),
            'new_item' => __('New HTML5 Blank Custom Post', 'html5blank'),
            'view' => __('View HTML5 Blank Custom Post', 'html5blank'),
            'view_item' => __('View HTML5 Blank Custom Post', 'html5blank'),
            'search_items' => __('Search HTML5 Blank Custom Post', 'html5blank'),
            'not_found' => __('No HTML5 Blank Custom Posts found', 'html5blank'),
            'not_found_in_trash' => __('No HTML5 Blank Custom Posts found in Trash', 'html5blank')
        ),
        'public' => true,
        'hierarchical' => true, // Allows your posts to behave like Hierarchy Pages
        'has_archive' => true,
        'supports' => array(
            'title',
            'editor',
            'excerpt',
            'thumbnail'
        ), // Go to Dashboard Custom HTML5 Blank post for supports
        'can_export' => true, // Allows export in Tools > Export
        'taxonomies' => array(
            'post_tag',
            'category'
        ) // Add Category and Post Tags support
        )
    );
}

function amp_post_template_add_script($amp_template)
{
    echo '<script async custom-element="amp-skimlinks" src="https://cdn.ampproject.org/v0/amp-skimlinks-0.1.js"></script>';
}



// add_action( 'after_setup_theme', 'kfalkner_theme_support' );
add_action('init', 'cptui_register_my_cpts');
add_action('init', 'cptui_register_careers');
add_action('init', 'html5wp_pagination'); // Add our HTML5 Pagination
add_action('amp_post_template_head', 'amp_post_template_add_script');

// add_action('get_header', 'enable_threaded_comments'); // Enable Threaded Comments
// add_action('init', 'register_html5_menu'); // Add HTML5 Blank Menu
// add_action('init', 'create_post_type_html5'); // Add our HTML5 Blank Custom Post Type
