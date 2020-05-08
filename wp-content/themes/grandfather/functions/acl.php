<?php

function check_if_user_can_add_term($term, $taxonomy)
{
    if ($taxonomy === 'stages') {
        global $current_user;
        if ($current_user->roles[0] !== 'administrator') {
            return new WP_Error('term_addition_blocked', __('You cannot add terms to this taxonomy'));
        }
    }
    return $term;
}

add_action('pre_insert_term', 'check_if_user_can_add_term', 0, 2);

add_action('pre_insert_term', 'fth_restrict_tag_creation_for_non_admins', 0, 2);
/**
 * fth_restrict_tag_creation_for_non_admins
 *
 * This function will fire on the `pre_insert_term` filter and will check if the taxonomy of the term being added is
 * `post_tag` and if so it will also check if the current user is an admin or a super editor if the current user  does
 * not have one of those two roles then they aren't allowed to add the term and an error message is returned to the user.
 *
 * In order to allow users to assign existing tags while at the same time disallow creation of new tags we have to get
 * creative in how we handle the error condition. When WP updates or saves a post all of the tags for the post are put
 * into an array whether they are existing tags or new tags. When we hook `pre_insert_term` if we return anything other
 * than a truthy value then WP will drop the remaining tags from the post update. So if a user has one brand new tag and
 * 3 existing tags on a post then it will drop the three existing tags once it gets an instance of WP_Error or anything
 * that cannot ultimately be used to create the new tag. Whatever string we return will ultimately become the name of
 * the new tag that is to be created. What we do here is we first set up our error message telling the user which tag
 * they were trying to add that caused the error and then we return the string `invalid_tag`. This will result in a
 * new tag being created called `invalid_tag` this tag is immediately deleted as soon as it's created using the
 * `created_term` hook. This is all done in the same request as the post update/save meaning the user will not see the
 * `invalid_tag` they will only see the tags they added sans the brand new tag which is mentioned in the error message.
 *
 * @param $term
 * @param $taxonomy
 * @return WP_Error | Bool | String
 */
function fth_restrict_tag_creation_for_non_admins($term, $taxonomy)
{
    $user = wp_get_current_user();
    if ($taxonomy == 'post_tag' && !in_array('administrator', $user->roles) && !in_array('super_editor', $user->roles)) {
        if ($postID = filter_input(INPUT_POST, 'post_ID')) {
            /*
             * This means that our failure is happening on the post edit screen and not on the main tags page. We must
             * handle errors and show feedback differently. We set a transient with an expiration time of 30 seconds
             * that contains the error message.
             */
            set_transient("{$postID}_term_addition_blocked", sprintf("You cannot create the tag %s. New terms may only be added by Admins and Super Editors", $term), 30);
            return 'invalid_tag';
        } else {
            return new WP_Error('term_addition_blocked', __("You are unauthorized to add new tags."));
        }
    }
    return $term;
}

/**
 * fth_show_term_addition_notice
 *
 * This is used to show the term addition error message to a user when they try to add a new tag but aren't allowed to.
 * We check to see if a transient is set for this post related to term addition errors and then we show it to the user.
 */
function fth_show_term_addition_notice()
{
    $screen = get_current_screen();
    if ('post' == $screen->post_type
        && 'post' == $screen->base) {
        $postID = filter_input(INPUT_GET, 'post', FILTER_SANITIZE_STRING);
        if ($errorMessage = get_transient("{$postID}_term_addition_blocked")) {
            ?>
            <div class="error">
                <p><?php _e($errorMessage, 'fth'); ?></p>
            </div>
            <?php
        }
    }
}

add_action('admin_notices', 'fth_show_term_addition_notice');

/**
 * fth_remove_invalid_tag
 *
 * This method will fire when a new terms is created. If the term being created is a `post_tag` and the new term has a
 * name of `invalid_tag` then this method will delete that term.
 * @param $term_id
 * @param $tt_id
 * @param $taxonomy
 */
function fth_remove_invalid_tag($term_id, $tt_id, $taxonomy)
{
    if ($taxonomy == 'post_tag') {
        $tag = wpcom_vip_get_term_by('id', $term_id, 'post_tag');
        if ($tag->name === 'invalid_tag') {
            wp_delete_term($term_id, 'post_tag');
        }
    }
}

add_action('created_term', 'fth_remove_invalid_tag', 10, 3);
