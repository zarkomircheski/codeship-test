<?php

namespace Fatherly\Rule\Type;

use Fatherly\Rule\Rule;
use Fatherly\Rule\RuleContract;

/**
 * Class NotSafeForAdvertisers
 *
 * This is the Rule type class for the Not Safe For Advertisers rule type
 *
 * @package Fatherly\Rule\Type
 */
class NotSafeForAdvertisers extends Rule implements RuleContract
{
    public function initialize()
    {
        $this->fieldKey = 'nsfa';
        $this->fieldLabel = __('Not Safe For Advertisers', 'fatherly');
    }

    /**
     * validateRuleValue
     *
     * When a user saves a rule value for the Not Safe For Advertisers rule type we check to make sure that the value
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
     * not they have the `not_safe_for_advertisers` ACF field set. Our switch statement will handle both and ultimately
     * return true or false indicating the rules passage.
     *
     * @param $ruleSettings
     * @param $postID
     * @return bool|void
     */
    public function validateRulePassage($ruleSettings, $postID)
    {
        $nsfa = get_field('not_safe_for_advertisers', get_the_ID());
        switch ($ruleSettings['rule_operator']) {
            case '==':
                if ($nsfa) {
                    return true;
                } else {
                    return false;
                }
                break;
            case '!=':
                if (!$nsfa) {
                    return true;
                } else {
                    return false;
                }
                break;
        }
    }
}
