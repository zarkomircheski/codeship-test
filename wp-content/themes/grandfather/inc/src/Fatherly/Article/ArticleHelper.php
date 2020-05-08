<?php

namespace Fatherly\Article;

use Fatherly\Taxonomy\Tag\TagHelper as TagHelper;

/**
 * Class ArticleHelper
 * @package Fatherly\Article
 */
class ArticleHelper
{

    /**
     * articleHasFranchiseTag
     *
     * This method will accept an instance of `WP_Post` and then check to see if any of the tags on that post are
     * currently marked as a franchise and if so it will return that tag.
     *
     * @param \WP_Post $article
     * @return bool | \WP_Term
     */
    public static function articleHasFranchiseTag(\WP_Post $article)
    {
        $articleTags = get_the_tags($article->ID);
        if ($articleTags) {
            foreach ($articleTags as $articleTag) {
                if (TagHelper::isFranchise($articleTag)) {
                    return $articleTag;
                }
            }
        }
        return false;
    }
}
