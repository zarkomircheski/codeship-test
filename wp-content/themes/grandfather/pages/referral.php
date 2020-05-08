<?php
/*
Template Name: Referral
*/

get_header();
?>
<div class="referral">
    <div class="referral-dek">
        <h1 class="referral-dek-title">The Fatherly Referral Program</h1>
        <p class="referral-dek-cta">Click the link below to copy it to your clipboard!</p>
    </div>
    <div class="referral-image">
        <img src="/wp-content/themes/grandfather/images/referral_promo.gif">
    </div>
    <div class="referral-copy">
        <button class="referral-copy-button fas icon-docs" data-clipboard-text="https://www.fatherly.com/newsletter/?referrerID=<?php echo $_GET['recipientID']; ?>">
            <span class="referral-copy-button-text">Copy Your Link</span>
        </button>
        <div class="referral-copy-success">Copied!</div>
    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/clipboard.js/1.5.12/clipboard.min.js"></script>
<script>
  var clip = new Clipboard('.referral-copy-button');

  clip.on("success", function() {
    let $success = $('.referral-copy-success');
    $success.addClass('show');
    setTimeout(function(){$success.removeClass('show').delay( 1000 )}, 2000);
  });

</script>

<?php get_footer(); ?>