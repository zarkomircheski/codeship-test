<?php
if ($module->data['sub_categories']) {
    $subCategories = \Fatherly\Page\Helper::buildCategoryLinkArray($module->data['sub_categories']);
} else {
    $subCategories = null;
}

?>
<div class="three-items-with-lead">

    <div class="three-items-with-lead-title title">
        <?php
        if (!is_category() && $module->data['disable_link'] !== true) { ?>
            <a href="<?php echo site_url($module->data['main_category']->slug) ?>"
            data-ev-loc="Feed" data-ev-name="Category Link" data-ev-val="<?php echo str_replace('"', "'", $module->data['title']) ?>"><?php echo $module->data['title'] ?></a>
            <?php
        } else {
            echo $module->data['title'];
        }
        ?>
    </div>
    <?php
    if (isset($module->isSponsored)) {
        $module->renderPartial('sponsored-by');
    }
    ?>
    <div class="three-items-with-lead-sub sub"><?php
        echo ($subCategories) ? implode(
            ', ',
            $subCategories
        ) : null; ?></div>
    <div class="three-items-with-lead-items">
        <?php foreach ($module->data['highlighted_posts'] as $i => $feature) : ?>
            <div class="three-items-with-lead-item <?php
            if ($i == 0) {
                echo 'large';
            } ?>">
                <div class="three-items-with-lead-image <?php
                if ($i == 0) {
                    echo 'large';
                } ?>">
                    <!-- Link to Article -->
                    <?php
                    if ($i == 0) {
                        $dimensions = array('post_id' => $feature['id'], 'width' => 480, 'height' => 530); ?>
                        <?php
                    } else {
                        $dimensions = array('post_id' => $feature['id'], 'width' => 180, 'height' => 180);
                    } ?>

                    <!-- link to category -->
                    <a href="<?php echo $feature['permalink'] ?>" data-ev-loc="Feed" data-ev-name="Featured Image">
                        <img src='<?php echo fth_img($dimensions) ?>'
                            <?php
                            if (array_key_exists('franchise', $feature)) {
                                echo "class=\"is-franchise\"";
                            }
                            ?>>
                    </a>
                    <?php if (array_key_exists('franchise', $feature)) : ?>
                        <!-- link to franchise -->
                        <a href="<?php echo get_tag_link($feature['franchise']); ?>"
                           data-ev-loc="Feed" data-ev-name="Category Link" data-ev-val="<?php echo str_replace('"', "'", $feature['franchise']->name); ?>"
                           class="franchise__tag franchise__tag__module franchise__tag__module-border-top">
                            <span class="franchise__tag-name"><?php echo $feature['franchise']->name; ?></span>
                        </a>
                    <?php else : ?>
                        <?php if (isset($feature['category'])) : ?>
                            <a href="<?php echo $feature['category']['url'] ?>"
                               data-ev-loc="Feed" data-ev-name="Category Link" data-ev-val="<?php echo str_replace('"', "'", $feature['category']['name']) ?>">
                                <div class="three-items-with-lead-item-category category"><span
                                            class="arrow"></span><?php echo $feature['category']['name'] ?>
                                </div>
                            </a>
                        <?php endif; ?>
                    <?php endif; ?>
                </div>
                <div class="three-items-with-lead-description <?php
                if ($i == 0) {
                    echo 'large';
                } ?>">
                    <!-- Link to Article -->
                    <a href="<?php echo $feature['permalink'] ?>" data-ev-loc="Feed" data-ev-name="Headline">
                        <span class="three-items-with-lead-title"><?php echo $feature['title'] ?></span>
                        <span class="three-items-with-lead-summary"><?php echo $feature['excerpt'] ?></span>
                    </a>
                    <!-- Link to Author Page -->
                    <a href="<?php echo $feature['author']['url'] ?>" data-ev-loc="Feed" data-ev-name="Byline">
                        <div class="three-items-with-lead-item-author author">
                            By <span><?php echo $feature['author']['name'] ?></span></div>
                    </a>
                </div>
            </div>
        <?php endforeach; ?>
        <div class="three-items-with-lead-item more-content">
            <?php
            $module->renderPartial('more-content');

            ?>
        </div>
    </div>
    <span class="three-items-with-lead-height"></span>
</div>
