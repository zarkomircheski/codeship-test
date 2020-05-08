<div class="mobile-ad-nav">
    <div class="mobile-ad-nav__ad">
        <?php Fatherly\Dfp\Helper::init()->id('lead2')->printTag(); ?>
    </div>
    <button data-ev-loc="Footer" data-form="inline-tap" data-submit="inline-tap-submit" data-ev-name="Sticky Subscribe Overlay" class="newsletter__button">Tap to Subscribe</button>
    <div class="inline-newsletter inline-newsletter--general newsletter__mobile inline-tap">
        <div class="newsletter__overlay">
            <a class="mobile-like-button" href="<?php echo Fatherly\Config\FatherlyConfig::config()->get('social_accounts')->facebook; ?>">Like fatherly on Facebook</a>
        </div>
    </div>
    <?php

    // Newsletter signup
    set_query_var('form_name', 'flyout');
    get_template_part('partials/generic-newsletter-submit');

    // Data Collection
    $collectionModule = get_field('newsletter_survey_module', 'options');
    if ($collectionModule) {
        $collectionModuleFields = get_fields($collectionModule->ID);
        $module = \Fatherly\Page\Module::init()->setupModuleStandalone($collectionModuleFields);
        set_query_var('class', 'tap-subscribe-form');
        set_query_var('module', $module);
        $module->renderBareModule($module->template);
    }
    ?>
</div>
