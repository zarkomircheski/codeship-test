<?php

namespace Fatherly\Page;

use Fatherly\Page\Helper as Helper;
use Fatherly\Page\Module as Module;
use Symfony\Component\Yaml\Yaml;

class Collection
{
    public $moduleDefinitionsFile = __DIR__ . '/ModuleDefinitions.yml';
    public $modules = array();
    public $moduleDefinitions;
    public $collection;
    public $bannerModule;
    public $contextual;
    public static $postIdsToExclude = array();

    public function __construct($collectionId = null, $contextual = false)
    {
        $this->moduleDefinitions = Yaml::parseFile($this->moduleDefinitionsFile);
        $this->contextual = $contextual;
        $this->loadCollection($collectionId);
        $this->setupCollectionModules();
    }


    public function loadCollection($id = null)
    {
        if ($id) {
            $this->collection = $this->loadCollectionById($id);
        } else {
            $this->collection = $this->loadHomepageCollection();
        }
    }

    public function loadHomepageCollection()
    {
        $args = array(
            'no_found_rows' => true,
            'post_type' => 'hp_collection',
            'posts_per_page' => 1,
            'meta_key' => 'collection_type',
            'meta_value' => 'homepage'
        );
        $query = new \WP_Query($args);
        if ($query->have_posts()) {
            $query->post->custom_fields = get_fields($query->post->ID);
            return $query->post;
        } else {
            $args = array(
                'no_found_rows' => true,
                'post_type' => 'hp_collection',
                'posts_per_page' => 1,
            );
            $query = new \WP_Query($args);
            if ($query->have_posts()) {
                $query->post->custom_fields = get_fields($query->post->ID);
                return $query->post;
            }
        }
    }


    public function loadCollectionById($id)
    {
        $args = array(
            'no_found_rows' => true,
            'p' => $id,
            'post_type' => 'hp_collection',
            'posts_per_page' => 1,
        );
        $query = new \WP_Query($args);
        if ($query->have_posts()) {
            $query->post->custom_fields = get_fields($query->post->ID);
            return $query->post;
        }
    }

    public function setupCollectionModules()
    {
        $adCount = 0;
        foreach ($this->collection->custom_fields['modules'] as $module) {
            $moduleFields = get_fields($module->ID);
            $moduleDef = array();
            $moduleDef['id'] = $module->ID;
            $moduleDef['group'] = $moduleFields['module_type'];
            $moduleDef['type'] = $moduleFields[$moduleDef['group'] . '_type'];
            $moduleDef['settings'] = array();
            /*
             * The custom sponsored content module is the only module type that stores its settings in more than one
             * field group so we need to combine those into one array key here so that the settings get passed through
             * correctly to the module class.
             */
            if ($moduleDef['type'] === 'custom_sponsored_content_hub') {
                $moduleFields['custom_sponsored_content_hub_settings'] = array_merge($moduleFields['custom_sponsored_content_hub_settings'], $moduleFields['sponsored_content_hub_settings']);
            }
            foreach ($moduleFields as $fieldKey => $moduleField) {
                if ($fieldKey == $moduleDef['type'] . '_settings') {
                    $moduleDef['settings'] = array_merge($moduleDef['settings'], $moduleField);
                }
            }
            $variant = (isset($moduleDef['settings']['variant'])) ? $moduleDef['settings']['variant'] : null;
            if ($moduleDef['type'] === 'ad') {
                $moduleDef['adCount'] = $adCount;
                $adCount++;
            }
            $defYamlConf = $this->moduleDefinitions[$moduleDef['group']][$moduleDef['type']];
            if ($variant) {
                $defYamlConf = $this->moduleDefinitions[$moduleDef['group']][$moduleDef['type']]['variants'][$variant];
            }
            if (array_key_exists('layout', $defYamlConf)) {
                $moduleDef['layout'] = $defYamlConf['layout'];
            }
            $moduleDef['template'] = $defYamlConf['template'];
            $moduleDef['fields'] = (isset($defYamlConf['fields'])) ? $defYamlConf['fields'] : array();
            if ($this->contextual) {
                $moduleDef['settings']['context'] = $this->contextual;
            }
            $this->modules[] = new Module($moduleDef);
        }
        echo '<script>var excludes = ' . wp_json_encode(self::$postIdsToExclude) . ' </script>';
    }


    public static function addPostIdToExclusion($id)
    {
        if (is_array($id)) {
            self::$postIdsToExclude = array_merge(self::$postIdsToExclude, $id);
        } else {
            self::$postIdsToExclude[] = $id;
        }
    }
}
