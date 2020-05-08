<?php

namespace Fatherly\Rule;

/**
 * Interface RuleContract
 *
 * Any rule types in our rule groups section on the backend need to implement this interface in order for
 * everything to work correctly.
 *
 * @package Fatherly\Rule
 */
interface RuleContract
{
    public function initialize();

    public function validateRuleValue($valid, $value, $field, $input);

    public function validateRuleOperator($valid, $value, $field, $input);

    public function validateRulePassage($ruleSettings, $postID);
}
