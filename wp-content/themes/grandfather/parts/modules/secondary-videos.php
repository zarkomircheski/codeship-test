<?php
set_query_var('args', $module->data['video_query_args']);
$count = get_query_var('video_row_count') ? get_query_var('video_row_count') : 1;
set_query_var('video_row_count', $count);
$module->renderPartial('single-row-video');
$count += 1;
set_query_var('video_row_count', $count);
