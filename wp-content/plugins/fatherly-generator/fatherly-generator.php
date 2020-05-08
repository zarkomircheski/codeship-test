<?php
/**
 * Plugin Name:     Fatherly Generator
 * Plugin URI:      https://www.fatherly.com
 * Description:     Created during work for <pre>WEB-1059</pre> this plugin will create a database that will be used to store the generator name and output
 * Author:          Sean Walsh
 * Author URI:      mailto:sean.walsh@fatherly.com
 */


/**
 * fatherly_create_data_table
 *
 * This function fires on plugin activation and will create the database table that will contain user answers to survey questions
 */
function fatherly_create_generator_tables()
{
    global $wpdb;
    $charset_collate = $wpdb->get_charset_collate();
    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');

    // Create table to store names of the generators and have an id associated with it
    $generator = $wpdb->prefix . 'generator';
    if ($wpdb->get_var("SHOW TABLES LIKE '$generator'") != $generator) {
        $sql = "CREATE TABLE `$generator` (
                `post_id` BIGINT(20) NOT NULL,
                `generator_name` text NOT NULL,
                PRIMARY KEY(`post_id`)
            ) $charset_collate;";
        dbDelta($sql);
    }
    // Create table with generator output that references the key from the first table
    $generation = $wpdb->prefix . 'generation';
    if ($wpdb->get_var("SHOW TABLES LIKE '$generation'") != $generation) {
        $sql2 = "CREATE TABLE `$generation` (
                `id` BIGINT(20) NOT NULL AUTO_INCREMENT,
                `generator_post_id` BIGINT(20),
                `generation` text NOT NULL,
                PRIMARY KEY(`id`),
                FOREIGN KEY(`generator_post_id`) REFERENCES `$generator`(`post_id`)
            ) $charset_collate;";
        dbDelta($sql2);
    }
}

register_activation_hook(__FILE__, 'fatherly_create_generator_tables');
