<div class="sponsored-content">
    <div class="sponsored-content-disclaimer">Sponsored Content</div>
    <div class="sponsored-content-title title"><?php echo $module->data['title']; ?></div>
    <a class="sponsored-content-icon" href="<?php echo $module->data['sponsor_data']['sponsor_link']; ?>">
        <img src="<?php echo $module->data['sponsor_data']['sponsor_logo'] ?>">
    </a>
    <div class="sponsored-content-items">
        <?php foreach ($module->data['highlighted_posts'] as $i => $m_post) : ?>
            <?php $sp_dimensions = ($i == 1) ? array('width' => 400, 'height' => 300) : array(
                    'width' => 240,
                    'height' => 180
                );
                $sp_dimensions['post_id'] = $m_post['id'];
?>
            <div class="sponsored-content-item <?php
            if ($i == 1) {
                echo 'large';
            } ?>">
                <!-- link to Article -->
                <a href="<?php echo $m_post['permalink']; ?>">
                    <img src='<?php echo fth_img($sp_dimensions); ?>'>
                </a>
                <!-- link to category -->
                <?php if (isset($m_post['category'])) : ?>
                    <a href="<?php echo $m_post['category']['url']; ?>">
                        <div class="sponsored-content-item-category category"><?php echo $m_post['category']['name']; ?></div>
                    </a>
                <?php endif; ?>
                <!-- link to Article -->
                <a href="<?php echo $m_post['permalink']; ?>">
                    <div class="sponsored-content-item-title content-title"><?php echo $m_post['title']; ?>  </div>
                </a>
            </div>
        <?php endforeach; ?>
    </div>
</div>
