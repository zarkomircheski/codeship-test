<?php
/**
 * Display the buy button on the frontend
 * @param $atts
 * @return string
 */
function buybutton_func($atts)
{
    $shortCodeAtts = shortcode_atts(array(
        'url' => 'something',
        'price' => '',
    ), $atts);

    $shortCodeAtts['price_sanitized'] = str_replace(['$', ','], '', $shortCodeAtts['price']);

    return "<p><a class=\"aud-dev-track buy-button\""
            ." href=\"{$shortCodeAtts['url']}\" target=\"_blank\">"
            ."<span data-ev-loc=\"Body\" data-ev-name=\"Buy Button\">Buy Now".(strpos($shortCodeAtts['price'], '$') === false ? " $" : " " )."{$shortCodeAtts['price']}</span></a></p>";
}

add_shortcode('buybutton', 'buybutton_func');

/**
 * Checks to see if the buy button has the correct value in the price
 *
 * @param $post_id
 * @param $post
 */
function fth_validate_post_buy_button($post_id, $post)
{
    $pattern = get_shortcode_regex();
    $errors = '';
    $postContent = $post->post_content;
    //Checks to see if the buy button is present
    if (preg_match_all('/'. $pattern .'/s', $post->post_content, $matches) && array_key_exists(2, $matches) && in_array('buybutton', $matches[2])) {
        //Go through each instance of the buy button
        foreach ($matches[0] as $key => $value) {
            $attributes = str_replace(['[', ']', 'buybutton', ''], '', $value);

            $pattern = '/(\\w+)\s*=\\s*("[^"]*"|\'[^\']*\'|[^"\'\\s>]*)/';

            preg_match_all($pattern, $attributes, $matches, PREG_SET_ORDER);

            $attrs = array();

            foreach ($matches as $match) {
                if (($match[2][0] == '"' || $match[2][0] == "'") && $match[2][0] == $match[2][strlen($match[2])-1]) {
                    $match[2] = substr($match[2], 1, -1);
                }

                $name = strtolower($match[1]);

                $value = html_entity_decode($match[2]);

                $attrs[$name] = $value;

                if ($name == 'price') {
                    $newPrice =  str_replace('$', '', $attrs[$name]);
                    $postContent = str_replace('price="' . $attrs[$name] . '"]', 'price="'.$newPrice.'"]', $postContent);
                    if (!(is_numeric($newPrice)=== true)) {
                        $postContent = str_replace('price="' . $newPrice . '"]', 'price="0"]', $postContent);
                        if ($errors === '') {
                            $errors = 'The Buy Button price of  "' . $attrs[$name] . '" is incorrect. It must be a number and cannot contain a dollar sign.';
                        }
                    }
                }
            }
        }//end foreach loop

        global $wpdb;
        $where = array( 'ID' => $post->ID );
        $wpdb->update($wpdb->posts, array('post_content' => $postContent), $where);

        update_option('buy_button_errors', $errors);
    }//end if buybutton


    return;
}

add_action('save_post', 'fth_validate_post_buy_button', 1, 2);


/**
 * Display an error message if the buy button price is incorrect.
 */
function fth_buy_button_notice()
{

    $errors = get_option('buy_button_errors');

    if ($errors) {
        echo '<div class="error"><p>' . $errors . '</p></div>';

        //Clear The Error Message
        update_option('buy_button_errors', '');
    }
}

add_action('admin_notices', 'fth_buy_button_notice');
