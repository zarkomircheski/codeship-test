<div class="experts">
    <h2 class="experts-title sub-title"><?php echo $module->data['title'] ?></h2>
    <div class="experts-content">
        <?php foreach ($module->data['add_experts'] as $expert) { ?>
            <div class="experts-content-expert">
                <a href="<?php echo $expert['expert_page_link'] ?>">
                    <div class="experts-content-expert-image">
                        <img src="<?php echo $expert['expert_image'] ?>?w=150&h=150">
                    </div>
                    <div class="experts-content-expert-title">
                        <?php echo $expert['expert_name'] ?>
                    </div>
                    <div class="experts-content-expert-profession">
                        <?php echo $expert['expert_profession'] ?>
                    </div>
                </a>
            </div>
        <?php } ?>
    </div>
</div>