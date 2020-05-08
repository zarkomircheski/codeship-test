<?php get_header(); ?>
<?php if ($collection = get_field('content_collection')) : ?>
    <?php
    $collection = new \Fatherly\Page\Collection($collection->ID);
    set_query_var('collection', $collection);
    foreach ($collection->modules as $module) {
        set_query_var('module', $module);
        $module->render();
    }

    ?>

<?php else : ?>
    <?php if (have_posts()) :
        while (have_posts()) :
            the_post(); ?>

            <div class="hero-container--campaign">
                <div class="hero-container__image hero-container__image--campaign"
                     style="background-image:url(<?php echo fth_img(); ?>);"></div>
                <div class="hero-container__photo-credit"><?php the_field('photo_credit'); ?></div>
                <div class="hero-container__hero-overlay">
                    <h1 class="hero-container__sponsor-title"><?php the_title(); ?></h1>
                    <div class="hero-container__sponsor-text">
                        <?php the_field('sponsor_text'); ?>
                        <?php

                            $image = get_field('sponsor_logo');

                        if (!empty($image)) : ?>
                                <a href="<?php the_field('link'); ?>" target="_blank"
                                   class="hero-container__sponsor-link">
                                    <img src="<?php echo $image['url']; ?>" alt="<?php echo $image['alt']; ?>"
                                         class="hero-container__sponsor-logo"/>
                                </a>

                        <?php endif; ?>
                    </div>
                    <?php the_field('hero_overlay_text'); ?>
                </div>
            </div>

            <div class="home page-content container-sm clearfix">

                <div class="main">
                    <?php

                        $posts = get_field('associated_posts');

                    if ($posts) : ?>
                            <?php foreach ($posts as $post) : // variable must be called $post (IMPORTANT)?>
                                <?php setup_postdata($post); ?>
                                <?php get_template_part('parts/loop', 'category'); ?>
                            <?php endforeach; ?>
                            <?php wp_reset_postdata(); // IMPORTANT - reset the $post object so the rest of the page works correctly?>
                    <?php endif; ?>
                </div>
                <div class="sidebar latest-posts__sidebar">

                    <?php get_template_part('parts/sidebar', 'posts'); ?>

                </div>
            </div>
        <?php endwhile;
    endif; ?>
<?php endif; ?>
<?php get_footer(); ?>
