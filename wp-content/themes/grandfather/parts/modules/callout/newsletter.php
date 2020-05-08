<div class="newsletter-signup <?php echo $module->variant ?>">
    <div class="newsletter-signup-input email-submit email-submit-module" <?php echo ($module->variant === 'variant_2') ? sprintf(
        "style=\"background-image:url('%s')\"",
        $module->data['image']
    ) : null; ?>>
        <form method="post" class="email-submit-form" data-ev-loc="Feed" data-ev-name="Email Submit">
            <div class="newsletter-signup-content">
                <div class="newsletter-signup-image">
                    <img src="/wp-content/themes/grandfather/images/fatherly-icon.svg">
                </div>
                <div class="newsletter-signup-content-text">
                    <div class="newsletter-signup-title"><?php echo $module->data['content'] ?></div>
                    <div class="newsletter-signup-info">&#x02193;
                        <?php
                        if ($module->variant !== 'variant_2') {
                            echo 'Get the best of Fatherly in your inbox';
                        } ?>
                    </div>
                    <div class="newsletter-signup-form">
                        <input name="newsletterEmail" type="email" value=""
                               class="newsletter-signup-textbox email-submit-input" tabindex="1"
                               placeholder="Enter your email" required>
                        <input type="hidden" name="newsletterRef" class="newsletter-signup-source"
                               value="HP Module <?php echo $module->variant ?>">
                        <input type="submit" class="newsletter-signup-button email-submit-submit" value="Sign Up" tabindex="2">
                    </div>
                </div>
            </div>
        </form>
        <div class="email-submit-error">Oops! Please try again.</div>
        <div class="email-submit-success">Thanks for subscribing!
        </div>

    </div>
    <?php
    if ($module->variant === 'variant_2') {
        ?>
        <div class="newsletter-signup-ad">
            <div class="newsletter-signup-ad-text advertisement-text">ADVERTISEMENT</div>
            <?php Fatherly\Dfp\Helper::init()->id('box1')->printTag(); ?>
        </div>
        <?php
    } else {
        ?>
        <div class="newsletter-signup-top-image">
            <img src='<?php echo fth_img(array('width' => 300, 'height' => 180, 'attachment_id' => $module->data['image'],
                'cropType' => false, 'fit' => 'clip')); ?>'>
        </div>
        <?php
    } ?>
</div>
