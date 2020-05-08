<?php
if (function_exists('acf_add_local_field_group')) :
    acf_add_local_field_group(array(
        'key' => 'group_59b918a8d776c',
        'title' => 'Image CDN Settings',
        'fields' => array(
            array(
                'key' => 'field_59b918ed0b306',
                'label' => 'Image CDN Enabled',
                'name' => 'imgix_enabled',
                'type' => 'true_false',
                'instructions' => 'Uncheck to disable the image CDN and serve all images from the filesystem',
                'required' => 0,
                'conditional_logic' => 0,
                'wrapper' => array(
                    'width' => '',
                    'class' => '',
                    'id' => '',
                ),
                'message' => '',
                'default_value' => 1,
            ),

            array(
                'key' => 'field_image_cdn_domain',
                'label' => 'Image CDN Domain',
                'name' => 'image_cdn_domain',
                'type' => 'text',
                'instructions' => 'The domain to use for our image CDN',
                'required' => 0,
                'conditional_logic' => 0,
                'wrapper' => array(
                    'width' => '',
                    'class' => '',
                    'id' => '',
                ),
                'message' => '',

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
