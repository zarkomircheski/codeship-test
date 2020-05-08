<?php
add_filter('tml_title', 'tml_title_filter', 10, 2);
add_action('show_user_profile', 'fth_add_location_meta');
add_action('edit_user_profile', 'fth_add_location_meta');
add_action('personal_options_update', 'fth_save_location_meta');
add_action('edit_user_profile_update', 'fth_save_location_meta');

function fth_add_location_meta($user)
{
    ?>
        <table class="tml-form-table profile-location">
            <tr class="tml-user-url-wrap">
                <th><label for="zip">Zip Code</label></th>
                <td><input type="text" name="zip" id="zip" value="<?php echo esc_attr(get_the_author_meta('zip', $user->ID)); ?>" class="regular-text code" /><span class="profile-location__invalid-zip-notice">Invalid zip code</span></td>
            </tr>   
        </table>    
    <?php
}

function fth_save_location_meta($user_id)
{
    if (!current_user_can('edit_user', $user_id)) {
        return false;
    }

    if (isset($_POST['zip'])) {
        update_user_meta(absint($user_id), 'zip', wp_kses_post($_POST['zip']));
    }
}

function tml_title_filter($title, $action)
{
    if ('login' == $action) {
        return 'Login / Register';
    }
    return $title;
}
