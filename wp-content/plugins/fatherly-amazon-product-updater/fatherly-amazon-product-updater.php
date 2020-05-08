<?php
/**
 * Plugin Name:     Fatherly Amazon Product Updater
 * Plugin URI:      https://www.fatherly.com
 * Description:     Created during work for <pre>WEB-854</pre> this plugin takes will keep the information on our amazon products up to date
 * Author:          William Wilkerson
 * Author URI:      mailto:william.wilkerson@fatherly.com
 * Text Domain:     fth-amazon-product-updater
 * Version:         1.0
 *
 *
 * @package Fatherly_Amazon_Product_updater
 * @subpackage Main
 * @author William Wilkerson
 * @version 1.0
 */

/*
 * Loading in PSR-4 autoloader from composer.
 */
require_once(__DIR__ . '/inc/vendor/autoload.php');

/**
 * fatherly_apu_install
 *
 * This function fires on plugin activation and will create the database table that will contain the products from
 * Amazon. Also sets up the weekly cron event needed to update stale products.
 */
function fatherly_apu_install()
{
    global $wpdb;
    $table_name = $wpdb->prefix . 'amazon_products';
    $charset_collate = $wpdb->get_charset_collate();
    if ($wpdb->get_var("SHOW TABLES LIKE '$table_name'") != $table_name) {
        $sql = "CREATE TABLE `$table_name` (
                `id` BIGINT(20) NOT NULL AUTO_INCREMENT,
                `product_asin` char(15) NOT NULL,
                `product_link` varchar(255) NOT NULL,
                `product_title` varchar(255) NOT NULL,
                `product_image` varchar(255) NOT NULL,
                `product_description` text NULL,
                `product_button_text` varchar(255) DEFAULT 'Buy Now' NOT NULL,
                `product_price` varchar (255) NOT NULL,
                `created_at` datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
                `updated_at` datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
                PRIMARY KEY(`id`),
                UNIQUE KEY(`product_asin`)
            ) $charset_collate;";
        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($sql);
    }


    //Setup scheduled event for updating products weekly.
    fatherly_apu_manage_weekly_cron_state();
}

register_activation_hook(__FILE__, 'fatherly_apu_install');

/**
 * fatherly_apu_is_link_from_amazon
 * This is called when a product shortcode is being created. We check the URL to see if it's an Amazon URL and return
 * true or false.
 * @param $link
 * @return bool
 */
function fatherly_apu_is_link_from_amazon($link)
{
    $regex_pattern = '/(a\.co|amazon\.)/m';
    if (preg_match($regex_pattern, $link, $matches)) {
        return true;
    } else {
        return false;
    }
}

/**
 * fatherly_apu_handle_ajax
 * The entirety of our product shortcode interaction happen through AJAX. This is the function called by our JS to
 * perform operations relating to the product shortcode. This can handle adding new products as well as updating
 * existing products. This function makes calls to our `AmazonHelper` class and calls to other functions in this file to
 * carry out these duties.
 */
function fatherly_apu_handle_ajax()
{
    if ($_REQUEST['operation'] == 'add') {
        $product_url = urldecode($_REQUEST['url']);
        $results = array();
        if (fatherly_apu_is_link_from_amazon($product_url)) {
            $results['amazon'] = true;
            $amazon_product = \FatherlyPlugin\APU\AmazonHelper::init()->createNewProduct($product_url);
            if ($amazon_product['product_asin'] && $amazon_product['product_price']) {
                $results['product'] = $amazon_product;
                $results['success'] = true;
                $results['message'] = "Product added successfully";
            } elseif ($amazon_product['product_asin'] && !$amazon_product['product_price']) {
                $results['product'] = $amazon_product;
                $results['success'] = true;
                $results['message'] = "Product price not available through API currently.";
            } else {
                $results['product'] = $amazon_product;
                $results['success'] = false;
                $results['message'] = ($amazon_product['error_message']) ? $amazon_product['error_message'] : "The Amazon API is either unreachable or the URL is malformed. Please reach out to the dev team to tell them.";
            }
        } else {
            $results['amazon'] = false;
            $results['success'] = false;
            $results['message'] = "Product is not from Amazon";
        }
        echo wp_json_encode($results);
    }
    if ($_REQUEST['operation'] == 'update') {
        $results = array();
        $product_asin = $_REQUEST['asin'];
        $product = fatherly_apu_get_product_by_asin($product_asin);
        \FatherlyPlugin\APU\AmazonHelper::init()->maybeDispatchAmazonProductUpdateEvent($product);
        $results['success'] = true;
        $results['message'] = "Update task spawned successfully!";
        echo wp_json_encode($results);
    }
    if ($_REQUEST['operation'] == 'delete') {
        $results = array();
        $product_asin = $_REQUEST['asin'];
        fatherly_apu_delete_error_product($product_asin);
        $results['success'] = true;
        $results['message'] = "Delete task spawned successfully!";
        echo wp_json_encode($results);
    }
    if ($_REQUEST['operation'] == 'resolve') {
        $results = array();
        $product_asin = $_REQUEST['asin'];
        fatherly_apu_resolve_error_product($product_asin);
        $results['success'] = true;
        $results['message'] = "Resolve task spawned successfully!";
        echo wp_json_encode($results);
    }
    if ($_REQUEST['operation'] == 'fetchArticles') {
        $results = array();
        $product_asin = $_REQUEST['asin'];
        $articles = fatherly_fetch_articles_using_asin($product_asin);
        if (count($articles) > 0) {
            $results['success'] = true;
            $results['message'] = "Articles fetched Successfully";
            $results['markup'] = "<ul>";
            foreach ($articles as $article) {
                $permalink = get_the_permalink($article->id);
                $results['markup'] .= sprintf("<li><a target='_blank' href='%s'>%s</a>&nbsp;<span>(<strong>%s</strong>)</span></li>", $permalink, $article->post_title, $article->id);
            }
            $results['markup'] .= "</ul>";
        } else {
            $results['success'] = false;
            $results['message'] = "No articles found using this product.";
        }
        echo wp_json_encode($results);
    }
    wp_die();
}

add_action('wp_ajax_fatherly_amazon_product_updater', 'fatherly_apu_handle_ajax');


/**
 * fatherly_apu_get_product_by_asin
 * Makes a call to the `AmazonHelper` method `fetchProductFromDBByASIN` and returns the resulting rows data or false if
 * no row is found.
 * @param $asin
 * @return array|null|object|void
 */
function fatherly_apu_get_product_by_asin($asin)
{
    $product = \FatherlyPlugin\APU\AmazonHelper::init()->fetchProductFromDBByASIN($asin);
    if (!$product) {
        $product_data = \FatherlyPlugin\APU\AmazonHelper::init()->getProductInformationFromAmazonByASIN($asin)[0];
        if ($product_data['price']) {
            \FatherlyPlugin\APU\AmazonHelper::init()->insertAmazonProductIntoDB($product_data);
        }
        return \FatherlyPlugin\APU\AmazonHelper::init()->convertAmazonProductKeysToWP($product_data);
    } else {
        return $product;
    }
}

/**
 * fatherly_apu_add_weekly_cron_schedule
 * WordPress by default does not have a weekly cron schedule for events to use so this function creates that interval.
 * @param $schedules
 * @return mixed
 */
function fatherly_apu_add_weekly_cron_schedule($schedules)
{
    // add a 'weekly' schedule to the existing set
    $schedules['weekly'] = array(
        'interval' => 604800,
        'display' => __('Once Weekly')
    );
    return $schedules;
}

add_filter('cron_schedules', 'fatherly_apu_add_weekly_cron_schedule');

add_action('amazon_weekly_update', 'fatherly_apu_update_weekly');
/**
 * fatherly_apu_update_weekly
 * This is the function that is called when our weekly cron event kicks off. It makes a call to the `AmazonHelper` class
 * method `updateAmazonProducts` which performs the updating of all our stale products.
 */
function fatherly_apu_update_weekly()
{
    \FatherlyPlugin\APU\AmazonHelper::init()->updateAmazonProducts();
}

add_action('update_amazon_product', 'fatherly_apu_update_single_product', 10, 1);

/**
 * fatherly_apu_update_single_product
 * This is the function called to update a product when an admin clicks the `update` button for an individual production
 * the administration page for this plugin.
 * @param $product_data
 */
function fatherly_apu_update_single_product($product_data)
{
    \FatherlyPlugin\APU\AmazonHelper::init()->updateProductDataFromAmazon($product_data);
}

/**
 * fatherly_apu_delete_error_product
 * This is the function called to delete a product when an admin clicks the `delete` button for an individual product
 * on the product errors page for this plugin.
 * @param $product_data
 */
function fatherly_apu_delete_error_product($product_asin)
{
    \FatherlyPlugin\APU\AmazonHelper::init()->deleteAmazonProductFromDB($product_asin);
    $error_asins_option = get_option('fth_amazon_error_products');
    if (($key = array_search($product_asin, $error_asins_option)) !== false) {
        unset($error_asins_option[$key]);
    }
    update_option('fth_amazon_error_products', $error_asins_option);
}

/**
 * fatherly_apu_resolve_error_product
 * This is the function called to remove a product from the error product option when an admin clicks the \
 * `Mark as Fixed` button for an individual product on the product errors page for this plugin.
 * @param $product_data
 */
function fatherly_apu_resolve_error_product($product_asin)
{
    $error_asins_option = get_option('fth_amazon_error_products');
    if (($key = array_search($product_asin, $error_asins_option)) !== false) {
        unset($error_asins_option[$key]);
    }
    update_option('fth_amazon_error_products', $error_asins_option);
}

/**
 * fatherly_apu_register_admin_page
 * This sets up the admin page for the plugin under the `Tools` admin menu item.
 */
function fatherly_apu_register_admin_page()
{
    add_submenu_page(
        'tools.php',
        'Amazon Product Updater',
        'Amazon Product Updater',
        'view_fatherly_settings',
        'amazon-product-updater',
        'fatherly_apu_process_admin_page'
    );
    add_submenu_page(
        'tools.php',
        'Products With Errors',
        null,
        'view_fatherly_settings',
        'amazon-product-errors',
        'fatherly_apu_process_error_products_admin_page'
    );
}

add_action('admin_menu', 'fatherly_apu_register_admin_page');

/**
 * fatherly_apu_process_admin_page
 * This is called when the admin page is loaded. We create a new instance of our ProductListingTable class, prepare the
 * items and then setup the templates query var with that data and call the template file for displaying the table.
 */
function fatherly_apu_process_admin_page()
{
    $productsTable = new \FatherlyPlugin\APU\ProductListingTable('fth-amazon-product-updater');
    $productsTable->prepare_items();
    set_query_var('productsTable', $productsTable);
    include(__DIR__ . '/inc/pages/amazon-product-listing-table.php');
}

/**
 * fatherly_apu_process_error_products_admin_page
 * This is called when the product errors admin page is loaded. We fetch the data needed for the page and then load
 * the template for the page.
 */
function fatherly_apu_process_error_products_admin_page()
{
    $pageData = \FatherlyPlugin\APU\AmazonHelper::init()->getProductErrorsAdminPageData();
    include(__DIR__ . '/inc/pages/amazon-product-errors.php');
}

add_action('admin_enqueue_scripts', 'fatherly_apu_enqueue_admin_styles');

/**
 * fatherly_apu_enqueue_admin_styles
 * enqueues our CSS and JS on our admin page
 */
function fatherly_apu_enqueue_admin_styles()
{
    $current_screen = get_current_screen();
    if (strpos($current_screen->base, 'amazon-product-updater') === false && strpos($current_screen->base, 'amazon-product-errors') === false) {
        return;
    } else {
        wp_enqueue_style('fatherly_apu_main_style', plugins_url('inc/css/fapu-styles.css', __FILE__));
        wp_enqueue_script('fatherly_apu_main_script', plugins_url('inc/js/fapu-script.js', __FILE__));
    }
}

function fatherly_apu_manage_weekly_cron_state($updated_value = null)
{
    /*
     * @var $cron_queue_size
     * This is the total amount of cron jobs scheduled in WP. This includes jobs that aren't currently running. Most of
     * the jobs here are scheduled to run in the future but for queue size control that doesn't matter much. Our current
     * job count is around 11 so if that jumps past 30 then definitely don't want to add another job to the schedule.
     */
    $cron_queue_size = count(get_option('cron'));

    if (function_exists('get_field')) {
        $weekly_update_enabled = $updated_value ?? get_field('enable_weekly_update', 'option');
        if (!$weekly_update_enabled) {
            // This means the weekly update has been disabled so we need to remove the cron events for it.
            wp_clear_scheduled_hook('amazon_weekly_update');
        } else {
            if (!wp_next_scheduled('amazon_weekly_update')) {
                if ($weekly_update_enabled && $cron_queue_size < 30) {
                    wp_schedule_event(time(), 'weekly', 'amazon_weekly_update');
                }
            }
        }
    } else {
        //This means we don't have ACF so we can only check the queue size when considering adding the event
        if (!wp_next_scheduled('amazon_weekly_update')) {
            if ($cron_queue_size < 30) {
                wp_schedule_event(time(), 'weekly', 'amazon_weekly_update');
            }
        }
    }
}

add_action('fatherly_apu_enable_weekly_update_setting_update', 'fatherly_apu_manage_weekly_cron_state', 10, 1);

/**
 * fatherly_apu_manage_daily_cron_state
 *
 * This function operates differently from the weekly cron management function. Since we check the queue and disallow
 * the addition of new daily update events elsewhere all we need to do here is detect if the daily cron is being
 * turned off and if so then we need to delete any product update cron events that are already scheduled.
 * This function will be fired when daily update field is updated and it will pass the updated value to this function
 * @param string $daily_update_enabled
 */
function fatherly_apu_manage_daily_cron_state($daily_update_enabled)
{
    if (!$daily_update_enabled) {
        $crons = get_option('cron');
        $updated = array_filter($crons, function ($v) {
            return !array_key_exists("update_amazon_product", $v);
        });
        if (count($crons) !== count($updated)) {
            _set_cron_array($updated);
        }
    }
}

add_action('fatherly_apu_enable_daily_update_setting_update', 'fatherly_apu_manage_daily_cron_state', 10, 1);

/**
 * fatherly_fetch_articles_using_asin
 * will accept a single ASIN and return an array containing the ID and title of all posts that have that ASIN in their
 * post content
 * @param $asin
 * @return array|object|null
 */
function fatherly_fetch_articles_using_asin($asin)
{
    global $wpdb;
    $table = $wpdb->prefix . 'posts';
    return $wpdb->get_results("SELECT id, post_title from {$table} WHERE post_content LIKE '%{$asin}%' AND post_type = 'post'");
}
