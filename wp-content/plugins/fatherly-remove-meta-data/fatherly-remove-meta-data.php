<?php
/**
 * Plugin Name:     Fatherly Remove Meta Data
 * Plugin URI:      https://www.fatherly.com
 * Description:     Created during work for <pre>WEB-336</pre>
 * Author:          Sean M Walsh
 * Text Domain:     fth-meta-remover
 * Version:         1.0
 *
 *
 * @package Fatherly_Remove_Meta_Data
 * @subpackage Main
 * @author Sean M Walsh
 * @version 1.0
 */

/*
* fatherly_remove_meta_data_install
*
 * This function fires on plugin activation and will delete all occurrences of the meta key value
*/
function fatherly_remove_meta_data_install()
{
    global $wpdb;
    $table_name = $wpdb->postmeta;
    $wpdb->query("DELETE FROM `$table_name` WHERE meta_value = 'template-featured.php'");
    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
}

register_activation_hook(__FILE__, 'fatherly_remove_meta_data_install');