<?php

if (function_exists('acf_add_options_page')) {
    acf_add_options_page(
        array(
            'page_title' => 'Options',
            'menu_title' => 'Options',
            'menu_slug' => 'acf-options',
            'capability' => 'manage_fatherly_options',
            'redirect' => false
        )
    );
    acf_add_options_page('Fatherly Facts');
    acf_add_options_sub_page([
        'page_title' => 'Ad Code Snippets',
        'parent_slug' => 'options-general.php'
    ]);
    acf_add_options_sub_page(
        array(
            'page_title' => __('Module Insertion', 'fth'),
            'menu_slug' => 'acf-module-insertion-options',
            'parent_slug' => 'acf-options'
        )
    );
}
