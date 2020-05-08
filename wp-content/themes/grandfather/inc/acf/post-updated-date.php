<?php
if (function_exists('acf_add_local_field_group')) :
    acf_add_local_field_group(array(
        'key' => 'group_post_date',
        'title' => 'Post Date',
        'fields' => array(
            array(
                'key' => 'field_show_article_updated_date',
                'label' => 'Show article updated time in header',
                'name' => 'show_article_updated_date',
                'type' => 'true_false',
                'instructions' => '',
                'required' => 0,
                'conditional_logic' => 0,
                'wrapper' => array(
                    'width' => '',
                    'class' => '',
                    'id' => '',
                ),
                'message' => '',
                'default_value' => 0,
                'ui' => 0,
                'ui_on_text' => '',
                'ui_off_text' => '',
            ),
        ),
        'location' => array(
            array(
                array(
                    'param' => 'post_type',
                    'operator' => '==',
                    'value' => 'post',
                ),
            ),
        ),
        'menu_order' => 0,
        'position' => 'side',
        'style' => 'default',
        'label_placement' => 'top',
        'instruction_placement' => 'label',
        'hide_on_screen' => '',
        'active' => 1,
        'description' => '',
    ));
endif;
