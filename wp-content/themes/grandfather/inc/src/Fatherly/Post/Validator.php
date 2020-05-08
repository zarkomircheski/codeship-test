<?php

namespace Fatherly\Post;

class Validator
{
    public $message;
    public $input;

    /**
     * validateAges
     * This method is responsible for validating the age range fields on post save.
     *
     * The criteria we check is listed below.
     *  - If the n/a field is checked then validation is true regardless of other field values.
     *  - If n/a is not checked and the value for the age to field is greater than the value of the age from field then
     *  the validation is true.
     * @param $post
     * @return bool
     */
    public function validateAges($post)
    {
        // ACF field id for the age from field
        $ageRangeFromFieldId = 'field_5a9589f4a8d5c';
        // ACF field id for the age to field
        $ageRangeToFieldId = 'field_5a958b38de303';
        // ACF field id for the n/a field
        $ageRangeNAFieldId = 'field_5a958a57a8d5d';

        $ageRangeFromFieldValue = intval($post['acf'][$ageRangeFromFieldId]);
        $ageRangeToFieldValue = intval($post['acf'][$ageRangeToFieldId]);
        $ageRangeNAFieldValue = $post['acf'][$ageRangeNAFieldId];

        // If the n/a field is checked then we want to return early.
        if ($ageRangeNAFieldValue === '1') {
            return true;
        }

        // If the age range to value is greater than zero and also greater than the from value then return true.
        if ($ageRangeToFieldValue > -2 && $ageRangeFromFieldValue > -2 && $ageRangeToFieldValue >= $ageRangeFromFieldValue) {
            return true;
        }


        /**
         * If we get here then that means that the post was not able to be successfully validated. We need to set the
         * message that will show the user feedback informing them of this and then we return false.
         **/
        $this->input = sprintf("acf[%s]", $ageRangeFromFieldId);
        $this->message = "<strong>There seems to be an error with your choice in the age range box.<strong>";
        return false;
    }
}
