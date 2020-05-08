<?php
$title = ((get_query_var('show_title', true) || $module->variant === 'custom') ? $module->data['title'] : null);
$row_posts = (array_key_exists(
    'highlighted_posts',
    $module->data
)) ? $module->data['highlighted_posts'] : $module->data['posts'];
$count = 0;
?>
<div class="single-row">
    <div class="single-row-title title">
        <?php
        if (!is_tax('stages') && !empty($module->data['title_slug'])) {
            ?>
            <a href="<?php echo site_url($module->data['title_slug']) ?>" data-ev-loc="Feed" data-ev-name="Category Link" dave-ev-val="<?php echo str_replace('"', "'", $module->data['title']) ?>"> <?php echo $module->data['title'] ?> </a>
            <?php
        } elseif (!is_category() && !empty($module->data['view_more_url'])) {
            ?>
            <a href="<?php echo $module->data['view_more_url'] ?>" data-ev-loc="Feed" data-ev-name="Category Link" dave-ev-val="<?php echo str_replace('"', "'", $module->data['title']) ?>"> <?php echo $module->data['title'] ?> </a>
            <?php
        } else {
            echo $title;
        } ?>
    </div>
    <div class="single-row-items">
        <?php foreach ($row_posts as $i => $row_post) : ?>
            <div class="single-row-item">
                <?php if ($count !== 4 || $module->variant !== 'trending') : ?>
                    <!-- link to article -->
                    <a href="<?php echo $row_post['permalink'] ?>" data-ev-loc="Feed" data-ev-name="Featured Image">
                        <img src='<?php echo $row_post['featured_image'] ?>'>
                    </a>
                    <!-- link to category -->
                    <?php if (array_key_exists('franchise', $row_post)) : ?>
                        <!-- link to franchise -->
                        <a href="<?php echo get_tag_link($row_post['franchise']); ?>"
                           data-ev-loc="Feed" data-ev-name="Category Link" data-ev-val="<?php echo str_replace('"', "'", $row_post['franchise']->name); ?>"
                           class="franchise__tag franchise__tag__module franchise__tag__module-border-top single-row-item-franchise-link">
                            <span class="franchise__tag-name"><?php echo $row_post['franchise']->name; ?></span>
                        </a>
                    <?php else : ?>
                        <?php if (isset($row_post['category'])) : ?>
                            <a class="single-row-item-category-link" href="<?php echo $row_post['category']['url'] ?>"
                               data-ev-loc="Feed" data-ev-name="Category Link" data-ev-val="<?php echo str_replace('"', "'", categoryWrap($row_post['category']['name'])); ?>">
                                <div class="single-row-item-category category"><span
                                            class="arrow"></span><?php echo categoryWrap($row_post['category']['name']); ?>
                                </div>
                            </a>
                        <?php endif; ?>
                    <?php endif; ?>
                    <!-- Link to Article -->
                    <a class="single-row-item-title-link" href="<?php echo $row_post['permalink'] ?>" data-ev-loc="Feed" data-ev-name="Headline">
                        <div class="single-row-item-title"> <?php echo $row_post['title']; ?> </div>
                    </a>
                    <!-- Link to Author Page -->
                    <?php if (isset($row_post['author'])) : ?>
                        <a href="<?php echo $row_post['author']['url'] ?>" data-ev-loc="Feed" data-ev-name="Byline">
                            <div class="single-row-item-author author">By
                                <span><?php echo $row_post['author']['name'] ?></span></div>
                        </a>
                    <?php endif; ?>
                <?php elseif ($module->variant === 'trending') : ?>
                    <div class="nativo-ad">
                        <?php Fatherly\Dfp\Helper::init()->id('list_native1')->printTag(); ?>
                    </div>
                <?php endif;
                $count++; ?>
            </div>
        <?php endforeach; ?>

    </div>
    <?php if ($module->variant === 'trending') : ?>
        <div class="view-more">
            <a href="<?php echo $module->data['view_more_url'] ?>">View More &#8594;</a>
        </div>
    <?php endif; ?>
</div>

