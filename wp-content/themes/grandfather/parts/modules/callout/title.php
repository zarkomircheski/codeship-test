<div class="title-container">
    <div class="title-only title"><?php echo $module->data['title'] ?></div>
    <?php if (isset($module->data['sponsor_data'])) : ?>
        <div class="title-only-sponsor">
            <span class="sponsored-by">sponsored by </span>
            <a class="title-only-sponsor-logo" href="<?php echo $module->data['sponsor_data']['sponsor_link']; ?>"
               data-ev-loc="Secondary" data-ev-name="Sponsored Logo" data-ev-val="<?php echo $module->data['sponsor_data']['sponsor_link']; ?>">
                <img src="<?php echo $module->data['sponsor_data']['sponsor_logo_url'] ?>">
            </a>
        </div>
    <?php endif; ?>
</div>
