<?php $count = 0; ?>
<div class="faq">
    <h2 class="faq-title sub-title"><?php echo $module->data['title'] ?></h2>
    <div class="faq-content">
        <?php foreach ($module->data['add_faq'] as $faq) { ?>
            <div class="faq-content-section">
               <?php  if (substr_count($faq['faq_module'], '<p>') > 1) : ?>
                    <input id="faq-toggle-<?php echo $count ?>" class="faq-toggle" type="checkbox">
                    <label for="faq-toggle-<?php echo $count ?>"><div class="read-more-arrow"></div></label>
                    <?php $count++;
               endif; ?>
                <div class="faq-content-section-text">
                    <?php echo $faq['faq_module'] ?>
                </div>
            </div>
        <?php } ?>
    </div>
</div>
