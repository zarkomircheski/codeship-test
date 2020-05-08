const moreFromModule = {
  init() {
    if ($('.more-from-items').length > 0 && typeof $('.more-from-items').data('module-param') !== 'undefined') {
      const queryArgs = $('.more-from-items').data('module-param');
      fth_page_data.page_type = 'custom';
      fth_page_data.tag__in = queryArgs.tag__in;
      $('.more-from-items').removeAttr('data-module-param');
    }
  },
};
export default moreFromModule;
