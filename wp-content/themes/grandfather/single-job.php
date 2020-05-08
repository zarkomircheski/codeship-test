<?php get_header(); ?>
<div class="page-content container-xsm clearfix">

    <div class="default-page">
        <?php if (have_posts()) :
            while (have_posts()) :
                the_post(); ?>

                <div class="post" id="post-<?php the_ID(); ?>">
                    <h2><?php the_title(); ?></h2>
                    <?php the_content(); ?>

                </div>

            <?php endwhile;
        endif; ?>

    </div>

</div>

<?php get_footer(); ?>
