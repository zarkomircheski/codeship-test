<?php
if (function_exists('acf_add_local_field_group')) :
    acf_add_local_field_group(array(
        'key' => 'group_5a04a6d2dbf14',
        'title' => 'Sweepstakes',
        'fields' => array(
            array(
                'key' => 'field_5a04a71a2b308',
                'label' => 'Sweepstakes Image',
                'name' => 'sweepstakes_image',
                'type' => 'image',
                'value' => null,
                'instructions' => 'This is the image that will be used as the background for the sweepstakes page.',
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
            array(
                'key' => 'field_5a09a217a0100',
                'label' => 'Custom Title',
                'name' => 'custom_title',
                'type' => 'text',
                'value' => null,
                'instructions' => 'If you would like a custom title to display overtop the image then please provide that text here.',
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
                'key' => 'field_5a09978bced1c',
                'label' => 'Dojo Mojo Campaign ID #2',
                'name' => 'dojo_mojo_embed_id_2',
                'type' => 'text',
                'value' => null,
                'instructions' => 'When looking at the export code from DojoMojo you should see a line that looks like the following. 

{ iframe.src = \'//www.dojomojo.ninja/landing/campaign/40ec868d-0cda-4c03-a429-84a7b5608f02\'; }

The ID in this instance is 40ec868d-0cda-4c03-a429-84a7b5608f02. Please put the id from your export here.',
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
        ),
        'location' => array(
            array(
                array(
                    'param' => 'post_type',
                    'operator' => '==',
                    'value' => 'sweepstakes',
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
