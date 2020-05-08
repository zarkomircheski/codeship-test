<div class="more-content">
    <div class="more-content-title"><?php echo sprintf("More From %s", (is_category()) ? get_cat_name(get_queried_object_id()) : $module->data['main_category']->name); ?></div>
    <ul class="more-content-list">
        <?php
        foreach ($module->data['more_posts'] as $m_post) :?>
            <li>
                <a href="<?php echo $m_post['url'] ?>" data-ev-loc="Feed" data-ev-name="Headline"><?php echo $m_post['title'] ?></a>
            </li>
        <?php endforeach; ?>
    </ul>
</div>
