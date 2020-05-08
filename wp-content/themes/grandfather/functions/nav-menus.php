<?php
// add specific WP menus
add_action('init', 'register_my_menu');
function register_my_menu()
{
    register_nav_menus(array(
        'primary' => __('Primary Menu', 'fatherly'),
        'footer-menu' => __('Footer Menu', 'fatherly'),
        'header-secondary' => __('Secondary Menu', 'fatherly'),
        'slideout-secondary' => __('Slideout Bottom Menu', 'fatherly'),
        'vertical-menu' => __('Category Menu', 'fatherly'),
        'parenting-stages' => __('Parenting Stages', 'fatherly'),
        'article-menu' => __('Article Menu', 'fatherly'),
        ));
}

function vertical_menu()
{
    return wp_nav_menu(array(
        'items_wrap' => '%3$s',
        'container' => null,
        'theme_location' => 'vertical-menu',
        'walker' => new Fatherly\Nav\Walker
    ));
}

function parenting_menu()
{
    return wp_nav_menu(array(
        'container' => '',
        'theme_location' => 'parenting-stages',
        'menu_class' => 'nav__main-menu--slideout-section-parenting-stages',
        'walker' => new Fatherly\Nav\gaWalker,
    ));
}

function slideout_secondary_menu()
{
    return wp_nav_menu(array(
        'items_wrap' => '%3$s',
        'container' => null,
        'theme_location' => 'slideout-secondary',
        'walker' => new Fatherly\Nav\gaWalker,
    ));
}

function parenting_menu_homepage()
{
    return wp_nav_menu(array(
        'container' => '',
        'theme_location' => 'parenting-stages',
        'menu_class' => 'header-nav-links-bottom',
        'walker' => new Fatherly\Nav\gaWalker,
    ));
}

function header_secondary_menu()
{
    return wp_nav_menu(array(
        'container' => '',
        'theme_location' => 'header-secondary',
        'menu_class' => 'header-nav-second-top',
        'walker' => new Fatherly\Nav\gaWalker,
    ));
}

function header_article_menu()
{
    return wp_nav_menu(array(
        'container'       => 'nav',
        'container_class' => 'nav__main-menu--secondary-verticals',
        'theme_location'  => 'article-menu',
        'walker' => new Fatherly\Nav\gaWalker,
    ));
}
