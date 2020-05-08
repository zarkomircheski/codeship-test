<?php
$gear = (has_category('gear')) ? 'gear-post' : '';
if ($franchise = \Fatherly\Article\ArticleHelper::articleHasFranchiseTag($post)) {
    set_query_var('franchise_tag', $franchise);
    $featured = true;
} else {
    $featured = (strpos(get_field('article_type'), 'Feature') === false) ? false : true;
}
$featuredCat = $featured ? 'article__featured' : '';
$data_categories = get_query_var('data_categories');
$add_classes = get_query_var('add_classes');
$share_url = get_query_var('share_url');
$rail_template = get_query_var('rail_template');
$pin_desc = (get_field('pinterest_description')) ? get_field('pinterest_description') : get_the_title() . ' - ' . get_post_meta(
    get_the_ID(),
    '_yoast_wpseo_metadesc',
    true
);
$pin_img = (get_field('pinterest_img')) ? get_field('pinterest_img') : get_the_post_thumbnail_url(
    get_the_ID(),
    'full'
);
$post_thumbnail = get_post(get_post_thumbnail_id());
$youtube = get_field('youtube_url') ? get_field('youtube_url') : false;
// Check if post is a list type
$isList = \Fatherly\Listicle\Helper::isListicle($post);
// Check if the user came in sideway on an item
global $wp_query;
$isListItem = array_key_exists('list_item', $wp_query->query_vars) ? true : false;
// Add class list so it can be styled properly
$list = $isList ? 'list' : '';
?>

<article data-tracking='<?php set_ga_tags_on_article($post); ?>'
         id="<?php the_ID(); ?>" <?php post_class(['article-post', $add_classes, $gear, $list, $featuredCat]) ?>
         data-read-more="<?php echo $readmore; ?>"
         data-url="<?php echo $share_url; ?>"
         data-title="<?php echo the_title(); ?>"
         data-categories='<?php echo json_encode($data_categories); ?>'
         data-published-time='<?php echo the_date("Y-m-d H:i:s"); ?>'
         data-stuck="false">
    <?php get_template_part('parts/leaderboard-ads'); ?>
    <?php if (has_post_thumbnail($post)) {
        $has_caption = ($post_thumbnail->post_excerpt != '') ? true : false;

        if (($featured == true || $isList == true) && !$isListItem) {
        // cap max-width with inline style so we can keep caption left aligned with image
            $image_data = wp_get_attachment_metadata(get_post_thumbnail_id($post));
            ?>
    <div class="feature__hero<?php echo ($has_caption) ? ' feature__hero--caption' : null; ?>">
        <div class="feature__hero-image">
            <img src="<?php echo fth_img(array('width' => 1200, 'retina' => false)); ?>"
                 srcset="<?php echo fth_img(array('width' => 2400, 'retina' => false)); ?> 2400w,
                                <?php echo fth_img(array('width' => 1200, 'retina' => false)); ?> 1200w,
                                <?php echo fth_img(array('width' => 600, 'retina' => false)); ?> 600w,
                                <?php echo fth_img(array('width' => 400, 'retina' => false)); ?> 400w"
                 sizes="100vw">
        </div>
        <div class="article__hero-caption article__hero-caption-feature"><?php echo $post_thumbnail->post_excerpt; ?></div>
        <?php } ?>
    <?php } ?>

        <div class="article__inner">
            <div class="article-post__main-content article-post__main-content--read-more">
                <?php if (!$isListItem) { ?>
                <div class="article__header">
                    <div class="article__header-intro">
                        <?php if (get_field('brand_text')) : ?>
                            <div class="brand-logo">
                                <div class="brand-logo__logo">
                                    <img src="<?php echo fth_get_protocol_relative_template_directory_uri(); ?>/images/fatherly-icon-white.svg"
                                         alt="fatherly logo">
                                </div>
                                <div class="brand-logo__text"><?php echo get_field('brand_text'); ?></div>
                            </div>
                        <?php endif; ?>

                        <?php echo fth_category_breadcrumbs(get_the_category()); ?>
                        <h1 class="article__title"><?php the_title(); ?></h1>
                        <?php if (get_field('sponsored') && get_field('sponsor_logo')) : ?>
                            <div class="sponsored">
                                <div class="sponsored__logo">Sponsored by <?php if (get_field('show_sponsor_name')) :
                                                                                echo get_field('sponsor_name');
                                                                          endif; ?><br/>
                                    <a href="<?php echo get_field('sponsor_link'); ?>" data-ev-loc="Body" data-ev-name="Sponsor Logo" data-ev-val="<?php echo str_replace('"', "'", get_field('sponsor_link')); ?>">
                                        <?php echo wp_get_attachment_image(
                                            get_field('sponsor_logo'),
                                            'sponsor_logo'
                                        ); ?>
                                    </a>
                                    <?php
                                    if ($impTracker = get_field('sponsored_post_impression_tracker')) {
                                        echo $impTracker;
                                    }
                                    ?>
                                </div>
                            </div>
                        <?php endif; ?>

                        <?php if (get_field('custom_excerpt')) : ?>
                            <h2 class="article__subheading"><?php echo get_field('custom_excerpt'); ?></h2>
                        <?php endif; ?>

                        <div class="article__author">By <?php the_author_posts_link(); ?></div>
                        <div class="article__date"><?php echo (fth_get_article_updated_date($post) && get_field('show_article_updated_date')) ? 'Updated ' . fth_get_article_updated_date($post) : get_the_time('M d Y, g:i A') ?></div>
                        <?php ($franchise) ? get_template_part('parts/article', 'franchise-tag-link') : null; ?>
                        <?php set_query_var('share_icon_loc', 'Top');
                        get_template_part('parts/modules/article-social-share-icons'); ?>
                    </div>
                    <?php if (has_post_thumbnail() && !$featured && !$youtube && !$isList) : ?>
                        <div class="article__hero article__hero--image">
                            <div class="article__hero-pin" data-ev-loc="Body"
                                 data-ev-name="Featured Image - Pinterest">
                                <a href="https://www.pinterest.com/pin/create/button/" data-pin-tall="true"
                                   data-pin-round="true" data-pin-do="buttonPin"
                                   data-pin-description="<?php echo $pin_desc; ?>"
                                   data-pin-media="<?php echo $pin_img; ?>"></a>
                            </div>
                            <img src="<?php echo fth_img(array('width' => 600, 'retina' => false)); ?>"
                                 srcset="<?php echo fth_img(array('width' => 600, 'retina' => true)); ?> 1200w,
                            <?php echo fth_img(array('width' => 800, 'retina' => false)); ?> 800w,
                            <?php echo fth_img(array('width' => 600, 'retina' => false)); ?> 600w,
                            <?php echo fth_img(array('width' => 400, 'retina' => false)); ?> 400w"
                                 sizes="(max-width: 600px) 100vw, 600px">

                            <?php if ($post_thumbnail->post_excerpt != '') : ?>
                                <div class="article__hero-caption">
                                    <?php echo $post_thumbnail->post_excerpt; ?>
                                </div>
                            <?php endif; ?>
                        </div>

                    <?php elseif ($youtube) : ?>
                        <div class="article-video-lead article-video-lead-youtube">
                            <?php
                            set_query_var('youtubeURL', $youtube);
                            get_template_part('parts/modules/article-youtube');
                            set_query_var('youtubeURL', null);
                            ?>
                        </div>
                    <?php endif; ?>
                </div>
                    <?php $dropcap = (get_field('disable_drop_cap')) ? '' : 'article__content--dropcap'; ?>

                <div class="article__body">
                <div class="article__content <?php echo $dropcap; ?>">
                    <?php the_content(); ?>
                <?php } ?>
                    <?php
                    if ($isList) {
                        $index = 0;
                        $next = '';
                        $prev = '';
                        $loadPrev = '';
                        if ($isListItem) { ?>
                    <div class="article__content <?php echo $dropcap; ?>">
                            <?php
                            $newListItem = \Fatherly\Listicle\Helper::getPublishedListItem($post, substr($list_item, -3));
                            $index = $newListItem[0]['index'] + 1;

                            // Get pagination slugs
                            if ($newListItem[2] !== null) {
                                $next = $newListItem[2]['item_url'];
                                $prev = $newListItem[1]['item_url'];
                                $loadPrev = 'list-load-more-prev';
                            } elseif ($newListItem[0]['index'] === 0 && $newListItem[1] !== null) {
                                $next = $newListItem[1]['item_url'];
                            } elseif ($newListItem[1] !== null) {
                                $prev = $newListItem[1]['item_url'];
                                $loadPrev = 'list-load-more-prev';
                            }

                            //Link back to the top of the list
                            ?>
                        <div class="list-load-more"
                             data-parent-url="<?php echo get_permalink(); ?>">
                            <a data-ev-loc="Body" data-ev-name="Back to Top" href="<?php echo $share_url; ?>">BACK TO THE TOP</a>
                        </div>
                            <?php
                            //Load previous button
                            if ($prev !== '') { ?>
                            <div class="list-load-more<?php echo ' ' . $loadPrev; ?>" data-index="<?php echo $index - 1; ?>">
                                <a data-ev-loc="Body" data-ev-name="Load Previous" href="<?php echo $prev ?>">LOAD PREVIOUS</a>
                            </div>
                            <?php }

                            set_query_var('list_items', array($newListItem[0]));
                            get_template_part('parts/article-listicle-items');
                        } else {
                            // If coming into the gallery from the top get the first list item for the load more button
                            $nextListItem = \Fatherly\Listicle\Helper::getPublishedListItemsPaged($post, 1);
                            if ($nextListItem[0]['item_url'] !== null) {
                                $next = $nextListItem[0]['item_url'];
                            }
                        }

                        ?>
                        <div class="list-body"></div>
                        <?php if ($next !== '') { ?>
                            <div class="list-load-more list-load-more-next" data-index="<?php echo $index; ?>">
                                <a href="<?php echo $next ?>" data-ev-loc="Body" data-ev-name="Load More">LOAD MORE</a>
                            </div>
                        <?php }
                    } ?>
                    <div class="nativo-ad nativo-ad-bottom">
                        <?php Fatherly\Dfp\Helper::init()->id('list_native3')->printTag(); ?>
                    </div>
                    <div class="article__content-footer">
                        <?php $default = get_field('default_newsletter_signup', 'option');?>
                        <div class="article__tags">
                            <?php
                                $tags = get_the_tags();
                            if ($tags) :
                                foreach ($tags as $tag) : ?>
                                    <a class="article__tag"
                                       href="<?php echo get_term_link($tag->term_id); ?>"><?php echo $tag->name; ?></a>
                                <?php endforeach;
                            endif; ?>
                        </div>
                    </div>
                </div>
                <?php if (!$isList) { ?>
                    <div class="article__body-rail">
                        <?php if (!(get_field('sponsored') && get_field('sponsor_logo'))) { ?>
                            <div class="article__body-rail-ad">
                                <div class="article__rail-ad-txt advertisement-text">ADVERTISEMENT</div>
                                <?php Fatherly\Dfp\Helper::init()->id('box1')->printTag(); ?>
                            </div>
                        <?php } ?>
                        <div class="article__body-rail-trending"></div>
                        <div class="article__body-rail-ad article__body-rail-ad-sticky">
                            <div class="article__rail-ad-txt advertisement-text">ADVERTISEMENT</div>
                            <?php Fatherly\Dfp\Helper::init()->id('box3')->printTag(); ?>
                        </div>
                    </div>
                <?php } ?>
                </div>
                </div>
                <div class="article__footer-recirculation">
                    <?php fth_outbrain(get_the_ID(), get_query_var('first_article')); ?>
                    <?php get_template_part('parts/article', 'related'); ?>
                </div>
</article>
