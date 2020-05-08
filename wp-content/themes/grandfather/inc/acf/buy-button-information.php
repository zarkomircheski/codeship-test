<?php
if (function_exists('acf_add_local_field_group')) :
    acf_add_local_field_group(array(
    'key' => 'group_586a176a427ec',
    'title' => 'Buy Button Information',
    'fields' => array(
        array(
            'key' => 'field_586a17702bfaf',
            'label' => 'Buy Link',
            'name' => 'buy_link',
            'type' => 'url',
            'instructions' => '',
            'required' => 0,
            'conditional_logic' => 0,
            'wrapper' => array(
                'width' => '',
                'class' => '',
                'id' => '',
            ),
            'default_value' => '',
            'placeholder' => '',
        ),
    ),
    'location' => array(
        array(
            array(
                'param' => 'post_taxonomy',
                'operator' => '==',
                'value' => 'fields:gear-toy-individual',
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
