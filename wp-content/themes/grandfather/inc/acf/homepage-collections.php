<?php
if (function_exists('acf_add_local_field_group')) :
    acf_add_local_field_group(array(
        'key' => 'group_5a4bf7c70a328',
        'title' => 'Homepage Collections',
        'fields' => array(
            array(
                'key' => 'field_5a6b2fbc39b1e',
                'label' => 'What type of content is this collection being created for?',
                'name' => 'collection_type',
                'type' => 'select',
                'instructions' => '',
                'required' => 1,
                'conditional_logic' => 0,
                'wrapper' => array(
                    'width' => '',
                    'class' => '',
                    'id' => '',
                ),
                'choices' => array(
                    'sponsored' => 'Sponsored/Custom Content Hub',
                    'section' => 'Category/Tag Front',
                    'homepage' => 'Homepage',
                ),
                'default_value' => array(),
                'allow_null' => 0,
                'multiple' => 0,
                'ui' => 0,
                'ajax' => 0,
                'return_format' => 'value',
                'placeholder' => '',
            ),
            array(
                'key' => 'field_5a4bf7dfc9eed',
                'label' => 'Modules',
                'name' => 'modules',
                'type' => 'relationship',
                'instructions' => 'Please select all the modules that you would like to show on the homepage',
                'required' => 1,
                'conditional_logic' => 0,
                'wrapper' => array(
                    'width' => '',
                    'class' => '',
                    'id' => '',
                ),
                'post_type' => array(
                    0 => 'hp_module',
                ),
                'taxonomy' => array(),
                'filters' => array(
                    0 => 'search',
                ),
                'elements' => '',
                'min' => '',
                'max' => '',
                'return_format' => 'object',
            ),
        ),
        'location' => array(
            array(
                array(
                    'param' => 'post_type',
                    'operator' => '==',
                    'value' => 'hp_collection',
                ),
            ),
        ),
        'menu_order' => 0,
        'position' => 'acf_after_title',
        'style' => 'default',
        'label_placement' => 'top',
        'instruction_placement' => 'label',
        'hide_on_screen' => array(
            0 => 'permalink',
            1 => 'the_content',
            2 => 'excerpt',
            3 => 'custom_fields',
            4 => 'discussion',
            5 => 'comments',
            6 => 'revisions',
            7 => 'slug',
            8 => 'author',
            9 => 'format',
            10 => 'page_attributes',
            11 => 'featured_image',
            12 => 'categories',
            13 => 'tags',
            14 => 'send-trackbacks',
        ),
        'active' => 1,
        'description' => '',
    ));
endif;
