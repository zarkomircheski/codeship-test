<?php

namespace Fatherly\Page;

use Fatherly\Page\Collection as Collection;

class Helper
{
    public static function fetchPostsForLandingPage($fields, $count = null)
    {
        $args = array('tax_query' => array('relation' => 'OR'));
        if ($fields['parenting_page']) {
            //This means that we will need to load posts from terms in the stages taxonomy
            $args['tax_query'][] =
                array(
                    'taxonomy' => 'stages',
                    'field' => 'term_id',
                    'terms' => $fields['parenting_stages'],
                    'operator' => 'IN'
                );
        }
        if ($fields['related_tags']) {
            //This means we need to load all posts that exist in the following tags.
            $args['tax_query'][] =
                array(
                    'taxonomy' => 'post_tag',
                    'field' => 'term_id',
                    'terms' => $fields['related_tags'],
                    'operator' => 'IN'
                );
        }
        if ($fields['paged']) {
            $args['paged'] = $fields['paged'];
        }
        if ($count) {
            $args['posts_per_page'] = $count;
        }
        if ($fields['excluded_posts']) {
            $args['post__not_in'] = $fields['excluded_posts'];
        }
        $args['no_found_rows'] = true;
        return new \WP_Query($args);
    }

    public static function fetchPostsForContentHubModule1($fields, $count = null, $categoryID = null)
    {
        $excludedPosts = Collection::$postIdsToExclude;
        $args = array('tax_query' => array('relation' => 'AND'));
        $subQuery = array('relation' => 'OR');
        if ($fields['parenting_page']) {
            //This means that we will need to load posts from terms in the stages taxonomy
            $subQuery[] =
                array(
                    'taxonomy' => 'stages',
                    'field' => 'term_id',
                    'terms' => $fields['parenting_stages'],
                    'operator' => 'IN'
                );
        }
        if ($fields['related_tags']) {
            //This means we need to load all posts that exist in the following tags.
            $subQuery[] =
                array(
                    'taxonomy' => 'post_tag',
                    'field' => 'term_id',
                    'terms' => $fields['related_tags'],
                    'operator' => 'IN'
                );
        }
        //This ensures that posts in the news tag are excluded and the post is in the parenting category.
        $args['tax_query'][] = array(
            'relation' => 'AND',
            array(
                'taxonomy' => 'category',
                'field' => 'slug',
                'terms' => 'news',
                'operator' => 'NOT IN',
            ),
            array(
                'taxonomy' => 'category',
                'field' => 'slug',
                'terms' => 'parenting',
                'operator' => 'IN',
            ),
        );

        if (isset($fields['paged'])) {
            $args['paged'] = $fields['paged'];
        }
        if ($count) {
            $args['posts_per_page'] = $count;
        }
        if (is_array($excludedPosts) && count($excludedPosts) > 0) {
            $args['post__not_in'] = $excludedPosts;
        }

        if ($categoryID) {
            /**
             * This will be set when we pass a category to this content hub to further filter results.
             * Mainly used on sub-category pages that reside under parenting.
             **/
            $args['cat'] = $categoryID;
        }
        $args['no_found_rows'] = true;
        $args['post_status'] = 'publish';
        $args['tax_query'][] = $subQuery;
        $query = new \WP_Query($args);
        Collection::addPostIdToExclusion(wp_list_pluck($query->posts, 'ID'));
        return $query;
    }

    public static function fetchNewsForNav()
    {
        $args = array(
            'no_found_rows' => true,
            'posts_per_page' => 3,
            'tax_query' => array('relation' => 'OR')
        );
        $args['tax_query'][] =
            array(
                'taxonomy' => 'category',
                'field' => 'slug',
                'terms' => 'news',
                'operator' => 'IN'
            );
        return new \WP_Query($args);
    }

    /**
     * getPostCategory
     *
     * Method will get the category for a post.
     *
     * @param $post post to get the category for
     * @param bool $returnSub Whether or not to return the sub category for a post or the parent. DEFAULT: parent
     * @return \WP_Term
     */
    public static function getPostCategory($post, $returnSub = false)
    {
        $category = get_the_category($post->ID);
        if (count($category) > 0) {
            if ($returnSub) {
                foreach ($category as $cat) {
                    if ($cat->category_parent !== 0) {
                        return $cat;
                    }
                }
                return $category[0];
            } else {
                $categoryParentId = $category[0]->category_parent;
                if ($categoryParentId !== 0) {
                    return get_term($categoryParentId, 'category');
                } else {
                    return $category[0];
                }
            }
        }
    }

    public static function getPostAuthorNameAndUrl($post)
    {
        return array(
            'name' => get_the_author_meta('display_name', $post->post_author),
            'url' => get_author_posts_url($post->post_author)
        );
    }

    public static function getPostsByTag($tagID, $count = 1000)
    {
        $args = array(
            'no_found_rows' => true,
            'posts_per_page' => $count,
            'tag__in' => $tagID,
            'post_status' => 'publish',
            'tax_query' => array(
                'relation' => 'AND',
                array(
                    //This ensures that posts in the news category are excluded from the results here.
                    array(
                        'taxonomy' => 'category',
                        'field' => 'slug',
                        'terms' => 'news',
                        'operator' => 'NOT IN'
                    )
                )
            )
        );
        $query = new \WP_Query($args);
        Collection::addPostIdToExclusion(wp_list_pluck($query->posts, 'ID'));
        return $query->posts;
    }

    public static function getPostsByCategory($categoryID, $count = 1000)
    {
        $args = array(
            'no_found_rows' => true,
            'posts_per_page' => $count,
            'cat' => $categoryID,
            'post_status' => 'publish',
            'meta_query' => array(
                'relation' => 'AND',
                array(
                    'key' => 'not_safe_for_advertisers',
                    'value' => 1,
                    'compare' => '!=',
                ),
            ),
        );

        $query = new \WP_Query($args);
        Collection::addPostIdToExclusion(wp_list_pluck($query->posts, 'ID'));
        return $query->posts;
    }

    public static function getPostsByAuthor($authorID, $count = 1000)
    {
        $args = array(
            'no_found_rows' => true,
            'posts_per_page' => $count,
            'post_status' => 'publish',
            'author' => $authorID,
            'meta_query' => array(
                'relation' => 'AND',
                array(
                    'key' => 'not_safe_for_advertisers',
                    'value' => 1,
                    'compare' => '!=',
                ),
            ),
        );
        $query = new \WP_Query($args);
        Collection::addPostIdToExclusion(wp_list_pluck($query->posts, 'ID'));
        return $query->posts;
    }

    public static function getPostsByParentingStage($stageID, $count = 1000, $limitToCategory = false)
    {
        $args = array(
            'no_found_rows' => true,
            'posts_per_page' => $count,
            'post_status' => 'publish',
            'meta_query' => array(
                'relation' => 'AND',
                array(
                    'key' => 'not_safe_for_advertisers',
                    'value' => 1,
                    'compare' => '!=',
                ),
            ),
        );
        if (is_array($stageID)) {
            $args['tax_query'] = array('relation' => 'OR');
            foreach ($stageID as $Id) {
                $args['tax_query'][] = array(
                    'taxonomy' => 'stages',
                    'field' => 'id',
                    'terms' => $Id
                );
            }
        } else {
            $args['tax_query'] = array(
                array(
                    'taxonomy' => 'stages',
                    'field' => 'id',
                    'terms' => $stageID
                )
            );
        }
        if ($limitToCategory) {
            $args['category_name'] = 'parenting';
        }
        $query = new \WP_Query($args);
        Collection::addPostIdToExclusion(wp_list_pluck($query->posts, 'ID'));
        return $query->posts;
    }

    public static function buildCategoryLinkArray($categories)
    {
        $return = array();
        foreach ($categories as $category) {
            $return[] = sprintf('<a href="%s" data-ev-loc="Feed" data-ev-name="Category Link" data-ev-val="%2$s">%2$s</a>', $category['url'], $category['name']);
        }
        return $return;
    }

    public static function isHomepageTemplate()
    {
        if (is_front_page() && !is_home()) {
            return true;
        }
        if (strpos(get_page_template(), 'homepagev2') !== false) {
            return true;
        }
        if (get_post_type() == 'hp_collection') {
            return true;
        }
        if (get_field('content_collection', get_the_ID())) {
            return true;
        }
        if (is_category() || is_tag() || is_tax()) {
            $term = get_queried_object();
            if (get_field('content_collection', $term)) {
                return true;
            }
            if (is_tag() && get_field('default_tag_collection', 'option')) {
                return true;
            }
            if ($term->parent !== 0) {
                $parentTerm = get_term($term->parent, (is_category()) ? 'category' : 'post_tag');
                if (get_field('content_collection', $parentTerm)) {
                    return true;
                }
            }
        }
        if (get_field('default_author_collection', 'option') && is_author()) {
            return true;
        }
        if (get_post_type() === 'hp_module') {
            return true;
        }
        return false;
    }


    public static function movePostsFromTagToCat($tagFrom, $catTo)
    {
        $args = array(
            'posts_per_page' => 25,
            'tag_id' => $tagFrom
        );
        $updatedCategory = get_category($catTo);
        $postsQuery = new \WP_Query($args);
        $return = array();
        foreach ($postsQuery->posts as $post) {
            $tags = get_the_tags($post->ID);
            $updatedTags = array();
            foreach ($tags as $tag) {
                if ($tag->term_id !== (int)$tagFrom) {
                    $updatedTags[] = $tag;
                }
            }
            wp_set_post_categories($post->ID, $updatedCategory, false);
            wp_set_post_terms($post->ID, $updatedTags, 'post_tag', false);
        }
        if ($postsQuery->post_count !== 25) {
            $return['more'] = false;
        } else {
            $return['more'] = true;
        }
        $return['count'] = $postsQuery->post_count;
        return $return;
    }
}
