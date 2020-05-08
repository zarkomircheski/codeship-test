<?php

add_filter('acf/validate_value/name=posts', 'fth_validate_post_listing_module', 10, 4);
function fth_validate_post_listing_module($valid, $value, $field, $input)
{
    // bail early if value is already invalid
    if (!$valid) {
        return $valid;
    }
    if (count($value) > 6) {
        $valid = sprintf(
            "You've selected too many posts. The maximum allowed here is <strong>%d</strong> you have added <strong>%d</strong>",
            6,
            count($value)
        );
    }

    return $valid;
}

add_filter('acf/validate_save_post', 'fth_validate_post_save', 10, 0);

function fth_validate_post_save()
{
    if ($_POST['post_type'] === 'post') {
        $validator = new \Fatherly\Post\Validator();
        if (!$validator->validateAges($_POST)) {
            acf_add_validation_error($validator->input, $validator->message);
        } else {
            acf_reset_validation_errors();
        }
    }
}


add_action('save_post', 'fth_add_stages_terms_to_post', 10, 1);
function fth_add_stages_terms_to_post($id)
{
    \Fatherly\Taxonomy\Stages::init()->addStagesToPost(get_post($id));
}


function fth_set_default_age_range_value_on_post($field)
{
    $current_page = get_current_screen();
    if ($current_page->base == 'post' && $current_page->action == 'add') {
        if (!$field['value']) {
            $field['value'] = $field['default_value'];
        }
    }
    return $field;
}

add_filter('acf/prepare_field/key=field_5a9589f4a8d5c', 'fth_set_default_age_range_value_on_post');
add_filter('acf/prepare_field/key=field_5a958b38de303', 'fth_set_default_age_range_value_on_post');
