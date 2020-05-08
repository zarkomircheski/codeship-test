<?php

namespace Fatherly\Listicle;

use Fatherly\Rewrite\ListicleRules;
use FatherlyPlugin\APU\AmazonHelper;
use WPSEO_Sitemaps_Cache as SitemapCache;

class Helper
{
    public static $listItems;

    /**
     * isListicle
     *
     * Accepts an instance of \WP_Post and will return a boolean indication of whether or not the post is a listicle
     *
     * @param $post
     * @return bool
     */
    public static function isListicle($post)
    {
        if (get_field('is_listicle', $post->ID)) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * getPublishedListItem
     *
     * This method will return the individual item, the previous and next item
     * so next and previous buttons can be populated
     *
     * @param $post
     * @param $list_item
     * @return array
     */
    public static function getPublishedListItem($post, $list_item_uuid)
    {
        $listItems = self::getPublishedListItems($post);
        for ($i = 0; $i < count($listItems); $i++) {
            if ($listItems[$i]['uuid'] == $list_item_uuid) {
                $listItems[$i]['index'] = $i;
                $return = array($listItems[$i]);
                if ($listItems[$i - 1]) {
                    array_push($return, $listItems[$i - 1]);
                }
                if ($listItems[$i + 1]) {
                    array_push($return, $listItems[$i + 1]);
                }
                return $return;
            }
        }
        return;
    }

    /**
     * getPublishedListItems
     *
     * This method will return all of the published list items for a listicle.
     *
     * @param $post
     * @return mixed|null|void
     */
    public static function getPublishedListItems($post, $performInsertion = false)
    {
        $listItems = get_field('list_items', $post->ID);
        if ($listItems) :
            foreach ($listItems as $key => $listItem) {
                if (!$listItem['is_published']) {
                    unset($listItems[$key]);
                } else {
                    $listItems[$key]['item_url'] = get_permalink($post) . sanitize_title($listItem['headline']) . "-{$listItem['uuid']}/";
                    $listItems[$key]['item_slug'] = get_permalink($post) . sanitize_title($listItem['headline']) . "-{$listItem['uuid']}/";
                }
            }
            if ($performInsertion) {
                return apply_filters('the_listicle_items', array_values($listItems));
            } else {
                return array_values($listItems);
            }
        endif;
    }

    /**
     * getPublishedListItemsPaged
     *
     * This method will return a subset of published list items from a listicle.
     *
     * @param $post
     * @param int $amount
     * @param int $offset
     * @return array
     */
    public static function getPublishedListItemsPaged($post, $amount = 10, $offset = 0, $direction = "down")
    {
        $listItems = self::getPublishedListItems($post);
        $return = array();
        for ($i = 0; $i < $amount; $i++) {
            if ($listItems[$offset + $i] != null) {
                $return[] = $listItems[$offset + $i];
            } else {
                break;
            }
        }
        $return = apply_filters('the_listicle_items', $return);
        // Check to see if there is more content
        if ($direction == 'down') {
            array_push($return, $listItems[$offset + $amount] != null ? $listItems[$offset + $amount] : false);
        } else {
            array_push($return, $listItems[$offset - 1] != null ? $listItems[$offset - 1] : false);
        }
        return $return;
    }

    /**
     * removePostIDFromListiclePosts
     *
     * This method is used when a post that was a listicle is updated and is no longer a listicle. This method will
     * update our listicle_posts option so that we reflect this change.
     *
     * @param $postID
     */
    public static function removePostIDFromListiclePosts($postID)
    {
        $listiclePostIDs = get_option('listicle_posts');
        update_option('listicle_posts', array_diff($listiclePostIDs, array($postID)));
    }

    /**
     * handleListiclePostCRUD
     *
     * Since there are a lot of operations that need to happen when we save a listicle it makes sense to have the hook
     * call one helper method which in turn will call all the other methods that need to be fired.
     *
     * @param $post
     * @param $listItems
     */
    public static function handleListiclePostCRUD($post, $listItems)
    {
        self::$listItems = $listItems;
        self::determineIfAmazonLinkAndUpdateFields($post->ID, self::$listItems);
        self::generateListItemUUIDSAndSave($post->ID, self::$listItems);
        self::slugifyListItemsAndSave($post->ID, self::$listItems);
        self::updateRecordOfListiclePosts($post->ID);
        self::updateLastModifiedForListsSitemap($post);
        SitemapCache::invalidate('lists');
    }

    public static function determineIfAmazonLinkAndUpdateFields($postID, $listItems)
    {
        if (have_rows('list_items', $postID)) {
            while (have_rows('list_items', $postID)) {
                the_row();
                if ($productDataJSON = get_sub_field('product_data')) {
                    $productData = json_decode($productDataJSON);
                    $headline = get_sub_field('headline');
                    $image = get_sub_field('image');
                    if (isset($productData->product_asin)) {
                        $amazonProduct = AmazonHelper::init()->fetchProductFromDBByASIN($productData->product_asin);
                        $headline = get_sub_field('headline');
                        if (!$headline) {
                            update_sub_field('headline', $amazonProduct['product_title']);
                        }
                    } else {
                        if (!$headline) {
                            update_sub_field('headline', $productData->product_title);
                        }
                        if (!$image) {
                            update_sub_field('image', $productData->product_image_id);
                        }
                    }
                }
            }
        }
    }

    public static function generateListItemUUIDSAndSave($postID, $listItems)
    {
        $uuids = array();
        foreach ($listItems as $listItem) {
            if (!empty($listItem['uuid'])) {
                $uuids[] = (int)$listItem['uuid'];
            }
        }
        if (have_rows('list_items', $postID)) {
            while (have_rows('list_items', $postID)) {
                the_row();
                $uuid = get_sub_field('uuid');
                if (empty($uuid)) {
                    do {
                        $rand = rand(100, 999);
                    } while (in_array($rand, $uuids));
                    $uuids[] = $rand;
                    update_sub_field('uuid', $rand);
                    self::$listItems[(get_row_index() - 1)]['uuid'] = $rand;
                }
            }
        }
    }

    /**
     * slugifyListItemsAndSave
     *
     * This method is responsible for turning list item headlines into slugs and then saving these slugs to the post in
     * the form of post meta. This makes it easy to retrieve all the valid list item slugs for a listicle so that when
     * someone visits the url of a listicle item we can more easily determine the validity of the url.
     *
     * @param $postID
     * @param $listItems
     *
     */
    public static function slugifyListItemsAndSave($postID, $listItems)
    {
        $listItemSlugs = array();
        foreach ($listItems as $listItem) {
            if ($listItem['is_published']) {
                $listItemSlugs[$listItem['uuid']] = sanitize_title($listItem['headline']) . '-' . $listItem['uuid'];
            }
        }
        if (get_post_meta($postID, 'list_item_slugs')) {
            update_post_meta($postID, 'list_item_slugs', $listItemSlugs);
        } else {
            add_post_meta($postID, 'list_item_slugs', $listItemSlugs);
        }
    }

    /**
     * updateRecordOfListiclePosts
     *
     * This method saves an option in the `options` table that is an array containing all the post ids of posts that
     *  are marked as a listicle. This is done primarily to make the lists sitemap more performant. With this in place
     * we will not have to do a complex query to fetch all the listicle posts we will need to only load this option and
     * loop through the post ids.
     *
     * @param $postID
     */
    public static function updateRecordOfListiclePosts($postID)
    {
        if ($listiclePosts = get_option('listicle_posts')) {
            if (!in_array($postID, $listiclePosts)) {
                $listiclePosts[] = $postID;
                update_option('listicle_posts', $listiclePosts, false);
                add_option('flush_listicle_rewrite_rules', 1);
            }
        } else {
            add_option('listicle_posts', array($postID), '', false);
        }
    }

    /**
     * updateLastModifiedForListsSitemap
     *
     * When a listicle post is updated we need to also update the last modified date for our lists sitemap. Since the
     * lists sitemap is just a collection of list items its last updated time will be the last time a listicle was
     * saved or updated. This will save a value in the `options` table of the time so that crawlers will know to
     * re-crawl the lists sitemap
     *
     * @param $post
     */
    public static function updateLastModifiedForListsSitemap($post)
    {

        if ($listicleLastModified = get_option('listicle_last_modified')) {
            if ($listicleLastModified !== $post->post_modified) {
                update_option('listicle_last_modified', $post->post_modified, false);
            }
        } else {
            add_option('listicle_last_modified', $post->post_modified, '', false);
        }
    }
}
