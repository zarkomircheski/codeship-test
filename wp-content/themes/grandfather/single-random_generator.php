<?php
    get_header();

    $post_id = get_the_ID();
    $generator = $wpdb->prefix . 'generator';
    $generation = $wpdb->prefix . 'generation';

    // Get all generations and put then in an array
    $output = $wpdb->get_results("SELECT generation FROM `$generation` WHERE generator_post_id = $post_id");
    $generations = array();
foreach ($output as $row) {
    $generations[] = $row->generation;
}

    $generatorData = get_fields(get_the_id());
?>

<div class="random-generator" data-output='<?php echo json_encode($generations); ?>'>
    <div class="random-generator-image">
        <img src="<?php echo fth_img(array('width' => 1200, 'retina' => false)); ?>"
             srcset="<?php echo fth_img(array('width' => 2400, 'retina' => false)); ?> 2400w,
                                <?php echo fth_img(array('width' => 1200, 'retina' => false)); ?> 1200w,
                                <?php echo fth_img(array('width' => 600, 'retina' => false)); ?> 600w,
                                <?php echo fth_img(array('width' => 400, 'retina' => false)); ?> 400w"
             sizes="100vw">
    </div>
    <div class="random-generator-title">
        <?php the_title(); ?>
    </div>
    <div class="random-generator-dek">
        <?php the_content(); ?>
    </div>
    <div class="random-generator-output">
        <div class="random-generator-output-container">
        </div>

    </div>
    <div class="random-generator-button"><?php echo $generatorData['button_text'] ?></div>
</div>


<?php get_footer(); ?>