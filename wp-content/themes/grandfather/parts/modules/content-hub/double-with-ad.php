<?php
if ($module->data['sub_categories']) {
    $subCategories = \Fatherly\Page\Helper::buildCategoryLinkArray($module->data['sub_categories']);
} else {
    $subCategories = null;
}
?>

<div class="double-row">
    <div class="double-row-title title">
        <?php
        if (!is_category() && $module->data['disable_link'] !== true) {
            ?>
            <a href="<?php echo site_url($module->data['main_category']->slug) ?>"><?php echo $module->data['title'] ?></a>
            <?php
        } else {
            echo $module->data['title'];
        } ?>
    </div>
    <?php
    if (isset($module->isSponsored)) {
        $module->renderPartial('sponsored-by');
    } ?>
    <div class="double-row-title-sub sub"><?php echo ($subCategories) ? implode(', ', $subCategories) : null; ?></div>
    <div class="double-row-items <?php echo $module->variant ?>">
        <?php
        $module->renderPartial('medium-format-content-ad');
        ?>
    </div>
</div>
