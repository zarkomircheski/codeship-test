<?php
/**
 * Plugin Name: TinyMCE Fatherly
 * Version: 1.0.0
 * Author: Division Of/
 * Author URI: http://divisionof.com
 * Description: Adds custom styles to the TinyMCE editor for use by the Fatherly editorial team
 */

class TinyMCE_Fatherly {
    function  __construct() {
        if (is_admin()) {
            add_action('init', array($this, 'setup_tinymce_plugin'));
            wp_enqueue_style('tinymce-fatherly-css', plugin_dir_url(__FILE__) . 'tinymce-fatherly.css');
            add_editor_style(plugin_dir_url(__FILE__) . 'tinymce-fatherly-editor.css');
        }
    }

    function setup_tinymce_plugin() {
        if (!current_user_can('edit_posts') && !current_user_can('edit_pages')) return;
        if (get_user_option('rich_editing') !== 'true') return;

        add_filter('mce_external_plugins', array(&$this, 'add_tinymce_plugin'));
        add_filter('mce_buttons', array(&$this, 'add_tinymce_toolbar_button'));
    }

    function add_tinymce_plugin($plugin_array) {
        $plugin_array['fatherly'] = plugin_dir_url(__FILE__) . 'tinymce-fatherly.js';
        return $plugin_array;
    }

    function add_tinymce_toolbar_button($buttons) {
        array_push($buttons, '|', 'pull_quote', 'end_block', 'bg_highlight', 'tips');
        return $buttons;
    }

    // https://developer.wordpress.org/reference/functions/add_editor_style/
    function add_editor_style($stylesheet) {
        add_theme_support('editor-style');

        if (!is_admin()) return;

        global $editor_styles;
        $editor_styles = (array) $editor_styles;
        $stylesheet = (array) $stylesheet;
        if (is_rtl()) {
            $rtl_stylesheet = str_replace('.css', '-rtl.css', $stylesheet[0]);
            $stylesheet[] = $rtl_stylesheet;
        }

        $editor_styles = array_merge($editor_styles, $stylesheet);
    }
}

$tinymce_fatherly = new TinyMCE_Fatherly;
