<?php

namespace Fatherly\Rewrite;

/**
 * Class RewriteRules
 * @package Fatherly\Rewrite
 */
class RewriteRules
{


    /**
     * Contains an array of taxonomies that should have their types removed from url slugs
     *
     * @var array
     */
    public $taxTypes = array(
        'stages'
    );
    /**
     * Contains the updated term link
     * @var string
     */
    public $termLink;


    /**
     * RewriteRules constructor.
     * Sets up the actions that will be needed for setting up the rewrite rules and keeping them up to date.
     */
    public function __construct()
    {
        $this->setupArgs();
        add_filter('term_link', array($this, 'removeTaxSlug'), 10, 3);
        add_action('init', array($this, 'addRootTermRewrites'), 9);
        add_action('created_term', array($this, 'flushRewrites'), 11);
        add_action('deleted_term', array($this, 'flushRewrites'), 11);
        add_action('edited_term', array($this, 'flushRewrites'), 11);
    }

    public static function init()
    {
        return new self;
    }

    /**
     * Flushes the rewrite rules on term update so that the urls are always up to date with the most recent version of
     * a term
     */
    public function flushRewrites()
    {
        $this->addRootTermRewrites();
        return flush_rewrite_rules();
    }

    /**
     * Removes the taxonomy type from the url slug
     *
     * @param $termLink
     * @param $termObject
     * @param $termSlug
     *
     * @return mixed
     */
    public function removeTaxSlug($termLink, $termObject, $termSlug)
    {
        if (in_array($termSlug, $this->taxTypes)) {
            if ($termObject->parent !== 0) {
                $parent = get_term_by('id', $termObject->parent, $termObject->taxonomy);
                $this->termLink = get_site_url() . "/{$parent->slug}/{$termObject->slug}/";
            } else {
                $this->termLink = get_site_url() . "/{$termObject->slug}/";
            }
            return $this->termLink;
        } else {
            return $termLink;
        }
    }

    /**
     * Sets up the rewrite rules for the taxonomy terms without their type being in the slug.
     */
    public function addRootTermRewrites()
    {
        global $wp_rewrite;
        $parentTerms = get_terms($this->taxTypes, $this->termArgs);
        foreach ($parentTerms as $term) {
            $rootTermRegex = "(^{$term->slug})+$";
            $wp_rewrite->add_rule($rootTermRegex, 'index.php?stages=$matches[1]', 'top');
        }
    }

    /**
     * Defauly WP_Query params to be used when getting the listing of terms in a taxonomy for use in the generation
     * of the rewrite rules.
     */
    protected function setupArgs()
    {
        $this->termArgs = array(
            'orderby' => 'name',
            'order' => 'ASC',
            'hide_empty' => false,
            'include' => array(),
            'exclude' => array(),
            'exclude_tree' => array(),
            'number' => '',
            'offset' => '',
            'fields' => 'all',
            'name' => '',
            'slug' => '',
            'hierarchical' => true,
            'search' => '',
            'name__like' => '',
            'description__like' => '',
            'pad_counts' => false,
            'get' => '',
            'child_of' => 0,
            'parent' => 0,
            'childless' => false,
            'cache_domain' => 'core',
            'update_term_meta_cache' => true,
            'meta_query' => ''
        );
    }
}
