<?php
if (function_exists('acf_add_local_field_group')) :
    acf_add_local_field_group(array(
        'key' => 'group_5a9589e7646ea',
        'title' => 'Ages',
        'fields' => array(
            array(
                'key' => 'field_5a958b5cde304',
                'label' => 'Applicable age range for this article',
                'name' => '',
                'type' => 'message',
                'instructions' => '',
                'required' => 0,
                'conditional_logic' => 0,
                'wrapper' => array(
                    'width' => '',
                    'class' => '',
                    'id' => '',
                ),
                'message' => 'Use the sliders below to select the age range that this article is applicable to. If it\'s not applicable to any age range then select the n/a checkbox.',
                'new_lines' => 'wpautop',
                'esc_html' => 0,
            ),
            array(
                'key' => 'field_5a9589f4a8d5c',
                'label' => 'Ages From',
                'name' => 'age_range_from',
                'type' => 'range',
                'instructions' => '',
                'required' => 0,
                'conditional_logic' => array(
                    array(
                        array(
                            'field' => 'field_5a958a57a8d5d',
                            'operator' => '!=',
                            'value' => '1',
                        ),
                    ),
                ),
                'wrapper' => array(
                    'width' => '',
                    'class' => '',
                    'id' => '',
                ),
                'default_value' => '-2',
                'min' => '-2',
                'max' => '18',
                'step' => '',
                'prepend' => '',
                'append' => '',
            ),
            array(
                'key' => 'field_5a958b38de303',
                'label' => 'Ages to',
                'name' => 'age_range_to',
                'type' => 'range',
                'instructions' => '',
                'required' => 0,
                'conditional_logic' => array(
                    array(
                        array(
                            'field' => 'field_5a958a57a8d5d',
                            'operator' => '!=',
                            'value' => '1',
                        ),
                    ),
                ),
                'wrapper' => array(
                    'width' => '',
                    'class' => '',
                    'id' => '',
                ),
                'default_value' => '-2',
                'min' => '-2',
                'max' => '18',
                'step' => '',
                'prepend' => '',
                'append' => '',
            ),
            array(
                'key' => 'field_5a958a57a8d5d',
                'label' => 'N/A',
                'name' => 'not_applicable',
                'type' => 'true_false',
                'instructions' => 'If this post does not apply to an age range then check this box.',
                'required' => 0,
                'conditional_logic' => 0,
                'wrapper' => array(
                    'width' => '',
                    'class' => '',
                    'id' => '',
                ),
                'message' => '',
                'default_value' => 0,
                'ui' => 0,
                'ui_on_text' => '',
                'ui_off_text' => '',
            ),
            array(
                'key' => 'field_5ab3d269d1c49',
                'label' => 'Stages Key',
                'name' => '',
                'type' => 'message',
                'instructions' => 'Below are the age ranges for each stage',
                'required' => 0,
                'conditional_logic' => 0,
                'wrapper' => array(
                    'width' => '',
                    'class' => '',
                    'id' => '',
                ),
                'message' => '<ul>
                    <li><strong>Trying & Expecting:</strong> <code>-1</code></li>
                    <li><strong>Baby:</strong><code>0</code></li>
                    <li><strong>Toddlers:</strong> <code>1</code> - <code>2</code></li>
                    <li><strong>Preschool:</strong> <code>3</code> - <code>4</code></li>
                    <li><strong>Big Kids:</strong><code>5</code> - <code>8</code></li>
                    <li><strong>Tween Teen:</strong><code>9</code> - <code>17</code></li>
                    </ul>',
                'new_lines' => 'wpautop',
                'esc_html' => 0,
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
        'menu_order' => 1,
        'position' => 'side',
        'style' => 'default',
        'label_placement' => 'top',
        'instruction_placement' => 'label',
        'hide_on_screen' => '',
        'active' => 1,
        'description' => '',
    ));
endif;
