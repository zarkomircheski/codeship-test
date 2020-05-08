<?php

namespace Fatherly\Rule;

/**
 * Class Rule
 *
 * This is the base Rule class and all rule types extend this class. This class sets up all the filters that will
 * need to fire for each sub class. This also sets up the ACF field keys that will need to be targeted when validating
 * rule value and operators as well as when inserting the rule type into the select box.
 *
 * @package Fatherly\Rule
 */
class Rule implements RuleContract
{
    /**
     * Rules will need a label
     * Rules will need a validation method for acceptable values for the rule value
     * Rules will need a method to check if a rule passes or not
     */
    /** $ruleTypes
     *
     * Contains all the sub classes that have extended this class which are our rule types.
     *
     * @var array
     */
    public static $ruleTypes = array();
    public $fieldKey;
    public $fieldLabel;
    public $ruleTypeFieldKey = "field_5a9f05f5c5ada";
    public $ruleOperatorFieldKey = "field_5a9f062ec5adb";
    public $ruleValueFieldKey = "field_5a9f0696c5adc";

    /**
     * Rule constructor.
     *
     * This calls the initialize method which is empty on the base class but is used on all the sub classes so we put
     * the call here so that when this constructor is fired on the sub classes it will call intialize on the sub class
     *
     * This method also will setup all the filters necessary for setting up the field for the rule in ACF as well as
     * the filters for validating the rule values and validating whether or not a rule passes for a given module.
     */
    public function __construct()
    {
        $this->initialize();
        add_filter('acf/load_field/name=rule_type', array($this, 'addRuleType'), 10, 1);
        add_filter('acf/validate_value/name=rule_value', array($this, 'validateRuleValue'), 10, 4);
        add_filter(sprintf("fth_validate_rule/type=%s", $this->fieldKey), array($this, 'validateRulePassage'), 10, 2);
    }

    /**
     * initialize
     *
     * This method is used on the individual rule type classes to set the field key and field label for the select field
     * in ACF
     */
    public function initialize()
    {
        //Do nothing
    }

    /**
     * validateRuleValue
     *
     * This method is used on the individual rule type classes to ensure that when a user saves a value for a rule type
     * it passes some validation. An example would be making sure that if a user sets a value on the category rule type
     * that the value saved is an actual category.
     *
     * @param $valid
     * @param $value
     * @param $field
     * @param $input
     */
    public function validateRuleValue($valid, $value, $field, $input)
    {
        //Do nothing
    }

    /**
     * validateRuleOperator
     *
     * This allows individual rule sub classes to validate the rule operator saved if needed. For instances where a
     * user saves an operator like `>` in a situation where it doesn't make sense.
     *
     * @param $valid
     * @param $value
     * @param $field
     * @param $input
     */
    public function validateRuleOperator($valid, $value, $field, $input)
    {
        //Do nothing
    }

    /**
     * validateRulePassage
     *
     * This method is implemented on all the rule type classes and is the method used to determine if a rule passes or
     * not for a module on an article.
     *
     * @param $ruleSettings
     * @param $postID
     */
    public function validateRulePassage($ruleSettings, $postID)
    {
        //Do nothing
    }

    /**
     * addRuleType
     *
     * This method is called to add the rule type to the ACF select field on the backend. The information is setup
     * inside the initialize method on the individual rule type classes.
     *
     * @param $field
     * @return mixed
     */
    public function addRuleType($field)
    {
        $field['choices'][$this->fieldKey] = $this->fieldLabel;
        asort($field['choices']);
        return $field;
    }

    /**
     * registerRuleClasses
     *
     * This method is called inside the article-insertion.php file and it will return a list of all of our rule types
     * and will then go through and initialize new instances of each of those classes.
     *
     * @return array
     */
    public static function registerRuleClasses()
    {
        foreach (new \DirectoryIterator(__DIR__ . '/Type') as $ruleTypeInfo) {
            if ($ruleTypeInfo->isDot()) {
                continue;
            }
            $name = str_replace(".php", "", $ruleTypeInfo->getFilename());
            self::$ruleTypes[] = "Fatherly\Rule\Type\\" . $name;
        }
        return self::$ruleTypes;
    }
}
