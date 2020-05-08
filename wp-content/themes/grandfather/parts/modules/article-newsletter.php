<?php
$list_id = $module->data['specify_list'] === 'yes' ? $module->data['list_id'] : 1;

$headerCopy = $module->data['newsletter_cta'];

if (is_amp_endpoint()) {
    $domain_url = (isset($_SERVER['HTTPS']) ? "https" : "http") . "://$_SERVER[HTTP_HOST]";
    $actionAttr = 'action-xhr="' . $domain_url . '/wp-content/themes/grandfather/parts/postup-put.php"';
    $placeHolder = 'yourname@email.com';
    $classes = 'amp-wp-newsletter';
    $errorAmp = '<input type="hidden" name="amp" value="true">'
        .'<input name="list_id" type="hidden" value="' . $list_id . '">'
        .'<div submit-error>Oops! Something went wrong. Please contact <a href="mailto:support@fatherly.com">support@fatherly.com</a>.</div>'
        .'<div submit-success>Thanks for subscribing! Go Dads!</div>'
        .'<div submitting>Signing you up now...</div>';
    $error = '';
} else {
    $placeHolder = 'Your Email';
    $classes = 'email-submit email-submit-article-footer';
    $error = '<div class="email-submit-error">Oops! Something went wrong. Please contact <a href="mailto:support@fatherly.com">support@fatherly.com</a>.</div>
                    <div class="email-submit-success">Thank you for subscribing</div>';
    $errorAmp = '';
    set_query_var('class', 'article-bottom-data-form');
}
echo '<div class="' . $classes . '">
            <form method="post"' . $actionAttr . ' class="email-submit-form form" data-list="' . $list_id . '" data-ev-loc="Body" data-ev-name="Email Submit">
                <h3 class="email-submit-title">' . $headerCopy . '</h3>
                <div class="email-submit-wrap">
                    <input name="ampEmailSubmit"  type="email" value="" class="email-submit-input" tabindex="1" placeholder="' . $placeHolder . '" required />
                    <input type="submit" class="button email-submit-submit" value="Sign Up" tabindex="2">
                </div>' .
    $errorAmp . '</form>
            ' . $error . '
        </div>';
$collectionModule = get_field('newsletter_survey_module', 'options');
if (!is_amp_endpoint() && $collectionModule) {
    $collectionModuleFields = get_fields($collectionModule->ID);
    $module = \Fatherly\Page\Module::init()->setupModuleStandalone($collectionModuleFields);
    set_query_var('module', $module);
    $module->renderBareModule($module->template);
}
