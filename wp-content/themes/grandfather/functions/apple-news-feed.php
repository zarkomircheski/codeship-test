<?php
function custom_apple_rss()
{
    get_template_part('rss', 'applenews');
}
add_action('init', 'customRSS');
function customRSS()
{
    add_feed('parenting-feed', 'custom_apple_rss');
}
