<?php
if (function_exists('acf_add_local_field_group')) :
    acf_add_local_field_group(array(
        'key' => 'group_amazon_product_updater_settings',
        'title' => 'Amazon product updater settings',
        'fields' => array(
            array(
                'key' => 'field_enable_daily_update',
                'label' => 'Targeted daily product updates enabled',
                'name' => 'enable_daily_update',
                'type' => 'true_false',
                'instructions' => 'This enables the cron that updates products not updated in the last 24 hours if viewed by a user.',
                'required' => 0,
                'conditional_logic' => 0,
                'wrapper' => array(
                    'width' => '',
                    'class' => '',
                    'id' => '',
                ),
                'message' => '',
                'default_value' => 0,
                'ui' => 1,
                'ui_on_text' => '',
                'ui_off_text' => '',
            ),
            array(
                'key' => 'field_enable_weekly_update',
                'label' => 'Weekly product updates enabled',
                'name' => 'enable_weekly_update',
                'type' => 'true_false',
                'instructions' => 'This enables the cron that updates products not updated in the last week.',
                'required' => 0,
                'conditional_logic' => 0,
                'wrapper' => array(
                    'width' => '',
                    'class' => '',
                    'id' => '',
                ),
                'message' => '',
                'default_value' => 0,
                'ui' => 1,
                'ui_on_text' => '',
                'ui_off_text' => '',
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
        'label_placement' => 'left',
        'instruction_placement' => 'label',
        'hide_on_screen' => '',
        'active' => 1,
        'description' => '',
    ));
endif;
