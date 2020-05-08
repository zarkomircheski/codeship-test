<footer class="site-footer">
    <div class="site-footer__footer-logo">
        <a href="https://www.fatherly.com/">
            <img src="<?php echo fth_get_protocol_relative_template_directory_uri(); ?>/images/fatherly-icon-white.svg"
                 alt="Fatherly logo">
        </a>
    </div>
    <hr/>
    <ul class="footer-social site-footer__footer-social-list">
        <li class="site-footer__footer-social-list-item"><a href="<?php echo Fatherly\Config\FatherlyConfig::config()->get('social_accounts')->facebook; ?>"
                                                            target="_blank"><i class="fas icon-facebook-squared"></i></a>
        </li>
        <li class="site-footer__footer-social-list-item"><a href="<?php echo Fatherly\Config\FatherlyConfig::config()->get('social_accounts')->instagram; ?>"
                                                            target="_blank"><i class="fas icon-instagram"></i></a></li>
        <li class="site-footer__footer-social-list-item"><a href="<?php echo Fatherly\Config\FatherlyConfig::config()->get('social_accounts')->pinterest; ?>"
                                                            target="_blank"><i class="fas icon-pinterest-circled"></i></a></li>
        <li class="site-footer__footer-social-list-item"><a href="<?php echo Fatherly\Config\FatherlyConfig::config()->get('social_accounts')->twitter; ?>" target="_blank"><i
                        class="fas icon-twitter"></i></a></li>
    </ul>

    <?php
    wp_nav_menu([
        'theme_location' => 'footer-menu',
        'container_class' => 'menu-footer-menu-container site-footer__menu-container',
        'menu_class' => 'menu site-footer__menu-container-list'
    ]); ?>

    <p class="site-footer__copyright">©<?php echo date('Y'); ?> Fatherly. All rights reserved.</p>
</footer>

</div>
<?php
wp_footer();
Fatherly\Dfp\Helper::init()->id('oop')->getTag();
Fatherly\Dfp\Helper::init()->id('oop_track')->getTag();
include_once('parts/footer-analytics.php');
get_template_part('partials/modals/surveymonkey');
?>
<script async type="text/javascript" src="https://widgets.outbrain.com/outbrain.js"> </script>
<script async src="https://d2rk50diug0dse.cloudfront.net/512754cfc1f3d16c25c350b7/ediflo-ix_1.1.3f.min.js"></script>
<div id="amzn-assoc-ad-7bd37df6-587c-4acf-ba9d-6e636f327265"></div>
<script async
        src="//z-na.amazon-adsystem.com/widgets/onejs?MarketPlace=US&adInstanceId=7bd37df6-587c-4acf-ba9d-6e636f327265"></script>
</body>
</html>
