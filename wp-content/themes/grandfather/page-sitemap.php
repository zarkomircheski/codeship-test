<?php
    /*
    Template Name: HTML Sitemap Page
    */
?>
<?php
    $dateRanges = Fatherly\Page\Sitemap::init()->getMonthsRange();

?>
<?php get_header(); ?>
<div class="page-template-default page-content container-xsm clearfix">
    <main role="main" class="default-page">
        <!-- section -->
        <section class="sitemap page-template">

            <h1 class="page-template__title sitemap__title"><?php the_title(); ?></h1>

            <?php foreach ($dateRanges as $year => $months) : ?>
                <h4><?php echo $year ?></h4>
                <ul class='sitemap sitemap__listing'>
                    <?php foreach ($months as $month) : ?>
                        <?php $monthtstamp = strtotime($month); ?>
                        <?php printf(
                            "<li class='sitemap sitemap__listing-item'><a href='%s'>%s</a></li>",
                            get_month_link(date('Y', $monthtstamp), date('m', $monthtstamp)),
                            substr($month, 5)
                        ) ?>
                    <?php endforeach; ?>
                </ul>
            <?php endforeach; ?>

        </section>
        <!-- /section -->
    </main>
</div>

<?php // get_sidebar();?>

<?php get_footer(); ?>

