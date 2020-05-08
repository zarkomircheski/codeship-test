<div class="lead-with-video-items">
    <?php foreach ($module->data['highlighted_posts'] as $m_post) : ?>
        <div class="medium-format-content-item">
            <!-- link to Article -->
            <a href="<?php echo $m_post['permalink'] ?>" data-ev-loc="Feed" data-ev-name="Featured Image">
                <img src='<?php echo $m_post['featured_image'] ?>'>
            </a>
            <!-- link to category -->
            <?php if (array_key_exists('franchise', $m_post)) : ?>
                <a href="<?php echo get_tag_link($m_post['franchise']); ?>" data-ev-loc="Feed" data-ev-name="Category Link"
                   data-ev-val="<?php echo str_replace('"', "'", $m_post['franchise']->name); ?>"
                   class="franchise__tag franchise__tag__module franchise__tag__module-border-top">
                    <span class="franchise__tag-name"><?php echo $m_post['franchise']->name; ?></span>
                </a>
            <?php else : ?>
                <a href="<?php echo $m_post['category']['url'] ?>" data-ev-loc="Feed" data-ev-name="Category Link"
                   data-ev-val="<?php echo str_replace('"', "'", $m_post['category']['name']) ?>">
                    <div class="medium-format-content-item-category category"><span
                                class="arrow"></span><?php echo $m_post['category']['name'] ?></div>
                </a>
            <?php endif; ?>
            <!-- link to Article -->
            <a href="<?php echo $m_post['permalink'] ?>" data-ev-loc="Feed" data-ev-name="Headline">
                <div class="medium-format-content-item-title"><?php echo $m_post['title']; ?>
                </div>
            </a>
        </div>
    <?php endforeach; ?>
    <div class="lead-with-video-video">
        <script id="3a1915230e4c4c8aaf54186da6267749">
          cnx.cmd.push(function() {
            cnx({
              playerId: '769f6a51-98b3-4fa3-95f9-f1ea0468c02d'
            }).render('3a1915230e4c4c8aaf54186da6267749');
          });
        </script>
        <div class="homepage-jwplayer-video-category category playlist">
            <span class="arrow"></span>Must Watch Video
        </div>
    </div>
</div>