(function ($) {
  /**
   * Simple hack to show a character count in WP-Admin
   * for the Yoast Open Graph Title input field.
   * At the moment the allowed length is hard coded to 80,
   * but is easy to edit.
   * TODO: Create a WP settings page where you can edit the limit.
   */
  $(document).ready(($) => {
    const inputOgTitle = $('#yoast_wpseo_opengraph-title');

    if (inputOgTitle.length) {
      const titleValue = inputOgTitle.val();
      let charCount = titleValue.length;
      const allowedLength = 58; // <- edit value
      const counterWrapper = $('<div />').addClass('wp-ui-text-primary');
      const counter = $('<strong />').text(charCount);

      counterWrapper.text(' characters').prepend(counter);

      inputOgTitle.after(counterWrapper).on('keyup', () => {
        charCount = inputOgTitle.val().length;
        counterColor = (charCount > allowedLength) ? '#d54e21' : '#32373c';

        counter.text(charCount).css('color', counterColor);
      });
    }
  });
}(jQuery));
