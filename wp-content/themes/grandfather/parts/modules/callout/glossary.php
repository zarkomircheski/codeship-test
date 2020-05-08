<div class="glossary">
    <h2 class="glossary-title sub-title"><?php echo $module->data['title'] ?></h2>
    <div class="glossary-content">
        <?php foreach ($module->data['add_glossary_item'] as $glossary) { ?>
            <div class="glossary-content-item">
                <div class="glossary-content-item-title">
                    <?php echo $glossary['glossary_item_title'] ?>
                </div>
                <div class="glossary-content-item-definition">
                    <div class="glossary-content-item-definition-text">
                        <span class="glossary-content-item-definition-text-title">
                            <?php echo $glossary['glossary_item_title'] ?>:
                        </span>
                        <?php echo $glossary['glossary_item_definition'] ?>
                    </div>
                </div>
            </div>
        <?php } ?>
    </div>
</div>