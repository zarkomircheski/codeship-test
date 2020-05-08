<?php
if (function_exists('acf_add_local_field_group')) :
    acf_add_local_field_group(array(
    'key' => 'group_5806f0d6eebaa',
    'title' => 'Post Video',
    'fields' => array(
        array(
            'key' => 'field_5806f0e837c1f',
            'label' => 'Video Url',
            'name' => 'video_url',
            'type' => 'oembed',
            'instructions' => 'Enter youtube link',
            'required' => 0,
            'conditional_logic' => 0,
            'wrapper' => array(
                'width' => '',
                'class' => '',
                'id' => '',
            ),
            'width' => '',
            'height' => '',
        ),
    ),
    'location' => array(
        array(
            array(
                'param' => 'post_format',
                'operator' => '==',
                'value' => 'video',
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
