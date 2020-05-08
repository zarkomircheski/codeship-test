<?php
function infscrl_click($area = '', $url = '')
{
    if (isset($GLOBALS['infscr']) && $GLOBALS['infscr'] == true) {
        echo sprintf('onclick=dataLayer.push({"event","Infinite Scroll Click","%s","%s"})', $area, $url);
    }
}

function fth_load_more_js()
{
    global $wp_query;
    $args = array(
        'url' => str_replace(['http:','https:'], '', admin_url('admin-ajax.php')),
        'query' => $wp_query->query,
        'post_id' => $wp_query->post->ID
    );

    wp_localize_script('fatherly-js', 'fthloadmore', $args);
}

add_action('wp_enqueue_scripts', 'fth_load_more_js');


function fth_ajax_load_more()
{
    header_remove('cache-control');
    header_remove('expires');

    if (isset($_GET['tax']) && ($_GET['tax'] == 'single' || $_GET['tax'] == 'selected')) :
        $GLOBALS['infscr'] = true;
        get_template_part('parts/next-article');
    elseif (isset($_GET['tax']) &&  $_GET['tax'] == 'list') :
        get_template_part('parts/load-listicle');
    else :
            $query_json = stripslashes($_GET['query']);
        $query_obj = json_decode($query_json, true);
        if ($query_obj['pagename'] === 'news') :
            $news_page_fields = get_fields($_GET['post_id']);
            $news_page_fields['paged'] = $_GET['page'];
            ($_GET['excluded_posts'] ? $news_page_fields['excluded_posts'] = $_GET['excluded_posts'] : null);
            $loop = Fatherly\Page\Helper::fetchPostsForLandingPage($news_page_fields);
        else :
                    $args = isset($_GET['query']) ? $query_obj : array();
            $args['post_type'] = isset($args['post_type']) ? $args['post_type'] : 'post';
            $args['paged'] = $_GET['page'];
            $args['post_status'] = 'publish';
            if ($_GET['tax'] == 'home') {
                $args['meta_key'] = 'visibility_on_home_page';
                $args['meta_value'] = 'show';
            }
            $loop = new WP_Query($args);
        endif;
        ob_start();
        if ($loop->have_posts()) :
            while ($loop->have_posts()) :
                $loop->the_post();
                get_template_part('parts/loop', 'category'); ?>

            <?php endwhile;
        endif;
        wp_reset_postdata();
        $data = ob_get_clean();
        wp_send_json_success($data);
    endif;
        exit;
}

add_action('wp_ajax_fth_ajax_load_more', 'fth_ajax_load_more');
add_action('wp_ajax_nopriv_fth_ajax_load_more', 'fth_ajax_load_more');
