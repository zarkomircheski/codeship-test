<?php

namespace Fatherly\Taxonomy\Tag;

/**
 * Class TagHelper
 * @package Fatherly\Taxonomy\Tag
 */
class TagHelper
{

    /**
     * isFranchise
     *
     * This method will accept an instance of `WP_Term` the taxonomy of the term passed should be `post_tag`.
     * This method will then check the tag to see if it has a value in the ACF field `is_franchise` and if so it will
     * return true. If the tag does not have a value for this field then `get_field()` will return null. If the tag has
     * a value but it's set to no then `get_field()` will return false. We're simply returning the output of
     * `get_field()`
     *
     * @param \WP_Term $tag
     * @return mixed|null|void
     */
    public static function isFranchise(\WP_Term $tag)
    {
        return get_field('is_franchise', $tag);
    }
}
