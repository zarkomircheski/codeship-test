<?php
$sponsor = $module->data['sponsor_name'];
$image = $module->data['image']['sizes']['apple_news_ca_square_4_0'];
$title = $module->data['title'];
$content = $module->data['content'];
$button = $module->data['button'];
$tracking = $module->data['tracking'];

?>
<section class="module module__sponsor-highlight">
    <?php if ($sponsor) : ?>
        <div class="module__sponsor-highlight--sponsor-name">
            <span>Sponsored by <?php echo $sponsor; ?></span>
        </div>
    <?php endif; ?>
    <div class="module__sponsor-highlight--columns">
        <div class="module__sponsor-highlight--column">
            <?php if ($image) : ?>
                <div class="module__sponsor-highlight--image">
                    <img src="<?php echo $image; ?>" alt="">
                </div>
            <?php endif; ?>
        </div>
        <div class="module__sponsor-highlight--column">
            <?php if ($title) : ?>
                <div class="module__sponsor-highlight--title">
                    <h5><?php echo $title; ?></h5>
                </div>
            <?php endif; ?>
            <?php if ($content) : ?>
                <div class="module__sponsor-highlight--content">
                    <?php echo $content; ?>
                </div>
            <?php endif; ?>
            <?php if ($button) : ?>
            <div class="module__sponsor-highlight--button">
                <?php echo $button; ?>
            </div>
        </div>
    </div>
            <?php endif; ?>
    <?php if ($tracking) : ?>
        <?php echo $tracking; ?>
    <?php endif; ?>

</section>