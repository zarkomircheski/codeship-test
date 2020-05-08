<?php
$trending = $module->data['type'] === 'Trending';

$amp = '';
$tracking = 'Article Body';

if (is_amp_endpoint()) {
    $tracking = 'AMP';
    $amp = 'amp/';
}
?>

<div class="recirculation">
    <div class="recirculation-title "><?php echo $module->data['title'] ?></div>
    <div class="recirculation-content <?php if ($trending) {
        echo "recirculation-content-trending";
                                      } ?>">
        <?php if (!$trending) {
            $related_articles = new WP_Query([
                'posts_per_page' => 2,
                'post_status' => 'publish'
            ]); ?>

            <?php foreach ($related_articles->posts as $related) : ?>
                <div class="recirculation-content-item">
                        <div class="recirculation-content-item-image">
                            <?php
                            $alt = get_post_meta(
                                get_post_thumbnail_id($related->ID),
                                '_wp_attachment_image_alt',
                                true
                            ); ?>
                            <a href="<?php echo get_permalink($related->ID).$amp; ?>" data-ev-loc="<?php echo $tracking; ?>" data-ev-name="Recirculation - Image">

                                <?php if (!is_amp_endpoint()) {
                                    echo fth_img_tag(array(
                                        'width' => 60,
                                        'height' => 60,
                                        'alt' => $alt,
                                        'post_id' => $related->ID
                                    ));
                                } else {
                                    $img = fth_img(array(
                                        'width' => 60,
                                        'height' => 60,
                                        'post_id' => $related->ID
                                    ));
                                    echo '<amp-img  alt="related content" width="60"  height="60" src="'.$img.'">';
                                }?>
                            </a>
                        </div>
                        <div class="recirculation-content-item-title">
                            <a href="<?php echo get_permalink($related->ID).$amp; ?>"
                               data-ev-loc="<?php echo $tracking; ?>" data-ev-name="Recirculation - Headline">
                                <?php echo $related->post_title; ?>
                            </a>
                        </div>
                </div>
            <?php endforeach; ?>
        <?php } ?>
    </div>
</div>