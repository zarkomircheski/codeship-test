<?php  $isList = \Fatherly\Listicle\Helper::isListicle($post);
$emailFooterCopy = '%3Futm_source=email%26utm_medium=onsiteshare'
    . '%0A%0A%0ASign up for our newsletter to receive our best stories in your inbox every day:%0A%0Ahttps://www.fatherly.com/newsletter-sign-up/email-newsletter-blue/'
    . '%0A%0AOr, subscribe to our YouTube channel:%0A%0Ahttps://www.youtube.com/channel/UC-PfbmXWqUYO_UCKP08LKDA?sub_confirmation=1';
$share_location = get_query_var('share_icon_loc'); ?>
<ul class="social-share <?php echo $list_item['item_number'] ?: 'list-item-social-small';?>">
    <?php if ($list_item['item_number']) { ?>
        <li class="list-item-number">
            <?php echo $list_item['item_number']; ?>
        </li>
    <?php } ?>
    <li class="social-share-email">
        <a target="_blank"
              href="mailto:?subject=<?php if (!in_array($share_location, array('Top', 'Bottom'), true) && $isList) :
                                            echo rawurlencode($list_item['headline'].' Made Fatherly\'s '.get_the_title().' List').
                                            '&body='.rawurlencode($list_item['headline'].' made Fatherly\'s '.get_the_title()).'list.%0A%0A'.rawurlencode($list_item['item_slug']) . $emailFooterCopy;?>"
                                            data-ev-loc="Body" data-ev-name="List Item Share - Email" <?php echo htmlentities($list_item['item_number'] ? 'data-ev-val="'.$list_item['item_number'].'"' : ''); ?>
                                    <?php elseif (in_array($share_location, array('Top', 'Bottom'), true)) :
                                                echo rawurlencode(get_the_title()).'| Fatherly&body=I thought you might like this article from Fatherly...%0A%0A'.rawurlencode(get_the_title()).'%0A%0A'.rawurlencode(get_the_permalink()) . $emailFooterCopy?>"
                                            data-ev-loc="Body" data-ev-name="Email Share - <?php echo $share_location; ?>"
                                    <?php endif; ?>><span class="fas icon-mail"></span> EMAIL</a>
    </li>
    <li class="social-share-facebook">
        <a target="_blank" target="_blank" data-platform="Facebook"
            <?php if (!in_array($share_location, array('Top', 'Bottom'), true) && $isList) : ?>
                data-ev-loc="Body" data-ev-name="List Item Share - Facebook" <?php echo $list_item['item_number'] ? 'data-ev-val="'.$list_item['item_number'].'"' : ''; ?>
                href="https://www.facebook.com/sharer/sharer.php?u=<?php echo $list_item['item_slug']. "?utm_source=facebook&utm_medium=onsiteshare"; ?>"
            <?php elseif (in_array($share_location, array('Top', 'Bottom'), true)) : ?>
                data-ev-loc="Body" data-ev-name="Facebook Share - <?php echo $share_location; ?>"
                href="https://www.facebook.com/sharer/sharer.php?u=<?php echo $share_url. "?utm_source=facebook&utm_medium=onsiteshare"; ?>"
            <?php endif; ?>
        ><span class="fas icon-facebook-squared"></span> SHARE</a>
    </li>
</ul>
