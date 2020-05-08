<div class="popular-topics-wrapper">
    <div class="popular-topics-image">
        <?php if ($module->data['show_arrows'] == "true") : ?>
            <img src='https://images.fatherly.com/wp-content/uploads/2018/01/arrows_back2.png'>
        <?php endif ?>
    </div>
    <div class="popular-topics <?php echo ($module->data['show_arrows'] == "true") ? "arrows" : "no-arrows" ?>">
        <div class="popular-topics-text">
            <?php echo wp_kses_post($module->data['content']); ?>
        </div>

    </div>
</div>
