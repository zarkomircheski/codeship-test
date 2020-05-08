<?php
add_shortcode('connatix', 'shortcode_connatix');


function shortcode_connatix($atts)
{
    $shortCodeAtts = shortcode_atts(array(
        'script' => '',
        'player' => '',
        'media' => '',
    ), $atts);

    if (!is_amp_endpoint()) {
        return "<div class='article__content-video'><script id='".$shortCodeAtts['script']."'>cnx.cmd.push(function() { cnx({".
        "playerId: '".$shortCodeAtts['player']."',".
        "mediaId: '".$shortCodeAtts['media']."'".
        "}).render('".$shortCodeAtts['script']."');});</script></div>";
    } else {
        return '<div class="amp-wp-article-content-video"><amp-connatix-player data-player-id="'.$shortCodeAtts['player'].'"'.
        'data-media-id="'.$shortCodeAtts['media'].'"'.
        'layout="responsive" width="16" height="9"></amp-connatix-player></div>';
    }
}
