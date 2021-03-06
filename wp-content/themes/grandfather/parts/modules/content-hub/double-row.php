<?php
$term = get_queried_object();

if (is_category()) {
    if ($term->parent !== 0) {
        $subCatPage = true;
        $module->data['title'] = $term->name;
    }
}
if (is_tag()) {
    if (!get_field('is_franchise', $term)) {
        $module->data['title'] = $term->name;
    }
}
if ($module->data['sub_categories'] && !isset($subCatPage)) {
    $subCategories = \Fatherly\Page\Helper::buildCategoryLinkArray($module->data['sub_categories']);
} else {
    $subCategories = null;
}
?>

<div class="double-row">
    <div class="double-row-title title">
        <?php
        if (!is_tag() && !is_category() && $module->data['disable_link'] !== true) {
            ?>
            <a href="<?php echo site_url($module->data['main_category']->slug) ?>"
            data-ev-loc="Feed" data-ev-name="Category Link" data-ev-val="<?php echo str_replace('"', "'", $module->data['title']) ?>">
                <?php echo $module->data['title'] ?></a>
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
    <div class="double-row-items <?php echo $module->variant; ?>">
        <?php
        $module->renderPartial('medium-format-content');
        ?>
    </div>
</div>
