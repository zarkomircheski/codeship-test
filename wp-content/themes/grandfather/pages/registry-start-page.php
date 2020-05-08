<?php
/*
Template Name: Registry Start Page
*/

get_header();
$registryData = get_fields(get_the_ID());
?>
<div class="registry-start-page">
    <div class="registry-start-page-lead">
        <div class="registry-start-page-lead-image">
            <img src="<?php echo fth_img(array('width' => 1000, 'retina' => false)); ?>"
                 srcset="<?php echo fth_img(array('width' => 1000, 'retina' => false)); ?> 2400w,
                <?php echo fth_img(array('width' => 800, 'retina' => false)); ?> 1200w,
                <?php echo fth_img(array('width' => 600, 'retina' => false)); ?> 600w,
                <?php echo fth_img(array('width' => 400, 'retina' => false)); ?> 400w"
            sizes="(max-width: 1000px) 100vw, 1000px">
        </div>
        <a data-ev-loc="Start Page" data-ev-name="Start - Arrow" class="registry-start-page-lead-button" href="<?php echo $registryData['survey_collection']; ?>">
            <div class="registry-start-page-lead-button-arrow"></div>
        </a>
    </div>
    <div class="registry-start-page-content">
        <div class="registry-start-page-content-branded-image">
            <img src="<?php echo $registryData['branded_image']['url']; ?>">
        </div>
        <div class="registry-start-page-content-cta">
            <?php the_content(); ?>
            <div class="registry-start-page-content-cta-button">
                <a data-ev-loc="Start Page" data-ev-name="Start - CTA" href="<?php echo $registryData['survey_collection']; ?>">LET'S GET STARTED</a>
            </div>
        </div>
    </div>
</div>
<?php get_footer(); ?>
