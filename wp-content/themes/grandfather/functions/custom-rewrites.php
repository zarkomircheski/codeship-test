<?php

function fth_stages_custom_rewrite()
{
    Fatherly\Rewrite\RewriteRules::init();
}

function fth_listicle_custom_rewrites()
{
    \Fatherly\Rewrite\ListicleRules::init();
}

add_action('init', 'fth_stages_custom_rewrite', -1);
add_action('init', 'fth_listicle_custom_rewrites', -1);
