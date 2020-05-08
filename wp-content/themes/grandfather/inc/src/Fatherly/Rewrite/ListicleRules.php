<?php

namespace Fatherly\Rewrite;

/**
 * Class ListicleRules
 * @package Fatherly\Rewrite
 */
class ListicleRules
{
    public $listItemsACFFieldID = 'field_5b68633f33a34';
    public $isListItemACFFieldID = 'field_5b6862d933a32';

    public function __construct()
    {
        add_action('init', array($this, 'setupRewriteRules'), 10, 0);
        add_action('init', array($this, 'setupRewriteTag'), 10, 0);
    }

    public static function init()
    {
        return new self;
    }

    public static function setupRewriteRules()
    {

        $listiclePosts = get_option('listicle_posts');
        foreach ($listiclePosts as $listiclePost) {
            $postSlug = str_replace(get_site_url() . '/', "", get_the_permalink($listiclePost));
            $listicleStatus = get_post_status($listiclePost);
            if (!empty($postSlug) && $listicleStatus !== 'draft') {
                $rewriteRegex = sprintf("^%s([^/]*)/?$", $postSlug);
                $rewriteQuery = sprintf('index.php?p=%d&list_item=$matches[1]', $listiclePost);
                add_rewrite_rule($rewriteRegex, $rewriteQuery, 'top');
            }
        }
        if (get_option('flush_listicle_rewrite_rules')) {
            flush_rewrite_rules(false);
            delete_option('flush_listicle_rewrite_rules');
        }
    }

    public function isItemAListicle($postID)
    {
        $listicleField = $_POST['acf'][$this->isListItemACFFieldID];
        if ($listicleField === "1") {
            //This means that the post is set to be a listicle.
            $postSlug = str_replace(get_site_url() . '/', "", get_the_permalink($postID));
            $this->setupRewriteRule($postSlug, $postID);
        }
    }

    public function setupRewriteTag()
    {
        add_rewrite_tag('%list_item%', '([^&]+)');
    }
}
