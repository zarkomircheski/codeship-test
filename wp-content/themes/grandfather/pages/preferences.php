<?php
/*
Template Name: Preferences
*/

get_header();
$email = str_replace(" ", "+", $_GET['email']);
$id = $_GET['recipid'];
?>
<div class="preferences-page">
    <?php if (filter_var($email, FILTER_VALIDATE_EMAIL) && is_numeric($id)) { ?>
        <iframe class="submitted"
                src="https://www.mail.fatherly.com/fatherlypref/fatherlypref.html?recipid=<?php echo $id; ?>&email=<?php echo $email; ?>"
                width="100%" height="100%">
        </iframe>
    <?php } else { ?>
        <p>Sorry your request could not be completed at this time</p>
    <?php } ?>
</div>
<?php
get_footer();
