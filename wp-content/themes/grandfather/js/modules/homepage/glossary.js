(function ($) {
  $(document).ready(() => {
    let touchmoved = false;

    $('body').on('touchend click', '.glossary-content-item', (e) => {
      e.preventDefault();

      if (touchmoved === false) {
        let $target = $(e.currentTarget);
        if ($target.hasClass('show')) {
          $target.removeClass('show');
        } else {
          $target.addClass('show');
        }
      }
    }).on('touchmove', (e) => {
      touchmoved = true;
    }).on('touchstart', (e) => {
      touchmoved = false;
    });
  });
}(jQuery, this));
