<div class="free-form">
    <div class="free-form-content <?php echo $module->data['form_layout'] ?>">
        <?php echo wp_kses_post($module->data['content']); ?>
    </div>
</div>