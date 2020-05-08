<?php
if (function_exists('acf_add_local_field_group')) :
    acf_add_local_field_group(array(
    'key' => 'group_5a7c7d0a6e6a0',
    'title' => '404 Collection',
    'fields' => array(
        array(
            'key' => 'field_5a7c7d15f5d1c',
            'label' => '404 Collection',
            'name' => '404_collection',
            'type' => 'post_object',
            'instructions' => '',
            'required' => 0,
            'conditional_logic' => 0,
            'wrapper' => array(
                'width' => '',
                'class' => '',
                'id' => '',
            ),
            'post_type' => array(
                0 => 'hp_collection',
            ),
            'taxonomy' => array(
            ),
            'allow_null' => 0,
            'multiple' => 0,
            'return_format' => 'id',
            'ui' => 1,
        ),
    ),
    'location' => array(
        array(
            array(
                'param' => 'options_page',
                'operator' => '==',
                'value' => 'acf-options',
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
    ));
endif;
