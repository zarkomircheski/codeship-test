<?php
$banner_image = fth_img(array('attachment_id' => $module->data['banner_image']['ID'], 'height' => '277px'));
$tag = get_queried_object();
$title_text = (isset($module->data['title_text'])) ? $module->data['title_text'] : $tag->name;
$classes = array("tag-banner-title");
if (!tag_description()) {
    $classes[] = "no-desc";
}
?>

<div class="tag-banner" style="background-image: url('<?php echo $banner_image ?>');">
    <div class="tag-banner-overlay"></div>
    <div class="<?php echo implode(" ", $classes); ?>">
            <div class="franchise__tag franchise__tag__tag-banner">
            <?php if (!is_tag($tag)) : ?>
                <a href="<?php echo get_tag_link($tag); ?>">
            <?php endif; ?>
            <div class="franchise__tag-info <?php echo (is_tag($tag)) ? 'on-tag-page' : ''; ?>">
        <span class="franchise__tag-icon"><img
                    src="<?php echo fth_get_protocol_relative_template_directory_uri(); ?>/images/fatherly-icon-white.svg"
                    alt="fatherly logo" class="seahorse-logo"></span>
                <span class="franchise__tag-name"><em><?php echo $title_text; ?></em></span>
            </div>
            <?php if (!is_tag($tag)) : ?>
                </a>
            <?php endif; ?>
            </div>
        <?php if (tag_description()) : ?>
            <div class="tag-banner-description">
                <?php echo tag_description(); ?>
            </div>
        <?php endif; ?>
    </div>
</div>
