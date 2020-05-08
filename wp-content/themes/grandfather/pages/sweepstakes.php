<?php
/* Template Name: Sweepstakes */
?>
<?php get_header();

// Custom fields
$sweepstakes_cta = get_field('sweepstakes_cta');
$success_message = get_field('sweepstakes_success_msg');

?>
<div class="login-custom page-content container-xsm clearfix">
    <main role="main" class="default-page">
        <!-- section -->
        <section class="page-template">
          <h2 class="login-custom__title">
                <?php echo $sweepstakes_cta; ?>
          </h2>
            <?php if (have_posts()) :
                while (have_posts()) :
                    the_post(); ?>
              <article id="post-<?php the_ID(); ?>" <?php post_class('login-custom__form'); ?>>
                    <?php the_content(); ?>
                  <p class="login-page__success-msg">
                    <?php echo $success_message; ?>
                  </p>
              </article>
                <?php endwhile; ?>
            <?php endif; ?>

        </section>
        <!-- /section -->
    </main>
</div>

<?php get_footer(); ?>
