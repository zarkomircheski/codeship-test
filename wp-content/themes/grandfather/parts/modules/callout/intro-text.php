<div class="intro-text">
    <h2 class="intro-text-title">
        <?php echo $module->data['title'] ?>
    </h2>
    <div class="intro-text-content">
        <?php if (substr_count($module->data['content'], '<p>') > 1) : ?>
            <input id="intro-text-toggle" type="checkbox">
            <label for="intro-text-toggle">Read More <div class="read-more-arrow"></div></label>
        <?php endif; ?>
        <div class="intro-text-content-text">
            <?php echo $module->data['content'] ?>
        </div>
    </div>
</div>
