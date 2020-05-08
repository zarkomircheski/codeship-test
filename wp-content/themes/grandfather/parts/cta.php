<div class="calls-to-action clearfix clear">
    <div class="calls-to-action__main">

        <div class="calls-to-action__newsletter clearfix">
            <h4 class="calls-to-action__newsletter-title">Personalized Content in Your Inbox</h4>
            <form method="post" class="calls-to-action__newsletter-form boomtrain-form__archive">
                <div class="ginput_container ginput_container_email">
                    <input name="boomtrainEmail" type="text" value="" class="medium calls-to-action__newsletter-email-input boomtrain-form__archive-email" tabindex="1" placeholder="Enter Email Address" />
                    <input type="submit" class="gform_button button calls-to-action__newsletter-submit-btn boomtrain-form__archive-submit" value="Sign Me Up" tabindex="2">
                </div>
                <div class="gfield_description validation_message calls-to-action__newsletter-validation-message boomtrain-form__archive-error" id="boomtrainMessage" style="display:none;"></div>
            </form>
            <div id="gform_confirmation_message_1" class="gform_confirmation_message_1 gform_confirmation_message boomtrain-form__archive-ok" style="display:none;">
                Thanks for subscribing!
            </div>
        </div>

        <div class="calls-to-action__ages-stages clearfix">
            <h4 class="calls-to-action__ages-stages-title">Ages &amp; Stages</h4>
            <ul class="calls-to-action__ages-stages-list">
                <li class="calls-to-action__ages-stages-item">
                    <a href="" class="calls-to-action__ages-stages-link">Expecting</a>
                </li>
                <li class="calls-to-action__ages-stages-item">
                    <a href="" class="calls-to-action__ages-stages-link">Newborn</a>
                </li>
                <li class="calls-to-action__ages-stages-item">
                    <a href="" class="calls-to-action__ages-stages-link">Age 1</a>
                </li>
                <li class="calls-to-action__ages-stages-item">
                    <a href="" class="calls-to-action__ages-stages-link">Age 2</a>
                </li>
                <li class="calls-to-action__ages-stages-item">
                    <a href="" class="calls-to-action__ages-stages-link">Age 3</a>
                </li>
                <li class="calls-to-action__ages-stages-item">
                    <a href="" class="calls-to-action__ages-stages-link">Age 4</a>
                </li>
                <li class="calls-to-action__ages-stages-item">
                    <a href="" class="calls-to-action__ages-stages-link">Age 5</a>
                </li>

            </ul>
        </div>
    </div>
    <div class="calls-to-action__sidebar">
        <div class="calls-to-action__sidebar-ad-container">
            <div class="calls-to-action__sidebar-ad"></div>
        </div>
        <div class="sponsored-sidebar">
            <h3 class="right-rail__heading">From Our Partners</h3>
            <?php Fatherly\Dfp\Helper::init()->id('rail_native1')->customClass('right-rail__side-post')->printTag(); ?>
            <?php Fatherly\Dfp\Helper::init()->id('rail_native2')->customClass('right-rail__side-post')->printTag(); ?>
            <?php Fatherly\Dfp\Helper::init()->id('rail_native3')->customClass('right-rail__side-post')->printTag(); ?>
        </div>
    </div>

</div>
