<?php
$post_count = count($module->data['sponsored_posts']);
$item_classes = array();
switch ($post_count) {
    case 2:
        $item_classes[0] = 'large';
        $item_classes[1] = 'large';
        break;
    case 3:
        $item_classes[0] = '';
        $item_classes[1] = 'large';
        $item_classes[2] = '';
        break;
    case 4:
        $item_classes[0] = '';
        $item_classes[1] = '';
        $item_classes[2] = '';
        $item_classes[3] = '';
        break;
}
?>
<div class="sponsored-content">
    <div class="sponsored-content-disclaimer">Sponsored Content</div>
    <div class="sponsored-content-title title"><?php echo $module->data['title']; ?></div>
    <?php if (!empty($module->data['sponsor_data']['sponsor_link'])) : ?>
        <a class="sponsored-content-icon" href="<?php echo $module->data['sponsor_data']['sponsor_link']; ?>"
        data-ev-loc="Feed" data-ev-name="Sponsor Logo" data-ev-val="<?php echo str_replace('"', "'", $module->data['sponsor_data']['sponsor_link']); ?>">
            <?php if (!empty($module->data['sponsor_data']['sponsor_logo'])) : ?>
                <img src="<?php echo $module->data['sponsor_data']['sponsor_logo'] ?>">
            <?php endif; ?>
        </a>
    <?php endif; ?>
    <div class="tracker"><?php echo $module->data['tracking_code']; ?></div>
    <div class="sponsored-content-items sponsored-content-items-<?php echo count($module->data['sponsored_posts']) ?>">
        <?php foreach ($module->data['sponsored_posts'] as $i => $m_post) : ?>
            <?php
            $sp_dimensions = array('width' => 400, 'height' => 300);
            $sponsor_post = $m_post['sponsored_post'];
            $sp_dimensions['post_id'] = $sponsor_post['id'];
            $itemUrl = (!empty($m_post['sponsor_link']) ? $m_post['sponsor_link'] : $sponsor_post['permalink']);
            ?>
            <div class="sponsored-content-item <?php echo $item_classes[$i]; ?>">
                <!-- link to Article -->
                <a href="<?php echo $itemUrl ?>" data-ev-loc="Feed" data-ev-name="Featured Image">
                    <img src='<?php echo fth_img($sp_dimensions); ?>'>
                </a>
                <!-- link to category -->
                <?php if (isset($sponsor_post['category'])) : ?>
                    <a href="<?php echo $sponsor_post['category']['url']; ?>" data-ev-loc="Feed" data-ev-name="Category Link" data-ev-val="<?php echo str_replace('"', "'", $sponsor_post['category']['name']); ?>">
                        <div class="sponsored-content-item-category category"><?php echo $sponsor_post['category']['name']; ?></div>
                    </a>
                <?php endif; ?>
                <a href="<?php echo $itemUrl; ?>" data-ev-loc="Feed" data-ev-name="Headline">
                    <div class="sponsored-content-icon-small sponsored-content-item-sponsor-logo">
                        <?php if (!empty($m_post['sponsor_logo'])) : ?>
                            <img src="<?php echo fth_img(array('attachment_id' => $m_post['sponsor_logo']['id'], 'width' => 55, 'retina' => false)); ?>"
                                 alt="">
                        <?php endif; ?>
                    </div>

                </a>
                <!-- link to Article -->
                <?php if (isset($sponsor_post['title']) && $sponsor_post['title']) : ?>
                    <a href="<?php echo $itemUrl ?>" data-ev-loc="Feed" data-ev-name="Headline">
                        <div class="sponsored-content-item-title content-title"><?php echo $sponsor_post['title']; ?>  </div>
                    </a>
                <?php endif; ?>
                <div class="tracker"><?php echo $m_post['tracking_code']; ?></div>
            </div>
        <?php endforeach; ?>
    </div>
</div>
