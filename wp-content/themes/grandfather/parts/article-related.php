<div class="article__related <?php echo $recirc ? ' article__related-large': ''; ?>">
    <?php
    // get top level category (first in array without parent)
    $top_level_category;
    $categories = get_the_category();
    $next_post = get_query_var('next_post');
    foreach ($categories as $category) {
        if ($category->category_parent === 0) {
            $top_level_category = $category;
            break;
        }
    }

    if (!is_amp_endpoint()) {
        $break =  6;
        $amp = '';
    } else {
        $break = 13;
        $amp = 'amp/';
    }

    // if the above comes up empty, just use the first category in the array
    $top_level_category = (!$top_level_category) ? $categories[0] : $top_level_category;
    ?>
    <?php if ($top_level_category) : ?>
        <div class="related-articles">
            <div class="related-articles__inner">
                <div class="related-articles__header">More From <?php echo $top_level_category->name; ?></div>
                <?php
                $exclude = [get_the_ID()];
                if ($next_post) {
                    $exclude[] = $next_post->ID;
                }
                $related_articles = new WP_Query([
                    'posts_per_page' => 13,
                    'category__in' => $top_level_category->term_id,
                    'post__not_in' => $exclude,
                    'post_status' => 'publish'
                ]);
                $related_post_count = 1;
                $related_display_count = 0;
                ?>
                <?php foreach ($related_articles->posts as $related) : ?>
                    <div class="related-article">
                        <div class="related-article__image">
                            <?php
                            $alt = get_post_meta(
                                get_post_thumbnail_id($related->ID),
                                '_wp_attachment_image_alt',
                                true
                            ); ?>
                            <a href="<?php echo get_permalink($related->ID).$amp; ?>" data-ev-loc="Footer" data-ev-name="Category Module - Image">

                                <?php if (!is_amp_endpoint()) {
                                    $img = fth_img(array(
                                        'width' => 512,
                                        'height' => 288,
                                        'post_id' => $related->ID
                                    )); ?>
                                    <picture>
                                        <source media="(min-width: 601px)" srcset="<?php echo $img ?>">
                                        <img src="<?php echo $img ?>">
                                    </picture>
                                    <?php
                                } else {
                                    $img = fth_img(array(
                                        'width' => 512,
                                        'height' => 288,
                                        'post_id' => $related->ID
                                    ));
                                    echo '<amp-img alt="related content" layout="responsive" width="512" height="288" src="'.$img.'">';
                                }?>
                            </a>
                        </div>
                        <div class="related-article__details">
                            <?php
                            $articleCat = \Fatherly\Page\Helper::getPostCategory($related, true);
                            echo sprintf("<a href='%s' class='related-article__subcategory'>%s</a>", get_category_link($articleCat->term_id), $articleCat->name);?>
                            <a href="<?php echo get_permalink($related->ID).$amp; ?>"
                               data-ev-loc="Footer" data-ev-name="Category Module - Headline"
                               class="related-article__title"><?php echo $related->post_title; ?></a>
                        </div>
                    </div>
                    <?php if (is_amp_endpoint() && $related_post_count % 3 === 1) {
                        echo '<div class="related-article__ad">';
                        Fatherly\Dfp\Helper::init()->id('box'.floor($related_post_count/3).'_amp')->printAmpTag();
                        echo '</div>';
                    } ?>
                    <?php
                    // track how many related posts with images we've displayed
                    $related_display_count++;
                    $related_post_count++;
                    if ($related_display_count === $break) {
                        break;
                    }
                endforeach; ?>
            </div>
        </div>
    <?php endif; ?>
</div>
<div class="article__related-footer">
    <div class="article__related-logo"></div>
    <hr class="article__related-hr"/>
</div>
