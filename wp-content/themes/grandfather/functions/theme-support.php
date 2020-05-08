<?php

if (!isset($content_width)) {
    $content_width = 900;
}

if (function_exists('add_theme_support')) {
    add_theme_support('menus');
    add_theme_support('post-thumbnails');

    // Enables post and comment RSS feed links to head
    add_theme_support('automatic-feed-links');
    add_theme_support(
        'html5',
        array(
            'comment-list',
            'comment-form',
            'search-form',
        )
    );

    // Localisation Support
    load_theme_textdomain('html5blank', get_template_directory() . '/languages');
}

// Custom Excerpts
function html5wp_index($length) // Create 20 Word Callback for Index page Excerpts, call using html5wp_excerpt('html5wp_index');
{
    return 20;
}

// Create 40 Word Callback for Custom Post Excerpts, call using html5wp_excerpt('html5wp_custom_post');
function html5wp_custom_post($length)
{
    return 40;
}

// Create the Custom Excerpts callback
function html5wp_excerpt($length_callback = '', $more_callback = '')
{
    global $post;
    if (function_exists($length_callback)) {
        add_filter('excerpt_length', $length_callback);
    }
    if (function_exists($more_callback)) {
        add_filter('excerpt_more', $more_callback);
    }
    $output = get_the_excerpt();
    $output = apply_filters('wptexturize', $output);
    $output = apply_filters('convert_chars', $output);
    echo $output;
}

// Custom Comments Callback
function html5blankcomments($comment, $args, $depth)
{
    $GLOBALS['comment'] = $comment;
    extract($args, EXTR_SKIP);

    if ('div' == $args['style']) {
        $tag = 'div';
        $add_below = 'comment';
    } else {
        $tag = 'li';
        $add_below = 'div-comment';
    } ?>
        <!-- heads up: starting < for the html tag (li or div) in the next line: -->
        <<?php echo $tag ?>

        <?php comment_class(empty($args['has_children']) ? '' : 'parent') ?> id="comment-<?php comment_ID() ?>">
        <?php if ('div' != $args['style']) : ?>
        <div id="div-comment-<?php comment_ID() ?>" class="comment-body">
        <?php endif; ?>
        <div class="comment-author vcard">
            <?php
            if ($args['avatar_size'] != 0) {
                echo get_avatar($comment, $args['180']);
            }
            ?>
            <?php printf(__('<cite class="fn">%s</cite> <span class="says">says:</span>'), get_comment_author_link()) ?>
        </div>
        <?php if ($comment->comment_approved == '0') : ?>
        <em class="comment-awaiting-moderation"><?php _e('Your comment is awaiting moderation.') ?></em>
        <br/>
        <?php endif; ?>

        <div class="comment-meta commentmetadata"><a
                    href="<?php echo htmlspecialchars(get_comment_link($comment->comment_ID)) ?>">
            <?php
                printf(
                    __('%1$s at %2$s'),
                    get_comment_date(),
                    get_comment_time()
                ) ?></a><?php edit_comment_link(__('(Edit)'), '  ', ''); ?>
        </div>

        <?php comment_text() ?>

        <div class="reply">
            <?php comment_reply_link(array_merge(
                $args,
                array('add_below' => $add_below, 'depth' => $depth, 'max_depth' => $args['max_depth'])
            )) ?>
        </div>
        <?php if ('div' != $args['style']) : ?>
        </div>
        <?php endif; ?>
        <?php
}


// Shortcodes
// add_shortcode('html5_shortcode_demo', 'html5_shortcode_demo');
//You can place [html5_shortcode_demo] in Pages, Posts now.
// add_shortcode('html5_shortcode_demo_2', 'html5_shortcode_demo_2');
// Place [html5_shortcode_demo_2] in Pages, Posts now.

// Shortcodes above would be nested like this -
//[html5_shortcode_demo][html5_shortcode_demo_2]Here's the page title![/html5_shortcode_demo_2][/html5_shortcode_demo]

    /*------------------------------------*\
        Custom Post Types
    \*------------------------------------*/


    /*------------------------------------*\
        ShortCode Functions
    \*------------------------------------*/

// Shortcode Demo with Nested Capability
function html5_shortcode_demo($atts, $content = null)
{
    // do_shortcode allows for nested Shortcodes
    return '<div class="shortcode-demo">' . do_shortcode($content) . '</div>';
}

// Shortcode Demo with simple <h2> tag
function html5_shortcode_demo_2(
    $atts,
    $content = null
) { // Demo Heading H2 shortcode, allows for nesting within above element. Fully expandable.
    return '<h2>' . $content . '</h2>';
}

function get_top_category($categories)
{
    $show_cat = array(
        'show' => array(
            'category_name' => '',
            'category_slug' => '',
        ),
        'parent' => array(
            'category_name' => '',
            'category_slug' => '',
        ),
        'child' => array(
            'category_name' => '',
            'category_slug' => ''
        )
    );
    foreach ($categories as $category) {
        if ($category->parent == 0) {
            $show_cat['parent']['category_name'] = $category->name;
            $show_cat['parent']['category_slug'] = $category->slug;
        } else {
            $show_cat['child']['category_name'] = $category->name;
            $show_cat['child']['category_slug'] = $category->slug;
        }
    }
    if ($show_cat['child']['category_slug'] == '') {
        $show_cat['show']['category_name'] = $show_cat['parent']['category_name'];
        $show_cat['show']['category_slug'] = $show_cat['parent']['category_slug'];
    } else {
        $show_cat['show']['category_name'] = $show_cat['child']['category_name'];
        $show_cat['show']['category_slug'] = $show_cat['child']['category_slug'];
    }
    return $show_cat;
}

function the_sponsor_post_excerpt($elem_class = 'archive-post-horizontal')
{
    if (get_field('sponsored')) {
        $sponsor_image = get_field('sponsor_logo');
        $sponsor_size = 'sponsor_logo';
        if ($sponsor_image) :
            // TODO: create FIT option to be able to use 400x80 image size without cropping
            $sponsor_logo = "<a href=\""
                . get_field('sponsor_link') . "\" target=\"_blank\" class=\"" . $elem_class . "__sponsor-link\">"
                . fth_img(array(
                    'attachment_id' => $sponsor_image,
                    'width' => 400,
                    'tag' => true
                )) . "</a>";
        else :
            $sponsor_logo = '';
        endif;
        $output = "<div class=\"" . $elem_class . "__sponsor-info\"><div class=\"" . $elem_class . "__post-excerpt " . $elem_class . "__post-excerpt--sponsored\">Sponsored By</div>" . $sponsor_logo . "</div>";
    } else {
        $output = "<p class=\"" . $elem_class . "__post-excerpt\">" . get_field('sub_heading_custom') . "</p>";
    }

        echo $output;
}

function fth_get_user_role($user = null)
{
    $user = $user ? new WP_User($user) : wp_get_current_user();
    return $user->roles ? $user->roles[0] : false;
}

    add_action('get_header', 'remove_admin_login_header');
function remove_admin_login_header()
{
    remove_action('wp_head', '_admin_bar_bump_cb');
}
