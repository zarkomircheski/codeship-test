<?php
foreach ($module->data['posts'] as $m_post) :?>
    <div class="medium-format-content-item">
        <!-- link to Article -->
        <a href="<?php echo $m_post['permalink'] ?>" data-ev-loc="Feed" data-ev-name="Featured Image">
            <img src='<?php echo $m_post['featured_image'] ?>'>
        </a>
        <?php if (array_key_exists('franchise', $m_post) && !is_tag($m_post['franchise'])) : ?>
            <!-- link to franchise -->
            <a href="<?php echo get_tag_link($m_post['franchise']); ?>"
               data-ev-loc="Feed" data-ev-name="Category Link" data-ev-val="<?php echo str_replace('"', "'", $m_post['franchise']->name); ?>"
               class="franchise__tag franchise__tag__module franchise__tag__module-border-top">
                <span class="franchise__tag-name"><?php echo $m_post['franchise']->name; ?></span>
            </a>
        <?php else : ?>
            <!-- link to category -->
            <?php if (isset($m_post['category'])) : ?>
                <a href="<?php echo $m_post['category']['url'] ?>"
                   data-ev-loc="Feed" data-ev-name="Category Link" data-ev-val="<?php echo str_replace('"', "'", $m_post['category']['name']) ?>">
                    <div class="medium-format-content-item-category category"><span
                                class="arrow"></span><?php echo $m_post['category']['name'] ?></div>
                </a>
            <?php endif; ?>
        <?php endif; ?>
        <!-- link to Article -->
        <a href="<?php echo $m_post['permalink'] ?>" data-ev-loc="Feed" data-ev-name="Headline">
            <div class="medium-format-content-item-title"><?php echo $m_post['title'] ?></div>
        </a>
        <?php if (array_key_exists('author', $m_post)) : ?>
            <a href="<?php echo $m_post['author']['url'] ?>" data-ev-loc="Feed" data-ev-name="Byline">
                <div class="medium-format-content-item-author author">By
                    <span><?php echo $m_post['author']['name'] ?></span></div>
            </a>
        <?php endif; ?>
    </div>
<?php endforeach; ?>
