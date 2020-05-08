<?php
$ad_slot_number = $module->data['ad_name'] + 1 ?: 1;
$ad_slot_name = 'inarticle_amp' . $ad_slot_number;
$is_list = \Fatherly\Listicle\Helper::isListicle($post);
?>

<div class="in-article-ad" amp-access="NOT scroll.scroll">
    <div class="in-article-ad-text advertisement-text">ADVERTISEMENT</div>
    <?php
    Fatherly\Dfp\Helper::init(($is_list) ? false : null)->id($ad_slot_name)->printAmpTag();
    ?>
</div>
