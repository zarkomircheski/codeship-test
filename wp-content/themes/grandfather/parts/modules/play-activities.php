<?php
set_query_var('title', 'Play<span style="color:#DC4028">/</span>Activities');
set_query_var('show_categories', true);
set_query_var('show_count', false);
set_query_var('show_author', false);
set_query_var('is_sponsored', false);

get_template_part('parts/module', 'single-row');
