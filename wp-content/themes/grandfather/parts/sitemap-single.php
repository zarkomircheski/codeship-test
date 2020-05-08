<h1 class="page-template__title sitemap__title">Sitemap for <?php the_time("F, Y"); ?>
    <span> Total of <?php echo count($posts); ?> posts</span></h1>
<?php
if (have_posts()) :
    echo "<ul class='sitemap sitemap__listing'>";
    while (have_posts()) :
        the_post();
        printf(
            "<li class='sitemap sitemap__listing-item'><a href='%s'>%s</a>  <strong>%s</strong></li>",
            get_the_permalink(),
            get_the_title(),
            get_the_time("F j, Y")
        );
        if (\Fatherly\Listicle\Helper::isListicle($post)) {
            echo "<ul class='sitemap sitemap__listing'>";
            $listItems = \Fatherly\Listicle\Helper::getPublishedListItems($post);
            foreach ($listItems as $listItem) :
                printf(
                    "<li class='sitemap sitemap__listing-item'><a href='%s'>%s</a></li>",
                    $listItem['item_url'],
                    $listItem['headline']
                );
            endforeach;
            echo "</ul>";
        }
        echo "<hr>";
    endwhile;
    echo "</ul>";
endif;
