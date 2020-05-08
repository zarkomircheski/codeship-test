jQuery(document).ready(function ($) {
  $('#show_add_form').on('click', function () {
    $('#add-new-form').slideToggle();
  });
  var FAPUSettings = {
    handleAdd: function ($link) {
      var self = this;
      data = {
        'action': 'fatherly_amazon_product_updater',
        'operation': 'add',
        'url': $link
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
    handleUpdate: function ($asin) {
      var self = this;
      data = {
        'action': 'fatherly_amazon_product_updater',
        'operation': 'update',
        'asin': $asin
      };
      $.post(ajaxurl, data, function (res) {
        res = JSON.parse(res);
        if (res.success == false) {
          self.showErrorMessage(res.message);
          $(`#${$asin}`).removeClass('row-processing');
        }
        if (res.success == true) {
          self.showSuccessMessage(res.message);
          $(`#${$asin}`).removeClass('row-processing');
        }
      });
    },
    handleDelete: function ($asin) {
      var self = this;
      data = {
        'action': 'fatherly_amazon_product_updater',
        'operation': 'delete',
        'asin': $asin
      };
      $.post(ajaxurl, data, function (res) {
        res = JSON.parse(res);
        if (res.success == false) {
          self.showErrorMessage(res.message);
          $(`#${$asin}`).removeClass('row-processing');
        }
        if (res.success == true) {
          self.showSuccessMessage(res.message);
          $(`#${$asin}`).fadeOut();
        }
      });
    },
    handleErrorResolve: function ($asin) {
      var self = this;
      data = {
        'action': 'fatherly_amazon_product_updater',
        'operation': 'resolve',
        'asin': $asin
      };
      $.post(ajaxurl, data, function (res) {
        res = JSON.parse(res);
        if (res.success == false) {
          self.showErrorMessage(res.message);
          $(`#${$asin}`).removeClass('row-processing');
        }
        if (res.success == true) {
          self.showSuccessMessage(res.message);
          $(`#${$asin}`).fadeOut();
        }
      });
    },
    handleFetchArticles: function ($asin) {
      var self = this;
      data = {
        'action': 'fatherly_amazon_product_updater',
        'operation': 'fetchArticles',
        'asin': $asin
      };
      $.post(ajaxurl, data, function (res) {
        res = JSON.parse(res);
        if (res.success == false) {
          self.showErrorMessage(res.message);
          $(`#${$asin} .articles-with-product`).html(`<strong>${res.message}</strong>`);
        }
        if (res.success == true) {
          $(`#${$asin} .articles-with-product`).html(`${res.markup}`);
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
  $('#add_new_product').on('click', function (e) {
    e.preventDefault();
    var $link = encodeURIComponent($('#product_url').val());
    $('#add_form_spinner').toggleClass('hidden');
    FAPUSettings.handleAdd($link);
  });

  $('.updateProduct').on('click', function (e) {
    e.preventDefault();
    var $row = $(this).parent().parent();
    var $asin = $row.attr('data-asin');
    $row.addClass('row-processing');
    FAPUSettings.handleUpdate($asin);
  });
  $('.deleteProduct').on('click', function (e) {
    e.preventDefault();
    if (window.confirm('Are you sure you want to delete this product?')) {
      var $row = $(this).parent().parent();
      var $asin = $row.attr('data-asin');
      $row.addClass('row-processing');
      FAPUSettings.handleDelete($asin);
    }
  });
  $('.markResolved').on('click', function (e) {
    e.preventDefault();
    var $row = $(this).parent().parent();
    var $asin = $row.attr('data-asin');
    $row.addClass('row-processing');
    FAPUSettings.handleErrorResolve($asin);
  });
  $('.fetchArticles').on('click', function (e) {
    e.preventDefault();
    var $row = $(this).parent().parent();
    var $asin = $row.attr('data-asin');
    $(`#${$asin} .articles-with-product`).html('<div class="fatherly-spinner spinner"></div>');
    FAPUSettings.handleFetchArticles($asin);
  });
});
