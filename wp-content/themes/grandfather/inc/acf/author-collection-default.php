<?php
if (function_exists('acf_add_local_field_group')) :
    acf_add_local_field_group(array(
        'key' => 'group_author_collections',
        'title' => 'Author Collection',
        'fields' => array(
            array(
                'key' => 'field_default_author_collection',
                'label' => 'Default Author Collection',
                'name' => 'default_author_collection',
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
                'allow_null' => 1,
                'multiple' => 0,
                'return_format' => 'object',
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
