<?php

/**
 * The admin area of the plugin to load the Product List Table
 */
$productsTable = get_query_var('productsTable');
?>

<div class="wrap">
    <h1><?php _e('Fatherly Amazon Product Updater', 'fth-amazon-product-updater'); ?>
        <button id="show_add_form" class="button-primary">Add New Product</button>
        <button class="fatherly-error-button button-primary"><a href="<?php echo admin_url('tools.php?page=amazon-product-errors'); ?>">View products with errors</a></button>
    </h1>
    <hr class="wp-header-end">
    <div id="add-new-form" class="add-new-product-form hidden">
        <h3>Please enter the URL for the Amazon product you would like to add below and then click
            "add"</h3>
        <form name="add_post_form" action="#">
            <label for="product_url">URL</label>
            <input name="product_url" id="product_url" type="text" required>
            <input class="button-primary" value="add" type="submit" name="add_new_product" id="add_new_product">
            <div id="add_form_spinner" class="hidden">
                <div class="spinner"></div>
            </div>
        </form>
    </div>
    <div id="fth-apu-wp-list-table">
        <div id="fth-apu-post-body">
            <form id="fth-apu-product-list-form" method="get">
                <input type="hidden" name="page" value="<?php echo $_REQUEST['page'] ?>"/>
                <?php
                $productsTable->search_box("Search", "fth-product-search");
                $productsTable->display();
                ?>
            </form>
        </div>
    </div>
</div>