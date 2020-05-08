<?php
if (function_exists('acf_add_local_field_group')) :
    acf_add_local_field_group(array(
        'key' => 'group_5a9589e7646ei',
        'title' => 'Ages',
        'fields' => array(
            array(
                'key' => 'field_5a958b5cde306',
                'label' => 'Applicable age range for this Stage',
                'name' => '',
                'type' => 'message',
                'instructions' => '',
                'required' => 0,
                'conditional_logic' => 0,
                'wrapper' => array(
                    'width' => '',
                    'class' => '',
                    'id' => '',
                ),
                'message' => 'Use the sliders below to select the age range that is applicable to this stage',
                'new_lines' => 'wpautop',
                'esc_html' => 0,
            ),
            array(
                'key' => 'field_5a9589f4a8d5g',
                'label' => 'Ages From',
                'name' => 'age_range_from',
                'type' => 'range',
                'instructions' => '',
                'required' => 0,
                'conditional_logic' => array(

                ),
                'wrapper' => array(
                    'width' => '',
                    'class' => '',
                    'id' => '',
                ),
                'default_value' => '-2',
                'min' => '-2',
                'max' => '18',
                'step' => '',
                'prepend' => '',
                'append' => '',
            ),
            array(
                'key' => 'field_5a958b38de309',
                'label' => 'Ages to',
                'name' => 'age_range_to',
                'type' => 'range',
                'instructions' => '',
                'required' => 0,
                'conditional_logic' => array(

                ),
                'wrapper' => array(
                    'width' => '',
                    'class' => '',
                    'id' => '',
                ),
                'default_value' => '-2',
                'min' => '-2',
                'max' => '18',
                'step' => '',
                'prepend' => '',
                'append' => '',
            ),
        ),
        'location' => array(
            array(
                array(
                    'param' => 'taxonomy',
                    'operator' => '==',
                    'value' => 'stages',
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
