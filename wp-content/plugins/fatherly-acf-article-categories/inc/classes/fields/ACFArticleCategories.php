<?php

namespace FatherlyPlugin\FACF;

use acf_field;

if (!defined('ABSPATH')) {
    exit;
}

class ACFArticleCategories extends acf_field
{
    public function __construct($settings)
    {
        $this->name = 'article_category';
        $this->label = 'Article Category';
        $this->category = 'content';
        $this->settings = $settings;
        $this->viewsPath = $this->settings['path'] . 'inc/views/';
        parent::__construct();
    }

    public function input_admin_enqueue_scripts()
    {
        $url = $this->settings['url'];
        $version = $this->settings['version'];
        wp_register_style('FACF', "{$url}inc/css/input.css", array('acf-input'), $version);
        wp_enqueue_style('FACF');
    }

    public function format_value($value, $post_id, $field)
    {
        if (empty($value)) {
            return $value;
        }
        $value = array_map('intval', $value);
        return $value;
    }

    public function render_field($field)
    {
        $taxName = 'category';
        $taxonomy = get_taxonomy($taxName);
        include_once($this->settings['path'] . 'inc/classes/ArticleCategoryChecklistWalker.php');
        $walker = new ArticleCategoryChecklistWalker($field);
        include_once($this->viewsPath . 'article-categories-metabox.php');
    }
}