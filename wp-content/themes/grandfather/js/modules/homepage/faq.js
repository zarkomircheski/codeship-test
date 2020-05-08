
$(document).on('click touchend', '.faq-content-section-text', (e) => {
  let $checkbox = $(e.currentTarget).parent().find('.faq-toggle:not(:checked)');

  // Open FAQ if it is closed
  if ($checkbox.length > 0) {
    e.preventDefault();
    $checkbox.prop('checked', true);
  }
});
