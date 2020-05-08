<?php
if (function_exists('acf_add_local_field_group')) :
    acf_add_local_field_group(array(
        'key' => 'group_5a4b8d85e04fa',
        'title' => 'Homepage Modules',
        'fields' => array(
            array(
                'key' => 'field_5a4b8db4a8ed3',
                'label' => 'Module Type',
                'name' => 'module_type',
                'type' => 'select',
                'instructions' => 'Is this module going to be a	Callout or a Section?',
                'required' => 1,
                'conditional_logic' => 0,
                'wrapper' => array(
                    'width' => '',
                    'class' => '',
                    'id' => '',
                ),
                'choices' => array(
                    'callout' => 'Callout',
                    'section' => 'Section',
                ),
                'default_value' => array(),
                'allow_null' => 0,
                'multiple' => 0,
                'ui' => 0,
                'ajax' => 1,
                'return_format' => 'value',
                'placeholder' => '',
            ),
            array(
                'key' => 'field_5a4b90d813dd9',
                'label' => 'Callout Type',
                'name' => 'callout_type',
                'type' => 'select',
                'instructions' => 'Which type of callout are you creating?',
                'required' => 1,
                'conditional_logic' => array(
                    array(
                        array(
                            'field' => 'field_5a4b8db4a8ed3',
                            'operator' => '==',
                            'value' => 'callout',
                        ),
                    ),
                ),
                'wrapper' => array(
                    'width' => '',
                    'class' => '',
                    'id' => '',
                ),
                'choices' => array(
                    'ad' => 'Ad',
                    'arrow_callout' => 'Text Callout with Arrows',
                    'in_article_0' => 'In Article 0',
                    'banner_image' => 'Banner Image',
                    'data_collection' => 'Data Collection',
                    'experts' => 'Experts',
                    'faq' => 'FAQ',
                    'franchise_tag_banner' => 'Franchise Tag Banner',
                    'free_form' => 'Free Form',
                    'glossary' => 'Glossary',
                    'hero' => 'Hero',
                    'in_article_ad' => 'In Article Ad',
                    'in_article_amp_0' => 'In Article AMP 0',
                    'in_article_amp_1' => 'In Article AMP 1',
                    'in_article_amp_ad' => 'In Article AMP Ad',
                    'intro_text' => 'Introductory Text',
                    'info_graphic' => 'Info Graphic',
                    'in_article_newsletter' => 'In Article Newsletter',
                    'lead_with_video' => 'Lead With Video',
                    'mens_dove_widget' => 'Mens Dove Widget',
                    'more_from' => 'More From',
                    'navigation' => 'Navigation',
                    'newsletter_signup' => 'Newsletter Signup',
                    'post_highlight' => 'Post Highlight',
                    'recirculation' => 'Recirculation',
                    'related_posts' => 'Related Posts',
                    'search_form' => 'Search Form',
                    'sponsor_highlight' => 'Sponsor Highlight',
                    'title' => 'Title',
                    'text_overlay' => 'Text Overlaying Image',
                    'video_highlight' => 'Video Highlight'
                ),
                'default_value' => array(),
                'allow_null' => 0,
                'multiple' => 0,
                'ui' => 0,
                'ajax' => 1,
                'return_format' => 'value',
                'placeholder' => '',
            ),
            array(
                'key' => 'field_5a4b912b688fa',
                'label' => 'Section Type',
                'name' => 'section_type',
                'type' => 'select',
                'instructions' => 'Which type of section would you like to create?',
                'required' => 1,
                'conditional_logic' => array(
                    array(
                        array(
                            'field' => 'field_5a4b8db4a8ed3',
                            'operator' => '==',
                            'value' => 'section',
                        ),
                    ),
                ),
                'wrapper' => array(
                    'width' => '',
                    'class' => '',
                    'id' => '',
                ),
                'choices' => array(
                    'content_hub' => 'Content Hub',
                    'sponsored_content_hub' => 'Sponsored Content Hub',
                    'post_listing' => 'Post Listing',
                    'custom_sponsored_content_hub' => 'Custom Sponsored Content Hub',
                    'secondary_videos' => 'Secondary Videos'
                ),
                'default_value' => array(),
                'allow_null' => 0,
                'multiple' => 0,
                'ui' => 0,
                'ajax' => 1,
                'return_format' => 'value',
                'placeholder' => '',
            ),
            array(
                'key' => 'field_more_from_settings',
                'label' => 'More From Settings',
                'name' => 'more_from_settings',
                'type' => 'group',
                'instructions' => 'Use the fields below to configure the More From module to your liking.',
                'required' => 0,
                'conditional_logic' => array(
                    array(
                        array(
                            'field' => 'field_5a4b90d813dd9',
                            'operator' => '==',
                            'value' => 'more_from',
                        ),
                        array(
                            'field' => 'field_5a4b8db4a8ed3',
                            'operator' => '==',
                            'value' => 'callout',
                        ),
                    ),
                ),
                'wrapper' => array(
                    'width' => '',
                    'class' => '',
                    'id' => '',
                ),
                'layout' => 'block',
            ),
            array(
                'key' => 'field_custom_sponsored_content_hub_settings',
                'label' => 'Sponsored Posts',
                'name' => 'custom_sponsored_content_hub_settings',
                'type' => 'repeater',
                'instructions' => '',
                'required' => 0,
                'conditional_logic' => array(
                    array(
                        array(
                            'field' => 'field_5a4b912b688fa',
                            'operator' => '==',
                            'value' => 'custom_sponsored_content_hub',
                        ),
                        array(
                            'field' => 'field_5a4b8db4a8ed3',
                            'operator' => '==',
                            'value' => 'section',
                        ),
                    ),
                ),
                'wrapper' => array(
                    'width' => '',
                    'class' => '',
                    'id' => '',
                ),
                'collapsed' => '',
                'min' => 2,
                'max' => 4,
                'layout' => 'table',
                'button_label' => 'Add Post',
                'sub_fields' => array(),
            ),
            array(
                'key' => 'field_hero_settings',
                'label' => 'Hero Settings',
                'name' => 'hero_settings',
                'type' => 'group',
                'instructions' => 'Use the fields below to configure the Hero module to your liking.',
                'required' => 0,
                'conditional_logic' => array(
                    array(
                        array(
                            'field' => 'field_5a4b90d813dd9',
                            'operator' => '==',
                            'value' => 'hero',
                        ),
                        array(
                            'field' => 'field_5a4b8db4a8ed3',
                            'operator' => '==',
                            'value' => 'callout',
                        ),
                    ),
                ),
                'wrapper' => array(
                    'width' => '',
                    'class' => '',
                    'id' => '',
                ),
                'layout' => 'block',
            ),
            array(
                'key' => 'field_7a4b92313cd08',
                'label' => 'Navigation Settings',
                'name' => 'navigation_settings',
                'type' => 'group',
                'instructions' => 'Use the fields below to configure the navigation module to your liking.',
                'required' => 0,
                'conditional_logic' => array(
                    array(
                        array(
                            'field' => 'field_5a4b90d813dd9',
                            'operator' => '==',
                            'value' => 'navigation',
                        ),
                        array(
                            'field' => 'field_5a4b8db4a8ed3',
                            'operator' => '==',
                            'value' => 'callout',
                        ),
                    ),
                ),
                'wrapper' => array(
                    'width' => '',
                    'class' => '',
                    'id' => '',
                ),
                'layout' => 'block',
                'sub_fields' => array(
                    array(
                        'key' => 'field_6a4b87343cd0d',
                        'label' => 'Which display variant would you like to use for this module?',
                        'name' => 'variant',
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
                            'header_nav' => 'Homepage Nav',
                            'sponsored_nav' => 'Sponsored Nav',
                            'article_nav' => 'Article Nav',
                        ),
                        'default_value' => array(),
                        'allow_null' => 0,
                        'multiple' => 0,
                        'ui' => 0,
                        'ajax' => 1,
                        'return_format' => 'value',
                        'placeholder' => '',
                    ),
                ),
            ),
            array(
                'key' => 'field_in_article_newsletter_settings',
                'label' => 'In Article Newsletter Settings',
                'name' => 'in_article_newsletter_settings',
                'type' => 'group',
                'instructions' => 'Use the fields below to configure the in article newsletter module to your liking.',
                'required' => 0,
                'conditional_logic' => array(
                    array(
                        array(
                            'field' => 'field_5a4b90d813dd9',
                            'operator' => '==',
                            'value' => 'in_article_newsletter',
                        ),
                        array(
                            'field' => 'field_5a4b8db4a8ed3',
                            'operator' => '==',
                            'value' => 'callout',
                        ),
                    ),
                ),
                'wrapper' => array(
                    'width' => '',
                    'class' => '',
                    'id' => '',
                ),
                'layout' => 'block',
                'sub_fields' => array(
                    array(
                        'key' => 'field_in_article_newsletter_cta',
                        'label' => 'Newsletter CTA',
                        'name' => 'newsletter_cta',
                        'type' => 'text',
                        'instructions' => '',
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
                        'key' => 'field_in_article_newsletter_specify_list',
                        'label' => 'Would you like to specify a list for this newsletter to subscribe users to?',
                        'name' => 'specify_list',
                        'type' => 'radio',
                        'instructions' => '',
                        'required' => 1,
                        'conditional_logic' => 0,
                        'wrapper' => array(
                            'width' => '',
                            'class' => '',
                            'id' => '',
                        ),
                        'choices' => array(
                            'yes' => 'Yes',
                            'no' => 'No',
                        ),
                        'allow_null' => 0,
                        'other_choice' => 0,
                        'save_other_choice' => 0,
                        'default_value' => 'no',
                        'layout' => 'vertical',
                        'return_format' => 'value',
                    ),
                    array(
                        'key' => 'field_specify_list_id',
                        'label' => 'Newsletter List ID',
                        'name' => 'list_id',
                        'type' => 'number',
                        'instructions' => '',
                        'required' => 0,
                        'conditional_logic' => array(
                            array(
                                array(
                                    'field' => 'field_in_article_newsletter_specify_list',
                                    'operator' => '==',
                                    'value' => 'yes',
                                ),
                            ),
                        ),
                        'wrapper' => array(
                            'width' => '',
                            'class' => '',
                            'id' => '',
                        ),
                        'layout' => 'block',
                    ),
                ),
            ),
            array(
                'key' => 'field_5a4b92313cd08',
                'label' => 'Post Highlight Settings',
                'name' => 'post_highlight_settings',
                'type' => 'group',
                'instructions' => 'Use the fields below to configure the post highlight module to your liking.',
                'required' => 1,
                'conditional_logic' => array(
                    array(
                        array(
                            'field' => 'field_5a4b90d813dd9',
                            'operator' => '==',
                            'value' => 'post_highlight',
                        ),
                        array(
                            'field' => 'field_5a4b8db4a8ed3',
                            'operator' => '==',
                            'value' => 'callout',
                        ),
                    ),
                ),
                'wrapper' => array(
                    'width' => '',
                    'class' => '',
                    'id' => '',
                ),
                'layout' => 'block',
                'sub_fields' => array(
                    array(
                        'key' => 'field_5a4b95773cd09',
                        'label' => 'Would you like to display a title on this callout?',
                        'name' => 'custom_title_enabled',
                        'type' => 'radio',
                        'instructions' => '',
                        'required' => 1,
                        'conditional_logic' => 0,
                        'wrapper' => array(
                            'width' => '',
                            'class' => '',
                            'id' => '',
                        ),
                        'choices' => array(
                            'yes' => 'Yes',
                            'no' => 'No',
                        ),
                        'allow_null' => 0,
                        'other_choice' => 0,
                        'save_other_choice' => 0,
                        'default_value' => 'no',
                        'layout' => 'vertical',
                        'return_format' => 'value',
                    ),
                    array(
                        'key' => 'field_5a4b96a63cd0a',
                        'label' => 'Title',
                        'name' => 'custom_title',
                        'type' => 'text',
                        'instructions' => '',
                        'required' => 1,
                        'conditional_logic' => array(
                            array(
                                array(
                                    'field' => 'field_5a4b95773cd09',
                                    'operator' => '==',
                                    'value' => 'yes',
                                ),
                            ),
                        ),
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
                        'key' => 'field_5a4b95883cd09',
                        'label' => 'Would you like to display a quote above the post title on this callout?',
                        'name' => 'custom_quote_enabled',
                        'type' => 'radio',
                        'instructions' => '',
                        'required' => 1,
                        'conditional_logic' => 0,
                        'wrapper' => array(
                            'width' => '',
                            'class' => '',
                            'id' => '',
                        ),
                        'choices' => array(
                            'yes' => 'Yes',
                            'no' => 'No',
                        ),
                        'allow_null' => 0,
                        'other_choice' => 0,
                        'save_other_choice' => 0,
                        'default_value' => 'no',
                        'layout' => 'vertical',
                        'return_format' => 'value',
                    ),
                    array(
                        'key' => 'field_5a4b96a63ca0c',
                        'label' => 'Quote Text',
                        'name' => 'custom_quote',
                        'type' => 'text',
                        'instructions' => '',
                        'required' => 1,
                        'conditional_logic' => array(
                            array(
                                array(
                                    'field' => 'field_5a4b95883cd09',
                                    'operator' => '==',
                                    'value' => 'yes',
                                ),
                            ),
                        ),
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
                        'key' => 'field_5a4b96cb3cd0b',
                        'label' => 'Which post would you like to highlight?',
                        'name' => 'highlighted_post',
                        'type' => 'post_object',
                        'instructions' => '',
                        'required' => 1,
                        'conditional_logic' => 0,
                        'wrapper' => array(
                            'width' => '',
                            'class' => '',
                            'id' => '',
                        ),
                        'post_type' => array(
                            0 => 'page',
                            1 => 'post'
                        ),
                        'taxonomy' => array(),
                        'allow_null' => 0,
                        'multiple' => 0,
                        'return_format' => 'object',
                        'ui' => 1,
                    ),
                    array(
                        'key' => 'field_5a4b97343cd0d',
                        'label' => 'Which display variant would you like to use for this module?',
                        'name' => 'variant',
                        'type' => 'select',
                        'instructions' => 'To see what the different variations look like click <a target="_blank" href="https://docs.google.com/document/d/1WmdXfhN0__C64Nw7wtJZ-MbumwhuWr4L-gnROfiyb3A/edit?usp=sharing">here</a>',
                        'required' => 1,
                        'conditional_logic' => 0,
                        'wrapper' => array(
                            'width' => '',
                            'class' => '',
                            'id' => '',
                        ),
                        'choices' => array(
                            'variant_1' => 'Variant 1',
                            'variant_2' => 'Variant 2',
                            'variant_3' => 'Variant 3',
                            'variant_4' => 'Variant 4',
                            'variant_5' => 'Variant 5',
                        ),
                        'default_value' => array(),
                        'allow_null' => 0,
                        'multiple' => 0,
                        'ui' => 0,
                        'ajax' => 1,
                        'return_format' => 'value',
                        'placeholder' => '',
                    ),
                ),
            ),
            array(
                'key' => 'video_highlight_settings',
                'label' => 'Video Highlight Settings',
                'name' => 'video_highlight_settings',
                'type' => 'group',
                'instructions' => 'Use the fields below to configure the video highlight module to your liking.',
                'required' => 1,
                'conditional_logic' => array(
                    array(
                        array(
                            'field' => 'field_5a4b90d813dd9',
                            'operator' => '==',
                            'value' => 'video_highlight',
                        ),
                        array(
                            'field' => 'field_5a4b8db4a8ed3',
                            'operator' => '==',
                            'value' => 'callout',
                        ),
                    ),
                ),
                'wrapper' => array(
                    'width' => '',
                    'class' => '',
                    'id' => '',
                ),
                'layout' => 'block',
                'sub_fields' => array(
                    array(
                        'key' => 'video_highlight_module_title_enable',
                        'label' => 'Would you like to display a title?',
                        'name' => 'video_highlight_module_title_enable',
                        'type' => 'radio',
                        'instructions' => '',
                        'required' => 1,
                        'conditional_logic' => 0,
                        'wrapper' => array(
                            'width' => '',
                            'class' => '',
                            'id' => '',
                        ),
                        'choices' => array(
                            'yes' => 'Yes',
                            'no' => 'No',
                        ),
                        'allow_null' => 0,
                        'other_choice' => 0,
                        'save_other_choice' => 0,
                        'default_value' => 'no',
                        'layout' => 'vertical',
                        'return_format' => 'value',
                    ),
                    array(
                        'key' => 'video_highlight_module_title',
                        'label' => 'Title',
                        'name' => 'custom_title',
                        'type' => 'text',
                        'instructions' => '',
                        'required' => 0,
                        'conditional_logic' => array(
                            array(
                                array(
                                    'field' => 'video_highlight_module_title_enable',
                                    'operator' => '==',
                                    'value' => 'yes',
                                ),
                            ),
                        ),
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
                        'key' => 'video_highlight_post',
                        'label' => 'Which video would you like to highlight?',
                        'name' => 'highlighted_post',
                        'type' => 'post_object',
                        'instructions' => '',
                        'required' => 1,
                        'conditional_logic' => 0,
                        'wrapper' => array(
                            'width' => '',
                            'class' => '',
                            'id' => '',
                        ),
                        'post_type' => array(
                            0 => 'page',
                            1 => 'post'
                        ),
                        'taxonomy' => array(),
                        'allow_null' => 0,
                        'multiple' => 0,
                        'return_format' => 'object',
                        'ui' => 1,
                    ),
                ),
            ),
            array(
                'key' => 'field_secondary_videos_settings',
                'label' => 'Secondary Videos',
                'name' => 'secondary_videos_settings',
                'type' => 'group',
                'instructions' => 'Use the fields below to configure the Secondary Videos module to your liking.',
                'required' => 0,
                'conditional_logic' => array(
                    array(
                        array(
                            'field' => 'field_5a4b912b688fa',
                            'operator' => '==',
                            'value' => 'secondary_videos',
                        ),
                        array(
                            'field' => 'field_5a4b8db4a8ed3',
                            'operator' => '==',
                            'value' => 'section',
                        ),
                    ),
                ),
                'wrapper' => array(
                    'width' => '',
                    'class' => '',
                    'id' => '',
                ),
                'layout' => 'block',
            ),
            array(
                'key' => 'field_5a4b9ae245505',
                'label' => 'Arrow Callout Settings',
                'name' => 'arrow_callout_settings',
                'type' => 'group',
                'instructions' => 'Use the fields below to configure the arrow callout module to your liking.',
                'required' => 1,
                'conditional_logic' => array(
                    array(
                        array(
                            'field' => 'field_5a4b90d813dd9',
                            'operator' => '==',
                            'value' => 'arrow_callout',
                        ),
                        array(
                            'field' => 'field_5a4b8db4a8ed3',
                            'operator' => '==',
                            'value' => 'callout',
                        ),
                    ),
                ),
                'wrapper' => array(
                    'width' => '',
                    'class' => '',
                    'id' => '',
                ),
                'layout' => 'block',
                'sub_fields' => array(
                    array(
                        'key' => 'field_5a4b95773cd09asdfasdf',
                        'label' => 'Do you want to display the Gray Arrows on the side of the text?',
                        'name' => 'show_arrows',
                        'type' => 'radio',
                        'instructions' => '',
                        'required' => 1,
                        'conditional_logic' => 0,
                        'wrapper' => array(
                            'width' => '',
                            'class' => '',
                            'id' => '',
                        ),
                        'choices' => array(
                            'true' => 'Yes',
                            'false' => 'No',
                        ),
                        'allow_null' => 0,
                        'other_choice' => 0,
                        'save_other_choice' => 0,
                        'default_value' => 'true',
                        'layout' => 'vertical',
                        'return_format' => 'value',
                    ),
                    array(
                        'key' => 'field_5a4b9b5645506',
                        'label' => 'Message',
                        'name' => 'message',
                        'type' => 'wysiwyg',
                        'instructions' => 'You can use the WYSIWYG here to provide the content for your module.',
                        'required' => 1,
                        'conditional_logic' => 0,
                        'wrapper' => array(
                            'width' => '',
                            'class' => '',
                            'id' => '',
                        ),
                        'default_value' => '',
                        'tabs' => 'visual',
                        'toolbar' => 'basic',
                        'media_upload' => 0,
                        'delay' => 1,
                    )
                ),
            ),
            array(
                'key' => 'field_5a4b9ae345872345-title',
                'label' => 'Title Settings',
                'name' => 'title_settings',
                'type' => 'group',
                'instructions' => 'Use the fields below to configure the title module to your liking.',
                'required' => 1,
                'conditional_logic' => array(
                    array(
                        array(
                            'field' => 'field_5a4b90d813dd9',
                            'operator' => '==',
                            'value' => 'title',
                        ),
                        array(
                            'field' => 'field_5a4b8db4a8ed3',
                            'operator' => '==',
                            'value' => 'callout',
                        ),
                    ),
                ),
                'wrapper' => array(
                    'width' => '',
                    'class' => '',
                    'id' => '',
                ),
                'layout' => 'block',
                'sub_fields' => array(
                    array(
                        'key' => 'field_5a4bda83a438dh-title',
                        'label' => 'Title',
                        'name' => 'title',
                        'type' => 'text',
                        'instructions' => '',
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

                        'key' => 'field_title_module_sponsor_settings',
                        'label' => 'Sponsored Settings',
                        'name' => 'sponsored_settings',
                        'type' => 'group',
                        'instructions' => '',
                        'required' => 1,
                        'conditional_logic' => array(
                            array(
                                array(
                                    'field' => 'field_title_module_sponsored',
                                    'operator' => '==',
                                    'value' => 'yes',
                                ),
                            ),
                        ),
                        'wrapper' => array(
                            'width' => '',
                            'class' => '',
                            'id' => '',
                        ),
                        'layout' => 'block',
                        'sub_fields' => array(
                            array(
                                'key' => 'field_title_module_sponsor_settings_logo',
                                'label' => 'Sponsor Logo',
                                'name' => 'sponsor_logo',
                                'type' => 'image',
                                'instructions' => '',
                                'required' => 1,
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
                                'key' => 'field_title_module_sponsor_settings_link',
                                'label' => 'Sponsor Link',
                                'name' => 'sponsor_link',
                                'type' => 'text',
                                'instructions' => '',
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
                        ),
                    ),
                ),
            ),
            array(
                'key' => 'field_5a4b9c5545507',
                'label' => 'Newsletter Signup Settings',
                'name' => 'newsletter_signup_settings',
                'type' => 'group',
                'instructions' => 'Use the fields below to configure the newsletter signup module. Both variants can be used in collections, only variant 1 can be used in articles',
                'required' => 1,
                'conditional_logic' => array(
                    array(
                        array(
                            'field' => 'field_5a4b90d813dd9',
                            'operator' => '==',
                            'value' => 'newsletter_signup',
                        ),
                        array(
                            'field' => 'field_5a4b8db4a8ed3',
                            'operator' => '==',
                            'value' => 'callout',
                        ),
                    ),
                ),
                'wrapper' => array(
                    'width' => '',
                    'class' => '',
                    'id' => '',
                ),
                'layout' => 'block',
                'sub_fields' => array(
                    array(
                        'key' => 'field_5a4b9cc845508',
                        'label' => 'Which display variant would you like to use for this module?',
                        'name' => 'variant',
                        'type' => 'select',
                        'instructions' => 'To see what the different variations look like click <a target="_blank" href="https://docs.google.com/document/d/1WIdQvhvxuOYbL5NzFhNqAYxZEcnDyCCq3ohUI4RTDmY/edit?usp=sharing">here</a>',
                        'required' => 1,
                        'conditional_logic' => 0,
                        'wrapper' => array(
                            'width' => '',
                            'class' => '',
                            'id' => '',
                        ),
                        'choices' => array(
                            'variant_1' => 'Variant 1',
                            'variant_2' => 'Variant 2',
                        ),
                        'default_value' => array(),
                        'allow_null' => 0,
                        'multiple' => 0,
                        'ui' => 0,
                        'ajax' => 1,
                        'return_format' => 'value',
                        'placeholder' => '',
                    ),
                    array(
                        'key' => 'field_5a4b9df345509',
                        'label' => 'Content',
                        'name' => 'message',
                        'type' => 'wysiwyg',
                        'instructions' => 'Use the field below to input the content that you would like to show in this module.',
                        'required' => 0,
                        'conditional_logic' => 0,
                        'wrapper' => array(
                            'width' => '',
                            'class' => '',
                            'id' => '',
                        ),
                        'default_value' => '',
                        'tabs' => 'visual',
                        'toolbar' => 'basic',
                        'media_upload' => 0,
                        'delay' => 0,
                    ),
                    array(
                        'key' => 'field_5a4ba18d4550a',
                        'label' => 'Image',
                        'name' => 'image',
                        'type' => 'image',
                        'instructions' => 'If you selected variant 1 then this image will be displayed to the right of the content. 
If you selected variant 2 then this image will be used as the background image for the content.',
                        'required' => 1,
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
            array(
                'key' => 'field_6a5b9c5845507',
                'label' => 'Banner Image Settings',
                'name' => 'banner_image_settings',
                'type' => 'group',
                'instructions' => '',
                'required' => 0,
                'conditional_logic' => array(
                    array(
                        array(
                            'field' => 'field_5a4b90d813dd9',
                            'operator' => '==',
                            'value' => 'banner_image',
                        ),
                        array(
                            'field' => 'field_5a4b8db4a8ed3',
                            'operator' => '==',
                            'value' => 'callout',
                        ),
                    ),
                ),
                'wrapper' => array(
                    'width' => '',
                    'class' => '',
                    'id' => '',
                ),
                'layout' => 'block',
                'sub_fields' => array(
                    array(
                        'key' => 'field_8a4ba13d4550a',
                        'label' => 'Image',
                        'name' => 'image',
                        'type' => 'image',
                        'instructions' => '',
                        'required' => 1,
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
                        'key' => 'field_5a4bd8f6b479f',
                        'label' => 'Banner Image Link',
                        'name' => 'banner_image_link',
                        'type' => 'link',
                        'instructions' => '',
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
                ),
            ),
            array(
                'key' => 'field_5a4bce551583c',
                'label' => 'Content Hub Settings',
                'name' => 'content_hub_settings',
                'type' => 'group',
                'instructions' => '',
                'required' => 0,
                'conditional_logic' => array(
                    array(
                        array(
                            'field' => 'field_5a4b912b688fa',
                            'operator' => '==',
                            'value' => 'content_hub',
                        ),
                        array(
                            'field' => 'field_5a4b8db4a8ed3',
                            'operator' => '==',
                            'value' => 'section',
                        ),
                    ),
                ),
                'wrapper' => array(
                    'width' => '',
                    'class' => '',
                    'id' => '',
                ),
                'layout' => 'block',
                'sub_fields' => array(
                    array(

                        'key' => 'field_5a4bd423930c3',
                        'label' => 'Sponsored Settings',
                        'name' => 'sponsored_settings',
                        'type' => 'group',
                        'instructions' => '',
                        'required' => 1,
                        'conditional_logic' => array(
                            array(
                                array(
                                    'field' => 'field_5a4bd404930c2',
                                    'operator' => '==',
                                    'value' => 'yes',
                                ),
                            ),
                        ),
                        'wrapper' => array(
                            'width' => '',
                            'class' => '',
                            'id' => '',
                        ),
                        'layout' => 'block',
                        'sub_fields' => array(
                            array(
                                'key' => 'field_5a4bd448930c4',
                                'label' => 'Sponsor Name',
                                'name' => 'sponsor_name',
                                'type' => 'text',
                                'instructions' => '',
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
                                'key' => 'field_5a4bd454930c5',
                                'label' => 'Sponsor Logo',
                                'name' => 'sponsor_logo',
                                'type' => 'image',
                                'instructions' => '',
                                'required' => 1,
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
                                'key' => 'field_5a4bd466930c6',
                                'label' => 'Sponsor Link',
                                'name' => 'sponsor_link',
                                'type' => 'text',
                                'instructions' => '',
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
                        ),
                    ),
                    array(
                        'key' => 'field_5a4bd57e930c8',
                        'label' => 'Variant Settings',
                        'name' => 'variant_settings',
                        'type' => 'group',
                        'instructions' => 'Some variants have extra fields to customize you will see those below.',
                        'required' => 1,
                        'conditional_logic' => array(
                            array(
                                array(
                                    'field' => 'field_5a4bd4b9930c7',
                                    'operator' => '==',
                                    'value' => 'variant_2',
                                ),
                            ),
                            array(
                                array(
                                    'field' => 'field_5a4bd4b9930c7',
                                    'operator' => '==',
                                    'value' => 'variant_4',
                                ),
                            ),

                        ),
                        'wrapper' => array(
                            'width' => '',
                            'class' => '',
                            'id' => '',
                        ),
                        'layout' => 'block',
                        'sub_fields' => array(
                            array(
                                'key' => 'field_5a4bd5d2930c9',
                                'label' => 'Section Graphic',
                                'name' => 'section_graphic',
                                'type' => 'image',
                                'instructions' => '',
                                'required' => 1,
                                'conditional_logic' => array(
                                    array(
                                        array(
                                            'field' => 'field_5a4bd4b9930c7',
                                            'operator' => '==',
                                            'value' => 'variant_2',
                                        ),
                                    ),
                                ),
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
                                'key' => 'field_5a4bd5ed930ca',
                                'label' => 'Highlighted Posts',
                                'name' => 'highlighted_posts',
                                'type' => 'post_object',
                                'instructions' => 'please select up to <strong>3</strong> posts to highlight.',
                                'required' => 1,
                                'conditional_logic' => array(
                                    array(
                                        array(
                                            'field' => 'field_5a4bd4b9930c7',
                                            'operator' => '==',
                                            'value' => 'variant_4',
                                        ),
                                    ),
                                ),
                                'wrapper' => array(
                                    'width' => '',
                                    'class' => '',
                                    'id' => '',
                                ),
                                'post_type' => array(
                                    0 => 'page',
                                    1 => 'post'
                                ),
                                'taxonomy' => array(),
                                'allow_null' => 0,
                                'multiple' => 1,
                                'return_format' => 'object',
                                'ui' => 1,
                            ),
                        ),
                    ),
                ),
            ),
            array(
                'key' => 'field_5a4bd7f6b4797',
                'label' => 'Sponsored Content Hub Settings',
                'name' => 'sponsored_content_hub_settings',
                'type' => 'group',
                'instructions' => '',
                'required' => 0,
                'conditional_logic' => array(
                    array(
                        array(
                            'field' => 'field_5a4b912b688fa',
                            'operator' => '==',
                            'value' => 'sponsored_content_hub',
                        ),
                        array(
                            'field' => 'field_5a4b8db4a8ed3',
                            'operator' => '==',
                            'value' => 'section',
                        ),
                    ),
                    array(
                        array(
                            'field' => 'field_5a4b912b688fa',
                            'operator' => '==',
                            'value' => 'custom_sponsored_content_hub',
                        ),
                        array(
                            'field' => 'field_5a4b8db4a8ed3',
                            'operator' => '==',
                            'value' => 'section',
                        ),
                    ),
                ),
                'wrapper' => array(
                    'width' => '',
                    'class' => '',
                    'id' => '',
                ),
                'layout' => 'block',
                'sub_fields' => array(
                    array(
                        'key' => 'field_5a4bd8b8b4799',
                        'label' => 'Title',
                        'name' => 'title',
                        'type' => 'text',
                        'instructions' => '',
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
                        'key' => 'field_5a4bd8e1b479a',
                        'label' => 'Sponsor Image',
                        'name' => 'sponsor_image',
                        'type' => 'image',
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
                        'key' => 'field_5a4bd8f6b479b',
                        'label' => 'Sponsor Link',
                        'name' => 'sponsor_link',
                        'type' => 'url',
                        'instructions' => '',
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
                        'key' => 'field_5a4bd902b479c',
                        'label' => 'Posts',
                        'name' => 'posts',
                        'type' => 'relationship',
                        'instructions' => '',
                        'required' => 1,
                        'conditional_logic' => array(
                            array(
                                array(
                                    'field' => 'field_5a4b912b688fa',
                                    'operator' => '==',
                                    'value' => 'sponsored_content_hub',
                                ),
                                array(
                                    'field' => 'field_5a4b8db4a8ed3',
                                    'operator' => '==',
                                    'value' => 'section',
                                ),
                            ),
                        ),
                        'wrapper' => array(
                            'width' => '',
                            'class' => '',
                            'id' => '',
                        ),
                        'post_type' => array(
                            0 => 'page',
                            1 => 'post'
                        ),
                        'taxonomy' => array(),
                        'filters' => array(
                            0 => 'search',
                        ),
                        'elements' => array(
                            0 => 'featured_image',
                        ),
                        'min' => 3,
                        'max' => 3,
                        'return_format' => 'object',
                    ),
                ),
            ),
            array(
                'key' => 'field_5a4bd82ab4798',
                'label' => 'Post Listing Settings',
                'name' => 'post_listing_settings',
                'type' => 'group',
                'instructions' => '',
                'required' => 0,
                'conditional_logic' => array(
                    array(
                        array(
                            'field' => 'field_5a4b912b688fa',
                            'operator' => '==',
                            'value' => 'post_listing',
                        ),
                        array(
                            'field' => 'field_5a4b8db4a8ed3',
                            'operator' => '==',
                            'value' => 'section',
                        ),
                    ),
                ),
                'wrapper' => array(
                    'width' => '',
                    'class' => '',
                    'id' => '',
                ),
                'layout' => 'block',
                'sub_fields' => array(
                    array(
                        'key' => 'field_5a4bd9f00e3e5',
                        'label' => 'List Type',
                        'name' => 'variant',
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
                            'custom' => 'Custom',
                            'trending' => 'Trending',
                            'category' => 'Category Driven',
                        ),
                        'default_value' => array(),
                        'allow_null' => 0,
                        'multiple' => 0,
                        'ui' => 0,
                        'ajax' => 1,
                        'return_format' => 'value',
                        'placeholder' => '',
                    ),
                    array(
                        'key' => 'field_5a4bda460e3e6',
                        'label' => 'Override Title?',
                        'name' => 'override_title',
                        'type' => 'radio',
                        'instructions' => '',
                        'required' => 1,
                        'conditional_logic' => array(
                            array(
                                array(
                                    'field' => 'field_5a4bd9f00e3e5',
                                    'operator' => '!=',
                                    'value' => 'custom',
                                ),
                            ),
                        ),
                        'wrapper' => array(
                            'width' => '',
                            'class' => '',
                            'id' => '',
                        ),
                        'choices' => array(
                            'yes' => 'Yes',
                            'no' => 'No',
                        ),
                        'allow_null' => 0,
                        'other_choice' => 0,
                        'save_other_choice' => 0,
                        'default_value' => 'no',
                        'layout' => 'vertical',
                        'return_format' => 'value',
                    ),
                    array(
                        'key' => 'field_3a9bda830e2e7',
                        'label' => 'View More Url',
                        'name' => 'view_more_url',
                        'type' => 'text',
                        'instructions' => '',
                        'required' => 0,
                        'conditional_logic' => array(
                            array(
                                array(
                                    'field' => 'field_5a4bd9f00e3e5',
                                    'operator' => '==',
                                    'value' => 'trending',
                                ),
                            ),
                        ),
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
                        'key' => 'field_5a4bda830e3e7',
                        'label' => 'Title',
                        'name' => 'title',
                        'type' => 'text',
                        'instructions' => '',
                        'required' => 1,
                        'conditional_logic' => array(
                            array(
                                array(
                                    'field' => 'field_5a4bd9f00e3e5',
                                    'operator' => '==',
                                    'value' => 'custom',
                                ),
                            ),
                            array(
                                array(
                                    'field' => 'field_5a4bda460e3e6',
                                    'operator' => '==',
                                    'value' => 'yes',
                                ),
                            ),
                        ),
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
                        'key' => 'field_5a434dd830e3e7',
                        'label' => 'Title Link',
                        'name' => 'title_slug',
                        'type' => 'text',
                        'instructions' => '(Optional: ex. /gear)',
                        'required' => 0,
                        'conditional_logic' => array(
                            array(
                                array(
                                    'field' => 'field_5a4bd9f00e3e5',
                                    'operator' => '==',
                                    'value' => 'custom',
                                ),
                            ),
                            array(
                                array(
                                    'field' => 'field_5a4bda460e3e6',
                                    'operator' => '==',
                                    'value' => 'yes',
                                ),
                            ),
                        ),
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
                        'key' => 'field_5a5bda460e3e6',
                        'label' => 'Show Category?',
                        'name' => 'show_category',
                        'type' => 'radio',
                        'instructions' => '',
                        'required' => 1,
                        'conditional_logic' => array(),
                        'wrapper' => array(
                            'width' => '',
                            'class' => '',
                            'id' => '',
                        ),
                        'choices' => array(
                            'yes' => 'Yes',
                            'no' => 'No',
                        ),
                        'allow_null' => 0,
                        'other_choice' => 0,
                        'save_other_choice' => 0,
                        'default_value' => 'no',
                        'layout' => 'vertical',
                        'return_format' => 'value',
                    ),
                    array(
                        'key' => 'field_5a6bda460e3e6',
                        'label' => 'Show Author?',
                        'name' => 'show_author',
                        'type' => 'radio',
                        'instructions' => '',
                        'required' => 1,
                        'conditional_logic' => array(),
                        'wrapper' => array(
                            'width' => '',
                            'class' => '',
                            'id' => '',
                        ),
                        'choices' => array(
                            'yes' => 'Yes',
                            'no' => 'No',
                        ),
                        'allow_null' => 0,
                        'other_choice' => 0,
                        'save_other_choice' => 0,
                        'default_value' => 'no',
                        'layout' => 'vertical',
                        'return_format' => 'value',
                    ),
                    array(
                        'key' => 'field_5a4bdadb0e3e9',
                        'label' => 'Posts',
                        'name' => 'posts',
                        'type' => 'relationship',
                        'instructions' => '',
                        'required' => 1,
                        'conditional_logic' => array(
                            array(
                                array(
                                    'field' => 'field_5a4bd9f00e3e5',
                                    'operator' => '==',
                                    'value' => 'custom',
                                ),
                            ),
                            array(
                                array(
                                    'field' => 'field_5a4bd9f00e3e5',
                                    'operator' => '==',
                                    'value' => 'trending',
                                ),
                            ),
                        ),
                        'wrapper' => array(
                            'width' => '',
                            'class' => '',
                            'id' => '',
                        ),
                        'post_type' => array(
                            0 => 'page',
                            1 => 'post'
                        ),
                        'taxonomy' => array(),
                        'filters' => array(
                            0 => 'search',
                        ),
                        'elements' => array(
                            0 => 'featured_image',
                        ),
                        'min' => 5,
                        'max' => 10,
                        'return_format' => 'object',
                    ),
                    array(
                        'key' => 'field_5a4bdb920e3ec',
                        'label' => 'Category',
                        'name' => 'category',
                        'type' => 'taxonomy',
                        'instructions' => '',
                        'required' => 1,
                        'conditional_logic' => array(
                            array(
                                array(
                                    'field' => 'field_5a4bd9f00e3e5',
                                    'operator' => '==',
                                    'value' => 'category',
                                ),
                            ),
                        ),
                        'wrapper' => array(
                            'width' => '',
                            'class' => '',
                            'id' => '',
                        ),
                        'taxonomy' => 'category',
                        'field_type' => 'checkbox',
                        'allow_null' => 0,
                        'add_term' => 0,
                        'save_terms' => 0,
                        'load_terms' => 0,
                        'return_format' => 'object',
                        'multiple' => 0,
                    ),
                ),
            ),
            array(
                'key' => 'field_5a57dd0554000',
                'label' => 'Lead with video settings',
                'name' => 'lead_with_video_settings',
                'type' => 'group',
                'instructions' => '',
                'required' => 0,
                'conditional_logic' => array(
                    array(
                        array(
                            'field' => 'field_5a4b90d813dd9',
                            'operator' => '==',
                            'value' => 'lead_with_video',
                        ),
                        array(
                            'field' => 'field_5a4b8db4a8ed3',
                            'operator' => '==',
                            'value' => 'callout',
                        ),
                    ),
                ),
                'wrapper' => array(
                    'width' => '',
                    'class' => '',
                    'id' => '',
                ),
                'layout' => 'block',
                'sub_fields' => array(
                    array(
                        'key' => 'field_5a57dd3654001',
                        'label' => 'Highlighted Posts',
                        'name' => 'highlighted_posts',
                        'type' => 'relationship',
                        'instructions' => 'Please select the 2 posts that you would like to have in the lead module.',
                        'required' => 1,
                        'conditional_logic' => 0,
                        'wrapper' => array(
                            'width' => '',
                            'class' => '',
                            'id' => '',
                        ),
                        'post_type' => array(
                            0 => 'page',
                            1 => 'post'
                        ),
                        'taxonomy' => array(),
                        'filters' => array(
                            0 => 'search',
                        ),
                        'elements' => array(
                            0 => 'featured_image',
                        ),
                        'min' => 2,
                        'max' => 2,
                        'return_format' => 'object',
                    ),
                ),
            ),
            array(
                'key' => 'field_sponsor_highlight_settings',
                'label' => 'Sponsor Highlight Settings',
                'name' => 'sponsor_highlight_settings',
                'type' => 'group',
                'instructions' => '',
                'required' => 0,
                'conditional_logic' => array(
                    array(
                        array(
                            'field' => 'field_5a4b90d813dd9',
                            'operator' => '==',
                            'value' => 'sponsor_highlight',
                        ),
                        array(
                            'field' => 'field_5a4b8db4a8ed3',
                            'operator' => '==',
                            'value' => 'callout',
                        ),
                    ),
                ),
                'wrapper' => array(
                    'width' => '',
                    'class' => '',
                    'id' => '',
                ),
                'layout' => 'block',
                'sub_fields' => array()
            ),
            array(
                'key' => 'field_data_collection_settings',
                'label' => 'Data Collection',
                'name' => 'data_collection_settings',
                'type' => 'group',
                'instructions' => 'This is the module used to ask questions to the user after they signup for the Newsletter',
                'required' => 0,
                'conditional_logic' => array(
                    array(
                        array(
                            'field' => 'field_5a4b90d813dd9',
                            'operator' => '==',
                            'value' => 'data_collection',
                        ),
                        array(
                            'field' => 'field_5a4b8db4a8ed3',
                            'operator' => '==',
                            'value' => 'callout',
                        ),
                    ),
                ),
                'wrapper' => array(
                    'width' => '',
                    'class' => '',
                    'id' => '',
                ),
                'layout' => 'block',
            ),
            array(
                'key' => 'field_intro_text_settings',
                'label' => 'Introductory Text',
                'name' => 'intro_text_settings',
                'type' => 'group',
                'instructions' => 'This is an expandable text module',
                'required' => 0,
                'conditional_logic' => array(
                    array(
                        array(
                            'field' => 'field_5a4b90d813dd9',
                            'operator' => '==',
                            'value' => 'intro_text',
                        ),
                        array(
                            'field' => 'field_5a4b8db4a8ed3',
                            'operator' => '==',
                            'value' => 'callout',
                        ),
                    ),
                ),
                'wrapper' => array(
                    'width' => '',
                    'class' => '',
                    'id' => '',
                ),
                'layout' => 'block',
            ),
            array(
                'key' => 'field_text_overlay',
                'label' => 'Text Overlay Image',
                'name' => 'text_overlay_settings',
                'type' => 'group',
                'required' => 0,
                'conditional_logic' => array(
                    array(
                        array(
                            'field' => 'field_5a4b90d813dd9',
                            'operator' => '==',
                            'value' => 'text_overlay',
                        ),
                        array(
                            'field' => 'field_5a4b8db4a8ed3',
                            'operator' => '==',
                            'value' => 'callout',
                        ),
                    ),
                ),
                'wrapper' => array(
                    'width' => '',
                    'class' => '',
                    'id' => '',
                ),
                'layout' => 'block',
            ),
            array(
                'key' => 'field_free_form',
                'label' => 'Free Form',
                'name' => 'free_form_settings',
                'type' => 'group',
                'required' => 0,
                'conditional_logic' => array(
                    array(
                        array(
                            'field' => 'field_5a4b90d813dd9',
                            'operator' => '==',
                            'value' => 'free_form',
                        ),
                        array(
                            'field' => 'field_5a4b8db4a8ed3',
                            'operator' => '==',
                            'value' => 'callout',
                        ),
                    ),
                ),
                'wrapper' => array(
                    'width' => '',
                    'class' => '',
                    'id' => '',
                ),
                'layout' => 'block',
            ),
            array(
                'key' => 'field_faq',
                'label' => 'FAQ Form',
                'name' => 'faq_settings',
                'type' => 'group',
                'required' => 0,
                'conditional_logic' => array(
                    array(
                        array(
                            'field' => 'field_5a4b90d813dd9',
                            'operator' => '==',
                            'value' => 'faq',
                        ),
                        array(
                            'field' => 'field_5a4b8db4a8ed3',
                            'operator' => '==',
                            'value' => 'callout',
                        ),
                    ),
                ),
                'wrapper' => array(
                    'width' => '',
                    'class' => '',
                    'id' => '',
                ),
                'layout' => 'block',
            ),
            array(
                'key' => 'field_related_posts',
                'label' => 'Related Posts',
                'name' => 'related_posts_settings',
                'type' => 'group',
                'required' => 0,
                'conditional_logic' => array(
                    array(
                        array(
                            'field' => 'field_5a4b90d813dd9',
                            'operator' => '==',
                            'value' => 'related_posts',
                        ),
                        array(
                            'field' => 'field_5a4b8db4a8ed3',
                            'operator' => '==',
                            'value' => 'callout',
                        ),
                    ),
                ),
                'wrapper' => array(
                    'width' => '',
                    'class' => '',
                    'id' => '',
                ),
                'layout' => 'block',
            ),
            array(
                'key' => 'field_info_graphic',
                'label' => 'Info Graphic',
                'name' => 'info_graphic_settings',
                'type' => 'group',
                'required' => 0,
                'conditional_logic' => array(
                    array(
                        array(
                            'field' => 'field_5a4b90d813dd9',
                            'operator' => '==',
                            'value' => 'info_graphic',
                        ),
                        array(
                            'field' => 'field_5a4b8db4a8ed3',
                            'operator' => '==',
                            'value' => 'callout',
                        ),
                    ),
                ),
                'wrapper' => array(
                    'width' => '',
                    'class' => '',
                    'id' => '',
                ),
                'layout' => 'block',
            ),
            array(
                'key' => 'field_experts',
                'label' => 'Experts',
                'name' => 'experts_settings',
                'type' => 'group',
                'required' => 0,
                'conditional_logic' => array(
                    array(
                        array(
                            'field' => 'field_5a4b90d813dd9',
                            'operator' => '==',
                            'value' => 'experts',
                        ),
                        array(
                            'field' => 'field_5a4b8db4a8ed3',
                            'operator' => '==',
                            'value' => 'callout',
                        ),
                    ),
                ),
                'wrapper' => array(
                    'width' => '',
                    'class' => '',
                    'id' => '',
                ),
                'layout' => 'block',
            ),
            array(
                'key' => 'field_glossary',
                'label' => 'glossary',
                'name' => 'glossary_settings',
                'type' => 'group',
                'required' => 0,
                'conditional_logic' => array(
                    array(
                        array(
                            'field' => 'field_5a4b90d813dd9',
                            'operator' => '==',
                            'value' => 'glossary',
                        ),
                        array(
                            'field' => 'field_5a4b8db4a8ed3',
                            'operator' => '==',
                            'value' => 'callout',
                        ),
                    ),
                ),
                'wrapper' => array(
                    'width' => '',
                    'class' => '',
                    'id' => '',
                ),
                'layout' => 'block',
            ),
            array(
                'key' => 'field_recirculation',
                'label' => 'Recirculation',
                'name' => 'recirculation_settings',
                'type' => 'group',
                'required' => 0,
                'conditional_logic' => array(
                    array(
                        array(
                            'field' => 'field_5a4b90d813dd9',
                            'operator' => '==',
                            'value' => 'recirculation',
                        ),
                        array(
                            'field' => 'field_5a4b8db4a8ed3',
                            'operator' => '==',
                            'value' => 'callout',
                        ),
                    ),
                ),
                'wrapper' => array(
                    'width' => '',
                    'class' => '',
                    'id' => '',
                ),
                'layout' => 'block',
            ),
        ),
        'location' => array(
            array(
                array(
                    'param' => 'post_type',
                    'operator' => '==',
                    'value' => 'hp_module',
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
    /**
     * Hero Module Fields
     */
    acf_add_local_field(array(
        'key' => 'field_hero_title',
        'label' => 'Hero Title',
        'name' => 'hero_title',
        'type' => 'text',
        'parent' => 'field_hero_settings'
    ));
    acf_add_local_field(array(
        'key' => 'field_hero_image',
        'label' => 'Hero Image',
        'name' => 'hero_image',
        'type' => 'image',
        'parent' => 'field_hero_settings'
    ));
    acf_add_local_field(array(
        'key' => 'field_hero_button',
        'label' => 'Hero Button Link',
        'name' => 'hero_button',
        'type' => 'link',
        'parent' => 'field_hero_settings'
    ));
    acf_add_local_field(array(
        'key' => 'field_hero_content',
        'label' => 'Hero Content',
        'name' => 'hero_content',
        'type' => 'wysiwyg',
        'parent' => 'field_hero_settings',
        'toolbar' => 'basic',
        'media_upload' => 0
    ));
    /**
     * END Hero Module Fields
     */
    /**
     * Navigation Module Fields
     */
    acf_add_local_field(array(
        'parent' => 'field_7a4b92313cd08',
        'key' => 'field_sponsored_nav_color',
        'name' => 'sponsored_nav_color',
        'label' => 'Sponsored Nav Color',
        'type' => 'color_picker',
        'conditional_logic' => array(
            array(
                array(
                    'field' => 'field_6a4b87343cd0d',
                    'operator' => '==',
                    'value' => 'sponsored_nav'
                )
            )
        ),
    ));
    acf_add_local_field(array(
        'parent' => 'field_7a4b92313cd08',
        'key' => 'field_sponsored_nav_sponsor_image',
        'name' => 'sponsored_nav_sponsor_image',
        'label' => 'Sponsored Nav Sponsor Image',
        'type' => 'image',
        'conditional_logic' => array(
            array(
                array(
                    'field' => 'field_6a4b87343cd0d',
                    'operator' => '==',
                    'value' => 'sponsored_nav'
                )
            )
        ),
    ));
    acf_add_local_field(array(
        'parent' => 'field_7a4b92313cd08',
        'key' => 'field_sponsored_nav_sponsor_link',
        'name' => 'sponsored_nav_sponsor_link',
        'label' => 'Sponsored Nav Sponsor Link',
        'type' => 'text',
        'conditional_logic' => array(
            array(
                array(
                    'field' => 'field_6a4b87343cd0d',
                    'operator' => '==',
                    'value' => 'sponsored_nav'
                )
            )
        ),
    ));
    acf_add_local_field(array(
        'key' => 'field_sponsored_nav_text_color',
        'label' => 'Hero Text & Border Color',
        'name' => 'sponsored_nav_text_color',
        'type' => 'select',
        'choices' => array(
            'white' => 'White',
            'black' => 'Black'
        ),
        'parent' => 'field_7a4b92313cd08',
        'conditional_logic' => array(
            array(
                array(
                    'field' => 'field_6a4b87343cd0d',
                    'operator' => '==',
                    'value' => 'sponsored_nav'
                )
            )
        ),
    ));
    acf_add_local_field(array(
        'parent' => 'field_7a4b92313cd08',
        'key' => 'field_header_nav_banner_image',
        'name' => 'header_nav_banner_image',
        'label' => 'Header Nav Banner Image',
        'type' => 'image',
        'conditional_logic' => array(
            array(
                array(
                    'field' => 'field_6a4b87343cd0d',
                    'operator' => '==',
                    'value' => 'header_nav'
                )
            )
        ),
    ));
    acf_add_local_field(array(
        'parent' => 'field_7a4b92313cd08',
        'key' => 'field_header_nav_hide_banner_image',
        'name' => 'header_nav_hide_banner_image',
        'label' => 'Hide the banner image',
        'type' => 'true_false',
        'conditional_logic' => array(
            array(
                array(
                    'field' => 'field_6a4b87343cd0d',
                    'operator' => '==',
                    'value' => 'header_nav'
                )
            )
        ),
    ));
    /**
     * END Navigation Module Fields.
     */
    /**
     * Content Hub Settings Fields.
     */
    acf_add_local_field(array(
        'parent' => 'field_5a4bce551583c',
        'key' => 'field_content_hub_fill_type',
        'label' => 'How would you like to fill the content for this module?',
        'name' => 'content_hub_fill_type',
        'required' => 1,
        'conditional_logic' => array(
            array(
                array(
                    'field' => 'field_5a4bd4b9930c7',
                    'operator' => '!=',
                    'value' => 'variant_1',
                ),
            ),

        ),
        'type' => 'select',

        'choices' => array(
            'category' => 'Category',
            'tag' => 'Post Tag',
            'stages' => 'Stages Term'
        ),

    ));
    /**
     * END Content Hub Settings Fields.
     */
    /**
     * Sponsor Highlight Settings Fields.
     */
    acf_add_local_field(array(
        'parent' => 'field_sponsor_highlight_settings',
        'key' => 'field_sponsor_highlight_sponsor_name',
        'name' => 'sponsor_highlight_sponsor_name',
        'label' => __('What is the name of the sponsor for this module?'),
        'instructions' => __('e.g. GMC,New York Life etc...'),
        'required' => 0,
        'type' => 'text'

    ));
    acf_add_local_field(array(
        'parent' => 'field_sponsor_highlight_settings',
        'key' => 'field_sponsor_highlight_image',
        'name' => 'sponsor_highlight_image',
        'label' => __('Please select the image you would like to use with this module'),
        'required' => 0,
        'type' => 'image'

    ));
    acf_add_local_field(array(
        'parent' => 'field_sponsor_highlight_settings',
        'key' => 'field_sponsor_highlight_title',
        'name' => 'sponsor_highlight_title',
        'label' => __('What title would you like to show under the image for this module?'),
        'required' => 0,
        'type' => 'text'
    ));
    acf_add_local_field(array(
        'parent' => 'field_sponsor_highlight_settings',
        'key' => 'field_sponsor_highlight_content',
        'name' => 'sponsor_highlight_content',
        'label' => __('Please provide the content that will appear under the title on this module.'),
        'required' => 0,
        'type' => 'wysiwyg',
        'tabs' => 'visual',
        'toolbar' => 'basic',
        'media_upload' => 0,
        'delay' => 1,
    ));
    acf_add_local_field(array(
        'parent' => 'field_sponsor_highlight_settings',
        'key' => 'field_sponsor_highlight_link',
        'name' => 'sponsor_highlight_link',
        'label' => __('If you would like to show a button at the bottom of this module please provide that info here.'),
        'type' => 'link',
    ));
    acf_add_local_field(array(
        'parent' => 'field_sponsor_highlight_settings',
        'key' => 'field_sponsor_highlight_tracking',
        'name' => 'sponsor_highlight_tracking',
        'label' => __('Paste the tag that you would like used for impression tracking.'),
        'type' => 'textarea',
    ));
    /**
     * END Sponsor Highlight Settings Fields.
     */
    //Override Title
    acf_add_local_field(
        array(
            'parent' => 'field_5a4bce551583c',
            'key' => 'field_5a4bd383930c0',
            'label' => 'Override Title',
            'name' => 'override_title',
            'type' => 'radio',
            'instructions' => '',
            'required' => 0,
            'conditional_logic' => 0,
            'wrapper' => array(
                'width' => '',
                'class' => '',
                'id' => '',
            ),
            'choices' => array(
                'yes' => 'Yes',
                'no' => 'No',
            ),
            'allow_null' => 0,
            'other_choice' => 0,
            'save_other_choice' => 0,
            'default_value' => 'no',
            'layout' => 'vertical',
            'return_format' => 'value',
        )
    );
    // Stop Title from Linking
    acf_add_local_field(
        array(
            'parent' => 'field_5a4bce551583c',
            'key' => 'field_5b200a894dedf',
            'label' => 'Disable Link',
            'name' => 'disable_link',
            'type' => 'true_false',
            'instructions' => '',
            'required' => 0,
            'conditional_logic' => array(
                array(
                    array(
                        'field' => 'field_5a4bd9f00e3e5',
                        'operator' => '==',
                        'value' => 'custom',
                    ),
                ),
            ),
            'wrapper' => array(
                'width' => '',
                'class' => '',
                'id' => '',
            ),
            'allow_null' => 0,
            'other_choice' => 0,
            'save_other_choice' => 0,
            'default_value' => '',
            'layout' => 'vertical',
            'return_format' => 'value',
        )
    );
    //Title
    acf_add_local_field(array(
        'parent' => 'field_5a4bce551583c',
        'key' => 'field_5a4bd3b6930c1',
        'label' => 'Title',
        'name' => 'title',
        'type' => 'text',
        'instructions' => '',
        'required' => 1,
        'conditional_logic' => array(
            array(
                array(
                    'field' => 'field_5a4bd383930c0',
                    'operator' => '==',
                    'value' => 'yes',
                ),
            ),
        ),
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
    ));
    //Main Category
    acf_add_local_field(
        array(
            'parent' => 'field_5a4bce551583c',
            'key' => 'field_5a4bd2f3930be',
            'label' => 'Main Category',
            'name' => 'main_category',
            'type' => 'taxonomy',
            'instructions' => '',
            'required' => 1,
            'conditional_logic' => array(
                array(
                    array(
                        'field' => 'field_content_hub_fill_type',
                        'operator' => '==',
                        'value' => 'category'
                    )
                )
            ),
            'wrapper' => array(
                'width' => '',
                'class' => '',
                'id' => '',
            ),
            'taxonomy' => 'category',
            'field_type' => 'select',
            'allow_null' => 0,
            'add_term' => 0,
            'save_terms' => 0,
            'load_terms' => 0,
            'return_format' => 'object',
            'multiple' => 0,
        )
    );
    //Post Tag
    acf_add_local_field(array(
        'parent' => 'field_5a4bce551583c',
        'key' => 'field_post_tag',
        'label' => 'Post Tag',
        'name' => 'main_post_tag',
        'type' => 'taxonomy',
        'instructions' => '',
        'required' => 1,
        'conditional_logic' => array(
            array(
                array(
                    'field' => 'field_content_hub_fill_type',
                    'operator' => '==',
                    'value' => 'tag',
                )
            )
        ),
        'wrapper' => array(
            'width' => '',
            'class' => '',
            'id' => '',
        ),
        'taxonomy' => 'post_tag',
        'field_type' => 'multi_select',
        'allow_null' => 0,
        'add_term' => 0,
        'save_terms' => 0,
        'load_terms' => 0,
        'multiple' => 1,
        'return_format' => 'object',

    ));
    //Stages Term
    acf_add_local_field(array(
        'parent' => 'field_5a4bce551583c',
        'key' => 'field_stages_term',
        'label' => 'Stages Term',
        'name' => 'main_stages_term',
        'type' => 'taxonomy',
        'instructions' => '',
        'required' => 1,
        'conditional_logic' => array(
            array(
                array(
                    'field' => 'field_content_hub_fill_type',
                    'operator' => '==',
                    'value' => 'stages',
                )
            )
        ),
        'wrapper' => array(
            'width' => '',
            'class' => '',
            'id' => '',
        ),
        'taxonomy' => 'stages',
        'field_type' => 'multi_select',
        'allow_null' => 0,
        'add_term' => 0,
        'save_terms' => 0,
        'load_terms' => 0,
        'multiple' => 1,
        'return_format' => 'object',

    ));
    //Highlighted Sub Categories
    acf_add_local_field(array(
        'parent' => 'field_5a4bce551583c',
        'key' => 'field_5a4bd32c930bf',
        'label' => 'Highlighted Sub Categories',
        'name' => 'highlighted_sub_categories',
        'type' => 'taxonomy',
        'instructions' => '',
        'required' => 0,
        'conditional_logic' => array(
            array(
                array(
                    'field' => 'field_5a4bd4b9930c7',
                    'operator' => '!=',
                    'value' => 'variant_1',
                ),
            ),

        ),
        'wrapper' => array(
            'width' => '',
            'class' => '',
            'id' => '',
        ),
        'taxonomy' => 'category',
        'field_type' => 'checkbox',
        'allow_null' => 0,
        'add_term' => 0,
        'save_terms' => 0,
        'load_terms' => 0,
        'return_format' => 'object',
        'multiple' => 1,
    ));
    //Parenting Stages Legacy
    acf_add_local_field(array(
        'parent' => 'field_5a4bce551583c',
        'key' => 'field_6a4bd32c930bf',
        'label' => 'Parenting Stages',
        'name' => 'highlighted_stages',
        'type' => 'post_object',
        'instructions' => 'If you would like to feature parenting stages instead of categories select them here.',
        'required' => 0,
        'conditional_logic' => array(
            array(
                array(
                    'field' => 'field_5a4bd4b9930c7',
                    'operator' => '==',
                    'value' => 'variant_1',
                ),
            ),
        ),
        'wrapper' => array(
            'width' => '',
            'class' => '',
            'id' => '',
        ),
        'post_type' => array(
            0 => 'page',
        ),
        'taxonomy' => array(),
        'allow_null' => 0,
        'multiple' => 1,
        'return_format' => 'object',
        'ui' => 1,
    ));
    // Parenting Stages
    acf_add_local_field(array(
        'parent' => 'field_5a4bce551583c',
        'key' => 'field_6a4bd32c940bf',
        'label' => 'Parenting Stages',
        'name' => 'highlighted_stages_new',
        'type' => 'taxonomy',
        'instructions' => 'If you would like to feature parenting stages instead of categories select them here.',
        'required' => 1,
        'conditional_logic' => array(
            array(
                array(
                    'field' => 'field_5a4bd4b9930c7',
                    'operator' => '==',
                    'value' => 'variant_1',
                ),
            ),
        ),
        'wrapper' => array(
            'width' => '',
            'class' => '',
            'id' => '',
        ),
        'taxonomy' => 'stages',
        'allow_null' => 0,
        'multiple' => 1,
        'return_format' => 'object',
        'field_type' => 'multi_select',
    ));
    //Is Sponsored
    acf_add_local_field(array(
        'parent' => 'field_5a4bce551583c',
        'key' => 'field_5a4bd404930c2',
        'label' => 'Sponsored?',
        'name' => 'sponsored',
        'type' => 'radio',
        'instructions' => '',
        'required' => 0,
        'conditional_logic' => 0,
        'wrapper' => array(
            'width' => '',
            'class' => '',
            'id' => '',
        ),
        'choices' => array(
            'yes' => 'Yes',
            'no' => 'No',
        ),
        'allow_null' => 0,
        'other_choice' => 0,
        'save_other_choice' => 0,
        'default_value' => 'no',
        'layout' => 'vertical',
        'return_format' => 'value',
    ));
    //Display Variant
    acf_add_local_field(array(
        'parent' => 'field_5a4bce551583c',
        'key' => 'field_5a4bd4b9930c7',
        'label' => 'Display Variant',
        'name' => 'variant',
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
            'variant_1' => 'Variant 1',
            'variant_2' => 'Variant 2',
            'variant_3' => 'Variant 3',
            'variant_4' => 'Variant 4',
            'variant_5' => 'Variant 5',
        ),
        'default_value' => array(),
        'allow_null' => 0,
        'multiple' => 0,
        'ui' => 0,
        'ajax' => 1,
        'return_format' => 'value',
        'placeholder' => '',
    ));
    /**
     * END Content Hub Settings Fields.
     */
    /**
     * More From Settings Fields
     */
    acf_add_local_field(array(
        'parent' => 'field_more_from_settings',
        'key' => 'field_more_from_enable_tag',
        'label' => 'Would you like to pull in posts from a tag for this module?',
        'name' => 'more_from_enable_tag',
        'type' => 'radio',
        'instructions' => 'On certain occasions (e.g. sponsored content hubs) it\'s necessary to select the tag you would like used for the more from module.',
        'required' => 0,
        'conditional_logic' => 0,
        'wrapper' => array(
            'width' => '',
            'class' => '',
            'id' => '',
        ),
        'choices' => array(
            'yes' => 'Yes',
            'no' => 'No',
        ),
        'allow_null' => 0,
        'other_choice' => 0,
        'save_other_choice' => 0,
        'default_value' => 'no',
        'layout' => 'vertical',
        'return_format' => 'value',
    ));
    acf_add_local_field(array(
        'parent' => 'field_more_from_settings',
        'key' => 'field_more_from_post_tag',
        'label' => 'Post Tag',
        'name' => 'more_from_post_tag',
        'type' => 'taxonomy',
        'instructions' => '',
        'required' => 1,
        'conditional_logic' => array(
            array(
                array(
                    'field' => 'field_more_from_enable_tag',
                    'operator' => '==',
                    'value' => 'yes',
                )
            )
        ),
        'wrapper' => array(
            'width' => '',
            'class' => '',
            'id' => '',
        ),
        'taxonomy' => 'post_tag',
        'field_type' => 'multi_select',
        'allow_null' => 0,
        'add_term' => 0,
        'save_terms' => 0,
        'load_terms' => 0,
        'multiple' => 1,
        'return_format' => 'object',
    ));
    /**
     * END More From Settings Fields.
     */
    /**
     * Homepage Sponsored Module Fields
     */
    acf_add_local_field(array(
        'parent' => 'field_custom_sponsored_content_hub_settings',
        'key' => 'field_sponsored_post',
        'label' => 'Sponsored Post',
        'name' => 'sponsored_post',
        'type' => 'post_object',
        'required' => 1,
        'wrapper' => array(
            'width' => '',
            'class' => '',
            'id' => '',
        ),
        'post_type' => array(
            0 => 'page',
            1 => 'post'
        ),
        'taxonomy' => array(),
        'allow_null' => 0,
        'multiple' => 0,
        'return_format' => 'object',
        'ui' => 1,
    ));
    acf_add_local_field(array(
        'parent' => 'field_custom_sponsored_content_hub_settings',
        'key' => 'field_sponsored_post_tracking_code',
        'name' => 'sponsored_post_tracking_code',
        'label' => 'Impression Tracker',
        'type' => 'textarea'
    ));
    acf_add_local_field(array(
        'parent' => 'field_5a4bd7f6b4797',
        'key' => 'field_sponsored_hub_tracking_code',
        'name' => 'sponsored_hub_tracking_code',
        'label' => 'Impression Tracker',
        'type' => 'textarea'
    ));
    acf_add_local_field(array(
        'parent' => 'field_custom_sponsored_content_hub_settings',
        'key' => 'field_sponsored_post_sponsor_logo',
        'name' => 'sponsored_post_sponsor_logo',
        'label' => 'Sponsor Logo',
        'type' => 'image',
    ));
    acf_add_local_field(array(
        'parent' => 'field_custom_sponsored_content_hub_settings',
        'key' => 'field_sponsored_post_sponsor_link',
        'name' => 'sponsored_post_sponsor_link',
        'label' => 'Sponsor Link',
        'type' => 'url',
    ));
    /**
     * END Sponsored Module Settings Fields.
     */
    /**
     * Secondary Video Module Fields
     */
    acf_add_local_field(array(
        'parent' => 'field_secondary_videos_settings',
        'key' => 'field_secondary_videos_post_tag',
        'label' => 'Post Tag',
        'name' => 'secondary_videos_post_tag',
        'type' => 'taxonomy',
        'instructions' => '',
        'required' => 1,
        'conditional_logic' => 0,
        'wrapper' => array(
            'width' => '',
            'class' => '',
            'id' => '',
        ),
        'taxonomy' => 'post_tag',
        'field_type' => 'multi_select',
        'allow_null' => 0,
        'add_term' => 0,
        'save_terms' => 0,
        'load_terms' => 0,
        'multiple' => 1,
        'return_format' => 'object',
    ));
    acf_add_local_field(array(
        'parent' => 'field_secondary_videos_settings',
        'key' => 'field_secondary_videos_follow_link',
        'name' => 'secondary_videos_follow_link',
        'label' => 'Follow Link',
        'type' => 'url',
    ));
    acf_add_local_field(array(
        'parent' => 'field_secondary_videos_settings',
        'key' => 'field_secondary_videos_sponsored',
        'label' => 'Sponsored?',
        'name' => 'sponsored',
        'type' => 'radio',
        'instructions' => '',
        'required' => 1,
        'conditional_logic' => 0,
        'wrapper' => array(
            'width' => '',
            'class' => '',
            'id' => '',
        ),
        'choices' => array(
            'yes' => 'Yes',
            'no' => 'No',
        ),
        'allow_null' => 0,
        'other_choice' => 0,
        'save_other_choice' => 0,
        'default_value' => 'no',
        'layout' => 'vertical',
        'return_format' => 'value',
    ));
    acf_add_local_field(array(
        'parent' => 'field_secondary_videos_settings',
        'key' => 'field_secondary_videos_sponsor_settings',
        'label' => 'Sponsored Settings',
        'name' => 'sponsored_settings',
        'type' => 'group',
        'instructions' => '',
        'required' => 0,
        'conditional_logic' => array(
            array(
                array(
                    'field' => 'field_secondary_videos_sponsored',
                    'operator' => '==',
                    'value' => 'yes',
                ),
            ),
        ),
        'wrapper' => array(
            'width' => '',
            'class' => '',
            'id' => '',
        ),
        'layout' => 'block',
        'sub_fields' => array(
            array(
                'key' => 'field_secondary_videos_sponsor_name',
                'label' => 'Sponsor Name',
                'name' => 'sponsor_name',
                'type' => 'text',
                'instructions' => '',
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
                'key' => 'field_secondary_videos_sponsor_logo',
                'label' => 'Sponsor Logo',
                'name' => 'sponsor_logo',
                'type' => 'image',
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
                'key' => 'field_secondary_videos_sponsor_link',
                'label' => 'Sponsor Link',
                'name' => 'sponsor_link',
                'type' => 'text',
                'instructions' => '',
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
        ),
    ));
    /**
     * END Secondary Video Module Fields.
     */
    acf_add_local_field(array(
        'parent' => 'field_5a4b9ae345872345-title',
        'key' => 'field_title_module_sponsored',
        'label' => 'Sponsored?',
        'name' => 'sponsored',
        'type' => 'radio',
        'instructions' => '',
        'required' => 0,
        'conditional_logic' => 0,
        'wrapper' => array(
            'width' => '',
            'class' => '',
            'id' => '',
        ),
        'choices' => array(
            'yes' => 'Yes',
            'no' => 'No',
        ),
        'allow_null' => 0,
        'other_choice' => 0,
        'save_other_choice' => 0,
        'default_value' => 'no',
        'layout' => 'vertical',
        'return_format' => 'value',
    ));
    /**
     * Newsletter Data Collection Module Fields
     */
    acf_add_local_field(array(
        'parent' => 'field_data_collection_settings',
        'key' => 'field_collect_child_info',
        'label' => 'Ask For Children\'s Ages?',
        'name' => 'childs_age',
        'type' => 'true_false',
        'instructions' => '',
        'required' => 0,
        'conditional_logic' => 0,
        'wrapper' => array(
            'width' => '',
            'class' => '',
            'id' => '',
        ),
        'message' => '',
        'default_value' => 1,
        'ui' => 0,
        'ui_on_text' => '',
        'ui_off_text' => '',
    ));
    acf_add_local_field(array(
        'parent' => 'field_data_collection_settings',
        'key' => 'field_style_format',
        'label' => 'Style Format',
        'name' => 'style_format',
        'type' => 'radio',
        'instructions' => '',
        'required' => 1,
        'conditional_logic' => 0,
        'wrapper' => array(
            'width' => '',
            'class' => '',
            'id' => '',
        ),
        'choices' => array(
            'newsletter' => 'Newsletter',
            'fatherly_iq' => 'Fatherly IQ',
            'registry' => 'Registry',
        ),
        'allow_null' => 0,
        'other_choice' => 0,
        'save_other_choice' => 0,
        'default_value' => '',
        'layout' => 'vertical',
        'return_format' => 'value',
    ));
    acf_add_local_field(array(
        'parent' => 'field_data_collection_settings',
        'key' => 'field_lead_type',
        'label' => 'Lead Type',
        'name' => 'lead_type',
        'type' => 'radio',
        'instructions' => '',
        'required' => 1,
        'conditional_logic' => 0,
        'wrapper' => array(
            'width' => '',
            'class' => '',
            'id' => '',
        ),
        'choices' => array(
            'text_lead' => 'Text Lead',
            'image_lead' => 'Image Lead',
        ),
        'allow_null' => 0,
        'other_choice' => 0,
        'save_other_choice' => 0,
        'default_value' => '',
        'layout' => 'vertical',
        'return_format' => 'value',
    ));
    acf_add_local_field(array(
        'parent' => 'field_data_collection_settings',
        'key' => 'field_text_lead',
        'label' => 'Text Lead',
        'name' => 'text_lead',
        'type' => 'text',
        'instructions' => '',
        'required' => 0,
        'conditional_logic' => array(
            array(
                array(
                    'field' => 'field_lead_type',
                    'operator' => '==',
                    'value' => 'text_lead',
                ),
            ),
        ),
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
    ));
    acf_add_local_field(array(
        'parent' => 'field_data_collection_settings',
        'key' => 'field_image_lead',
        'label' => 'Image lead',
        'name' => 'image_lead',
        'type' => 'image',
        'instructions' => '',
        'required' => 0,
        'conditional_logic' => array(
            array(
                array(
                    'field' => 'field_lead_type',
                    'operator' => '==',
                    'value' => 'image_lead',
                ),
            ),
        ),
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
    ));
    acf_add_local_field(array(
        'parent' => 'field_data_collection_settings',
        'key' => 'field_add_a_question',
        'label' => 'Add A Question',
        'name' => 'add_a_question',
        'type' => 'repeater',
        'instructions' => '',
        'required' => 0,
        'conditional_logic' => 0,
        'wrapper' => array(
            'width' => '',
            'class' => '',
            'id' => '',
        ),
        'collapsed' => '',
        'min' => 1,
        'max' => 20,
        'layout' => 'table',
        'button_label' => '',
        'sub_fields' => array(
            array(
                'key' => 'field_question',
                'label' => 'Question',
                'name' => 'question',
                'type' => 'textarea',
                'instructions' => '',
                'required' => 0,
                'conditional_logic' => 0,
                'wrapper' => array(
                    'width' => '',
                    'class' => '',
                    'id' => '',
                ),
                'default_value' => '',
                'placeholder' => '',
                'maxlength' => '',
                'rows' => '',
                'new_lines' => '',
            ),
            array(
                'key' => 'field_enter_answers',
                'label' => 'Enter Answers',
                'name' => 'enter_answer',
                'type' => 'repeater',
                'instructions' => '',
                'required' => 0,
                'conditional_logic' => 0,
                'wrapper' => array(
                    'width' => '',
                    'class' => '',
                    'id' => '',
                ),
                'collapsed' => '',
                'min' => 1,
                'max' => 6,
                'layout' => 'table',
                'button_label' => '',
                'sub_fields' => array(
                    array(
                        'key' => 'field_answers',
                        'label' => 'Answers',
                        'name' => 'answer',
                        'type' => 'text',
                        'instructions' => '',
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
                ),
            ),
            array(
                'parent' => 'field_data_collection_settings',
                'key' => 'field_enter_weight',
                'label' => 'Enter Weight',
                'name' => 'enter_weight',
                'type' => 'repeater',
                'instructions' => '',
                'required' => 0,
                'conditional_logic' => array(
                    array(
                        array(
                            'field' => 'field_style_format',
                            'operator' => '==',
                            'value' => 'registry',
                        ),
                    ),
                ),
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
                        'key' => 'field_5c5b386a71bac',
                        'label' => 'Result Page',
                        'name' => 'result_page',
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
                        ),
                        'taxonomy' => array(
                        ),
                        'allow_null' => 0,
                        'multiple' => 0,
                        'return_format' => 'object',
                        'ui' => 1,
                    ),
                    array(
                        'key' => 'field_5c5b399971bad',
                        'label' => 'Weight',
                        'name' => 'weight',
                        'type' => 'range',
                        'instructions' => '',
                        'required' => 0,
                        'conditional_logic' => 0,
                        'wrapper' => array(
                            'width' => '',
                            'class' => '',
                            'id' => '',
                        ),
                        'default_value' => 0,
                        'min' => -3,
                        'max' => 3,
                        'step' => '',
                        'prepend' => '',
                        'append' => '',
                    ),
                ),
            ),
        ),
    ));
    /**
     * END Newsletter Data Collection Module Fields
     */
    /**
     * Introductory Text Expandable Module Fields
     */
    acf_add_local_field(array(
        'parent' => 'field_intro_text_settings',
        'key' => 'field_intro_text_title',
        'label' => 'Title',
        'name' => 'custom_title',
        'type' => 'text',
        'required' => 1
    ));
    acf_add_local_field(array(
        'parent' => 'field_intro_text_settings',
        'key' => 'field_intro_text_content',
        'label' => 'Content',
        'name' => 'content',
        'type' => 'wysiwyg',
        'toolbar' => 'basic',
        'media_upload' => 0,
        'delay' => 1
    ));
    /**
     * END Introductory Text Expandable Module Fields
     */

    /**
     * Text Overlaying Image Module
     */

    acf_add_local_field(array(
        'parent' => 'field_text_overlay',
        'key' => 'field_text_overlay_image',
        'label' => 'Intro Image',
        'name' => 'intro_image',
        'type' => 'image',
        'instructions' => '',
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
    ));
    acf_add_local_field(array(
        'parent' => 'field_text_overlay',
        'key' => 'field_text_overlay_text',
        'label' => 'Overlay Text',
        'name' => 'overlay_text',
        'type' => 'text',
        'instructions' => '',
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
    ));
    /**
     * END Text Overlaying Image Module
     */

    /**
     * Free Form Module
     */

    acf_add_local_field(array(
        'parent' => 'field_free_form',
        'key' => 'field_free_form_wysiwyg',
        'label' => 'Message',
        'name' => 'message',
        'type' => 'wysiwyg',
        'instructions' => 'You can use the WYSIWYG here to provide the content for your module.',
        'required' => 1,
        'conditional_logic' => 0,
        'wrapper' => array(
            'width' => '',
            'class' => '',
            'id' => '',
        ),
        'default_value' => '',
        'tabs' => 'visual',
        'toolbar' => 'basic',
        'media_upload' => 0,
        'delay' => 1,
    ));

    acf_add_local_field(array(
        'parent' => 'field_free_form',
        'key' => 'field_form_layout',
        'label' => 'Form Layout',
        'name' => 'form_layout',
        'type' => 'radio',
        'instructions' => '',
        'required' => 0,
        'conditional_logic' => 0,
        'wrapper' => array(
            'width' => '',
            'class' => '',
            'id' => '',
        ),
        'choices' => array(
            'row' => 'row',
            'column' => 'column',
        ),
        'allow_null' => 0,
        'other_choice' => 0,
        'save_other_choice' => 0,
        'default_value' => '',
        'layout' => 'vertical',
        'return_format' => 'value',
    ));
    /**
     * END Free Form Module
     */

    /**
     * Faq Module
     */
    acf_add_local_field(array(
        'parent' => 'field_faq',
        'key' => 'field_faq_title',
        'label' => 'FAQ Title',
        'name' => 'faq_title',
        'type' => 'text',
        'instructions' => '',
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
    ));
    acf_add_local_field(array(
        'parent' => 'field_faq',
        'key' => 'field_add_faq',
        'label' => 'Add Faq',
        'name' => 'add_faq',
        'type' => 'repeater',
        'instructions' => '',
        'required' => 0,
        'conditional_logic' => 0,
        'wrapper' => array(
            'width' => '',
            'class' => '',
            'id' => '',
        ),
        'collapsed' => '',
        'min' => 1,
        'max' => 25,
        'layout' => 'table',
        'button_label' => '',
        'sub_fields' => array(
            array(
                'key' => 'field_faq_module',
                'label' => 'Faq Module',
                'name' => 'faq_module',
                'type' => 'wysiwyg',
                'instructions' => 'the first paragraph will show and the rest will be hidden until the expand button is pressed',
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
                'media_upload' => 1,
                'delay' => 1,
            ),
        ),
    ));
    /**
     * END Faq Module
     */

    /**
     * Related Posts Module
     */
    acf_add_local_field(array(
        'parent' => 'field_related_posts',
        'key' => 'field_related_posts_title',
        'label' => 'Related Posts Title',
        'name' => 'related_posts_title',
        'type' => 'text',
        'instructions' => '',
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
    ));
    acf_add_local_field(array(
        'parent' => 'field_related_posts',
        'key' => 'field_add_related_posts',
        'label' => 'Add Related Posts',
        'name' => 'add_related_posts',
        'type' => 'repeater',
        'instructions' => '',
        'required' => 0,
        'conditional_logic' => 0,
        'wrapper' => array(
            'width' => '',
            'class' => '',
            'id' => '',
        ),
        'collapsed' => '',
        'min' => 1,
        'max' => 8,
        'layout' => 'table',
        'button_label' => '',
        'sub_fields' => array(
            array(
                'key' => 'field_5cdc4d4a3cf4e',
                'label' => 'Related Story',
                'name' => 'related_story',
                'type' => 'post_object',
                'instructions' => '',
                'required' => 0,
                'conditional_logic' => 0,
                'wrapper' => array(
                    'width' => '',
                    'class' => '',
                    'id' => '',
                ),
                'post_type' => array(
                ),
                'taxonomy' => array(
                ),
                'allow_null' => 0,
                'multiple' => 0,
                'return_format' => 'object',
                'ui' => 1,
            ),
        ),
    ));
    /**
     * END Related Posts Module
     */

    /**
     * Info Graphics Module
     */
    acf_add_local_field(array(
        'parent' => 'field_info_graphic',
        'key' => 'field_info_graphic_title',
        'label' => 'Info Graphic Title',
        'name' => 'info_graphic_title',
        'type' => 'text',
        'instructions' => '',
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
    ));
    acf_add_local_field(array(
        'parent' => 'field_info_graphic',
        'key' => 'field_add_info_graphic',
        'label' => ' Add Info Graphic',
        'name' => 'add_info_graphic',
        'type' => 'image',
        'instructions' => '',
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
    ));
    /**
     * END Info Graphics Module
     */

    /**
     * Experts Module
     */
    acf_add_local_field(array(
        'parent' => 'field_experts',
        'key' => 'field_experts_title',
        'label' => 'Experts Title',
        'name' => 'experts_title',
        'type' => 'text',
        'instructions' => '',
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
    ));
    acf_add_local_field(array(
        'parent' => 'field_experts',
        'key' => 'field_add_expert',
        'label' => 'Add Expert',
        'name' => 'add_expert',
        'type' => 'repeater',
        'instructions' => '',
        'required' => 0,
        'conditional_logic' => 0,
        'wrapper' => array(
            'width' => '',
            'class' => '',
            'id' => '',
        ),
        'collapsed' => '',
        'min' => 1,
        'max' => 8,
        'layout' => 'table',
        'button_label' => '',
        'sub_fields' => array(
            array(
                'key' => 'field_5cdee1ce648a1',
                'label' => 'Expert Image',
                'name' => 'expert_image',
                'type' => 'image',
                'instructions' => '',
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
                'key' => 'field_5cdee214648a2',
                'label' => 'Expert Name',
                'name' => 'expert_name',
                'type' => 'text',
                'instructions' => '',
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
                'key' => 'field_5cdee21f648a3',
                'label' => 'Expert Profession',
                'name' => 'expert_profession',
                'type' => 'text',
                'instructions' => '',
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
                'key' => 'field_5cdee7088ef29',
                'label' => 'Expert Page Link',
                'name' => 'expert_page_link',
                'type' => 'link',
                'instructions' => '',
                'required' => 0,
                'conditional_logic' => 0,
                'wrapper' => array(
                    'width' => '',
                    'class' => '',
                    'id' => '',
                ),
                'return_format' => 'url',
            ),
        ),
    ));
    /**
     * END Info Graphics Module
     */

    /**
     * Glossary Module
     */
    acf_add_local_field(array(
        'parent' => 'field_glossary',
        'key' => 'field_glossary_title',
        'label' => 'Glossary Title',
        'name' => 'glossary_title',
        'type' => 'text',
        'instructions' => '',
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
    ));
    acf_add_local_field(array(
        'parent' => 'field_glossary',
        'key' => 'field_5ce2ea0b86e99',
        'label' => 'add_glossary_item',
        'name' => 'add_glossary_item',
        'type' => 'repeater',
        'instructions' => '',
        'required' => 0,
        'conditional_logic' => 0,
        'wrapper' => array(
            'width' => '',
            'class' => '',
            'id' => '',
        ),
        'collapsed' => '',
        'min' => 1,
        'max' => 15,
        'layout' => 'table',
        'button_label' => '',
        'sub_fields' => array(
            array(
                'key' => 'field_5ce2ea3d86e9a',
                'label' => 'glossary Item Title',
                'name' => 'glossary_item_title',
                'type' => 'text',
                'instructions' => '',
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
                'key' => 'field_5ce2ea4c86e9b',
                'label' => 'Glossary Item Definition',
                'name' => 'glossary_item_definition',
                'type' => 'text',
                'instructions' => '',
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
        ),
    ));
    /**
     * END Glossary Module
     *
    //**
     * Recirculation Module
     */
    acf_add_local_field(array(
        'parent' => 'field_recirculation',
        'key' => 'field_recirculation_title',
        'label' => 'Recirculation Title',
        'name' => 'recirculation_title',
        'type' => 'text',
        'instructions' => '',
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
    ));
    acf_add_local_field(array(
        'parent' => 'field_recirculation',
        'key' => 'field_recirculation_feed_type',
        'label' => 'Feed Type',
        'name' => 'feed_type',
        'type' => 'radio',
        'instructions' => '',
        'required' => 0,
        'conditional_logic' => 0,
        'wrapper' => array(
            'width' => '',
            'class' => '',
            'id' => '',
        ),
        'choices' => array(
            'Trending' => 'Trending',
            'Newest' => 'Newest',
        ),
        'allow_null' => 0,
        'other_choice' => 0,
        'save_other_choice' => 0,
        'default_value' => '',
        'layout' => 'vertical',
        'return_format' => 'value',
    ));
    /**
     * END Recirculation Module
     */
endif;
