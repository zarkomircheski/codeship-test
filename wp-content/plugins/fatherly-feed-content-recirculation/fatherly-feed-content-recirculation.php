<?php
/**
 * Plugin Name:     Fatherly Feed Content Recirculation
 * Plugin URI:      https://www.fatherly.com
 * Description:     Created during work for <pre>WEB-855</pre> this plugin takes popular posts and appends them to the end of the results on our feed pages.
 * Author:          William Wilkerson
 * Author URI:      mailto:william.wilkerson@fatherly.com
 * Text Domain:     fth-feed-content-recirculation
 * Version:         1.0
 *
 *
 * @package Fatherly_Feed_Content_Recirculation
 * @subpackage Main
 * @author William Wilkerson
 * @version 1.0
 */

/**
 * fatherly_fcr_install
 *
 * This function fires on plugin activation and will create the database table that will contain the data from
 * spreadsheet put together with all posts that will be recirculated.
 */

function fatherly_fcr_install()
{
    global $wpdb;

    $table_name = $wpdb->prefix . 'feed_content_recirculation_posts';
    if ($wpdb->get_var("SHOW TABLES LIKE '$table_name'") != $table_name) {
        $sql = "CREATE TABLE `$table_name` (
                `id` BIGINT(20) NOT NULL AUTO_INCREMENT,
                `post_url` varchar(255) NOT NULL,
                `post_id` BIGINT(20) UNSIGNED NOT NULL,
                `processed` INT(1) DEFAULT 0 NOT NULL,
                PRIMARY KEY(`id`)
            );";
        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($sql);
        fatherly_fcr_populate_data();
    }
}

/**
 * fatherly_fcr_populate_data
 * This will prepopulate the database table from the csv provided by Meren.
 */
function fatherly_fcr_populate_data()
{
    global $wpdb;
    $table_name = $wpdb->prefix . 'feed_content_recirculation_posts';
    $csv_file = __DIR__ . '/inc/data/popular_posts.csv';
    $csv_file_data = array_map('str_getcsv', file($csv_file));

    foreach ($csv_file_data as $csv_file_datum) {
        $wpdb->insert($table_name, array('post_url' => $csv_file_datum[0], 'post_id' => $csv_file_datum[1]));
    }
}

register_activation_hook(__FILE__, 'fatherly_fcr_install');

/**
 * fatherly_fcr_append_posts_to_feed
 * Responsible for appending posts to the end of the RSS feeds. Is fired on its own filter.
 * @param $posts
 * @return array
 */
function fatherly_fcr_append_posts_to_feed($posts)
{
    include_once(__DIR__ . '/inc/classes/ContentRecirculation.php');
    $popular_posts = FCR\ContentRecirculation::init()->getPostsForRecirculation();
    if (count($popular_posts) > 1) {
        $posts = array_merge($posts, $popular_posts);
    }

    return $posts;
}

add_filter('fcr_recirc_posts', 'fatherly_fcr_append_posts_to_feed');

/**
 * fatherly_fcr_register_admin_page
 *
 * Sets up the admin page link on the backend for administering the site
 */
function fatherly_fcr_register_admin_page()
{
    add_submenu_page(
        'tools.php',
        'Feed Content Recirculation',
        'Feed Content Recirculation',
        'view_fatherly_settings',
        'feed-content-recirculation',
        'fatherly_fcr_process_admin_page'
    );
}

add_action('admin_menu', 'fatherly_fcr_register_admin_page');

/**
 * fatherly_fcr_process_admin_page
 * Responsible for displaying the page on the backend.
 */
function fatherly_fcr_process_admin_page()
{
    include_once(__DIR__ . '/inc/classes/ContentRecirculation.php');
    $pageData = FCR\ContentRecirculation::init()->getAdminPageData();
    include(__DIR__ . '/inc/pages/feed-content-recirculation.php');
}

add_action('wp_ajax_fatherly_feed_content_recirculation', 'fatherly_fcr_handle_ajax');

/**
 * fatherly_fcr_handle_ajax
 * Responsible for handling the ajax requests from the admin page
 */
function fatherly_fcr_handle_ajax()
{
    include_once(__DIR__ . '/inc/classes/ContentRecirculation.php');
    $FCRInstance = FCR\ContentRecirculation::init();
    switch ($_REQUEST['operation']) {
        case 'add':
            $results = $FCRInstance->addNewPostToRecirculation($_REQUEST['post_id']);
            break;
        case 'delete':
            $results = $FCRInstance->deletePostFromRecirculation($_REQUEST['post_id']);
            break;
        case 'reset':
            $results = $FCRInstance->resetPostProcessedStatus($_REQUEST['post_id']);
            break;
    }
    echo wp_json_encode($results);
    wp_die();
}

add_action('admin_enqueue_scripts', 'fatherly_fcr_enqueue_admin_styles');

/**
 * fatherly_fcr_enqueue_admin_styles
 * enqueues our CSS and JS on our admin page
 */
function fatherly_fcr_enqueue_admin_styles()
{
    $current_screen = get_current_screen();
    if (strpos($current_screen->base, 'feed-content-recirculation') === false) {
        return;
    } else {
        wp_enqueue_style('fatherly_fcr_main_style', plugins_url('inc/css/fcr-styles.css', __FILE__));
        wp_enqueue_script('fatherly_fcr_main_script', plugins_url('inc/js/fcr-script.js', __FILE__));
    }
}
