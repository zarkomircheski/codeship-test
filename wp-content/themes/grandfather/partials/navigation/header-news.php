<ul class="nav__main-menu--slideout-section-news">
    <?php
    $news = Fatherly\Page\Helper::fetchNewsForNav();
    if ($news->have_posts()) :
        foreach ($news->posts as $newsPost) :?>
            <li><a href="<?php echo get_the_permalink($newsPost->ID) ?>" data-ev-loc="Header" data-ev-name="Navigation Menu Link" data-ev-val="<?php echo str_replace('"', "'", $newsPost->post_title); ?>">
                    <div class="nav__main-menu--slideout-section-news-item">
                        <p class="nav__main-menu--slideout-section-news-item-title">
                                    <?php echo $newsPost->post_title ?>
                        </p>
                        <div class="nav__main-menu--slideout-section-news-item-image">
                                    <?php echo fth_img_tag(array( 'post_id' => $newsPost->ID, 'classes'=> 'healo', 'width' => 141, 'height' => 141)) ?>
                        </div>
                    </div>
                </a>
            </li>
        <?php endforeach;
    endif; ?>
</ul>
