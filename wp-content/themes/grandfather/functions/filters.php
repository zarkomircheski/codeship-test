<?php
// Add page slug to body class, love this - Credit: Starkers Wordpress Theme
function add_slug_to_body_class($classes)
{
    global $post;
    if (is_home()) {
        $key = array_search('blog', $classes);
        if ($key > -1) {
            unset($classes[$key]);
        }
    } elseif (is_page()) {
        $classes[] = sanitize_html_class($post->post_name);
    } elseif (is_singular()) {
        $classes[] = sanitize_html_class($post->post_name);
    }

    return $classes;
}

// Remove the <div> surrounding the dynamic navigation to cleanup markup
function my_wp_nav_menu_args($args = '')
{
    $args['container'] = false;
    return $args;
}

// Remove Injected classes, ID's and Page ID's from Navigation <li> items
function my_css_attributes_filter($var)
{
    return is_array($var) ? array() : '';
}

// Remove invalid rel attribute values in the categorylist
function remove_category_rel_from_category_list($thelist)
{
    return str_replace('rel="category tag"', 'rel="tag"', $thelist);
}

// Custom View Article link to Post
function html5_blank_view_article($more)
{
    global $post;
    return '... <a class="view-article" href="' . get_permalink($post->ID) . '">' . __('View Article', 'html5blank') . '</a>';
}

// Remove Admin bar
function remove_admin_bar()
{
    return false;
}

// Remove 'text/css' from our enqueued stylesheet
function html5_style_remove($tag)
{
    return preg_replace('~\s+type=["\'][^"\']++["\']~', '', $tag);
}

// only update the post modified date if post content has changed and the update is at least 60 seconds from the publish date
function fth_update_post_modified_date($data, $postarr)
{
    if ("publish" != $postarr['post_status']) {
        return $data;
    }
    $current_post = get_post($postarr['ID']);
    if (strtotime($current_post->post_modified) > (strtotime($current_post->post_date) + 60)) {
        if (strcmp($current_post->post_content, stripslashes_deep($data['post_content'])) != 0) {
            $content_updated_date = current_time('Y-m-d H:i:s');
            update_post_meta($postarr['ID'], 'content_updated_date', $content_updated_date);
        }
    }
    return $data;
}


function add_background_image_to_body_for_newsletter($content)
{
    $post_type = get_post_type();
    if ($post_type === "page") {
        $template = get_page_template();
        if (strpos($template, 'page-signup-confirmation') !== false) {
            $featured_img_url = get_the_post_thumbnail_url(get_the_ID(), 'full');
            $content = sprintf('style="background-image: url(\'%s\')"', esc_url($featured_img_url));
        }
    }
    return $content;
}

function module_insertion()
{
    \Fatherly\Article\Module\Insertion::init();
}


/**
 * Add the AMP Ad script to AMP pages.
 */
function insert_amp_scripts($data)
{
    $data['amp_component_scripts']['amp-ad'] = 'https://cdn.ampproject.org/v0/amp-ad-0.1.js';
    $data['amp_component_scripts']['amp-form'] = 'https://cdn.ampproject.org/v0/amp-form-0.1.js';
    $data['amp_component_scripts']['amp-analytics'] = 'https://cdn.ampproject.org/v0/amp-analytics-0.1.js';
    $data['amp_component_scripts']['amp-access'] = 'https://cdn.ampproject.org/v0/amp-access-0.1.js';
    $data['amp_component_scripts']['amp-access-scroll'] = 'https://cdn.ampproject.org/v0/amp-access-scroll-0.1.js';
    $data['amp_component_scripts']['amp-iframe'] = 'https://cdn.ampproject.org/v0/amp-iframe-0.1.js';
    $data['amp_component_scripts']['amp-sticky-ad'] = 'https://cdn.ampproject.org/v0/amp-sticky-ad-1.0.js';
    return $data;
}

function fth_change_permissions()
{
    return  'publish_posts';
}


function fth_deactivate_plugin()
{
    if (constant('ENV') !== 'prod' && is_plugin_active('publish-to-apple-news/apple-news.php')) {
        deactivate_plugins('publish-to-apple-news/apple-news.php');
    }
}

// Remove all HTML entities on paste except those listened below
function tinymce_paste_as_text($init)
{
    $init['plugins'] = $init['plugins'].',paste';
    // Below is a list of acceptable HTML entities that can be pasted
    // Entities to the left of a slash will replace all occurrences of the second entity
    $init['paste_word_valid_elements'] = "strong/b,em/i,h1,h2,h3,p,ol,ul,li,a[href],span";
    return $init;
}

function fth_update_apple_news_json($json, $id)
{
    if ($jw_lead = get_post_meta($id, 'jw_media_id', true)) {
        $json["components"]["0"] = [
            "role" => "video",
            "URL" => "https://content.jwplatform.com/manifests/".$jw_lead.".m3u8"
        ];
    } elseif ($youtube_lead = get_post_meta($id, 'youtube_url', true)) {
        parse_str(parse_url($youtube_lead, PHP_URL_QUERY), $urlParams);
        $embed = $urlParams['v'];

        $json["components"]["0"] = [
            "role" => "embedwebvideo",
            "aspectRatio" => 1.777,
            "URL" => "https://www.youtube.com/embed/$embed"
        ];
    }

    // Check if the content is a list. If it is a list add list content to the json payload sent to apple news
    global $post;
    $isList = \Fatherly\Listicle\Helper::isListicle($post);
    if ($isList) {
        // If no image was in the body this does not get added and this needs to be there for images to render
        $json['componentLayouts'][ "full-width-image"] = [
            "full-width-image" => [
                "margin" => [
                    "bottom" => 25,
                    "top" => 25
                ],
                "columnSpan" => 7,
                "columnStart" => 0
            ]
        ];


        // Get list items and add to json
        $list_items =  \Fatherly\Listicle\Helper::getPublishedListItems($post);
        foreach ($list_items as $list_item) {
            $headline = $list_item['item_number'] ? $list_item['item_number'] . ' ' . $list_item['headline'] : $list_item['headline'];
            array_push($json['components'][1]['components'], [
                "role" => "heading2",
                "text" => $headline,
                "format" => "markdown",
                "textStyle" => "default-heading-2",
                "layout" => "heading-layout"
            ]);
            array_push($json['components'][1]['components'], [
                'role' => 'photo',
                'URL' => fth_img(array('width' => 638, 'retina' => false, 'attachment_id' => $list_item['image']['ID'])),
                'layout' => "full-width-image"
            ]);
            array_push($json['components'][1]['components'], [
                'role' => 'body',
                'text' => $list_item['body_copy'],
                "format" => "html",
                "textStyle" => "default-body",
                "layout" => "body-layout"
            ]);
        }
    }

    return $json;
}

function filter_blank_lines($content, $html)
{
    return str_replace(html_entity_decode('<p>&nbsp;</p>'), '', html_entity_decode($html));
}

function fth_amp_custom_templates($file, $type, $post)
{
    if ('single' === $type) {
        $file = get_template_directory().'/parts/amp-article.php';
    }

    return $file;
}

function fth_author_link($link)
{
    $author_name = get_the_author();
    return str_replace('rel="author"', 'rel="author" data-ev-loc="Body" data-ev-name="Byline - Top" data-ev-val="'.$author_name.'"', $link);
}

function fth_use_correct_amp_url($pre, $post_id)
{
    global $post;
    global $wp_query;
    if (\Fatherly\Listicle\Helper::isListicle($post)) {
        $list_item = $wp_query->query_vars['list_item'];
        if ($list_item) {
            $newListItem = \Fatherly\Listicle\Helper::getPublishedListItem($post, substr($list_item, -3));
            return $newListItem[0]['item_slug'].'?amp';
        }
        return add_query_arg(amp_get_slug(), '', get_permalink($post_id));
    }
    return $pre;
}

/**
 * Stop editors, super editors and admins from logging in not using google
 */
function disable_login($user, $username)
{
    if (constant('ENV') !== 'dev' && $username !== '' && $username !== 'dev@fatherly.com' && is_array($user->roles) &&
        (in_array('super_editor', $user->roles) || in_array('editor', $user->roles) || in_array('administrator', $user->roles))) {
        return new WP_Error('disabled_account', 'Please login with Google');
    } else {
        return $user;
    }
}

function my_gal_set_login_cookie($dosetcookie)
{
    // Only set cookie on wp-login.php page
    return $GLOBALS['pagenow'] === 'wp-login.php';
}

function set_insertion_flag($content)
{
    return 'appleNewsExporting' . $content;
}

function create_update_generator($post_id)
{
    global $wpdb;

    // get text file and title
    $output = get_field('random_generator_output');
    $title = get_the_title($post_id);

    // Check to make sure you are on the right post type
    if (isset($output) && isset($title)) {
        //split output by line
        $rows = explode("\n", $output);
        $generator = $wpdb->prefix . 'generator';
        $generation = $wpdb->prefix . 'generation';

        // Check if this generator already exists
        $sqlGetId = "SELECT id FROM `$generator` WHERE post_id = $post_id";
        $id = $wpdb->get_results($sqlGetId);

        if ($id[0]->post_id === null) {
            //First insert new generator name and get the id associated with it
            $sqlTitle = "INSERT INTO `$generator` (post_id, generator_name) VALUES ('$post_id', '$title')";
            $wpdb->get_results($sqlTitle);
            $id = $wpdb->get_results($sqlGetId);
        }

        // Remove all current rows for this generator
        $sqlRemove = "DELETE FROM `$generation` WHERE generator_post_id =".$post_id;
        $wpdb->get_results($sqlRemove);

        //Insert each row fo the text file that is not empty into the generation db
        foreach ($rows as $row => $data) {
            if ($data !== '') {
                $sqlGeneration = "INSERT INTO `$generation` (generator_post_id, generation) VALUES (".$post_id.", '$data')";
                $wpdb->get_results($sqlGeneration);
            }
        }
    }
}

function reformat_instagram_embed($html, $url, $attr, $post_ID)
{

    if (is_feed() && strpos($url, 'instagram.com')!==false) {
        $startPos = strpos($html, 'data-instgrm-permalink="https://www.instagram.com/p/') + 52;
        $endPos = strpos($html, '/', $startPos);
        $gramID = substr($html, $startPos, $endPos - $startPos);

        return '<iframe width="320" height="320" frameBorder="0" src="https://www.instagram.com/p/'.$gramID.'/embed" frameborder="0"></iframe>';
    }

    return $html;
}


function kses_filter_allowed_html($allowed)
{
    $allowed['iframe'] = array (
        'align'       => true,
        'frameborder' => true,
        'height'      => true,
        'width'       => true,
        'sandbox'     => true,
        'seamless'    => true,
        'scrolling'   => true,
        'srcdoc'      => true,
        'src'         => true,
        'class'       => true,
        'id'          => true,
        'style'       => true,
        'border'      => true,
    );

    return $allowed;
}

function wrap_embed_with_div($html, $url, $attr)
{
    if (strpos($url, 'fatherly.') !== false) {
        $post_id = url_to_postid($url);
        global $wpdb;
        $html = '<div class="internal-embed">'.
                '<a href="'.$url.'">'.
                '<div class="internal-embed-link">'.
                '<h3>'.get_the_title($post_id).'</h3>'.
                '<div class="internal-embed-link-content">'.
                '<img src="'.fth_img(array('width' => 400, 'height' => 225, 'retina' => false, 'post_id' => $post_id)).'">'.
                '<p>'.get_field('custom_excerpt', $post_id).'</p>'.
                '</div><div class="internal-embed-continue"><a href="'.$url.'">Read More &rarr;</a></div>'.
                '</div></a></div>';
    }
    return $html;
}

add_filter('init', 'module_insertion');
add_filter('fatherly_body', 'add_background_image_to_body_for_newsletter');
// Add Filters
add_filter('body_class', 'add_slug_to_body_class'); // Add slug to body class (Starkers build)
add_filter('widget_text', 'do_shortcode'); // Allow shortcodes in Dynamic Sidebar
add_filter('widget_text', 'shortcode_unautop'); // Remove <p> tags in Dynamic Sidebars (better!)
add_filter('the_category', 'remove_category_rel_from_category_list'); // Remove invalid rel attribute
add_filter('the_excerpt', 'shortcode_unautop'); // Remove auto <p> tags in Excerpt (Manual Excerpts only)
add_filter('the_excerpt', 'do_shortcode'); // Allows Shortcodes to be executed in Excerpt (Manual Excerpts only)
add_filter('excerpt_more', 'html5_blank_view_article'); // Add 'View Article' button instead of [...] for Excerpts
add_filter('style_loader_tag', 'html5_style_remove'); // Remove 'text/css' from enqueued stylesheet
add_filter('wp_insert_post_data', 'fth_update_post_modified_date', '99', 2);
add_filter('amp_post_template_data', 'insert_amp_scripts');
add_action('admin_init', 'fth_deactivate_plugin');
add_filter('apple_news_publish_capability', 'fth_change_permissions');
add_filter('tiny_mce_before_init', 'tinymce_paste_as_text');
add_filter('apple_news_generate_json', 'fth_update_apple_news_json', 10, 2);
add_filter('apple_news_parse_html', 'filter_blank_lines', 10, 2);
add_filter('amp_post_template_file', 'fth_amp_custom_templates', 10, 3);
add_filter('the_author_posts_link', 'fth_author_link');
add_filter('amp_pre_get_permalink', 'fth_use_correct_amp_url', 10, 2);
add_filter('authenticate', 'disable_login', 100, 2);
add_filter('gal_set_login_cookie', 'my_gal_set_login_cookie');
add_filter('apple_news_exporter_content_pre', 'set_insertion_flag');
add_action('acf/save_post', 'create_update_generator', 20);
add_filter('embed_oembed_html', 'reformat_instagram_embed', 10, 4);
add_filter('wp_kses_allowed_html', "kses_filter_allowed_html");
add_filter('embed_oembed_html', 'wrap_embed_with_div', 10, 3);
// Remove Filters
remove_filter('the_excerpt', 'wpautop'); // Remove <p> tags from Excerpt altogether
