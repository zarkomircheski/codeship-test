<?php
if ($module->data['sub_categories']) {
    $subCategories = \Fatherly\Page\Helper::buildCategoryLinkArray($module->data['sub_categories']);
} else {
    $subCategories = null;
}

?>
<div class="double-with-lead">
    <div class="double-with-lead-title title">
        <?php
        if (!is_category() && $module->data['disable_link'] !== true) {
            ?>
            <a href="<?php echo site_url($module->data['main_category']->slug) ?>"
               data-ev-loc="Feed" data-ev-name="Category Link" data-ev-val="<?php echo str_replace('"', "'", $module->data['title']) ?>"><?php echo $module->data['title'] ?></a>
            <?php
        } else {
            echo $module->data['title'];
        } ?>
    </div>
    <?php
    if (isset($module->isSponsored)) {
        $module->renderPartial('sponsored-by');
    } ?>
    <div class="double-with-lead-title-sub sub">
        <?php echo ($subCategories) ? implode(
            ', ',
            $subCategories
        ) : null; ?></div>

    <div class="double-with-lead-items">
        <div class="double-with-lead-lead">
            <a href="/<?php echo $module->data['main_category']->slug ?>" data-ev-loc="Feed" data-ev-name="Category Link" data-ev-val="<?php echo str_replace('"', "'", $module->data['title']) ?>">
                <img class="double-with-lead-image" src='<?php echo $module->data['lead_graphic'] ?>'>
            </a>
        </div>
        <?php
        $module->renderPartial('medium-format-content');
        ?>
    </div>
</div>
