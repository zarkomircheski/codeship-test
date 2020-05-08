<?php
/**
 * Plugin Name:     Fatherly Data Capture
 * Plugin URI:      https://www.fatherly.com
 * Description:     Created during work for <pre>WEB-975</pre> this plugin will create a database that will be used to store survey question
 * Author:          Sean Walsh
 * Author URI:      mailto:sean.walsh@fatherly.com
 */


/**
 * fatherly_create_data_table
 *
 * This function fires on plugin activation and will create the database table that will contain user answers to survey questions
 */
function fatherly_create_data_table()
{
    global $wpdb;
    $table_name = $wpdb->prefix . 'data_collection';
    $charset_collate = $wpdb->get_charset_collate();
    if ($wpdb->get_var("SHOW TABLES LIKE '$table_name'") != $table_name) {
        $sql = "CREATE TABLE `$table_name` (
                `id` BIGINT(20) NOT NULL AUTO_INCREMENT,
                `uuid` varchar(255) NOT NULL,
                `question` text NOT NULL,
                `answer` text NOT NULL,
                `location` varchar(255),
                PRIMARY KEY(`id`)
            ) $charset_collate;";
        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($sql);
    }
}

register_activation_hook(__FILE__, 'fatherly_create_data_table');