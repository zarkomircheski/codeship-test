<?php
/*
Plugin Name: Fatherly Advanced Custom Fields: Article Categories
Plugin URI: https://www.fatherly.com
Description: Adds a new ACF field type to replace the category metabox in WP so that we may perform validation prior to post save.
Version: 1.0.0
Author: William Wilkerson
Author URI: mailto:william.wilkerson@fatherly.com
*/


// exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}
include_once('inc/classes/ACFArticleCategoriesSetup.php');
$settings = array(
    'version' => '1.0.0',
    'url' => plugin_dir_url(__FILE__),
    'path' => plugin_dir_path(__FILE__)
);
new \FatherlyPlugin\FACF\ACFArticleCategoriesSetup($settings);
include_once('inc/classes/ArticleCategoryUpdater.php');
new \FatherlyPlugin\FACF\ArticleCategoryUpdater();
add_filter('wpseo_primary_term_taxonomies', '__return_empty_array');