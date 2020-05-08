<?php

/**
 * setup_super_editor_role
 *
 * This function sets up the super editor user role if it does not already exist.
 * We get all of the capabilities from the editor user and then create the new role with all those capabilities we then
 * add all of our additional capabilities to the role for managing users and viewing the dashboard.
 */
function setup_super_editor_role()
{
    // This is the current version of capabilities that should apply to the super editor user role.
    $super_editor_role_version = '3.0';

    // These are the capabilities available to admins which should NOT be available to super editors.
    $disallowed_caps = array(
        'switch_themes',
        'edit_themes',
        'update_themes',
        'install_themes',
        'delete_themes',
        'activate_plugins',
        'edit_plugins',
        'update_plugins',
        'delete_plugins',
        'install_plugins',
        'update_core'
    );

    if (!get_role('super_editor')) {
        //Super Editor will need to have all the permissions of an admin minus plugin,theme and core CRUD options.
        $admin_role_caps = get_role('administrator')->capabilities;
        foreach ($disallowed_caps as $disallowed_cap) {
            unset($admin_role_caps[$disallowed_cap]);
        }
        $role = add_role('super_editor', __('Super Editor'), $admin_role_caps);
        add_site_option('super_editor_role_version', $super_editor_role_version);
    } else {
        if (!get_site_option('super_editor_role_version')) {
            $update = true;
            add_site_option('super_editor_role_version', $super_editor_role_version);
            $super_editor_role_current_version = get_site_option('super_editor_role_version');
        } else {
            $super_editor_role_current_version = get_site_option('super_editor_role_version');
        }
        if ($super_editor_role_current_version !== $super_editor_role_version || $update) {
            $super_editor = get_role('super_editor');
            if ($super_editor_role_version == '3.0') {
                $admin_role_caps = get_role('administrator')->capabilities;
                foreach ($disallowed_caps as $disallowed_cap) {
                    unset($admin_role_caps[$disallowed_cap]);
                }
                foreach ($admin_role_caps as $cap => $value) {
                    if (!$super_editor->has_cap($cap)) {
                        $super_editor->add_cap($cap);
                    }
                }
            }
            if (!$super_editor->has_cap('view_fatherly_settings')) {
                $super_editor->add_cap('view_fatherly_settings');
            }
        }
        if ($super_editor_role_current_version !== $super_editor_role_version) {
            update_site_option('super_editor_role_version', $super_editor_role_version);
        }
    }
}

add_action('init', 'setup_super_editor_role');

/**
 * add_fatherly_settings_to_admin_role
 *
 * Since we're creating a new capability to control access to some fatherly settings pages we need to make sure that the
 * administrator role has this capability.
 */
function add_fatherly_settings_to_admin_role()
{
    $role = get_role('administrator');
    if (!$role->has_cap('view_fatherly_settings')) {
        $role->add_cap('view_fatherly_settings');
    }
}

add_action('init', 'add_fatherly_settings_to_admin_role');

/**
 * remove_admins_from_user_list_for_non_admins
 *
 * This function will run when the user list is about to be shown and if the current user does not have admin
 * rights then they will not be able to view admin users.
 *
 * @param $user_search
 */
function remove_admins_from_user_list_for_non_admins($user_search)
{
    if (!in_array('administrator', wp_get_current_user()->roles)) {
        global $wpdb;
        $replace = "WHERE 1=1 AND {$wpdb->users}.ID IN (
                    SELECT {$wpdb->usermeta}.user_id FROM $wpdb->usermeta
                    WHERE {$wpdb->usermeta}.meta_key = '{$wpdb->prefix}capabilities'
                    AND {$wpdb->usermeta}.meta_value NOT LIKE '%administrator%')";

        $user_search->query_where =
            str_replace('WHERE 1=1', $replace, $user_search->query_where);
    }
}

add_action('pre_user_query', 'remove_admins_from_user_list_for_non_admins');

/**
 * disallow_super_editor_from_assigning_certain_roles
 *
 * This will set the roles that the super editor has permission to edit and will remove `administrator` and
 *`super_editor` this means that super editors will not be able to assign other users to those roles. This
 * takes care of the display of these roles in the select field as well as the validation when saving.
 *
 * @param $roles
 * @return mixed
 */
function disallow_super_editor_from_assigning_certain_roles($roles)
{
    $current_user = wp_get_current_user();

    if (in_array('super_editor', $current_user->roles)) {
        unset($roles['administrator']);
        unset($roles['super_editor']);
    }

    return $roles;
}

add_filter('editable_roles', 'disallow_super_editor_from_assigning_certain_roles');

/**
 * stop_super_editor_from_accessing_admin_profile
 *
 * If a super editor manually puts in the url for an admins user edit page this will ensure that they are denied access
 */
function stop_super_editor_from_accessing_admin_profile()
{
    global $pagenow;
    if (isset($_REQUEST['user_id'])) {
        $user_id = (int)$_REQUEST['user_id'];
    } elseif (isset($_REQUEST['user'])) {
        $user_id = (int)$_REQUEST['user'];
    } else {
        $user_id = 0;
    }
    $level = get_user_meta($user_id, 'fth_user_level', true);
    $user = wp_get_current_user();
    $blocked_admin_pages = array('user-edit.php', 'users.php');
    if (in_array('super_editor', $user->roles)) {
        if (in_array($pagenow, $blocked_admin_pages) && ($level == 10)) { // 10 corresponds to admin level.
            wp_die('You cannot access the admin user.');
        }
    }
}

add_action('admin_init', 'stop_super_editor_from_accessing_admin_profile');


/**
 * fth_add_manage_fatherly_options_cap
 *
 * This sets up a new user cap called `manage_fatherly_options` which is then applied to the administrator and
 * super editor user roles if the cap does not already exists on those roles.
 */
function fth_add_manage_fatherly_options_cap()
{
    $admin_role = get_role('administrator');
    if (!$admin_role->has_cap('manage_fatherly_options')) {
        $admin_role->add_cap('manage_fatherly_options');
    }
    $super_editor_role = get_role('super_editor');
    if (!$super_editor_role->has_cap('manage_fatherly_options')) {
        $super_editor_role->add_cap('manage_fatherly_options');
    }
}

add_action('admin_init', 'fth_add_manage_fatherly_options_cap');
