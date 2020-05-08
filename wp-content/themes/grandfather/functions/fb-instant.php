<?php
function fb_page_meta()
{
    $page_id = '348596051860210';
    echo '<meta property="fb:pages" content="' . $page_id . '" />';
}
add_action('schema_org', 'fb_page_meta');
