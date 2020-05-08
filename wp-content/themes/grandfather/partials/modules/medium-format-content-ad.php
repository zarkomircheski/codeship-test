<?php
$i = 1;
$count = count($module->data['posts']);
foreach ($module->data['posts'] as $m_post) :
    if ($i == $count) {
        ?>
    <div class="medium-format-content-item medium-format-content-ad">
    <div class="advertisement-text">ADVERTISEMENT</div>
        <?php Fatherly\Dfp\Helper::init()->id('box1')->printTag();
        echo '</div>';
    } else {
        ?>
      <div class="medium-format-content-item">
          <!-- link to Article -->
          <a href="<?php echo $m_post['permalink'] ?>" data-ev-loc="Feed" data-ev-name="Featured Image">
              <img src='<?php echo $m_post['featured_image'] ?>'>
          </a>
          <!-- link to category -->
          <a href="<?php echo $m_post['category']['url'] ?>"
             data-ev-loc="Feed" data-ev-name="Category Link" data-ev-val="<?php echo str_replace('"', "'", $m_post['category']['name']) ?>">
              <div class="medium-format-content-item-category category"><span
                          class="arrow"></span><?php echo $m_post['category']['name'] ?></div>
          </a>
          <!-- link to Article -->
          <a href="<?php echo $m_post['permalink'] ?>" data-ev-loc="Feed" data-ev-name="Headline">
              <div class="medium-format-content-item-title"><?php echo $m_post['title'] ?></div>
          </a>
        <?php if (array_key_exists('author', $m_post)) : ?>
            <a href="<?php echo $m_post['author']['url'] ?>" data-ev-loc="Feed" data-ev-name="Byline">
                <div class="medium-format-content-item-author author">By
                    <span><?php echo $m_post['author']['name'] ?></span></div>
            </a>
        <?php endif; ?>
      </div>
        <?php
        $i++;
    }
endforeach;

?>