<div class="nav__main-menu--top">
    <div class="container-fluid">
        <?php get_template_part('partials/navigation/menu', 'hamburger'); ?>
        <div class="nav__logo-wordmark" data-ev-loc="Header" data-ev-name="Header Logo">
            <a href="<?php echo site_url(); ?>">
                <?php get_template_part('img/wordmark', 'header.svg'); ?>
            </a>
        </div>
                <?php header_article_menu(); ?>
        <div class="nav__main-menu--top-actions">
            <ul>
                <li>
                    <a href="javascript:void(0)" id="header-newsletter"
                       data-ev-loc="Header" data-ev-name="Email Sign Up"> Get The Newsletter
                    </a>
                </li>
            </ul>
        </div>
    </div>
</div>
