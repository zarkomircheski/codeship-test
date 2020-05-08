<?php get_header(); ?>
    
<section class="home-featured">         
    <?php get_template_part('parts/loop', 'featured'); ?>
</section>
<div class="page-content container-sm clearfix">

    <main role="main">

        <section class="home-latest">

            <?php get_template_part('parts/popular', 'posts'); ?>
            <?php get_template_part('parts/cta', 'mixed'); ?>
            <?php get_template_part('parts/banner', 'ads'); ?>
            <?php get_template_part('parts/video', 'posts'); ?>
            <?php get_template_part('parts/latest', 'posts'); ?>
            <?php get_template_part('pagination'); ?>
            
        </section>

    </main>

    <?php // get_sidebar();?>

</div>

<?php get_footer(); ?>
