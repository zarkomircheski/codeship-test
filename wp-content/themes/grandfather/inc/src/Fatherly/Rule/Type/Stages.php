<?php

namespace Fatherly\Rule\Type;

use Fatherly\Rule\Rule;
use Fatherly\Rule\RuleContract;

/**
 * Class Category
 *
 * This is the Rule type class for the Category rule type.
 *
 * @package Fatherly\Rule\Type
 */
class Stages extends Rule implements RuleContract
{
    public function initialize()
    {
        $this->fieldKey = 'rule_stages';
        $this->fieldLabel = __('Stage', 'fatherly');
    }

    /**
     * validateRuleValue
     *
     * When a user saves a rule value for the Category rule type we check to make sure that the value matches a valid
     * category slug. For this rule type a user can set a comma separated list of category slugs as well as just one
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
        //check if multiple slugs have been passed
        if (strpos($value, ",") !== false) {
            $slugs = explode(',', $value);
            foreach ($slugs as $slug) {
                $stage = wpcom_vip_term_exists($slug, 'stages');
                if ($stage === 0 || $stage === null) {
                    $valid = sprintf("The slug %s is not a valid stage slug", $slug);
                    return $valid;
                }
            }
        } else {
            $stage = wpcom_vip_term_exists($value, 'stages');
            if ($stage === 0 || $stage === null) {
                $valid = sprintf("The slug %s is not a valid stage slug", $value);
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
     * category (==) or posts that do not have the category (!==) our switch statement will handle both and ultimately
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
                if (has_term(explode(',', $ruleSettings['rule_value']), 'stages')) {
                    return true;
                } else {
                    return false;
                }
                break;
            case '!=':
                if (!has_term(explode(',', $ruleSettings['rule_value']), 'stages')) {
                    return true;
                } else {
                    return false;
                }
                break;
        }
    }
}
