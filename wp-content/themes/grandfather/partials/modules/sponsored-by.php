<?php if ($module->isSponsored) : ?>
    <a class="sponsored-by-icon" href="<?php echo $module->data['sponsor_data']['sponsor_link']; ?>"
        data-ev-loc="Feed" data-ev-name="Sponsor Logo" data-ev-val="<?php echo str_replace('"', "'", $module->data['sponsor_data']['sponsor_link']); ?>">
        <div class="sponsored-by-byline">Sponsored by</div>
        <div class="sponsored-by-image">
            <img src="<?php echo $module->data['sponsor_data']['sponsor_logo_url']; ?>">
        </div>
    </a>
<?php endif; ?>
