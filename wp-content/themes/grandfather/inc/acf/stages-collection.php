<?php
if (function_exists('acf_add_local_field_group')) :
    acf_add_local_field_group(array(
        'key' => 'group_5a78960128333',
        'title' => 'Stages Fields',
        'fields' => false,
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
        'position' => 'normal',
        'style' => 'default',
        'label_placement' => 'top',
        'instruction_placement' => 'label',
        'hide_on_screen' => '',
        'active' => 1,
        'description' => '',
    ));

    acf_add_local_field(array(
        'parent' => 'group_5a78960128333',
        'key' => 'field_content_collection',
        'label' => 'Content Collection',
        'name' => 'content_collection',
        'type' => 'post_object',
        'post_type' => 'hp_collection',
        'allow_null' => 0,
        'multiple' => 0,
        'return_format' => 'object'
    ));
endif;
