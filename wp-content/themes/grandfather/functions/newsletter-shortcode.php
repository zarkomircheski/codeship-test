<?php
add_shortcode('fth_newsletter', 'fth_shortcode_newsletter');

function fth_shortcode_newsletter($atts, $content = null)
{
    wp_enqueue_script('jquery-ui-datepicker');
    return fth_get_template_part('partials/forms/newsletter-expecting');
}
