<?php
if (function_exists('acf_add_local_field_group')) :
    acf_add_local_field_group(array(
        'key' => 'group_5a1c606454f7c',
        'title' => 'Newsletter Page',
        'fields' => array(
            array(
                'key' => 'field_5a1c607cb4a7a',
                'label' => 'Default Copy',
                'name' => 'default_copy',
                'type' => 'wysiwyg',
                'value' => null,
                'instructions' => 'If no variation is passed or if the variation has no copy defined then this will be used.',
                'required' => 1,
                'conditional_logic' => 0,
                'wrapper' => array(
                    'width' => '',
                    'class' => '',
                    'id' => '',
                ),
                'default_value' => '',
                'tabs' => 'all',
                'toolbar' => 'full',
                'media_upload' => 0,
                'delay' => 0,
            ),
            array(
                'key' => 'field_5a1c60afb4a7b',
                'label' => 'Default Image',
                'name' => 'default_image',
                'type' => 'image',
                'value' => null,
                'instructions' => 'If no variation is passed or if the variation has no image defined then this will be used.',
                'required' => 0,
                'conditional_logic' => 0,
                'wrapper' => array(
                    'width' => '',
                    'class' => '',
                    'id' => '',
                ),
                'return_format' => 'array',
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
            array(
                'key' => 'field_5a3a857a2e4f6',
                'label' => 'Signup Confirmation Page',
                'name' => 'signup_confirmation_page',
                'type' => 'page_link',
                'instructions' => '',
                'required' => 0,
                'conditional_logic' => 0,
                'wrapper' => array(
                    'width' => '',
                    'class' => '',
                    'id' => '',
                ),
                'post_type' => array(
                    0 => 'page',
                ),
                'taxonomy' => array(
                ),
                'allow_null' => 0,
                'allow_archives' => 0,
                'multiple' => 0,
            ),
            array(
                'key' => 'field_5a1c612fb4a7c',
                'label' => 'Variants',
                'name' => 'variants',
                'type' => 'repeater',
                'value' => null,
                'instructions' => 'This is where you will define all of the different variants for this newsletter sign-up',
                'required' => 0,
                'conditional_logic' => 0,
                'wrapper' => array(
                    'width' => '',
                    'class' => '',
                    'id' => '',
                ),
                'collapsed' => '',
                'min' => 0,
                'max' => 0,
                'layout' => 'table',
                'button_label' => '',
                'sub_fields' => array(
                    array(
                        'key' => 'field_5a1c615cb4a7d',
                        'label' => 'Referral Key',
                        'name' => 'referral_key',
                        'type' => 'text',
                        'value' => null,
                        'instructions' => 'this is the value that will be used in the "fth_ref="	part of the url to determine which variant to use.',
                        'required' => 1,
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
                        'key' => 'field_5a1c61aab4a7e',
                        'label' => 'Variant Copy',
                        'name' => 'variant_copy',
                        'type' => 'wysiwyg',
                        'value' => null,
                        'instructions' => 'If this variant has special copy that will need to be used then provide that here.',
                        'required' => 0,
                        'conditional_logic' => 0,
                        'wrapper' => array(
                            'width' => '',
                            'class' => '',
                            'id' => '',
                        ),
                        'default_value' => '',
                        'tabs' => 'all',
                        'toolbar' => 'full',
                        'media_upload' => 1,
                        'delay' => 0,
                    ),
                    array(
                        'key' => 'field_5a1c61d7b4a7f',
                        'label' => 'Variant Desktop Image',
                        'name' => 'variant_image',
                        'type' => 'image',
                        'value' => null,
                        'instructions' => '',
                        'required' => 0,
                        'conditional_logic' => 0,
                        'wrapper' => array(
                            'width' => '',
                            'class' => '',
                            'id' => '',
                        ),
                        'return_format' => 'array',
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
                    array(
                        'key' => 'field_5a1c61d7b4a7e',
                        'label' => 'Variant Mobile Image',
                        'name' => 'variant_mobile_image',
                        'type' => 'image',
                        'value' => null,
                        'instructions' => '',
                        'required' => 0,
                        'conditional_logic' => 0,
                        'wrapper' => array(
                            'width' => '',
                            'class' => '',
                            'id' => '',
                        ),
                        'return_format' => 'array',
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
            ),
        ),
        'location' => array(
            array(
                array(
                    'param' => 'post_type',
                    'operator' => '==',
                    'value' => 'newsletter_signup',
                ),
            ),
        ),
        'menu_order' => 0,
        'position' => 'acf_after_title',
        'style' => 'default',
        'label_placement' => 'top',
        'instruction_placement' => 'label',
        'hide_on_screen' => '',
        'active' => 1,
        'description' => '',
    ));
endif;
