<?php

/**
 * Array of ACF export files (without .php extension)
 */
$acf_field_groups = array(
    '404-collection',
    'additional-post-details',
    'ages-fields-post',
    'ages-fields-stages',
    'article-categories',
    'amazon-product-updater',
    'author-collection-default',
    'buy-button-information',
    'campaign-details',
    'category-fields',
    'content-collection',
    'fatherly-facts',
    'franchise',
    'general-site-options',
    'homepage-collections',
    'homepage-modules',
    'imgix-settings',
    'landing-page',
    'listicle-fields',
    'module-insertion-settings',
    'newsletter-signup',
    'newsletter-signup-default',
    'options-page',
    'pinterest',
    'post-options',
    'post-updated-date',
    'post-video',
    'random-generator',
    'registry-landing',
    'rule-groups',
    'registry-start-page',
    'stages-collection',
    'sweepstakes',
    'sweepstakes-fields',
    'tag-collection-default',
    'video-lead',
);

/**
 * Load the ACF export files to the theme.
 */
foreach ($acf_field_groups as $field_group) {
    require_once($field_group . '.php');
}
