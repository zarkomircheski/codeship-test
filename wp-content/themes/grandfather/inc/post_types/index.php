<?php

/**
 * Array of Post Type files (without .php extension)
 */
$custom_post_types = array(
    'homepage_collections',
    'homepage_modules',
    'rule_groups',
    'newsletter_signup',
    'sweepstakes',
    'random_generator',
    'registry_landing'
);

/**
 * Load the Custom post types into the theme.
 */
foreach ($custom_post_types as $custom_post_type) {
    require_once($custom_post_type . '.php');
}
