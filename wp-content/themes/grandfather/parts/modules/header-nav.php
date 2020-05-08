<div class="header-nav expandable-nav">
  <?php get_template_part('partials/navigation/header', 'search'); ?>
    <div class="header-nav-top">
        <?php get_template_part('partials/navigation/menu', 'hamburger'); ?>
        <div class="header-nav-top-content">
            <ul class="header-nav-top-links">
                <li>
                    <a href="<?php echo get_site_url() . "/to-me-he-was-just-dad-fathers-day-present-2020/" ?>" data-ev-loc="Header" data-ev-name="Navigation Menu - Promo" data-ev-val="Check Out Our Book">Check Out Our Book!
                    </a>
                </li>
                <li>
                    <a href="<?php echo get_site_url() . "/finding-fred-rogers-podcast/" ?>" data-ev-loc="Header" data-ev-name="Navigation Menu - Promo" data-ev-val="Finding Fred">Finding Fred
                    </a>
                </li>
            </ul>
            <ul class="header-nav-top-social">
                <li>
                    <a target="_blank" data-ev-loc="Header" data-ev-name="Navigation Menu - Promo" data-ev-val="Youtube"
                       href="<?php echo Fatherly\Config\FatherlyConfig::config()->get('social_accounts')->youtube; ?>?sub_confirmation=1"><i class="fas icon-youtube"></i></a>
                </li>
                <li>
                    <a target="_blank" data-ev-loc="Header" data-ev-name="Navigation Menu - Promo" data-ev-val="Instagram"
                       href="<?php echo Fatherly\Config\FatherlyConfig::config()->get('social_accounts')->instagram; ?>"><i class="fas icon-instagram"></i></a>
                </li>
                <li>
                    <a target="_blank" data-ev-loc="Header" data-ev-name="Navigation Menu - Promo" data-ev-val="Facebook"
                       href="<?php echo Fatherly\Config\FatherlyConfig::config()->get('social_accounts')->facebook; ?>"><i class="fas icon-facebook-squared"></i></a>
                </li>
                <li>
                    <a href="javascript:void(0)" id="header-search"
                       class="site-navigation__right-social-item site-navigation__right-social-item--search search-button search-button--closed"
                       data-ev-loc="Header" data-ev-name="Search"><i class="fas icon-search"></i></a>
                </li>
            </ul>
        </div>
    </div>
    <?php get_template_part('partials/navigation/menu', 'slideout');
    if (!$module->data['hide_banner'] && $module->data['image']) {
        $module->render('callout/banner-image', false);
    }
    if (!$module->data['hide_banner'] && !$module->data['image']) {
        echo ' <img class="top-image" src="https://images.fatherly.com/wp-content/uploads/2018/01/banner-image.png">';
    } ?>
    <div class="header-nav-bottom">
        <div class="header-nav-image">
            <img src="/wp-content/themes/grandfather/images/fatherly-icon-white.svg">
        </div>
        <div class="header-nav-links email-submit">
            <?php header_secondary_menu(); ?>
            <form method="post" class="header-nav-email-form newsletter-form__generic email-submit-form homepage-header-nav-email-form" data-ev-loc="Header" data-ev-name="Email Submit">
                <input type="email" value="" name="newsletterEmail" class="header-nav-email email-submit-input"
                       placeholder="Get the Fatherly newsletter" required>
                <input type="hidden" name="newsletterRef" id="" value="HP Nav - Top">
                <input type="submit" class="header-nav-email-button email-submit-submit" value="Sign Up" tabindex="2">
            </form>
            <div class="email-submit-error">Oops! Please try again.</div>
            <div class="email-submit-success">Thanks for subscribing!</div>
            <?php parenting_menu_homepage(); ?>
        </div>
    </div>
</div>
