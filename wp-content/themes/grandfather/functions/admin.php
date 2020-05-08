<?php
// Footer text modification
function remove_footer_admin()
{
    echo 'Theme developed by <a href="http://www.milkandpixels.com" target="_blank">Milk & Pixels</a> and powered by <a href="http://wordpress.org" target="_blank">WordPress</a>.';
}
add_filter('admin_footer_text', 'remove_footer_admin');

// Custom login styling
function theme_specific_login_style()
{
    wp_enqueue_style('theme-specific-login', fth_get_protocol_relative_template_directory_uri() . '/css/login.css');
}
add_action('login_enqueue_scripts', 'theme_specific_login_style');

// Editor styling
function theme_specific_editor_style()
{
    add_editor_style('theme-specific-editor-style', fth_get_protocol_relative_template_directory_uri() . '/css/admin.css');
}
add_action('admin_init', 'theme_specific_editor_style');
