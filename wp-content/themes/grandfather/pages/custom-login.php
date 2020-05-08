<?php /* Template Name: Custom Login */ ?>
<?php get_header(); ?>
<div class="login-custom page-content container-xsm clearfix">
    <main role="main" class="default-page">
        <!-- section -->
        <?php $utm_name = fth_get_utm_info() ?>
        <section class="page-template">
            <?php
            if (isset($utm_name['name']) && $utm_name['name'] == 'energystar') {
                get_template_part('pages/sweepstakes/energystar');
            } elseif (isset($utm_name['name']) && $utm_name['name'] == 'foty') {
                get_template_part('pages/sweepstakes/foty');
            } else {
                get_template_part('pages/sweepstakes/default');
            }
            ?>

        </section>
        <!-- /section -->
    </main>
</div>

<?php // get_sidebar();?>

<?php get_footer(); ?>
