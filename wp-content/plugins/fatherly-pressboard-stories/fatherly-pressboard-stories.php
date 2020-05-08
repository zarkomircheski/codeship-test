<?php
/**
 * Plugin Name: Fatherly Pressboard Stories
 * Plugin URI: https://www.fatherly.com
 * Description: Automatically activate your Pressboard sponsored stories.
 * Version: 1.07
 * Author: Fatherly
 * Author URI: https://www.fatherly.com
 * License: A "Slug" license name e.g. GPL2
 */

/*  Copyright YEAR  PLUGIN_AUTHOR_NAME  (email : PLUGIN AUTHOR EMAIL)

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License, version 2, as 
published by the Free Software Foundation.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
 */

class PressboardStories
{
    /**
     * Holds the values to be used in the fields callbacks
     */
    private $options;

    /**
     * Start up
     */
    public function __construct()
    {
        // Set class property
        $this->options = get_option( 'pressboard_stories_options' );

        if( is_admin() ) {
            add_action( 'admin_menu', array( $this, 'add_plugin_page' ) );
            add_action( 'admin_init', array( $this, 'page_init' ) );

            // Check if plugin is configured, if not, add new hook. 
            if (! isset($this->options['media_id']) || $this->options[ 'media_id' ] <= 0)
                add_action( 'admin_notices', array ( $this, 'pressboard_stories_admin_notices' ) );
        }

        // Include the java script files
        add_action( 'wp_print_scripts', array( $this, 'pressboard_scripts' ) );
        // add_action( 'wp_enqueue_scripts', array( $this, 'pressboard_enqueue_scripts' ) );

        // Check if sposnorship tag is included. 
        if (! isset($this->options['include_sponsorship_tag']) || $this->options[ 'include_sponsorship_tag' ] == 1)
            add_filter('the_content', array( $this, 'insert_pressboard_sponsorship_tag'), 100 );

        // Insert html tags into content
        add_filter('the_content', array( $this, 'insert_pressboard_sponsorship_msg'), 10 );
    }

    /**
     * Add JavaScript files
     */
    public function pressboard_scripts()
    {
        // Modified from original file to to make sure we are not on an amp page
        if ( isset($this->options[ 'media_id' ] ) && $this->options[ 'media_id' ] > 0  && !is_amp_endpoint()) {
            // echo '<script>(function (global) { global.PressboardMedia = { mediaId: ' . esc_attr($this->options[ 'media_id' ]) . ' } })(this);</script>';
            // . '<script type="text/javascript" src="' . plugins_url( 'pressboard-js.js', __FILE__ ) . '"></script>';

            echo '<script type="text/javascript">' .
            '(function () { ' .
            '    var d = document,' .
            '        s = d.createElement("script"), ' .
            '        d = d.getElementsByTagName("script")[0]; ' .
            '    s.type = "text/javascript"; ' .
            '    s.async = !0; ' .
            '    s.src = "https://adserver.pressboard.ca/v3/embedder?media=' . esc_attr($this->options[ 'media_id' ]) . '"; ' .
            '    d.parentNode.insertBefore(s, d); ' .
            '})(); ' .
            '</script> ';
        }
    }

    /**
     * Enqueue scripts and styles
     */
    //function pressboard_enqueue_scripts() {
    //    wp_enqueue_script( 'script-pressboard-embedder', plugins_url( 'pressboard-js.js', __FILE__ ), array(), '1.0.0', true );
    //}

    /**
     * Insert HTML sponsorship tag into content
     */
    public function insert_pressboard_sponsorship_tag($content) {
        // Modified from original file to to make sure we are not on an amp page
        if(!is_amp_endpoint()) {
            return "<h4 id='pressboard-ad-sponsorship-tag' style='margin-bottom: 35px;'></h4>" . $content;
        }else{
            return $content;
        }
    }

    /**
     * Insert HTML tags into content
     */
    public function insert_pressboard_sponsorship_msg($content) {
        // Modified from original file to to make sure we are not on an amp page
        if(!is_amp_endpoint()) {
            return $content . "<div id='pressboard-ad-sponsorship-msg'></div>";
        }else{
            return $content;
        }
    }

    /**
     * The load function
     */
    public function plugin_page_loaded()
    {
        // Current admin page is the options page for our plugin, so do not display the notice
        remove_action( 'admin_notices', array ( $this, 'pressboard_stories_admin_notices' ) );
    }

    public function pressboard_stories_admin_notices()
    {
        echo "<div id='notice' class='error fade'><p><b>Pressboard Stories</b> is not configured yet. Please add your Media ID under <a href='".admin_url( "options-general.php?page=pressboard-setting-admin" )."'>Settings</a>.</p></div>\n";
    }

    /**
     * Add options page
     */
    public function add_plugin_page()
    {
        // This page will be under "Settings"
        $hook_suffix = add_options_page(
            'Pressboard Stories Admin',
            'Pressboard Stories',
            'manage_options',
            'pressboard-setting-admin',
            array( $this, 'create_admin_page' )
        );

        // Use the hook suffix to compose the hook and register an action executed when plugin's options page is loaded
        add_action( 'load-' . $hook_suffix , array ( $this, 'plugin_page_loaded' ) );
    }

    /**
     * Options page callback
     */
    public function create_admin_page()
    {
        if (!current_user_can('manage_options'))  {
            wp_die( __('You do not have sufficient permissions to access this page.') );
        }
        ?>
        <div class="wrap">
            <h2><img src="<?php echo plugins_url( 'pressboard-logo.png', __FILE__ ) ?>" alt="Pressboard Settings" style="height: 55px; margin-bottom: 15px;" /></h2>
            <form method="post" action="options.php">
            <?php
                // This prints out all hidden setting fields
                settings_fields( 'pressboard_stories_options' );
                do_settings_sections( 'pressboard-setting-admin' );
                submit_button();
            ?>
            </form>
        </div>
        <?php
    }

    /**
     * Register and add settings
     */
    public function page_init()
    {
        register_setting(
            'pressboard_stories_options', // Option group
            'pressboard_stories_options', // Option name
            array( $this, 'sanitize' ) // Sanitize
        );

        add_settings_section(
            'setting_section_website_id', // ID
            'Web Site Settings', // Title
            array( $this, 'print_section_info' ), // Callback
            'pressboard-setting-admin' // Page
        );

        add_settings_field(
            'media_id', // ID
            'Media ID', // Title 
            array( $this, 'media_id_callback' ), // Callback
            'pressboard-setting-admin', // Page
            'setting_section_website_id' // Section           
        );

        add_settings_section(
            'setting_section_other_id', // ID
            'Other Options', // Title
            array( $this, 'print_section_info' ), // Callback
            'pressboard-setting-admin' // Page
        );

        add_settings_field(
            'include_sponsorship_tag', // ID
            'Include Sponsorship Tag', // Title 
            array( $this, 'include_sponsorship_tag_callback' ), // Callback
            'pressboard-setting-admin', // Page
            'setting_section_other_id' // Section           
        );
    }

    /**
     * Sanitize each setting field as needed
     *
     * @param array $input Contains all settings fields as array keys
     */
    public function sanitize( $input )
    {
        $new_input = array();
        if( isset( $input['media_id'] ) )
            $new_input['media_id'] = absint( $input['media_id'] );

        if( ! isset( $input['include_sponsorship_tag'] ) || $input['include_sponsorship_tag'] != 1 )
            $new_input['include_sponsorship_tag'] = 0;
        else
            $new_input['include_sponsorship_tag'] = 1;

        return $new_input;
    }

    /**
     * Print the Website Section text
     */
    public function print_section_info()
    {
        // print 'Enter your settings below:';
    }

    /**
     * Get the settings option array and print one of its values
     */
    public function media_id_callback()
    {
        printf(
            '<input type="text" id="media_id" name="pressboard_stories_options[media_id]" value="%s" />',
            isset( $this->options['media_id'] ) ? esc_attr( $this->options['media_id']) : ''
        );
    }

    /**
     * Get the settings option array and print one of its values
     */
    public function include_sponsorship_tag_callback()
    {
        $checked = true;

        if (isset( $this->options['include_sponsorship_tag'] ) && $this->options['include_sponsorship_tag'] != 1)
            $checked = false;

        echo '<input type="checkbox" id="include_sponsorship_tag" name="pressboard_stories_options[include_sponsorship_tag]" value="1" ' . ($checked ? 'checked="checked"' : '') . ' />';
    }
}


$pressboard_stories_plugin = new PressboardStories();

/* End of File */