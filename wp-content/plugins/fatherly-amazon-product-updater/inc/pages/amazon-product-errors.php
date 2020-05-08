<div class="wrap">
    <div id="icon-tools" class="icon32"></div>
    <h1>Amazon Products With Errors</h1>
    <span class="product-num"><?php echo number_format(count($pageData)); ?> product(s)</span>
    <hr class="wp-header-end">
    <table class="wp-list-table widefat fixed striped">
        <thead>
        <tr>
            <th class="column-title">ASIN</th>
            <th class="column-title">Link</th>
            <th class="column-title">Articles using product</th>
            <th class="column-title">Actions</th>
        </tr>
        </thead>
        <tbody>
        <?php if ($pageData) :
            foreach ($pageData as $asin => $product) : ?>
                <?php $PData = $product['product_data']; ?>
                <tr id="<?php echo $asin; ?>" data-asin="<?php echo $asin; ?>">
                    <td class="product-asin"><?php echo $asin; ?></td>
                    <td class="product-name"><?php echo sprintf("<a target='_blank' href='%s'>%s</a>", $PData['product_link'],
                            $PData['product_title']); ?></td>
                    <td class="articles-with-product">

                        <input type="submit" value="Fetch Articles"
                               class="fetchArticles fatherly-admin-button button-secondary"/>
                    </td>
                    <td id="actions">
                        <input type="submit" value="Mark as Fixed"
                               class="markResolved fatherly-admin-button button-primary"/>
                        <input type="submit" value="Delete Product"
                               class="deleteProduct fatherly-error-button button-primary"/>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php else : ?>
            <tr>
                <td><h3>No Products are currently invalid</h3></td>
            </tr>
        <?php endif; ?>

        </tbody>
    </table>
</div>