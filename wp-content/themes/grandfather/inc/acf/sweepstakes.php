<?php
if (function_exists('acf_add_local_field_group')) :
    acf_add_local_field_group(array(
    'key' => 'group_59d4e0c340629',
    'title' => 'Sweepstakes',
    'fields' => array(
        array(
            'key' => 'field_59d4e15b13b5f',
            'label' => 'Call To Action',
            'name' => 'sweepstakes_cta',
            'type' => 'text',
            'instructions' => '',
            'required' => 0,
            'conditional_logic' => 0,
            'wrapper' => array(
                'width' => '',
                'class' => '',
                'id' => '',
            ),
            'default_value' => 'Sign up for tips, tricks, and advice you\'ll actually use.',
            'placeholder' => 'Sign up for tips, tricks, and advice you\'ll actually use.',
            'prepend' => '',
            'append' => '',
            'maxlength' => '',
            'readonly' => 0,
            'disabled' => 0,
        ),
        array(
            'key' => 'field_59d4e46375a76',
            'label' => 'Success Message',
            'name' => 'sweepstakes_success_msg',
            'type' => 'text',
            'instructions' => '',
            'required' => 0,
            'conditional_logic' => 0,
            'wrapper' => array(
                'width' => '',
                'class' => '',
                'id' => '',
            ),
            'default_value' => 'Thank you! You are now subscribed.',
            'placeholder' => 'Thank you! You are now subscribed.',
            'prepend' => '',
            'append' => '',
            'maxlength' => '',
            'readonly' => 0,
            'disabled' => 0,
        ),
    ),
    'location' => array(
        array(
            array(
                'param' => 'page_template',
                'operator' => '==',
                'value' => 'pages/sweepstakes.php',
            ),
        ),
    ),
    'menu_order' => -20,
    'position' => 'normal',
    'style' => 'default',
    'label_placement' => 'top',
    'instruction_placement' => 'label',
    'hide_on_screen' => array(
        0 => 'excerpt',
        1 => 'discussion',
        2 => 'comments',
        3 => 'format',
        4 => 'send-trackbacks',
    ),
    'active' => 1,
    'description' => '',
    ));
endif;
