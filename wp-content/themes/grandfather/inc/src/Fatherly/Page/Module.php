<?php

namespace Fatherly\Page;

use Fatherly\Page\Helper as Helper;
use Fatherly\Page\Collection as Collection;
use Fatherly\Article\ArticleHelper as ArticleHelper;
use Symfony\Component\Yaml\Yaml;

class Module
{
    public $id;
    public $group;
    public $type;
    public $variant;
    public $data;
    public $template;
    public $postCount;
    public $showCategory;
    public $showAuthor;
    public $layout = 'boxed';
    public $moduleDefinitionsFile = __DIR__ . '/ModuleDefinitions.yml';
    protected $settings;
    protected $fields;


    public static function init()
    {
        return new self;
    }


    public function __construct($moduleSettings = null)
    {
        if ($moduleSettings) {
            $this->id = $moduleSettings['id'];
            $this->group = $moduleSettings['group'];
            $this->type = $moduleSettings['type'];
            $this->settings = $moduleSettings['settings'];
            $this->template = $moduleSettings['template'];
            $this->fields = $moduleSettings['fields'];

            if (array_key_exists('layout', $moduleSettings)) {
                $this->layout = $moduleSettings['layout'];
            }
            if (array_key_exists('adCount', $moduleSettings)) {
                $this->adCount = $moduleSettings['adCount'];
            }

            if ($this->settings) {
                $this->variant = (isset($moduleSettings['settings']['variant'])) ? $moduleSettings['settings']['variant'] : null;
                if (array_key_exists('show_author', $this->settings)) {
                    $this->showAuthor = ($this->settings['show_author'] === "no") ? false : true;
                }
                if (array_key_exists('show_category', $this->settings)) {
                    $this->showCategory = ($this->settings['show_category'] === "no") ? false : true;
                }
                if (array_key_exists('sponsored', $moduleSettings['settings']) && $moduleSettings['settings']['sponsored'] === "yes") {
                    $this->isSponsored = true;
                    $this->data['sponsor_data'] = $this->setSponsorDataOnModule();
                }
                if ($this->type === 'content_hub' && $this->variant === 'variant_1') {
                    if ($this->settings['highlighted_stages_new']) {
                        $this->isUpdated = true;
                    }
                }
            }
            if ($this->fields) {
                $this->setupData();
            }
        }
    }

    public function setupModuleStandalone($moduleFields, $context = null)
    {
        $moduleDefinitions = Yaml::parseFile($this->moduleDefinitionsFile);
        $this->group = $moduleFields['module_type'];
        $this->type = $moduleFields[$this->group . '_type'];
        $this->settings = $moduleFields[$this->type . '_settings'];
        $this->variant = (isset($this->settings['variant'])) ? $this->settings['variant'] : null;
        if ($context) {
            $this->settings['context'] = $context;
        }

        if ($this->variant || array_key_exists($this->type, $moduleDefinitions[$this->group])) {
            $moduleDef = ($this->variant) ? $moduleDefinitions[$this->group][$this->type]['variants'][$this->variant] : $moduleDefinitions[$this->group][$this->type];
            $this->template = $moduleDef['template'];
            $this->fields = $moduleDef['fields'];
            $this->setupData();
        }

        return $this;
    }

    public function setupData()
    {
        if (!empty(($this->fields))) {
            /**
             * We add a check here to see if there's a context being passed to the collection rendering the module here.
             * If there is and it's using a content hub variant with custom posts that is not the parenting variant
             * then we will automatically set the template to use the standard 3x3 display.
             *
             */
            if ($this->type === 'content_hub' && isset($this->settings['context']) && array_key_exists('sub_category', $this->settings['context'])) {
                if ($this->variant === 'variant_4') {
                    $this->template = "content-hub/double-row";
                    $this->postCount = 6;
                    $this->setDoubleRowContentHubPostFieldSettings();
                }
            }

            foreach ($this->fields as $type => $field) {
                if (!empty($field) && !is_array($field)) {
                    $data = $this->fields[$type];
                } else {
                    switch ($type) {
                        case 'highlighted_posts':
                            $data = $this->setupHighlightedPosts($this->fields['highlighted_posts']);
                            break;
                        case 'highlighted_post':
                            $data = $this->setupHighlightedPost($this->fields['highlighted_post']);
                            break;
                        case 'sub_categories':
                            $data = $this->setSubCategoriesOnModule();
                            break;
                        case 'posts':
                            if (array_key_exists('count', $this->fields['posts'])) {
                                $this->postCount = $this->fields['posts']['count'];
                                unset($this->fields['posts']['count']);
                            }
                            $data = $this->setupBackfillPosts($this->fields['posts']);
                            break;
                        case 'more_posts':
                            $data = $this->setupReadMorePosts($this->fields['more_posts']['count']);
                            break;
                        case 'title':
                            $data = $this->setTitleOnModule();
                            break;
                        case 'quote':
                            $data = $this->setQuoteOnModule();
                            break;
                        case 'content_type':
                            $data = $this->setContentTypeOnModule();
                            break;
                        case 'content':
                            $data = $this->setContentOnModule($this->fields['content']);
                            break;
                        case 'view_more_url':
                            $data = $this->settings['view_more_url'];
                            break;
                        case 'image':
                            $data = $this->setImageOnModule();
                            break;
                        case 'lead_graphic':
                            $data = $this->setImageOnModule();
                            break;
                        case 'sponsor_data':
                            $data = $this->setSponsorDataOnSponsoredContentHub($this->fields['sponsor_data']);
                            break;
                        case 'ad_name':
                            $data = $this->setAdNameOnModule();
                            break;
                        case 'main_category':
                            $data = (isset($this->settings['main_category'])) ? $this->settings['main_category'] : null;
                            break;
                        case 'button':
                            $data = $this->setButtonDataOnModule($this->fields['button']);
                            break;
                        case 'custom':
                            $this->setCustomFieldData($this->fields['custom']);
                            break;
                        case 'query_args':
                            $data = $this->setMoreFromQueryArgs();
                            break;
                        case 'sponsored_posts':
                            $data = $this->setSponsoredPostsFieldData($this->fields['sponsored_posts']);
                            break;
                        case 'video_query_args':
                            $data = $this->setVideoQueryArgs();
                            break;
                        default:
                            if (is_array($field) && array_key_exists('acf', $field)) {
                                $data = $this->settings[$field['acf']];
                            }
                            break;
                    }
                }
                $this->data[$type] = $data;
            }
            if ($this->type === 'franchise_tag_banner') {
                $this->setupFranchiseTagBanner($this->settings['context']['franchise_fields']);
            }
        }
    }

    public function setCustomFieldData($fields)
    {
        foreach ($fields as $type => $field) {
            if (array_key_exists($field['acf'], $this->settings)) {
                $this->data[$type] = $this->settings[$field['acf']];
            }
        }
    }

    public function setSponsoredPostsFieldData($fields)
    {
        $sponsoredPosts = get_field($fields['acf'], $this->id);
        $data = array();
        foreach ($sponsoredPosts as $i => $sponsoredPost) {
            foreach ($fields['fields'] as $sponsoredPostFieldKey => $sponsoredPostFieldValue) {
                switch ($sponsoredPostFieldKey) {
                    case 'sponsored_post':
                        $data[$i][$sponsoredPostFieldKey] = $this->setFieldsOnPost($sponsoredPost[$sponsoredPostFieldValue['acf']], $sponsoredPostFieldValue['fields']);
                        break;
                    default:
                        $data[$i][$sponsoredPostFieldKey] = $sponsoredPost[$sponsoredPostFieldValue['acf']];
                        break;
                }
            }
        }
        return $data;
    }

    public function setVideoQueryArgs()
    {
        $args = array(
            'post_type' => 'post',
            'post_status' => 'publish',
            'posts_per_page' => -1,
        );
        if (isset($this->settings['secondary_videos_post_tag'])) {
            //This is the argument that will populate this post tag row
            $args['tag__in'] = wp_list_pluck($this->settings['secondary_videos_post_tag'], 'term_id');
            $args['posts_per_page'] = 24;
        }
        return $args;
    }

    public function setMoreFromQueryArgs()
    {
        if (isset($this->settings['context']) && array_key_exists('contextual_more_from', $this->settings['context'])) {
            $args = $this->setContextualMoreFrom();
        } else {
            //These are the default query args for the more from module that are the same no matter where it's loaded.
            $args = array(
                'post_type' => 'post',
                'post_status' => 'publish',
                'posts_per_page' => 16,
            );
            if (isset($this->settings['more_from_enable_tag']) && $this->settings['more_from_enable_tag'] === "yes") {
                //This is the arguments for when the more from module is set to pull from a tag.
                $args['tag__in'] = wp_list_pluck($this->settings['more_from_post_tag'], 'term_id');
            } else {
                //These declarations are in the else because if a user sets the tag then that should override these.
                if (is_category() || is_tag()) {
                    //If the more from module is loaded on a category or tag page then this sets the query to pull from that.
                    if (get_queried_object_id() !== get_cat_ID('News')) {
                        $args['category__not_in'][] = get_cat_ID('News');
                    }
                    $args[(is_category()) ? 'cat' : 'tag_id'] = get_queried_object_id();
                } elseif (is_tax('stages')) {
                    $args['category__not_in'][] = get_cat_ID('News');
                    $args['tax_query'][] =
                        array(
                            'taxonomy' => 'stages',
                            'field' => 'term_id',
                            'terms' =>  get_queried_object_id(),
                            'include_children' => 'false'
                        );
                } else {
                    $args['category__not_in'][] = get_cat_ID('News');
                    //This is for the state of the module on it's default settings on a normal page such as the homepage.
                    $args['meta_key'] = 'visibility_on_home_page';
                    $args['meta_value'] = 'show';
                }
            }
        }
        $args['no_found_rows'] = true;
        return $args;
    }


    public function setButtonDataOnModule($field)
    {
        if ($button = $field['acf']) {
            return sprintf(
                '<a href="%s" target="%s">%s</a>',
                $this->settings[$button]['url'],
                $this->settings[$button]['target'],
                $this->settings[$button]['title']
            );
        }
    }

    public function setSponsorDataOnModule()
    {
        $args = array(
            'attachment_id' => $this->settings['sponsored_settings']['sponsor_logo']['ID'],
            'height' => '40'
        );
        $this->settings['sponsored_settings']['sponsor_logo_url'] = fth_img($args);
        return $this->settings['sponsored_settings'];
    }

    public function setSponsorDataOnSponsoredContentHub($fields)
    {
        $data = array();
        foreach ($fields as $type => $value) {
            switch ($type) {
                case 'sponsor_logo':
                    if ($this->settings['sponsor_image']) {
                        $options = $this->fields['sponsor_data']['sponsor_logo'];
                        $options['attachment_id'] = $this->settings['sponsor_image']['ID'];
                        $data[$type] = fth_img($options);
                    } else {
                        $data[$type] = '';
                    }
                    break;
                case 'sponsor_link':
                    $data[$type] = $this->settings['sponsor_link'];
                    break;
            }
        }
        return $data;
    }

    public function setImageOnModule()
    {
        $field = (!empty($this->fields['image'])) ? $this->fields['image'] : array();
        if (isset($field['acf'])) {
            $image = $this->settings[$field['acf']];
            $field['attachment_id'] = $image['ID'];
            if ($image) {
                return fth_img($field);
            } else {
                return false;
            }
        }
        if ($this->type === 'newsletter_signup') {
            $imageOptions = array(
                'attachment_id' => $this->settings['image']['ID'],
                'cropType' => false,
                'fit' => 'clip',
                'retina' => false,
            );
            if ($this->variant === 'variant_1') {
                $imageOptions['width'] = 380;
                return $this->settings['image']['ID'];
            }
            return fth_img($imageOptions);
        }
        if ($this->type === 'content_hub') {
            $this->fields['lead_graphic']['attachment_id'] = $this->settings['variant_settings']['section_graphic']['ID'];
            return fth_img($this->fields['lead_graphic']);
        }

        if ($this->type == 'banner_image') {
            return $this->settings['image']['url'];
        }
    }

    public function setContentOnModule($field)
    {
        if ($content = $field['acf']) {
            return $this->settings[$content];
        } else {
            return $this->settings['message'];
        }
    }

    public function setupReadMorePosts($count)
    {
        $data = array();
        /**
         * ADDED WHILE WORKING ON WEB-480
         * We check here for a contextual sub category. If it exists then it overrides the category set by
         * the user using instead the category passed through to the context setting. Typically this value
         * will be the sub category currently being viewed.
         **/
        if (isset($this->settings['context'])) {
            $morePosts = $this->getPostsByContext($count);
        } else {
            $morePosts = Helper::getPostsByCategory($this->settings['main_category']->term_id, $count);
        }
        foreach ($morePosts as $morePost) {
            $morePostData = array(
                'title' => $this->setFieldOnPost('title', $morePost),
                'url' => $this->setFieldOnPost('permalink', $morePost)
            );
            $data[] = $morePostData;
        }

        return $data;
    }

    public function setAdNameOnModule()
    {
        switch ($this->type) {
            case 'ad':
                if ($this->adCount > 0) {
                    $data = sprintf("list_lead%d", $this->adCount);
                } else {
                    $data = "lead1";
                }
                break;
            case 'in_article_ad':
                $data = $this->settings['context']->num;
                break;
            case 'in_article_amp_ad':
                $data = $this->settings['context']->num;
                break;
        }

        return $data;
    }

    public function setSubCategoriesOnModule()
    {
        $subCategories = array();
        if ($this->type === "content_hub") {
            if ($this->variant === 'variant_1') {
                //This is the parenting module which has a sub nav so it's a tad different.
                if (!empty($this->settings['highlighted_stages']) || !empty($this->settings['highlighted_stages_new'])) {
                    if ($this->isUpdated) {
                        foreach ($this->settings['highlighted_stages_new'] as $stage) {
                            $subCategories[$stage->term_id] = $stage->name;
                        }
                    } else {
                        foreach ($this->settings['highlighted_stages'] as $stage) {
                            $subCategories[$stage->ID] = $stage->post_title;
                        }
                    }
                }
            } else {
                if (!empty($this->settings['highlighted_sub_categories'])) {
                    foreach ($this->settings['highlighted_sub_categories'] as $subCat) {
                        $subCategories[] = array(
                            'name' => $subCat->name,
                            'url' => get_category_link($subCat)
                        );
                    }
                }
            }
        }

        return $subCategories;
    }

    public function setTitleOnModule()
    {
        $field = $this->fields['title'];
        if (isset($this->settings['override_title']) && $this->settings['override_title'] === 'yes') {
            return $this->settings['title'];
        }
        /**
         * If the content hub doesn't have override title set AND there's a context then we will make the title dynamic
         * if the module is not the parenting module.
         */
        if (isset($this->settings['context']) && $this->variant !== 'variant_1') {
            return $this->setContextualModuleTitle();
        }
        if ($this->type === 'post_highlight') {
            if ($this->settings['custom_title_enabled'] === 'yes') {
                return $this->settings['custom_title'];
            }
            $title = $this->settings['custom_title'];
            return (!empty($title)) ? $title : false;
        }

        if ($this->type === 'video_highlight') {
            if ($this->settings['video_highlight_module_title_enable'] === 'yes') {
                return $this->settings['custom_title'];
            }
            return false;
        }

        if ($this->type === 'content_hub') {
            if ($this->settings['override_title'] === "no" && $this->variant !== 'custom') {
                return $this->settings['main_category']->name;
            } else {
                return $this->settings['title'];
            }
        }
        if ($this->type === 'sponsored_content_hub' || $this->type === 'custom_sponsored_content_hub' || $this->type === 'title') {
            return $this->settings['title'];
        }
        if ($this->type === 'post_listing') {
            if ($this->variant === 'custom') {
                return $this->settings['title'];
            }
            if ($this->variant === 'category') {
                $mainCat = $this->settings['category'][0];
                if ($mainCat->parent === 0) {
                    return $mainCat->name;
                } else {
                    $parent = get_category($mainCat->parent);
                    return sprintf("%s<span style=\"color:#DC4028\"> / </span>%s", $parent->name, $mainCat->name);
                }
            }
        }
        if ($this->type === 'secondary_videos') {
            return $this->settings['secondary_videos_post_tag'][0]->name;
        }
        if ($title = $field['acf']) {
            return $this->settings[$title];
        }
    }

    public function setContextualModuleTitle()
    {
        $title = '';
        switch ($this->settings['context']) {
            case array_key_exists('sub_category', $this->settings['context']):
                $title = $this->settings['context']['sub_category']->name;
                break;
            case array_key_exists('stage', $this->settings['context']):
                $title = $this->settings['context']['stage']->name;
                break;
            case array_key_exists('author', $this->settings['context']):
                $title = $this->settings['context']['author']->display_name;
                break;
        }
        return $title;
    }

    public function setContextualMoreFrom()
    {
        $args = array(
            'post_type' => 'post',
            'post_status' => 'publish',
            'posts_per_page' => 16,
        );

        switch ($this->settings['context']) {
            case array_key_exists('author', $this->settings['context']):
                $args['author'] = $this->settings['context']['author']->ID;
                break;
        }

        return $args;
    }

    public function setQuoteOnModule()
    {
        if ($this->settings['custom_quote_enabled'] === 'yes') {
            return $this->settings['custom_quote'];
        } else {
            return false;
        }
    }

    public function setContentTypeOnModule()
    {
        if ($this->variant === 'variant_3' || $this->variant === 'variant_4') {
            if ($this->variant === 'variant_3') {
                $contentType = sprintf("podcast %s", $this->variant);
            } else {
                $contentType = sprintf("franchise-module %s", $this->variant);
            }
            $this->showAuthor = false;
            $this->showArrow = true;
            $this->showCategory = true;
            $this->showLogo = false;
        } else {
            $this->showAuthor = true;
            $this->showArrow = true;
            $this->showCategory = true;
            $this->showLogo = true;
            if ($this->variant === 'variant_1') {
                $this->showArrow = false;
            }
            if ($this->variant === 'variant_5') {
                $this->showCategory = false;
            }
            $contentType = sprintf("featured-module %s", $this->variant);
        }
        return $contentType;
    }

    public function setupBackfillPosts($fields)
    {
        $posts = array();
        if ($this->type === 'content_hub') {
            if (!empty($fillType = $this->settings['content_hub_fill_type']) && $this->variant !== 'variant_1') {
                /**
                 * This means that a fill type was set and that the content hub variant is not `variant_1`
                 */
                switch ($fillType) {
                    case 'tag':
                        $tagIds = wp_list_pluck($this->settings['main_post_tag'], 'term_id');

                        $contentHubPosts = Helper::getPostsByTag($tagIds, $this->postCount);
                        foreach ($contentHubPosts as $contentHubPost) {
                            $data = array();
                            if ($franchise = ArticleHelper::articleHasFranchiseTag($contentHubPost)) {
                                $data['franchise'] = $franchise;
                            }
                            foreach ($fields as $type => $value) {
                                $data[$type] = $this->setFieldOnPost($type, $contentHubPost, $value);
                            }
                            $posts[] = $data;
                        }
                        break;
                    case 'stages':
                        $stagesTermIds = wp_list_pluck($this->settings['main_stages_term'], 'term_id');
                        $contentHubPosts = Helper::getPostsByParentingStage($stagesTermIds, $this->postCount);
                        foreach ($contentHubPosts as $contentHubPost) {
                            $data = array();
                            if ($franchise = ArticleHelper::articleHasFranchiseTag($contentHubPost)) {
                                $data['franchise'] = $franchise;
                            }
                            foreach ($fields as $type => $value) {
                                $data[$type] = $this->setFieldOnPost($type, $contentHubPost, $value);
                            }
                            $posts[] = $data;
                        }
                        break;
                    default:
                        //If they didn't select `tag` or `stages` as the fill type then that means we use the category.
                        /**
                         * ADDED WHILE WORKING ON WEB-480
                         * We check here for a contextual sub category. If it exists then it overrides the category set by
                         * the user using instead the category passed through to the context setting. Typically this value
                         * will be the sub category currently being viewed.
                         **/
                        if (isset($this->settings['context'])) {
                            $contentHubPosts = $this->getPostsByContext($this->postCount);
                        } else {
                            $contentHubPosts = Helper::getPostsByCategory($this->settings['main_category']->term_id, $this->postCount);
                        }
                        foreach ($contentHubPosts as $contentHubPost) {
                            $data = array();
                            if ($franchise = ArticleHelper::articleHasFranchiseTag($contentHubPost)) {
                                $data['franchise'] = $franchise;
                            }
                            foreach ($fields as $type => $value) {
                                $data[$type] = $this->setFieldOnPost($type, $contentHubPost, $value);
                            }
                            $posts[] = $data;
                        }
                        break;
                }
            } else {
                if ($this->variant === 'variant_1') {
                    /**
                     * ADDED WHILE WORKING ON WEB-480
                     * We check here for a contextual sub category. If it exists then it overrides the category set by
                     * the user using instead the category passed through to the context setting. Typically this value
                     * will be the sub category currently being viewed.
                     **/

                    if (isset($this->settings['context']) && array_key_exists('sub_category', $this->settings['context'])) {
                        $this->template = "content-hub/double-row";
                        $this->postCount = 6;
                        $this->setDoubleRowContentHubPostFieldSettings();
                        $fields = $this->fields['posts'];
                        $contentHubPosts = Helper::getPostsByCategory($this->settings['context']['sub_category']->term_id, $this->postCount);
                        foreach ($contentHubPosts as $contentHubPost) {
                            $data = array();
                            if ($franchise = ArticleHelper::articleHasFranchiseTag($contentHubPost)) {
                                $data['franchise'] = $franchise;
                            }
                            foreach ($fields as $type => $value) {
                                $data[$type] = $this->setFieldOnPost($type, $contentHubPost, $value);
                            }
                            $posts[] = $data;
                        }
                    } else {
                        if ($this->isUpdated) {
                            foreach ($this->settings['highlighted_stages_new'] as $stage) {
                                $stagePosts = Helper::getPostsByParentingStage($stage->term_id, 6, true);
                                foreach ($stagePosts as $stagePost) {
                                    $data = array();
                                    if ($franchise = ArticleHelper::articleHasFranchiseTag($stagePost)) {
                                        $data['franchise'] = $franchise;
                                    }
                                    foreach ($fields as $type => $value) {
                                        $data[$type] = $this->setFieldOnPost($type, $stagePost, $value);
                                    }
                                    $posts[] = $data;
                                }
                            }
                        } else {
                            foreach ($this->settings['highlighted_stages'] as $stage) {
                                $stagePosts = Helper::fetchPostsForContentHubModule1(get_fields($stage->ID), 6);
                                foreach ($stagePosts->posts as $stagePost) {
                                    $data = array();
                                    if ($franchise = ArticleHelper::articleHasFranchiseTag($stagePost)) {
                                        $data['franchise'] = $franchise;
                                    }
                                    foreach ($fields as $type => $value) {
                                        $data[$type] = $this->setFieldOnPost($type, $stagePost, $value);
                                    }
                                    $posts[] = $data;
                                }
                            }
                        }
                        $posts = array_chunk($posts, 6, true);
                    }
                } else {
                    /**
                     * ADDED WHILE WORKING ON WEB-480
                     * We check here for a contextual sub category. If it exists then it overrides the category set by
                     * the user using instead the category passed through to the context setting. Typically this value
                     * will be the sub category currently being viewed.
                     **/

                    if (isset($this->settings['context'])) {
                        $contentHubPosts = $this->getPostsByContext($this->postCount);
                    } else {
                        $contentHubPosts = Helper::getPostsByCategory($this->settings['main_category']->term_id, $this->postCount);
                    }
                    foreach ($contentHubPosts as $contentHubPost) {
                        $data = array();
                        if ($franchise = ArticleHelper::articleHasFranchiseTag($contentHubPost)) {
                            $data['franchise'] = $franchise;
                        }
                        foreach ($fields as $type => $value) {
                            $data[$type] = $this->setFieldOnPost($type, $contentHubPost, $value);
                        }
                        $posts[] = $data;
                    }
                }
            }
        }
        if ($this->type === 'post_listing') {
            /**
             * ADDED WHILE WORKING ON WEB-480
             * We check here for a contextual sub category. If it exists then it overrides the category set by
             * the user using instead the category passed through to the context setting. Typically this value
             * will be the sub category currently being viewed.
             **/
            if (isset($this->settings['context'])) {
                $postListingPosts = $this->getPostsByContext($this->postCount);
            } else {
                $postListingPosts = Helper::getPostsByCategory($this->settings['category'][0]->term_id, $this->postCount);
            }
            foreach ($postListingPosts as $postListingPost) {
                $data = array();
                if ($franchise = ArticleHelper::articleHasFranchiseTag($postListingPost)) {
                    $data['franchise'] = $franchise;
                }
                foreach ($fields as $type => $value) {
                    $data[$type] = $this->setFieldOnPost($type, $postListingPost, $value);
                }
                $posts[] = $data;
            }
        }


        return $posts;
    }

    public function setupHighlightedPosts($fields)
    {
        $posts = array();
        if ($this->type === 'post_listing' || $this->type === 'sponsored_content_hub') {
            $hPosts = $this->settings['posts'];
        } elseif ($this->type === 'content_hub') {
            $hPosts = $this->settings['variant_settings']['highlighted_posts'];
        } else {
            $hPosts = $this->settings['highlighted_posts'];
        }
        if (isset($this->settings['context'])) {
            $hPosts = $this->getPostsByContext(count($hPosts));
        }

        foreach ($hPosts as $i => $highlighted_post) {
            Collection::addPostIdToExclusion($highlighted_post->ID);
            $data = array();
            if ($franchise = ArticleHelper::articleHasFranchiseTag($highlighted_post)) {
                $data['franchise'] = $franchise;
            }
            foreach ($fields as $type => $value) {
                $data[$type] = $this->setFieldOnPost($type, $highlighted_post, $value);
            }
            $posts[] = $data;
        }
        return $posts;
    }

    public function setupHighlightedPost($fields)
    {
        Collection::addPostIdToExclusion($this->settings['highlighted_post']->ID);
        $data = array();
        if ($franchise = ArticleHelper::articleHasFranchiseTag($this->settings['highlighted_post'])) {
            $data['franchise'] = $franchise;
        }
        foreach ($fields as $type => $value) {
            $data[$type] = $this->setFieldOnPost($type, $this->settings['highlighted_post'], $value);
        }
        return $data;
    }

    protected function setFieldsOnPost($post, array $fields)
    {
        foreach ($fields as $field => $value) {
            $fields[$field] = $this->setFieldOnPost($field, $post);
        }
        return $fields;
    }

    protected function setFieldOnPost($type, $post, $fieldSettings = null)
    {
        switch ($type) {
            case 'title':
                return (!empty(get_field('frontpage_headline', $post->ID))) ? get_field('frontpage_headline', $post->ID) : $post->post_title;
                break;
            case 'featured_image':
                $fieldSettings['post_id'] = $post->ID;
                return fth_img($fieldSettings);
                break;
            case 'category':
                $cat = Helper::getPostCategory($post, true);
                if (isset($cat)) {
                    return array('name' => $cat->name, 'url' => get_category_link($cat->term_id));
                } else {
                    return;
                }
                break;
            case 'permalink':
                return get_the_permalink($post->ID);
                break;
            case 'author':
                return Helper::getPostAuthorNameAndUrl($post);
                break;
            case 'excerpt':
                return get_field('custom_excerpt', $post->ID);
                break;
            case 'id':
                return $post->ID;
                break;
        }
    }

    public function setDoubleRowContentHubPostFieldSettings()
    {
        $this->fields['posts'] = array(
            'title' => '',
            'category' => '',
            'permalink' => '',
            'author' => '',
            'featured_image' => array(
                'width' => 300,
                'height' => 150
            )
        );
    }

    public function getPostsByContext($count)
    {
        $posts = array();
        switch ($this->settings['context']) {
            case array_key_exists('sub_category', $this->settings['context']):
                $posts = Helper::getPostsByCategory($this->settings['context']['sub_category']->term_id, $count);
                break;
            case array_key_exists('stage', $this->settings['context']):
                $posts = Helper::getPostsByParentingStage($this->settings['context']['stage']->term_id, $count);
                break;
            case array_key_exists('author', $this->settings['context']):
                $posts = Helper::getPostsByAuthor($this->settings['context']['author']->ID, $count);
                break;
            case array_key_exists('tag', $this->settings['context']):
                $posts = Helper::getPostsByTag($this->settings['context']['tag']->term_id, $count);
                break;
        }
        return $posts;
    }

    public function setupFranchiseTagBanner($tagFields)
    {
        $this->data['banner_image'] = $tagFields['franchise_tag_banner_image'];
        if ($tagFields['franchise_custom_title']) {
            $this->data['title_text'] = $tagFields['franchise_tag_title_text'];
        }
    }
    public function render($template = null, $container = true)
    {
        $template = (isset($template)) ? $template : $this->template;
        $class = ($this->variant) ? sprintf(" module__%s--%s", $this->type, $this->variant) : sprintf("module__%s", $this->type);
        if ($container) {
            echo ($this->layout === 'full') ? "<section class=\"home-page-module {$class}\">" : "<section class=\"home-page home-page-module {$class}\">";
        }
        get_template_part(sprintf('parts/modules/%s', $template));
        if ($container) {
            echo '</section>';
        }
    }

    public function renderPreview($template = null, $container = true)
    {
        $template = (isset($template)) ? $template : $this->template;
        $class = ($this->variant) ? sprintf(" module__%s--%s", $this->type, $this->variant) : sprintf("module__%s", $this->type);
        $class .= " module-preview";
        if ($container) {
            echo ($this->layout === 'full') ? "<section class=\"home-page-module {$class}\">" : "<section class=\"home-page home-page-module {$class}\">";
            echo "<span class='module-preview-text'>Preview</span>";
        }
        get_template_part(sprintf('parts/modules/%s', $template));
        if ($container) {
            echo '</section>';
        }
    }

    public static function renderPartial($partial)
    {
        return get_template_part(sprintf("partials/modules/%s", $partial));
    }

    public static function renderBareModule($template)
    {
        return get_template_part(sprintf('parts/modules/%s', $template));
    }
}
