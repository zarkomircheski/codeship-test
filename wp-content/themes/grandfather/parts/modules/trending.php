<?php
set_query_var('custom_posts', $module->data['highlighted_posts']);
set_query_var('show_title', true);
$module->renderPartial('single-row');
