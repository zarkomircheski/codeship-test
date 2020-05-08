<?php

function intercept_and_add_posts_to_sitemap($query)
{
    global $wpdb;
    if (strpos($wpdb->get_caller(), 'WPSEO_News_Sitemap->get_items') !== false) {
        $query = "
			 SELECT ID, post_content, post_name, post_author, post_parent, post_date_gmt, post_date, post_date_gmt, post_title, post_type
			 FROM {$wpdb->posts} AS a INNER JOIN {$wpdb->prefix}term_relationships AS b ON a.ID = b.object_id
			 WHERE post_status='publish'
			 AND (DATEDIFF(CURDATE(), post_date_gmt)<=2)
			 AND b.term_taxonomy_id = '46593'
			 AND post_type IN ('post')
			 ORDER BY post_date_gmt DESC
			 LIMIT 0, 1000
		 ";
    }
    return $query;
}

add_filter('query', 'intercept_and_add_posts_to_sitemap', 10, 1);
