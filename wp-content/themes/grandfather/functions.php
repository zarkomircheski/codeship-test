<?php
/*
 *  Author: Aaron Baker
 *  URL: fatherly.com
 */

require_once(get_template_directory() . '/inc/vendor/autoload.php');
require_once(get_template_directory() . '/functions/vip-caching.php');
require_once(get_template_directory() . '/functions/acl.php');
require_once(get_template_directory() . '/functions/actions.php');
require_once(get_template_directory() . '/functions/additional-user-meta.php');
require_once(get_template_directory() . '/functions/admin.php');
require_once(get_template_directory() . '/functions/admin/enqueue-scripts.php');
require_once(get_template_directory() . '/functions/ages-stages-meta.php');
require_once(get_template_directory() . '/functions/ages-stages-taxonomy.php');
require_once(get_template_directory() . '/functions/analytics.php');
require_once(get_template_directory() . '/functions/apple-news-feed.php');
require_once(get_template_directory() . '/functions/article.php');
require_once(get_template_directory() . '/functions/article-insertion.php');
require_once(get_template_directory() . '/functions/buy-button-shortcode.php');
require_once(get_template_directory() . '/functions/cleanup.php');
require_once(get_template_directory() . '/functions/connatix-shortcode.php');
require_once(get_template_directory() . '/functions/country-list.php');
require_once(get_template_directory() . '/functions/custom-dimensions.php');
require_once(get_template_directory() . '/functions/custom-image-sizes.php');
require_once(get_template_directory() . '/functions/custom-rewrites.php');
require_once(get_template_directory() . '/functions/data-collection.php');
require_once(get_template_directory() . '/functions/data-layer.php');
require_once(get_template_directory() . '/functions/disable-emojis.php');
require_once(get_template_directory() . '/functions/enqueue.php');
require_once(get_template_directory() . '/functions/fb-instant.php');
require_once(get_template_directory() . '/functions/fatherly-iq-admin.php');
require_once(get_template_directory() . '/functions/filters.php');
require_once(get_template_directory() . '/functions/google-amp.php');
require_once(get_template_directory() . '/functions/gulp-scripts.php');
require_once(get_template_directory() . '/functions/header-analytics.php');
require_once(get_template_directory() . '/functions/imgix.php');
require_once(get_template_directory() . '/functions/inline-recommendations.php');
require_once(get_template_directory() . '/functions/lazy-load-images.php');
require_once(get_template_directory() . '/functions/lists-sitemap.php');
require_once(get_template_directory() . '/functions/load-more.php');
require_once(get_template_directory() . '/functions/nav-menus.php');
require_once(get_template_directory() . '/functions/news-sitemap.php');
require_once(get_template_directory() . '/functions/newsletter-shortcode.php');
require_once(get_template_directory() . '/functions/page-data.php');
require_once(get_template_directory() . '/functions/pinterest.php');
require_once(get_template_directory() . '/functions/post-deduplication.php');
require_once(get_template_directory() . '/functions/post-validation.php');
require_once(get_template_directory() . '/functions/postup-calls.php');
require_once(get_template_directory() . '/functions/redirect.php');
require_once(get_template_directory() . '/functions/rss.php');
require_once(get_template_directory() . '/functions/sanitize-filenames.php');
require_once(get_template_directory() . '/functions/schema-org.php');
require_once(get_template_directory() . '/functions/seo.php');
require_once(get_template_directory() . '/functions/shortcodes.php');
require_once(get_template_directory() . '/functions/sidebar.php');
require_once(get_template_directory() . '/functions/sign-up-utm.php');
require_once(get_template_directory() . '/functions/theme-support.php');
require_once(get_template_directory() . '/functions/utilities.php');
require_once(get_template_directory() . '/functions/user-roles.php');
require_once(get_template_directory() . '/functions/yoast-exclusion-settings.php');
require_once(get_template_directory() . '/inc/acf/index.php');
require_once(get_template_directory() . '/inc/post_types/index.php');
require_once get_template_directory() . '/inc/src/Fatherly/Nav/Walker.php';


/**
 * Begin requires for function files associated with modules.
 */
require_once(get_template_directory() . '/functions/modules/more-from/ajax_load_more.php');
