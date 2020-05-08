<?php

set_query_var('title', 'Gift Guides');
set_query_var('show_categories', true);
set_query_var('show_count', false);
set_query_var('show_author', false);
set_query_var('is_sponsored', true);

get_template_part('parts/module', 'single-row');
