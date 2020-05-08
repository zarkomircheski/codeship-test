<div class="nav__menu--email email-submit email-submit-header">
    <div class="nav__menu--email">
        <form method="post" class="email-submit-form nav__menu--email-form calls-to-action__newsletter-form boomtrain-form__desktop article-header-nav-email-form"
              data-ev-loc="Header"
              data-ev-name="Email Submit">
            <input name="boomtrain-form__desktop-email"
                   type="email" value=""
                   class="medium nav__menu--email-form-input boomtrain-form__desktop-email email-submit-input"
                   tabindex="1"
                   data-placeholder-desktop="yourname@email.com"
                   data-placeholder-mobile="yourname@email.com"
                   placeholder=""
                   required/>
            <input type="submit"
                   class="button submit nav__menu--email-form-submit boomtrain-form__desktop-submit email-submit-submit"
                   value="sign up" tabindex="2">
        </form>
        <div class="validation_message boomtrain-form__desktop-error email-submit-error">Oops! Please try again.
        </div>
        <div class="validation_message boomtrain-form__desktop-ok email-submit-success">Thanks! Go Dads!
        </div>
    </div>
    <?php
    $collectionModule = get_field('newsletter_survey_module', 'options');
    if ($collectionModule) {
        $collectionModuleFields = get_fields($collectionModule->ID);
        $module = \Fatherly\Page\Module::init()->setupModuleStandalone($collectionModuleFields);
        set_query_var('class', 'header-nav-form');
        set_query_var('module', $module);
        $module->renderBareModule($module->template);
    }
    ?>
</div>
