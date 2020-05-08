<?php
$ad_slot_name = 'inarticle' . $module->data['ad_name'];
?>

<div class="in-article-ad">
    <div class="in-article-ad-text advertisement-text">ADVERTISEMENT</div>
    <?php
    Fatherly\Dfp\Helper::init()->id($ad_slot_name)->printTag();
    ?>
</div>