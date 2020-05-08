<?php
    $highlighted_post = $module->data['highlighted_post'];
?>
<?php if (!empty($module->data['title'])) : ?>
    <div class="featured-video-title title"><?php echo $module->data['title']; ?></div>
<?php endif; ?>
<div class="featured-video-image">
    <a href="<?php echo $highlighted_post['permalink'] ?>">
        <?php echo fth_img_tag(array('post_id' => $highlighted_post['id'],'width' => 1000, 'height' => 580)); ?>
    </a>
</div>
<div class="featured-video-content">
    <div class="featured-video-content-cta">Featured Video</div>
    <a href="<?php echo $highlighted_post['permalink'] ?>">
        <h3 class="featured-video-content-title"><?php echo $highlighted_post['title']; ?></h3>
    </a>
    <a class="btn__play-button" href="<?php echo $highlighted_post['permalink'] ?>">
        <button class="btn__play-button-icon"></button>
    </a>
</div>

