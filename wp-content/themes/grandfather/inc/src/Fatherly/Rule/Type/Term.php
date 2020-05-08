<?php

namespace Fatherly\Rule\Type;

use Fatherly\Rule\Rule;
use Fatherly\Rule\RuleContract;

/**
 * Class Term
 *
 * This is the Rule type class for the Term rule type.
 *
 * @package Fatherly\Rule\Type
 */
class Term extends Rule implements RuleContract
{
    public function initialize()
    {
        $this->fieldKey = 'rule_tag';
        $this->fieldLabel = __('Tag', 'fatherly');
    }

    /**
     * validateRuleValue
     *
     * When a user saves a rule value for the Tag rule type we check to make sure that the value matches a valid
     * tag slug. For this rule type a user can set a comma separated list of tag slugs as well as just one
     * slug and we will account for it.
     *
     * @param $valid
     * @param $value
     * @param $field
     * @param $input
     * @return string|void
     */
    public function validateRuleValue($valid, $value, $field, $input)
    {
        // bail early if value is already invalid
        if (!$valid) {
            return $valid;
        }
        $ruleData = fth_get_acf_post_key_from_input_string($input);
        // Check to make sure the rule is of this type
        if ($ruleData[$this->ruleTypeFieldKey] !== $this->fieldKey) {
            return $valid;
        }
        if (strpos($value, ",") !== false) {
            $slugs = explode(',', $value);
            foreach ($slugs as $slug) {
                $tag = term_exists($slug, 'post_tag');
                if ($tag === 0 || $tag === null) {
                    $valid = sprintf("The slug %s is not a valid post tag slug", $slug);
                    return $valid;
                }
            }
        } else {
            $tag = term_exists($value, 'post_tag');
            if ($tag === 0 || $tag === null) {
                $valid = sprintf("The slug %s is not a valid post tag slug", $value);
                return $valid;
            }
        }
        return $valid;
    }

    public function validateRuleOperator($valid, $value, $field, $input)
    {
    }

    /**
     * validateRulePassage
     *
     * This will determine if the current post validates for the given rule. The rule can target posts that have the
     * tag (==) or posts that do not have the tag (!==) our switch statement will handle both and ultimately
     * return true or false indicating the rules passage.
     *
     * @param $ruleSettings
     * @param $postID
     * @return bool|void
     */
    public function validateRulePassage($ruleSettings, $postID)
    {
        switch ($ruleSettings['rule_operator']) {
            case '==':
                if (has_tag(explode(',', $ruleSettings['rule_value']))) {
                    return true;
                } else {
                    return false;
                }
                break;
            case '!=':
                if (!has_tag(explode(',', $ruleSettings['rule_value']))) {
                    return true;
                } else {
                    return false;
                }
                break;
        }
    }
}
