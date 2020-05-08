<?php
if (get_query_var('args')) :
    $args = get_query_var('args');
    $latest_query = new WP_Query($args);
    $post_count = $latest_query->post_count;
    $i = 0;
    $count = get_query_var('video_row_count');
endif;

if (isset($latest_query) && $latest_query->have_posts()) : ?>
<div class="secondary-videos">
    <div class="secondary-videos-details">
        <div class="secondary-videos-details-title">
            <?php echo $module->data['title']; ?>
        </div>
        <?php if (isset($module->data['sponsor_data']) && !empty($module->data['sponsor_data']['sponsor_name'])) : ?>
            <div class="secondary-videos-details-sponsor">
                <a class="sponsored-content-icon" href="<?php echo $module->data['sponsor_data']['sponsor_link']; ?>"
                   data-ev-loc="Secondary" data-ev-name="Sponsored Logo" data-ev-val="<?php echo str_replace('"', "'", $module->data['sponsor_data']['sponsor_link']); ?>">
                    <img src="<?php echo $module->data['sponsor_data']['sponsor_logo_url'] ?>">
                </a>
                <span class="sponsored-by">Sponsored By </span>
                <a class="sponsored-content-name" href="<?php echo $module->data['sponsor_data']['sponsor_link']; ?>"
                   data-ev-loc="Secondary" data-ev-name="Sponsored Name" data-ev-val="<?php echo str_replace('"', "'", $module->data['sponsor_data']['sponsor_link']); ?>">
                    <?php echo $module->data['sponsor_data']['sponsor_name']; ?>
                </a>
            </div>
        <?php endif; ?>
        <div class="secondary-videos-details-count">
            <?php echo $post_count > 1 ? $post_count . ' Videos' : $post_count . ' Video'; ?>
        </div>
        <?php if (isset($module->data['follow_link']) && $module->data['follow_link'] !== '') : ?>
            <a class="secondary-videos-details-follow" href="<?php echo $module->data['follow_link'];?>"
                data-ev-loc="Secondary" data-ev-name="Secondary Video Follow" data-ev-val="<?php echo str_replace('"', "'", $module->data['follow_link']);?>">Follow</a>
        <?php endif; ?>
    </div>
    <div class="secondary-videos-overflow move-0">
        <div class="video-arrow video-arrow-left" data-ev-loc="Secondary" data-ev-name="Secondary Video Backtrack" data-ev-val="<?php echo str_replace('"', "'", $module->data['title']); ?> | <?php echo $count;?>">
            <i class="video-arrow-icon video-arrow-left-icon"></i>
        </div>
        <div class="secondary-videos-content <?php echo 'content-width-' . $post_count; ?>">
            <?php while ($latest_query->have_posts()) : ?>
                <?php $latest_query->the_post(); ?>
                <div id="<?php echo $i; ?>" class="secondary-videos-item <?php echo ($latest_query->current_post + 1 === $post_count) ? 'last-index' : ''; ?>">
                    <a href="<?php the_permalink(); ?>" data-ev-loc="Secondary" data-ev-name="Secondary Video Play - Thumbnail" data-ev-val="<?php echo str_replace('"', "'", $module->data['title']); ?> | <?php echo $count; ?> | <?php echo $i + 1; ?>">
                        <?php echo fth_img_tag(array('width' => 250, 'height' => 150)); ?>
                        <div class="btn__play-button">
                            <button class="btn__play-button-icon"></button>
                        </div>
                    </a>
                    <a href="<?php the_permalink(); ?>" data-ev-loc="Secondary" data-ev-name="Secondary Video Play - Title" data-ev-val="<?php echo str_replace('"', "'", $module->data['title']); ?> | <?php echo $count; ?> | <?php echo $i + 1; ?>">
                        <div class="secondary-videos-item-description">
                            <span class="secondary-videos-item-title"><?php echo the_title(); ?></span>
                        </div>
                    </a>
                </div>
                <?php $i++; ?>
            <?php endwhile; ?>
        </div>
        <div class="video-arrow video-arrow-right" data-ev-loc="Secondary" data-ev-name="Secondary Video Advance" data-ev-val="<?php echo str_replace('"', "'", $module->data['title']); ?> | <?php echo $count;?>">
            <i class="video-arrow-icon video-arrow-right-icon"></i>
        </div>
    </div>
</div>
<?php endif; ?>
