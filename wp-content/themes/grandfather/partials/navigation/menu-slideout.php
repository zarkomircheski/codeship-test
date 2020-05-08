<div class="nav__main-menu--slideout">
    <div class="container clearfix">
        <div class="nav__main-menu--slideout-column">
            <?php vertical_menu(); ?>
        </div>
        <div class="nav__main-menu--slideout-column">
            <div class="nav__main-menu--slideout-section">
                <h4 class="nav__main-menu--slideout-section-name"><a href="<?php echo site_url('parenting');?>" data-ev-loc="Header" data-ev-name="Navigation Menu Link" data-ev-val="Parenting">
                        Parenting Stages</a></h4>
                <?php parenting_menu(); ?>
            </div>
            <div class="nav__main-menu--slideout-section mobile-hidden">
                <h4 class="nav__main-menu--slideout-section-name">Follow
                    Us</h4>
                <ul class="nav__main-menu--slideout-section-social">
                    <li><a href="<?php echo Fatherly\Config\FatherlyConfig::config()->get('social_accounts')->facebook; ?>"
                           target="_blank"
                           class="nav__main-menu--slideout-section-social-item--facebook"
                           data-ev-loc="Header"
                           data-ev-name="Navigation Menu Link"
                           data-ev-val="Facebook"
                           ><i class="icon-facebook-squared"></i></a>
                    </li>
                    <li><a href="<?php echo Fatherly\Config\FatherlyConfig::config()->get('social_accounts')->instagram; ?>"
                           target="_blank"
                           class="nav__main-menu--slideout-section-social-item--instagram"
                           data-ev-loc="Header"
                           data-ev-name="Navigation Menu Link"
                           data-ev-val="Instagram"
                           ><i class="icon-instagram"></i></a>
                    </li>
                    <li><a href="<?php echo Fatherly\Config\FatherlyConfig::config()->get('social_accounts')->pinterest; ?>"
                           target="_blank"
                           class="nav__main-menu--slideout-section-social-item--pinterest"
                           data-ev-loc="Header"
                           data-ev-name="Navigation Menu Link"
                           data-ev-val="Pinterest"
                           ><i class="icon-pinterest-circled"></i></a>
                    </li>
                    <li><a href="<?php echo Fatherly\Config\FatherlyConfig::config()->get('social_accounts')->twitter; ?>"
                           target="_blank"
                           class="nav__main-menu--slideout-section-social-item--twitter"
                           data-ev-loc="Header"
                           data-ev-name="Navigation Menu Link"
                           data-ev-val="Twitter"
                           ><i class="icon-twitter"></i></a>
                    </li>
                </ul>
            </div>

        </div>
        <div class="nav__main-menu--slideout-column mobile-hidden">
            <div class="nav__main-menu--slideout-section">
                <h4 class="nav__main-menu--slideout-section-name"><a
                            href="/news" data-ev-loc="Header" data-ev-name="Category Link" data-ev-val="News">News</a></h4>
                <?php get_template_part('partials/navigation/header', 'news'); ?>
            </div>
            <div class="nav__main-menu--slideout-section">
                <?php
                if ($feature = get_field('navigation_featured_label', 'option')) :
                    $featurePost = get_field('navigation_post', 'option'); ?>
                    <h4 class="nav__main-menu--slideout-section-name"><?php echo $feature; ?></h4>
                    <div class="nav__main-menu--slideout-section-feature">
                        <?php echo fth_img_tag(array('post_id' => $featurePost, 'width' => 120, 'height' => 90)); ?>
                        <p class="nav__main-menu--slideout-section-feature-title">
                            <a href="<?php echo get_the_permalink($featurePost); ?>" data-ev-loc="Header" data-ev-name="Headline">
                                <?php echo substr(wp_strip_all_tags(get_the_title($featurePost), true), 0, 60) . "…"; ?>
                            </a>
                        </p>
                    </div>
                <?php endif;
                ?>

            </div>
            <div class="nav__main-menu--slideout-search-form">
                <form class="search" method="get" action="/"
                      role="search">
                    <input class="nav__main-menu--slideout-search-form-input"
                           type="text" name="s"
                           placeholder="Search...">
                    <button class="nav__main-menu--slideout-search-form-submit"
                            type="submit" role="button" data-ev-loc="Header" data-ev-name="Search Submit"><i
                                class="fas icon-search"></i>
                    </button>
                </form>
            </div>
        </div>
        <div class="nav__main-menu--slideout-column mobile-visible">
            <div class="nav__main-menu--slideout-section">
                <div class="nav__main-menu--slideout-search-form">
                    <form class="search" method="get" action="/"
                          role="search">
                        <input class="nav__main-menu--slideout-search-form-input"
                               type="text" name="s"
                               placeholder="Search...">
                        <button class="nav__main-menu--slideout-search-form-submit"
                                type="submit" role="button" data-ev-loc="Header" data-ev-name="Search Submit"><i
                                    class="fas icon-search"></i>
                        </button>
                    </form>
                </div>
            </div>
            <div class="nav__main-menu--slideout-section">
                <h4 class="nav__main-menu--slideout-section-name-orange">
                    Follow
                    Us</h4>
                <ul class="nav__main-menu--slideout-section-social">
                    <li><a href="<?php echo Fatherly\Config\FatherlyConfig::config()->get('social_accounts')->facebook; ?>"
                           target="_blank"
                           class="nav__main-menu--slideout-section-social-item--facebook"
                           data-ev-loc="Header" data-ev-name="Navigation Menu Link" data-ev-val="Facebook">
                            <i class="fab icon-facebook-squared"></i>
                        </a>
                    </li>
                    <li><a href="<?php echo Fatherly\Config\FatherlyConfig::config()->get('social_accounts')->instagram; ?>"
                           target="_blank"
                           class="nav__main-menu--slideout-section-social-item--instagram"
                           data-ev-loc="Header" data-ev-name="Navigation Menu Link" data-ev-val="Instagram">
                            <i class="fab incon-instagram"></i>
                        </a>
                    </li>
                    <li><a href="<?php echo Fatherly\Config\FatherlyConfig::config()->get('social_accounts')->pinterest; ?>"
                           target="_blank"
                           class="nav__main-menu--slideout-section-social-item--pinterest"
                           data-ev-loc="Header" data-ev-name="Navigation Menu Link" data-ev-val="Pinterest">
                            <i class="fab icon-pinterest-circled"></i>
                        </a>
                    </li>
                    <li><a href="<?php echo Fatherly\Config\FatherlyConfig::config()->get('social_accounts')->twitter; ?>"
                           target="_blank"
                           class="nav__main-menu--slideout-section-social-item--twitter"
                           data-ev-loc="Header" data-ev-name="Navigation Menu Link" data-ev-val="Twitter">
                            <i class="fab icon-twitter"></i>
                        </a>
                    </li>
                </ul>
            </div>
        </div>

    </div>
    <div class="nav__main-menu--slideout-secondary">
        <div class="container clearfix">
            <div class="nav__main-menu--slideout-secondary-menu">
                <ul>
                    <?php slideout_secondary_menu(); ?>
                    <li>
                        <span class="copyright">&copy; <?php echo date('Y'); ?></span>
                    </li>
                </ul>

            </div>

        </div>
    </div>

</div>
