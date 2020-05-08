<?php
set_query_var('show_title', false);

if (is_tax('stages')) {
    $stage = get_queried_object();
    $title = sprintf(__("More From %s", 'fth'), $module->data['title']);
    $n = 0;
    foreach ($module->data['sub_categories'] as $key => $subcat) {
        if ($subcat == $stage->name) {
            $active_index = $n;
            break;
        } else {
            $n++;
        }
    }
} else {
    $title = $module->data['title'];
    $active_index = 2;
}
?>

<div class="parenting">
    <button class="parenting-arrow parenting-arrow-left stop">&#8592;</button>
    <button class="parenting-arrow parenting-arrow-right">&#8594;</button>
    <div class="parenting-title title">
        <?php
        if (!is_category()) { ?>
            <a href="<?php echo site_url($module->data['main_category']->slug) ?>" data-ev-loc="Feed" data-ev-name="Category Link" data-ev-val="<?php echo str_replace('"', "'", $title) ?>">
                <?php echo $title ?></a>
            <?php
        } else {
            echo $title;
        } ?>
    </div>

    <div class="parenting-nav">
        <ul>
            <?php $i = 0; ?>
            <?php foreach ($module->data['sub_categories'] as $index => $subnav) : ?>
                <li id="<?php echo $i; ?>"
                    class="parenting-link <?php echo ($i == $active_index) ? ' active' : null; ?>"
                    data-ev-loc="Feed" data-ev-name="Stage Link" data-ev-val="<?php echo str_replace('"', "'", $subnav); ?>"><?php echo $subnav ?>

                    <div class="parenting-down-arrow">
                        <div class="parenting-down-arrow-border parenting-down-arrow-left"></div>
                        <div class="parenting-down-arrow-center"></div>
                        <div class="parenting-down-arrow-border parenting-down-arrow-right"></div>
                    </div>
                </li>
                <?php $i++; ?>
            <?php endforeach; ?>

        </ul>
    </div>
    <div class="parenting-overflow <?php echo sprintf("move-%d", $active_index); ?>">
        <div class="parenting-content">
            <?php $i = 0; ?>

            <?php foreach ($module->data['posts'] as $row_index => $row_posts) : ?>
                <div class="parenting-section index-<?php echo $i; ?> <?php echo ($i == $active_index) ? ' active' : null; ?>">
                    <?php
                    set_query_var('custom_posts', $row_posts);
                    $module->renderPartial('parenting-single-row');
                    ?>

                </div>
                <?php $i++; ?>
            <?php endforeach; ?>
            <?php set_query_var('custom_posts', null); ?>
        </div>
    </div>
    <div class="parenting-more">
        <?php
        $more_posts = array_chunk($module->data['more_posts'], 3);
        set_query_var('m_posts', $more_posts);
        set_query_var('m_category', $module->data['title']);
        $module->renderPartial('more-content-parenting');
        ?>
    </div>
</div>
