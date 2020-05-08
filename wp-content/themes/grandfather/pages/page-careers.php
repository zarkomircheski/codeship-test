<?php
/*
Template Name: Careers
*/
?>

<?php get_header(); ?>
    <div class="page-content container-sm clearfix">
        
            <div class="default-page">
        
                <?php if (have_posts()) :
                    while (have_posts()) :
                        the_post(); ?>
                
                <div class="post career-container" id="post-<?php the_ID(); ?>">
                
                    <h2 class="career-container__title"><?php the_title(); ?></h2>
                    <div class="career-container__career-list">
                        <?php
                        query_posts(array(
                            'post_type' => 'job',
                            'showposts' => 50
                        ));
                        ?>
                        <?php while (have_posts()) :
                            the_post(); ?>
                            <a class="career-container__post-link" href="<?php the_permalink(); ?>">
                                <div class="title career-container__post-title"><?php the_title(); ?></div>
                            </a>
                        <?php endwhile;?>
                        <?php wp_reset_query(); ?>
                    </div>
                </div>
                
                    <?php endwhile;
                endif; ?>
        
            </div>

    </div>

<?php get_footer(); ?>
