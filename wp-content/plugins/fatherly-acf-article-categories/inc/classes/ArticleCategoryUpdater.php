<?php

namespace FatherlyPlugin\FACF;

class ArticleCategoryUpdater
{
    public function __construct()
    {
        add_action('save_post', array($this, 'setCategoriesFromCustomField'), 10, 1);
    }

    public function setCategoriesFromCustomField($id)
    {
        $post = get_post($id);
        $currentCategories = wp_get_post_categories($id);
        $updatedCategories = get_field('article_categories', $id);
        if ($currentCategories && $updatedCategories) {
            if ($currentCategories !== $updatedCategories) {
                wp_set_post_categories($id, $updatedCategories);
            }
        }
    }
}