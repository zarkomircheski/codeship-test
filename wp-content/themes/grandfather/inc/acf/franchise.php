<?php
if (function_exists('acf_add_local_field_group')) :
    acf_add_local_field_group(array(
        'key' => 'group_editorial_franchise',
        'title' => 'Editorial Franchise',
        'fields' => array(
            array(
                'key' => 'field_franchise',
                'label' => 'Is Franchise',
                'name' => 'is_franchise',
                'type' => 'true_false',
                'instructions' => 'This tag is an Editoral Franchise',
                'required' => 0,
                'conditional_logic' => 0,
                'wrapper' => array(
                    'width' => '',
                    'class' => '',
                    'id' => '',
                ),
                'taxonomy' => array(),
                'allow_null' => 1,
                'multiple' => 0,
                'return_format' => 'object',
                'ui' => 1,
            ),
            array(
                'key' => 'field_franchise_custom_title',
                'label' => 'Custom Title?',
                'name' => 'franchise_custom_title',
                'type' => 'true_false',
                'instructions' => 'Would you like to show a custom title for this tag?',
                'required' => 0,
                'conditional_logic' => array(
                    array(
                        array(
                            'field' => 'field_franchise',
                            'operator' => '==',
                            'value' => '1',
                        )
                    )
                ),
                'wrapper' => array(
                    'width' => '',
                    'class' => '',
                    'id' => '',
                ),
                'taxonomy' => array(),
                'allow_null' => 1,
                'multiple' => 0,
                'return_format' => 'object',
                'ui' => 1,
            ),
            array(
                'key' => 'field_franchise_tag_title_text',
                'name' => 'franchise_tag_title_text',
                'label' => 'Title Text',
                'type' => 'text',
                'instructions' => 'Please provide the text you would like to use as the title on this tag',
                'required' => 1,
                'wrapper' => array(
                    'width' => '',
                    'class' => '',
                    'id' => '',
                ),
                'conditional_logic' => array(
                    array(
                        array(
                            'field' => 'field_franchise_custom_title',
                            'operator' => '==',
                            'value' => '1',
                        ),
                    ),
                ),


            ),
            array(
                'key' => 'field_franchise_tag_banner_image',
                'name' => 'franchise_tag_banner_image',
                'label' => 'Banner Image',
                'type' => 'image',
                'instructions' => 'Please provide the image you would like to use as a banner on this tag',
                'required' => 1,
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
                'conditional_logic' => array(
                    array(
                        array(
                            'field' => 'field_franchise',
                            'operator' => '==',
                            'value' => '1',
                        )
                    )
                )


            )
        ),
        'location' => array(
            array(
                array(
                    'param' => 'taxonomy',
                    'operator' => '==',
                    'value' => 'post_tag',
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
