<div class="egstar__cover-image">
    <img src="https://www.fatherly.com/wp-content/uploads/2017/07/fatherly-energystar-coverimage.jpg">
</div>
<h2 class="login-custom__title">Enter To <span
            class="drop-title">Win The Wash</span></h2>
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
<p class="egstar__copy">Fatherly has partnered with LG to offer
    you the chance to win an ENERGY STAR-certified washer/dryer
    to make laundry better -- better for your clothes, better
    for the environment, better for your family. This LG washer
    and dryer system includes the LG TWINWash™ 2-in-1 washer
    system which lets you do two loads of laundry at once,
    delivering superior efficiency and performance. ENERGY
    STAR's certification also means you'll save on your utility
    bills ($490 over the lifetime of an ENERGY STAR-certified
    washer and even more with a washer/dryer pair) and do your
    part to reduce your carbon footprint. Enter now for your
    chance to win this top-of-the-line, energy-efficient
    washer/dryer!</p>
<div class="egstar__sponsor-logos">
    <ul class="egstar__sponsor-list">
        <li class="egstar__sponsor-item "><img
                    src="https://www.fatherly.com/wp-content/uploads/2017/07/LG-TWIN-Wash-Logo_4c_grey.png">
        </li>
        <li class="egstar__sponsor-item"><img
                    src="https://1ehukb3kd764oddub3rdo4uw-wpengine.netdna-ssl.com/wp-content/themes/grandfather/images/logo-thick.png">
        </li>
    </ul>
</div>
<p class="egstar__fineprint">LG TwinWash washer and dryer pair
    models may vary from pictured due to inventory and/or
    household requirements related to size and installation
    requirements.</p>
<div class="egstar__footer-image"><img
        src="https://images.fatherly.com/wp-content/uploads/2017/07/energystar_motherlyad_r5_hires_300ppi.jpg"
        alt=""/></div>
