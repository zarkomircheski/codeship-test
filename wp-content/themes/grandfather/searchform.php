<div class="search-form">
    <form class="search-form" method="get" action="<?php echo home_url(); ?>" role="search">
        <div class="search-form__button"><input type="text" value="<?php echo esc_html($_GET['s']); ?>" name="s"
                                                class="search-form__input"
                                                placeholder="You have questions, we have answers." required>
            <button class="search-form__arrow">&#8594;</button>
        </div>
    </form>
</div>
