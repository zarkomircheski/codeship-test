<!-- Start Boomtrain Analytics -->

<?php if (is_single()) {
    ?>
    <meta property="bt:id" content="<?php echo $wp_query->post->ID; ?>"/>
    <?php if (bt_meta_keywords() !== '') : ?>
        <meta property="bt:keywords"
              content="<?php echo bt_meta_keywords(); ?>">
    <?php endif; ?>
    <?php
} ?>
<!-- End Boomtrain Analytics -->

<!-- Google Analytics RANK Tracking events -->
<script>
    (function () {
        var referrer = document.referrer;
        if (referrer.match(/google\.com/gi) && referrer.match(/cd/gi)) {
            var r = referrer.match(/cd=(.*?)&/);
            var rank = parseInt(r[1]);
            var kw = referrer.match(/q=(.*?)&/);
            var word = (kw[1].length > 0) ? decodeURI(kw[1]) : "(not provided)";
            var p = document.location.pathname;
            dataLayer.push({
                'rtracker_word': word,
                'rtracker_p': p,
                'rtracker_rank': rank
            });
            dataLayer.push({'event': 'fireRankTrack'});
        }
    })()
</script>




