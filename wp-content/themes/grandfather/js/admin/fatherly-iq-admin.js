(function ($) {
  $(document).ready(($) => {
    $(document).on('click touchend', '.fatherly-iq-csv, .fatherly-iq-display', (e) => {
      e.preventDefault();

      const params = {
        action: 'fth_ajax_get_answers',
        question: $(e.currentTarget).closest('.fatherly-iq-question').attr('data-question'),
        type: 'display',
      };

      if ($(e.currentTarget).hasClass('fatherly-iq-csv')) {
        params.type = 'csv';
      }

      $.ajax({
        method: 'GET',
        url: '/wp-admin/admin-ajax.php',
        data: params,
      }).done((res) => {
        if (res) {
          if (params.type === 'display') {
            $('.fatherly-iq-questions').remove();
            $('.fatherly-iq').append(res);
          } else {
            res = JSON.parse(res);
            let csvContent = `data:text/csv;charset=utf-8,${res.map(e => e.join(',')).join('\n')}`;

            let encodedUri = encodeURI(csvContent);
            window.location.href = encodedUri;
          }
        }
      }).fail((xhr, textStatus, e) => {

      });
    });
  });
}(jQuery));
