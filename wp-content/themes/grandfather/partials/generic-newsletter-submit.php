<?php
$formName = get_query_var('form_name') !== null ? get_query_var('form_name') : 'generic-form';
?>

<div class="email-submit <?php echo $formName; ?>">
    <div class="email-submit-close">&#x02A2F;</div>
    <form method="post" class="email-submit-form"
          data-ev-loc="Footer" data-ev-name="Email Submit" data-ev-val="<?php echo $formName; ?>">
        <div class="email-submit-title">Get Our Daily Newsletter</div>
        <input  type="email" value=""
                class="email-submit-form-input"
                tabindex="1" placeholder="EMAIL ADDRESS" required/>
        <input type="submit"
               class="button email-submit-form-submit"
               value="SUBSCRIBE" tabindex="2">
    </form>
    <div class="email-submit-error">Oops! Something went wrong please contact us at support@fatherly.com.</div>
    <div class="email-submit-success">Thank You!</div>
</div>