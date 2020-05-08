<?php get_header(); ?>
    <div class="page-content container-sm clearfix">
        <main role="main">
            <section class="sitemap archive-latest">
                <?php
                if (is_archive() && is_date()) {
                    get_template_part('parts/sitemap', 'single');
                } else {
                    get_template_part('parts/category', 'posts');
                    get_template_part('pagination');
                }
                ?>
            </section>
        </main>
    </div>
<?php get_footer(); ?>
