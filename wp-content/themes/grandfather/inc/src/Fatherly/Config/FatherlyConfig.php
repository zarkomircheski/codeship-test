<?php

namespace Fatherly\Config;

/**
 * Class FatherlyConfig
 *
 * A registry for global configuration variables that can be used in any part of the app. This class is a singleton
 * and must be called as such. Items inside of the dev environment will be loaded inside of production as well so the
 * items in the development_envs can be treated as global.
 *
 * Everything can be called in single line:
 *
 * //Get Item
 * $variable = FatherlyConfig::config() -> get('item');
 *
 */
class FatherlyConfig
{

    //The set environment, production or development
    private $_env = null;

    //The set production variables
    private $_production_envs = array(
        'postgres_host' => '10.46.1.4',
        'header_bidding' => '//biddr.brealtime.com/41479704-1234.js'
    );

    private $_staging_envs = array(
        'gtm_container_id' => 'GTM-PQMJTXX',
        'ga_id' => 'UA-50600763-1',
        'header_bidding' => '//biddr.brealtime.com/81308497-1252.js',
        'parsely_secret_key' => false,
        'postgres_host' => '10.55.1.3'
    );

    //The set variables for staging and local
    // variables set here will also be used on production unless there is a variable set to overwrite it
    private $_development_envs = array(
        'gtm_container_id' => 'GTM-TNTTDH3',
        'fb_app_id' => '1667245973359975',
        'ga_id' => 'UA-50600763-2',
        'parsely_secret_key' => 'b93JYKXkEjdgHjJX7qVSJaWVBhkq8UMkyJE9zPOLV9Y',
        'postgres_host' => 'host.docker.internal',
        'post_types_excluded_in_sitemap' => array(
            'hp_collection',
            'hp_module',
            'rule_groups'
        ),
        'header_bidding' => '//biddr.brealtime.com/81308497-1252.js',
        'postgresUser' => 'postgres'
    );


    //The stored singleton instance
    private static $_instance = null;


    /**
     * FatherlyConfig constructor
     *
     * The constructor for initiating the class. Should set which
     * env is being used based on the constant. Its private because its only
     * accessible through the config method.
     */
    private function __construct()
    {

        //Adding social accounts on instantiation
        $this->_development_envs['social_accounts'] = (object) array(
            'facebook' => 'https://www.facebook.com/FatherlyHQ/',
            'pinterest' => 'https://www.pinterest.com/FatherlyHQ/',
            'instagram' => 'https://www.instagram.com/Fatherly/',
            'youtube' => 'https://www.youtube.com/channel/UC-PfbmXWqUYO_UCKP08LKDA',
            'twitter' => 'https://twitter.com/FatherlyHQ',
        );
        if (constant('ENV') == 'prod') {
            $this->_env = 'production';
            $this->_production_envs += $this->_staging_envs + $this->_development_envs;
        } else if (constant('ENV') == 'staging') {
            $this->_env = 'staging';
            $this->_staging_envs += $this->_development_envs;
        } else {
            $this->_env = 'development';
        }
    }

    /**
     * Call this method to get singleton instance of the configuration
     *
     * @return FatherlyConfig
     */
    public static function config()
    {
        if (!isset(static::$_instance)) {
            static::$_instance = new static;
        }
        return static::$_instance;
    }

    /**
     * Returns all the the environment variables
     *
     * @return array
     */
    public function getEnvVariables()
    {
        if ($this->_env == 'production') {
            return $this->_production_envs;
        } else if ($this->_env == 'staging') {
            return $this->_staging_envs;
        }

        return $this->_development_envs;
    }

    /**
     * Return a single environment variable based on the key
     *
     * @param $key
     * @return bool|mixed
     */
    public function get($key)
    {
        $environment = $this->getEnvVariables();

        if (isset($environment[$key])) {
            return $environment[$key];
        }

        return false;
    }

    /**
     * Checks to see if a config variable exist
     *
     * @param $key
     * @return bool|mixed
     */
    public function exist($key)
    {
        $environment = $this->getEnvVariables();

        return isset($environment[$key]);
    }
}
