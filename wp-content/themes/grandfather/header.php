<!doctype html>
<html <?php language_attributes(); ?> class="no-js">
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <title><?php echo wp_get_document_title(); ?></title>
    <?php if (constant('ENV') != 'prod') : ?>
        <meta name="robots" content="noindex" />
    <?php endif; ?>
    <link href="//www.google-analytics.com" rel="dns-prefetch">
    <?php if (Fatherly\Config\FatherlyConfig::config()->exist('fb_app_id')) : ?>
        <meta property="fb:app_id" content="<?php echo Fatherly\Config\FatherlyConfig::config()->get('fb_app_id'); ?>"/>
    <?php endif; ?>
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no"/>
    <?php do_action('header_analytics'); ?>
    <?php Fatherly\Dfp\Helper::init(); ?>
    <?php wp_head(); ?>
    <?php
    if (isset($listicle_next) || isset($listicle_prev)) { ?>
        <script>history.scrollRestoration = "manual"</script>
        <?php $post_url = get_permalink($post->ID);
        echo (isset($listicle_previous)) ? sprintf(
            '<link rel="prev" href="%s">' . "\n",
            $post_url . $listicle_previous
        ) : null;
        echo (isset($listicle_next)) ? sprintf('<link rel="next" href="%s">' . "\n", $post_url . $listicle_next) : null;
    }
    ?>
    <?php include(get_template_directory() . '/parts/header-icons.php'); ?>
    <?php do_action('fb_instant'); ?>
    <?php do_action('schema_org'); ?>
    <?php include_once('parts/amzn-bidding.php'); ?>
    <?php include_once('parts/scroll-ad-blocking.php'); ?>
    <script async src="https://securepubads.g.doubleclick.net/tag/js/gpt.js"></script>
    <script async src="https://static.scroll.com/js/scroll.js"></script>

    <script async type="text/javascript" src="https://s.skimresources.com/js/132532X1617500.skimlinks.js"></script>
    <script> var envConfig   =  <?php echo wp_json_encode(Fatherly\Config\FatherlyConfig::config()->getEnvVariables());?>;</script>
    <script> var parselySecret = <?php echo (defined('PARSELY_SECRET_KEY')) ? constant('PARSELY_SECRET_KEY') : 'false'; ?>;</script>
    <?php if ($pinterest_image = get_post_meta(get_the_ID(), '_pinterest_img', true)) : ?>
        <?php if ($pinterest_image_url = get_field($pinterest_image)) : ?>
            <meta property="og:image" content="<?php echo $pinterest_image_url; ?>"/>
        <?php endif; ?>
    <?php elseif ($lead_image = get_the_post_thumbnail_url()) : ?>
        <meta property="og:image" content="<?php echo $lead_image; ?>"/>
    <?php endif; ?>
    <?php if ($description = get_field('custom_excerpt')) : ?>
        <meta name='parsely-metadata' content='{"description" : "<?php echo htmlspecialchars($description, ENT_QUOTES); ?>"}'>
    <?php endif; ?>
    <?php if (is_single()) : ?>
        <?php Fatherly\Dfp\Helper::init()->id('oop1')->printTag(); ?>
        <meta property="og:updated_time" content="<?php echo (fth_get_article_updated_date($post)) ? fth_get_article_updated_date($post, DATE_W3C) : get_the_date(DATE_W3C); ?>"/>
    <?php endif; ?>
    <script>
      !function(n){if(!window.cnx){window.cnx={},window.cnx.cmd=[];var t=n.createElement('iframe');t.display='none',t.onload=function(){var n=t.contentWindow.document,c=n.createElement('script');c.src='//cd.connatix.com/connatix.player.js',c.setAttribute('async','1'),c.setAttribute('type','text/javascript'),n.body.appendChild(c)},n.head.appendChild(t)}}(document);
    </script>
</head>
<body <?php
    $user_role = fth_get_user_role();
    $utm_info = fth_get_utm_class();
    $collection = get_query_var('collection');
    $display_name = '';
    $headerBodyClass = '';
if (isset($collection->modules)) {
    foreach ($collection->modules as $module) {
        if ($module->variant === 'article_nav') {
            $display_name = ' has-collection-push-down';
            break;
        }
    }
}

$pinned_urls = [];
if ($pinned_items = get_field('field_infinite_pinned', 'options')) {
    foreach ($pinned_items as $pinned) {
        array_push($pinned_urls, '"' . get_permalink($pinned->ID) . '"');
    }
}

body_class($user_role . $utm_info . $display_name); ?> data-current-article="<?php the_ID(); ?>"
                                                       data-pagetracking='<?php set_ga_tags_on_page($post); ?>' <?php echo apply_filters(
                                                           'fatherly_body',
                                                           null
                                                       ); ?>
                                                       data-pinned='<?php echo implode(',', $pinned_urls) ?>'
                                                       data-lead1='<?php echo get_field('field_lead1_refresh', 'options')?>'
                                                       data-lead2='<?php echo get_field('field_lead2_refresh', 'options')?>'
                                                       data-box3='<?php echo get_field('field_box3_refresh', 'options')?>'
                                                       data-desktop='<?php echo get_field('field_desktop_refresh', 'options')?>'
                                                       data-mobile='<?php echo get_field('field_mobile_refresh', 'options')?>'>
<!-- Google Tag Manager (noscript) -->
<noscript>
    <iframe src="https://www.googletagmanager.com/ns.html?id=<?php echo Fatherly\Config\FatherlyConfig::config()->get('gtm_container_id'); ?>"
            height="0" width="0"
            style="display:none;visibility:hidden"></iframe>
</noscript>

<!-- TODO update to work on index page when transitioning new HP -->
<?php if (!\Fatherly\Page\Helper::isHomepageTemplate()) {
    get_template_part('parts/modules/nav-2017');
} ?>
<div id="content">
    <!-- /header -->
