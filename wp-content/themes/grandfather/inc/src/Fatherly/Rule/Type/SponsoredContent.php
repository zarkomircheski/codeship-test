<?php

namespace Fatherly\Rule\Type;

use Fatherly\Rule\Rule;
use Fatherly\Rule\RuleContract;

/**
 * Class SponsoredContent
 *
 * This is the Rule type class for the Hide Sponsored Content rule type.
 *
 * @package Fatherly\Rule\Type
 */
class SponsoredContent extends Rule implements RuleContract
{
    public function initialize()
    {
        $this->fieldKey = 'rule_hide_sponsored_content';
        $this->fieldLabel = __('Hide Sponsored Content', 'fatherly');
    }

    /**
     * validateRuleValue
     *
     * When a user saves a rule value for the Hide Sponsored Content rule type we check to make sure that the value
     * they save is the string "true" this is done so that we don't have to worry about calculating true and false
     * conditions on the '=='  and the '!==' operator. A value of true with only a change in the operator gets either
     * of the only two allowable conditions. If a user tries to save a value other than "true" we return a message
     * telling them that they can't do that.
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

        if ($value !== 'true') {
            $valid = sprintf("Rule value must be set to <strong>true</strong> for this rule.");
        }

        return $valid;
    }

    public function validateRuleOperator($valid, $value, $field, $input)
    {
    }

    /**
     * validateRulePassage
     *
     * This will determine if the current post validates for the given rule. The rule targets posts based on whether or
     * not they have the `hide_sponsored_content` ACF field set. Our switch statement will handle both and ultimately
     * return true or false indicating the rules passage.
     *
     * @param $ruleSettings
     * @param $postID
     * @return bool|void
     */
    public function validateRulePassage($ruleSettings, $postID)
    {
        $hideSponsoredContent = get_field('hide_sponsored_content', get_the_ID());
        switch ($ruleSettings['rule_operator']) {
            case '==':
                if ($hideSponsoredContent) {
                    return true;
                } else {
                    return false;
                }
                break;
            case '!=':
                if (!$hideSponsoredContent) {
                    return true;
                } else {
                    return false;
                }
                break;
        }
    }
}
