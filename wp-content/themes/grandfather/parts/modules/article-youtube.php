<?php
$youtubeUrl = get_query_var('youtubeURL');
$isShortened = strpos($youtubeUrl, 'youtu.be/');
if ($isShortened !== false) {
    $youtubeUrl = strtok($youtubeUrl, '?');
    $link =  substr($youtubeUrl, $isShortened + 9);
} else {
    parse_str(parse_url($youtubeUrl, PHP_URL_QUERY), $urlParams);
    $link = $urlParams['v'];
}
?>
<div class="article-video-lead-youtube-container">
    <iframe id="ytplayer" type="text/html"
            src="https://www.youtube.com/embed/<?php echo $link; ?>"
            frameborder="0" allowfullscreen>
    </iframe>
</div>