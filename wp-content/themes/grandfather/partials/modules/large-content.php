<?php
    $highlighted_post = $module->data['highlighted_post'];
?>
<div class="large-content-title title"><?php echo $module->data['title']; ?></div>
<div class="large-content <?php echo $module->data['content_type'] ?>">

    <div class="large-content-padding"></div>
    <div class="large-content-image">
        <a href="<?php echo $highlighted_post['permalink'] ?>" data-ev-loc="Feed" data-ev-name="Featured Image">
            <img src="<?php echo $highlighted_post['featured_image'] ?>">
        </a>
    </div>
    <div class="large-content-description">
        <?php if ($module->showLogo) : ?>
            <div class="large-content-logo <?php echo ($module->data['quote']) ? 'large-content-logo-bottom' : ''; ?>">
                <img src="/wp-content/themes/grandfather/images/fatherly-icon.svg">
            </div>
        <?php endif; ?>
        <!-- Link to Article -->
        <div class="large-content-info">
            <?php if ($module->showCategory && isset($highlighted_post['category'])) : ?>
                <!-- Link to Category Page-->
                <a href="<?php echo $highlighted_post['category']['url'] ?>" data-ev-loc="Feed" data-ev-name="Category Link"
                   data-ev-val="<?php echo str_replace('"', "'", $highlighted_post['category']['name']) ?>">
                    <span class="large-content-category category">
                        <span class="arrow"></span>
                        <span class="large-content-category-text">
                            <?php echo $highlighted_post['category']['name'] ?>
                        </span>
                    </span>
                </a>
            <?php endif; ?>
            <a href="<?php echo $highlighted_post['permalink']; ?>" data-ev-loc="Feed" data-ev-name="Headline">
                <?php if ($module->data['quote']) { ?>
                    <div class="large-content-quote"> <?php echo $module->data['quote'] ?> &rdquo;
                    </div>
                    <div class="large-content-borders"></div>
                <?php } ?>
                <div class="large-content-dek">
                    <span class="large-content-title"> <?php echo $highlighted_post['title'] ?></span>
                    <span class="large-content-summary"><?php echo $highlighted_post['excerpt'] ?></span>
                    <?php if ($module->showArrow) { ?>
                        <?php echo '<span class="large-content-arrow">&#8594;</span>'; ?>
                    <?php } ?>
                </div>
            </a>
            <?php if (array_key_exists('franchise', $highlighted_post)) : ?>
                <a href="<?php echo get_tag_link($highlighted_post['franchise']); ?>" data-ev-loc="Feed" data-ev-name="Category Link"
                   data-ev-val="<?php echo str_replace('"', "'", $highlighted_post['franchise']->name); ?>" class="franchise__tag franchise__tag__module">
                    <span class="franchise__tag-name"><?php echo $highlighted_post['franchise']->name; ?></span>
                </a>
            <?php endif; ?>
        </div>
        <?php if ($module->showAuthor) { ?>
            <!-- Link to Author Page -->
            <a href="<?php echo $highlighted_post['author']['url'] ?>" data-ev-loc="Feed" data-ev-name="Byline">
                <div class="large-content-author author">By
                    <span><?php echo $highlighted_post['author']['name'] ?></span></div>
            </a>
        <?php } ?>

    </div>
</div>
