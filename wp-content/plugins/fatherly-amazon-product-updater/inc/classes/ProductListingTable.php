<?php

namespace FatherlyPlugin\APU;

use FatherlyPlugin\APU\Lib\WP_List_Table;
use FatherlyPlugin\APU\AmazonHelper;

/**
 * Class for displaying Amazon products
 * in a WordPress-like Admin Table with pagination, search and filtering.
 */
class ProductListingTable extends WP_List_Table
{

    /**
     * @var $pluginTextDomain | string
     */
    protected $pluginTextDomain;

    /**
     * @var $data | string
     *
     * Holds the products for display in the table.
     */
    public $data;

    /**
     * ProductListingTable constructor.
     * @param $pluginTextDomain
     */
    public function __construct($pluginTextDomain)
    {
        global $status, $page;
        $this->pluginTextDomain = $pluginTextDomain;
        parent::__construct(array(
            'plural' => 'products',
            'singular' => 'product',
            'ajax' => 'false'
        ));
    }

    /**
     * column_default
     * This is called when `WP_List_Table` is displaying data for each column in a row. This tell the class where the
     * data for each column is as well as how it should be output in the table.
     * @param object $item
     * @param string $column_name
     * @return string|void
     */
    public function column_default($item, $column_name)
    {

        switch ($column_name) {
            case 'link':
                return sprintf("<a target='_blank' href='%s'>%s</a>", $item['product_link'], $item['product_title']);
            case 'image':
                return sprintf('<img width="150px" src="%s" alt="%s">', $item['product_image'], $item['product_title']);
            case 'created_at':
                return $item[$column_name];
            case 'updated_at':
                return $item[$column_name];
            case 'actions':
                return '<input type="submit" value="Update Product" class="updateProduct button-primary"/>';
            default:
                $key = 'product_' . $column_name;
                return $item[$key];
        }
    }

    /**
     * get_columns
     * This sets up the columns for our table
     * @return array
     */
    public function get_columns()
    {
        $columns = array(
            'asin' => "ASIN",
            'link' => "Link",
            'image' => "Image",
            'price' => "Price",
            'created_at' => "Created",
            'updated_at' => "Last Updated",
            'actions' => "Actions",
        );
        return $columns;
    }

    /**
     * no_items
     * This is the text that will be displayed on page in the event no products were returned e.g. on a search.
     */
    public function no_items()
    {
        _e('No Products Found');
    }

    /**
     * single_row
     * This overrides how `WP_List_Table` outputs the `<tr>` in the table to ensure we add our ASIN data attribute and
     * ID. This is what ensures our JS for updating products on page will continue to function.
     * @param object $item
     */
    public function single_row($item)
    {
        echo "<tr id='{$item['product_asin']}' data-asin='{$item['product_asin']}'>";
        $this->single_row_columns($item);
        echo '</tr>';
    }

    /**
     * get_sortable_columns
     * This method merely defines which columns should be sortable and makes them
     * clickable - it does not handle the actual sorting. We still have to detect
     * the ORDERBY and ORDER querystring variables within prepare_items() and sort
     * our data accordingly.
     * This should return an array where the
     * key is the column that needs to be sortable, and the value is the db column to
     * sort by.
     * @return array
     */
    public function get_sortable_columns()
    {
        $sortableColumns = array(
            'updated_at' => array('updated_at', true), //true means it's already sorted
            'created_at' => array('created_at', false),
            'link' => array('product_title', false),
        );
        return $sortableColumns;
    }

    /**
     * prepare_items
     * This method is where we prepare our data for display. This method detects orderby params as well as search params
     * and then parses them into arguments that are passed to the `getAdminPageData` method on the `AmazonHelper` class
     * to ensure we take the information into account when querying the products table.
     * This method will also set the amount of products that should be displayed per page and set up the pagination
     * arguments and ensure we return the appropriate subset of our data.
     */
    public function prepare_items()
    {
        // First, lets decide how many records per page to show
        $per_page = 50;
        /*
         * Now we need to define our column headers. This includes a complete
         * array of columns to be displayed (slugs & titles), a list of columns
         * to keep hidden, and a list of columns that are sortable.
         */
        $columns = $this->get_columns();
        $hidden = array();
        $sortable = $this->get_sortable_columns();
        /*
         * Finally, we build an array to be used by the class for column
         * headers. The `$this->_column_headers` property takes an array which contains
         * 3 other arrays. One for all columns, one for hidden columns, and one
         * for sortable columns.
         */
        $this->_column_headers = array($columns, $hidden, $sortable);
        /*
         * This checks for sorting and/or search input and sets up the params for our SQL query to obtain the data.
         */
        $orderby = $_REQUEST['orderby'] ?: null;
        $order = $_REQUEST['order'] ?: 'asc';
        if (isset($orderby)) {
            $orderParams = array('orderby' => $orderby, 'order' => $order);
            if (array_key_exists('s', $_REQUEST)) {
                $orderParams['s'] = $_REQUEST['s'];
            }
            $this->data = AmazonHelper::init()->getAdminPageData($orderParams);
        } else {
            if (array_key_exists('s', $_REQUEST)) {
                $orderParams['s'] = $_REQUEST['s'];
                $this->data = AmazonHelper::init()->getAdminPageData($orderParams);
            } else {
                $this->data = AmazonHelper::init()->getAdminPageData();
            }

        }
        /*
         * REQUIRED for pagination. figure out what page the user is currently
         * looking at. We'll need this later.
         */
        $current_page = $this->get_pagenum();
        /*
         * REQUIRED for pagination check how many items are in our data array
         */
        $total_items = count($this->data);
        /**
         * The `WP_List_Table` class does not handle pagination for us, so we need
         * to ensure that the data is trimmed to only the current page. Here we use
         * `array_slice()` for this purpose.
         */
        $this->data = array_slice($this->data, (($current_page - 1) * $per_page), $per_page);
        /**
         * REQUIRED. Now we can add our *sorted* data to the items property, where
         * it can be used by the rest of the class.
         */
        $this->items = $this->data;
        /**
         * REQUIRED. We also have to register our pagination options & calculations.
         */
        $this->set_pagination_args(array(
            'total_items' => $total_items,                  //WE have to calculate the total number of items
            'per_page' => $per_page,                     //WE have to determine how many items to show on a page
            'total_pages' => ceil($total_items / $per_page)   //WE have to calculate the total number of pages
        ));
    }
}
