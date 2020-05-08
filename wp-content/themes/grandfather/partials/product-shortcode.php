<?php
setlocale(LC_MONETARY, 'en_US.UTF-8');
$product_data['price'] = str_replace(array("$",","), '', $product_data['price']);
?>
<div class="product-shortcode">
    <div class="product-image">
        <a data-ev-loc="Body" data-ev-name="Commerce - Image" data-ev-val="<?php echo $product_data['link']; ?>" target="_blank" href="<?php echo $product_data['link']; ?>">
            <img src="<?php echo $product_data['image_url']; ?>" alt="">
        </a>
    </div>
    <div class="product-title">
        <h3><a data-ev-loc="Body" data-ev-name="Commerce - Headline" data-ev-val="<?php echo $product_data['link']; ?>" target="_blank" href="<?php echo $product_data['link']; ?>"><?php echo $product_data['title']; ?></a></h3>
    </div>
    <div class="product-description">
        <p><?php echo $product_data['description']; ?></p>
    </div>
    <div class="product-buy__btn">
        <a data-ev-loc="Body" data-ev-name="Commerce - Button" data-ev-val="<?php echo $product_data['link']; ?>" target="_blank" href="<?php echo $product_data['link']; ?>"><?php echo $product_data['btn_text'] . ' ' . ((int)$product_data['price'] !== 0 ? money_format('%.2n', floatval($product_data['price'])) : ''); ?></a>
    </div>
</div>