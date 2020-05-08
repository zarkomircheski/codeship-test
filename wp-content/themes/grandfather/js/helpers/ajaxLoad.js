(function ($) {
  /**
   * Trigger ajax call
   * @param {string} url - location of file to use for the ajax call.
   * @param {object} params - object of parameters needed for the ajax call to execute
   * @param {function} callback - a function that manipulates the response of the ajax request
   */
  function triggerAjaxLoad(url, method, params, callback) {
    $.ajax({
      method,
      url,
      data: params,
    }).done((res) => {
      callback(res);
    }).fail((xhr, textStatus, e) => {

    });
  }

  module.exports = triggerAjaxLoad;
}(jQuery, this));

