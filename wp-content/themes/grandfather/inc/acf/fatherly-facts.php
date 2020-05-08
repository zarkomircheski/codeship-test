<?php
if (function_exists('acf_add_local_field_group')) :
    acf_add_local_field_group(array(
    'key' => 'group_573d25f67b537',
    'title' => 'Fatherly Facts',
    'fields' => array(
        array(
            'key' => 'field_573d25fae0dec',
            'label' => 'List of Facts',
            'name' => 'fatherly_facts',
            'type' => 'repeater',
            'instructions' => '',
            'required' => 0,
            'conditional_logic' => 0,
            'wrapper' => array(
                'width' => '',
                'class' => '',
                'id' => '',
            ),
            'collapsed' => '',
            'min' => '',
            'max' => '',
            'layout' => 'row',
            'button_label' => 'Add Fact',
            'sub_fields' => array(
                array(
                    'key' => 'field_573d260ce0ded',
                    'label' => 'Text',
                    'name' => 'text',
                    'type' => 'textarea',
                    'instructions' => 'Keep below 140 characters',
                    'required' => 0,
                    'conditional_logic' => 0,
                    'wrapper' => array(
                        'width' => '',
                        'class' => '',
                        'id' => '',
                    ),
                    'default_value' => '',
                    'placeholder' => '',
                    'maxlength' => '',
                    'rows' => '',
                    'new_lines' => 'wpautop',
                    'readonly' => 0,
                    'disabled' => 0,
                ),
            ),
        ),
    ),
    'location' => array(
        array(
            array(
                'param' => 'options_page',
                'operator' => '==',
                'value' => 'acf-options-fatherly-facts',
            ),
        ),
    ),
    'menu_order' => 0,
    'position' => 'normal',
    'style' => 'default',
    'label_placement' => 'top',
    'instruction_placement' => 'label',
    'hide_on_screen' => '',
    'active' => 1,
    'description' => '',
    'modified' => 1463625409,
    'local' => 'php',
    ));
endif;
