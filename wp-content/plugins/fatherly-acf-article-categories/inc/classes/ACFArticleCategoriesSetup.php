<?php

namespace FatherlyPlugin\FACF;

class ACFArticleCategoriesSetup
{
    public $settings;

    public function __construct($settings)
    {
        $this->settings = $settings;
        add_action('acf/include_field_types', array($this, 'includeField')); // v5
    }

    public function includeField()
    {
        include_once('fields/ACFArticleCategories.php');
        new ACFArticleCategories($this->settings);
    }
}