<?php
$title = (get_query_var('show_title', true) ? $module->data['title'] : null);
$row_posts = (get_query_var('custom_posts') ? $custom_posts : $module->data['posts']);
?>
<div class="single-row">
    <div class="single-row-items">
        <?php foreach ($row_posts as $i => $row_post) : ?>
            <div class="single-row-item">
                <!-- link to article -->
                <a href="<?php echo $row_post['permalink'] ?>" data-ev-loc="Feed" data-ev-name="Featured Image">
                    <img src='<?php echo $row_post['featured_image'] ?>'>
                </a>
                <?php if (array_key_exists('franchise', $row_post)) : ?>
                    <!-- link to franchise -->
                    <a href="<?php echo get_tag_link($row_post['franchise']); ?>" data-ev-loc="Feed" data-ev-name="Category Link"
                       data-ev-val="<?php echo str_replace('"', "'", $row_post['franchise']->name); ?>"
                       class="franchise__tag franchise__tag__module franchise__tag__module-border-top single-row-item-franchise-link">
                        <span class="franchise__tag-name"><?php echo $row_post['franchise']->name; ?></span>
                    </a>
                <?php else : ?>
                    <!-- link to category -->
                    <?php if ($row_post['category']) : ?>
                        <a class="single-row-item-category-link" href="<?php echo $row_post['category']['url'] ?>"
                           data-ev-loc="Feed" data-ev-name="Category Link" data-ev-val="<?php str_replace('"', "'", categoryWrap($row_post['category']['name'])); ?>">
                            <div class="single-row-item-category category"><span
                                        class="arrow"></span><?php categoryWrap($row_post['category']['name']); ?></div>
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
            </div>
        <?php endforeach; ?>
    </div>
</div>

