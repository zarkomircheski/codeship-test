<div class="sponsored-nav expandable-nav <?php echo $module->data['text_color'] ?>" style="background-color:<?php echo $module->data['color']; ?>;">
    <div class="sponsored-nav-left">
        <?php set_query_var('nav_name', 'sponsored-nav-button'); ?>
        <?php get_template_part('partials/navigation/menu', 'hamburger'); ?>
        <a class="sponsored-nav-logo" href="<?php echo get_site_url()?>">
            <?php get_template_part('img/wordmark', 'header.svg'); ?>
        </a>
    </div>
    <div class="sponsored-nav-sponsor">
        <?php if ($module->data['link']) { ?>
            <a href="<?php echo $module->data['link'] ?>">
        <?php } ?>
            <img src="<?php echo $module->data['image'] ?>">
        <?php if ($module->data['link']) { ?>
            </a>
        <?php } ?>
    </div>
    <?php get_template_part('partials/navigation/menu', 'slideout'); ?>
</div>
