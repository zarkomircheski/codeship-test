<?php get_header();

    $desktop = Fatherly\Page\NewsletterSignup::init()->getBackgroundImage('desktop');
    $mobile = Fatherly\Page\NewsletterSignup::init()->getBackgroundImage('mobile');
    $hasRecipId = isset($_GET['recipientID']);
    $recipId = $hasRecipId ? ' from-newsletter" data-recip="'.$_GET['recipientID'].'"' : '"';
    $referrer = isset($_GET['referrerID']) ? 'data-referrer="'.$_GET['referrerID'].'"': '';
?>

<style>
    body {
        background-image: url(<?php echo $desktop ?>);
    }

    @media screen and (max-width: 850px) {
        body {
            background-image: url(<?php echo $mobile ?>);
        }
    }
</style>
<div class="login-custom page-content clearfix newsletter--signup-landing <?php echo $recipId.' '.$referrer ?>>
    <main role="main" class="default-page newsletter--signup-landing-wrapper">
        <!-- section -->

        <section class="page-template newsletter--signup-landing-content">
            <?php if (!$hasRecipId) { ?>
            <div class="login-custom__title">
                <?php echo Fatherly\Page\NewsletterSignup::init()->getCopy(); ?>
            </div>
            <?php } ?>
            <article class="newsletter--signup_article"
                     data-confirm-redirect="<?php echo get_field('signup_confirmation_page') ?>">
                <div class="email-submit email-submit-landing-form newsletter--signup-landing-form">
                    <?php if (!$hasRecipId) { ?>
                    <form method="post" class="email-submit-form"
                          data-ev-loc="Newsletter Page<?php echo $_GET['article_footer'] ? ' From Article' : ''; ?>" data-ev-name="Email Submit" data-ev-val="<?php echo Fatherly\Page\NewsletterSignup::init()->getReferralKey(); ?>">
                        <input  type="email" value=""
                                class="email-submit-input"
                                tabindex="1" placeholder="Enter your email address" required/>
                        <input type="submit"
                               class="button email-submit-submit"
                               value="Sign Up" tabindex="2">
                        <input type="hidden" name="referred_by" class="boomtrain-form__archive-referred-by"
                               value="<?php echo Fatherly\Page\NewsletterSignup::init()->getReferralKey(); ?>">
                    </form>
                    <div class="email-submit-error">Oops! Please try again.</div>
                    <a class="newsletter--signup-landing-link" target="_blank"
                       href="http://click1.mail.fatherly.com/ViewMessage.do?m=jvmbjsqv&r=dmtsttmmt&s=hbvwwnnhhdjqvjnsdgbjdqvpbsnvmggsgdb&q=1526501727&a=view">See
                        a sample of our newsletter</a>
                    <div class="email-submit-success">
                        Thanks for subscribing!
                    </div>
                    <?php } else {
                        set_query_var('show', 'show'); ?>
                    <?php } ?>
                </div>
                <?php
                $collectionModule = get_field('newsletter_survey_module', 'options');
                if ($collectionModule) {
                    $collectionModuleFields = get_fields($collectionModule->ID);
                    $module = \Fatherly\Page\Module::init()->setupModuleStandalone($collectionModuleFields);
                    set_query_var('class', 'newsletter-landing-page');
                    set_query_var('module', $module);
                    $module->renderBareModule($module->template);
                }
                ?>
            </article>


        </section>
        <!-- /section -->
        <div class="newsletter--signup-landing-phone">
            <img src="<?php echo get_template_directory_uri(); ?>/images/phone.png">
        </div>
    </main>
</div>

<?php get_footer(); ?>