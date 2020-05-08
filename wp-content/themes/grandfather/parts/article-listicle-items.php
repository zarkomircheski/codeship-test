<div class="list-items list-items-new">
    <?php foreach ($list_items as $list_item) : ?>
        <?php
        if ($list_item['module']) :
            echo do_shortcode($list_item['module']);
        else :
            ?>
        <div class="list-item <?php echo $list_item['item_type'];?>" >
            <div class="list-item-sentinel list-item-sentinel-top trigger" data-slug="<?php echo $list_item['item_slug']; ?>"></div>
            <div class="list-item-image">
                <?php if (!is_amp_endpoint()) : ?>
                    <div class="list-item-pin">
                        <a href="https://www.pinterest.com/pin/create/button/" data-pin-tall="true"
                           data-pin-round="true" data-pin-do="buttonPin"
                           data-pin-description="<?php echo $list_item['headline']; ?>"
                           data-pin-media="<?php echo fth_img(array('width' => 638, 'retina' => false, 'attachment_id' => $list_item['image']['ID'])); ?>"></a>
                    </div>
                <?php endif; ?>
                <?php if (!is_amp_endpoint()) { ?>
                    <img src="<?php echo fth_img(array('width' => 638, 'retina' => false,'attachment_id' =>$list_item['image']['ID'])); ?>"
                         srcset="<?php echo fth_img(array('width' => 638, 'retina' => true, 'attachment_id' =>$list_item['image']['ID'])); ?> 1200w,
                                <?php echo fth_img(array('width' => 800, 'retina' => false, 'attachment_id' =>$list_item['image']['ID'])); ?> 800w,
                                <?php echo fth_img(array('width' => 600, 'retina' => false, 'attachment_id' =>$list_item['image']['ID'])); ?> 600w,
                                <?php echo fth_img(array('width' => 400, 'retina' => false, 'attachment_id' =>$list_item['image']['ID'])); ?> 400w"
                         sizes="(max-width: 600px) 100vw, 638px">
                <?php } else { ?>
                <figure class="amp-wp-article-featured-image wp-caption">
                    <amp-img width="770" height="<?php echo round(($list_item['image']['height']/$list_item['image']['width'])*770); ?>" layout="responsive" class="amp-inbody-img"
                             src="<?php echo fth_img(array('width' => 638, 'retina' => false, 'attachment_id' => $list_item['image']['ID'])); ?>"
                             srcset="<?php echo fth_img(array('width' => 600, 'retina' => true, 'attachment_id' => $list_item['image']['ID'])); ?> 1200w,
                                <?php echo fth_img(array('width' => 760, 'retina' => false, 'attachment_id' => $list_item['image']['ID'])); ?> 800w,
                                <?php echo fth_img(array('width' => 560, 'retina' => false, 'attachment_id' => $list_item['image']['ID'])); ?> 600w,
                                <?php echo fth_img(array('width' => 360, 'retina' => false, 'attachment_id' => $list_item['image']['ID'])); ?> 400w"
                             sizes="(max-width: 600px) 90vw, 638px">
                    </amp-img>
                </figure>
                <?php } ?>
            </div>
            <div class="list-item-content">
                <div class="list-item-top">
                    <?php if (!is_amp_endpoint()) {
                        set_query_var('list_item', $list_item);
                        get_template_part('parts/modules/article-social-share-icons'); ?>
                    <?php } elseif ($list_item['item_number']) {
                        echo $list_item['item_number']; ?>
                    <?php } ?>
                    <h3 class="list-item-title"><a href="<?php echo $list_item['item_slug']?>" data-ev-loc="Body" data-ev-name="List Item Headline"
                        data-ev-val="<?php echo str_replace('"', "'", $list_item['headline']); ?>"><?php echo $list_item['headline']; ?></a></h3>
                </div>
                <div class="list-item-body">
                    <?php echo $list_item['body_copy']; ?>
                </div>
                <div class="list-item-sentinel list-item-sentinel-bottom trigger" data-slug="<?php echo str_replace('"', "'", $list_item['item_slug']); ?>"></div>
            </div>
        </div>
        <?php endif; ?>
    <?php endforeach; ?>
</div>
