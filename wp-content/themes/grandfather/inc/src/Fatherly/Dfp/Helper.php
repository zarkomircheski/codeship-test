<?php

namespace Fatherly\Dfp;

/**
 * USAGE:
 * Fatherly\Dfp\Helper::init()->id('box1')->printTag();
 */

class Helper
{
    public $targeting = array();
    public $globalTargeting = array();
    public $adUnit;
    public $customClasses;
    public $queriedPageId;
    public $echoDfpData;
    public $prefix;
    public $env;

    public function __construct()
    {
    }

    public static function init($echoDfpData = true)
    {
        static $instance = null;
        if (null === $instance) {
            $instance = new self;
            $instance->superConstruct($echoDfpData);
        }
        return $instance;
    }

    public function superConstruct($echoDfpData = true)
    {
        $this->queriedPageId = get_queried_object_id();
        $this->setEnv();
        $this->setPrefix();
        $this->setPgType();
        $this->checkForTest();
        $this->setT();
        $this->setArticleKeyValue();
        $this->setCategory();
        $this->setStages();
        $this->setSubCategory();
        $this->setTags();
        $this->setPid();
        $this->setSponsored();
        $this->setNsfa();
        $this->setRefreshCount();
        $this->setUrl();
        // prevent the dfp data to be echoed (this is for json api debugging)
        $this->echoDfpData = $echoDfpData;

        if ($this->echoDfpData && !is_feed()) {
            $this->generateHeaderScript();
        }
    }

    protected function setEnv()
    {
        $this->env = constant('ENV');
    }

    protected function setPrefix()
    {
        if (constant('ENV') === 'prod') {
            $this->prefix = '';
        } else {
            $this->prefix = 'z_';
        }
    }

    /**
     * Sets the current page type and adds it to the
     * targeting variable.
     * @return void
     */
    protected function setPgType()
    {
        global $post;

        if (is_front_page()) {
            $this->pgType = 'home';
            $this->addToTargeting('pt', 'home', true);
        } elseif (is_single() || \Fatherly\Listicle\Helper::isListicle($post)) {
            $this->pgType = 'art';
            $this->addToTargeting('pt', 'art', true);
        } elseif (is_category()) {
            $this->pgType = 'cat';
            $this->addToTargeting('pt', 'cat', true);
        } else {
            $this->pgType = 'misc';
            $this->addToTargeting('pt', 'misc', true);
        }
    }

    /**
     *
     * Adds a key value pair to the targeting variable
     * which is read by DFP to target the correct audience.
     *
     * @param $key    string
     * @param $value  string
     */
    public function addToTargeting($key, $value, $global = false)
    {
        if ($global) {
            $this->globalTargeting[$key] = $value;
        } else {
            $this->targeting[$key] = $value;
        }
    }

    /**
     * Checks if a test query string is being added and
     * adds it to the targeting variable.
     *
     * @return void
     */
    protected function checkForTest()
    {
        if (isset($_GET['test'])) {
            $this->addToTargeting('test', strip_tags($_GET['test']), true);
        }
    }

    /**
     * Sets the t targeting variable based on
     * current environment.
     * @return void
     */
    protected function setT()
    {
        $t = constant('ENV') !== 'prod' ? 'y' : 'n';
        $this->addToTargeting('t', $t, true);
    }

    /**
     * Looks for a custom field for the current article.
     * @return void
     */
    protected function setArticleKeyValue()
    {
        if (function_exists('get_field') && is_single()) {
            $key_value = get_field('key_value', $this->queriedPageId);
            $this->addToTargeting('article', $key_value, true);
        }
    }

    /**
     * Sets the current parent category
     * targeting variable.
     * @return void
     */
    protected function setCategory()
    {
        // if we're on category archive page, return the
        // single category title.
        global $post;
        if (is_category()) {
            $category = get_category(get_queried_object_id());
            if ($category->category_parent !== 0) {
                $cat = get_the_category_by_ID($category->category_parent);
            } else {
                $cat = $category->slug;
            }
        } elseif (is_single()  || \Fatherly\Listicle\Helper::isListicle($post)) {
            // elseif we're on a post, get the first parent category
            $cats = array_filter(get_the_category(), array($this, 'categoryParentFilter'));
            $cats = array_map(array($this, 'categoryMap'), $cats);
            $cat = !empty($cats) ? reset($cats) : null;
        }
        $this->category = false;

        if (isset($cat)) {
            $this->category = $cat;
            $this->addToTargeting('cat', $cat, true);
        } else {
            $this->addToTargeting('cat', '', true);
        }
    }

    /**
     * Sets the current subcategory
     * targeting variable.
     * @return void
     */
    protected function setSubCategory()
    {
        // if we're on a post, get the first child category
        if (is_category()) {
            $category = get_category(get_queried_object_id());
            if ($category->category_parent !== 0) {
                $cat = $category->slug;
            }
        } else if (is_single()) {
            $cats = array_filter(get_the_category(), array($this, 'categoryChildFilter'));
            $cats = array_map(array($this, 'categoryMap'), $cats);
            $cat = !empty($cats) ? reset($cats) : null;
        }

        if (isset($cat)) {
            $this->addToTargeting('scat', $cat, true);
        } else {
            $this->addToTargeting('scat', '', true);
        }
    }

    /**
     * Sets the current category/categories
     * targeting variable.
     * @return void
     */
    protected function setTags()
    {
        // if we're on category archive page, return the
        // single tag title.
        if (is_tag()) {
            $tags = single_tag_title('', false);
        } elseif (is_single()) {
            // elseif we're on a post, create a comma separated
            // list of tag titles.
            if (get_the_tags()) {
                $tags = array_map(array($this, 'tagMap'), get_the_tags());
            }
        }
        if (isset($tags)) {
            $this->addToTargeting('tag', $tags, true);
        } else {
            $this->addToTargeting('tag', [], true);
        }
    }

    /**
     * Sets the current stage/stages
     * targeting variable.
     * @return void
     */
    protected function setStages()
    {
        global $post;
        $stages = '';
        // if we're on stage page, return the
        // first word of the page
        if (is_tax('stages')) {
            $newStage = explode(' ', single_tag_title('', false), 2);
            $stages = strtolower($newStage[0]);
        } elseif (is_single()) {
            // elseif we're on a post, create a comma separated
            // list of first word of each stage.
            $postStages = wp_get_post_terms($post->ID, 'stages');
            if (count($postStages) > 0) {
                foreach ($postStages as $stage) {
                    $newStage = explode('-', $stage->slug, 2);
                    $stages .= $newStage[0] . ', ';
                }
            }
        }
        if (isset($stages)) {
            $this->addToTargeting('stage', $stages, true);
        }
    }

    /**
     * Sets the current Post/Article ID
     * targeting variable.
     * @return void
     */
    protected function setPid()
    {
        if (is_single()) {
            $this->addToTargeting('pid', (string)get_the_ID(), true);
        } else {
            $this->addToTargeting('pid', '', true);
        }
    }

    /**
     * Sets the current Sponsored
     * targeting variable.
     * @return void
     */
    protected function setSponsored()
    {
        if (is_single()) {
            $sponsored = get_field('sponsored', get_the_ID());
            $sp = $sponsored ? 'y' : 'n';
            $this->addToTargeting('sp', $sp, true);
        } else {
            $this->addToTargeting('sp', '', true);
        }
    }

    /**
     * Sets nsfa to 'y' if that box is checked, 'n' if not
     * and adds it to the targeting variable.
     * @return void
     */
    protected function setNsfa()
    {
        if (function_exists('get_field') && is_single()) {
            $nsfa = (get_field('not_safe_for_advertisers', $this->queriedPageId) ? 'y' : 'n');
            $this->addToTargeting('nsfa', $nsfa, true);
        } else {
            $this->addToTargeting('nsfa', 'n', true);
        }
    }

    /**
     * Sets refreshcount to 0
     * and adds it to the targeting variable.
     * @return void
     */
    protected function setRefreshCount()
    {
        $this->addToTargeting('refreshcount', '0', false);
    }

    /**
     * Sets url
     * Only set slug if it is a post otherwise just pass the full slug
     * @return void
     */
    protected function setUrl()
    {
        if (is_single()) {
            global $post;
            $this->addToTargeting('url', $post->post_name, true);
            $this->addToTargeting('urlPath', substr($post->post_name, 0, 40), true);
        }
    }

    protected function generateHeaderScript()
    {
        // this will only run once.
        $dfpData = wp_json_encode($this->globalTargeting);
        echo "<script>var dfpData = JSON.parse(" . wp_json_encode($dfpData) . ");</script>";
        echo "<script>var dfpEnv = '" . $this->env . "';</script>";
    }

    /**
     * Sets the ad unit name, mapping-size
     * @return void
     */
    public function id($adUnit)
    {
        $this->adUnitMapping = $this->prefix . $adUnit;
        if ($this->pgType == 'home' && $adUnit == 'box1') {
            //change mapping for the right rail on HP so it doesn't get 300x600
            $this->adUnitMapping = $this->adUnitMapping . '/' . $this->pgType;
        }
        $this->adUnit = $this->prefix . $adUnit . '/' . $this->pgType;
        if ($this->category) {
            $this->adUnit = $this->adUnit . '/' . $this->category;
        }

        // Check if this is an out of page ad unit
        $this->oop = strpos($this->adUnitMapping, 'oop') !== false ? 'true' : 'false';

        return $this;
    }

    /**
     * Sets the Tile number for the slot.
     */
    public function tl($tl)
    {
        $this->addToTargeting('tl', $tl, false);
        return $this;
    }

    /**
     * Sets the Native Tile number for the slot.
     */
    public function nv($nv)
    {
        $this->addToTargeting('nv', $nv, false);
        return $this;
    }

    /**
     *
     * Adds a custom class to the ad unit container.
     *
     * @param $customClass string
     *
     * @return $this Object
     */
    public function customClass(
        $customClass
    ) {
        $this->customClasses = $customClass;
        return $this;
    }

    /**
     * Prints out the tag
     * @return void
     */
    public function printTag()
    {
        $tag = $this->getTag();
        echo wp_kses($tag, array(
            'div' => array(
                'class' => array(),
                'data-adunit' => array(),
                'data-targeting' => array(),
                'data-size-mapping' => array(),
                'data-outofpage' => array(),
            )
        ));
    }

    public function getTag()
    {
        $tag = "<div class='fth-ad adunit " . esc_attr($this->customClasses) . "' data-adunit='" . esc_attr($this->adUnit) . "' data-targeting='" . wp_json_encode($this->targeting) . "' data-size-mapping='" . esc_attr($this->adUnitMapping) . "' data-outofpage='".esc_attr($this->oop)."'></div>";
        $this->reset();
        return $tag;
    }

    private function reset()
    {
        $this->customClasses = '';
        // add targeting keys to reset.
        // currently not in use.
        $targetingKeys = array();
        foreach ($targetingKeys as $key) {
            unset($this->targeting[$key]);
        }
    }

    public function printAmpTag($refresh = false)
    {
        $targeting['targeting'] = $this->globalTargeting;
        $refresh = $refresh ? 'data-enable-refresh="45"' : '';
        echo '<amp-ad width="300" height="250" type="doubleclick" data-loading-strategy="prefer-viewability-over-views" 
         rtc-config=\'{"vendors": {"indexexchange": {"SITE_ID": "366009"}},"timeoutMillis": 750}\' '.$refresh.' data-slot="/72233705/' . esc_attr($this->adUnit) . '" json="' .
            htmlspecialchars(json_encode($targeting), ENT_QUOTES, 'UTF-8') . '"></amp-ad>';
    }

    public function printLeaderAmpTag($refresh, $viewabilty = true)
    {
        $targeting['targeting'] = $this->globalTargeting;
        $refresh = $refresh ? 'data-enable-refresh="'.$refresh.'"' : '';
        $strategy = $viewabilty ? ' data-loading-strategy="prefer-viewability-over-views"'  : '';
        echo '<amp-ad width="320" height="50" type="doubleclick"'.$strategy.'
        rtc-config=\'{"vendors": {"indexexchange": {"SITE_ID": "366008"}},"timeoutMillis": 750}\' '.$refresh.' data-slot="/72233705/' . esc_attr($this->adUnit) . '" json="' .
            htmlspecialchars(json_encode($targeting), ENT_QUOTES, 'UTF-8') . '"></amp-ad>';
    }

    protected function categoryMap($c)
    {
        return $c->slug;
    }

    protected function categoryChildFilter($c)
    {
        return $c->parent !== 0;
    }

    protected function categoryParentFilter($c)
    {
        return $c->parent === 0;
    }

    protected function tagMap($t)
    {
        return $t->name;
    }

    private function __clone()
    {
    }

    private function __wakeup()
    {
    }
}
