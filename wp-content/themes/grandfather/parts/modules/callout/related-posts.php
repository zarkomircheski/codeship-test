
<div class="related">
    <h2  class="related-title sub-title"><?php echo $module->data['title'] ?></h2>
    <div class="related-posts">
        <?php foreach ($module->data['add_related_post'] as $related) : ?>
            <div class="related-posts-post">
                    <div class="related-posts-post-image">
                        <?php
                        $alt = get_post_meta(
                            get_post_thumbnail_id($related['related_story']->ID),
                            '_wp_attachment_image_alt',
                            true
                        ); ?>
                        <a href="<?php echo get_permalink($related['related_story']->ID); ?>" data-ev-loc="Footer" data-ev-name="Category Module - Image">
                            <?php
                                echo fth_img_tag(array(
                                    'width' => 100,
                                    'height' => 100,
                                    'alt' => $alt,
                                    'post_id' => $related['related_story']->ID
                                ));
                            ?>
                        </a>
                    </div>
                    <div class="related-posts-post-details">
                        <?php
                        $articleCat = \Fatherly\Page\Helper::getPostCategory($related['related_story'], true);
                        echo sprintf("<a href='%s' class='related-posts-post-details-subcategory'>%s</a>", get_category_link($articleCat->term_id), $articleCat->name);?>
                        <a href="<?php echo get_permalink($related['related_story']->ID); ?>"
                           data-ev-loc="Footer" data-ev-name="Category Module - Headline"
                           class="related-posts-post-details-title"><?php echo $related['related_story']->post_title; ?></a>
                    </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>