<?php
$is_list = \Fatherly\Listicle\Helper::isListicle($post);
?>

<div class="in-article-ad" amp-access="NOT scroll.scroll">
    <div class="in-article-ad-text advertisement-text">ADVERTISEMENT</div>
    <?php
    Fatherly\Dfp\Helper::init(($is_list) ? false : null)->id('inarticle_amp1')->printAmpTag();
    ?>
</div>