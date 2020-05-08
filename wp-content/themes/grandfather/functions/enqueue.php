<?php

/**
 * Just gets the regular one and strips out http or https
 */

function fth_get_protocol_relative_template_directory_uri()
{
    return str_replace(['http:','https:'], '', get_template_directory_uri());
}


/**
 * Register Scripts
 */
function fth_scripts_register()
{
    if ($GLOBALS['pagenow'] != 'wp-login.php' && !is_admin()) {
        // start with deregistering the wp-embed script.
        // it is included with other dependencies in the gulpfile
        wp_deregister_script('wp-embed');
        wp_deregister_script('jquery');
        // Register our main fatherly script
        if (constant('ENV') === 'prod' || constant('ENV') === 'staging') {
            $fatherly_js = MAIN_SCRIPT;
        } else {
            $fatherly_js = fth_get_protocol_relative_template_directory_uri() . '/dev_assets/js/app.js';
        }
        wp_register_script('fatherly-js', $fatherly_js, false, null, true);
        wp_localize_script('fatherly-js', 'fth_page_data', apply_filters('fth_page_data', array()));
    }
}

/**
 * Dequeue Scripts
 */
function fth_scripts_dequeue()
{
    wp_dequeue_script('tml-themed-profiles');
    wp_dequeue_script('jquery');
}

/**
 * Enqueue Scripts
 */
function fth_scripts_enqueue()
{
    wp_enqueue_script('fatherly-js');
}


/**
 * Register Styles
 */
function fth_styles_register()
{
    if (constant('ENV') === 'prod' || constant('ENV') === 'staging') {
        $fatherly_css = MAIN_STYLE;
    } else {
        $fatherly_css = fth_get_protocol_relative_template_directory_uri() . '/dev_assets/css/app.css';
    }
    wp_register_style('fatherly-css', $fatherly_css, array(), null, 'all');
}

/**
 * Register Styles For Admin Pages
 */
function fth_admin_styles_enqueue()
{
    if (constant('ENV') === 'prod' || constant('ENV') === 'staging') {
        $fatherly_admin_css = ADMIN_STYLE;
    } else {
        $fatherly_admin_css = fth_get_protocol_relative_template_directory_uri() . '/dev_assets/css/admin.css';
    }
    wp_register_style('fatherly-admin-css', $fatherly_admin_css, array(), null, 'all');
    wp_enqueue_style('fatherly-admin-css');
}

/**
 * Dequeue Styles
 */
function fth_styles_dequeue()
{
    wp_dequeue_style('jetpack_css');
    wp_dequeue_style('nextend_fb_connect_stylesheet');
}


/**
 * Enqueue Styles
 */
function fth_styles_enqueue()
{
    wp_enqueue_style('fatherly-css');
}


function add_defer_attribute($tag, $handle)
{
    // add script handles to the array below
    $scripts_to_defer = array('fth-load-more');

    foreach ($scripts_to_defer as $defer_script) {
        if ($defer_script === $handle) {
            return str_replace(' src', ' defer="defer" src', $tag);
        }
    }
    return $tag;
}

/**
 * Make sure Jetpack doesn't concatinate all our styles.
 */
add_filter('jetpack_implode_frontend_css', '__return_false');

/**
 * Remove all styles added by jetpack
 */
function fth_remove_jetpack_styles()
{
    wp_deregister_style('AtD_style'); // After the Deadline
    wp_deregister_style('jetpack_likes'); // Likes
    wp_deregister_style('jetpack_related-posts'); //Related Posts
    wp_deregister_style('jetpack-carousel'); // Carousel
    wp_deregister_style('grunion.css'); // Grunion contact form
    wp_deregister_style('the-neverending-homepage'); // Infinite Scroll
    wp_deregister_style('infinity-twentyten'); // Infinite Scroll - Twentyten Theme
    wp_deregister_style('infinity-twentyeleven'); // Infinite Scroll - Twentyeleven Theme
    wp_deregister_style('infinity-twentytwelve'); // Infinite Scroll - Twentytwelve Theme
    wp_deregister_style('noticons'); // Notes
    wp_deregister_style('post-by-email'); // Post by Email
    wp_deregister_style('publicize'); // Publicize
    wp_deregister_style('sharedaddy'); // Sharedaddy
    wp_deregister_style('sharing'); // Sharedaddy Sharing
    wp_deregister_style('stats_reports_css'); // Stats
    wp_deregister_style('jetpack-widgets'); // Widgets
    wp_deregister_style('jetpack-slideshow'); // Slideshows
    wp_deregister_style('presentations'); // Presentation shortcode
    wp_deregister_style('jetpack-subscriptions'); // Subscriptions
    wp_deregister_style('tiled-gallery'); // Tiled Galleries
    wp_deregister_style('widget-conditions'); // Widget Visibility
    wp_deregister_style('jetpack_display_posts_widget'); // Display Posts Widget
    wp_deregister_style('gravatar-profile-widget'); // Gravatar Widget
    wp_deregister_style('widget-grid-and-list'); // Top Posts widget
    wp_deregister_style('jetpack-widgets'); // Widgets
}


/**
 * Add actions
 */
add_filter('script_loader_tag', 'add_defer_attribute', 10, 2);
add_action('wp_enqueue_scripts', 'fth_scripts_dequeue');
add_action('wp_enqueue_scripts', 'fth_styles_dequeue');
add_action('wp_enqueue_scripts', 'fth_remove_jetpack_styles');
add_action('wp_enqueue_scripts', 'fth_scripts_register');
add_action('wp_enqueue_scripts', 'fth_styles_register');
add_action('wp_enqueue_scripts', 'fth_scripts_enqueue');
add_action('wp_enqueue_scripts', 'fth_styles_enqueue');
add_action('admin_enqueue_scripts', 'fth_admin_styles_enqueue');
