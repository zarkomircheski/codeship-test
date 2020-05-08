<?php

namespace Fatherly\Rule;

/**
 * Class Helper
 *
 * This class exists as an abstraction for processing rules on an article for modules that may need to be inserted.
 * It makes it where processing the rules is as easy as calling a method on this class with the rule group and the
 * post id.
 *
 * @package Fatherly\Rule
 */
class Helper
{
    /**
     * init
     *
     * Helper class factory method.
     *
     * @return Helper
     */
    public static function init()
    {
        return new self;
    }

    /**
     * processRuleGroups
     *
     * This method accepts the ruleGroups and the postID and will loop over and process each rule group and if all pass
     * then the module will be shown.
     *
     * @param $ruleGroup
     * @param $postID
     * @return bool
     */
    public function processRuleGroups($ruleGroups, $postID)
    {
        foreach ($ruleGroups as $ruleGroup) {
            if (!$this->processRuleGroup($ruleGroup, $postID)) {
                return false;
            }
        }
        return true;
    }

    /**
     * processRuleGroup
     *
     * This method processes individual rule groups. It will take all of the rules for a rule group as well as the post
     * id and then determine if the rule group passes for this post.
     *
     * @param $ruleGroup
     * @param $postID
     * @return bool
     */
    public function processRuleGroup($ruleGroup, $postID)
    {
        $rules = get_fields($ruleGroup);
        foreach ($rules['rules_or'] as $rulesOr) {
            foreach ($rulesOr['rules_and'] as $rulesAnd) {
                $passes = apply_filters(sprintf('fth_validate_rule/type=%s', $rulesAnd['rule_type']), $rulesAnd, $postID);
                if ($passes === false) {
                    break;
                }
            }
            if ($passes === true) {
                return true;
            }
        }
        return false;
    }
}
