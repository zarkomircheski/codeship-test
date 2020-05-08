<?php

namespace Fatherly\Article\Module;

use Fatherly\Rule\Helper;

/**
 * Class Insertion
 * @package Fatherly\Article\Module
 */
class Insertion
{
    /**
     * @var string $content
     */
    public $content;

    /**
     * @var array $contentNodes
     */
    public $contentNodes;

    /**
     * @var int $postID
     */
    public $postID;

    /**
     * @var array $articleBottomModules
     */
    public $articleBottomModules;

    /**
     * @var array $programmaticModules
     */
    public $programmaticModules;

    /**
     * @var array $repeatingModules
     */
    public $repeatingModules;

    /**
     * @var array $parsedContent
     */
    public $parsedContent;

    /**
     * @var int $lastInsertId
     */
    public $lastInsertId;
    public $listicleItems;
    public $listicleNodes;
    /**
     * Insertion constructor.
     *
     * The constructor in this instance calls the setup methods for all the module insertion queues so that when the
     * insertion for each queue runs it will have the data it needs. This method also sets up a filter on the content
     * that will fire `performModuleInsertion()`
     */
    public function __construct()
    {
        $this->programmaticModules = $this->setupProgrammaticModules();
        $this->articleBottomModules = $this->setupArticleBottomModules();
        $this->repeatingModules = $this->setupRepeatingModules();
        add_filter('the_content', array($this, 'performModuleInsertion'));
        add_filter('the_listicle_items', array($this, 'performModuleInsertionOnListicle'));
    }

    /**
     * Insertion factory method
     *
     * @return Insertion
     */
    public static function init()
    {
        return new self;
    }

    /**
     * performModuleInsertion
     *
     * This method is called to kick off the process of module insertions needed on a post. This will check to make
     * sure we're on an article and then it will pass the content down to all the appropriate methods.
     *
     * @param string $content
     * @return string
     */
    public function performModuleInsertion($content)
    {
        $this->content = $content;
        if (get_post()->post_type === 'post' && !strpos($this->content, 'appleNews')) :
            $this->postID = get_the_ID();
            $this->programmaticModuleInsertion($this->content);
            $this->repeatingModuleInsertion();
            $this->articleBottomModuleInsertion($this->content);
        else :
            $this->content =  str_replace('appleNewsExporting', '', $content);
        endif;
        return $this->content;
    }

    public function performModuleInsertionOnListicle($listicleItems)
    {
        $this->listicleItems = $listicleItems;
        $this->postID = get_the_ID();
        $this->content = get_the_content();
        $this->programmaticModuleInsertionListicle($listicleItems);
        $this->repeatingModuleInsertionListicle();
        return $this->listicleItems;
    }

    /**
     * programmaticModuleInsertion
     *
     * This method takes the post content and then intersperses the modules from the programmatic module insertion queue
     * into the content. These are placed after every 2nd node with various rules dictating if a module can be inserted
     * in a spot or not. If a module cannot be inserted in its first choice slot then we will check the next node in the
     * list until we find one that works (we will try a maximum of 8 times to insert a module).
     * With the introduction of lists there arose the need to be able to run insertion queues on list items. This caused
     * a problem to arise because the list queue does not know what modules have already been inserted by the article
     * queue. Since list items are loaded in an entirely seperate request we cannot set this information as a global so
     * to remedy this we create an array of module ids that have already been inserted and then we set that information
     * as a cookie with an expiration of 5 seconds to give the ajax that fires on page load enough time to make its call
     * whilst the cookie exists and then the cookie expires soon after since it's no longer needed.
     *
     * @param string $content
     * @return string
     */
    public function programmaticModuleInsertion($content)
    {
        $this->content = $content;
        $this->contentNodes = fth_get_root_html_elements_from_content($this->content);
        $insertId = 0;
        $start = 2;
        $inserted = array();
        foreach ($this->programmaticModules as $programmaticModule) {
            if ($this->canInsert($programmaticModule['id'])) :
                if ($insertId === 0) {
                    $insertId = $start;
                } else {
                    $insertId = $insertId + 2;
                }
                $this->parsedContent = fth_insert_after_node($programmaticModule['content'], $insertId, $this->contentNodes);
                $tries = 0;
                while ($this->parsedContent['success'] == false && $tries < 8) {
                    $insertId++;
                    $tries++;
                    $this->parsedContent = fth_insert_after_node($programmaticModule['content'], $insertId, $this->contentNodes);
                }
                if ($this->parsedContent['success'] === true) {
                    $this->contentNodes = $this->parsedContent['nodes'];
                    $this->parsedContent = false;
                    $this->lastInsertId = ($insertId - 1);
                    $inserted[] = $programmaticModule['id'];
                }
            endif;
        }
        if (!empty($this->contentNodes)) {
            $this->content = implode('', $this->contentNodes);
        }
        return $this->content;
    }
    /**
     * programmaticModuleInsertionListicle
     *
     * This method takes Listicle Items and then intersperses the modules from the programmatic module insertion queue
     * into the content. These are placed after every 2nd item with various rules dictating if a module can be inserted
     * in a spot or not. If a module cannot be inserted in its first choice slot then we will check the next item in the
     * list until we find one that works (we will try a maximum of 8 times to insert a module).
     *
     * Additional functionality has been added to ensure that we do not insert modules into the lists that have already
     * been inserted in the article content above the list. To do this we check for the existence of a cookie which we
     * set in the article insertion that contains the module ids that have been inserted. If the module we're about to
     * insert here exist inside the array from that cookie then we skip that module.
     *
     * @param string $listicleItems
     * @return string
     */
    public function programmaticModuleInsertionListicle($listicleItems)
    {
        $this->listicleItems = $listicleItems;
        $this->listicleNodes = $listicleItems;
        $insertId = 0;
        $start = 2;
        if (isset($_REQUEST) && array_key_exists('excluded_modules', $_REQUEST)) {
            $insertedModules = $_REQUEST['excluded_modules'];
        }
        foreach ($this->programmaticModules as $programmaticModule) {
            if (isset($insertedModules) && in_array($programmaticModule['id'], $insertedModules)) {
                continue;
            }
            if ($this->canInsert($programmaticModule['id'])) :
                if ($insertId === 0) {
                    $insertId = $start;
                } else {
                    $insertId = $insertId + 2;
                }
                $insertionContent = array("module" => $programmaticModule['content']);
                $this->parsedContent = fth_insert_after_list_item($this->listicleNodes, $insertId, array($insertionContent));
                $tries = 0;
                while ($this->parsedContent['success'] == false && $tries < 8) {
                    $insertId++;
                    $tries++;
                    $this->parsedContent = fth_insert_after_list_item($this->listicleNodes, $insertId, array($insertionContent));
                }
                if ($this->parsedContent['success'] === true) {
                    $this->listicleNodes = $this->parsedContent['listItems'];
                    $this->parsedContent = false;
                    $this->lastInsertId = $insertId;
                }
            endif;
        }
        if (!empty($this->listicleNodes)) {
            $this->listicleItems = $this->listicleNodes;
        }
    }

    /**
     * articleBottomModuleInsertion
     *
     * This method takes in the post content and then appends shortcodes for the modules that should go at the bottom
     * of articles.
     *
     * @param string $content
     * @return string
     */
    public function articleBottomModuleInsertion($content)
    {
        $this->content = $content;
        foreach ($this->articleBottomModules as $articleBottomModule) {
            if ($this->canInsert($articleBottomModule['id'])) :
                $this->content = $this->content . $articleBottomModule['content'];
            endif;
        }
        return $this->content;
    }

    /**
     * repeatingModuleInsertion
     *
     * This method performs the actual insertion of repeating modules into the post content. This method unlike the
     * programmatic and article bottom insertion does not take in the post content string. This is because it gets the
     * information it needs from the post content nodes which are first processed by the programmatic module insertion
     * before this method is called. The method is written this way because where the programmatic module inserted its
     * content is needed here so that we know where to start with our insertion.
     *
     * @return string
     */
    public function repeatingModuleInsertion()
    {
        foreach ($this->repeatingModules as $repeatingModule) {
            $insertId = $this->lastInsertId + (int)$repeatingModule['interval'];

            if ($this->canInsert($repeatingModule['id'])) {
                $max = $repeatingModule['max'] > 0 ? $repeatingModule['max'] : null;
                for ($i = 1; $i <= count($this->contentNodes); $i++) {
                    //We replace `||||` with a base64 encoded JSON string that will contain data for the module.
                    // All module will have at least the number for the amount of times it has been inserted.
                    if ($max && $i > $max) {
                        break;
                    }
                    $moduleContent = str_replace("||||", base64_encode(json_encode(array('num' => $i))), $repeatingModule['content']);
                    $this->parsedContent = fth_insert_after_node($moduleContent, $insertId, $this->contentNodes);
                    $tries = 0;
                    while ($this->parsedContent['success'] == false && $tries < 8) {
                        $insertId++;
                        $tries++;
                        $this->parsedContent = fth_insert_after_node($moduleContent, $insertId, $this->contentNodes);
                    }
                    if ($this->parsedContent['success'] === true) {
                        $this->contentNodes = $this->parsedContent['nodes'];
                        $this->parsedContent = false;
                        $insertId = $insertId + (int)$repeatingModule['interval'];
                    }
                }
            }
        }
        if (!empty($this->contentNodes)) {
            $this->content = implode('', $this->contentNodes);
        }
        return $this->content;
    }
    /**
     * repeatingModuleInsertionListicle
     *
     * This method performs the actual insertion of repeating modules into listicles. This method unlike the
     * programmatic insertion does not take in the listItems as a param. This is because it gets the
     * information it needs from the list content nodes which are first processed by the programmatic module insertion
     * before this method is called. The method is written this way because where the programmatic module inserted its
     * content is needed here so that we know where to start with our insertion.
     *
     * @return string
     */
    public function repeatingModuleInsertionListicle()
    {
        if (isset($_REQUEST) && array_key_exists('insertion_offset', $_REQUEST) && $_REQUEST['direction'] == 'down') {
            $insertionOffset = (int)$_REQUEST['insertion_offset'];
        }
        foreach ($this->repeatingModules as $repeatingModule) {
            if ($this->canInsert($repeatingModule['id'])) {
                $insertId = $this->lastInsertId + (int)$repeatingModule['interval'];
                if ($insertionOffset > 0) {
                    $insertId = $insertId - $insertionOffset;
                    $insertionOffset = 0;
                }
                $max = $repeatingModule['max'] > 0 ? $repeatingModule['max'] : null;

                for ($i = 1; $i <= count($this->listicleNodes); $i++) {
                    //We replace `||||` with a base64 encoded JSON string that will contain data for the module.
                    // All module will have at least the number for the amount of times it has been inserted.
                    if ($max && $i > $max) {
                        break;
                    }
                    if ($insertId > count($this->listicleNodes)) {
                        break;
                    }
                    if ($i > 1 || isset($this->lastInsertId)) {
                        $insertId++;
                    }
                    $moduleContent = str_replace("||||", base64_encode(json_encode(array('num' => $i))), $repeatingModule['content']);
                    $insertionContent = array("module" => $moduleContent);
                    $this->parsedContent = fth_insert_after_list_item($this->listicleNodes, $insertId, array($insertionContent));
                    $tries = 0;
                    while ($this->parsedContent['success'] == false && $tries < 8) {
                        $insertId++;
                        $tries++;
                        $this->parsedContent = fth_insert_after_list_item($this->listicleNodes, $insertId, array($insertionContent));
                    }
                    if ($this->parsedContent['success'] === true) {
                        $this->listicleNodes = $this->parsedContent['listItems'];
                        $this->parsedContent = false;
                        $lastInsert = $insertId;
                        $insertId = $insertId + (int)$repeatingModule['interval'];
                    }
                }
                if (isset($_REQUEST) && array_key_exists('insertion_offset', $_REQUEST) && $_REQUEST['direction'] == 'up') {
                    $upInsertionOffset = $_REQUEST['insertion_offset'];
                    $leftoverNodes = count($this->listicleNodes) - ($lastInsert + 1);
                    if (($leftoverNodes + $upInsertionOffset) > $repeatingModule['interval'] && !array_key_exists(
                        'module',
                        $this->listicleNodes[count($this->listicleNodes) - 1]
                    )) {
                        $this->listicleNodes[] = $insertionContent;
                    }
                }
            }
        }
        if (!empty($this->listicleNodes)) {
            $this->listicleItems = $this->listicleNodes;
        }
        return $this->listicleItems;
    }

    /**
     * setupProgrammaticModules
     *
     * Grabs the list of programmatic modules that will need to be inserted from the DB and sets up the data for
     * insertion
     * @return array
     */
    public function setupProgrammaticModules()
    {
        $modulesForInsertion = get_field('modules', 'option');
        $data = array();
        $start = 6;
        foreach ($modulesForInsertion as $moduleForInsertion) {
            $data[] = array(
                'p' => $start,
                'content' => sprintf('[module id="%d"]', $moduleForInsertion->ID),
                'id' => $moduleForInsertion->ID
            );
            $start = $start + 2;
        }
        return $data;
    }

    /**
     * setupArticleBottomModules
     *
     * Grabs the list of article bottom modules that will need to be inserted from the DB and sets up the data for
     * insertion
     *
     * @return array
     */
    public function setupArticleBottomModules()
    {
        $modulesForInsertion = get_field('modules_bottom', 'option');
        $data = array();
        if (!empty($modulesForInsertion)) {
            foreach ($modulesForInsertion as $moduleForInsertion) {
                $data[] = array(
                    'content' => sprintf('[module id="%d"]', $moduleForInsertion->ID),
                    'id' => $moduleForInsertion->ID
                );
            }
        }
        return $data;
    }

    /**
     * setupRepeatingModules
     *
     * Grabs the list of repeating modules that will need to be inserted from the DB and sets up the data for insertion
     *
     * @return array
     */
    public function setupRepeatingModules()
    {
        $modulesForInsertion = get_field('modules_repeating', 'option');
        $data = array();
        if (!empty($modulesForInsertion)) {
            foreach ($modulesForInsertion as $moduleForInsertion) {
                foreach ($moduleForInsertion['repeating_module'] as $repeating_module) {
                    $data[] = array(
                        //We add `||||` here to act as a string to run a replace on with our actual data when we perform our insertion.
                        'content' => sprintf('[module id="%d" data="||||"]', $repeating_module->ID),
                        'id' => $repeating_module->ID,
                        'interval' => $moduleForInsertion['repeating_module_interval'],
                        'max' => intval($moduleForInsertion['repeating_module_max'])
                    );
                }
            }
        }
        return $data;
    }


    /**
     * canInsert
     *
     * This method will accept a module id as a parameter and then load the rule groups for the module and determine
     * if the module can be inserted based on the rules applied to it.
     * the above criteria.
     *
     * @param $moduleId
     * @return bool
     */
    protected function canInsert($moduleId)
    {
        $ruleGroups = get_post_meta($moduleId, 'active_rule_groups', true);
        if (!empty($ruleGroups)) {
            $valid = Helper::init()->processRuleGroups($ruleGroups, $this->postID);
        } else {
            $valid = true;
        }

        return $valid;
    }
}
