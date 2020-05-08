<?php
// button
function myCustomButton($atts, $content = null)
{
    extract(shortcode_atts(array('link' => '#'), $atts));
    return '<a class="button" href="'.$link.'">' . do_shortcode($content) . '</a>';
}
add_shortcode('button', 'myCustomButton');
