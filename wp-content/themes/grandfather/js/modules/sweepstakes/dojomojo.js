import utilities from '../utilities';

const dojomojo = {
  init() {
    if ($('body').hasClass('single-sweepstakes')) {
      this.generateIframe();
    }
  },
  generateIframe() {
    const embed_id_2 = $('.sweepstakes__dojomojo-embed').data('embed-2');
    const iframe = document.createElement('iframe');
    let shareId = utilities.get_parameter_by_name('share_id');
    shareId = shareId ? `&share_id=${shareId}` : '';
    const promoId = utilities.get_parameter_by_name('promo_id');
    if (promoId) { iframe.src = `//landing.dojomojo.ninja/landing/campaign/${embed_id_2}${window.location.search}${shareId}`; } else { iframe.src = `//www.dojomojo.ninja/landing/campaign/${embed_id_2}`; }
    $('<iframe>', {
      src: iframe.src,
      id: 'dojomojo',
      width: '100%',
      height: '100%',
      frameborder: '0',
    }).appendTo('.sweepstakes__dojomojo-embed');
  },

};
export default dojomojo;
