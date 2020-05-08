<?php
get_header();
$registryData = get_fields(get_the_id());
$shareUrl = get_field('full_url', $post->ID) ? get_field('full_url', $post->ID) : get_permalink($post->ID);
$content = apply_filters('the_content', get_the_content());
?>


<div class="registry-landing">
    <div class="registry-landing-header">
        <h3 class="registry-landing-header-style"><?php echo $registryData['title_intro'] ?></h3>
        <h2 class="registry-landing-header-type"><?php the_title(); ?></h2>
        <div class="registry-landing-header-type-description">
            <?php if (substr_count($content, '<p>') > 1) : ?>
                <input id="registry-landing-header-type-description-toggle" type="checkbox">
                <label class="registry-landing-header-type-description-label" for="registry-landing-header-type-description-toggle">
                    <div class="registry-landing-header-type-description-border-left"></div>
                    <div class="registry-landing-header-type-description-arrow"></div>
                    <div class="registry-landing-header-type-description-border-right"></div>

                </label>
            <?php endif; ?>
            <div class="registry-landing-header-type-description-text">
                <?php echo $content; ?>
            </div>
        </div>
        <div class="registry-landing-header-button registry-landing-header-button-share">
            <a data-ev-loc="Results Page" data-ev-name="Share" target="_blank" href="https://www.facebook.com/sharer/sharer.php?u=<?php echo $shareUrl. "?utm_source=facebook&utm_medium=onsiteshare";?>">
                SHARE YOUR RESULTS
            </a>
        </div>
        <div class="registry-landing-header-button registry-landing-header-button-start">
            <a data-ev-loc="Results Page" data-ev-name="Restart" href="<?php echo $registryData['start_page'] ?>">
                TAKE THE QUIZ
            </a>
        </div>
        <div class="registry-landing-header-triangle"></div>
    </div>
    <div class="registry-landing-content">
        <?php foreach ($registryData['create_content_card'] as $contentCard) {?>
            <?php $title = substr(strip_tags($contentCard['text']), 0, 25); ?>
            <div class="registry-landing-content-card">
                <img class="registry-landing-content-img" src="<?php echo $contentCard['lead_image']['url'] ?>">
                <div class="registry-landing-content-card-info"><?php echo $contentCard['text'] ?>
                    <div class="registry-landing-content-card-info-buttons">
                        <a data-ev-loc="Results Page" data-ev-name="Buy Now" data-ev-val="<?php echo $title; ?>" class="registry-landing-content-card-info-button registry-landing-content-card-info-button-buy"
                           target="_blank"  href="<?php echo $contentCard['buy_now_link'] ?>">Buy Now <span>/</span> Add to Registry
                        </a>
                    </div>
                </div>
            </div>
        <?php } ?>
    </div>
</div>
<?php get_footer(); ?>