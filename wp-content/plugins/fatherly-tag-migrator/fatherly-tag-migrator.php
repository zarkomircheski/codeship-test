<?php
/**
 * Plugin Name:     Fatherly Tag Migrator
 * Plugin URI:      https://www.fatherly.com
 * Description:     Created during work for <pre>WEB-680</pre>
 * Author:          William Wilkerson
 * Author URI:      mailto:william@usterix.com
 * Text Domain:     fth-tag-migrator
 * Version:         1.0
 *
 *
 * @package Fatherly_Tag_Migrator
 * @subpackage Main
 * @author William Wilkerson
 * @version 1.0
 */

/**
 * fatherly_tag_migrator_install
 *
 * This function fires on plugin activation and will create the database table that will contain the data from
 * spreadsheet put together with all data needed for the tag migration
 */
function fatherly_tag_migrator_install()
{
    global $wpdb;

    $table_name = $wpdb->prefix . 'tag_migrator';
    $sql = "CREATE TABLE `$table_name` (
                `id` BIGINT(20) NOT NULL AUTO_INCREMENT,
                `name` TINYTEXT NOT NULL,
                `term_taxonomy_id` BIGINT(20) UNSIGNED NOT NULL,
                `taxonomy` TINYTEXT NOT NULL,
                `post_count` INT NOT NULL,
                `action` VARCHAR(255) NOT NULL,
                `target_taxonomy` TINYTEXT NOT NULL,
                `target_name` TINYTEXT,
                `notes` TEXT,
                `processed` INT(1) DEFAULT 0 NOT NULL,
                PRIMARY KEY(`id`)
            );";
    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql);
}

/**
 * fatherly_tag_migrator_populate_data
 *
 * This function will fire on plugin activation and after `fatherly_tag_migrator_install` and will parse a csv with
 * all the data needed for the migration and then import this data into the plugins database table.
 */
function fatherly_tag_migrator_populate_data()
{
    global $wpdb;
    $table_name = $wpdb->prefix . 'tag_migrator';
    $csv_file = __DIR__ . '/inc/data/fatherly-tag-migrator-data.csv';
    $csv_file_data = array_map('str_getcsv', file($csv_file));
    $keys = $csv_file_data[0];
    unset($csv_file_data[0]);

    foreach ($csv_file_data as $csv_file_datum) {
        $row_data = array_combine($keys, $csv_file_datum);
        $row_data['action'] = str_replace(' ', '_', $row_data['action']);
        $wpdb->insert($table_name, $row_data);
    }

}

register_activation_hook(__FILE__, 'fatherly_tag_migrator_install');
register_activation_hook(__FILE__, 'fatherly_tag_migrator_populate_data');


/**
 * fatherly_tag_migrator_register_admin_page
 *
 * This function sets up the administration menu link for the tag migrator backend page.
 */
function fatherly_tag_migrator_register_admin_page()
{
    add_submenu_page(
        'tools.php',
        'process tags',
        'Tag Migrator',
        'manage_options',
        'tag-migrator',
        'fatherly_tag_migrator_process_admin_page'
    );
}

add_action('admin_menu', 'fatherly_tag_migrator_register_admin_page');
/**
 * fatherly_tag_migrator_process_admin_page
 *
 * This function is used to generate the page on the backend for the tag migrator.
 */
function fatherly_tag_migrator_process_admin_page()
{
    include(__DIR__ . '/inc/classes/TagMigrator.php');
    $pageData = TagMigrator::getAdminPageData();
    include(__DIR__ . '/inc/pages/tag-migrator.php');
}

add_action('wp_ajax_fatherly_tag_migration', 'fatherly_tag_migrator_handle_ajax');
/**
 * fatherly_tag_migrator_handle_ajax
 *
 * When a user clicks to run a migration on the backend this function will handle those ajax requests.
 */
function fatherly_tag_migrator_handle_ajax()
{
    include(__DIR__ . '/inc/classes/TagMigrator.php');
    $results = TagMigrator::init()->runMigration(filter_input(INPUT_POST, 'type', FILTER_SANITIZE_STRING));
    echo wp_json_encode($results);
    wp_die();
}