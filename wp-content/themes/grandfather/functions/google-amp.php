<?php
add_action('amp_post_template_css', 'fth_amp_additional_css_styles');
add_filter('amp_post_template_analytics', 'fth_amp_add_custom_analytics');
add_action('amp_post_template_head', 'fth_amp_additional_head_scripts');

function fth_amp_additional_css_styles($amp_template)
{
    ?>
    .amp-wp-header a{background-image: url( '<?php echo fth_get_protocol_relative_template_directory_uri() . '/images/logo-thick.png' ?>' );}
    <?php
    if (constant('ENV') === 'prod' || constant('ENV') === 'staging') {
        include(dirname(__FILE__) . '/..' . AMP_STYLE);
    } else {
        include(dirname(__FILE__) . '/../dev_assets/css/amp.css');
    }
}

function fth_amp_additional_head_scripts($amp_template)
{
    echo '<script id="amp-access" type="application/json"> 
    {
        "vendor": "scroll", 
        "namespace": "scroll" 
    } </script>';
}

function fth_amp_add_custom_analytics($analytics)
{
    if (!is_array($analytics)) {
        $analytics = array();
    }

    // https://developers.google.com/analytics/devguides/collection/amp-analytics/
    $analytics['fth-googleanalytics'] = array(
        'type' => 'googleanalytics',
        'attributes' => array(// 'data-credentials' => 'include',
        ),
        'config_data' => array(
            'vars' => array(
                'account' => \Fatherly\Config\FatherlyConfig::config()->get('ga_id')
            ),
            'triggers' => array(
                'trackPageview' => array(
                    'on' => 'visible',
                    'request' => 'pageview',
                ),
                'emailSubmit' => array(
                    "on" => "amp-form-submit-success",
                    'request' => 'event',
                    'vars' => array(
                        'eventCategory'=> 'AMP',
                        'eventAction'=> 'Email Submit'
                    )
                ),
                'relatedArticleImg' => array(
                    "on" => "click",
                    "selector" => ".related-article__image a",
                    'request' => 'event',
                    'vars' => array(
                        'eventCategory'=> 'AMP',
                        'eventAction'=> 'Category Module - Image'
                    )
                ),
                'relatedArticleTitle' => array(
                    "on" => "click",
                    "selector" => ".related-article__title",
                    'request' => 'event',
                    'vars' => array(
                        'eventCategory'=> 'AMP',
                        'eventAction'=> 'Category Module - Title'
                    )
                ),
                'relatedInBodyArticleImg' => array(
                    "on" => "click",
                    "selector" => ".recirculation-content-item-image a",
                    'request' => 'event',
                    'vars' => array(
                        'eventCategory'=> 'AMP',
                        'eventAction'=> 'Recirculation - Image'
                    )
                ),
                'relatedInBodyTitle' => array(
                    "on" => "click",
                    "selector" => ".recirculation-content-item-title a",
                    'request' => 'event',
                    'vars' => array(
                        'eventCategory'=> 'AMP',
                        'eventAction'=> 'Recirculation - Title'
                    )
                ),
            ),
        ),
    );

    $analytics['fth-parsely'] = array(
        'type' => 'parsely',
        'attributes' => array(// 'data-credentials' => 'include',
        ),
        'config_data' => array(
            'vars' => array(
                'apikey' => "fatherly.com"
            ),
        ),
    );

    $analytics['fth-quantcast'] = array(
        'type' => 'quantcast',
        'attributes' => array(
        ),
        'config_data' => array(
            'vars' => array(
                'pcode' => 'p-CsS7pu3gG9C94',
                'labels' => array('Platform.AMP')
            )
        )
    );
    return $analytics;
}


add_filter('amp_post_template_metadata', 'xyz_amp_modify_json_metadata', 10, 2);

function xyz_amp_modify_json_metadata($metadata, $post)
{
    $metadata['publisher']['logo'] = array(
        '@type' => 'ImageObject',
        'url' => fth_get_protocol_relative_template_directory_uri() . '/images/logo-thick.png',
        'height' => 144,
        'width' => 545,
    );

    $metadata['image'] = array(
        '@type' => 'ImageObject',
        'url' => get_the_post_thumbnail_url(null, 'large-thumb'),
        'height' => 400,
        'width' => 500,
    );

    return $metadata;
}
