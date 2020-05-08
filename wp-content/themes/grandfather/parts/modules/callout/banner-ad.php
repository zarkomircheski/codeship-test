<?php
if ($module && array_key_exists('ad_name', $module->data)) {
    $ad_slot_name = $module->data['ad_name'];
} else {
    $ad_slot_name = 'lead1';
}
?>

<div class="homepage-leaderboard">
    <div class="homepage-leaderboard-ad">
    <div class="homepage-leaderboard-text advertisement-text">ADVERTISEMENT</div>
        <?php Fatherly\Dfp\Helper::init()->id($ad_slot_name)->printTag(); ?>
    </div>
    <div class="homepage-leaderboard-placeholder"></div>
</div>