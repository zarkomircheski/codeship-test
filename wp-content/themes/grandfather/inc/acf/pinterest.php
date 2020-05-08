<?php
if (function_exists('acf_add_local_field_group')) :
    acf_add_local_field_group(array(
    'key' => 'group_5aa2dcffa22c8',
    'title' => 'Pinterest',
    'fields' => array(
        array(
            'key' => 'field_5aa2dd1467323',
            'label' => 'Pinterest Description',
            'name' => 'pinterest_description',
            'type' => 'text',
            'instructions' => 'Enter a custom Pinterest description for the save button on the hero image. If not customized the description will be the combination of "{Title} - {Yoast Description}".',
            'required' => 0,
            'conditional_logic' => 0,
            'wrapper' => array(
                'width' => '',
                'class' => '',
                'id' => '',
            ),
            'default_value' => '',
            'placeholder' => '',
            'prepend' => '',
            'append' => '',
            'maxlength' => '',
        ),
        array(
            'key' => 'field_5aa2dde467324',
            'label' => 'Pinterest Image',
            'name' => 'pinterest_img',
            'type' => 'image',
            'instructions' => 'Use this field to override the Featured image for the save button on the hero image.',
            'required' => 0,
            'conditional_logic' => 0,
            'wrapper' => array(
                'width' => '',
                'class' => '',
                'id' => '',
            ),
            'return_format' => 'url',
            'preview_size' => 'thumbnail',
            'library' => 'all',
            'min_width' => '',
            'min_height' => '',
            'min_size' => '',
            'max_width' => '',
            'max_height' => '',
            'max_size' => '',
            'mime_types' => '',
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
    'position' => 'normal',
    'style' => 'default',
    'label_placement' => 'top',
    'instruction_placement' => 'label',
    'hide_on_screen' => '',
    'active' => 1,
    'description' => '',
    ));
endif;
