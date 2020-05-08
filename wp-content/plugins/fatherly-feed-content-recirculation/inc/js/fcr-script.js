jQuery(document).ready(function ($) {
  $('#show_add_form').on('click', function () {
    $('#add-new-form').slideToggle();
  });
  var FCRSettings = {
    handleAdd: function ($post_id) {
      var self = this;
      data = {
        'action': 'fatherly_feed_content_recirculation',
        'operation': 'add',
        'post_id': $post_id
      };
      $.post(ajaxurl, data, function (res) {
        res = JSON.parse(res);
        if (res.success == false) {
          self.showErrorMessage(res.message);
        }
        if (res.success == true) {
          self.showSuccessMessage(res.message);
        }
        $('#add_form_spinner').toggleClass('hidden');
      });
    },
    handleDeletion: function ($post_id) {
      var self = this;
      data = {
        'action': 'fatherly_feed_content_recirculation',
        'operation': 'delete',
        'post_id': $post_id
      };
      $.post(ajaxurl, data, function (res) {
        res = JSON.parse(res);
        if (res.success == false) {
          self.showErrorMessage(res.message);
          $(`#${$post_id}`).removeClass('row-processing');
        }
        if (res.success == true) {
          self.showSuccessMessage(res.message);
          $(`#${$post_id}`).fadeOut('slow', function () {
            $(`#${$post_id}`).remove();
          });
        }
      });
    },
    handleProcessingUpdate: function ($post_id) {
      var self = this;
      data = {
        'action': 'fatherly_feed_content_recirculation',
        'operation': 'reset',
        'post_id': $post_id
      };
      $.post(ajaxurl, data, function (res) {
        console.log(res);
        res = JSON.parse(res);
        if (res.success == false) {
          self.showErrorMessage(res.message);
          $(`#${$post_id}`).removeClass('row-processing');
        }
        if (res.success == true) {
          self.showSuccessMessage(res.message);
          if (res.object.processed == 0) {
            var $processedStatus = 'Has not been recirculated';
          } else {
            var $processedStatus = 'Has been recirculated';
          }
          $(`#${$post_id} .article-processed-status`).text($processedStatus);
          $(`#${$post_id}`).removeClass('row-processing');
        }
      });
    },

    showErrorMessage: function ($message) {
      $(`<div id=\'message\' class=\'notice notice-error\'><p>${$message}</p></div>`).insertAfter('.wp-header-end');
    },
    showSuccessMessage: function ($message) {
      $(`<div id=\'message\' class=\'notice notice-success\'><p>${$message}</p></div>`).insertAfter('.wp-header-end');
    }
  };
  $('#add_new_post').on('click', function (e) {
    e.preventDefault();
    var $post_id = parseInt($('#post_id').val());
    if (typeof $post_id == 'number') {
      $('#add_form_spinner').toggleClass('hidden');
      FCRSettings.handleAdd($post_id);
    } else {
      document.forms['add_post_form'].reportValidity();
    }
  });
  $('.deleteFromRecirculation').on('click', function (e) {
    e.preventDefault();
    var $row = $(this).parent().parent();
    var $post_id = $row.attr('id');
    $row.addClass('row-processing');
    FCRSettings.handleDeletion($post_id);
  });
  $('.resetProcessedStatus').on('click', function (e) {
    e.preventDefault();
    var $row = $(this).parent().parent();
    var $post_id = $row.attr('id');
    $row.addClass('row-processing');
    FCRSettings.handleProcessingUpdate($post_id);
  });
});
