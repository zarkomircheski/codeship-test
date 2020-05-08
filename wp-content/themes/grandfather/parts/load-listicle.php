<?php
$post = get_post($_GET['id']);

/*
 * Get the next 11 items in the array even though we only want to load ten
 * This way we can tell if there is more content to load
 * once that is confirmed remove the last item since it should not display on the front end
 */
$list_items = Fatherly\Listicle\Helper::getPublishedListItemsPaged($post, $_GET['amount'], $_GET['index'], $_GET['direction']);
$moreContent = $list_items[count($list_items)-1] ? $list_items[count($list_items)-1]['item_url'] : false;
array_pop($list_items);

// Get html to output ot the page
ob_start();
set_query_var('list_items', $list_items);
get_template_part('parts/article', 'listicle-items');
wp_reset_postdata();
$data = ob_get_clean();

wp_send_json_success(array('html' => $data, 'more' => $moreContent));
