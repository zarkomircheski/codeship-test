<?php
$popular_ids = wp_popular_terms_checklist($taxName, 0, 10, false);
$fieldName = "article-" . $taxName;
$fields = get_fields(get_the_ID());
?>

<div id="<?php echo $fieldName ?>" class="categorydiv fatherly-category-div">
    <ul id="<?php echo $taxName; ?>-tabs" class="category-tabs">
        <li class="tabs"><a href="#<?php echo $taxName; ?>-all"><?php echo $taxonomy->labels->all_items; ?></a></li>
    </ul>
    <div id="<?php echo $fieldName; ?>-all" class="tabs-panel">
        <ul id="<?php echo $fieldName; ?>checklist" class="categorychecklist form-no-clear">
            <?php wp_terms_checklist(get_the_ID(), array('taxonomy' => $taxName, 'popular_cats' => $popular_ids, 'walker' => $walker, 'testarg' => 'testing')); ?>
        </ul>
    </div>
</div>