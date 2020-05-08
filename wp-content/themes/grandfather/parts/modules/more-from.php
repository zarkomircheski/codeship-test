<?php
if (get_query_var('args')) {
    $args = get_query_var('args');
}
$count = 0;
$latest_query = new WP_Query($args);
if ($latest_query->have_posts()) :
    $more_posts = $latest_query->posts[15] ? false : true;

    if (!array_key_exists('offset', $latest_query->query)) : ?>
        <div class="more-from-title"><?php echo sprintf("More From %s", ($module->data['title']) ? $module->data['title'] : "Fatherly"); ?></div>
    <?php endif; ?>

<div class="more-from-items <?php if ($more_posts) :
    echo 'more-from-items-done';
                            endif; ?>" <?php echo ($ctag = get_query_var('custom_tag')) ? "data-module-param='{$ctag}'" : null; ?>>

    <?php while ($latest_query->have_posts()) :
        $latest_query->the_post();
        $categories = get_the_category();
        $top_category = $categories[0];
        $category_permalink = get_category_link($top_category);
        ?>
    <div class="more-from-item">
        <!-- link to Article -->
        <a href="<?php the_permalink(); ?>" data-ev-loc="Feed" data-ev-name="Headline">
        <?php echo fth_img_tag(array('width' => 400, 'height' => 200)); ?>
            <div class="more-from-item-description">
              <span class="more-from-item-title"><?php echo  (!empty(get_field('frontpage_headline', get_the_ID()))) ? get_field('frontpage_headline', get_the_ID()) : the_title(); ?></span>
              <span class="more-from-item-summary"><?php echo get_field('custom_excerpt'); ?></span>
            </div>
        </a>
        <?php if (($franchise = \Fatherly\Article\ArticleHelper::articleHasFranchiseTag($post)) && !is_tag($m_post['franchise'])) : ?>
            <a href="<?php echo get_tag_link($franchise); ?>" data-ev-loc="Feed" data-ev-name="Category Link"
               data-ev-val="<?php echo str_replace('"', "'", $franchise->name); ?>" class="franchise__tag franchise__tag__module more-from-item-franchise">
                <span class="franchise__tag-name"><?php echo $franchise->name; ?></span>
            </a>
        <?php else : ?>
            <!-- link to category -->
            <a class="more-from-item-category category" href="<?php echo $category_permalink ?>"
                data-ev-loc="Feed" data-ev-name="Category Link" data-ev-val="<?php echo str_replace('"', "'", $top_category->name); ?>">
                <div><span class="arrow"></span><?php echo $top_category->name; ?></div>
            </a>
        <?php endif; ?>
    </div>
    <?php endwhile; ?>
<?php else : ?>
    <div class="more-from-items more-from-items-done">
<?php endif; ?>
</div>
