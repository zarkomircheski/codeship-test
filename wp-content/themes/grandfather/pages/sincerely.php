<?php
/*
Template Name: Sincerely Event
*/

get_header();
$url = rawurlencode(get_page_link());
?>
    <div class="playroom sincerly">
        <div class="playroom-image">
            <picture>
                <source media="(min-width: 1000px)" srcset="https://images.fatherly.com/wp-content/uploads/2019/05/sincerely-banner-final.jpg?q=65&w=3000">
                <source media="(min-width: 601px)" srcset="https://images.fatherly.com/wp-content/uploads/2019/05/sincerely-banner-final.jpg?q=65&w=2000">
                <img src="https://images.fatherly.com/wp-content/uploads/2019/06/sincerely-mobile.jpg?q=65&w=1200">
            </picture>
        </div>
        <div class="playroom-about">
            <h2 class="playroom-about-title title">WHAT IS IT?</h2>
            <p class="playroom-about-body">
                In celebration of Father’s Day, Fatherly has partnered with Gillette to launch Sincerely, a gallery experience exploring how today’s men can help raise tomorrow’s husbands, fathers, and leaders in an era defined by a rethinking of masculine norms. Featuring open letters to young men penned by big thinkers and big doers, including rapper Common, therapist Dr. Michael Reichert, actor Luis Guzman, cook Edward Lee, and BASE jumper Matthias Girard, Sincerely, asks parents and non-parents alike to reconsider how they prepare boys for adulthood.
            </p>
        </div>
        <div class="playroom-location">
            <div class="playroom-location-inner">
                <div class="playroom-location-inner-map">
                    <iframe width="600" height="450" frameborder="0" style="border:0" src="https://www.google.com/maps/embed/v1/place?q=401%20W%2014th%20Street%2C%20New%20York%2C%20NY%2C%2010014%20&key=AIzaSyCstlGrx9ARAIK60cuOLJk1T91GfYCBdrM" allowfullscreen></iframe>
                </div>
                <div class="playroom-location-inner-info">
                <p class="playroom-location-inner-info-loc">401 W 14th Street, New York, NY, 10014 </p>
                    <div class="playroom-location-inner-info-pipe"></div>
                    <ul class="playroom-location-inner-info-time">
                        <li>Gallery Hours:</li>
                        <li>Thursday, June 13th: 12 PM - 8 PM</li>
                        <li>Friday, June 14th: 12 PM - 8 PM</li>
                        <li>Saturday, June 15th: 11 AM - 7 PM</li>
                        <li>Sunday, June 16th: 11 AM - 7 PM</li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="playroom-sponsors title">
            <h2 class="playroom-sponsors-title">SPONSORED BY</h2>
            <div class="playroom-sponsors-logos">
                <div class="playroom-sponsors-logo">
                    <img src="https://images.fatherly.com/wp-content/uploads/2019/05/gillettemast-fullcolor.jpg?w=400">
                </div>
        </div>
        <div class="playroom-thanks title">
            <h2 class="playroom-thanks-title">SPECIAL THANKS TO</h2>
            <div class="playroom-thanks-logos">
                <div class="playroom-thanks-logo">
                    <img src="https://images.fatherly.com/wp-content/uploads/2019/05/high-res-psny-logo-transparent-black.png?w=100">
                </div>
            </div>
        </div>
    </div>

<?php get_footer(); ?>