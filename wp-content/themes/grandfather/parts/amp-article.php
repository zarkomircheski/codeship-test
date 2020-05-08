<?php
/**
 * Single view template.
 *
 * @package AMP
 */

/**
 * Context.
 *
 * @var AMP_Post_Template $this
 */

$this->load_parts(array( 'html-start' ));
?>
<?php
$this->load_parts(array( 'header' ));
$isListItem = $wp_query->query_vars['list_item'] ? true : false;
global $posts;
global $wp_query;
?>

    <article class="amp-wp-article">
        <amp-skimlinks layout="nodisplay" publisher-code="132532X1617500"></amp-skimlinks>
        <div class="in-article-ad" amp-access="NOT scroll.scroll">
            <div class="advertisement-text">ADVERTISEMENT</div>
            <?php
            Fatherly\Dfp\Helper::init(($is_list) ? false : null)->id('lead1_amp')->printLeaderAmpTag(false, false);
            ?>
        </div>
        <?php
        //Check to see if the user is coming in sideways on a list
        $list_item = $wp_query->query_vars['list_item'];
        if ($list_item) {
            $newListItem = \Fatherly\Listicle\Helper::getPublishedListItem($post, substr($list_item, -3));
            set_query_var('list_items', array($newListItem[0]));
            ob_start();
            get_template_part('parts/article-listicle-items');
            $single_list_item_content = ob_get_clean();
            $amp_single_list_item_content = new AMP_Content($single_list_item_content, amp_get_content_embed_handlers($post), amp_get_content_sanitizers($post));
            echo $amp_single_list_item_content->get_amp_content(); ?>
            <div class="list-load-more list-load-more-next" data-index="<?php echo $index; ?>">
                <a href="<?php echo get_permalink($post).'?amp' ?>">SEE FULL LIST</a>
            </div>
        <?php } else { ?>
            <?php $this->load_parts(array( 'featured-image' )); ?>
            <header class="amp-wp-article-header">
                <div class="amp-wp-article-header-category">
                    <?php echo fth_category_breadcrumbs(get_the_category()); ?>
                </div>
                <h1 class="amp-wp-article-header-title"><?php echo esc_html($this->get('post_title')); ?></h1>
                <h2 class="amp-wp-article-header-subheading"><?php echo get_field('custom_excerpt'); ?></h2>
                <div class="amp-wp-article-header-author">By<?php $this->load_parts(apply_filters('amp_post_article_header_meta', array( 'meta-author', 'meta-time' ))); ?>
                </div>
                <div class="amp-wp-article-header-date"><?php echo (fth_get_article_updated_date($post) && get_field('show_article_updated_date')) ? 'Updated ' . fth_get_article_updated_date($post) : get_the_time('M d Y, g:i A') ?></div>
            </header>

            <div class="amp-wp-article-content">
                <?php echo $this->get('post_amp_content'); // WPCS: XSS ok. Handled in AMP_Content::transform().
                if (\Fatherly\Listicle\Helper::isListicle($post)) {
                    $list_items = \Fatherly\Listicle\Helper::getPublishedListItems($post, true);
                    set_query_var('list_items', $list_items);
                    ob_start();
                    get_template_part('parts/article-listicle-items');
                    $list_item_content = ob_get_clean();
                    $amp_list_item_content = new AMP_Content($list_item_content, amp_get_content_embed_handlers($post), amp_get_content_sanitizers($post));
                    echo $amp_list_item_content->get_amp_content();
                }
                ?>

            </div>
        <?php } ?>
        <amp-sticky-ad layout="nodisplay" amp-access="NOT scroll.scroll">
            <?php
            $refresh = get_field('field_amp_refresh', 'options') &&
                       get_field('field_amp_lead2_refresh', 'options') ? get_field('field_amp_refresh', 'options') : false;
            Fatherly\Dfp\Helper::init()->id('lead2_amp')->printLeaderAmpTag($refresh, true);
            ?>
        </amp-sticky-ad>
        <footer class="amp-wp-article-footer">
            <?php if (get_field('not_safe_for_advertisers')) { ?>
            <amp-embed class="amp-wp-article-outbrain" width="100" height="100" type="outbrain" layout="responsive" data-widgetIds="AMP_1"></amp-embed>
            <?php } ?>
            <?php get_template_part('parts/article', 'related'); ?>
        </footer>
    </article>

<?php
$this->load_parts(array( 'html-end' ));
