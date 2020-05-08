<?php
$categories = get_the_category();
$top_category = get_top_category($categories); ?>
<a class="search-post" href="<?php the_permalink(); ?>">
    <article>
        <div class="image-container">
            <?php echo fth_img_tag(array('width' => 300, 'height' => 200)); ?>
        </div>
        <h2 class="search-post__category"><?php echo $top_category['show']['category_name']; ?></h2>
        <h1 class="search-post__title"><?php the_title(); ?></h1>
    </article>
</a>
