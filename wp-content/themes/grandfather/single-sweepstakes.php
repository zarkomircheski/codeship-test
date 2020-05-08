<?php get_header(); ?>
<?php $sweep_fields = get_fields(); ?>
<div class="login-custom sweepstakes__page page-content clearfix">
    <main role="main" class="default-page">
        <!-- section -->
        <section class="page-template">
            <div class="sweepstakes sweepstakes__cover-image">
                <?php if (!empty($sweep_fields['sweepstakes_image'])) : ?>
                    <img src="<?php echo $sweep_fields['sweepstakes_image']; ?>">
                <?php else : ?>
                    <img src="https://images.fatherly.com/wp-content/uploads/2017/01/GettyImages-678751579-2.jpg"
                         alt="">
                <?php endif; ?>
            </div>
            <?php if (!empty($sweep_fields['custom_title'])) : ?>
                <h2 class="login-custom__title"><?php echo $sweep_fields['custom_title']; ?></h2>
            <?php endif; ?>


            <!-- article -->
            <article
                    id="post-<?php the_ID(); ?>" <?php post_class('login-custom__form sweepstakes__post'); ?>>
                <div class="sweepstakes__dojomojo-embed"
                     data-embed-2="<?php echo $sweep_fields['dojo_mojo_embed_id_2'] ?>"></div>
                <?php the_content(); ?>
            </article>
            <!-- /article -->


        </section>
        <!-- /section -->
    </main>
</div>

<?php // get_sidebar();?>

<?php get_footer(); ?>
