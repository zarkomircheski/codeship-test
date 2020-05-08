<?php
/**
 * Plugin Name:     Fatherly Update GA Tags
 * Plugin URI:      https://www.fatherly.com
 * Description:     Created during work for <pre>WEB-1102</pre>
 * Author:          Sean M Walsh
 * Version:         1.0
 */

/*
* fatherly_update_ga_tags_install
*
 * This function fires on plugin activation and update all posts in the csv file with the ga tags in the csv file
*/
function fatherly_update_ga_tags_install()
{
    global $wpdb;
    $csv_file = __DIR__ . '/inc/data/fatherly-update-ga-tags-data-2.csv';
    $csv_file_data = array_map('str_getcsv', file($csv_file));
    $keys = $csv_file_data[0];
    unset($csv_file_data[0]);

    foreach ($csv_file_data as $csv_file_datum) {
        $row_data = array_combine($keys, $csv_file_datum);

        // get post slug
        $post_name = explode("/", $row_data['url']);

        // get post object
        $args = [
            'post_type'      => 'post',
            'posts_per_page' => 1,
            'post_name__in'  => [$post_name[count($post_name)-2]]
        ];
        $post = get_posts( $args );

        // Transform ga tag so it follows current conventions
        $gaTag = strtolower(str_replace(' ', '-', $row_data['tag']));

        // Get current GA Tags
        $gaTags = get_post_meta($post[0]->ID, 'ga_tags', true );

        // If there is already a tag add the new on after with a comma separating them
        // otherwise just insert the ga tag
        if($gaTags != '') {
            // If tag already exists do not add it again
            if(strpos($gaTags, $row_data['tag']) === false) {
                update_post_meta($post[0]->ID, 'ga_tags', $gaTags . ',' . $gaTag);
            }
        } else {
            update_post_meta($post[0]->ID, 'ga_tags' , $gaTag);
        }
    }
}

register_activation_hook(__FILE__, 'fatherly_update_ga_tags_install');