<h2 class="login-custom__title">
    <?php if (isset($utm_name['cta'])) : ?>
        <?php echo $utm_name['cta']; ?>
    <?php else : ?>
        Sign up for tips, tricks, and advice you'll actually use.
    <?php endif; ?>
</h2>
<?php if (have_posts()) :
    while (have_posts()) :
        the_post(); ?>
    <!-- article -->
    <article
        id="post-<?php the_ID(); ?>" <?php post_class('login-custom__form'); ?>>

            <?php the_content(); ?>

    </article>
    <!-- /article -->

    <?php endwhile; ?>

<?php else : ?>
    <!-- article -->
    <article>

        <h2><?php _e('Sorry, nothing to display.', 'html5blank'); ?></h2>

    </article>
    <!-- /article -->

<?php endif; ?>
