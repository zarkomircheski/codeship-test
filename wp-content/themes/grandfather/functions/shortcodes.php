<?php

/**
 * @param $atts
 * @param string $content This is optional and is used for extra data that may need to be passed down to a module when
 * rendering it. This is similar to contextual information passed by collections. This function expects `$content` to be
 * a base64 encoded JSON string. This is necessary due to issues that arise when passing JSON as a param to a shortcode.
 * @return string
 */
function module_shortcode($atts, $content = '')
{
    if (isset($atts['id'])) {
        if (get_post_status($atts['id']) == 'publish') {
            $moduleFields = get_fields($atts['id']);
            if (isset($atts['data'])) {
                $data = base64_decode($atts['data']);
                $module = \Fatherly\Page\Module::init()->setupModuleStandalone($moduleFields, json_decode($data));
            } else {
                $module = \Fatherly\Page\Module::init()->setupModuleStandalone($moduleFields);
            }

            ob_start();
            set_query_var('module', $module);
            echo sprintf("<div data-module-id='%d'>", $atts['id']);
            $module->renderBareModule($module->template);
            echo "</div>";
            return ob_get_clean();
        } else {
            return $content;
        }
    }
}

add_shortcode('module', 'module_shortcode');


function product_shortcode($atts, $content = '')
{
    global $product_attributes;
    $product_attributes = $atts;
    if (array_key_exists('asin', $atts)) {
        //product is from amazon
        $product = fatherly_apu_get_product_by_asin($atts['asin']);
        if (!$product['product_price']) {
            $article_url = get_the_permalink(get_the_ID());
            error_log(sprintf("Product with ASIN: %s does not have a price listed on article \"%s\" ", $atts['asin'], $article_url));
        }
        $template_data = array();
        foreach ($product as $field => $value) {
            switch ($field) {
                case 'product_image':
                    $template_data['image_url'] = $value;
                    break;
                case 'product_button_text':
                    $template_data['btn_text'] = $value;
                    break;
                default:
                    $template_data[str_replace('product_', '', $field)] = $value;
            }
        }
        if (!empty($atts['description'])) {
            $template_data['description'] = $atts['description'];
        }
        if (!empty($atts['title'])) {
            $template_data['title'] = $atts['title'];
        }
        if (!empty($atts['image'])) {
            $template_data['image_url'] = $atts['image'];
        }
        if ($atts['tracking'] && !empty($atts['tracking'])) {
            $template_data['link'] = str_replace("tag=fatherlycom-20", sprintf("tag=%s", $atts['tracking']), $template_data['link']);
        }
        set_query_var('product_data', $template_data);
    } else {
        if ($atts['image_id']) {
            $atts['image_url'] = fth_img(array('width' => 626, 'height' => 500,
                'retina' => false, 'attachment_id' => $atts['image_id'], 'cropType' => false, 'fit' => 'clip'));
        }
        if ($atts['tracking'] && !empty($atts['tracking'])) {
            $urlQuery = parse_url($atts['link'], PHP_URL_QUERY);
            if ($urlQuery) {
                $atts['link'] .= sprintf("&tag=%s", $atts['tracking']);
            } else {
                $atts['link'] .= sprintf("?tag=%s", $atts['tracking']);
            }
        }
        set_query_var('product_data', $atts);
    }
    if (isset($atts['asin']) || isset($atts['link'])) {
        ob_start();
        get_template_part('partials/product', 'shortcode');
        return ob_get_clean();
    } else {
        return $content;
    }
}

add_shortcode('product', 'product_shortcode');
