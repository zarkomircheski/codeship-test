<?php

// Add new taxonomy, NOT hierarchical (like tags)
$labels = array(
    'name'                       => _x('Stages', 'taxonomy general name', 'textdomain'),
    'singular_name'              => _x('Stage', 'taxonomy singular name', 'textdomain'),
    'search_items'               => __('Search Stages', 'textdomain'),
    'popular_items'              => __('Popular Stages', 'textdomain'),
    'all_items'                  => __('All Stages', 'textdomain'),
    'parent_item'                => null,
    'parent_item_colon'          => null,
    'edit_item'                  => __('Edit Stage', 'textdomain'),
    'update_item'                => __('Update Stage', 'textdomain'),
    'add_new_item'               => __('Add New Stage', 'textdomain'),
    'new_item_name'              => __('New Stage Name', 'textdomain'),
    'separate_items_with_commas' => __('Separate stages with commas', 'textdomain'),
    'add_or_remove_items'        => __('Add or remove stages', 'textdomain'),
    'choose_from_most_used'      => __('Choose from the most used stages', 'textdomain'),
    'not_found'                  => __('No stages found.', 'textdomain'),
    'menu_name'                  => __('Stages', 'textdomain'),
);

$args = array(
    'hierarchical'          => false,
    'labels'                => $labels,
    'show_ui'               => true,
    'show_admin_column'     => true,
    'update_count_callback' => '_update_post_term_count',
    'query_var'             => true,
    'rewrite'               => array( 'slug' => 'stages' ),
);

register_taxonomy('stages', 'post', $args);


function set_ages_stages()
{
    $stage_info = array(
        'stage' => array(),
        'year'  => array(),
        'month' => array(),
        'week'  => array()
    );

    if (is_single()) {
        global $post;
        $terms = wp_get_post_terms($post->ID, 'stages');

        foreach ($terms as $term) {
            if (preg_match("/stage/i", $term->slug, $match)) {
                $val = preg_replace("/stage-/i", "", $term->slug);
                $val = strtoupper($val);
                array_push($stage_info['stage'], $val);
            }

            if (preg_match("/year/i", $term->slug, $match)) {
                $val = preg_replace("/year-/i", "", $term->slug);
                $val = strtoupper($val);
                if (strlen($val) == 1) {
                    $val = '0' . $val;
                }
                $meta_val = 'Y' . $val;
                array_push($stage_info['year'], $meta_val);
            }

            if (preg_match("/month/i", $term->slug, $match)) {
                $val = preg_replace("/month-/i", "", $term->slug);
                $val = strtoupper($val);
                if (strlen($val) == 1) {
                    $val = '0' . $val;
                }
                $meta_val = 'M' . $val;
                array_push($stage_info['month'], $meta_val);
            }

            if (preg_match("/week/i", $term->slug, $match)) {
                $val = preg_replace("/week-/i", "", $term->slug);
                $val = strtoupper($val);
                if (strlen($val) == 1) {
                    $val = '0' . $val;
                }
                $meta_val = 'W' . $val;
                array_push($stage_info['week'], $meta_val);
            }
        }
    }

    return $stage_info;
}

function week_level_stages($stages)
{
    $keys = '';
    foreach ($stages as $stage) {
        if (!empty($stage)) {
            $keys .= $stage[0] . '_';
        }
    }
    return rtrim($keys, '_');
}

function month_level_stages($stages)
{
    $keys = '';
    unset($stages['week']);
    foreach ($stages as $stage) {
        if (!empty($stage)) {
            $keys .= $stage[0] . '_';
        }
    }
    return rtrim($keys, '_');
}

function year_level_stages($stages)
{
    $keys = '';
    unset($stages['week']);
    unset($stages['month']);
    foreach ($stages as $stage) {
        if (!empty($stage)) {
            $keys .= $stage[0] . '_';
        }
    }
    return rtrim($keys, '_');
}
function bt_meta_keywords()
{
    $meta_value = '';
    $stages = set_ages_stages();
    if (!empty($stages['stage'])) {
        $meta_value .= week_level_stages($stages) . ', ';
        $meta_value .= month_level_stages($stages) . ', ';
        $meta_value .= year_level_stages($stages) . ', ';
        $meta_value .= $stages['stage'][0];
    } else {
        $meta_value .= 'General';
    }
    
    return $meta_value;
}
