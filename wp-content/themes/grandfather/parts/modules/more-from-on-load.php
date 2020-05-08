<?php
set_query_var('args', $module->data['query_args']);
if ($module->data['enable_tag'] === 'yes') {
    set_query_var('custom_tag', wp_json_encode($module->data['query_args']));
}
\Fatherly\Page\Module::renderBareModule('more-from');
?>
<div class="more-from-load-more">
    Show More
</div>
