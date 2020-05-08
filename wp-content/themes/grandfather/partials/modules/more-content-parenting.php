<?php
if (!empty($m_category)) {
    $cat = "More From {$m_category}";
} else {
    $cat = '';
}
if (is_tax('stages')) {
    $cat = "";
}
?>
<?php if (!empty($cat)) :?>
<div class="more-content-title"><?php echo $cat ?></div>
<?php endif;?>
<div class="more-content-wrapper">
<?php foreach ($m_posts as $m_post_group) : ?>
    <div class="more-content">
        <ul class="more-content-list">
            <?php foreach ($m_post_group as $m_post) : ?>
                <li>
                    <a href="<?php echo $m_post['url'] ?>" data-ev-loc="Feed" data-ev-name="Headline"><?php echo $m_post['title'] ?></a>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endforeach; ?>
</div>
