<?php

function fth_enqueue_pinit()
{
    ?>
<script
    type="text/javascript"
    async defer
    data-pin-build="parsePinBtns"
    src="//assets.pinterest.com/js/pinit.js"
></script>

<script>
   window.addEventListener('newContentLoaded', function(){
        window.parsePinBtns();
    });
</script>
    <?php
}
add_action('wp_footer', 'fth_enqueue_pinit');
