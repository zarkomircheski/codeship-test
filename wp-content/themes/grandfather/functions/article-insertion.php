<?php
add_action('init', 'setup_rule_types');
function setup_rule_types()
{
    $ruleTypes = Fatherly\Rule\Rule::registerRuleClasses();
    foreach ($ruleTypes as $ruleType) {
        new $ruleType;
    }
}

function fth_get_acf_post_key_from_input_string($input)
{
    $data = $_POST['acf'];
    $keys = array_slice(explode("|", str_replace(array('acf', '[', ']'), array('', '', '|'), $input)), 0, -2);
    foreach ($keys as $key) {
        $data = $data[$key];
    }
    return $data;
}


function fth_assign_rule_ids_to_module($post_id)
{
    $current_module_ids = get_field('modules', $post_id);
    $new_module_ids = $_POST['acf']['field_5a9f0ffbf061c'];
    foreach ($current_module_ids as $current_module_id) {
        if (!in_array((string)$current_module_id->ID, $new_module_ids)) {
            /**
             * This means that this module has been removed from this rule group
             * and it needs to be removed from the modules post meta
             */
            $current_rule_groups = get_post_meta($current_module_id->ID, 'active_rule_groups', $post_id);
            $updated_rule_groups = array_diff($current_rule_groups, array($post_id));
            if (count($updated_rule_groups) > 0) {
                update_post_meta($current_module_id->ID, 'active_rule_groups', $updated_rule_groups);
            } else {
                /**
                 * This means that the module no longer belongs to any rule groups and so we need to delete its
                 * post meta
                 */
                delete_post_meta($current_module_id->ID, 'active_rule_groups');
            }
        }
    }
    foreach ($new_module_ids as $new_module_id) {
        $current_rule_groups = get_post_meta($new_module_id, 'active_rule_groups', true);
        if ($current_rule_groups) {
            if (!in_array($post_id, $current_rule_groups)) {
                $current_rule_groups[] = $post_id;
                update_post_meta($new_module_id, 'active_rule_groups', $current_rule_groups);
            }
        } else {
            $rule_groups = array($post_id);
            add_post_meta($new_module_id, 'active_rule_groups', $rule_groups);
        }
    }
}

add_action('save_post_rule_groups', 'fth_assign_rule_ids_to_module', 10, 1);


function fth_insert_after_paragraph($insertion, $paragraph_id, $content)
{
    $closing_p = '</p>';
    $paragraphs = explode($closing_p, $content);
    $success = false;
    foreach ($paragraphs as $index => $paragraph) {
        if (trim($paragraph)) {
            $paragraphs[$index] .= $closing_p;
        }
        $before = $paragraphs[$index];
        $after = $paragraphs[$index + 1];
        $testBefore = preg_match("/\[module/", $before);
        $testAfter = preg_match("/\[module/", $after);
        if ($paragraph_id == $index + 1) {
            if ($testBefore == false && $testAfter == false) {
                $paragraphs[$index] .= $insertion;
                $success = true;
            }
        }
    }
    $return = array(
        'success' => $success,
        'content' => implode('', $paragraphs)
    );
    return $return;
}

function fth_insert_after_node($insertion, $node_id, $contentNodes)
{
    $success = false;
    $inside_caption = false;
    foreach ($contentNodes as $index => $node) {
        //Remove text from text, except for script tags
        $node_text_only = strip_tags($node, '<script>');

        //Test to see if content is an empty string, and skip if is
        if (trim($node_text_only) == '' || trim($node_text_only) == '&nbsp;' || preg_replace(
            '#<script(.*?)>(.*?)</script>#is',
            '',
            $node_text_only
        ) == '') {
            continue;
        }

        $has_shortcode = fth_check_before_after_element_for_shortcode($contentNodes[$index], $contentNodes[$index + 1]);
        $has_h_tag = fth_check_before_element_for_h_tags($contentNodes[$index]);

        $has_caption = fth_check_for_caption_start_end($contentNodes[$index]);
        if ($has_caption == 'open') {
            $inside_caption = true;
        }
        if ($has_caption == 'closed') {
            $inside_caption = false;
        }
        if ($node_id == $index + 1) {
            if (!$has_shortcode && !$has_h_tag && !$inside_caption) {
                $contentNodes[$index] .= $insertion;
                $success = true;
            }
        }
    }

    $return = array(
        'success' => $success,
        'nodes' => $contentNodes
    );
    return $return;
}

function fth_check_before_after_element_for_shortcode($before, $after)
{
    $testBefore = preg_match("/\[module/", $before);
    $testAfter = preg_match("/\[module|\[buybutton/", $after);
    if (!$testBefore && !$testAfter) {
        return false;
    } else {
        return true;
    }
}

function fth_check_before_element_for_h_tags($before)
{
    $before = substr($before, -15);
    $testBefore = strposa($before, array("</h1>", "</h2>", "</h3>", "</h4>", "</h5>", "</h6>", "</strong></p>"));
    if (!$testBefore) {
        return false;
    } else {
        return true;
    }
}

function fth_get_root_html_elements_from_content($content)
{
    $doc = new DOMDocument();
    @$doc->loadHTML('<?xml encoding="utf-8" ?>' . $content);
    $xpath = new DOMXPath($doc);
    $xpath->registerNamespace("php", "http://php.net/xpath");
    $xpath->registerPhpFunctions('fth_check_for_shortcode');
    $xpathQueries = array(
        '//body/*',
        "//body/text()[php:functionString('fth_check_for_shortcode',.)]"
    );
    $htmlElements = array();
    foreach ($xpath->evaluate(implode(' | ', $xpathQueries)) as $node) {
        $htmlElements[] = $doc->saveHTML($node);
    }

    return $htmlElements;
}

function fth_check_for_shortcode($content)
{
    $pattern1 = get_shortcode_regex(); //This will match any self closing or opening shortcode tags
    $pattern2 = "\[[\/\[]?(?:)(?=[\s\]\/])(?:[^\[\]<>]+|<[^\[\]>]*>)*+\]\]?"; // This will match lines with only a closing shortcode tag.
    return preg_match("/($pattern1)|($pattern2)/", $content, $matches) > 0;
}

function fth_insert_after_list_item($listItems, $insertID, $moduleForInsertion)
{
    // We only need to attempt insertion if the slot we're inserting at actually exists.
    if (count($listItems) >= $insertID) {
        //We need to make sure that we dont have a module in the slot before or after where this module needs to go
        $check_before = array_key_exists('module', $listItems[($insertID - 1)]);
        $check_after = (is_array($listItems[($insertID + 1)])) ? array_key_exists('module', $listItems[($insertID + 1)]) : null;
        if (!$check_before && !$check_after) {
            array_splice($listItems, $insertID, 0, $moduleForInsertion);
            $return = array('success' => true, 'listItems' => $listItems);
        } else {
            $return = array('success' => false);
        }
    } else {
        $return = array('success' => true, 'listItems' => $listItems);
    }
    return $return;
}

function fth_check_for_caption_start_end($content)
{
    if (strpos($content, '[caption') !== false) {
        return 'open';
    }
    if (strpos($content, "[/caption]") !== false) {
        return 'closed';
    }
    return false;
}
